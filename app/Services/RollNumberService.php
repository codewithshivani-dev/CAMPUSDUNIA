<?php
// app/Services/RollNumberService.php

namespace App\Services;

use App\Models\StudentRollNumber;
use App\Models\StudentAcademicTransportDetails;
use App\Models\StudentParentDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RollNumberService
{
    /**
     * Generate and assign roll number for a student
     */
    public function assignRollNumber(string $studentHashId, ?string $customRollNumber = null): ?StudentRollNumber
    {
        // try {
            DB::beginTransaction();

            $student = StudentParentDetails::where('student_hash_id', $studentHashId)->first();
            if (!$student) {
                Log::warning('Student not found for roll number assignment', ['student_hash_id' => $studentHashId]);
                DB::rollBack();
                return null;
            }

            $academic = StudentAcademicTransportDetails::where('student_hash_id', $studentHashId)->first();
            if (!$academic) {
                Log::warning('Academic details not found for roll number assignment', ['student_hash_id' => $studentHashId]);
                DB::rollBack();
                return null;
            }

            $trimmedRollNumber = trim((string)($customRollNumber ?? ''));
            $existing = StudentRollNumber::where('student_hash_id', $studentHashId)->first();

            if ($existing && $existing->status === 'active') {
                if ($trimmedRollNumber !== '' && $existing->roll_number !== $trimmedRollNumber) {
                    $duplicate = StudentRollNumber::where('roll_number', $trimmedRollNumber)
                        ->where('student_hash_id', '!=', $studentHashId)
                        ->first();

                    if ($duplicate) {
                        throw new \InvalidArgumentException('This roll number already exists for another student.');
                    }

                    $existing->update([
                        'roll_number' => $trimmedRollNumber,
                        'roll_number_sequence' => $this->getNextSequenceNumber(
                            $student->institute_id,
                            $academic->batch_id,
                            $academic->academic_year_id,
                            $academic->section_id
                        ),
                    ]);
                    DB::commit();
                    return $existing->fresh();
                }

                DB::rollBack();
                return $existing;
            }

            if ($existing && $existing->status === 'inactive') {
                $existing->delete();
            }

            $sequence = $this->getNextSequenceNumber(
                $student->institute_id,
                $academic->batch_id,
                $academic->academic_year_id,
                $academic->section_id
            );

            $rollNumber = $trimmedRollNumber !== '' ? $trimmedRollNumber : $this->generateRollNumber(
                $student,
                $academic,
                $sequence
            );

            $duplicate = StudentRollNumber::where('roll_number', $rollNumber)
                ->where('student_hash_id', '!=', $studentHashId)
                ->first();

            if ($duplicate) {
                throw new \InvalidArgumentException('This roll number already exists for another student.');
            }

            $rollNumberRecord = StudentRollNumber::create([
                'student_hash_id' => $studentHashId,
                'registration_number' => $student->registration_number,
                'institute_id' => $student->institute_id,
                'branch_id' => $student->branch_id,
                'department_id' => $academic->department_id,
                'department_category_id' => $academic->department_category_id,
                'course_subtype_id' => $academic->course_subtype_id,
                'batch_id' => $academic->batch_id,
                'academic_year_id' => $academic->academic_year_id,
                'section_id' => $academic->section_id,
                'roll_number' => $rollNumber,
                'roll_number_sequence' => $sequence,
                'status' => 'active',
            ]);

            DB::commit();

            Log::info('Roll number assigned successfully', [
                'student_hash_id' => $studentHashId,
                'roll_number' => $rollNumber,
                'sequence' => $sequence,
            ]);

            return $rollNumberRecord;

        // } catch (\InvalidArgumentException $e) {
        //     DB::rollBack();
        //     Log::warning('Invalid roll number assignment: ' . $e->getMessage(), ['student_hash_id' => $studentHashId]);
        //     throw $e;
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     Log::error('Error assigning roll number: ' . $e->getMessage(), [
        //         'student_hash_id' => $studentHashId,
        //         'trace' => $e->getTraceAsString(),
        //     ]);
        //     return null;
        // }
    }

    /**
     * Get the next sequence number for a section
     */
    private function getNextSequenceNumber($instituteId, $batchId, $academicYearId, $sectionId): int
    {
        $maxSequence = StudentRollNumber::where('institute_id', $instituteId)
            ->where('batch_id', $batchId)
            ->where('academic_year_id', $academicYearId)
            ->where('section_id', $sectionId)
            ->where('status', 'active')
            ->max('roll_number_sequence');

        return ($maxSequence ?? 0) + 1;
    }

    /**
     * Generate roll number based on institute format
     */
    private function generateRollNumber($student, $academic, int $sequence): string
    {
        // Format: [COURSE_CODE]-[BATCH_YEAR]-[SECTION_CODE]-[SEQUENCE]
        // Example: BCA-2024-A-001
        
        $courseCode = $this->getCourseCode($academic->course_subtype ?? $academic->course_subtype_id);
        $batchYear = $this->extractBatchYear($academic->batch ?? $academic->batch_id);
        $sectionCode = $this->getSectionCode($academic->section_id);
        
        // Pad sequence to 3 digits
        $paddedSequence = str_pad($sequence, 3, '0', STR_PAD_LEFT);
        
        return strtoupper("{$courseCode}-{$batchYear}-{$sectionCode}-{$paddedSequence}");
    }

    /**
     * Extract year from batch
     */
    private function extractBatchYear($batch): string
    {
        if (is_numeric($batch)) {
            $batchRecord = DB::table('course_fee_structures')
                ->where('batch_id', $batch)
                ->first();
            
            if ($batchRecord && $batchRecord->batch) {
                $batch = $batchRecord->batch;
            } else {
                return date('Y');
            }
        }

        if (preg_match('/\b(20\d{2})\b/', $batch, $matches)) {
            return $matches[1];
        }

        return date('Y');
    }

    /**
     * Get course code from course name
     */
    private function getCourseCode($courseName): string
    {
        if (empty($courseName)) {
            return 'COURSE';
        }

        if (is_numeric($courseName)) {
            $course = DB::table('product_details')
                ->where('product_id', $courseName)
                ->first();
            
            if ($course && $course->sub_type) {
                $courseName = $course->sub_type;
            }
        }

        $words = explode(' ', $courseName);
        $code = '';
        foreach ($words as $word) {
            $code .= strtoupper(substr($word, 0, 1));
        }

        if (strlen($code) < 2) {
            $code = strtoupper(substr($courseName, 0, 4));
        }

        return $code;
    }

    /**
     * Get section code
     */
    private function getSectionCode($sectionId): string
    {
        if (empty($sectionId)) {
            return 'GEN';
        }

        $sectionData = DB::table('course_fee_structures')
            ->where('sections', 'LIKE', '%"id":"' . $sectionId . '"%')
            ->orWhere('sections', 'LIKE', '%"section_id":"' . $sectionId . '"%')
            ->value('sections');

        if ($sectionData) {
            $sections = json_decode($sectionData, true);
            if (is_array($sections)) {
                foreach ($sections as $section) {
                    $sectionIdFromJson = $section['id'] ?? $section['section_id'] ?? null;
                    if ($sectionIdFromJson == $sectionId) {
                        $name = $section['name'] ?? $section['section_name'] ?? '';
                        if (!empty($name)) {
                            return strtoupper(substr($name, 0, 2));
                        }
                    }
                }
            }
        }

        $cleanId = str_replace('section_', '', $sectionId);
        if (is_numeric($cleanId)) {
            $letter = chr(64 + (int)$cleanId);
            if ($letter >= 'A' && $letter <= 'Z') {
                return $letter;
            }
        }

        return strtoupper(substr($sectionId, 0, 2));
    }

    /**
     * Get roll number for a student
     */
    public function getRollNumber(string $studentHashId): ?string
    {
        $record = StudentRollNumber::where('student_hash_id', $studentHashId)
            ->where('status', 'active')
            ->first();
        
        return $record ? $record->roll_number : null;
    }

    /**
     * Get full roll number record for a student
     */
    public function getRollNumberRecord(string $studentHashId): ?StudentRollNumber
    {
        return StudentRollNumber::where('student_hash_id', $studentHashId)
            ->where('status', 'active')
            ->first();
    }

    /**
     * Get students by section with roll number status
     */
    public function getStudentsBySection($instituteId, $batchId, $academicYearId, $sectionId, $courseSubtypeId = null)
    {
        $query = StudentParentDetails::where('institute_id', $instituteId)
            ->whereHas('academicTransportDetails', function ($q) use ($batchId, $academicYearId, $sectionId, $courseSubtypeId) {
                $q->where('batch_id', $batchId)
                  ->where('academic_year_id', $academicYearId)
                  ->where('section_id', $sectionId);
                
                if ($courseSubtypeId) {
                    $q->where('course_subtype_id', $courseSubtypeId);
                }
            })
            ->with(['academicTransportDetails', 'rollNumber']);

        return $query->get();
    }

    /**
     * Deactivate roll number (when student exits)
     */
    public function deactivateRollNumber(string $studentHashId): bool
    {
        try {
            $record = StudentRollNumber::where('student_hash_id', $studentHashId)->first();
            if ($record) {
                $record->update(['status' => 'inactive']);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Error deactivating roll number: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get students without roll numbers in a section
     */
    public function getStudentsWithoutRollNumber($instituteId, $batchId, $academicYearId, $sectionId, $courseSubtypeId = null)
    {
        $query = StudentParentDetails::where('institute_id', $instituteId)
            ->whereHas('academicTransportDetails', function ($q) use ($batchId, $academicYearId, $sectionId, $courseSubtypeId) {
                $q->where('batch_id', $batchId)
                  ->where('academic_year_id', $academicYearId)
                  ->where('section_id', $sectionId);
                
                if ($courseSubtypeId) {
                    $q->where('course_subtype_id', $courseSubtypeId);
                }
            })
            ->whereDoesntHave('rollNumber', function ($q) {
                $q->where('status', 'active');
            })
            ->with('academicTransportDetails');

        return $query->get();
    }

    /**
     * Get all students with roll numbers in a section
     */
    public function getStudentsWithRollNumber($instituteId, $batchId, $academicYearId, $sectionId, $courseSubtypeId = null)
    {
        $query = StudentParentDetails::where('institute_id', $instituteId)
            ->whereHas('academicTransportDetails', function ($q) use ($batchId, $academicYearId, $sectionId, $courseSubtypeId) {
                $q->where('batch_id', $batchId)
                  ->where('academic_year_id', $academicYearId)
                  ->where('section_id', $sectionId);
                
                if ($courseSubtypeId) {
                    $q->where('course_subtype_id', $courseSubtypeId);
                }
            })
            ->whereHas('rollNumber', function ($q) {
                $q->where('status', 'active');
            })
            ->with(['academicTransportDetails', 'rollNumber']);

        return $query->get();
    }

    /**
     * Reassign roll numbers for a section
     */
    public function reassignRollNumbers($instituteId, $batchId, $academicYearId, $sectionId, $courseSubtypeId = null): array
    {
        try {
            DB::beginTransaction();

            $students = $this->getStudentsBySection($instituteId, $batchId, $academicYearId, $sectionId, $courseSubtypeId);
            $assigned = 0;
            $failed = 0;

            foreach ($students as $student) {
                $academic = $student->academicTransportDetails;
                if (!$academic) continue;

                // Get current sequence
                $sequence = $this->getNextSequenceNumber(
                    $instituteId,
                    $batchId,
                    $academicYearId,
                    $sectionId
                );

                // Generate new roll number
                $rollNumber = $this->generateRollNumber($student, $academic, $sequence);

                // Update or create
                $record = StudentRollNumber::where('student_hash_id', $student->student_hash_id)->first();
                
                if ($record) {
                    $record->update([
                        'roll_number' => $rollNumber,
                        'roll_number_sequence' => $sequence,
                        'status' => 'active',
                    ]);
                } else {
                    StudentRollNumber::create([
                        'student_hash_id' => $student->student_hash_id,
                        'registration_number' => $student->registration_number,
                        'institute_id' => $instituteId,
                        'branch_id' => $student->branch_id,
                        'department_id' => $academic->department_id,
                        'department_category_id' => $academic->department_category_id,
                        'course_subtype_id' => $academic->course_subtype_id,
                        'batch_id' => $batchId,
                        'academic_year_id' => $academicYearId,
                        'section_id' => $sectionId,
                        'roll_number' => $rollNumber,
                        'roll_number_sequence' => $sequence,
                        'status' => 'active',
                    ]);
                }

                $assigned++;
            }

            DB::commit();

            return [
                'success' => true,
                'assigned' => $assigned,
                'failed' => $failed,
                'message' => "Roll numbers reassigned for {$assigned} students",
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error reassigning roll numbers: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }
}