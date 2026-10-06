<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LetterDesignSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'letter_id',
        'selected_style',
        'selected_template',
        'primary_color',
        'secondary_color',
        'header_bg',
        'header_border_color',
        'header_text_color',
        'company_name',
        'company_name_size',
        'company_tagline',
        'company_tagline_size',
        'logo_text',
        'logo_size',
        'stamp',
        'logo_radius',
        'header_alignment',
        'header_padding',
        'header_border_width',
        'header_style_type',
        'show_reference',
        'reference_label',
        'body_font_size',
        'body_line_height',
        'body_letter_spacing',
        'body_text_align',
        'body_color',
        'body_padding',
        'font_family',
        'signature_name',
        'signature_title',
        'signature_font_size',
        'signature_line_width',
        'signature',
        'footer_text',
        'footer_font_size',
        'footer_bg',
        'footer_padding',
        'footer_alignment',
        'footer_border_color',
        'footer_border_width',
        'footer_style_type',
        'footer_text_color',
        'date_position',
        'date_color',
        'date_size',
        'date_style',
        'date_margin_top',
        'date_margin_bottom',
        'template_styles',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'template_styles' => 'array',
        'body_font_size' => 'float',
        'body_line_height' => 'float',
        'body_letter_spacing' => 'float',
        'selected_style' => 'integer',
        'selected_template' => 'integer',
        'company_name_size' => 'integer',
        'company_tagline_size' => 'integer',
        'logo_size' => 'integer',
        'logo_radius' => 'integer',
        'header_padding' => 'integer',
        'header_border_width' => 'integer',
        'body_padding' => 'integer',
        'signature_font_size' => 'integer',
        'signature_line_width' => 'integer',
        'footer_font_size' => 'integer',
        'footer_padding' => 'integer',
        'footer_border_width' => 'integer',
        'date_size' => 'integer',
        'date_margin_top' => 'integer',
        'date_margin_bottom' => 'integer',
    ];

    /**
     * Normalize a letter identifier so both numeric and custom string IDs are stored safely.
     */
    public static function normalizeLetterId($letterId): string
    {
        if ($letterId === null) {
            return '';
        }

        return trim((string) $letterId);
    }

    /**
     * Get the letter that owns the design setting.
     */
    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }

    /**
     * Get the style name based on selected style ID.
     */
    public function getStyleNameAttribute(): string
    {
        $styles = [
            1 => 'Classic',
            2 => 'Modern',
            3 => 'Elegant',
        ];
        
        return $styles[$this->selected_style] ?? 'Classic';
    }

    /**
     * Get the template name based on selected template ID.
     */
    public function getTemplateNameAttribute(): string
    {
        $templates = [
            1 => 'Template 1',
            2 => 'Template 2',
            3 => 'Template 3',
        ];
        
        return $templates[$this->selected_template] ?? 'Template 1';
    }

    /**
     * Get all customizations as an array.
     */
    public function getCustomizationsAttribute(): array
    {
        return [
            'header' => [
                'primaryColor' => $this->primary_color,
                'secondaryColor' => $this->secondary_color,
                'headerBg' => $this->header_bg,
                'headerBorderColor' => $this->header_border_color,
                'headerTextColor' => $this->header_text_color,
                'companyName' => $this->company_name,
                'companyNameSize' => $this->company_name_size,
                'companyTagline' => $this->company_tagline,
                'companyTaglineSize' => $this->company_tagline_size,
                'logoText' => $this->logo_text,
                'logoSize' => $this->logo_size,
                'logoRadius' => $this->logo_radius,
                'headerAlignment' => $this->header_alignment,
                'headerPadding' => $this->header_padding,
                'headerBorderWidth' => $this->header_border_width,
                'headerStyleType' => $this->header_style_type,
                'showReference' => $this->show_reference,
                'referenceLabel' => $this->reference_label,
            ],
            'body' => [
                'bodyFontSize' => $this->body_font_size,
                'bodyLineHeight' => $this->body_line_height,
                'bodyLetterSpacing' => $this->body_letter_spacing,
                'bodyTextAlign' => $this->body_text_align,
                'bodyColor' => $this->body_color,
                'padding' => $this->body_padding,
                'fontFamily' => $this->font_family,
            ],
            'footer' => [
                'signatureName' => $this->signature_name,
                'signatureTitle' => $this->signature_title,
                'signatureFontSize' => $this->signature_font_size,
                'signatureLineWidth' => $this->signature_line_width,
                'footerText' => $this->footer_text,
                'footerFontSize' => $this->footer_font_size,
                'footerBg' => $this->footer_bg,
                'footerPadding' => $this->footer_padding,
                'footerAlignment' => $this->footer_alignment,
                'footerBorderColor' => $this->footer_border_color,
                'footerBorderWidth' => $this->footer_border_width,
                'footerStyleType' => $this->footer_style_type,
                'footerTextColor' => $this->footer_text_color,
                'stamp' => $this->stamp,
            ],
            'date' => [
                'datePosition' => $this->date_position,
                'dateColor' => $this->date_color,
                'dateSize' => $this->date_size,
                'dateStyle' => $this->date_style,
                'dateMarginTop' => $this->date_margin_top,
                'dateMarginBottom' => $this->date_margin_bottom,
            ],
        ];
    }

    /**
     * Get default design settings.
     */
    public static function getDefaults(): array
    {
        return [
            'selected_style' => 1,
            'selected_template' => 1,
            'primary_color' => '#3b82f6',
            'secondary_color' => '#2563eb',
            'header_bg' => '#ffffff',
            'header_border_color' => '#3b82f6',
            'header_text_color' => '#1f2937',
            'company_name' => 'ABC Institute',
            'company_name_size' => 16,
            'company_tagline' => 'Human Resources Department',
            'company_tagline_size' => 12,
            'logo_text' => 'A',
            'logo_size' => 50,
            'logo_radius' => 8,
            'header_alignment' => 'between',
            'header_padding' => 30,
            'header_border_width' => 2,
            'header_style_type' => 'default',
            'show_reference' => 'show',
            'reference_label' => 'Ref: APPOINT/2026',
            'body_font_size' => 14.0,
            'body_line_height' => 1.9,
            'body_letter_spacing' => 0.00,
            'body_text_align' => 'justify',
            'body_color' => '#1f2937',
            'body_padding' => 40,
            'font_family' => 'Georgia, serif',
            'signature_name' => 'Manager Name',
            'signature_title' => 'HR Department',
            'signature_font_size' => 13,
            'signature_line_width' => 200,
            'signature' => '',
            'footer_text' => 'ABC Institute © ' . date('Y'),
            'footer_font_size' => 12,
            'footer_bg' => '#ffffff',
            'footer_padding' => 20,
            'footer_alignment' => 'between',
            'footer_border_color' => '#3b82f6',
            'footer_border_width' => 2,
            'footer_style_type' => 'default',
            'footer_text_color' => '#6b7280',
            'date_position' => 'center',
            'date_color' => '#6b7280',
            'date_size' => 14,
            'date_style' => 'normal',
            'date_margin_top' => 20,
            'date_margin_bottom' => 30,
            'template_styles' => [''],
        ];
    }

    /**
     * Create default settings for a letter.
     */
    public static function createDefault($letterId): self
    {
        self::ensureLetterIdColumnIsString();

        $defaults = self::getDefaults();
        $defaults['letter_id'] = $letterId;
        
        return self::create($defaults);
    }

    /**
     * Ensure the letter_design_settings.letter_id column can store string IDs like LTR-HNCO.
     */
    public static function ensureLetterIdColumnIsString(): void
    {
        if (!Schema::hasTable('letter_design_settings') || !Schema::hasColumn('letter_design_settings', 'letter_id')) {
            return;
        }

        try {
            $columnType = Schema::getColumnType('letter_design_settings', 'letter_id');
        } catch (\Throwable $e) {
            return;
        }

        if (in_array($columnType, ['string', 'varchar', 'text', 'char'], true)) {
            return;
        }

        try {
            DB::statement('ALTER TABLE letter_design_settings MODIFY letter_id VARCHAR(100) NULL');
        } catch (\Throwable $e) {
            try {
                DB::statement('ALTER TABLE letter_design_settings ALTER COLUMN letter_id TYPE VARCHAR(100)');
            } catch (\Throwable $ignored) {
                // Ignore schema errors here; the save will still fail loudly if the database cannot be altered.
            }
        }
    }

    /**
     * Update settings from request data.
     */
    public function updateFromRequest(array $data): self
    {
        self::ensureLetterIdColumnIsString();

        $mapping = [
            'selectedStyle' => 'selected_style',
            'selectedTemplate' => 'selected_template',
            'primaryColor' => 'primary_color',
            'secondaryColor' => 'secondary_color',
            'headerBg' => 'header_bg',
            'headerBorderColor' => 'header_border_color',
            'headerTextColor' => 'header_text_color',
            'companyName' => 'company_name',
            'companyNameSize' => 'company_name_size',
            'companyTagline' => 'company_tagline',
            'companyTaglineSize' => 'company_tagline_size',
            'logoText' => 'logo_text',
            'logoSize' => 'logo_size',
            'logoRadius' => 'logo_radius',
            'headerAlignment' => 'header_alignment',
            'headerPadding' => 'header_padding',
            'headerBorderWidth' => 'header_border_width',
            'headerStyleType' => 'header_style_type',
            'showReference' => 'show_reference',
            'referenceLabel' => 'reference_label',
            'bodyFontSize' => 'body_font_size',
            'bodyLineHeight' => 'body_line_height',
            'bodyLetterSpacing' => 'body_letter_spacing',
            'bodyTextAlign' => 'body_text_align',
            'bodyColor' => 'body_color',
            'bodyPadding' => 'body_padding',
            'padding' => 'body_padding',
            'fontFamily' => 'font_family',
            'signatureName' => 'signature_name',
            'signatureTitle' => 'signature_title',
            'signatureFontSize' => 'signature_font_size',
            'signatureLineWidth' => 'signature_line_width',
            'signature' => 'signature',
            'signatureImageUrl' => 'signature',
            'footerText' => 'footer_text',
            'stamp' => 'stamp',
            'footerFontSize' => 'footer_font_size',
            'footerBg' => 'footer_bg',
            'footerPadding' => 'footer_padding',
            'footerAlignment' => 'footer_alignment',
            'footerBorderColor' => 'footer_border_color',
            'footerBorderWidth' => 'footer_border_width',
            'footerStyleType' => 'footer_style_type',
            'footerTextColor' => 'footer_text_color',
            'datePosition' => 'date_position',
            'dateColor' => 'date_color',
            'dateSize' => 'date_size',
            'dateStyle' => 'date_style',
            'dateMarginTop' => 'date_margin_top',
            'dateMarginBottom' => 'date_margin_bottom',
        ];

        foreach ($mapping as $requestKey => $dbColumn) {
            if (array_key_exists($requestKey, $data)) {
                $this->$dbColumn = $data[$requestKey];
            }
        }

        if (isset($data['templateStyles']) && is_array($data['templateStyles'])) {
            $this->template_styles = $data['templateStyles'];
        }

        $this->save();
        
        return $this;
    }
}