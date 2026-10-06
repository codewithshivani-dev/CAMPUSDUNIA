<?php

namespace App\Http\Controllers\institute\Frontend;

use App\Http\Controllers\Controller;

use App\Models\InstituteBasicDetails;
use App\Models\InstituteDocuments;
use App\Models\Stakeholder;
use App\Models\AuthorizedUser;
use App\Models\User;
use App\Models\InstituteBeneficiaryDetail;
use App\Models\StakeholderDocument;
use App\Models\AuthorizedUserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class InstituteOnboardController extends Controller
{
    
public function store(Request $request)
{
    DB::beginTransaction();
    try {
       
        $validated = $request->validate([
            // Form 1 validations
            // 'form1.name' => 'required|string|max:255',
            // 'form1.type' => 'required|string',
            // 'form1.registration_type' => 'required|string',
            // 'form1.email' => 'required|email',
            // 'form1.contact_number' => 'required',
            // 'form1.address_line_1' => 'required|string',
            // 'form1.state' => 'required|string',
            // 'form1.city' => 'required|string',
            // 'form1.pincode' => 'required|string',
            // 'form1.establishment_date' => 'required|date',
            
            // // Form 2 validations
            // 'form2.stakeholders.*.name' => 'sometimes|required|string|max:255',
            // 'form2.stakeholders.*.email' => 'sometimes|required|email',
            // 'form2.stakeholders.*.phone_number' => 'sometimes|required|string',
            // 'form2.stakeholders.*.designation' => 'sometimes|required|string',
            // 'form2.stakeholders.*.pan_number' => 'sometimes|required|string|max:10',
            // 'form2.stakeholders.*.aadhaar_number' => 'sometimes|required|string|max:12',
            
            // 'form2.authorizedUsers.*.authorized_name' => 'sometimes|required|string|max:255',
            // 'form2.authorizedUsers.*.authorized_email' => 'sometimes|required|email',
            // 'form2.authorizedUsers.*.authorized_phone_number' => 'sometimes|required|string',
            // 'form2.authorizedUsers.*.authorized_designation' => 'sometimes|required|string',
            // 'form2.authorizedUsers.*.authorized_pan_number' => 'sometimes|required|string|max:10',
            // 'form2.authorizedUsers.*.authorized_aadhaar_number' => 'sometimes|required|string|max:12',
            
            // // Form 4 validations - CHANGED terms_agreed validation
            // 'form4.beneficiary_name' => 'required|string|max:255',
            // 'form4.account_number' => 'required|string',
            // 'form4.bank_name' => 'required|string',
            // 'form4.ifsc_code' => 'required|string',
            // 'form4.account_type' => 'required|string',
            // 'form4.terms_agreed' => 'required',
        ]);

        // Generate unique merchant ID
        $fincapMerchantId = $this->generateFincapMerchantId();
        
        // ----------------------------
        // 1️⃣ Store Form 1 data (Institute)
        // ----------------------------
        $form1 = $request->form1;
        
        $institute = InstituteBasicDetails::create([
            'fincap_merchant_id' => $fincapMerchantId,
            'fincap_partner_id' => $form1['fincap_partner_id'] ?? null,
            'name' => $form1['name'] ?? null,
            'type' => $form1['type'] ?? null,
            'affiliation' => $form1['affiliation'] ?? null,
            'registration_type' => $form1['registration_type'] ?? null,
            'email' => $form1['email'] ?? null,
            'contact_number' => $form1['contact_number'] ?? null,
            'address_line_1' => $form1['address_line_1'] ?? null,
            'address_line_2' => $form1['address_line_2'] ?? null,
            'state' => $form1['state'] ?? null,
            'city' => $form1['city'] ?? null,
            'pincode' => $form1['pincode'] ?? null,
            'establishment_date' => $form1['establishment_date'] ?? null,
            'website' => $form1['website'] ?? null,
            'registration_number' => $form1['registration_number'] ?? null,
        ]);
      
        $instituteId = $institute->fincap_merchant_id;

        // ----------------------------
        // 2️⃣ Store Form 2 data (Stakeholders & Authorized Users)
        // ----------------------------
        $form2 = $request->form2 ?? [];
        
        // Store Stakeholders
        $stakeholders = $form2['stakeholders'] ?? [];
        $stakeholderRecords = [];
        foreach ($stakeholders as $index => $stakeholder) {
            if (!empty($stakeholder['name'])) {
                $stakeholderRecord = Stakeholder::create([
                    'institute_id' => $instituteId,
                    'name' => $stakeholder['name'] ?? null,
                    'email' => $stakeholder['email'] ?? null,
                    'phone_number' => $stakeholder['phone_number'] ?? null,
                    'designation' => $stakeholder['designation'] ?? null,
                    'pan_number' => $stakeholder['pan_number'] ?? null,
                    'aadhaar_number' => $stakeholder['aadhaar_number'] ?? null,
                ]);
                $stakeholderRecords[$index] = $stakeholderRecord;
            }
        }

        // Store Authorized Users
        $authorizedUsers = $form2['authorizedUsers'] ?? [];
        $authorizedUserRecords = [];
        foreach ($authorizedUsers as $index => $authUser) {
            if (!empty($authUser['authorized_name'])) {
                $authorizedUserRecord = AuthorizedUser::create([
                    'institute_id' => $instituteId,
                    'name' => $authUser['authorized_name'] ?? null,
                    'email' => $authUser['authorized_email'] ?? null,
                    'phone_number' => $authUser['authorized_phone_number'] ?? null,
                    'designation' => $authUser['authorized_designation'] ?? null,
                    'pan_number' => $authUser['authorized_pan_number'] ?? null,
                    'aadhaar_number' => $authUser['authorized_aadhaar_number'] ?? null,
                ]);
               
                 // ✅ CREATE USER ACCOUNT FOR AUTHORIZED USER
                $this->createAuthorizedUserAccount($authorizedUserRecord, $instituteId);
                
                $authorizedUserRecords[$index] = $authorizedUserRecord;
            }
        }

        // ----------------------------
        // 3️⃣ Store Form 3 data (Documents)
        // ----------------------------
        $form3 = $request->form3 ?? [];

        // Store Institute Documents
        $instituteDocument = InstituteDocuments::create([
            'institute_id' => $instituteId,
            'registration_number' => $form3['registration_number'] ?? null,
            'registration_document_path' => $this->storeFile($request->file('registration_document_path'), $instituteId, 'registration'),
            'pan_number' => $form3['pan_number'] ?? null,
            'pan_document_path' => $this->storeFile($request->file('pan_document_path'), $instituteId, 'pan'),
            'gst_number' => $form3['gst_number'] ?? null,
            'gst_document_path' => $this->storeFile($request->file('gst_document_path'), $instituteId, 'gst'),
            'logo_path' => $this->storeFile($request->file('logo_path'), $instituteId, 'logo'),
            'institute_image_path' => $this->storeFile($request->file('institute_image_path'), $instituteId, 'image'),
        ]);

        // Store Stakeholder Documents
        foreach ($stakeholderRecords as $index => $stakeholderRecord) {
            $stakeholderId = $stakeholderRecord->id;
            
            // Debug: Check if files exist
            \Log::info("Processing stakeholder {$index} documents");
            \Log::info("Aadhaar front file: " . ($request->file("stakeholder{$index}_aadhaar_front") ? 'Exists' : 'Missing'));
            \Log::info("Aadhaar back file: " . ($request->file("stakeholder{$index}_aadhaar_back") ? 'Exists' : 'Missing'));
            \Log::info("PAN file: " . ($request->file("stakeholder{$index}_pan") ? 'Exists' : 'Missing'));
            
            StakeholderDocument::create([
                'stakeholder_id' => $stakeholderId,
                'aadhaar_front_path' => $this->storeFile(
                    $request->file("stakeholder{$index}_aadhaar_front"), 
                    $instituteId, 
                    "stakeholders/{$stakeholderId}/aadhaar"
                ),
                'aadhaar_back_path' => $this->storeFile(
                    $request->file("stakeholder{$index}_aadhaar_back"), 
                    $instituteId, 
                    "stakeholders/{$stakeholderId}/aadhaar"
                ),
                'pan_document_path' => $this->storeFile(
                    $request->file("stakeholder{$index}_pan"), 
                    $instituteId, 
                    "stakeholders/{$stakeholderId}/pan"
                ),
                'aadhaar_status' => 'unverified',
                'pan_status' => 'unverified',
            ]);
        }


        // Store Authorized User Documents
        foreach ($authorizedUserRecords as $index => $authorizedUserRecord) {
            $authorizedUserId = $authorizedUserRecord->id;
            
            // Debug: Check if files exist
            \Log::info("Processing authorized user {$index} documents");
            \Log::info("Aadhaar front file: " . ($request->file("authorized{$index}_aadhaar_front") ? 'Exists' : 'Missing'));
            \Log::info("Aadhaar back file: " . ($request->file("authorized{$index}_aadhaar_back") ? 'Exists' : 'Missing'));
            \Log::info("PAN file: " . ($request->file("authorized{$index}_pan") ? 'Exists' : 'Missing'));
            
            AuthorizedUserDocument::create([
                'authorized_user_id' => $authorizedUserId,
                'aadhaar_front_path' => $this->storeFile(
                    $request->file("authorized{$index}_aadhaar_front"), 
                    $instituteId, 
                    "authorized_users/{$authorizedUserId}/aadhaar"
                ),
                'aadhaar_back_path' => $this->storeFile(
                    $request->file("authorized{$index}_aadhaar_back"), 
                    $instituteId, 
                    "authorized_users/{$authorizedUserId}/aadhaar"
                ),
                'pan_document_path' => $this->storeFile(
                    $request->file("authorized{$index}_pan"), 
                    $instituteId, 
                    "authorized_users/{$authorizedUserId}/pan"
                ),
                'aadhaar_status' => 'unverified',
                'pan_status' => 'unverified',
            ]);
        }

        // ----------------------------
        // 4️⃣ Store Form 4 data (Beneficiary)
        // ----------------------------
        $form4 = $request->form4 ?? [];
        
        // FIXED: Handle terms_agreed properly - it might come as string "true"/"false" or boolean
        $termsAgreed = 0;
        if (isset($form4['terms_agreed'])) {
            if (is_bool($form4['terms_agreed'])) {
                $termsAgreed = $form4['terms_agreed'] ? 1 : 0;
            } else {
                $termsAgreed = ($form4['terms_agreed'] === 'true' || $form4['terms_agreed'] === '1' || $form4['terms_agreed'] === true) ? 1 : 0;
            }
        }

        InstituteBeneficiaryDetail::create([
            'institute_id' => $instituteId,
            'beneficiary_name' => $form4['beneficiary_name'] ?? null,
            'account_number' => $form4['account_number'] ?? null,
            'bank_name' => $form4['bank_name'] ?? null,
            'ifsc_code' => $form4['ifsc_code'] ?? null,
            'account_type' => $form4['account_type'] ?? null,
            'cancelled_cheque_path' => $this->storeFile($request->file('cancelled_cheque_path'), $instituteId, 'cheque'),
            'terms_agreed' => $termsAgreed,
        ]);

        DB::commit();

        return response()->json([
            'success' => true, 
            'message' => 'Institute data saved successfully.',
            'institute_id' => $instituteId,
            'fincap_merchant_id' => $fincapMerchantId
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        \Log::error('Validation error in institute onboarding: ' . $e->getMessage());
        \Log::error('Validation errors: ', $e->errors());
        \Log::error('Request data: ', $request->all());
        
        return response()->json([
            'success' => false, 
            'error' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Institute onboarding error: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        \Log::error('Request data: ', $request->all());
        
        return response()->json([
            'success' => false, 
            'error' => 'Failed to save institute data: ' . $e->getMessage()
        ], 500);
    }
}

private function createAuthorizedUserAccount(AuthorizedUser $authorizedUser, $instituteId)
{
    $tempPassword = strtolower(str_replace(' ', '', $authorizedUser->name)) . '@' . date('Y');
    // $tempPassword = '12345678'; 
    // Create user account
    $user = User::create([
        'name' => $authorizedUser->name,
        'email' => $authorizedUser->email,
        'phone' => $authorizedUser->phone_number,
        'password' => Hash::make($tempPassword),
        'email_verified_at' => now(),
        'institute_id' => $instituteId, 
    ]);
    
    $user->assignRole('admin');
    $authorizedUser->update(['user_id' => $user->id]);
    // Mail::to($authorizedUser->email)->send(new AuthorizedUserCredentialsMail($authorizedUser, $tempPassword));   
    return $user;
}

// public function store(Request $request)
// {
//     DB::beginTransaction();
//     try {
//         // 🔹 Step 1: Generate Merchant ID
//         $fincapMerchantId = $this->generateFincapMerchantId();

//         // ----------------------------
//         // 1️⃣ FORM 1 — Institute Basic Details
//         // ----------------------------
//         $form1 = $request->form1 ?? [];
//         $institute = InstituteBasicDetails::create([
//             'fincap_merchant_id' => $fincapMerchantId,
//             'fincap_partner_id' => $form1['fincap_partner_id'] ?? null,
//             'name' => $form1['name'] ?? null,
//             'type' => $form1['type'] ?? null,
//             'registration_type' => $form1['registration_type'] ?? null,
//             'email' => $form1['email'] ?? null,
//             'contact_number' => $form1['contact_number'] ?? null,
//             'address_line_1' => $form1['address_line_1'] ?? null,
//             'address_line_2' => $form1['address_line_2'] ?? null,
//             'state' => $form1['state'] ?? null,
//             'city' => $form1['city'] ?? null,
//             'pincode' => $form1['pincode'] ?? null,
//             'establishment_date' => $form1['establishment_date'] ?? null,
//             'website' => $form1['website'] ?? null,
//             'registration_number' => $form1['registration_number'] ?? null,
//         ]);

//         $instituteId = $institute->fincap_merchant_id;

//         // ----------------------------
//         // 2️⃣ FORM 2 — Stakeholders & Authorized Users
//         // ----------------------------
//         $form2 = $request->form2 ?? [];

//         $stakeholders = [];
//         $authorizedUsers = [];

//         // ➤ Store Stakeholders
//         foreach ($form2['stakeholders'] ?? [] as $index => $s) {
//             if (!empty($s['name'])) {
//                 $stakeholder = Stakeholder::create([
//                     'institute_id' => $instituteId,
//                     'name' => $s['name'] ?? null,
//                     'email' => $s['email'] ?? null,
//                     'phone_number' => $s['phone_number'] ?? null,
//                     'designation' => $s['designation'] ?? null,
//                     'pan_number' => $s['pan_number'] ?? null,
//                     'aadhaar_number' => $s['aadhaar_number'] ?? null,
//                 ]);
//                 $stakeholders[$index] = $stakeholder;
//             }
//         }

//         // ➤ Store Authorized Users — avoid duplicates
//         foreach ($form2['authorizedUsers'] ?? [] as $index => $a) {
//             if (!empty($a['authorized_name'])) {
//                 // Check if already exists in stakeholders
//                 $matchedStakeholder = collect($stakeholders)->first(function ($s) use ($a) {
//                     return strtolower($s->name) === strtolower($a['authorized_name'])
//                         && $s->phone_number === ($a['authorized_phone_number'] ?? '');
//                 });

//                 if ($matchedStakeholder) {
//                     // ✅ Link to existing stakeholder
//                     $authorizedUser = AuthorizedUser::updateOrCreate([
//                         'stakeholder_id' => $matchedStakeholder->id,
//                     ], [
//                         'institute_id' => $instituteId,
//                         'name' => $a['authorized_name'] ?? null,
//                         'email' => $a['authorized_email'] ?? null,
//                         'phone_number' => $a['authorized_phone_number'] ?? null,
//                         'designation' => $a['authorized_designation'] ?? null,
//                         'pan_number' => $a['authorized_pan_number'] ?? null,
//                         'aadhaar_number' => $a['authorized_aadhaar_number'] ?? null,
//                     ]);
//                 } else {
//                     // ✅ Create new authorized user
//                     $authorizedUser = AuthorizedUser::create([
//                         'institute_id' => $instituteId,
//                         'name' => $a['authorized_name'] ?? null,
//                         'email' => $a['authorized_email'] ?? null,
//                         'phone_number' => $a['authorized_phone_number'] ?? null,
//                         'designation' => $a['authorized_designation'] ?? null,
//                         'pan_number' => $a['authorized_pan_number'] ?? null,
//                         'aadhaar_number' => $a['authorized_aadhaar_number'] ?? null,
//                     ]);
//                 }

//                 $authorizedUsers[$index] = $authorizedUser;
//             }
//         }

//         // ----------------------------
//         // 3️⃣ FORM 3 — Documents
//         // ----------------------------
//         $form3 = $request->form3 ?? [];

//         // ➤ Institute Documents
//         InstituteDocuments::create([
//             'institute_id' => $instituteId,
//             'registration_number' => $form3['registration_number'] ?? null,
//             'registration_document_path' => $this->storeFile($request->file('registration_document_path'), $instituteId, 'registration'),
//             'pan_number' => $form3['pan_number'] ?? null,
//             'pan_document_path' => $this->storeFile($request->file('pan_document_path'), $instituteId, 'pan'),
//             'gst_number' => $form3['gst_number'] ?? null,
//             'gst_document_path' => $this->storeFile($request->file('gst_document_path'), $instituteId, 'gst'),
//             'logo_path' => $this->storeFile($request->file('logo_path'), $instituteId, 'logo'),
//             'institute_image_path' => $this->storeFile($request->file('institute_image_path'), $instituteId, 'image'),
//         ]);

//         // ➤ Stakeholder Documents
//         foreach ($stakeholders as $index => $stakeholder) {
//             StakeholderDocument::create([
//                 'stakeholder_id' => $stakeholder->id,
//                 'aadhaar_front_path' => $this->storeFile($request->file("stakeholder{$index}_aadhaar_front"), $instituteId, "stakeholders/{$stakeholder->id}/aadhaar"),
//                 'aadhaar_back_path' => $this->storeFile($request->file("stakeholder{$index}_aadhaar_back"), $instituteId, "stakeholders/{$stakeholder->id}/aadhaar"),
//                 'pan_document_path' => $this->storeFile($request->file("stakeholder{$index}_pan"), $instituteId, "stakeholders/{$stakeholder->id}/pan"),
//                 'aadhaar_status' => 'unverified',
//                 'pan_status' => 'unverified',
//             ]);
//         }

//         // ➤ Authorized User Documents
//         foreach ($authorizedUsers as $index => $authUser) {
//             // Find if this authorized user is same as stakeholder
//             $linkedStakeholder = Stakeholder::where('id', $authUser->stakeholder_id)->first();

//             AuthorizedUserDocument::create([
//                 'authorized_user_id' => $authUser->id,
//                 'aadhaar_front_path' => $this->storeFile(
//                     $linkedStakeholder
//                         ? $request->file("stakeholder{$index}_aadhaar_front")
//                         : $request->file("authorized{$index}_aadhaar_front"),
//                     $instituteId,
//                     "authorized_users/{$authUser->id}/aadhaar"
//                 ),
//                 'aadhaar_back_path' => $this->storeFile(
//                     $linkedStakeholder
//                         ? $request->file("stakeholder{$index}_aadhaar_back")
//                         : $request->file("authorized{$index}_aadhaar_back"),
//                     $instituteId,
//                     "authorized_users/{$authUser->id}/aadhaar"
//                 ),
//                 'pan_document_path' => $this->storeFile(
//                     $linkedStakeholder
//                         ? $request->file("stakeholder{$index}_pan")
//                         : $request->file("authorized{$index}_pan"),
//                     $instituteId,
//                     "authorized_users/{$authUser->id}/pan"
//                 ),
//                 'aadhaar_status' => 'unverified',
//                 'pan_status' => 'unverified',
//             ]);
//         }

//         // ----------------------------
//         // 4️⃣ FORM 4 — Beneficiary Details
//         // ----------------------------
//         $form4 = $request->form4 ?? [];
//         $termsAgreed = in_array($form4['terms_agreed'] ?? '0', ['true', '1', 1, true]) ? 1 : 0;

//         InstituteBeneficiaryDetail::create([
//             'institute_id' => $instituteId,
//             'beneficiary_name' => $form4['beneficiary_name'] ?? null,
//             'account_number' => $form4['account_number'] ?? null,
//             'bank_name' => $form4['bank_name'] ?? null,
//             'ifsc_code' => $form4['ifsc_code'] ?? null,
//             'account_type' => $form4['account_type'] ?? null,
//             'cancelled_cheque_path' => $this->storeFile($request->file('cancelled_cheque_path'), $instituteId, 'cheque'),
//             'terms_agreed' => $termsAgreed,
//         ]);

//         DB::commit();

//         return response()->json([
//             'success' => true,
//             'message' => 'Institute data saved successfully.',
//             'institute_id' => $instituteId,
//             'fincap_merchant_id' => $fincapMerchantId
//         ]);

//     } catch (\Exception $e) {
//         DB::rollBack();
//         \Log::error('Institute onboarding error: ' . $e->getMessage());
//         \Log::error('Trace: ' . $e->getTraceAsString());
//         return response()->json([
//             'success' => false,
//             'error' => 'Failed to save institute data: ' . $e->getMessage()
//         ], 500);
//     }
// }


/**
 * Generate unique Fincap Merchant ID in format FMREGE063KJB
 */
private function generateFincapMerchantId()
{
    $prefix = 'FMREGE';
    
    do {
        // Generate random string: 3 digits + 3 uppercase letters
        $randomDigits = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        $randomLetters = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);
        
        $merchantId = $prefix . $randomDigits . $randomLetters;
        
        // Check if it already exists
        $exists = InstituteBasicDetails::where('fincap_merchant_id', $merchantId)->exists();
    } while ($exists);
    
    return $merchantId;
}

    // Improved file storage helper
    private function storeFile($file, $instituteId, $folder)
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        // Create directory path: institutes/{institute_id}/{folder}
        $path = "institutes/{$instituteId}/{$folder}";
        
        // Store file and return path
        return $file->store($path, 'public');
    }
}