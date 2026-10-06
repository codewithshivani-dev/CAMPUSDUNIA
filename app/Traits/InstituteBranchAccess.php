<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\DepartmentCategory;
use App\Models\Departments; 

// Remove the EmployeeDetails import and use fully qualified names

trait InstituteBranchAccess
{
    /**
     * Get institute and branch context for data scoping
     * 
     * @return array
     */
    protected function getInstituteBranchContext()
    {
        $user = Auth::user();
        
        if (!$user) {
            return [
                'institute_id' => null,
                'branch_id' => null,
                'branch_type' => null,
                'is_branch_admin' => false
            ];
        }

        $instituteId = $user->institute_id;
        $branchId = $user->branch_id ?? null;
        $branchType = $user->branch_type;
        
        // Determine user type
        $isBranchAdmin = ($branchType === 'branch_admin');

        return [
            'institute_id' => $instituteId,
            'branch_id' => $branchId,
            'branch_type' => $branchType,
            'is_branch_admin' => $isBranchAdmin
        ];
    }

    protected function applyInstituteBranchScope($query, $context = null)
    {
        $context = $context ?? $this->getInstituteBranchContext();
        
        // Always filter by institute_id
        if ($context['institute_id']) {
            $query->where('institute_id', $context['institute_id']);
        }
        
        // Additional filter for branch admins
        if ($context['is_branch_admin'] && $context['branch_id']) {
            $query->where('branch_id', $context['branch_id']);
        }
        
        return $query;
    }

    protected function createWithInstituteBranchContext(array $data, $context = null)
    {
        $context = $context ?? $this->getInstituteBranchContext();
        
        $baseData = [
            'institute_id' => $context['institute_id'],
        ];
        
        // FIXED: Institute admin can ONLY create institute-level data (branch_id = null)
        // Branch admin can ONLY create branch-specific data
        if ($context['is_branch_admin']) {
            $baseData['branch_id'] = $context['branch_id'];
        } else {
            $baseData['branch_id'] = null; // Institute admin always creates institute-level data
        }
        
        return array_merge($baseData, $data);
    }

    protected function getInstituteBranchData($model, $relations = [])
    {
        $context = $this->getInstituteBranchContext();
        
        $query = $model::where('institute_id', $context['institute_id']);
        
        if ($context['is_branch_admin'] && $context['branch_id']) {
            $query->where('branch_id', $context['branch_id']);
        }
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query;
    }

    protected function isBranchAdmin()
    {
        $context = $this->getInstituteBranchContext();
        return $context['is_branch_admin'];
    }

    protected function getCurrentInstituteId()
    {
        $context = $this->getInstituteBranchContext();
        return $context['institute_id'];
    }

    protected function getCurrentBranchId()
    {
        $context = $this->getInstituteBranchContext();
        return $context['branch_id'];
    }

    protected function getInstituteWideData($model, $relations = [])
    {
        $context = $this->getInstituteBranchContext();
        
        $query = $model::where('institute_id', $context['institute_id']);
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query;
    }

    protected function getCommonQuery($model, $relations = [])
    {
        $context = $this->getInstituteBranchContext();
        
        $query = $model::where('institute_id', $context['institute_id']);
        
        // FIXED: Institute admin sees ONLY institute-level data (branch_id = null)
        // Branch admin sees ONLY their branch data
        if ($context['is_branch_admin']) {
            $query->where('branch_id', $context['branch_id']);
        } else {
            $query->whereNull('branch_id'); // Institute admin only sees institute-level data
        }
        
        // Load relations if provided
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query;
    }

    protected function getInstituteWideQuery($model, $relations = [])
    {
        $context = $this->getInstituteBranchContext();
        
        // FIXED: Institute-wide means ONLY institute-level data (no branch data)
        $query = $model::where('institute_id', $context['institute_id'])
                    ->whereNull('branch_id'); // Only institute-level data
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query;
    }

    protected function getBranchSpecificQuery($model, $relations = [])
    {
        $context = $this->getInstituteBranchContext();
        
        $query = $model::where('institute_id', $context['institute_id'])
                    ->where('branch_id', $context['branch_id']);
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query;
    }
    
    protected function shouldShowBranchDataOnly()
    {
        $context = $this->getInstituteBranchContext();
        return $context['is_branch_admin'];
    }

    protected function getAllInstituteData($model, $relations = [])
    {
        $context = $this->getInstituteBranchContext();
        
        $query = $model::where('institute_id', $context['institute_id']);
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query;
    }

    // ===== ADD THESE MISSING METHODS =====

    /**
     * Verify if a category belongs to current institute/branch scope
     */
    protected function isCategoryInScope($categoryId)
    {
        return $this->getCommonQuery(DepartmentCategory::class)
            ->where('id', $categoryId)
            ->exists();
    }

    /**
     * Verify if a category is in scope using department_category_id
     */
    protected function isCategoryInScopeByCustomId($departmentCategoryId)
    {
        return $this->getCommonQuery(DepartmentCategory::class)
            ->where('department_category_id', $departmentCategoryId)
            ->exists();
    }

    /**
     * Verify if a department belongs to current institute/branch scope
     */
    protected function isDepartmentInScope($departmentId)
    {
        return $this->getCommonQuery(Departments::class)
            ->where('id', $departmentId)
            ->exists();
    }

    /**
     * Get departments by category using department_category_id
     */
    protected function getDepartmentsByCategoryCustom($departmentCategoryId)
    {
        return $this->getCommonQuery(Departments::class)
            ->where('department_category_id', $departmentCategoryId)
            ->orderBy('department')
            ->get();
    }

    /**
     * Get employees count by department - USING FULLY QUALIFIED CLASS NAME
     */
    protected function getEmployeesCountByDepartment($departmentId)
    {
        // Use fully qualified class name to avoid namespace issues
        return \App\Models\EmployeeDetails::where('department_id', $departmentId)
            ->where('institute_id', $this->getCurrentInstituteId())
            ->when($this->isBranchAdmin(), function($query) {
                $query->where('branch_id', $this->getCurrentBranchId());
            }, function($query) {
                $query->whereNull('branch_id');
            })
            ->count();
    }
}