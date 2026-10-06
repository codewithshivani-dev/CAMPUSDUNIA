<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\FincapMerchantSubCategories ;
use App\Models\InstituteBasicDetails;
use App\Models\FincapMerchant ;
use Illuminate\Support\Facades\Auth;
use App\models\Allsubcategories ;
use App\Models\AuthorizedUserDocument;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FincapMerchantController extends Controller
{
    public function getFincapMerchantDetails()
    {
       $merchantId = auth()->user()->institute_id;
       $fincapMerchants = InstituteBasicDetails::where('fincap_merchant_id', $merchantId)
        ->with(['stakeholders','stakeholderDocuments','authorizedUser','authorizedUserDocuments', 'documents','beneficiary'])
        ->first();
        return view('instituteAdmin.DashboardFiles.ViewinstituteDetails', compact('fincapMerchants'));
    }

    public function showEditForm()
    {
        $fincapMerchant = FincapMerchant::where('fincap_merchant_id', 
        'FMREGE063KJB')->first();
        if (!$fincapMerchant) {
            return redirect()->back()->with('error', 'Institute details not found.');
        }
        return view('instituteAdmin.DashboardFiles.EditinstituteDetails', compact('fincapMerchant'));
    }

    public function updatemerchantdetails(Request $request, $fincap_merchant_id)
    {
        $fincapMerchant = FincapMerchant::where('fincap_merchant_id', 
            'FMREGE063KJB')->first();
        
        if (!$fincapMerchant) {
            return redirect()->back()->with('error', 'Institute details not found.');
        }

        $request->validate([
            // Your validation rules
        ]);

        $fincapMerchant->fincap_merchant_name = $request->institute_name;
        $fincapMerchant->fincap_merchant_email = $request->fincap_merchant_email;
        $fincapMerchant->fincap_merchant_contact_number = $request->fincap_merchant_contact_number;
        $fincapMerchant->fincap_merchant_website = $request->fincap_merchant_website;
        $fincapMerchant->fincap_merchant_state = $request->fincap_merchant_state;
        $fincapMerchant->fincap_merchant_city = $request->fincap_merchant_city;
        $fincapMerchant->fincap_merchant_pincode = $request->fincap_merchant_pincode;

        if ($request->hasFile('fincap_merchant_images')) {
            $file = $request->file('fincap_merchant_images');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('images/instituteadminImages', $filename, 'public');
            $fincapMerchant->fincap_merchant_images = $path;
        }
        if ($request->hasFile('fincap_merchant_logo_image')) {
            $file = $request->file('fincap_merchant_logo_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('images/instituteadminImages', $filename, 'public');
            $fincapMerchant->fincap_merchant_logo_image = $path;
        }
        
        $fincapMerchant->save();
        return redirect()->route('fincap.merchant.edit', $fincapMerchant->fincap_merchant_id)
                         ->with('success', 'Institute details updated successfully.');
    }   

    /**
     * Upload STAMP image with background removal
     */
    public function uploadStamp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'authorized_user_id' => 'required|exists:authorized_users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Get the authorized user
            $authorizedUser = \App\Models\AuthorizedUser::find($request->authorized_user_id);
            if (!$authorizedUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authorized user not found.'
                ], 404);
            }

            // Get institute details
            $institute = InstituteBasicDetails::where('fincap_merchant_id', auth()->user()->institute_id)->first();
            if (!$institute) {
                return response()->json([
                    'success' => false,
                    'message' => 'Institute not found.'
                ], 404);
            }

            // Create directory structure in public folder
            $instituteId = $institute->fincap_merchant_id;
            $userId = $request->authorized_user_id;
            $directory = public_path("institutes/{$instituteId}/authorized_users/{$userId}/stamp/");
            
            // Create directory if not exists
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // Get the file and process it
            $file = $request->file('file');
            $timestamp = time();
            $filename = 'stamp_' . $timestamp . '.png';
            
            // Process stamp image - remove background and resize
            $processedImage = $this->processStampImage($file);
            
            // Save the file directly to public folder
            $fullPath = $directory . $filename;
            file_put_contents($fullPath, $processedImage);

            // Check if file was saved
            if (!file_exists($fullPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File was not saved properly'
                ], 500);
            }

            // Store relative path from public
            $path = "institutes/{$instituteId}/authorized_users/{$userId}/stamp/" . $filename;

            // Get or create document record
            $authorizedDoc = AuthorizedUserDocument::firstOrCreate(
                ['authorized_user_id' => $request->authorized_user_id],
                []
            );

            // Delete old stamp if exists
            if ($authorizedDoc->stamp_path) {
                $oldPath = public_path($authorizedDoc->stamp_path);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $authorizedDoc->update(['stamp_path' => $path]);

            return response()->json([
                'success' => true,
                'file_url' => asset($path),
                'message' => 'Stamp uploaded successfully.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Stamp upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload SIGNATURE image with background removal
     */
    public function uploadSignature(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'authorized_user_id' => 'required|exists:authorized_users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Get the authorized user
            $authorizedUser = \App\Models\AuthorizedUser::find($request->authorized_user_id);
            if (!$authorizedUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authorized user not found.'
                ], 404);
            }

            // Get institute details
            $institute = InstituteBasicDetails::where('fincap_merchant_id', auth()->user()->institute_id)->first();
            if (!$institute) {
                return response()->json([
                    'success' => false,
                    'message' => 'Institute not found.'
                ], 404);
            }

            // Create directory structure in public folder
            $instituteId = $institute->fincap_merchant_id;
            $userId = $request->authorized_user_id;
            $directory = public_path("institutes/{$instituteId}/authorized_users/{$userId}/signature/");
            
            // Create directory if not exists
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // Get the file and process it
            $file = $request->file('file');
            $timestamp = time();
            $filename = 'signature_' . $timestamp . '.png';
            
            // Process signature image - remove background and resize
            $processedImage = $this->processSignatureImage($file);
            
            // Save the file directly to public folder
            $fullPath = $directory . $filename;
            file_put_contents($fullPath, $processedImage);

            // Check if file was saved
            if (!file_exists($fullPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File was not saved properly'
                ], 500);
            }

            // Store relative path from public
            $path = "institutes/{$instituteId}/authorized_users/{$userId}/signature/" . $filename;

            // Get or create document record
            $authorizedDoc = AuthorizedUserDocument::firstOrCreate(
                ['authorized_user_id' => $request->authorized_user_id],
                []
            );

            // Delete old signature if exists
            if ($authorizedDoc->signature_path) {
                $oldPath = public_path($authorizedDoc->signature_path);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $authorizedDoc->update(['signature_path' => $path]);

            return response()->json([
                'success' => true,
                'file_url' => asset($path),
                'message' => 'Signature uploaded successfully.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Signature upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete STAMP image
     */
    public function deleteStamp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'authorized_user_id' => 'required|exists:authorized_users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $authorizedDoc = AuthorizedUserDocument::where('authorized_user_id', $request->authorized_user_id)->first();
            
            if (!$authorizedDoc) {
                return response()->json([
                    'success' => false,
                    'message' => 'Document record not found.'
                ], 404);
            }

            if ($authorizedDoc->stamp_path) {
                $fullPath = public_path($authorizedDoc->stamp_path);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }

            $authorizedDoc->update(['stamp_path' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Stamp removed successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Deletion failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete SIGNATURE image
     */
    public function deleteSignature(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'authorized_user_id' => 'required|exists:authorized_users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $authorizedDoc = AuthorizedUserDocument::where('authorized_user_id', $request->authorized_user_id)->first();
            
            if (!$authorizedDoc) {
                return response()->json([
                    'success' => false,
                    'message' => 'Document record not found.'
                ], 404);
            }

            if ($authorizedDoc->signature_path) {
                $fullPath = public_path($authorizedDoc->signature_path);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }

            $authorizedDoc->update(['signature_path' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Signature removed successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Deletion failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process STAMP image - remove background and resize
     */
    private function processStampImage($file)
    {
        // Get the image resource based on file type
        $imageInfo = getimagesize($file->getRealPath());
        $imageType = $imageInfo[2];
        
        // Create image resource based on type
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($file->getRealPath());
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($file->getRealPath());
                break;
            default:
                throw new \Exception('Unsupported image type');
        }
        
        // Get original dimensions
        $width = imagesx($image);
        $height = imagesy($image);
        
        // Remove white/light background with improved algorithm
        $image = $this->removeBackground($image, $width, $height);
        
        // Target dimensions for stamp (130x110)
        $targetWidth = 130;
        $targetHeight = 110;
        
        // Create resized image with transparency
        $resizedImage = $this->resizeWithTransparency($image, $width, $height, $targetWidth, $targetHeight);
        
        // Free memory
        imagedestroy($image);
        
        // Save as PNG with transparency
        ob_start();
        imagepng($resizedImage, null, 9);
        $imageData = ob_get_clean();
        
        // Free memory
        imagedestroy($resizedImage);
        
        return $imageData;
    }

    /**
     * Process SIGNATURE image - remove background and resize
     */
    private function processSignatureImage($file)
    {
        // Get the image resource based on file type
        $imageInfo = getimagesize($file->getRealPath());
        $imageType = $imageInfo[2];
        
        // Create image resource based on type
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($file->getRealPath());
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($file->getRealPath());
                break;
            default:
                throw new \Exception('Unsupported image type');
        }
        
        // Get original dimensions
        $width = imagesx($image);
        $height = imagesy($image);
        
        // Remove white/light background with improved algorithm
        $image = $this->removeBackground($image, $width, $height);
        
        // Target dimensions for signature (250x100)
        $targetWidth = 250;
        $targetHeight = 100;
        
        // Create resized image with transparency
        $resizedImage = $this->resizeWithTransparency($image, $width, $height, $targetWidth, $targetHeight);
        
        // Free memory
        imagedestroy($image);
        
        // Save as PNG with transparency
        ob_start();
        imagepng($resizedImage, null, 9);
        $imageData = ob_get_clean();
        
        // Free memory
        imagedestroy($resizedImage);
        
        return $imageData;
    }

    /**
     * Remove white/light background with improved algorithm
     */
    private function removeBackground($image, $width, $height)
    {
        // Create a new image with alpha channel
        $newImage = imagecreatetruecolor($width, $height);
        
        // Enable alpha blending
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        
        // Fill with transparent background
        $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
        imagefill($newImage, 0, 0, $transparent);
        
        // Sample edge pixels to detect the background color
        $bgColor = $this->detectBackgroundColor($image, $width, $height);
        
        // Process each pixel with improved algorithm
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                
                // Check if pixel is background color
                if ($this->isBackgroundPixel($r, $g, $b, $bgColor)) {
                    // Make transparent
                    $color = imagecolorallocatealpha($newImage, $r, $g, $b, 127);
                } else {
                    // Keep the pixel
                    $color = imagecolorallocatealpha($newImage, $r, $g, $b, 0);
                }
                
                imagesetpixel($newImage, $x, $y, $color);
            }
        }
        
        return $newImage;
    }

    /**
     * Detect background color by sampling edges
     */
    private function detectBackgroundColor($image, $width, $height)
    {
        $colors = [];
        
        // Sample corners and edges
        $positions = [
            [0, 0], [0, $height-1], [$width-1, 0], [$width-1, $height-1],
            [0, (int)($height/2)], [$width-1, (int)($height/2)],
            [(int)($width/2), 0], [(int)($width/2), $height-1]
        ];
        
        foreach ($positions as $pos) {
            $rgb = imagecolorat($image, $pos[0], $pos[1]);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
            $colors[] = ['r' => $r, 'g' => $g, 'b' => $b];
        }
        
        // Average the colors
        $avgR = array_sum(array_column($colors, 'r')) / count($colors);
        $avgG = array_sum(array_column($colors, 'g')) / count($colors);
        $avgB = array_sum(array_column($colors, 'b')) / count($colors);
        
        return ['r' => $avgR, 'g' => $avgG, 'b' => $avgB];
    }

    /**
     * Check if a pixel matches the background color
     */
    private function isBackgroundPixel($r, $g, $b, $bgColor, $threshold = 30)
    {
        $diffR = abs($r - $bgColor['r']);
        $diffG = abs($g - $bgColor['g']);
        $diffB = abs($b - $bgColor['b']);
        
        // Calculate Euclidean distance
        $distance = sqrt($diffR * $diffR + $diffG * $diffG + $diffB * $diffB);
        
        // Also check brightness as fallback for white backgrounds
        $brightness = ($r + $g + $b) / 3;
        
        // If pixel is very light OR close to background color
        return ($distance <= $threshold) || ($brightness > 200);
    }

    /**
     * Resize image with transparency
     */
    private function resizeWithTransparency($image, $origWidth, $origHeight, $targetWidth, $targetHeight)
    {
        // Calculate aspect ratio
        $aspectRatio = $origWidth / $origHeight;
        $newWidth = $targetWidth;
        $newHeight = $targetWidth / $aspectRatio;
        
        if ($newHeight > $targetHeight) {
            $newHeight = $targetHeight;
            $newWidth = $targetHeight * $aspectRatio;
        }
        
        // Create resized image with transparency
        $resizedImage = imagecreatetruecolor($targetWidth, $targetHeight);
        
        // Enable alpha blending and save alpha
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);
        
        // Fill with transparent background
        $transparent = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
        imagefill($resizedImage, 0, 0, $transparent);
        
        // Calculate position to center the image
        $x = ($targetWidth - $newWidth) / 2;
        $y = ($targetHeight - $newHeight) / 2;
        
        // Resize and copy
        imagecopyresampled($resizedImage, $image, $x, $y, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
        
        return $resizedImage;
    }
}