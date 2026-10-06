<?php
// app/Models/StudentIdCardTemplate.php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StudentIdCardTemplate extends Model
{
 
    protected $table = 'student_id_card_templates';

    protected $fillable = [
        'institute_id',
        'branch_id',
        'template_name',
        'card_title',
        'header_bg_color',
        'header_font_color',
        'footer_bg_color',
        'footer_font_color',
        'signature_text',
        'layout_style',
        'is_predefined',
        'is_default',
        'is_active',
        'has_back_side',
        'back_card_title',
        'back_signature_text',
        'back_header_bg_color',
        'back_header_font_color',
        'back_footer_bg_color',
        'back_footer_font_color',
        'back_layout_style',
        'header_banner',
        'signature_image',
        'back_header_banner',
        'back_signature_image',
        'field_settings',
        'back_field_settings',
    ];

    protected $casts = [
        'field_settings' => 'array',
        'back_field_settings' => 'array',
        'is_predefined' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'has_back_side' => 'boolean',
    ];

    public function generatedCards()
    {
        return $this->hasMany(GeneratedStudentIdCard::class, 'template_id');
    }

    public function scopeForInstitute($query, $instituteId)
    {
        return $query->where('institute_id', $instituteId);
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}