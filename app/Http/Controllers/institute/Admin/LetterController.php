<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\LetterTemplate;
use App\Models\Letter;
use App\Models\LetterDesignSetting;
use App\Models\AuthorizedUser;
use App\Models\EmployeeDetails;
use App\Models\AuthorizedUserDocument;
use App\Models\Designations;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class LetterController extends Controller
{
    /**
     * Resolve a letter by either its numeric id, custom letter_id, or key.
     */
    private function resolveLetterModel($id)
    {
        $identifier = trim((string) $id);
        if ($identifier === '') {
            abort(404);
        }

        $letter = null;

        if (is_numeric($identifier)) {
            $letter = Letter::find((int) $identifier);
            if (!$letter) {
                $letter = Letter::where('template_id', (int) $identifier)->latest('id')->first();
            }
        }

        if (!$letter) {
            $letter = Letter::where('template_key', $identifier)->latest('id')->first();
        }

        if ($letter) {
            return $letter;
        }

        $template = null;

        if (is_numeric($identifier)) {
            $template = LetterTemplate::find($identifier);
        }

        if (!$template) {
            $template = LetterTemplate::where('letter_id', $identifier)->first();
        }

        if (!$template) {
            $template = LetterTemplate::where('key', $identifier)->first();
        }

        if ($template) {
            return $template;
        }

        abort(404);
    }

    /**
     * Get all templates for the index page
     */
    public function getTemplates()
    {
        $templates = LetterTemplate::all();
        if ($templates->count() === 0) {
            return response()->json($this->getDefaultTemplates());
        }

        $out = [];
        foreach ($templates as $t) {
            $out[$t->key] = [
                'id' => $t->id,
                'letter_id' => $t->letter_id ?? null,
                'key' => $t->key,
                'title' => $t->title,
                'styles' => $t->styles ?: [],
                'template_1' => $t->style_1 ?? null,
                'template_2' => $t->style_2 ?? null,
                'template_3' => $t->style_3 ?? null,
                'status' => $t->status ?? 'active',
                'document_type' => $t->document_type ?? 'others',
                'isCustom' => $t->is_custom ?? false,
                'created_at' => $t->created_at
            ];
        }

        return response()->json($out);
    }

    /**
     * Get a specific template by key
     */
    public function getTemplate($key)
    {
        $template = LetterTemplate::where('key', $key)->first();
        
        if (!$template) {
            $defaults = $this->getDefaultTemplates();
            if (isset($defaults[$key])) {
                return response()->json($defaults[$key]);
            }
            return response()->json(['error' => 'Template not found'], 404);
        }

        return response()->json([
            'id' => $template->id,
            'letter_id' => $template->letter_id ?? null,
            'key' => $template->key,
            'title' => $template->title,
            'styles' => $template->styles ?: [],
            'template_1' => $template->style_1 ?? null,
            'template_2' => $template->style_2 ?? null,
            'template_3' => $template->style_3 ?? null,
            'document_type' => $template->document_type ?? 'others',
            'status' => $template->status ?? 'active'
        ]);
    }

    /**
     * Resolve template styles from either a template record or a letter record.
     */
    private function resolveTemplateStyles($model): array
    {
        if ($model instanceof LetterTemplate) {
            $styles = $model->styles ?? [];
            if (is_array($styles) && !empty($styles)) {
                return array_values(array_filter(array_map(function ($value) {
                    return is_string($value) ? trim($value) : $value;
                }, $styles), function ($value) {
                    return $value !== null && $value !== '';
                }));
            }

            return array_values(array_filter(array_map(function ($index) use ($model) {
                $value = $model->{'style_' . $index} ?? null;
                return is_string($value) ? trim($value) : $value;
            }, [1, 2, 3]), function ($value) {
                return $value !== null && $value !== '';
            }));
        }

        if ($model instanceof Letter) {
            $styles = $model->styles ?? [];
            if (is_array($styles) && !empty($styles)) {
                return array_values(array_filter(array_map(function ($value) {
                    return is_string($value) ? trim($value) : $value;
                }, $styles), function ($value) {
                    return $value !== null && $value !== '';
                }));
            }

            $template = $model->templateById()->first() ?? $model->template()->first();
            if ($template) {
                return $this->resolveTemplateStyles($template);
            }
        }

        return [];
    }

    /**
     * Get letter data for the builder with all 3 templates from style_1, style_2, style_3
     */
public function getLetter($id)
{
    Log::info("Fetching template with ID: {$id}");
    
    $template = null;
    $identifier = trim((string) $id);
    
    // ONLY look in letter_templates table - NEVER in letters table
    if (is_numeric($identifier)) {
        $template = LetterTemplate::find((int) $identifier);
    }
    
    if (!$template) {
        $template = LetterTemplate::where('letter_id', $identifier)->first();
    }
    
    if (!$template) {
        $template = LetterTemplate::where('key', $identifier)->first();
    }
    
    if (!$template) {
        Log::warning("No template found for ID: {$id}");
        return response()->json(['error' => 'Template not found'], 404);
    }
    
    // Get institute ID and fetch authorized users with signature paths
    $instituteId = auth()->user()->institute_id ?? null;
    $authorizedUsers = [];
    
    if ($instituteId) {
        $authorizedUsers = AuthorizedUser::where('institute_id', $instituteId)
            ->with('documents')
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                // Generate signature URL from signature_path
                $signatureUrl = null;
                if (!empty($user->signature_path)) {
                    if (filter_var($user->signature_path, FILTER_VALIDATE_URL)) {
                        $signatureUrl = $user->signature_path;
                    } else {
                        $signatureUrl = Storage::disk('public')->url($user->signature_path);
                    }
                }
                
                // Check documents as fallback
                $document = $user->documents;
                if (!$signatureUrl && $document && !empty($document->signature_path)) {
                    if (filter_var($document->signature_path, FILTER_VALIDATE_URL)) {
                        $signatureUrl = $document->signature_path;
                    } else {
                        $signatureUrl = Storage::disk('public')->url($document->signature_path);
                    }
                }
                
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'designation' => $user->designation ?? '',
                    'signature_url' => $signatureUrl,
                ];
            })
            ->toArray();
    }
    
    // Rest of your existing code...
    $isRegenerate = request()->input('regenerate', false);
    $employeeId = request()->input('employee_id', null);
    $referenceId = null;
    
    if (!$isRegenerate && $employeeId) {
        $letterRecord = null;
        
        if ($template->letter_id) {
            $letterRecord = Letter::where('letter_id', $template->letter_id)
                ->where('employee_id', $employeeId)
                ->where('status', '!=', 'cancelled')
                ->first();
            if ($letterRecord && $letterRecord->reference_id) {
                $referenceId = $letterRecord->reference_id;
                Log::info("Found reference_id for employee {$employeeId} by letter_id: " . $referenceId);
            }
        }
        
        if (!$referenceId && $template->key && $employeeId) {
            $letterRecord = Letter::where('template_key', $template->key)
                ->where('employee_id', $employeeId)
                ->where('status', '!=', 'cancelled')
                ->orderBy('id', 'desc')
                ->first();
            if ($letterRecord && $letterRecord->reference_id) {
                $referenceId = $letterRecord->reference_id;
                Log::info("Found reference_id for employee {$employeeId} by template_key: " . $referenceId);
            }
        }
        
        if (!$referenceId) {
            $companyName = request()->input('company', null);
            $referenceId = $this->generateReferenceId($employeeId, $companyName);
            Log::info("No existing reference_id found. Generated NEW reference_id for employee {$employeeId}: " . $referenceId);
        }
    } else {
        Log::info("No employee_id provided, generating temporary reference_id");
        $referenceId = $this->generateReferenceId('TEMP', null);
    }
    
    // Get the styles directly from the template
    $style1 = $template->style_1 ?? '';
    $style2 = $template->style_2 ?? '';
    $style3 = $template->style_3 ?? '';
    
    $templateStyles = [
        !empty($style1) ? $style1 : '',
        !empty($style2) ? $style2 : '',
        !empty($style3) ? $style3 : ''
    ];
    
    // Get design setting with signature data
    $designSetting = null;
    $letterIdForDesign = $template->letter_id ?? null;
    
    if ($letterIdForDesign) {
        $designSetting = LetterDesignSetting::where('letter_id', $letterIdForDesign)->first();
    }
    
    if (!$designSetting && $letterIdForDesign) {
        $designSetting = LetterDesignSetting::where('letter_id', (string) $template->id)->first();
    }
    
    // Get reference label from design settings
    $referenceLabel = 'Ref:';
    if ($designSetting && isset($designSetting->reference_label)) {
        $referenceLabel = $designSetting->reference_label;
    } elseif ($designSetting && isset($designSetting->customizations['header']['referenceLabel'])) {
        $referenceLabel = $designSetting->customizations['header']['referenceLabel'] ?? 'Ref:';
    }
    
    // Prepare the response
    $response = [
        'id' => $template->id,
        'key' => $template->key,
        'title' => $template->title ?? 'Letter Template',
        'content' => $template->content ?? '',
        'reference_id' => $referenceId,
        'letter_id' => $template->letter_id,
        'styles' => $templateStyles,
        'style_1' => $style1,
        'style_2' => $style2,
        'style_3' => $style3,
        'regenerate' => $isRegenerate,
        'reference_label' => $referenceLabel,
        // Add authorized users with signature URLs
        'authorized_users' => $authorizedUsers,
    ];
    
    // Add design settings if found
    if ($designSetting) {
        $response['design'] = [
            'selectedStyle' => $designSetting->selected_style ?? 1,
            'selectedTemplate' => $designSetting->selected_template ?? 1,
            'customizations' => $designSetting->customizations ?? [],
            'templateStyles' => $designSetting->template_styles ?? $templateStyles,
            'signature' => $designSetting->signature ?? null,
            'stamp' => $designSetting->stamp ?? null,
            'reference_id' => $referenceId,
            'reference_label' => $referenceLabel,
        ];
        
        Log::info("Design settings loaded successfully");
    } else {
        $response['design'] = [
            'selectedStyle' => 1,
            'selectedTemplate' => 1,
            'customizations' => [
                'header' => [
                    'referenceLabel' => $referenceLabel,
                ],
                'body' => [],
                'footer' => [],
                'date' => []
            ],
            'templateStyles' => $templateStyles,
            'signature' => null,
            'stamp' => null,
            'reference_id' => $referenceId,
            'reference_label' => $referenceLabel,
        ];
        Log::info("No design settings found, using defaults");
    }
    
    return response()->json($response);
}

    /**
     * Return a letter as JSON for the builder/viewer UI.
     */
    public function show($id)
    {
        return $this->getLetter($id);   
    }

    /**
     * Show the view page for a letter
     */
public function view($id)
{
    // Find the template using letter_id or id  
    $template = null;  
    
    if (is_numeric($id)) {
        $template = LetterTemplate::find((int) $id);     
    }
    
    if (!$template) {
        $template = LetterTemplate::where('letter_id', (string) $id)->first();      
    }
    
    if (!$template) {   
        $template = LetterTemplate::where('key', (string) $id)->first();  
    }
    
    if (!$template) { 
        abort(404, 'Template not found'); 
    }
    
    $letter = $template;
    
    $instituteId = auth()->user()->institute_id ?? null;

    $authorizedUsers = collect();
    $designations = collect();

    if ($instituteId) {
        $instituteStampDocument = AuthorizedUserDocument::where('institute_id', $instituteId)
            ->whereNotNull('stamp_path')
            ->where('stamp_path', '!=', '')
            ->orderByDesc('id')
            ->first();

        $instituteStampUrl = $instituteStampDocument?->stamp_url;

        $authorizedUsers = AuthorizedUser::where('institute_id', $instituteId)
            ->with('documents')
            ->orderBy('name')
            ->get()
            ->map(function ($user) use ($instituteStampUrl) {
                $document = $user->documents;
                $designation = trim((string) ($user->designation ?? ''));
                $normalized = strtolower($designation);
                $roleKey = 'authorized_person';
                $roleLabel = 'Authorized Person';

                if (Str::contains($normalized, 'hr')) {
                    $roleKey = 'hr';
                    $roleLabel = 'HR';
                } elseif (Str::contains($normalized, 'manager')) {
                    $roleKey = 'manager';
                    $roleLabel = 'Manager';
                } elseif (Str::contains($normalized, 'authorized') || Str::contains($normalized, 'authorised')) {
                    $roleKey = 'authorized_person';
                    $roleLabel = 'Authorized Person';
                }

                // ============ FETCH SIGNATURE PATH FROM DATABASE ============
                $signaturePath = $user->signature_path; // Gets: institutes/FMREGE585KIY/authorized_users/6/signature/signature_1785496092.png
                
                // ============ GENERATE THE CORRECT URL ============
                $signatureUrl = null;
                if ($signaturePath) {
                    // Use asset() helper - this works for files in public/ folder
                    $signatureUrl = asset($signaturePath);
                    // Creates: http://127.0.0.1:8000/institutes/FMREGE585KIY/authorized_users/6/signature/signature_1785496092.png
                }

                // If no signature from user, check documents as fallback
                if (!$signatureUrl && $document && !empty($document->signature_path)) {
                    $signatureUrl = asset($document->signature_path);
                }

                // Generate stamp URL
                $stampUrl = null;
                if ($document && !empty($document->stamp_path)) {
                    $stampUrl = asset($document->stamp_path);
                }

                if (!$stampUrl) {
                    $stampUrl = $instituteStampUrl;
                }

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'designation' => $designation,
                    'role_key' => $roleKey,
                    'role_label' => $roleLabel,
                    'option_label' => trim($user->name . ' - ' . ($designation ?: $roleLabel)),
                    'signature_url' => $signatureUrl,
                    'stamp_url' => $stampUrl,
                    'institute_stamp_url' => $instituteStampUrl,
                    'signature_path' => $signaturePath, // Keep for debugging
                ];
            });

        $designations = Designations::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->orderBy('designations')
            ->get()
            ->map(function ($designation) {
                return [
                    'id' => $designation->designation_id,
                    'name' => $designation->designations,
                ];
            });
    }

    return view('instituteAdmin.VisitorManagement.letter-view', compact('letter', 'authorizedUsers', 'designations'));
}

    /**
     * Show a saved employee letter (from `letters` table)
     */
    public function employeeLetterView($id)
    {
        $letter = Letter::with(['templateById', 'template'])->findOrFail($id);

        $designSetting = null;
        $resolvedLetterId = null;

        if ($letter->templateById && !empty($letter->templateById->letter_id)) {
            $resolvedLetterId = (string) $letter->templateById->letter_id;
        } elseif ($letter->template && !empty($letter->template->letter_id)) {
            $resolvedLetterId = (string) $letter->template->letter_id;
        } elseif (!empty($letter->template_key)) {
            $template = LetterTemplate::where('key', $letter->template_key)->first();
            if ($template && !empty($template->letter_id)) {
                $resolvedLetterId = (string) $template->letter_id;
            }
        }

        if ($resolvedLetterId) {
            $designSetting = LetterDesignSetting::where('letter_id', $resolvedLetterId)->first();
        }

        if (!$designSetting) {
            $designSetting = LetterDesignSetting::where('letter_id', (string) $letter->id)->first();
        }

        return view('instituteAdmin.EmployeeFiles.emp-letter-view', compact('letter', 'designSetting'));
    }

    /**
     * Show the edit page for a letter
     */
    public function edit($id)
    {
        $letter = $this->resolveLetterModel($id);
        return view('instituteAdmin.VisitorManagement.letter-edit', compact('letter'));
    }

    /**
     * Store a newly created letter template
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'key' => 'required|string|unique:letter_templates,key',
                'title' => 'required|string',
                'style_1' => 'required|string',
                'style_2' => 'nullable|string',
                'style_3' => 'nullable|string',
                'document_type' => 'sometimes|nullable|string',
                'status' => 'sometimes|in:active,draft'
            ]);
    
            $tpl = new LetterTemplate();
            $tpl->key = $data['key'];
            $tpl->title = $data['title'];
            $tpl->status = $data['status'] ?? 'active';
            $tpl->document_type = $data['document_type'] ?? 'others';
            $tpl->is_custom = true;
    
            $tpl->style_1 = $data['style_1'];
            $tpl->style_2 = $data['style_2'] ?? '';
            $tpl->style_3 = $data['style_3'] ?? '';
    
            $tpl->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Letter template saved successfully',
                'data' => $tpl
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified letter template
     */
    public function update(Request $request, $id)
    {
        $letter = LetterTemplate::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'styles' => 'sometimes|array|min:1',
            'styles.*' => 'string|nullable',
            'status' => 'sometimes|in:active,draft'
        ]);

        if (isset($validated['title'])) {
            $letter->title = $validated['title'];
        }
        
        if (isset($validated['status'])) {
            $letter->status = $validated['status'];
        }
        
        if (isset($validated['styles'])) {
            $letter->style_1 = $validated['styles'][0] ?? null;
            $letter->style_2 = $validated['styles'][1] ?? null;
            $letter->style_3 = $validated['styles'][2] ?? null;
        }
        
        $letter->save();

        return response()->json([
            'id' => $letter->id,
            'key' => $letter->key,
            'title' => $letter->title,
            'styles' => [$letter->style_1, $letter->style_2, $letter->style_3],
            'status' => $letter->status
        ]);
    }

    /**
     * Remove the specified letter template
     */
    public function destroy($id)
    {
        $letter = LetterTemplate::findOrFail($id);
        $letter->delete();
        
        return response()->json(['deleted' => true]);
    }

    /**
     * Preview a letter with variables replaced
     */
    public function preview(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'variables' => 'sometimes|array'
        ]);

        $content = $validated['content'];
        $variables = $validated['variables'] ?? [];

        foreach ($variables as $key => $value) {
            $content = str_replace("[[{$key}]]", $value, $content);
        }

        return response()->json([
            'preview' => $content
        ]);
    }

    /**
     * Normalize design settings payloads from the builder to the DB column format.
     */
    private function normalizeDesignSettingsPayload(array $designSettings = []): array
    {
        $customizations = $designSettings['customizations'] ?? [];
        $header = is_array($customizations['header'] ?? null) ? $customizations['header'] : [];
        $body = is_array($customizations['body'] ?? null) ? $customizations['body'] : [];
        $footer = is_array($customizations['footer'] ?? null) ? $customizations['footer'] : [];
        $date = is_array($customizations['date'] ?? null) ? $customizations['date'] : [];

        return [
            'selectedStyle' => $designSettings['selectedStyle'] ?? $designSettings['selected_style'] ?? 1,
            'selectedTemplate' => $designSettings['selectedTemplate'] ?? $designSettings['selected_template'] ?? 1,
            'primaryColor' => $designSettings['primaryColor'] ?? $header['primaryColor'] ?? $designSettings['primary_color'] ?? '#3b82f6',
            'secondaryColor' => $designSettings['secondaryColor'] ?? $header['secondaryColor'] ?? $designSettings['secondary_color'] ?? '#2563eb',
            'headerBg' => $designSettings['headerBg'] ?? $header['headerBg'] ?? $designSettings['header_bg'] ?? '#ffffff',
            'headerBorderColor' => $designSettings['headerBorderColor'] ?? $header['headerBorderColor'] ?? $designSettings['header_border_color'] ?? '#3b82f6',
            'headerTextColor' => $designSettings['headerTextColor'] ?? $header['headerTextColor'] ?? $designSettings['header_text_color'] ?? '#1f2937',
            'companyName' => $designSettings['companyName'] ?? $header['companyName'] ?? $designSettings['company_name'] ?? '',
            'companyNameSize' => $designSettings['companyNameSize'] ?? $header['companyNameSize'] ?? $designSettings['company_name_size'] ?? 16,
            'companyTagline' => $designSettings['companyTagline'] ?? $header['companyTagline'] ?? $designSettings['company_tagline'] ?? '',
            'companyTaglineSize' => $designSettings['companyTaglineSize'] ?? $header['companyTaglineSize'] ?? $designSettings['company_tagline_size'] ?? 12,
            'logoText' => $designSettings['logoText'] ?? $header['logoText'] ?? $designSettings['logo_text'] ?? '',
            'logoSize' => $designSettings['logoSize'] ?? $header['logoSize'] ?? $designSettings['logo_size'] ?? 50,
            'logoRadius' => $designSettings['logoRadius'] ?? $header['logoRadius'] ?? $designSettings['logo_radius'] ?? 8,
            'headerAlignment' => $designSettings['headerAlignment'] ?? $header['headerAlignment'] ?? $designSettings['header_alignment'] ?? 'between',
            'headerPadding' => $designSettings['headerPadding'] ?? $header['headerPadding'] ?? $designSettings['header_padding'] ?? 30,
            'headerBorderWidth' => $designSettings['headerBorderWidth'] ?? $header['headerBorderWidth'] ?? $designSettings['header_border_width'] ?? 2,
            'headerStyleType' => $designSettings['headerStyleType'] ?? $header['headerStyleType'] ?? $designSettings['header_style_type'] ?? 'default',
            'showReference' => $designSettings['showReference'] ?? $header['showReference'] ?? $designSettings['show_reference'] ?? 'show',
            'referenceLabel' => $designSettings['referenceLabel'] ?? $header['referenceLabel'] ?? $designSettings['reference_label'] ?? 'Ref:',
            'bodyFontSize' => $designSettings['bodyFontSize'] ?? $body['bodyFontSize'] ?? $designSettings['body_font_size'] ?? 14,
            'bodyLineHeight' => $designSettings['bodyLineHeight'] ?? $body['bodyLineHeight'] ?? $designSettings['body_line_height'] ?? 1.9,
            'bodyLetterSpacing' => $designSettings['bodyLetterSpacing'] ?? $body['bodyLetterSpacing'] ?? $designSettings['body_letter_spacing'] ?? 0,
            'bodyTextAlign' => $designSettings['bodyTextAlign'] ?? $body['bodyTextAlign'] ?? $designSettings['body_text_align'] ?? 'justify',
            'bodyColor' => $designSettings['bodyColor'] ?? $body['bodyColor'] ?? $designSettings['body_color'] ?? '#1f2937',
            'bodyPadding' => $designSettings['bodyPadding'] ?? $body['bodyPadding'] ?? $body['padding'] ?? $designSettings['body_padding'] ?? $designSettings['padding'] ?? 40,
            'fontFamily' => $designSettings['fontFamily'] ?? $body['fontFamily'] ?? $designSettings['font_family'] ?? 'Georgia, serif',
            'signatureName' => $designSettings['signatureName'] ?? $footer['signatureName'] ?? $designSettings['signature_name'] ?? '',
            'signatureTitle' => $designSettings['signatureTitle'] ?? $footer['signatureTitle'] ?? $designSettings['signature_title'] ?? '',
            'signatureFontSize' => $designSettings['signatureFontSize'] ?? $footer['signatureFontSize'] ?? $designSettings['signature_font_size'] ?? 13,
            'signatureLineWidth' => $designSettings['signatureLineWidth'] ?? $footer['signatureLineWidth'] ?? $designSettings['signature_line_width'] ?? 200,
            'footerText' => $designSettings['footerText'] ?? $footer['footerText'] ?? $designSettings['footer_text'] ?? '',
            'footerFontSize' => $designSettings['footerFontSize'] ?? $footer['footerFontSize'] ?? $designSettings['footer_font_size'] ?? 12,
            'footerBg' => $designSettings['footerBg'] ?? $footer['footerBg'] ?? $designSettings['footer_bg'] ?? '#ffffff',
            'footerPadding' => $designSettings['footerPadding'] ?? $footer['footerPadding'] ?? $designSettings['footer_padding'] ?? 20,
            'footerAlignment' => $designSettings['footerAlignment'] ?? $footer['footerAlignment'] ?? $designSettings['footer_alignment'] ?? 'between',
            'footerBorderColor' => $designSettings['footerBorderColor'] ?? $footer['footerBorderColor'] ?? $designSettings['footer_border_color'] ?? '#3b82f6',
            'footerBorderWidth' => $designSettings['footerBorderWidth'] ?? $footer['footerBorderWidth'] ?? $designSettings['footer_border_width'] ?? 2,
            'footerStyleType' => $designSettings['footerStyleType'] ?? $footer['footerStyleType'] ?? $designSettings['footer_style_type'] ?? 'default',
            'footerTextColor' => $designSettings['footerTextColor'] ?? $footer['footerTextColor'] ?? $designSettings['footer_text_color'] ?? '#6b7280',
            'datePosition' => $designSettings['datePosition'] ?? $date['datePosition'] ?? $designSettings['date_position'] ?? 'center',
            'dateColor' => $designSettings['dateColor'] ?? $date['dateColor'] ?? $designSettings['date_color'] ?? '#6b7280',
            'dateSize' => $designSettings['dateSize'] ?? $date['dateSize'] ?? $designSettings['date_size'] ?? 14,
            'dateStyle' => $designSettings['dateStyle'] ?? $date['dateStyle'] ?? $designSettings['date_style'] ?? 'normal',
            'dateMarginTop' => $designSettings['dateMarginTop'] ?? $date['dateMarginTop'] ?? $designSettings['date_margin_top'] ?? 20,
            'dateMarginBottom' => $designSettings['dateMarginBottom'] ?? $date['dateMarginBottom'] ?? $designSettings['date_margin_bottom'] ?? 30,
            'templateStyles' => $designSettings['templateStyles'] ?? $designSettings['template_styles'] ?? [],
        ];
    }

    /**
     * Save a custom letter
     */
    public function save(Request $request)
    {
        try {
            $data = $request->validate([
                'key' => 'required|string|unique:letter_templates,key',
                'title' => 'required|string',
                'style_1' => 'required|string',
                'style_2' => 'nullable|string',
                'style_3' => 'nullable|string',
                'document_type' => 'required|string',
                'official_documenttype_id' => 'required|string',
                'letter_id' => 'nullable|string',
            ]);
    
            if (empty($data['letter_id'])) {
                $data['letter_id'] = $this->generateLetterId();
            }
    
            $tpl = new LetterTemplate();
            $tpl->institute_id = auth()->user()->institute_id ?? null;
            $tpl->key = $data['key'];
            $tpl->default_style = 1;
            $tpl->document_type = $data['document_type'];
            $tpl->official_documenttype_id = $data['official_documenttype_id'];
            $tpl->letter_id = $data['letter_id'];
            $tpl->title = $data['title'];
            $tpl->style_1 = $data['style_1'] ?? '';
            $tpl->style_2 = $data['style_2'] ?? '';
            $tpl->style_3 = $data['style_3'] ?? '';
            $tpl->style_labels = json_encode(['Template 1', 'Template 2', 'Template 3']);
    
            $tpl->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Letter template created successfully',
                'data' => $tpl
            ], 201);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed'
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating letter template: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create letter template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a generated letter instance (from the builder) into `letters` table
     * UPDATED: Added regenerate functionality
     */
    public function storeLetter(Request $request)
    {
        \Log::info('storeLetter called with data:', $request->all());
        
        try {
            $data = $request->validate([
                'employee_id' => 'required|string',
                'letter_id' => 'sometimes|nullable|string',
                'template_key' => 'required|string',
                'title' => 'required|string',
                'content' => 'required|string',
                'status' => 'sometimes|in:draft,completed,sent,cancelled',
                'document_type' => 'sometimes|string|nullable',
                'employee_data' => 'sometimes|array',
                'design_settings' => 'sometimes|array',
                'reference_id' => 'sometimes|nullable|string',
                'regenerate' => 'sometimes|boolean',
                'regenerate_letter_id' => 'sometimes|nullable|string'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Letter validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        $templateId = null;
        if (isset($data['template_key']) && is_numeric($data['template_key'])) {
            $templateId = (int) $data['template_key'];
        } else {
            $tpl = LetterTemplate::where('key', $data['template_key'])->first();
            if ($tpl) $templateId = $tpl->id;
        }

        $companyName = $data['employee_data']['company'] ?? 
                       $data['employee_data']['company_name'] ?? null;

        $isRegenerate = $request->input('regenerate', false);

        // ============ HANDLE REGENERATION - ALWAYS CREATE NEW ============
        if ($isRegenerate) {
            $newReferenceId = $this->generateReferenceId($data['employee_id'], $companyName);
            
            $letterData = [
                'employee_id' => $data['employee_id'],
                'template_key' => $data['template_key'],
                'title' => $data['title'] . ' (Regenerated)',
                'content' => $data['content'],
                'status' => 'draft',
                'reference_id' => $newReferenceId,
                'letter_id' => $data['template_key'] . '-' . $data['employee_id'] . '-' . time()
            ];
            
            if (Schema::hasColumn('letters', 'template_id')) {
                $letterData['template_id'] = $templateId;
            }
            
            $letter = Letter::create($letterData);
            
            \Log::info('REGENERATED: Created new letter with NEW reference ID: ' . $newReferenceId, [
                'employee_id' => $data['employee_id'],
                'old_letter_id' => $request->input('regenerate_letter_id'),
                'new_letter_id' => $letter->id,
                'new_reference_id' => $newReferenceId
            ]);
            
            $isRegenerated = true;
            
        } else {
            // ============ NORMAL SAVE ============
            $letterData = [
                'employee_id' => $data['employee_id'],
                'template_key' => $data['template_key'],
                'title' => $data['title'],
                'content' => $data['content'],
                'status' => $data['status'] ?? 'draft'
            ];

            if (Schema::hasColumn('letters', 'template_id')) {
                $letterData['template_id'] = $templateId;
            }

            if (Schema::hasColumn('letters', 'reference_id')) {
                if (!empty($data['reference_id'])) {
                    $letterData['reference_id'] = $data['reference_id'];
                } else {
                    $letterData['reference_id'] = $this->generateReferenceId($data['employee_id'], $companyName);
                }
            }

            if (Schema::hasColumn('letters', 'letter_id')) {
                if (!empty($data['letter_id'])) {
                    $letterData['letter_id'] = $data['letter_id'];
                } else {
                    $letterData['letter_id'] = $data['template_key'] . '-' . $data['employee_id'] . '-' . time();
                }
            }

            $letter = null;

            // Find existing letter - MUST match employee_id
            if (!empty($data['letter_id']) && Schema::hasColumn('letters', 'letter_id')) {
                $letter = Letter::where('letter_id', $data['letter_id'])
                    ->where('employee_id', $data['employee_id'])
                    ->where('status', '!=', 'cancelled')
                    ->first();
            }

            if (!$letter && !empty($data['reference_id']) && Schema::hasColumn('letters', 'reference_id')) {
                $letter = Letter::where('reference_id', $data['reference_id'])
                    ->where('employee_id', $data['employee_id'])
                    ->where('status', '!=', 'cancelled')
                    ->first();
            }

            if (!$letter) {
                $letter = Letter::where('employee_id', $data['employee_id'])
                    ->where('template_key', $data['template_key'])
                    ->where('title', $data['title'])
                    ->where('status', '!=', 'cancelled')
                    ->orderByDesc('id')
                    ->first();
            }

            if ($letter) {
                $letter->fill($letterData);
                $letter->save();
            } else {
                $letter = Letter::create($letterData);
            }
            
            $isRegenerated = false;
        }

        // ============ SAVE DESIGN SETTINGS ============
        if ($request->has('design_settings') && Schema::hasTable('letter_design_settings')) {
            try {
                $designSettings = $this->normalizeDesignSettingsPayload($request->input('design_settings', []));
                $letterIdentifier = $letter->letter_id ?? (string) $letter->id;

                LetterDesignSetting::updateOrCreate(
                    ['letter_id' => $letterIdentifier],
                    [
                        'selected_style' => $designSettings['selectedStyle'] ?? 1,
                        'selected_template' => $designSettings['selectedTemplate'] ?? 1,
                        'primary_color' => $designSettings['primaryColor'] ?? '#3b82f6',
                        'secondary_color' => $designSettings['secondaryColor'] ?? '#2563eb',
                        'header_bg' => $designSettings['headerBg'] ?? '#ffffff',
                        'header_border_color' => $designSettings['headerBorderColor'] ?? '#3b82f6',
                        'header_text_color' => $designSettings['headerTextColor'] ?? '#1f2937',
                        'company_name' => $designSettings['companyName'] ?? '',
                        'company_name_size' => $designSettings['companyNameSize'] ?? 16,
                        'company_tagline' => $designSettings['companyTagline'] ?? '',
                        'company_tagline_size' => $designSettings['companyTaglineSize'] ?? 12,
                        'logo_text' => $designSettings['logoText'] ?? '',
                        'logo_size' => $designSettings['logoSize'] ?? 50,
                        'logo_radius' => $designSettings['logoRadius'] ?? 8,
                        'header_alignment' => $designSettings['headerAlignment'] ?? 'between',
                        'header_padding' => $designSettings['headerPadding'] ?? 30,
                        'header_border_width' => $designSettings['headerBorderWidth'] ?? 2,
                        'header_style_type' => $designSettings['headerStyleType'] ?? 'default',
                        'show_reference' => $designSettings['showReference'] ?? 'show',
                        'reference_label' => $designSettings['referenceLabel'] ?? 'Ref:',
                        'body_font_size' => $designSettings['bodyFontSize'] ?? 14,
                        'body_line_height' => $designSettings['bodyLineHeight'] ?? 1.9,
                        'body_letter_spacing' => $designSettings['bodyLetterSpacing'] ?? 0,
                        'body_text_align' => $designSettings['bodyTextAlign'] ?? 'justify',
                        'body_color' => $designSettings['bodyColor'] ?? '#1f2937',
                        'body_padding' => $designSettings['bodyPadding'] ?? 40,
                        'font_family' => $designSettings['fontFamily'] ?? 'Georgia, serif',
                        'signature_name' => $designSettings['signatureName'] ?? '',
                        'signature_title' => $designSettings['signatureTitle'] ?? '',
                        'signature_font_size' => $designSettings['signatureFontSize'] ?? 13,
                        'signature_line_width' => $designSettings['signatureLineWidth'] ?? 200,
                        'footer_text' => $designSettings['footerText'] ?? '',
                        'footer_font_size' => $designSettings['footerFontSize'] ?? 12,
                        'footer_bg' => $designSettings['footerBg'] ?? '#ffffff',
                        'footer_padding' => $designSettings['footerPadding'] ?? 20,
                        'footer_alignment' => $designSettings['footerAlignment'] ?? 'between',
                        'footer_border_color' => $designSettings['footerBorderColor'] ?? '#3b82f6',
                        'footer_border_width' => $designSettings['footerBorderWidth'] ?? 2,
                        'footer_style_type' => $designSettings['footerStyleType'] ?? 'default',
                        'footer_text_color' => $designSettings['footerTextColor'] ?? '#6b7280',
                        'date_position' => $designSettings['datePosition'] ?? 'center',
                        'date_color' => $designSettings['dateColor'] ?? '#6b7280',
                        'date_size' => $designSettings['dateSize'] ?? 14,
                        'date_style' => $designSettings['dateStyle'] ?? 'normal',
                        'date_margin_top' => $designSettings['dateMarginTop'] ?? 20,
                        'date_margin_bottom' => $designSettings['dateMarginBottom'] ?? 30,
                        'template_styles' => is_array($designSettings['templateStyles'] ?? null) ? $designSettings['templateStyles'] : [],
                    ]
                );
                \Log::info('Design settings saved for letter: ' . $letterIdentifier);
            } catch (\Exception $e) {
                \Log::error('Design settings save failed: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'data' => $letter,
            'id' => $letter->id,
            'letter_id' => $letter->letter_id ?? null,
            'reference_id' => $letter->reference_id ?? null,
            'regenerate' => $isRegenerated,
            'message' => $isRegenerated ? 'Letter regenerated successfully with new reference ID: ' . ($letter->reference_id ?? '') : 'Letter saved successfully'
        ], 201);
    }

    /**
     * CANCEL LETTER - Change status to cancelled
     * NEW METHOD
     */
    public function cancelLetter($id)
    {
        try {
            $letter = Letter::findOrFail($id);
            
            // Only allow cancellation if status is 'completed' or 'sent'
            if (!in_array($letter->status, ['completed', 'sent'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'This letter cannot be cancelled because it is in ' . $letter->status . ' status.'
                ], 400);
            }
            
            $letter->status = 'cancelled';
            $letter->save();
            
            \Log::info('Letter cancelled', [
                'letter_id' => $letter->id,
                'employee_id' => $letter->employee_id,
                'reference_id' => $letter->reference_id,
                'cancelled_by' => auth()->user()->id ?? null
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Letter cancelled successfully. You can now regenerate it with a new reference ID.',
                'data' => [
                    'id' => $letter->id,
                    'status' => $letter->status,
                    'reference_id' => $letter->reference_id
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to cancel letter: ' . $e->getMessage(), [
                'letter_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel letter: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save design settings for a letter
     */
  public function saveDesign(Request $request, $letterId)
{
    Log::info("Saving design for letterId: {$letterId}");
    
    $letterId = (string) $letterId;
    $template = null;
    $templateLetterId = null;
    
    if (is_numeric($letterId)) {
        $template = LetterTemplate::find((int) $letterId);
    }
    
    if (!$template) {
        $template = LetterTemplate::where('letter_id', $letterId)->first();
    }
    
    if (!$template) {
        $template = LetterTemplate::where('key', $letterId)->first();
    }
    
    if (!$template) {
        Log::error("Template not found for ID: {$letterId}");
        return response()->json([
            'success' => false,
            'message' => 'Template not found'
        ], 404);
    }
    
    if (!empty($template->letter_id)) {
        $templateLetterId = (string) $template->letter_id;
        Log::info("Using template's letter_id for design save: {$templateLetterId}");
    } else {
        $templateLetterId = (string) $template->id;
        Log::info("No letter_id found, using template ID for design save: {$templateLetterId}");
    }
    
    $designSetting = LetterDesignSetting::where('letter_id', $templateLetterId)->first();
    
    if (!$designSetting) {
        $designSetting = new LetterDesignSetting();
        $designSetting->letter_id = $templateLetterId;
        Log::info("Creating new design setting for letter_id: {$templateLetterId}");
    } else {
        Log::info("Updating existing design setting for letter_id: {$templateLetterId}");
    }
    
    // Get the signature data from request
    $signature = $request->input('signature');
    $stamp = $request->input('stamp');
    
    // If signature is provided, save it
    if ($signature) {
        $designSetting->signature = $signature;
    }
    
    if ($stamp) {
        $designSetting->stamp = $stamp;
    }
    
    $designSetting->updateFromRequest($request->all());
    $designSetting->save();
    
    if ($request->has('templateStyles')) {
        $templateStyles = $request->input('templateStyles', []);
        
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
    
    return response()->json([
        'success' => true,
        'message' => 'Design settings saved successfully',
        'data' => [
            'design_setting' => $designSetting,
            'letter_id_used' => $templateLetterId,
            'template_id' => $template->id,
            'template_letter_id' => $template->letter_id
        ]
    ]);
}
    
    /**
     * Get design settings for a letter
     */
    public function getDesign($letterId)
    {
        $letterId = (string) $letterId;
        $designSetting = LetterDesignSetting::where('letter_id', $letterId)->first();

        if (!$designSetting) {
            return response()->json([
                'success' => true,
                'data' => [
                    'selectedStyle' => 1,
                    'selectedTemplate' => 1,
                    'customizations' => [
                        'header' => [],
                        'body' => [],
                        'footer' => [],
                        'date' => []
                    ],
                    'templateStyles' => ['']
                ]
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'selectedStyle' => $designSetting->selected_style,
                'selectedTemplate' => $designSetting->selected_template,
                'customizations' => $designSetting->customizations,
                'templateStyles' => $designSetting->template_styles ?? [''],
            ]
        ]);
    }

    /**
     * Generate a reference identifier for a letter.
     */
    private function generateReferenceId(?string $employeeId = null, ?string $companyName = null): string
    {
        $employeeToken = '';
        if (!empty($employeeId) && $employeeId !== 'TEMP') {
            $employeeToken = preg_replace('/[^A-Za-z0-9]/', '', (string) $employeeId);
            $employeeToken = Str::upper(Str::limit($employeeToken, 3, ''));
        }

        $refToken = '';
        if (!empty($companyName)) {
            $refToken = Str::slug($companyName);
            $refToken = Str::upper(Str::limit(str_replace('-', '', $refToken), 3, ''));
        }

        $randomNumber = random_int(100000, 999999);

        return sprintf('%s/%s/%d', $employeeToken ?: 'EMP', $refToken ?: 'REF', $randomNumber);
    }

    /**
     * Get all letters (for the index page)
     */
    public function index()
    {
        $templates = LetterTemplate::all();
        return view('instituteAdmin.letter-template', compact('templates'));
    }

    /**
     * Get default template styles for 3 templates
     */
    private function getDefaultTemplateStyles()
    {
        return ['', '', ''];
    }

    /**
     * Default templates data
     */
    private function getDefaultTemplates()
    {
        $styles = $this->getDefaultTemplateStyles();
        
        return [
            'appointment' => [
                'id' => 'appointment',
                'key' => 'appointment',
                'title' => 'Appointment Letter',
                'style_1' => $styles[0],
                'style_2' => $styles[1],
                'style_3' => $styles[2],
                'status' => 'active',
                'isCustom' => false
            ],
            'offer' => [
                'id' => 'offer',
                'key' => 'offer',
                'title' => 'Job Offer Letter',
                'style_1' => $styles[0],
                'style_2' => $styles[1],
                'style_3' => $styles[2],
                'status' => 'active',
                'isCustom' => false
            ],
            'termination' => [
                'id' => 'termination',
                'key' => 'termination',
                'title' => 'Termination Letter',
                'style_1' => $styles[0],
                'style_2' => $styles[1],
                'style_3' => $styles[2],
                'status' => 'active',
                'isCustom' => false
            ],
            'recommendation' => [
                'id' => 'recommendation',
                'key' => 'recommendation',
                'title' => 'Recommendation Letter',
                'style_1' => $styles[0],
                'style_2' => $styles[1],
                'style_3' => $styles[2],
                'status' => 'active',
                'isCustom' => false
            ],
            'warning' => [
                'id' => 'warning',
                'key' => 'warning',
                'title' => 'Warning Letter',
                'style_1' => $styles[0],
                'style_2' => $styles[1],
                'style_3' => $styles[2],
                'status' => 'active',
                'isCustom' => false
            ],
            'noc' => [
                'id' => 'noc',
                'key' => 'noc',
                'title' => 'No Objection Certificate (NOC)',
                'style_1' => $styles[0],
                'style_2' => $styles[1],
                'style_3' => $styles[2],
                'status' => 'active',
                'isCustom' => false
            ],
            'salary' => [
                'id' => 'salary',
                'key' => 'salary',
                'title' => 'Salary Increment Letter',
                'style_1' => $styles[0],
                'style_2' => $styles[1],
                'style_3' => $styles[2],
                'status' => 'active',
                'isCustom' => false
            ],
            'experience' => [
                'id' => 'experience',
                'key' => 'experience',
                'title' => 'Experience Certificate',
                'style_1' => $styles[0],
                'style_2' => $styles[1],
                'style_3' => $styles[2],
                'status' => 'active',
                'isCustom' => false
            ]
        ];
    }
    
    /**
     * Save PDF to storage and update letter record
     * UPDATED: Added regenerate support
     */
    public function savePDF(Request $request, $id)
    {
        try {
            $request->validate([
                'pdf_data' => 'required|string',
                'file_name' => 'sometimes|string|nullable',
                'employee_id' => 'sometimes|string|nullable',
                'title' => 'sometimes|string|nullable',
                'template_key' => 'sometimes|string|nullable',
                'reference_id' => 'sometimes|string|nullable',
                'regenerate' => 'sometimes|boolean'
            ]);

            \Log::info('=== SAVING PDF START ===');
            \Log::info('ID received: ' . $id);
            \Log::info('Reference ID received: ' . $request->input('reference_id'));
            \Log::info('Regenerate flag: ' . $request->input('regenerate', false));

            $referenceId = $request->input('reference_id');
            $templateKey = $request->input('template_key');
            $employeeId = $request->input('employee_id');
            $title = $request->input('title');
            $isRegenerate = $request->input('regenerate', false);

            $letter = null;

            // If regenerating, ALWAYS create a new letter
            if ($isRegenerate) {
                \Log::info('=== REGENERATE MODE === Creating new letter with new reference ID');
                
                $companyName = $request->input('company_name', null);
                $newReferenceId = $this->generateReferenceId($employeeId, $companyName);
                
                if ($referenceId && Schema::hasColumn('letters', 'reference_id')) {
                    $existing = Letter::where('reference_id', $referenceId)
                        ->where('employee_id', $employeeId)
                        ->first();
                    if ($existing) {
                        \Log::info('Letter with reference_id already exists, using it: ' . $existing->id);
                        $letter = $existing;
                    }
                }
                
                if (!$letter) {
                    $letter = new Letter();
                    $letter->employee_id = $employeeId;
                    $letter->title = $title . ' (Regenerated)';
                    $letter->content = $request->input('content', '');
                    $letter->template_key = $templateKey;
                    $letter->status = 'draft';
                    $letter->reference_id = $newReferenceId;
                    $letter->letter_id = $templateKey . '-' . $employeeId . '-' . time();
                    
                    if (Schema::hasColumn('letters', 'template_id')) {
                        $tpl = LetterTemplate::where('key', $templateKey)->first();
                        if ($tpl) {
                            $letter->template_id = $tpl->id;
                        }
                    }
                    
                    $letter->save();
                    $letter->refresh();
                    
                    \Log::info('Created NEW regenerated letter ID: ' . $letter->id . ', reference_id: ' . $newReferenceId);
                }
                
            } else {
                // ============ NORMAL SAVE ============
                
                if ($referenceId && Schema::hasColumn('letters', 'reference_id')) {
                    $letter = Letter::where('reference_id', $referenceId)
                        ->where('employee_id', $employeeId)
                        ->where('status', '!=', 'cancelled')
                        ->first();
                    if ($letter) {
                        \Log::info('Found letter by reference_id + employee_id: ' . $letter->id);
                    }
                }

                if (!$letter && is_numeric($id)) {
                    $letter = Letter::find((int) $id);
                    if ($letter && $letter->employee_id != $employeeId) {
                        $letter = null;
                        \Log::info('Found letter but wrong employee_id');
                    } elseif ($letter && $letter->status === 'cancelled') {
                        $letter = null;
                        \Log::info('Found cancelled letter, will create new one');
                    } elseif ($letter) {
                        \Log::info('Found letter by numeric ID: ' . $letter->id);
                    }
                }

                if (!$letter && Schema::hasColumn('letters', 'letter_id')) {
                    $letter = Letter::where('letter_id', (string) $id)
                        ->where('employee_id', $employeeId)
                        ->where('status', '!=', 'cancelled')
                        ->first();
                    if ($letter) {
                        \Log::info('Found letter by letter_id + employee_id: ' . $letter->id);
                    }
                }

                if (!$letter && $templateKey && $employeeId) {
                    $letter = Letter::where('template_key', $templateKey)
                        ->where('employee_id', $employeeId)
                        ->where('status', '!=', 'cancelled')
                        ->latest('id')
                        ->first();
                    if ($letter) {
                        \Log::info('Found letter by template_key + employee_id: ' . $letter->id);
                    }
                }

                if (!$letter) {
                    \Log::info('No existing letter found. Creating new one');
                    
                    $letter = new Letter();
                    $letter->employee_id = $employeeId;
                    $letter->title = $title ?? 'Untitled Letter';
                    $letter->content = $request->input('content', '');
                    $letter->template_key = $templateKey;
                    $letter->status = 'draft';
                    
                    if (!is_numeric($id) && Schema::hasColumn('letters', 'letter_id')) {
                        $letter->letter_id = (string) $id;
                    }
                    
                    if (Schema::hasColumn('letters', 'reference_id')) {
                        if ($referenceId) {
                            $letter->reference_id = $referenceId;
                        } else {
                            $companyName = $request->input('company_name', null);
                            $letter->reference_id = $this->generateReferenceId($employeeId, $companyName);
                        }
                    }
                    
                    if (Schema::hasColumn('letters', 'template_id')) {
                        $tpl = LetterTemplate::where('key', $templateKey)->first();
                        if ($tpl) {
                            $letter->template_id = $tpl->id;
                        }
                    }
                    
                    $letter->save();
                    $letter->refresh();
                    
                    \Log::info('Created new letter for employee ' . $employeeId . ' with ID: ' . $letter->id);
                }
            }

            // ============ SAVE PDF ============
            $pdfData = $request->input('pdf_data');
            
            if (strpos($pdfData, 'base64,') !== false) {
                $pdfData = substr($pdfData, strpos($pdfData, 'base64,') + 7);
            }
            
            $pdfBinary = base64_decode($pdfData);
            
            if ($pdfBinary === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid PDF data'
                ], 400);
            }

            $fileName = $request->input('file_name', $letter->title ?? 'letter');
            $fileName = Str::slug($fileName) . '_' . $employeeId . '_' . time() . '.pdf';
            
            $storagePath = 'letters/' . date('Y') . '/' . date('m');
            $fullPath = $storagePath . '/' . $fileName;
            
            \Log::info('Saving PDF to: ' . $fullPath);
            
            $stored = Storage::disk('public')->put($fullPath, $pdfBinary);
            
            if (!$stored) {
                \Log::error('Failed to store PDF file');
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store PDF file'
                ], 500);
            }
            
            \Log::info('PDF file stored successfully');
            
            if ($letter->official_document_file && Storage::disk('public')->exists($letter->official_document_file)) {
                Storage::disk('public')->delete($letter->official_document_file);
                \Log::info('Deleted old file: ' . $letter->official_document_file);
            }

            $letter->official_document_file = $fullPath;
            $letter->status = 'completed';
            
            $letter->save();
            $letter->refresh();

            $fileUrl = Storage::disk('public')->url($fullPath);

            \Log::info('PDF saved successfully for letter ID: ' . $letter->id . ', reference_id: ' . ($letter->reference_id ?? 'null'));

            return response()->json([
                'success' => true,
                'message' => $isRegenerate ? 'PDF regenerated successfully with new reference ID' : 'PDF saved successfully',
                'data' => [
                    'id' => $letter->id,
                    'letter_id' => $letter->letter_id ?? null,
                    'reference_id' => $letter->reference_id ?? null,
                    'template_key' => $letter->template_key,
                    'file_path' => $fullPath,
                    'file_url' => $fileUrl,
                    'file_name' => $fileName,
                    'official_document_file' => $letter->official_document_file,
                    'status' => $letter->status,
                    'is_regenerated' => $isRegenerate
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to save PDF: ' . $e->getMessage(), [
                'letter_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View the letter PDF
     */
    public function viewLetterPdf($id)
    {
        try {
            $letter = Letter::findOrFail($id);
            
            if ($letter->official_document_file && Storage::disk('public')->exists($letter->official_document_file)) {
                $filePath = Storage::disk('public')->path($letter->official_document_file);
                
                return response()->file($filePath, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . basename($letter->official_document_file) . '"'
                ]);
            } else {
                return redirect()->back()->with('error', 'PDF not generated yet. Please generate the letter first.');
            }
        } catch (\Exception $e) {
            \Log::error('Error viewing letter PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load the letter. Please try again.');
        }
    }

    /**
     * Save a custom letter template with 3 styles
     */
    public function saveTemplate(Request $request)
    {
        try {
            $validated = $request->validate([
                'key' => 'required|string|max:255|unique:letter_templates,key',
                'title' => 'required|string|max:255',
                'style_1' => 'required|string',
                'style_2' => 'nullable|string',
                'style_3' => 'nullable|string',
                'style_labels' => 'nullable|array',
                'document_type' => 'required|string|max:100',
                'letter_id' => 'nullable|string|max:50|unique:letter_templates,letter_id',
                'default_style' => 'nullable|integer|min:1|max:3',
                'is_custom' => 'nullable|boolean',
                'status' => 'nullable|in:active,draft'
            ]);
    
            $instituteId = auth()->user()->institute_id ?? null;
            
            $officialDocTypeId = null;
            if ($validated['document_type']) {
                $docType = \App\Models\OfficialDocumentType::where('official_document_type', $validated['document_type'])
                    ->where('institute_id', $instituteId)
                    ->first();
                
                if ($docType) {
                    $officialDocTypeId = $docType->id;
                } else {
                    $docType = \App\Models\OfficialDocumentType::create([
                        'institute_id' => $instituteId,
                        'official_documenttype_id' => 'DOC-' . strtoupper(\Illuminate\Support\Str::random(8)),
                        'official_document_type' => $validated['document_type'],
                        'status' => 'active'
                    ]);
                    $officialDocTypeId = $docType->id;
                }
            }
    
            if (empty($validated['letter_id'])) {
                $validated['letter_id'] = $this->generateLetterId();
            }
    
            $template = LetterTemplate::create([
                'institute_id' => $instituteId,
                'key' => $validated['key'],
                'default_style' => $validated['default_style'] ?? 1,
                'document_type' => $validated['document_type'],
                'official_documenttype_id' => $officialDocTypeId,
                'letter_id' => $validated['letter_id'],
                'title' => $validated['title'],
                'style_1' => $validated['style_1'] ?? '',
                'style_2' => $validated['style_2'] ?? '',
                'style_3' => $validated['style_3'] ?? '',
                'style_labels' => json_encode($validated['style_labels'] ?? ['Template 1', 'Template 2', 'Template 3']),
                'is_custom' => $validated['is_custom'] ?? true,
                'status' => $validated['status'] ?? 'active'
            ]);
    
            \Log::info('Letter template created successfully', [
                'template_id' => $template->id,
                'letter_id' => $template->letter_id,
                'key' => $template->key,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Letter template created successfully',
                'data' => [
                    'id' => $template->id,
                    'key' => $template->key,
                    'letter_id' => $template->letter_id,
                    'title' => $template->title,
                    'document_type' => $template->document_type,
                    'style_1' => $template->style_1,
                    'style_2' => $template->style_2,
                    'style_3' => $template->style_3
                ]
            ], 201);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed'
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating letter template: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create letter template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate a unique letter ID in format LTR-XXXX
     */
    private function generateLetterId()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $result = 'LTR-';
        for ($i = 0; $i < 6; $i++) {
            $result .= $chars[rand(0, strlen($chars) - 1)];
        }
        
        while (LetterTemplate::where('letter_id', $result)->exists()) {
            $result = 'LTR-';
            for ($i = 0; $i < 6; $i++) {
                $result .= $chars[rand(0, strlen($chars) - 1)];
            }
        }
        
        return $result;
    }
    
    public function getReferenceId($id)
    {
        try {
            $identifier = trim((string) $id);
            $letter = null;
            $referenceId = null;
            
            if (is_numeric($identifier)) {
                $letter = Letter::find((int) $identifier);
                if ($letter && $letter->reference_id) {
                    $referenceId = $letter->reference_id;
                }
            }
            
            if (!$referenceId && Schema::hasColumn('letters', 'letter_id')) {
                $letter = Letter::where('letter_id', $identifier)->first();
                if ($letter && $letter->reference_id) {
                    $referenceId = $letter->reference_id;
                }
            }
            
            if (!$referenceId) {
                $letter = Letter::where('template_key', $identifier)
                    ->whereNotNull('reference_id')
                    ->first();
                if ($letter && $letter->reference_id) {
                    $referenceId = $letter->reference_id;
                }
            }
            
            if ($referenceId) {
                return response()->json([
                    'success' => true,
                    'reference_id' => $referenceId,
                    'letter_id' => $letter->id ?? null
                ]);
            }
    
            $template = LetterTemplate::where('letter_id', $identifier)
                ->orWhere('key', $identifier)
                ->first();
            
            if ($template) {
                $letter = Letter::where('template_key', $template->key)
                    ->whereNotNull('reference_id')
                    ->first();
                
                if ($letter && $letter->reference_id) {
                    return response()->json([
                        'success' => true,
                        'reference_id' => $letter->reference_id, 
                        'letter_id' => $letter->id
                    ]);
                }
                
                $employeeId = request()->input('employee_id', 'EMP');
                $companyName = request()->input('company_name', 'REF');
                $newReferenceId = $this->generateReferenceId($employeeId, $companyName);
                
                $newLetter = new Letter();
                $newLetter->employee_id = $employeeId;
                $newLetter->template_key = $template->key;
                $newLetter->title = $template->title ?? 'Untitled';
                $newLetter->content = $template->style_1 ?? '';
                $newLetter->reference_id = $newReferenceId;
                $newLetter->letter_id = $template->letter_id;
                $newLetter->status = 'draft';
                $newLetter->save();
                
                return response()->json([
                    'success' => true,
                    'reference_id' => $newReferenceId,
                    'letter_id' => $newLetter->id,
                    'generated' => true
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No reference_id found for this letter'
            ], 404);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching reference_id: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch reference_id: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getAllDesignSettings()
{
    try {
        $designSettings = LetterDesignSetting::all();
        return response()->json($designSettings);
    } catch (\Exception $e) {
        \Log::error('Error fetching design settings: ' . $e->getMessage());
        return response()->json([]);
    }
}
}