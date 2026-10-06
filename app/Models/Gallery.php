<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;
    
    protected $table = "galleries";
    protected $guarded = [];
    
    // Add these relationships
    public function category()
    {
        return $this->belongsTo(DepartmentCategory::class, 'category_id');
    }
    
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    
    // Method to determine folder type
    public function getFolderType()
    {
        if ($this->category_id && $this->department_id && $this->course_id) {
            return 'all';
        } elseif ($this->department_id && $this->course_id) {
            return 'department_course';
        } elseif ($this->category_id && $this->department_id) {
            return 'category_department';
        } elseif ($this->department_id) {
            return 'department';
        } elseif ($this->course_id) {
            return 'course';
        } elseif ($this->category_id) {
            return 'category';
        } else {
            return 'general';
        }
    }
}