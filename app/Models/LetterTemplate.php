<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class LetterTemplate extends Model
{
    use HasFactory;

    protected $table = 'letter_templates';

    protected $Guarded = [];

    protected $casts = [
        'styles' => 'array',  
        'style_labels' => 'array', 
    ];

    public function getStyleLabelsAttribute()
    {
        // If explicit column exists and has values, return them
        if (isset($this->attributes['style_labels']) && $this->attributes['style_labels']) {
            $raw = $this->attributes['style_labels']; 
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) return $decoded;  
        }

        // Fallback: generate labels from styles count
        $styles = $this->styles;
        if (!is_array($styles)) return [];

        return array_map(function ($idx) {
            return 'Template '.($idx + 1);
        }, array_keys($styles));
    }

    public function getStylesAttribute($value)
    {
        // If styles column exists and has data, let casts handle it
        if ($value !== null) {
            if (is_array($value)) return $value;
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }

        // Fallback: attempt to build styles from style_1..style_3 columns
        $result = [];
        for ($i = 1; $i <= 3; $i++) {
            $col = 'style_'.$i;
            if (!array_key_exists($col, $this->attributes) || $this->attributes[$col] === null) continue;
            $raw = $this->attributes[$col];
            // If column stores JSON, try decode
            $decoded = json_decode($raw, true);
            $candidate = null;
            if (is_string($decoded)) {
                $candidate = $decoded;
            } elseif (is_array($decoded)) {
                // join array into a string if needed
                $candidate = implode("\n", $decoded);
            } elseif (is_string($raw)) {
                $candidate = $raw;
            }
            if ($candidate !== null) {
                $candidate = trim($candidate);
                if ($candidate !== '') $result[] = $candidate;
            }
        }
        return $result;
    }
}
