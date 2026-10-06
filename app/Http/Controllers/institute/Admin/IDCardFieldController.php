<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\IDCardFieldSetting;
use Illuminate\Http\Request;

class IDCardFieldController extends Controller
{
    /**
     * Get field settings for the institute
     */
    public function getFieldSettings(Request $request)
    {
        $instituteId = auth()->user()->institute_id ?? $request->institute_id;
        
        // Essential fields that must always be shown (cannot be disabled)
        $essentialFields = [
            'student_name', 'father_name', 'mother_name', 'class', 'section', 'phone', 'address'
        ];
        
        // Default fields that can be shown on ID card
        $defaultFields = [
            ['name' => 'student_name', 'label' => 'Student Name', 'essential' => true, 'visible' => true, 'order' => 1],
            ['name' => 'father_name', 'label' => "Father's Name", 'essential' => true, 'visible' => true, 'order' => 2],
            ['name' => 'mother_name', 'label' => "Mother's Name", 'essential' => true, 'visible' => true, 'order' => 3],
            ['name' => 'class', 'label' => 'Class', 'essential' => true, 'visible' => true, 'order' => 4],
            ['name' => 'section', 'label' => 'Section', 'essential' => true, 'visible' => true, 'order' => 5],
            ['name' => 'phone', 'label' => 'Contact No.', 'essential' => true, 'visible' => true, 'order' => 6],
            ['name' => 'address', 'label' => 'Address', 'essential' => true, 'visible' => true, 'order' => 7],
            ['name' => 'admission_number', 'label' => 'Admission Number', 'essential' => false, 'visible' => false, 'order' => 8],
            ['name' => 'blood_group', 'label' => 'Blood Group', 'essential' => false, 'visible' => true, 'order' => 9],
            ['name' => 'dob', 'label' => 'Date of Birth', 'essential' => false, 'visible' => true, 'order' => 10],
            ['name' => 'email', 'label' => 'Email', 'essential' => false, 'visible' => false, 'order' => 11],
            ['name' => 'emergency_contact', 'label' => 'Emergency Contact', 'essential' => false, 'visible' => false, 'order' => 12],
            ['name' => 'aadhar_number', 'label' => 'Aadhar Number', 'essential' => false, 'visible' => false, 'order' => 13],
            ['name' => 'category', 'label' => 'Category', 'essential' => false, 'visible' => false, 'order' => 14],
            ['name' => 'religion', 'label' => 'Religion', 'essential' => false, 'visible' => false, 'order' => 15],
            ['name' => 'nationality', 'label' => 'Nationality', 'essential' => false, 'visible' => false, 'order' => 16],
        ];
        
        // Get saved settings from database
        $savedSettings = IDCardFieldSetting::where('institute_id', $instituteId)
            ->get()
            ->keyBy('field_name');
        
        // Merge with defaults
        $fields = [];
        foreach ($defaultFields as $field) {
            if (isset($savedSettings[$field['name']])) {
                $fields[] = [
                    'name' => $field['name'],
                    'label' => $savedSettings[$field['name']]->field_label,
                    'essential' => $field['essential'], // Keep essential flag from defaults
                    'is_visible' => $field['essential'] ? true : $savedSettings[$field['name']]->is_visible, // Essential always visible
                    'sort_order' => $savedSettings[$field['name']]->sort_order
                ];
            } else {
                $fields[] = [
                    'name' => $field['name'],
                    'label' => $field['label'],
                    'essential' => $field['essential'],
                    'is_visible' => $field['essential'] ? true : $field['visible'], // Essential always visible
                    'sort_order' => $field['order']
                ];
            }
        }
        
        // Sort by order
        usort($fields, function($a, $b) {
            return $a['sort_order'] - $b['sort_order'];
        });
        
        return response()->json([
            'success' => true,
            'fields' => $fields
        ]);
    }
    
    /**
     * Save field settings
     */
    public function saveFieldSettings(Request $request)
    {
        $request->validate([
            'fields' => 'required|array',
            'fields.*.name' => 'required|string',
            'fields.*.label' => 'required|string',
            'fields.*.is_visible' => 'required|boolean',
            'fields.*.sort_order' => 'required|integer'
        ]);
        
        $instituteId = auth()->user()->institute_id ?? $request->institute_id;
        
        if (!$instituteId) {
            return response()->json([
                'success' => false,
                'message' => 'Institute ID not found'
            ], 400);
        }
        
        // Essential fields that cannot be disabled
        $essentialFields = ['student_name', 'father_name', 'mother_name', 'class', 'section', 'phone', 'address'];
        
        // Delete existing settings
        IDCardFieldSetting::where('institute_id', $instituteId)->delete();
        
        // Save new settings
        foreach ($request->fields as $field) {
            // Force essential fields to be visible
            $isVisible = in_array($field['name'], $essentialFields) ? true : $field['is_visible'];
            
            IDCardFieldSetting::create([
                'institute_id' => $instituteId,
                'type' => 'student_card',
                'field_name' => $field['name'],
                'field_label' => $field['label'],
                'is_visible' => $isVisible,
                'sort_order' => $field['sort_order']
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Field settings saved successfully'
        ]);
    }
    
    /**
     * Reset to default settings
     */
    public function resetToDefault(Request $request)
    {
        $instituteId = auth()->user()->institute_id ?? $request->institute_id;
        
        IDCardFieldSetting::where('institute_id', $instituteId)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Reset to default settings'
        ]);
    }
}