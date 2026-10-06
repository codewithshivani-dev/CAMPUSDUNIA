<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\AdmissionProcessConfig;
use App\Models\StudentAdmissionProcess;
use App\Models\EntranceTestSlot;
use App\Models\EntranceTest;
use App\Models\CounsellingTimeSlot;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class AdmissionSlotAssignmentService
{
    public function assignSlots($user)
    {
        return DB::transaction(function () use ($user) {

            $leads = Lead::query()
                ->join('admission_registrations as ar', 'ar.reference_id', '=', 'leads.reference_id')
                ->join('admission_process_configs as apc', function ($join) {
                    $join->on('apc.institute_id', '=', 'ar.institute_id')
                        ->whereRaw("
                            JSON_CONTAINS(
                                apc.product_id,
                                JSON_OBJECT('id', ar.applying_for_grade)
                            )
                        ");
                })
                ->where('leads.institute_id', $user->institute_id)
                ->select(
                    'leads.*',
                    'apc.id as apc_id',
                    'apc.entrance_tests_enabled as apc_entrance_tests_enabled',
                    'apc.counselling_enabled as apc_counselling_enabled',
                    'apc.counselling_mode as apc_counselling_mode'
                )
                ->orderBy('leads.created_at', 'asc') // FIFO
                ->get();
            log::info($leads);

            foreach ($leads as $lead) {

                if (!$lead->apc_id) {
                    continue;
                }

                $config = AdmissionProcessConfig::with([
                    'entranceTestSlots',
                    'counsellingTimeSlots'
                ])->find($lead->apc_id);

                if (!$config) {
                    continue;
                }

                $process = StudentAdmissionProcess::firstOrCreate(
                    ['lead_id' => $lead->id],
                    [
                        'reference_id' => $lead->reference_id ?? null,
                        'institute_id' => $lead->institute_id,
                        'branch_id' => $lead->branch_id,
                        'admission_config_id' => $config->id,
                        'registration_date' => now()
                    ]
                );
                $this->assignEntranceSlot($lead, $config, $process);
                $this->assignCounsellingSlot($lead, $config, $process);
            }

            return "Admission Slot Assignment Completed";
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ENTRANCE SLOT ASSIGNMENT (SAFE + LOCKED)
    |--------------------------------------------------------------------------
    */
    private function assignEntranceSlot($lead, $config, $process)
    {
        if ($lead->apc_entrance_tests_enabled != 1 || $process->entrance_test_slot_id) {
            return;
        }

        foreach ($config->entranceTestSlots as $slotData) {

            $slot = EntranceTestSlot::where('id', $slotData->id)
                ->where('test_id', $slotData->test_id)
                ->whereColumn('booked_count', '<', 'capacity')
                ->lockForUpdate()
                ->first();

            \Log::info($slot);

            if ($slot) {

                // Duplicate check
                $alreadyExists = EntranceTest::where('reference_id', $lead->reference_id)
                    ->where('test_id', $slotData->test_id)
                    ->exists();

                if ($alreadyExists) {
                    continue;
                }

                EntranceTest::create([
                    'reference_id' => $lead->reference_id ?? null,
                    'institute_id' => $lead->institute_id,
                    'branch_id' => $lead->branch_id,
                    'admission_config_id' => $config->id,

                    // correct test id
                    'test_id' => $slotData->test_id,

                    'entrance_test_slot_id' => $slot->id,
                    'entrance_test_date' => $slot->test_date ?? null,
                    'entrance_test_time' => $slot->start_time ?? null,
                    'test_name' => $slot->test_name ?? 'General Test',
                    'test_marks' => $slot->test_marks ?? null,
                    'test_passing_marks' => $slot->test_passing_marks_percentage ?? null,
                    'test_status' => 'pending'
                ]);

                $slot->increment('booked_count');
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | COUNSELLING SLOT ASSIGNMENT (SAFE + LOCKED)
    |--------------------------------------------------------------------------
    */
    private function assignCounsellingSlot($lead, $config, $process)
    {
        if ($lead->apc_counselling_enabled != 1 || $process->counselling_slot_id) {
            return;
        }

        foreach ($config->counsellingTimeSlots as $slotData) {

            $slot = CounsellingTimeSlot::where('id', $slotData->id)
                ->whereColumn('booked_count', '<', 'capacity')
                ->lockForUpdate()
                ->first();

            if ($slot) {

                $process->update([
                    'counselling_slot_id' => $slot->id,
                    'counselling_date' => $slot->slot_date ?? null,
                    'counselling_time' => $slot->start_time ?? null
                ]);

                $slot->increment('booked_count');

                break;
            }
        }
    }
}
