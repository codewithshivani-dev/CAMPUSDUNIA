<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionProcessConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'institute_id',
        'branch_id',
        'admissionprocess_confun_id',
        'academic_year',
        'department_category_id',
        'department_id',
        'product_id',
        
        // Admission Form
        'admission_form_enabled',
        'admission_form_mode',
        'admission_form_fee_amount',
        'entrance_test_fee_amount',
        'admission_form_max',
        'admission_form_start_date',
        'admission_form_end_date',
        
        // Entrance Tests
        'entrance_tests_enabled',
        'entrance_tests_mode',
        'entrance_tests',
        
        // Counselling
        'counselling_enabled',
        'counselling_mode',
        'counselling_session_duration',
        'counselling_max_candidates',
        'counselling_start_date',
        'counselling_end_date',
        'counselling_working_start',
        'counselling_working_end',
        'counselling_break_start',
        'counselling_break_end',
        'counselling_days',
        'counselling_time_slots',
        
        // Onboarding
        'onboarding_enabled',
        'onboarding_admission_fee',
        'onboarding_security_deposit',
        'onboarding_other_charges',
        'onboarding_start_date',
        'onboarding_classes_start_date',
        'onboarding_documents_list',
        'is_active'
    ];

    protected $casts = [
        'product_id' => 'array',
        'counselling_days' => 'array',
        'admission_form_enabled' => 'boolean',
        'entrance_tests_enabled' => 'boolean',
        'counselling_enabled' => 'boolean',
        'onboarding_enabled' => 'boolean',
        'is_active' => 'boolean',
        'onboarding_admission_fee' => 'decimal:2',
        'onboarding_security_deposit' => 'decimal:2',
        'onboarding_other_charges' => 'decimal:2',
        'admission_form_start_date' => 'date',
        'admission_form_end_date' => 'date',
        'counselling_start_date' => 'date',
        'counselling_end_date' => 'date',
        'onboarding_start_date' => 'date',
        'onboarding_classes_start_date' => 'date',
        'counselling_working_start' => 'datetime:H:i',
        'counselling_working_end' => 'datetime:H:i',
        'counselling_break_start' => 'datetime:H:i',
        'counselling_break_end' => 'datetime:H:i',
        'entrance_tests' => 'array',
        'counselling_days' => 'array',
        'counselling_time_slots' => 'array'
    ];

    /**
     * Get the counselling time slots for the config.
     */
    public function counsellingTimeSlots()
    {
        return $this->hasMany(CounsellingTimeSlot::class);
    }

    /**
     * Get the entrance test slots for the config.
     */
    public function entranceTestSlots()
    {
        return $this->hasMany(EntranceTestSlot::class);
    }

    /**
     * Get counselling time slots for a specific day.
     */
    public function getCounsellingSlotsForDay($day)
    {
        return $this->counsellingTimeSlots()
                    ->where('day_of_week', $day)
                    ->where('status', 'active')
                    ->orderBy('start_time')
                    ->get();
    }

    /**
     * Generate counselling slots based on configuration.
     */
    public function generateCounsellingSlots()
    {
        return CounsellingTimeSlot::generateSlotsFromSchedule($this->id, $this);
    }

    /**
     * Generate entrance test slots from configuration.
     */
    public function generateEntranceTestSlots()
    {
        $tests = $this->entrance_tests ?? [];
        $generatedSlots = [];
        
        foreach ($tests as $test) {
            $slots = EntranceTestSlot::generateFromTestConfig($this->id, $test);
            $generatedSlots = array_merge($generatedSlots, $slots);
        }
        
        return $generatedSlots;
    }
    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }
}