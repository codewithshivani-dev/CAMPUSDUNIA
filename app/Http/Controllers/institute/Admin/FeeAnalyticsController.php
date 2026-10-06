<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentGatewayLink;
use App\Models\StudentCourseFeeStructure;
use App\Models\StudentCustomFeestructure;
use App\Models\StudentHostelFeeStructure;
use App\Models\StudentMiscellaneousFeeStructure;
use App\Models\StudentRegistrationFeeStructure;
use App\Models\StudentTransportFeeStructure;
use App\Models\StudentParentDetails;
use App\Models\InstituteBasicDetails;
use App\Models\Departments;
use App\Models\StudentAcademicTransportDetails;
use App\Models\CourseFeeStructure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FeeAnalyticsController extends Controller
{

    public function feeAnalytics(Request $request)
    {
        $institute_id = auth()->user()->institute_id;
        $reportType = $request->get('report_type', 'daily');
        $feeType = $request->get('fee_type', 'all');
        $departmentId = $request->get('department_id');
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('n'));
        
        // Define fee models with their respective total amount columns
        $feeModels = [
            'course' => [
                'model' => StudentCourseFeeStructure::class,
                'amount_column' => 'course_total_fee',
                'fee_column' => 'course_fee'
            ],
            'registration' => [
                'model' => StudentRegistrationFeeStructure::class,
                'amount_column' => 'registration_total_fee',
                'fee_column' => 'registration_fee'
            ],
            'transport' => [
                'model' => StudentTransportFeeStructure::class,
                'amount_column' => 'transport_total_fee',
                'fee_column' => 'transport_fee'
            ],
            'hostel' => [
                'model' => StudentHostelFeeStructure::class,
                'amount_column' => 'hostel_total_fee',
                'fee_column' => 'hostel_fee'
            ],
            'miscellaneous' => [
                'model' => StudentCustomFeestructure::class,
                'amount_column' => 'total_fee_amount',
                'fee_column' => 'custom_fee_value'
            ]
        ];
        
        // Filter by department if selected
        $studentHashIds = [];
        if ($departmentId) {
            $studentHashIds = StudentAcademicTransportDetails::where('institute_id', $institute_id)
                ->where('department_id', $departmentId)
                ->pluck('student_hash_id')
                ->toArray();
        }
        
        // Collection data by period
        $collectionData = [];
        $chartLabels = [];
        $chartValues = [];
        $totals = [
            'course_fee' => 0,
            'registration_fee' => 0,
            'transport_fee' => 0,
            'hostel_fee' => 0,
            'miscellaneous_fee' => 0,
            'total' => 0,
            'transaction_count' => 0
        ];
        
        // Fee type breakdown totals
        $feeTypeTotals = [
            'course' => 0,
            'registration' => 0,
            'transport' => 0,
            'hostel' => 0,
            'miscellaneous' => 0
        ];
        
        // Process based on report type
        switch ($reportType) {
            case 'daily':
            // Get last 30 days - make sure to include today
            for ($i = 30; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                
                // Create proper start and end of day for this date
                $startDate = $date->copy()->startOfDay();
                $endDate = $date->copy()->endOfDay();
                
                $dayData = $this->getCollectionForPeriod($startDate, $endDate, $feeModels, $feeType, $studentHashIds, $institute_id);
                
                $collectionData[] = [
                    'period' => $date->format('d M Y'),
                    'course_fee' => $dayData['course'],
                    'registration_fee' => $dayData['registration'],
                    'transport_fee' => $dayData['transport'],
                    'hostel_fee' => $dayData['hostel'],
                    'miscellaneous_fee' => $dayData['miscellaneous'],
                    'total' => $dayData['total'],
                    'transaction_count' => $dayData['count'],
                    'collection_rate' => $dayData['collection_rate']
                ];
                $chartLabels[] = $date->format('d M');
                $chartValues[] = $dayData['total'];
                
                // Update totals
                foreach (['course', 'registration', 'transport', 'hostel', 'miscellaneous'] as $type) {
                    $totals[$type . '_fee'] += $dayData[$type];
                    $feeTypeTotals[$type] += $dayData[$type];
                }
                $totals['total'] += $dayData['total'];
                $totals['transaction_count'] += $dayData['count'];
            }
            break;
                
            case 'weekly':
                // Get last 12 weeks
                $currentDate = Carbon::now()->subWeeks(12);
                
                while ($currentDate <= Carbon::now()) {
                    $weekStart = $currentDate->copy()->startOfWeek();
                    $weekEnd = $currentDate->copy()->endOfWeek();
                    $weekData = $this->getCollectionForPeriod($weekStart, $weekEnd, $feeModels, $feeType, $studentHashIds, $institute_id);
                    
                    $collectionData[] = [
                        'period' => 'Week ' . $currentDate->weekOfYear . ' (' . $weekStart->format('d M') . ' - ' . $weekEnd->format('d M') . ')',
                        'course_fee' => $weekData['course'],
                        'registration_fee' => $weekData['registration'],
                        'transport_fee' => $weekData['transport'],
                        'hostel_fee' => $weekData['hostel'],
                        'miscellaneous_fee' => $weekData['miscellaneous'],
                        'total' => $weekData['total'],
                        'transaction_count' => $weekData['count'],
                        'collection_rate' => $weekData['collection_rate']
                    ];
                    $chartLabels[] = 'W' . $currentDate->weekOfYear;
                    $chartValues[] = $weekData['total'];
                    
                    foreach (['course', 'registration', 'transport', 'hostel', 'miscellaneous'] as $type) {
                        $totals[$type . '_fee'] += $weekData[$type];
                        $feeTypeTotals[$type] += $weekData[$type];
                    }
                    $totals['total'] += $weekData['total'];
                    $totals['transaction_count'] += $weekData['count'];
                    
                    $currentDate->addWeek();
                }
                break;
                
            case 'monthly':
                // Get months for selected year
                for ($m = 1; $m <= 12; $m++) {
                    $monthStart = Carbon::create($year, $m, 1)->startOfMonth();
                    $monthEnd = Carbon::create($year, $m, 1)->endOfMonth();
                    $monthData = $this->getCollectionForPeriod($monthStart, $monthEnd, $feeModels, $feeType, $studentHashIds, $institute_id);
                    
                    $collectionData[] = [
                        'period' => $monthStart->format('M Y'),
                        'course_fee' => $monthData['course'],
                        'registration_fee' => $monthData['registration'],
                        'transport_fee' => $monthData['transport'],
                        'hostel_fee' => $monthData['hostel'],
                        'miscellaneous_fee' => $monthData['miscellaneous'],
                        'total' => $monthData['total'],
                        'transaction_count' => $monthData['count'],
                        'collection_rate' => $monthData['collection_rate']
                    ];
                    $chartLabels[] = $monthStart->format('M');
                    $chartValues[] = $monthData['total'];
                    
                    foreach (['course', 'registration', 'transport', 'hostel', 'miscellaneous'] as $type) {
                        $totals[$type . '_fee'] += $monthData[$type];
                        $feeTypeTotals[$type] += $monthData[$type];
                    }
                    $totals['total'] += $monthData['total'];
                    $totals['transaction_count'] += $monthData['count'];
                }
                break;
                
            case 'yearly':
                // Get last 5 years
                $currentYear = Carbon::now()->year;
                for ($y = $currentYear - 4; $y <= $currentYear; $y++) {
                    $yearStart = Carbon::create($y, 1, 1)->startOfYear();
                    $yearEnd = Carbon::create($y, 12, 31)->endOfYear();
                    $yearData = $this->getCollectionForPeriod($yearStart, $yearEnd, $feeModels, $feeType, $studentHashIds, $institute_id);
                    
                    $collectionData[] = [
                        'period' => $y,
                        'course_fee' => $yearData['course'],
                        'registration_fee' => $yearData['registration'],
                        'transport_fee' => $yearData['transport'],
                        'hostel_fee' => $yearData['hostel'],
                        'miscellaneous_fee' => $yearData['miscellaneous'],
                        'total' => $yearData['total'],
                        'transaction_count' => $yearData['count'],
                        'collection_rate' => $yearData['collection_rate']
                    ];
                    $chartLabels[] = $y;
                    $chartValues[] = $yearData['total'];
                    
                    foreach (['course', 'registration', 'transport', 'hostel', 'miscellaneous'] as $type) {
                        $totals[$type . '_fee'] += $yearData[$type];
                        $feeTypeTotals[$type] += $yearData[$type];
                    }
                    $totals['total'] += $yearData['total'];
                    $totals['transaction_count'] += $yearData['count'];
                }
                break;
        }
        
        // Get departments
        $departments = Departments::where('institute_id', $institute_id)->get();
        
        // Calculate department-wise collections
        $departmentCollection = [];
        $departmentTotals = [];
        foreach ($departments as $dept) {
            $deptStudentIds = StudentAcademicTransportDetails::where('institute_id', $institute_id)
                ->where('department_id', $dept->department_id)
                ->pluck('student_hash_id')
                ->toArray();
            
            $deptTotal = 0;
            
            // Only calculate if there are students in this department
            if (!empty($deptStudentIds)) {
                foreach ($feeModels as $type => $modelConfig) {
                    if ($feeType != 'all' && $feeType != $type) {
                        continue;
                    }
                    
                    $model = $modelConfig['model'];
                    $amountColumn = $modelConfig['amount_column'];
                    
                    $query = $model::where('institute_id', $institute_id)
                        ->where('payment_status', 'paid')
                        ->whereIn('student_hash_id', $deptStudentIds);
                    
                    if ($request->get('year')) {
                        $query->whereYear('pay_date', $request->get('year'));
                    }
                    
                    // Add date range filter based on report type
                    $this->applyDateRangeFilter($query, $request);
                    
                    $deptTotal += $query->sum($amountColumn);
                }
            }
            
            // Always add the department with its collection amount (even if 0)
            $departmentCollection[$dept->department] = $deptTotal;
        }

        ksort($departmentCollection);
        // Calculate payment status statistics
        $statusData = $this->getPaymentStatusStats($feeModels, $feeType, $studentHashIds, $institute_id);
        $paidCount = $statusData['paid'];
        $pendingCount = $statusData['pending'];
        $overdueCount = $statusData['overdue'];
        $totalOverdueAmount = $statusData['overdue_amount'];
        
        // Calculate totals for display
        $totalCollection = $totals['total'];
        $totalPaidCount = $totals['transaction_count'];
        $totalPending = $this->getTotalPending($feeModels, $feeType, $studentHashIds, $institute_id);
        $totalOverdueCount = $overdueCount;
        $totalStudents = StudentParentDetails::where('institute_id', $institute_id)->count();
        $avgFeePerStudent = $totalStudents > 0 ? $totalCollection / $totalStudents : 0;
        
        // Calculate collection rate
        $totalExpected = $totalCollection + $totalPending + $totalOverdueAmount;
        $collectionRate = $totalExpected > 0 ? ($totalCollection / $totalExpected) * 100 : 0;
        
        // Calculate collection growth (compare with previous month)
        $collectionGrowth = $this->calculateCollectionGrowth($feeModels, $institute_id);
        
        // Prepare data for charts
        $feeTypeLabels = ['Course Fee', 'Registration Fee', 'Transport Fee', 'Hostel Fee', 'Miscellaneous Fee'];
        $feeTypeValues = array_values($feeTypeTotals);
        $departmentLabels = array_keys($departmentCollection);
        $departmentValues = array_values($departmentCollection);
        $paymentStatusLabels = ['Paid', 'Pending', 'Overdue'];
        $paymentStatusValues = [$paidCount, $pendingCount, $overdueCount];
        
        // Available years for filter
        $availableYears = range(date('Y') - 4, date('Y'));
        
        return view('instituteAdmin.AdminFeeStructureFile.FeeAnalyticsReport', compact(
            'collectionData',
            'chartLabels',
            'chartValues',
            'totals',
            'feeTypeLabels',
            'feeTypeValues',
            'departmentLabels',
            'departmentValues',
            'paymentStatusLabels',
            'paymentStatusValues',
            'departments',
            'availableYears',
            'totalCollection',
            'totalPaidCount',
            'totalPending',
            'totalOverdueCount',
            'totalOverdueAmount',
            'totalStudents',
            'avgFeePerStudent',
            'collectionRate',
            'collectionGrowth',
            'paidCount',
            'pendingCount',
            'overdueCount'
        ));
    }

    /**
     * Helper method to get collection for a specific period
     */
    private function getCollectionForPeriod($startDate, $endDate, $feeModels, $feeType, $studentHashIds, $institute_id)
    {
        $result = [
            'course' => 0,
            'registration' => 0,
            'transport' => 0,
            'hostel' => 0,
            'miscellaneous' => 0,
            'total' => 0,
            'count' => 0,
            'collection_rate' => 0
        ];
        
        // Convert dates to proper format with start and end of day
        if ($startDate instanceof Carbon) {
            $startDateTime = $startDate->copy()->startOfDay();
        } else {
            $startDateTime = Carbon::parse($startDate)->startOfDay();
        }
        
        if ($endDate instanceof Carbon) {
            $endDateTime = $endDate->copy()->endOfDay();
        } else {
            $endDateTime = Carbon::parse($endDate)->endOfDay();
        }
        
        foreach ($feeModels as $type => $modelConfig) {
            if ($feeType != 'all' && $feeType != $type) {
                continue;
            }
            
            $model = $modelConfig['model'];
            $amountColumn = $modelConfig['amount_column'];
            
            $query = $model::where('institute_id', $institute_id)
                ->where('payment_status', 'paid');
            
            // For daily view, use whereDate instead of whereBetween to handle date-only comparison
            if ($startDateTime->toDateString() == $endDateTime->toDateString()) {
                // Same day - use whereDate
                $query->whereDate('pay_date', $startDateTime->toDateString());
            } else {
                // Different days - use whereBetween with proper datetime range
                $query->whereBetween('pay_date', [$startDateTime, $endDateTime]);
            }
            
            if (!empty($studentHashIds)) {
                $query->whereIn('student_hash_id', $studentHashIds);
            }
            
            $sum = $query->sum($amountColumn);
            $count = $query->count();
            
            
            $result[$type] = $sum;
            $result['total'] += $sum;
            $result['count'] += $count;
        }
        
        // Calculate collection rate for this period
        $result['collection_rate'] = $result['count'] > 0 ? 100 : 0;
        
        return $result;
    }

    /**
     * Get payment status statistics
     */
    private function getPaymentStatusStats($feeModels, $feeType, $studentHashIds, $institute_id)
    {
        $stats = [
            'paid' => 0,
            'pending' => 0,
            'overdue' => 0,
            'overdue_amount' => 0
        ];
        
        foreach ($feeModels as $type => $modelConfig) {
            if ($feeType != 'all' && $feeType != $type) {
                continue;
            }
            
            $model = $modelConfig['model'];
            $amountColumn = $modelConfig['amount_column'];
            $feeColumn = $modelConfig['fee_column'];
            
            $query = $model::where('institute_id', $institute_id);
            
            if (!empty($studentHashIds)) {
                $query->whereIn('student_hash_id', $studentHashIds);
            }
            
            // Count paid payments
            $stats['paid'] += (clone $query)->where('payment_status', 'paid')->count();
            
            // Count pending payments (not paid and due date >= today)
            $stats['pending'] += (clone $query)->where('payment_status', '!=', 'paid')
                ->whereDate('due_date', '>=', Carbon::now())
                ->count();
            
            // Get overdue records (not paid and due date < today)
            $overdueRecords = (clone $query)->where('payment_status', '!=', 'paid')
                ->whereDate('due_date', '<', Carbon::now())
                ->get();
            
            $stats['overdue'] += $overdueRecords->count();
            
            // Calculate overdue amount
            foreach ($overdueRecords as $record) {
                $feeAmount = $record->$feeColumn ?? 0;
                $lateFee = $record->late_fee_amount ?? 0;
                $discount = $record->discount_amount ?? 0;
                $stats['overdue_amount'] += $feeAmount + $lateFee - $discount;
            }
        }
        
        return $stats;
    }

    /**
     * Get total pending amount
     */
    private function getTotalPending($feeModels, $feeType, $studentHashIds, $institute_id)
    {
        $total = 0;
        
        foreach ($feeModels as $type => $modelConfig) {
            if ($feeType != 'all' && $feeType != $type) {
                continue;
            }
            
            $model = $modelConfig['model'];
            $feeColumn = $modelConfig['fee_column'];
            
            $pendingRecords = $model::where('institute_id', $institute_id)
                ->where('payment_status', '!=', 'paid')
                ->whereDate('due_date', '>=', Carbon::now());
            
            if (!empty($studentHashIds)) {
                $pendingRecords->whereIn('student_hash_id', $studentHashIds);
            }
            
            foreach ($pendingRecords->get() as $record) {
                $feeAmount = $record->$feeColumn ?? 0;
                $lateFee = $record->late_fee_amount ?? 0;
                $discount = $record->discount_amount ?? 0;
                $total += $feeAmount + $lateFee - $discount;
            }
        }
        
        return $total;
    }

    /**
     * Calculate collection growth compared to previous month
     */
    private function calculateCollectionGrowth($feeModels, $institute_id)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $lastMonth = Carbon::now()->subMonth()->month;
        $lastMonthYear = Carbon::now()->subMonth()->year;
        
        $currentMonthTotal = 0;
        $lastMonthTotal = 0;
        
        foreach ($feeModels as $type => $modelConfig) {
            $model = $modelConfig['model'];
            $amountColumn = $modelConfig['amount_column'];
            
            $currentMonthTotal += $model::where('institute_id', $institute_id)
                ->where('payment_status', 'paid')
                ->whereMonth('pay_date', $currentMonth)
                ->whereYear('pay_date', $currentYear)
                ->sum($amountColumn);
                
            $lastMonthTotal += $model::where('institute_id', $institute_id)
                ->where('payment_status', 'paid')
                ->whereMonth('pay_date', $lastMonth)
                ->whereYear('pay_date', $lastMonthYear)
                ->sum($amountColumn);
        }
        
        if ($lastMonthTotal == 0) {
            return 0;
        }
        
        return (($currentMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100;
    }

    /**
     * Apply date range filter based on report type
     */
    private function applyDateRangeFilter($query, Request $request)
    {
        $reportType = $request->get('report_type', 'daily');
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('n'));
        
        switch ($reportType) {
            case 'daily':
                // For daily, we want current month to date or specific date range
                $query->whereYear('pay_date', $year)
                    ->whereMonth('pay_date', $month);
                break;
            case 'weekly':
                $startOfWeek = Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();
                $query->whereBetween('pay_date', [$startOfWeek, $endOfWeek]);
                break;
            case 'monthly':
                $query->whereYear('pay_date', $year)
                    ->whereMonth('pay_date', $month);
                break;
            case 'yearly':
                $query->whereYear('pay_date', $year);
                break;
        }
    }

}