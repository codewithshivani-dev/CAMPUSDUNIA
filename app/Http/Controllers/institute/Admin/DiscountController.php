<?php
namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;

use App\Models\Discount;
use App\Models\DiscountAssignment;
use App\Models\DepartmentCategory;
use App\Models\Departments;
use App\Models\StudentParentDetails;
use App\Models\FincapMerchantSubCategories;
use App\Models\ProductDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show create discount form
    public function create()
    {
        $context = $this->getInstituteBranchContext();
        
        // Get department categories for dropdown
        $categories = DepartmentCategory::where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->get();
        
        return view('instituteAdmin.Discounts.create', compact('categories', 'context'));
    }

    public function store(Request $request)
    {
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:flat,percentage',
            'value' => 'required|numeric|min:0',
            'fee_type' => 'required|string',
            'custom_fee_id' => 'nullable', // Adjust based on your fee structure table
            'description' => 'nullable|string',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'max_usage' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $context = $this->getInstituteBranchContext();
        
        // Generate unique IDs
        $discountHashId = 'DISC-' . strtoupper(Str::random(8));
        $couponCode = 'CPN-' . strtoupper(Str::random(6));
        
        // Create discount with fee type
        $discountData = [
            'discount_hash_id' => $discountHashId,
            'coupon_code' => $couponCode,
            'name' => $request->name,
            'type' => $request->type,
            'value' => $request->value,
            'fee_type' => $request->fee_type,
            'custom_fee_id' => $request->custom_fee_id,
            'description' => $request->description,
            'valid_from' => $request->valid_from,
            'valid_to' => $request->valid_to,
            'max_usage' => $request->max_usage,
            'institute_id' => $context['institute_id'],
            'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
            'created_by' => auth()->id()
        ];

        $discount = Discount::create($discountData);

        return response()->json([
            'success' => true,
            'message' => 'Discount created successfully!',
            'discount' => $discount,
            'redirect' => route('discounts.list', $discount->discount_id)
        ]);
    }

    public function getCustomFees(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        $customFees = DB::table('common_custom_fees')
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($q) use ($context) {
                return $q->where('branch_id', $context['branch_id']);
            })
            ->select('custom_reference_id', 'custom_fee_key', 'custom_fee_value')
            ->get();
        
        return response()->json([
            'success' => true,
            'fees' => $customFees
        ]);
    }

    // List all discounts
    public function index(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        $query = Discount::withCount('assignments')
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function ($q) use ($context) {
                return $q->where('branch_id', $context['branch_id']);
            }, function ($q) {
                return $q->whereNull('branch_id');
            });

        // 🔥 Filters
        $query->when($request->name, function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->name . '%');
        });

        $query->when($request->type, function ($q) use ($request) {
            $q->where('type', $request->type);
        });

        $query->when($request->fee_type, function ($q) use ($request) {
            $q->where('fee_type', $request->fee_type);
        });

        $query->when($request->valid_from, function ($q) use ($request) {
            $q->whereDate('valid_from', '>=', $request->valid_from);
        });

        $query->when($request->valid_to, function ($q) use ($request) {
            $q->whereDate('valid_to', '<=', $request->valid_to);
        });

        $query->when(filled($request->status), function ($q) use ($request) {

            if ($request->status === 'active') {
                $q->where('is_active', 1)
                ->whereDate('valid_from', '<=', now())
                ->whereDate('valid_to', '>=', now());

            } elseif ($request->status === 'inactive') {
                $q->where(function ($sub) {
                    $sub->where('is_active', 0)
                        ->orWhereDate('valid_from', '>', now())
                        ->orWhereDate('valid_to', '<', now());
                });
            }

        });

        $discounts = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString(); // 🔥 keeps filters on pagination

        // 🔥 Datalist data
        $filterData = [
            'names' => Discount::where('institute_id', $context['institute_id'])
                ->pluck('name')
                ->unique(),

            'fee_types' => Discount::where('institute_id', $context['institute_id'])
                ->pluck('fee_type')
                ->unique(),
        ];

        return view('instituteAdmin.Discounts.index', compact('discounts', 'context', 'filterData'));
    }

    // View discount details
    public function show($discountHashId)
    {
        $discount = Discount::with(['assignments.departmentCategory', 'assignments.department', 
                                  'assignments.product', 'assignments.student'])
            ->findOrFail($discountHashId);
        
        $context = $this->getInstituteBranchContext();
        $this->verifyDiscountScope($discount, $context);
        
        return view('discounts.show', compact('discount', 'context'));
    }

    // Verify discount belongs to user's scope
    private function verifyDiscountScope($discount, $context)
    {
        if ($discount->institute_id != $context['institute_id']) {
            abort(403, 'Access denied');
        }
        
        if ($context['is_branch_admin'] && $discount->branch_id != $context['branch_id']) {
            abort(403, 'Access denied');
        }
    }

    // In DiscountController.php
    public function getAvailableTransportDiscounts(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        // Get discounts that are valid for transportation
        $discounts = Discount::where('institute_id', $context['institute_id'])
            ->where('fee_type', 'transportation') // Filter by transport fee type
            ->where(function($query) {
                // Valid discounts (not expired and within usage limits)
                $query->whereNull('valid_to')
                    ->orWhere('valid_to', '>=', now());
            })
            ->where(function($query) {
                // Check max usage if set - we'll handle this in the loop
            })
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Filter discounts based on usage limits
        $filteredDiscounts = $discounts->filter(function($discount) {
            // If no max usage limit, always include
            if (!$discount->max_usage) {
                return true;
            }
            
            // Get current usage count
            $currentUsage = $discount->active_transport_assignments_count;
            
            // Include if current usage is less than max usage
            return $currentUsage < $discount->max_usage;
        });
        
        return response()->json([
            'success' => true,
            'discounts' => $filteredDiscounts->values() // Reset keys
        ]);
    }

}

 
