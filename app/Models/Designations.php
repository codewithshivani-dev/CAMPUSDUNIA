<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designations  extends Model
{
    use HasFactory;
    protected $table="designations";
    
    protected $guarded = [];

     public function departmentCategory()
    {
        return $this->belongsTo(DepartmentCategory::class, 'department_category_id', 'department_category_id');
    }

    /**
     * Relationship to institute
     */
    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id', 'fincap_merchant_id');
    }

    /**
     * Relationship to branch
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get roles as array
     */
    public function getRolesAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    /**
     * Set roles as JSON
     */
    public function setRolesAttribute($value)
    {
        $this->attributes['roles'] = json_encode($value);
    }

    /**
     * Scope for active designations
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    public function employees()
    {
        return $this->hasMany(EmployeeDetails::class, 'designation_id', 'designation_id');
    }

    /**
     * Relationship with active employees only
     */
    public function activeEmployees()
    {
        return $this->hasMany(EmployeeDetails::class, 'designation_id', 'designation_id')
                    ->where('status', 'active');
    }
}


