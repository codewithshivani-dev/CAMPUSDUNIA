<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use App\Models\LetterDesignSetting;
use App\Models\LetterTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log; // <-- ADD THIS LINE

class LetterDesignSettingController extends Controller
{
    /**
     * Resolve the persisted letter identifier from the template record.
     */
    private function resolveLetterIdentifier($letterId)
    {
        $letterId = (string) $letterId;

        $designSetting = LetterDesignSetting::where('letter_id', $letterId)->first();
        if ($designSetting) {
            return $letterId;
        }

        if (is_numeric($letterId)) {
            $template = LetterTemplate::find($letterId);
            if ($template && !empty($template->letter_id)) {
                return (string) $template->letter_id;
            }
        }

        $templateByLetterId = LetterTemplate::where('letter_id', $letterId)->first();
        if ($templateByLetterId) {
            return (string) $templateByLetterId->letter_id;
        }

        return $letterId;
    }

    /**
     * Save design settings for a letter.
     */
    public function save(Request $request, $letterId)
    {
        Log::info("Saving design for letterId: {$letterId}");
        
        $letterId = (string) $letterId;
        $template = null;
        $templateLetterId = null;
        
        // Find the template first
        if (is_numeric($letterId)) {
            $template = LetterTemplate::find((int) $letterId);
        }
        
        if (!$template) {
            $template = LetterTemplate::where('letter_id', $letterId)->first();
        }
        
        if (!$template) {
            $template = LetterTemplate::where('key', $letterId)->first();
        }
        
        // Determine the letter_id to use for design settings
        if ($template && !empty($template->letter_id)) {
            $templateLetterId = (string) $template->letter_id;
            Log::info("Using template's letter_id: {$templateLetterId}");
        } else {
            // If no letter_id in template, use the original ID
            $templateLetterId = $letterId;
            Log::info("Using original ID as letter_id: {$templateLetterId}");
        }
        
        // CRITICAL FIX: Check for existing record ONCE
        $designSetting = LetterDesignSetting::where('letter_id', $templateLetterId)->first();
        
        if ($designSetting) {
            Log::info("Found existing design setting with ID: {$designSetting->id}, updating...");
            // Update the existing record
            $designSetting->updateFromRequest($request->all());
            $designSetting->save();
        } else {
            Log::info("No existing design setting found, creating new one...");
            // Create a new record
            $designSetting = new LetterDesignSetting();
            $designSetting->letter_id = $templateLetterId;
            $designSetting->updateFromRequest($request->all());
            $designSetting->save();
        }
        
        // Also update template styles if provided
        if ($request->has('templateStyles')) {
            $templateStyles = $request->input('templateStyles', []);
            
            // Find the template again if we don't have it
            if (!$template) {
                if (is_numeric($letterId)) {
                    $template = LetterTemplate::find((int) $letterId);
                }
                if (!$template) {
                    $template = LetterTemplate::where('letter_id', $letterId)->first();
                }
            }
            
            if ($template) {
                $template->style_1 = $templateStyles[0] ?? null;
                $template->style_2 = $templateStyles[1] ?? null;
                $template->style_3 = $templateStyles[2] ?? null;
                
                if (Schema::hasColumn('letter_templates', 'styles')) {
                    $template->styles = array_values(array_filter($templateStyles, function ($value) {
                        return $value !== null && $value !== '';
                    }));
                }
                
                $template->save();
                Log::info("Updated template styles");
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Design settings saved successfully',
            'data' => [
                'design_setting' => $designSetting,
                'letter_id_used' => $templateLetterId,
                'was_existing' => $designSetting->wasRecentlyCreated ? false : true
            ]
        ]);
    }
    
    /**
     * Get design settings for a letter.
     */
    public function get($letterId)
    {
        Log::info("Getting design for letterId: {$letterId}");
        
        $letterId = (string) $letterId;
        $templateLetterId = null;
        
        // Find the template first
        $template = null;
        if (is_numeric($letterId)) {
            $template = LetterTemplate::find((int) $letterId);
        }
        
        if (!$template) {
            $template = LetterTemplate::where('letter_id', $letterId)->first();
        }
        
        if (!$template) {
            $template = LetterTemplate::where('key', $letterId)->first();
        }
        
        // Determine the letter_id to use for design settings
        if ($template && !empty($template->letter_id)) {
            $templateLetterId = (string) $template->letter_id;
            Log::info("Using template's letter_id: {$templateLetterId}");
        } else {
            // If no letter_id in template, use the original ID
            $templateLetterId = $letterId;
            Log::info("Using original ID as letter_id: {$templateLetterId}");
        }
        
        // Get the design setting
        $designSetting = LetterDesignSetting::where('letter_id', $templateLetterId)->first();
        
        if (!$designSetting) {
            Log::info("No design setting found for letter_id: {$templateLetterId}");
            return response()->json([
                'success' => true,
                'data' => [
                    'selectedStyle' => 1,
                    'selectedTemplate' => 1,
                    'selected_style' => 1,
                    'selected_template' => 1,
                    'customizations' => [
                        'header' => [],
                        'body' => [],
                        'footer' => [],
                        'date' => []
                    ],
                    'templateStyles' => ['', '', ''],
                    'template_styles' => ['', '', ''],
                    'content' => '',
                    'title' => $template?->title ?? '',
                ]
            ]);
        }
        
        $customizations = $designSetting->customizations ?? [
            'header' => [],
            'body' => [],
            'footer' => [],
            'date' => [],
        ];
        
        // Return the design settings
        return response()->json([
            'success' => true,
            'data' => [
                // Both camelCase and snake_case for compatibility
                'selected_style' => $designSetting->selected_style,
                'selected_template' => $designSetting->selected_template,
                'selectedStyle' => $designSetting->selected_style,
                'selectedTemplate' => $designSetting->selected_template,
                
                // Header settings
                'primary_color' => $designSetting->primary_color,
                'secondary_color' => $designSetting->secondary_color,
                'header_bg' => $designSetting->header_bg,
                'header_border_color' => $designSetting->header_border_color,
                'header_text_color' => $designSetting->header_text_color,
                'company_name' => $designSetting->company_name,
                'company_name_size' => $designSetting->company_name_size,
                'company_tagline' => $designSetting->company_tagline,
                'company_tagline_size' => $designSetting->company_tagline_size,
                'logo_text' => $designSetting->logo_text,
                'logo_size' => $designSetting->logo_size,
                'logo_radius' => $designSetting->logo_radius,
                'header_alignment' => $designSetting->header_alignment,
                'header_padding' => $designSetting->header_padding,
                'header_border_width' => $designSetting->header_border_width,
                'header_style_type' => $designSetting->header_style_type,
                'show_reference' => $designSetting->show_reference,
                'reference_label' => $designSetting->reference_label,
                
                // Body settings
                'body_font_size' => $designSetting->body_font_size,
                'body_line_height' => $designSetting->body_line_height,
                'body_letter_spacing' => $designSetting->body_letter_spacing,
                'body_text_align' => $designSetting->body_text_align,
                'body_color' => $designSetting->body_color,
                'body_padding' => $designSetting->body_padding,
                'font_family' => $designSetting->font_family,
                
                // Footer settings
                'signature_name' => $designSetting->signature_name,
                'signature_title' => $designSetting->signature_title,
                'signature_font_size' => $designSetting->signature_font_size,
                'signature_line_width' => $designSetting->signature_line_width,
                'signature' => $designSetting->signature,
                'footer_text' => $designSetting->footer_text,
                'stamp' => $designSetting->stamp,
                'footer_font_size' => $designSetting->footer_font_size,
                'footer_bg' => $designSetting->footer_bg,
                'footer_padding' => $designSetting->footer_padding,
                'footer_alignment' => $designSetting->footer_alignment,
                'footer_border_color' => $designSetting->footer_border_color,
                'footer_border_width' => $designSetting->footer_border_width,
                'footer_style_type' => $designSetting->footer_style_type,
                'footer_text_color' => $designSetting->footer_text_color,
                
                // Date settings
                'date_position' => $designSetting->date_position,
                'date_color' => $designSetting->date_color,
                'date_size' => $designSetting->date_size,
                'date_style' => $designSetting->date_style,
                'date_margin_top' => $designSetting->date_margin_top,
                'date_margin_bottom' => $designSetting->date_margin_bottom,
                
                // Template styles
                'template_styles' => $designSetting->template_styles ?? ['', '', ''],
                'templateStyles' => $designSetting->template_styles ?? ['', '', ''],
                
                // Customizations
                'customizations' => $customizations,
                
                // Letter content
                'content' => $template?->content ?? '',
                'title' => $template?->title ?? '',
            ]
        ]);
    }
}