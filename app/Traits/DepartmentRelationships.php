<?php

namespace App\Traits;

use App\Models\EmployeeDetails;
use App\Models\DepartmentCategory;
use App\Models\Departments;
use App\Models\Designations;
use App\Models\ProductDetails;
use App\Models\StudentParentDetails;
use App\Models\StudentAcademicTransportDetails;


trait DepartmentRelationships
{
    use InstituteBranchAccess;

    /**
     * Get departments by category with common scope
     */
    protected function getDepartmentsByCategory($categoryId, $relations = [])
    {
        return $this->getCommonQuery(Departments::class)
            ->where('department_category_id', $categoryId)
            ->when(!empty($relations), function($query) use ($relations) {
                $query->with($relations);
            })
            ->orderBy('department')
            ->get();
    }

    /**
     * Get employees by department with common scope
     */
      protected function getEmployeesByDepartment($departmentId, $relations = [])
    {
        // First verify the department belongs to current institute/branch scope
        $department = $this->getCommonQuery(Departments::class)
            ->where('department_id', $departmentId)
            ->first();

        if (!$department) {
            return collect(); // Return empty collection if department not in scope
        }

        // UPDATED: Use both department_id AND department_category_id from employee_details table
        return $this->getCommonQuery(EmployeeDetails::class)
            ->where('department_id', $departmentId)
            ->where('department_category_id', $department->department_category_id) // Add this line
            ->when(!empty($relations), function($query) use ($relations) {
                $query->with($relations);
            })
            ->orderBy('name')
            ->get();
    }

    /**
     * Get course types by department from product_details table
     */
    protected function getCourseTypesByDepartment($departmentId, $relations = [])
    {
        // First verify the department belongs to current institute/branch scope
        $department = $this->getCommonQuery(Departments::class)
            ->where('id', $departmentId)
            ->first();

        if (!$department) {
            return collect(); // Return empty collection if department not in scope
        }

        return $this->getCommonQuery(ProductDetails::class)
            ->where('department_id', $departmentId)
            ->when(!empty($relations), function($query) use ($relations) {
                $query->with($relations);
            })
            ->select('course_type')
            ->distinct()
            ->orderBy('course_type')
            ->get()
            ->pluck('course_type');
    }

    /**
     * Get products by department and optionally by course type
     */
    protected function getProductsByDepartment($departmentId, $courseType = null, $relations = [])
    {
        // First verify the department belongs to current institute/branch scope
        $department = $this->getCommonQuery(Departments::class)
            ->where('id', $departmentId)
            ->first();

        if (!$department) {
            return collect();
        }

        $query = $this->getCommonQuery(ProductDetails::class)
            ->where('department_id', $departmentId)
            ->when(!empty($relations), function($query) use ($relations) {
                $query->with($relations);
            });

        if ($courseType) {
            $query->where('course_type', $courseType);
        }

        return $query->orderBy('course_type')->get();
    }

    /**
     * Get all department relationships in one call
     */
    protected function getDepartmentRelationships($departmentId)
    {
        // First verify the department belongs to current institute/branch scope
        $department = $this->getCommonQuery(Departments::class)
            ->where('id', $departmentId)
            ->with('category')
            ->first();

        if (!$department) {
            return null;
        }

        return [
            'employees' => $this->getEmployeesByDepartment($departmentId, ['department']),
            'course_types' => $this->getCourseTypesByDepartment($departmentId),
            'products' => $this->getProductsByDepartment($departmentId),
            'department' => $department
        ];
    }

    /**
     * Get category with all its departments and relationships
     */
    protected function getCategoryWithFullData($categoryId)
    {
        // First verify the category belongs to current institute/branch scope
        $category = $this->getCommonQuery(DepartmentCategory::class)
            ->where('id', $categoryId)
            ->with(['departments' => function($query) {
                $query->orderBy('department');
            }])
            ->first();

        if (!$category) {
            return null;
        }

        // Add employees and products count to each department
        $category->departments->each(function($department) {
            $department->employees_count = $this->getEmployeesByDepartment($department->id)->count();
            $department->products_count = $this->getProductsByDepartment($department->id)->count();
        });

        return $category;
    }

    /**
     * Search employees across departments
     */
    protected function searchEmployees($searchTerm, $departmentId = null)
    {
        $query = $this->getCommonQuery(EmployeeDetails::class)
            ->with(['department' => function($query) {
                $query->where('institute_id', $this->getCurrentInstituteId());
            }])
            ->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('employee_id', 'like', "%{$searchTerm}%");
            });

        if ($departmentId) {
            // Verify department belongs to current scope
            $department = $this->getCommonQuery(Departments::class)
                ->where('id', $departmentId)
                ->first();
                
            if ($department) {
                $query->where('department_id', $departmentId);
            }
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Search products by course type or product name
     */
    protected function searchProducts($searchTerm, $departmentId = null, $courseType = null)
    {
        $query = $this->getCommonQuery(ProductDetails::class)
            ->with(['department' => function($query) {
                $query->where('institute_id', $this->getCurrentInstituteId());
            }])
            ->where(function($q) use ($searchTerm) {
                $q->where('product_name', 'like', "%{$searchTerm}%")
                  ->orWhere('course_type', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });

        if ($departmentId) {
            $department = $this->getCommonQuery(Departments::class)
                ->where('id', $departmentId)
                ->first();
                
            if ($department) {
                $query->where('department_id', $departmentId);
            }
        }

        if ($courseType) {
            $query->where('course_type', $courseType);
        }

        return $query->orderBy('course_type')->get();
    }

    /**
     * Get department statistics
     */
    protected function getDepartmentStatistics($departmentId = null)
    {
        $stats = [
            'total_employees' => 0,
            'total_products' => 0,
            'active_employees' => 0,
            'departments_count' => 0,
            'course_types_count' => 0
        ];

        if ($departmentId) {
            // Single department stats
            $department = $this->getCommonQuery(Departments::class)
                ->where('id', $departmentId)
                ->first();

            if ($department) {
                $stats['total_employees'] = $this->getEmployeesByDepartment($departmentId)->count();
                $stats['total_products'] = $this->getProductsByDepartment($departmentId)->count();
                $stats['course_types_count'] = $this->getCourseTypesByDepartment($departmentId)->count();
                $stats['active_employees'] = $this->getCommonQuery(EmployeeDetails::class)
                    ->where('department_id', $departmentId)
                    ->where('status', 'active')
                    ->count();
            }
        } else {
            // All departments stats for current scope
            $departments = $this->getCommonQuery(Departments::class)->get();
            $stats['departments_count'] = $departments->count();
            
            foreach ($departments as $department) {
                $stats['total_employees'] += $this->getEmployeesByDepartment($department->id)->count();
                $stats['total_products'] += $this->getProductsByDepartment($department->id)->count();
                $stats['course_types_count'] += $this->getCourseTypesByDepartment($department->id)->count();
                $stats['active_employees'] += $this->getCommonQuery(EmployeeDetails::class)
                    ->where('department_id', $department->id)
                    ->where('status', 'active')
                    ->count();
            }
        }

        return $stats;
    }

    /**
     * Get all available course types across all departments
     */
    protected function getAllCourseTypes()
    {
        return $this->getCommonQuery(ProductDetails::class)
            ->select('course_type')
            ->distinct()
            ->orderBy('course_type')
            ->get()
            ->pluck('course_type');
    }

    /**
     * Get employees with their department information
     */
    protected function getEmployeesWithDepartments($filters = [])
    {
        $query = $this->getCommonQuery(EmployeeDetails::class)
            ->with(['department' => function($query) {
                $query->select('id', 'department', 'department_category_id');
            }]);

        // Apply filters if provided
        if (!empty($filters['department_id'])) {
            $query->where('department_id', $filters['department_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Get products grouped by course type
     */
    protected function getProductsGroupedByCourseType($departmentId = null)
    {
        $query = $this->getCommonQuery(ProductDetails::class)
            ->select('course_type', \DB::raw('COUNT(*) as product_count'))
            ->groupBy('course_type')
            ->orderBy('course_type');

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        return $query->get();
    }
}