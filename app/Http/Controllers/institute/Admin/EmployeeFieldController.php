<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\IDCardFieldSetting;
use Illuminate\Http\Request;

class EmployeeFieldController extends Controller
{
    /**
     * Get field settings for employee card
     */
    public function getFieldSettings(Request $request)
    {
        $instituteId = auth()->user()->institute_id ?? $request->institute_id;
        
        // Default fields for employee card
        $defaultFields = [
            ['name' => 'full_name', 'label' => 'Name', 'visible' => true, 'order' => 1], 
            ['name' => 'employee_code', 'label' => 'Employee ID', 'visible' => true, 'order' => 2],
            ['name' => 'designation', 'label' => 'Designation', 'visible' => true, 'order' => 3],
            ['name' => 'department', 'label' => 'Department', 'visible' => true, 'order' => 4],
            ['name' => 'dob', 'label' => 'Date of Birth', 'visible' => true, 'order' => 5],
            ['name' => 'doj', 'label' => 'Joining Date', 'visible' => true, 'order' => 6],
            ['name' => 'phone', 'label' => 'Phone', 'visible' => true, 'order' => 7],
            ['name' => 'email', 'label' => 'Email', 'visible' => true, 'order' => 8],
            ['name' => 'blood_group', 'label' => 'Blood Group', 'visible' => true, 'order' => 9],
            ['name' => 'emergency_contact', 'label' => 'Emergency Contact', 'visible' => false, 'order' => 10],
            ['name' => 'aadhar_number', 'label' => 'Aadhar Number', 'visible' => false, 'order' => 11],
            ['name' => 'pan_number', 'label' => 'PAN Number', 'visible' => false, 'order' => 12],
            ['name' => 'gender', 'label' => 'Gender', 'visible' => false, 'order' => 13],
            ['name' => 'marital_status', 'label' => 'Marital Status', 'visible' => false, 'order' => 14],
            ['name' => 'address', 'label' => 'Address', 'visible' => false, 'order' => 15],
            ['name' => 'city', 'label' => 'City', 'visible' => false, 'order' => 16],
            ['name' => 'state', 'label' => 'State', 'visible' => false, 'order' => 17],
            ['name' => 'pincode', 'label' => 'Pincode', 'visible' => false, 'order' => 18],
        ];
        
        // Get saved settings from database for employee card
        $savedSettings = IDCardFieldSetting::where('institute_id', $instituteId)
            ->where('type', 'employee_card')
            ->get()
            ->keyBy('field_name');
        
        // Merge with defaults
        $fields = [];
        foreach ($defaultFields as $field) {
            if (isset($savedSettings[$field['name']])) {
                $fields[] = [
                    'name' => $field['name'],
                    'label' => $savedSettings[$field['name']]->field_label,
                    'is_visible' => $savedSettings[$field['name']]->is_visible,
                    'sort_order' => $savedSettings[$field['name']]->sort_order
                ];
            } else {
                $fields[] = [
                    'name' => $field['name'],
                    'label' => $field['label'],
                    'is_visible' => $field['visible'],
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
     * Save field settings for employee card
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
        
        // Delete existing settings for employee card
        IDCardFieldSetting::where('institute_id', $instituteId)
            ->where('type', 'employee_card')
            ->delete();
        
        // Save new settings
        foreach ($request->fields as $field) {
            IDCardFieldSetting::create([
                'institute_id' => $instituteId,
                'field_name' => $field['name'],
                'field_label' => $field['label'],
                'is_visible' => $field['is_visible'],
                'sort_order' => $field['sort_order'],
                'type' => 'employee_card'
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Employee card field settings saved successfully'
        ]);
    }
    
    /**
     * Reset to default settings for employee card
     */
    public function resetToDefault(Request $request)
    {
        $instituteId = auth()->user()->institute_id ?? $request->institute_id;
        
        IDCardFieldSetting::where('institute_id', $instituteId)
            ->where('type', 'employee_card')
            ->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Reset to default settings for employee card'
        ]);
    }
}