<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait SectionNameHelper
{
    /**
     * Get section display name from section ID
     */
    private function getSectionDisplayName($sectionId, $productId, $instituteId, $branchId = null)
    {
        if (empty($sectionId) || empty($productId)) {
            return $sectionId;
        }

        $query = DB::table('course_fee_structures')
            ->where('product_id', $productId)
            ->where('institute_id', $instituteId);

        if (!empty($branchId)) {
            $query->where('branch_id', $branchId);
        }

        $sectionData = $query->value('sections');

        if (!$sectionData) {
            return $sectionId;
        }

        $sections = json_decode($sectionData, true);

        if (!is_array($sections)) {
            return $sectionId;
        }

        // Remove 'section_' prefix for comparison
        $normalizedInput = str_replace('section_', '', $sectionId);
        $originalInput = $sectionId;

        foreach ($sections as $section) {
            // Check multiple possible key fields
            $possibleKeys = [
                $section['id'] ?? null,
                $section['section_id'] ?? null,
                str_replace('section_', '', $section['id'] ?? ''),
                str_replace('section_', '', $section['section_id'] ?? '')
            ];
            
            $sectionName = $section['name'] ?? $section['section_name'] ?? null;
            
            if (!$sectionName) continue;

            foreach ($possibleKeys as $key) {
                if (!$key) continue;
                
                if ($key == $originalInput || $key == $normalizedInput) {
                    return $sectionName;
                }
            }
        }

        // Try to match by numeric ID only
        if (is_numeric($normalizedInput)) {
            foreach ($sections as $section) {
                $id = $section['id'] ?? $section['section_id'] ?? null;
                if ($id) {
                    $numericId = str_replace('section_', '', $id);
                    if ($numericId == $normalizedInput) {
                        return $section['name'] ?? $section['section_name'] ?? $sectionId;
                    }
                }
            }
        }

        return $sectionId;
    }

    /**
     * Format duration in hours and minutes
     */
    private function formatDuration($minutes)
    {
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        
        if ($hours > 0 && $mins > 0) {
            return "{$hours}h {$mins}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$mins}m";
        }
    }
}