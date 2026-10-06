<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\FincapMerchantSubCategories;
use App\Models\FincapMerchant;
use Illuminate\Support\Facades\Auth;
use App\Models\Allsubcategories;
use App\Models\ProductDetails;
use App\Models\InstituteBasicDetails;
use App\Models\CourseFeeStructure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
class EditCourseFeeStructureController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;

public function editCourseFeeStructure($fee_structure_id)
{
    $context = $this->getInstituteBranchContext();

    if (!$context['institute_id']) {
        return redirect()->back()
            ->with('error', 'You are not associated with any institute.');
    }

    /*
    |--------------------------------------------------------------------------
    | Get EXACT fee structure
    |--------------------------------------------------------------------------
    */

    $feeStructure = CourseFeeStructure::where('id', $fee_structure_id)
        ->where('institute_id', $context['institute_id'])
        ->first();

    if (!$feeStructure) {
        return redirect()
            ->route('fee.structure.view')
            ->with(
                'error',
                'Fee structure not found or you do not have access.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Get Product Details
    |--------------------------------------------------------------------------
    */

    $productId = $feeStructure->product_id;

    $ProductDetail = ProductDetails::where('product_id', $productId)
        ->where('institute_id', $context['institute_id'])
        ->first();

    /*
    |--------------------------------------------------------------------------
    | Institute Details
    |--------------------------------------------------------------------------
    */

    $serviceInstitutedetails = DB::table('institutes')
        ->where('fincap_merchant_id', $context['institute_id'])
        ->first();

    /*
    |--------------------------------------------------------------------------
    | Process Sections
    |--------------------------------------------------------------------------
    */

    $sectionsData = json_decode(
        $feeStructure->sections,
        true
    ) ?? [];

    $processedSections = [];

    foreach ($sectionsData as $index => $section) {

        if (isset($section['id'])) {

            $processedSections[] = $section;

        } else {

            $sectionId = $section['section_id']
                ?? 'section_' . ($index + 1);

            $section['id'] = $sectionId;

            if (!isset($section['section_id'])) {
                $section['section_id'] = $sectionId;
            }

            $processedSections[] = $section;
        }
    }

    return view(
        'instituteAdmin.CourseFiles.EditCourseFeeStructure',
        compact(
            'ProductDetail',
            'feeStructure',
            'serviceInstitutedetails'
        )
    )->with(
        'sectionsData',
        $processedSections
    );
}

public function updateCourseFeeStructure(Request $request, $fee_structure_id)
{
    // ============================================================
    // 1. Get institute / branch context
    // ============================================================
    $context = $this->getInstituteBranchContext();

    if (!$context['institute_id']) {
        return back()
            ->with('error', 'You are not associated with any institute.')
            ->withInput();
    }

    // ============================================================
    // 2. Find EXACT fee structure by its primary ID
    //    IMPORTANT:
    //    Do NOT use product_id + academic_year_id to identify
    //    the record because multiple records can have the same
    //    product_id.
    // ============================================================
    $feeStructure = CourseFeeStructure::where('id', $fee_structure_id)
        ->where('institute_id', $context['institute_id'])
        ->first();

    if (!$feeStructure) {
        return back()
            ->with('error', 'Fee structure not found or you do not have access to this record.')
            ->withInput();
    }

    // ============================================================
    // 3. Verify academic year
    //    Academic year is used for validation, NOT for finding
    //    the record.
    // ============================================================
    $submittedAcademicYearId = $request->input('academic_year_id');

    if (
        $submittedAcademicYearId &&
        (string) $feeStructure->academic_year_id !== (string) $submittedAcademicYearId
    ) {
        return back()
            ->with('error', 'Academic year mismatch. The fee structure was not updated.')
            ->withInput();
    }

    // ============================================================
    // 4. Verify batch
    // ============================================================
    $submittedBatchId = $request->input('batch_id');

    if (
        $submittedBatchId &&
        (string) $feeStructure->batch_id !== (string) $submittedBatchId
    ) {
        return back()
            ->with('error', 'Batch ID mismatch. The fee structure was not updated.')
            ->withInput();
    }

    // ============================================================
    // 5. Get existing sections
    //    This allows us to preserve occupied_seats and IDs.
    // ============================================================
    $existingSections = [];

    if (!empty($feeStructure->sections)) {
        $decodedSections = json_decode($feeStructure->sections, true);

        if (is_array($decodedSections)) {
            $existingSections = $decodedSections;
        }
    }

    $sections = [];

    // ============================================================
    // 6. Prepare Sections
    // ============================================================
    if ($request->has('section_names') && is_array($request->section_names)) {

        foreach ($request->section_names as $index => $name) {

            // Ignore empty section names
            if (empty($name)) {
                continue;
            }

            $seats = $request->section_seats[$index] ?? 0;
            $seats = (int) $seats;

            $sectionId = $request->section_ids[$index] ?? null;

            $existingSection = null;

            // ----------------------------------------------------
            // Find existing section by ID
            // ----------------------------------------------------
            if ($sectionId && !empty($existingSections)) {

                foreach ($existingSections as $existing) {

                    if (
                        (isset($existing['id']) &&
                            (string) $existing['id'] === (string) $sectionId)
                        ||
                        (isset($existing['section_id']) &&
                            (string) $existing['section_id'] === (string) $sectionId)
                    ) {
                        $existingSection = $existing;
                        break;
                    }
                }
            }

            // ----------------------------------------------------
            // Prepare section data
            // ----------------------------------------------------
            $sectionData = [
                'name'  => $name,
                'seats' => $seats,
            ];

            // ----------------------------------------------------
            // Existing section
            // ----------------------------------------------------
            if ($existingSection) {

                $finalSectionId =
                    $existingSection['id']
                    ?? $existingSection['section_id']
                    ?? $sectionId
                    ?? 'section_' . ($index + 1);

                $finalSectionId2 =
                    $existingSection['section_id']
                    ?? $existingSection['id']
                    ?? $sectionId
                    ?? $finalSectionId;

                $occupiedSeats = (int) (
                    $existingSection['occupied_seats'] ?? 0
                );

                // Prevent occupied seats from becoming greater
                // than total seats.
                $occupiedSeats = min($occupiedSeats, $seats);

                $availableSeats = max(
                    0,
                    $seats - $occupiedSeats
                );

                $sectionData['id'] = $finalSectionId;
                $sectionData['section_id'] = $finalSectionId2;
                $sectionData['occupied_seats'] = $occupiedSeats;
                $sectionData['available_seats'] = $availableSeats;

            } else {

                // ------------------------------------------------
                // New section
                // ------------------------------------------------
                $newSectionId =
                    $sectionId
                    ?? 'section_' . ($index + 1);

                $sectionData['id'] = $newSectionId;
                $sectionData['section_id'] = $newSectionId;
                $sectionData['occupied_seats'] = 0;
                $sectionData['available_seats'] = $seats;
            }

            $sections[] = $sectionData;
        }
    }

    // ============================================================
    // 7. Prepare Course Fee
    // ============================================================
    $courseFee = [];

    if (
        $request->has('course_fee_checkbox') &&
        $request->course_fee_checkbox == 'on'
    ) {

        $courseFee = [
            'duration' => $request->course_fee_duration ?? 'One Time',
            'payments' => []
        ];

        // --------------------------------------------------------
        // Course fee payments
        // --------------------------------------------------------
        if (
            $request->has('course_fee_amount') &&
            is_array($request->course_fee_amount)
        ) {

            foreach ($request->course_fee_amount as $index => $amount) {

                if ($amount !== null && $amount !== '') {

                    $courseFee['payments'][] = [
                        'amount' => $amount,

                        'start_date' =>
                            $request->course_start_date[$index] ?? null,

                        'end_date' =>
                            $request->course_end_date[$index] ?? null,
                    ];
                }
            }
        }

        // --------------------------------------------------------
        // Course Late Fee
        // --------------------------------------------------------
        if (
            $request->has('course_late_fee') &&
            $request->course_late_fee == 'on'
        ) {

            $courseFee['late_fee'] = [
                'type' =>
                    $request->course_late_fee_type ?? 'flat',

                'amount' =>
                    $request->course_late_fee_amount ?? 0,
            ];
        }

        // --------------------------------------------------------
        // Course Partial Payment
        // --------------------------------------------------------
        if (
            $request->has('course_partial_fee') &&
            $request->course_partial_fee == 'on'
        ) {

            $courseFee['partial_payment'] = [
                'type' =>
                    $request->course_partial_fee_type ?? 'fixed',

                'amount' =>
                    $request->course_partial_fee_amount ?? 0,
            ];
        }
    }

    // ============================================================
    // 8. Prepare Registration Fee
    // ============================================================
    $registrationFee = [];

    if (
        $request->has('registration_fee_checkbox') &&
        $request->registration_fee_checkbox == 'on'
    ) {

        $registrationFee = [
            'duration' => 'One Time',
            'payments' => []
        ];

        // --------------------------------------------------------
        // Registration fee payments
        // --------------------------------------------------------
        if (
            $request->has('registration_fee_amount') &&
            is_array($request->registration_fee_amount)
        ) {

            foreach ($request->registration_fee_amount as $index => $amount) {

                if ($amount !== null && $amount !== '') {

                    $registrationFee['payments'][] = [
                        'amount' => $amount,

                        'start_date' =>
                            $request->registration_start_date[$index] ?? null,

                        'end_date' =>
                            $request->registration_end_date[$index] ?? null,
                    ];
                }
            }
        }

        // --------------------------------------------------------
        // Registration Late Fee
        // --------------------------------------------------------
        if (
            $request->has('registration_late_fee') &&
            $request->registration_late_fee == 'on'
        ) {

            $registrationFee['late_fee'] = [
                'type' =>
                    $request->registration_late_fee_type ?? 'flat',

                'amount' =>
                    $request->registration_late_fee_amount ?? 0,
            ];
        }

        // --------------------------------------------------------
        // Registration Partial Payment
        // --------------------------------------------------------
        if (
            $request->has('registration_partial_fee') &&
            $request->registration_partial_fee == 'on'
        ) {

            $registrationFee['partial_payment'] = [
                'type' =>
                    $request->registration_partial_fee_type ?? 'fixed',

                'amount' =>
                    $request->registration_partial_fee_amount ?? 0,
            ];
        }
    }

    // ============================================================
    // 9. Update EXACT CourseFeeStructure record
    // ============================================================
    $feeStructure->update([
        'batch' =>
            $request->batch ?? $feeStructure->batch,

        'batch_year' =>
            $request->batch_year ?? $feeStructure->batch_year,

        'batch_status' =>
            $request->batch_status ?? 'running',

        'total_seats' =>
            $request->total_seats ?? 0,

        'available_seats' =>
            $request->available_seats ?? 0,

        'sections' =>
            !empty($sections)
                ? json_encode($sections)
                : null,

        'course_fee' =>
        
            !empty($courseFee)
                ? json_encode($courseFee)
                : null,

        'registration_fee' =>
            !empty($registrationFee)
                ? json_encode($registrationFee)
                : null,

        'total_fee' =>
            $request->total_fee ?? 0,

        'is_active' =>
            $request->has('is_active'),
    ]);

    // ============================================================
    // 10. Redirect
    // ============================================================
    return redirect()
        ->route('fee.structure.view')
        ->with(
            'success',
            'Fee structure updated successfully for academic year: '
            . ($feeStructure->academic_year ?? 'N/A')
        );
}

private function prepareSections($request)
{
    $sections = [];
    if ($request->has('section_names')) {
        foreach ($request->section_names as $index => $name) {
            $sections[] = [
                'name' => $name,
                'seats' => $request->section_seats[$index] ?? 0
            ];
        }
    }
    return $sections;
}

private function prepareCourseFee($request)
{
    $courseFee = [
        'duration' => $request->course_fee_duration,
        'payments' => []
    ];
    
    if ($request->has('course_fee_amount')) {
        foreach ($request->course_fee_amount as $index => $amount) {
            $courseFee['payments'][] = [
                'amount' => $amount,
                'start_date' => $request->course_start_date[$index] ?? null,
                'end_date' => $request->course_end_date[$index] ?? null
            ];
        }
    }
    
    // Add late fee if exists
    if ($request->has('course_late_fee_amount')) {
        $courseFee['late_fee'] = [
            'type' => $request->course_late_fee_type,
            'amount' => $request->course_late_fee_amount
        ];
    }
    
    // Add partial payment if exists
    if ($request->has('course_partial_fee_amount')) {
        $courseFee['partial_payment'] = [
            'type' => $request->course_partial_fee_type,
            'amount' => $request->course_partial_fee_amount
        ];
    }
    
    return $courseFee;
}

private function prepareRegistrationFee($request)
{
    $registrationFee = [
        'duration' => 'One Time',
        'payments' => []
    ];
    
    if ($request->has('registration_fee_amount')) {
        foreach ($request->registration_fee_amount as $index => $amount) {
            $registrationFee['payments'][] = [
                'amount' => $amount,
                'start_date' => $request->registration_start_date[$index] ?? null,
                'end_date' => $request->registration_end_date[$index] ?? null
            ];
        }
    }
    
    // Add late fee if exists
    if ($request->has('registration_late_fee_amount')) {
        $registrationFee['late_fee'] = [
            'type' => $request->registration_late_fee_type,
            'amount' => $request->registration_late_fee_amount
        ];
    }
    
    // Add partial payment if exists
    if ($request->has('registration_partial_fee_amount')) {
        $registrationFee['partial_payment'] = [
            'type' => $request->registration_partial_fee_type,
            'amount' => $request->registration_partial_fee_amount
        ];
    }
    
    return $registrationFee;
}

public function viewBatchDetails(Request $request)
{
    $batchId = $request->input('batch_id');
    
    if (!$batchId) {
        return response()->json(['error' => 'Batch ID required'], 400);
    }

    // Get all fee structures in this batch
    $batchDetails = CourseFeeStructure::where('batch_id', $batchId)
        ->orderBy('academic_year')
        ->get();

    if ($batchDetails->isEmpty()) {
        return '<p class="text-center text-muted">No records found for this batch.</p>';
    }

    $html = '<div class="table-responsive">';
    $html .= '<table class="table table-sm">';
    $html .= '<thead><tr><th>Academic Year</th><th>Total Seats</th><th>Available Seats</th><th>Total Fee</th><th>Status</th></tr></thead>';
    $html .= '<tbody>';
    
    foreach ($batchDetails as $detail) {
        $html .= '<tr>';
        $html .= '<td>' . ($detail->academic_year ?? 'N/A') . '</td>';
        $html .= '<td>' . ($detail->total_seats ?? 0) . '</td>';
        $html .= '<td>' . ($detail->available_seats ?? 0) . '</td>';
        $html .= '<td>₹' . number_format($detail->total_fee, 2) . '</td>';
        $html .= '<td><span class="badge text-white bg-' . ($detail->batch_status == 'running' ? 'success' : ($detail->batch_status == 'complete' ? 'secondary' : 'info')) . '">' . ucfirst($detail->batch_status) . '</span></td>';
        $html .= '</tr>';
    }
    
    $html .= '</tbody></table>';
    $html .= '<p class="text-muted"><small>Showing ' . $batchDetails->count() . ' records</small></p>';
    $html .= '</div>';
    
    return $html;
}
}