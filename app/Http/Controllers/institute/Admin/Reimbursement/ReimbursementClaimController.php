<?php

namespace App\Http\Controllers\institute\admin\Reimbursement;

use App\Http\Controllers\Controller;
use App\Models\ReimbursementClaim;
use App\Models\ReimbursementClaimSubEntry;
use App\Models\ReimbursementClaimApproval;
use App\Models\ReimbursementPolicyAssignment;
use App\Models\ReimbursementTravelPolicy;
use App\Models\ReimbursementAccommodationPolicy;
use App\Models\ReimbursementFoodPolicy;
use App\Models\ReimbursementOtherPolicies;
use App\Models\ReimbursementPayouts;
use App\Models\EmployeeDetails;
use App\Models\Designations;
use App\Models\Departments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ReimbursementClaimController extends Controller
{
    private function getInstituteContext()
    {
        return [
            'institute_id' => Auth::user()->institute_id,
            'branch_id' => Auth::user()->branch_id,
        ];
    }

    private function generateClaimId()
    {
        return 'REQ-' . Carbon::now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
    }

    private function generateMasterRequestId()
    {
        return 'BATCH-' . Carbon::now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function dashboard(Request $request)
    {
        $context = $this->getInstituteContext();
        $today = Carbon::today();
        [$rangeStart, $rangeEnd, $selectedRange] = $this->dashboardDateRange($request, $today);

        $allClaims = ReimbursementClaim::where('institute_id', $context['institute_id'])
            ->where('branch_id', $context['branch_id'])
            ->latest()
            ->get();

        $claims = $this->dashboardApplyFilters($allClaims, $request, $rangeStart, $rangeEnd)->values();
        $claimIds = $claims->pluck('id')->filter()->values();

        $approvals = ReimbursementClaimApproval::where('institute_id', $context['institute_id'])
            ->where('branch_id', $context['branch_id'])
            ->when($claimIds->isNotEmpty(), function ($query) use ($claimIds) {
                $query->whereIn('reimbursement_claim_id', $claimIds);
            })
            ->latest()
            ->get();

        $departmentNames = Departments::whereIn('department_id', $claims->pluck('department_id')->filter()->unique()->all())
            ->get()
            ->keyBy('department_id')
            ->map(function ($department) {
                return $department->department ?: $department->name;
            })->all();

        $resolveDepartmentLabel = function ($claim) use ($departmentNames) {
            if (!empty($claim->department_name)) {
                return $claim->department_name;
            }
            if (!empty($claim->department_id) && isset($departmentNames[$claim->department_id])) {
                return $departmentNames[$claim->department_id];
            }
            if (!empty($claim->department)) {
                return $claim->department;
            }
            return 'Unassigned';
        };

        $assignments = ReimbursementPolicyAssignment::where('institute_id', $context['institute_id'])
            ->where('branch_id', $context['branch_id'])
            ->latest()
            ->get();

        $payouts = Schema::hasTable('reimbursement_payouts')
            ? ReimbursementPayouts::where('institute_id', $context['institute_id'])
                ->where('branch_id', $context['branch_id'])
                ->latest()
                ->get()
            : collect();

        $policies = $this->getDashboardPolicies($context);
        $policyMap = $policies->keyBy('reimbursement_policy_id');
        $pendingStatuses = ['pending', 'step1_approved', 'step1_pending', 'step2_pending'];
        $approvedStatuses = ['approved', 'settled', 'completed'];
        $rejectedStatuses = ['rejected', 'declined'];
        $paidStatuses = ['completed', 'paid', 'settled'];

        $statusCounts = $claims->groupBy(function ($claim) {
            return strtolower($claim->status ?: 'pending');
        })->map->count();

        $claimedTotal = (float) $claims->sum('claim_amount');
        $approvedTotal = (float) $claims->sum('approved_amount');
        $paidTotal = (float) ($payouts->isNotEmpty()
            ? $payouts->sum('payout_amount')
            : $approvals->sum('payout_amount'));
        $pendingAmount = (float) $claims->whereIn('status', $pendingStatuses)->sum('claim_amount');
        $rejectedAmount = (float) $claims->whereIn('status', $rejectedStatuses)->sum('claim_amount');
        $pendingSettlementClaims = $claims->where('status', 'approved');
        $paidClaimCount = $claims->where('status', 'settled')->count() + $payouts->whereIn('status', $paidStatuses)->count();
        $thisMonthClaims = $claims->filter(function ($claim) use ($today) {
            return $this->dashboardDateBetween($claim->submission_date ?: $claim->created_at, $today->copy()->startOfMonth(), $today);
        });

        $lastMonth = $today->copy()->subMonthNoOverflow();
        $lastMonthClaims = $claims->filter(function ($claim) use ($lastMonth) {
            return $this->dashboardDateBetween($claim->submission_date ?: $claim->created_at, $lastMonth->copy()->startOfMonth(), $lastMonth->copy()->endOfMonth());
        });

        $limitViolations = $claims->filter(function ($claim) use ($policyMap) {
            $limit = (float) ($policyMap[$claim->reimbursement_policy_id]['limit_amount'] ?? 0);
            return $limit > 0 && (float) $claim->claim_amount > $limit;
        });
        $nearLimitClaims = $claims->filter(function ($claim) use ($policyMap) {
            $limit = (float) ($policyMap[$claim->reimbursement_policy_id]['limit_amount'] ?? 0);
            return $limit > 0 && (float) $claim->claim_amount <= $limit && (float) $claim->claim_amount >= ($limit * 0.9);
        });
        $missingDocuments = $claims->filter(function ($claim) {
            $missingBill = $claim->bill_required && empty($claim->bill_attachment);
            $missingPhoto = $claim->photo_required && empty($claim->photo_attachment);
            return $missingBill || $missingPhoto;
        });
        $duplicateClaims = $claims->groupBy(function ($claim) {
            return implode('|', [
                $claim->employee_id,
                $claim->reimbursement_policy_id,
                $claim->bill_number ?: 'no-bill',
                $claim->expense_date ?: $claim->submission_date,
                (float) $claim->claim_amount,
            ]);
        })->filter(function ($items) {
            return $items->count() > 1 && !empty($items->first()->bill_number);
        })->flatten(1);

        $monthlyTrend = collect(range(5, 0))->map(function ($monthsAgo) use ($today, $claims, $payouts) {
            $month = $today->copy()->subMonthsNoOverflow($monthsAgo);
            $monthClaims = $claims->filter(function ($claim) use ($month) {
                $date = $this->dashboardCarbon($claim->submission_date ?: $claim->created_at);
                return $date && $date->format('Y-m') === $month->format('Y-m');
            });
            $monthPayouts = $payouts->filter(function ($payout) use ($month) {
                $date = $this->dashboardCarbon($payout->payout_date ?: $payout->created_at);
                return $date && $date->format('Y-m') === $month->format('Y-m');
            });

            return [
                'label' => $month->format('M y'),
                'count' => $monthClaims->count(),
                'claimed' => (float) $monthClaims->sum('claim_amount'),
                'approved' => (float) $monthClaims->sum('approved_amount'),
                'paid' => (float) $monthPayouts->sum('payout_amount'),
            ];
        });

        $departmentAnalysis = $claims->groupBy(function ($claim) use ($resolveDepartmentLabel) {
            return $resolveDepartmentLabel($claim);
        })->map(function ($items, $department) use ($payouts, $approvedStatuses) {
            $employeeIds = $items->pluck('employee_id')->filter()->unique();
            $departmentPayouts = $payouts->whereIn('employee_id', $employeeIds);
            $approvedClaims = $items->whereIn('status', $approvedStatuses)->count();

            return [
                'department' => $department,
                'claims' => $items->count(),
                'employees' => $employeeIds->count(),
                'claimed' => (float) $items->sum('claim_amount'),
                'approved' => (float) $items->sum('approved_amount'),
                'paid' => (float) $departmentPayouts->sum('payout_amount'),
                'pending' => $items->whereIn('status', ['pending', 'step1_approved'])->count(),
                'approval_rate' => $items->count() > 0 ? round(($approvedClaims / $items->count()) * 100, 1) : 0,
            ];
        })->sortByDesc('claimed')->values()->take(8);

        $categoryAnalysis = $claims->groupBy(function ($claim) {
            return ucfirst($claim->policy_category ?: 'Other');
        })->map(function ($items, $category) {
            return [
                'category' => $category,
                'claims' => $items->count(),
                'claimed' => (float) $items->sum('claim_amount'),
                'approved' => (float) $items->sum('approved_amount'),
                'pending' => $items->whereIn('status', ['pending', 'step1_approved'])->count(),
            ];
        })->sortByDesc('claimed')->values();

        $policyUtilization = $policies->map(function ($policy) use ($claims, $assignments) {
            $policyClaims = $claims->where('reimbursement_policy_id', $policy['reimbursement_policy_id']);
            $limit = $policy['limit_amount'];
            $approved = (float) $policyClaims->sum('approved_amount');

            return [
                'policy_id' => $policy['reimbursement_policy_id'],
                'name' => $policy['policy_name'],
                'category' => ucfirst($policy['policy_category'] ?: 'Other'),
                'status' => $policy['status'] ?: 'active',
                'frequency' => $policy['frequency_type'] ?: 'N/A',
                'limit_amount' => $limit,
                'claimed' => (float) $policyClaims->sum('claim_amount'),
                'approved' => $approved,
                'claims' => $policyClaims->count(),
                'violations' => $policyClaims->filter(function ($claim) use ($limit) {
                    return $limit > 0 && (float) $claim->claim_amount > $limit;
                })->count(),
                'assignments' => $assignments->where('reimbursement_policy_id', $policy['reimbursement_policy_id'])->count(),
                'utilization' => $limit > 0 ? min(100, round(($approved / $limit) * 100, 1)) : null,
            ];
        })->sortByDesc('claimed')->values()->take(10);

        $step1Pending = $claims->filter(function ($claim) {
            return ($claim->step1_status ?: $claim->status) === 'pending';
        });
        $step1Approved = $claims->filter(function ($claim) {
            return $claim->step1_status === 'approved' || $claim->status === 'step1_approved';
        });
        $step2Pending = $claims->filter(function ($claim) {
            return $claim->step1_status === 'approved'
                && $claim->step2_approver_id
                && !in_array($claim->step2_status, ['approved', 'rejected', 'declined'], true);
        });
        $approvalPipeline = [
            ['label' => 'Submitted', 'count' => $claims->count(), 'amount' => $claimedTotal],
            ['label' => 'Step 1 Pending', 'count' => $step1Pending->count(), 'amount' => (float) $step1Pending->sum('claim_amount')],
            ['label' => 'Step 1 Approved', 'count' => $step1Approved->count(), 'amount' => (float) $step1Approved->sum('claim_amount')],
            ['label' => 'Step 2 Pending', 'count' => $step2Pending->count(), 'amount' => (float) $step2Pending->sum('claim_amount')],
            ['label' => 'Fully Approved', 'count' => $claims->whereIn('status', $approvedStatuses)->count(), 'amount' => $approvedTotal],
            ['label' => 'Settlement Pending', 'count' => $pendingSettlementClaims->count(), 'amount' => (float) $pendingSettlementClaims->sum('approved_amount')],
            ['label' => 'Paid', 'count' => $paidClaimCount, 'amount' => $paidTotal],
            ['label' => 'Rejected', 'count' => $claims->whereIn('status', $rejectedStatuses)->count(), 'amount' => $rejectedAmount],
        ];

        $pendingActions = collect([
            [
                'title' => 'Claims awaiting Step 1 approval',
                'count' => $step1Pending->count(),
                'amount' => (float) $step1Pending->sum('claim_amount'),
                'severity' => 'danger',
                'icon' => 'fa-circle-exclamation',
                'url' => url('/institute-admin/reimbursement-claims/approval-list'),
            ],
            [
                'title' => 'Claims awaiting Step 2 approval',
                'count' => $step2Pending->count(),
                'amount' => (float) $step2Pending->sum('claim_amount'),
                'severity' => 'warning',
                'icon' => 'fa-user-clock',
                'url' => url('/institute-admin/reimbursement-claims/approval-list'),
            ],
            [
                'title' => 'Claims pending settlement',
                'count' => $pendingSettlementClaims->count(),
                'amount' => (float) $pendingSettlementClaims->sum('approved_amount'),
                'severity' => 'warning',
                'icon' => 'fa-wallet',
                'url' => route('reimbursement.claims.admin.view'),
            ],
            [
                'title' => 'Failed payouts',
                'count' => $payouts->where('status', 'failed')->count(),
                'amount' => (float) $payouts->where('status', 'failed')->sum('payout_amount'),
                'severity' => 'danger',
                'icon' => 'fa-money-bill-wave',
                'url' => route('reimbursement.claims.admin.view'),
            ],
            [
                'title' => 'Claims missing required documents',
                'count' => $missingDocuments->count(),
                'amount' => (float) $missingDocuments->sum('claim_amount'),
                'severity' => 'warning',
                'icon' => 'fa-file-circle-exclamation',
                'url' => route('reimbursement.claims.admin.view'),
            ],
            [
                'title' => 'Claims exceeding policy limit',
                'count' => $limitViolations->count(),
                'amount' => (float) $limitViolations->sum('claim_amount'),
                'severity' => 'danger',
                'icon' => 'fa-scale-unbalanced',
                'url' => route('reimbursement.claims.admin.view'),
            ],
        ]);

        $exceptions = collect();
        $stalePending = $claims->whereIn('status', $pendingStatuses)->filter(function ($claim) use ($today) {
            $date = $this->dashboardCarbon($claim->submission_date ?: $claim->created_at);
            return $date && $date->diffInDays($today) > 10;
        });
        if ($stalePending->count() > 0) {
            $exceptions->push(['type' => 'danger', 'title' => 'Claims pending more than 10 days', 'count' => $stalePending->count()]);
        }
        if ($limitViolations->count() > 0) {
            $exceptions->push(['type' => 'danger', 'title' => 'Claims exceeding policy limit', 'count' => $limitViolations->count()]);
        }
        if ($payouts->where('status', 'failed')->count() > 0) {
            $exceptions->push(['type' => 'danger', 'title' => 'Failed payouts', 'count' => $payouts->where('status', 'failed')->count()]);
        }
        if ($missingDocuments->count() > 0) {
            $exceptions->push(['type' => 'warning', 'title' => 'Missing required documents', 'count' => $missingDocuments->count()]);
        }
        if ($duplicateClaims->count() > 0) {
            $exceptions->push(['type' => 'warning', 'title' => 'Possible duplicate claims', 'count' => $duplicateClaims->count()]);
        }
        $policyGaps = $policies->filter(function ($policy) use ($assignments) {
            return ($policy['status'] ?: 'active') === 'active'
                && $assignments->where('reimbursement_policy_id', $policy['reimbursement_policy_id'])->count() === 0;
        });
        if ($policyGaps->count() > 0) {
            $exceptions->push(['type' => 'info', 'title' => 'Active policies without assignment', 'count' => $policyGaps->count()]);
        }
        $expiringPolicies = $policies->filter(function ($policy) use ($today) {
            $date = $this->dashboardCarbon($policy['effective_to'] ?? null);
            return $date && $date->betweenIncluded($today, $today->copy()->addDays(30));
        });
        if ($expiringPolicies->count() > 0) {
            $exceptions->push(['type' => 'info', 'title' => 'Policies expiring in 30 days', 'count' => $expiringPolicies->count()]);
        }

        $agingBuckets = [
            '0-2 days' => 0,
            '3-5 days' => 0,
            '6-10 days' => 0,
            '11-30 days' => 0,
            '30+ days' => 0,
        ];
        $claims->whereIn('status', $pendingStatuses)->each(function ($claim) use (&$agingBuckets, $today) {
            $date = $this->dashboardCarbon($claim->submission_date ?: $claim->created_at);
            if (!$date) {
                return;
            }
            $days = $date->diffInDays($today);
            if ($days <= 2) {
                $agingBuckets['0-2 days']++;
            } elseif ($days <= 5) {
                $agingBuckets['3-5 days']++;
            } elseif ($days <= 10) {
                $agingBuckets['6-10 days']++;
            } elseif ($days <= 30) {
                $agingBuckets['11-30 days']++;
            } else {
                $agingBuckets['30+ days']++;
            }
        });

        $topEmployees = $claims->groupBy('employee_id')->map(function ($items, $employeeId) {
            $first = $items->first();

            return [
                'employee_id' => $employeeId,
                'name' => $first->name ?: $employeeId,
                'department' => $first->department_name ?: ($first->department ?: '-'),
                'claims' => $items->count(),
                'claimed' => (float) $items->sum('claim_amount'),
                'approved' => (float) $items->sum('approved_amount'),
            ];
        })->sortByDesc('claimed')->values()->take(8);

        $rejectionReasons = $approvals->whereIn('status', $rejectedStatuses)
            ->groupBy(function ($approval) {
                return $approval->decline_reason ?: 'Other';
            })->map(function ($items, $reason) {
                return [
                    'reason' => $reason,
                    'count' => $items->count(),
                    'amount' => (float) $items->sum('total_approved_amount'),
                ];
            })->sortByDesc('count')->values();

        if ($rejectionReasons->isEmpty()) {
            $rejectionReasons = $claims->whereIn('status', $rejectedStatuses)
                ->groupBy(function ($claim) {
                    if (str_contains(strtolower($claim->step1_remarks ?: $claim->step2_remarks ?: ''), 'limit')) {
                        return 'Policy limit exceeded';
                    }
                    return $claim->step1_remarks ?: $claim->step2_remarks ?: 'Other';
                })->map(function ($items, $reason) {
                    return [
                        'reason' => $reason,
                        'count' => $items->count(),
                        'amount' => (float) $items->sum('claim_amount'),
                    ];
                })->sortByDesc('count')->values();
        }

        $settlementSummary = [
            'pending' => [
                'count' => $pendingSettlementClaims->count() + $payouts->where('status', 'pending')->count(),
                'amount' => (float) $pendingSettlementClaims->sum('approved_amount') + (float) $payouts->where('status', 'pending')->sum('payout_amount'),
            ],
            'processing' => [
                'count' => $payouts->where('status', 'processing')->count(),
                'amount' => (float) $payouts->where('status', 'processing')->sum('payout_amount'),
            ],
            'paid' => [
                'count' => $paidClaimCount,
                'amount' => $paidTotal,
            ],
            'failed' => [
                'count' => $payouts->where('status', 'failed')->count(),
                'amount' => (float) $payouts->where('status', 'failed')->sum('payout_amount'),
            ],
        ];

        $approvalDurations = $claims->map(function ($claim) {
            $submitted = $this->dashboardCarbon($claim->submission_date ?: $claim->created_at);
            $step1 = $this->dashboardCarbon($claim->step1_approved_at);
            $step2 = $this->dashboardCarbon($claim->step2_approved_at);

            return [
                'department' => $claim->department_name ?: ($claim->department ?: 'Unassigned'),
                'step1_days' => $submitted && $step1 ? $submitted->floatDiffInDays($step1) : null,
                'step2_days' => $step1 && $step2 ? $step1->floatDiffInDays($step2) : null,
                'total_days' => $submitted && ($step2 ?: $step1) ? $submitted->floatDiffInDays($step2 ?: $step1) : null,
            ];
        });
        $departmentTimes = $approvalDurations->whereNotNull('total_days')->groupBy('department')->map(function ($items, $department) {
            return ['department' => $department, 'days' => round($items->avg('total_days'), 1)];
        })->values();
        $fastestDepartment = $departmentTimes->sortBy('days')->first();
        $slowestDepartment = $departmentTimes->sortByDesc('days')->first();

        $totalEmployees = Schema::hasTable('employee_details')
            ? EmployeeDetails::where('institute_id', $context['institute_id'])
                ->where('branch_id', $context['branch_id'])
                ->count()
            : 0;
        $assignedEmployees = $assignments->pluck('employee_id')->filter()->unique()->count();
        $eligibleEmployees = $claims->pluck('employee_id')->merge($assignments->pluck('employee_id'))->filter()->unique()->count();
        $inactivePolicies = $policies->filter(function ($policy) {
            return ($policy['status'] ?: 'active') !== 'active';
        })->count();

        $dashboard = [
            'filters' => [
                'range' => $selectedRange,
                'start_date' => $rangeStart ? $rangeStart->format('Y-m-d') : null,
                'end_date' => $rangeEnd ? $rangeEnd->format('Y-m-d') : null,
                'department' => $request->input('department', 'all'),
                'policy' => $request->input('policy', 'all'),
                'status' => $request->input('status', 'all'),
                'approval_stage' => $request->input('approval_stage', 'all'),
                'employee' => $request->input('employee', ''),
            ],
            'filterOptions' => [
                'departments' => $allClaims->map(function ($claim) use ($departmentNames, $resolveDepartmentLabel) {
                    $id = $claim->department_id ?: ($claim->department_name ?: $claim->department);
                    return [
                        'id' => $id,
                        'name' => $resolveDepartmentLabel($claim),
                    ];
                })->filter(fn($item) => !empty($item['id']))->unique('id')->values(),
                'policies' => $policies->map(fn($policy) => [
                    'id' => $policy['reimbursement_policy_id'],
                    'name' => $policy['policy_name'],
                ])->values(),
                'statuses' => $allClaims->pluck('status')->filter()->unique()->values(),
            ],
            'kpis' => [
                'total_policies' => $policies->count(),
                'active_policies' => $policies->where('status', 'active')->count(),
                'inactive_policies' => $inactivePolicies,
                'total_assignments' => $assignments->count(),
                'total_claims' => $claims->count(),
                'pending_claims' => $claims->whereIn('status', $pendingStatuses)->count(),
                'approved_claims' => $claims->whereIn('status', $approvedStatuses)->count(),
                'rejected_claims' => $claims->whereIn('status', $rejectedStatuses)->count(),
                'settled_claims' => $claims->where('status', 'settled')->count(),
                'pending_settlement_count' => $pendingSettlementClaims->count(),
                'pending_settlement_amount' => (float) $pendingSettlementClaims->sum('approved_amount'),
                'paid_count' => $paidClaimCount,
                'claimed_total' => $claimedTotal,
                'approved_total' => $approvedTotal,
                'paid_total' => $paidTotal,
                'pending_amount' => $pendingAmount,
                'rejected_amount' => $rejectedAmount,
                'this_month_count' => $thisMonthClaims->count(),
                'this_month_amount' => (float) $thisMonthClaims->sum('claim_amount'),
                'last_month_count' => $lastMonthClaims->count(),
            ],
            'statusCounts' => $statusCounts,
            'pendingActions' => $pendingActions,
            'recentClaims' => $claims->take(10)->values(),
            'approvalPipeline' => $approvalPipeline,
            'financialSummary' => [
                'claimed' => $claimedTotal,
                'calculated' => (float) $claims->sum('calculated_amount'),
                'approved' => $approvedTotal,
                'paid' => $paidTotal,
                'pending' => $pendingAmount,
                'deducted' => max(0, $claimedTotal - $approvedTotal),
            ],
            'policyUtilization' => $policyUtilization,
            'departmentAnalysis' => $departmentAnalysis,
            'categoryAnalysis' => $categoryAnalysis,
            'monthlyTrend' => $monthlyTrend,
            'settlements' => [
                'payouts' => $payouts->take(8)->values(),
                'approvals' => $approvals->whereNotNull('settlement_status')->take(8)->values(),
                'summary' => $settlementSummary,
                'pending' => $claims->where('status', 'approved')->count(),
                'completed' => $payouts->whereIn('status', $paidStatuses)->count() + $claims->where('status', 'settled')->count(),
                'failed' => $payouts->where('status', 'failed')->count(),
            ],
            'limitViolations' => $limitViolations->take(8)->values(),
            'limitHealth' => [
                'within' => max(0, $claims->count() - $nearLimitClaims->count() - $limitViolations->count()),
                'near' => $nearLimitClaims->count(),
                'exceeded' => $limitViolations->count(),
            ],
            'topEmployees' => $topEmployees,
            'rejectionReasons' => $rejectionReasons,
            'approvalTime' => [
                'step1' => round($approvalDurations->whereNotNull('step1_days')->avg('step1_days') ?: 0, 1),
                'step2' => round($approvalDurations->whereNotNull('step2_days')->avg('step2_days') ?: 0, 1),
                'total' => round($approvalDurations->whereNotNull('total_days')->avg('total_days') ?: 0, 1),
                'fastest_department' => $fastestDepartment['department'] ?? '-',
                'slowest_department' => $slowestDepartment['department'] ?? '-',
            ],
            'agingBuckets' => collect($agingBuckets),
            'employeeCoverage' => [
                'total' => $totalEmployees,
                'eligible' => $eligibleEmployees,
                'assigned' => $assignedEmployees,
                'unassigned' => max(0, $eligibleEmployees - $assignedEmployees),
            ],
            'policyHealth' => [
                'active' => $policies->where('status', 'active')->count(),
                'inactive' => $inactivePolicies,
                'expiring' => $expiringPolicies->count(),
                'no_assignments' => $policyGaps->count(),
                'over_utilized' => $policyUtilization->where('utilization', '>=', 100)->count(),
            ],
            'rejectedClaims' => $claims->whereIn('status', $rejectedStatuses)->take(8)->values(),
            'exceptions' => $exceptions,
            'generatedAt' => Carbon::now(),
        ];

        if ($request->filled('export')) {
            return $this->dashboardExport($dashboard, $request->input('export'));
        }

        return view('instituteAdmin.Reimbursement.ReimbursementDashboard', compact('dashboard'));
    }

    private function dashboardExport(array $dashboard, string $format)
    {
        $format = strtolower($format);
        $filename = 'reimbursement-dashboard-' . Carbon::now()->format('Ymd-His');

        if ($format === 'pdf') {
            $rows = collect($dashboard['kpis'])->map(function ($value, $label) {
                return '<tr><td>' . e(ucwords(str_replace('_', ' ', $label))) . '</td><td>' . e((string) $value) . '</td></tr>';
            })->implode('');
            $html = '<h1>Reimbursement Dashboard</h1><p>Generated at '
                . e($dashboard['generatedAt']->format('d M Y, h:i A'))
                . '</p><table border="1" cellpadding="6" cellspacing="0" width="100%">'
                . '<thead><tr><th>Metric</th><th>Value</th></tr></thead><tbody>'
                . $rows
                . '</tbody></table>';

            return \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->download($filename . '.pdf');
        }

        $rows = [
            ['Section', 'Metric', 'Count', 'Amount'],
            ['KPI', 'Total Claims', $dashboard['kpis']['total_claims'], $dashboard['kpis']['claimed_total']],
            ['KPI', 'Pending Approval', $dashboard['kpis']['pending_claims'], $dashboard['kpis']['pending_amount']],
            ['KPI', 'Approved Claims', $dashboard['kpis']['approved_claims'], $dashboard['kpis']['approved_total']],
            ['KPI', 'Rejected Claims', $dashboard['kpis']['rejected_claims'], $dashboard['kpis']['rejected_amount']],
            ['KPI', 'Pending Settlement', $dashboard['kpis']['pending_settlement_count'], $dashboard['kpis']['pending_settlement_amount']],
            ['KPI', 'Paid / Settled', $dashboard['kpis']['paid_count'], $dashboard['kpis']['paid_total']],
        ];

        foreach ($dashboard['departmentAnalysis'] as $department) {
            $rows[] = ['Department', $department['department'], $department['claims'], $department['claimed']];
        }

        foreach ($dashboard['policyUtilization'] as $policy) {
            $rows[] = ['Policy', $policy['name'], $policy['claims'], $policy['approved']];
        }

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $filename . ($format === 'excel' ? '.csv' : '.csv'), [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function dashboardApplyFilters($claims, Request $request, ?Carbon $start, ?Carbon $end)
    {
        return $claims->filter(function ($claim) use ($request, $start, $end) {
            $date = $this->dashboardCarbon($claim->submission_date ?: $claim->created_at);
            if ($start && $end && (!$date || !$date->betweenIncluded($start, $end))) {
                return false;
            }

            if ($request->filled('department') && $request->department !== 'all') {
                $department = $claim->department_id ?: ($claim->department_name ?: $claim->department);
                if ((string) $department !== (string) $request->department) {
                    return false;
                }
            }

            if ($request->filled('policy') && $request->policy !== 'all' && (string) $claim->reimbursement_policy_id !== (string) $request->policy) {
                return false;
            }

            if ($request->filled('status') && $request->status !== 'all' && (string) $claim->status !== (string) $request->status) {
                return false;
            }

            if ($request->filled('approval_stage') && $request->approval_stage !== 'all' && $this->dashboardApprovalStage($claim) !== $request->approval_stage) {
                return false;
            }

            if ($request->filled('employee')) {
                $needle = strtolower($request->employee);
                $haystack = strtolower(($claim->employee_id ?: '') . ' ' . ($claim->name ?: ''));
                if (!str_contains($haystack, $needle)) {
                    return false;
                }
            }

            return true;
        });
    }

    private function dashboardDateRange(Request $request, Carbon $today): array
    {
        $range = $request->input('range', 'last_6_months');

        if ($range === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            return [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay(), $range];
        }

        if ($range === 'this_month') {
            return [$today->copy()->startOfMonth(), $today->copy()->endOfDay(), $range];
        }

        if ($range === 'last_3_months') {
            return [$today->copy()->subMonthsNoOverflow(2)->startOfMonth(), $today->copy()->endOfDay(), $range];
        }

        if ($range === 'this_financial_year') {
            $startYear = $today->month >= 4 ? $today->year : $today->year - 1;
            return [Carbon::create($startYear, 4, 1)->startOfDay(), Carbon::create($startYear + 1, 3, 31)->endOfDay(), $range];
        }

        if ($range === 'previous_financial_year') {
            $startYear = ($today->month >= 4 ? $today->year : $today->year - 1) - 1;
            return [Carbon::create($startYear, 4, 1)->startOfDay(), Carbon::create($startYear + 1, 3, 31)->endOfDay(), $range];
        }

        return [$today->copy()->subMonthsNoOverflow(5)->startOfMonth(), $today->copy()->endOfDay(), 'last_6_months'];
    }

    private function dashboardApprovalStage($claim): string
    {
        if (in_array($claim->status, ['rejected', 'declined'], true)) {
            return 'rejected';
        }

        if (in_array($claim->status, ['settled', 'completed'], true)) {
            return 'paid';
        }

        if ($claim->status === 'approved') {
            return 'settlement_pending';
        }

        if ($claim->step1_status === 'approved' && $claim->step2_approver_id && $claim->step2_status !== 'approved') {
            return 'step2_pending';
        }

        if (($claim->step1_status ?: $claim->status) === 'pending') {
            return 'step1_pending';
        }

        return 'submitted';
    }

    private function getDashboardPolicies(array $context)
    {
        $models = [
            'travel' => ReimbursementTravelPolicy::class,
            'accommodation' => ReimbursementAccommodationPolicy::class,
            'food' => ReimbursementFoodPolicy::class,
            'other' => ReimbursementOtherPolicies::class,
        ];

        return collect($models)->flatMap(function ($model, $category) use ($context) {
            return $model::where('institute_id', $context['institute_id'])
                ->where('branch_id', $context['branch_id'])
                ->get()
                ->map(function ($policy) use ($category) {
                    return [
                        'reimbursement_policy_id' => $policy->reimbursement_policy_id,
                        'policy_name' => $policy->policy_name,
                        'policy_category' => $policy->policy_category ?: $category,
                        'status' => $policy->status ?: 'active',
                        'frequency_type' => $policy->frequency_type,
                        'effective_from' => $policy->effective_from,
                        'effective_to' => $policy->effective_to,
                        'settlement_mode' => $policy->settlement_mode,
                        'settlement_timeline' => $policy->settlement_timeline,
                        'limit_amount' => $this->dashboardPolicyLimit($policy, $category),
                    ];
                });
        })->values();
    }

    private function dashboardPolicyLimit($policy, string $category): float
    {
        $fields = [
            'travel' => ['two_wheeler_max', 'car_max', 'auto_max'],
            'accommodation' => ['basic_max', 'deluxe_max', 'premium_max'],
            'food' => ['breakfast_max', 'lunch_max', 'dinner_max', 'two_meals_max', 'three_meals_max'],
            'other' => ['max_amount'],
        ][$category] ?? ['max_amount'];

        return (float) collect($fields)->map(function ($field) use ($policy) {
            return (float) ($policy->{$field} ?? 0);
        })->max();
    }

    private function dashboardDateBetween($value, Carbon $start, Carbon $end): bool
    {
        $date = $this->dashboardCarbon($value);
        return $date && $date->betweenIncluded($start, $end);
    }

    private function dashboardCarbon($value): ?Carbon
    {
        if (!$value) {
            return null;
        }

        return $value instanceof Carbon ? $value : Carbon::parse($value);
    }


    public function getEmployeeDetails(Request $request)
    {
        try {
            $context = $this->getInstituteContext();

            $employee = EmployeeDetails::where('user_id', Auth::user()->id)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Logged-in employee not found'
                ], 404);
            }

            $designation_name = $employee->designation ?? 'N/A';
            $department_name = $employee->department ?? 'N/A';

            if ($employee->designation_id) {
                $designation = Designations::find($employee->designation_id);

                if ($designation) {
                    $designation_name =
                        $designation->designations ??
                        $designation->name ??
                        $designation_name;
                }
            }

            if ($employee->department_id) {
                $department = Departments::find($employee->department_id);

                if ($department) {
                    $department_name =
                        $department->department ??
                        $department->name ??
                        $department_name;
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $employee->id,
                    'employee_id' => $employee->employee_id,
                    'name' => $employee->name ?? 'Employee',
                    'designation' => $designation_name,
                    'designation_id' => $employee->designation_id,
                    'department' => $department_name,
                    'department_id' => $employee->department_id,
                ]
            ]);

        } catch (\Exception $e) {

            \Log::error(
                'Failed to load logged-in employee: ' .
                $e->getMessage()
            );

            return response()->json([
                'success' => false,
                'message' => 'Failed to load employee details.'
            ], 500);
        }
    }

    public function getAssignedPolicies(Request $request)
    {
        try {
            $context = $this->getInstituteContext();
            $employeeId = $request->employee_id;

            $employee = EmployeeDetails::where('employee_id', $employeeId)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if (!$employee) {
                return response()->json(['success' => false, 'message' => 'Employee not found', 'data' => []]);
            }

            $designationId = $employee->designation_id;
            $departmentId = $employee->department_id;

            $assignments = ReimbursementPolicyAssignment::where('institute_id', $context['institute_id'])
                ->where('branch_id', $context['branch_id'])
                ->where('status', 'active')
                ->where(function ($query) use ($designationId, $departmentId) {
                    $query->where('designation_id', $designationId)
                        ->orWhere('department_id', $departmentId)
                        ->orWhere('department_id', 'all');
                })
                ->get();

            $policies = [];
            foreach ($assignments as $assignment) {
                $policy = $this->findPolicyById($assignment->reimbursement_policy_id, $context);

                // ✅ ONLY show active policies for claiming - skip upcoming, expired, inactive
                if (!$policy || $policy->status !== 'active') {
                    continue;
                }

                if ($policy) {
                    $assignedRangesRaw = $assignment->allowed_ranges;
                    if (is_string($assignedRangesRaw)) {
                        $assignedRangesRaw = json_decode($assignedRangesRaw, true) ?? [];
                    }

                    $allowedRanges = $this->buildFullAllowedRangesForPolicy($policy, $assignedRangesRaw);

                    $category = $policy->policy_category;
                    $fixedCategories = ['mobile', 'internet', 'entertainment', 'miscellaneous'];

                    $minAmount = $policy->min_amount ?? null;
                    $maxAmount = $policy->max_amount ?? null;

                    if (in_array($category, $fixedCategories) && !empty($allowedRanges)) {
                        if (is_array($allowedRanges)) {
                            foreach ($allowedRanges as $range) {
                                if (is_array($range) && isset($range['min']) && isset($range['max'])) {
                                    $minAmount = $range['min'];
                                    $maxAmount = $range['max'];
                                    break;
                                }
                            }
                        }
                    }

                    // Get existing claim count for frequency display
                    $existingClaimCount = $this->getExistingClaimCountForDisplay(
                        $employee->employee_id,
                        $policy->reimbursement_policy_id,
                        $policy->frequency_type ?? 'unlimited',
                        $context
                    );

                    $policies[] = [
                        'reimbursement_policy_id' => $policy->reimbursement_policy_id,
                        'policy_name' => $policy->policy_name,
                        'policy_category' => $category,
                        'description' => $policy->description,
                        'allowed_ranges' => $allowedRanges,
                        'calculation_type' => $policy->calculation_type ?? 'per_claim',
                        'max_amount' => $maxAmount ?? 0,
                        'min_amount' => $minAmount ?? 0,
                        'frequency_type' => $policy->frequency_type ?? 'unlimited',
                        'frequency_value' => $policy->frequency_value ?? 1,
                        'submission_within' => $policy->submission_within ?? 7,
                        'submission_type' => $policy->submission_type ?? 'expense_date',
                        'allow_actual_amount' => $policy->allow_actual_amount ?? 'No',
                        'remarks_mandatory' => $policy->remarks_mandatory ?? 'No',
                        'bill_mandatory' => $policy->bill_mandatory ?? 'Yes',
                        'vendor_mandatory' => $policy->vendor_mandatory ?? 'No',
                        'policy_data' => $policy->policy_data ?? null,
                        'allow_multi_bills' => $policy->allow_multi_bills ?? 'Yes',
                        'max_bills' => $policy->max_bills ?? 5,
                        'allow_same_bill' => $policy->allow_same_bill ?? 'No',
                        'settlement_timeline' => $policy->settlement_timeline ?? 7,
                        'settlement_mode' => $policy->settlement_mode ?? null,
                        'auto_settlement' => $policy->auto_settlement ?? 'No',
                        'allow_partial_settlement' => $policy->allow_partial_settlement ?? 'Yes',
                        'status' => $policy->status ?? 'active',
                        'bill_required' => $policy->bill_required ?? 'Yes',
                        'photo_required' => $policy->photo_required ?? 'No',
                        'remarks' => $policy->remarks ?? null,
                        'existing_claim_count' => $existingClaimCount,
                        'financial_year' => $policy->financial_year ?? null,
                        'two_wheeler_min' => $policy->two_wheeler_min ?? null,
                        'two_wheeler_max' => $policy->two_wheeler_max ?? null,
                        'two_wheeler_rate_km' => $policy->two_wheeler_rate_km ?? null,
                        'two_wheeler_bill_required' => $policy->two_wheeler_bill_required ?? null,
                        'two_wheeler_photo_required' => $policy->two_wheeler_photo_required ?? null,
                        'car_min' => $policy->car_min ?? null,
                        'car_max' => $policy->car_max ?? null,
                        'car_rate_km' => $policy->car_rate_km ?? null,
                        'car_bill_required' => $policy->car_bill_required ?? null,
                        'car_photo_required' => $policy->car_photo_required ?? null,
                        'auto_min' => $policy->auto_min ?? null,
                        'auto_max' => $policy->auto_max ?? null,
                        'auto_rate_km' => $policy->auto_rate_km ?? null,
                        'auto_bill_required' => $policy->auto_bill_required ?? null,
                        'auto_photo_required' => $policy->auto_photo_required ?? null,
                        'bus_categories' => $policy->bus_categories ?? null,
                        'train_categories' => $policy->train_categories ?? null,
                        'flight_categories' => $policy->flight_categories ?? null,
                        'basic_min' => $policy->basic_min ?? null,
                        'basic_max' => $policy->basic_max ?? null,
                        'basic_includes_food' => $policy->basic_includes_food ?? null,
                        'basic_bill_required' => $policy->basic_bill_required ?? null,
                        'basic_photo_required' => $policy->basic_photo_required ?? null,
                        'deluxe_min' => $policy->deluxe_min ?? null,
                        'deluxe_max' => $policy->deluxe_max ?? null,
                        'deluxe_includes_food' => $policy->deluxe_includes_food ?? null,
                        'deluxe_bill_required' => $policy->deluxe_bill_required ?? null,
                        'deluxe_photo_required' => $policy->deluxe_photo_required ?? null,
                        'premium_min' => $policy->premium_min ?? null,
                        'premium_max' => $policy->premium_max ?? null,
                        'premium_includes_food' => $policy->premium_includes_food ?? null,
                        'premium_bill_required' => $policy->premium_bill_required ?? null,
                        'premium_photo_required' => $policy->premium_photo_required ?? null,
                        'breakfast_min' => $policy->breakfast_min ?? null,
                        'breakfast_max' => $policy->breakfast_max ?? null,
                        'lunch_min' => $policy->lunch_min ?? null,
                        'lunch_max' => $policy->lunch_max ?? null,
                        'dinner_min' => $policy->dinner_min ?? null,
                        'dinner_max' => $policy->dinner_max ?? null,
                        'individual_bill_required' => $policy->individual_bill_required ?? null,
                        'individual_photo_required' => $policy->individual_photo_required ?? null,
                        'two_meals_min' => $policy->two_meals_min ?? null,
                        'two_meals_max' => $policy->two_meals_max ?? null,
                        'two_meals_bill_required' => $policy->two_meals_bill_required ?? null,
                        'two_meals_photo_required' => $policy->two_meals_photo_required ?? null,
                        'three_meals_min' => $policy->three_meals_min ?? null,
                        'three_meals_max' => $policy->three_meals_max ?? null,
                        'three_meals_bill_required' => $policy->three_meals_bill_required ?? null,
                        'three_meals_photo_required' => $policy->three_meals_photo_required ?? null,
                    ];
                }
            }

            return response()->json(['success' => true, 'data' => $policies]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load policies: ' . $e->getMessage()
            ], 500);
        }
    }

    private function extractRangeFromPolicy($policy, $selectedRange, $category)
    {
        $result = ['min' => 0, 'max' => 0, 'bill' => 'Yes', 'photo' => 'No', 'ruleType' => 'actual', 'rate' => null, 'unit' => null];
        $sLower = strtolower(trim($selectedRange));

        if ($category === 'travel') {
            // Private vehicles
            $privateMap = [
                'two wheeler' => 'two_wheeler',
                'two_wheeler' => 'two_wheeler',
                '2 wheeler' => 'two_wheeler',
                'car' => 'car',
                'auto' => 'auto'
            ];
            if (isset($privateMap[$sLower])) {
                $key = $privateMap[$sLower];
                $result['min'] = floatval($policy->{$key . '_min'} ?? 0);
                $result['max'] = floatval($policy->{$key . '_max'} ?? 0);
                $result['bill'] = $policy->{$key . '_bill_required'} ?? 'Yes';
                $result['photo'] = $policy->{$key . '_photo_required'} ?? 'No';
                $result['rate'] = floatval($policy->{$key . '_rate_km'} ?? 0);
                $result['ruleType'] = $result['rate'] > 0 ? 'per_km' : 'actual';
                return $result;
            }

            // Bus categories
            if ($policy->bus_categories) {
                $busCat = is_string($policy->bus_categories) ? json_decode($policy->bus_categories, true) : $policy->bus_categories;
                $busMap = [
                    'general bus' => 'general',
                    'general' => 'general',
                    'seater ac' => 'seater_ac',
                    'seater non-ac' => 'seater_nonac',
                    'seater non ac' => 'seater_nonac',
                    'sleeper ac' => 'sleeper_ac',
                    'sleeper non-ac' => 'sleeper_nonac',
                    'sleeper non ac' => 'sleeper_nonac'
                ];
                if (isset($busMap[$sLower]) && isset($busCat[$busMap[$sLower]])) {
                    $subData = $busCat[$busMap[$sLower]];
                    $result['min'] = floatval($subData['min'] ?? 0);
                    $result['max'] = floatval($subData['max'] ?? 0);
                    $result['bill'] = $subData['bill_required'] ?? 'Yes';
                    $result['photo'] = $subData['photo_required'] ?? 'No';
                    $result['ruleType'] = 'actual';
                    return $result;
                }
            }

            // Train categories
            if ($policy->train_categories) {
                $trainCat = is_string($policy->train_categories) ? json_decode($policy->train_categories, true) : $policy->train_categories;
                $trainMap = [
                    'general' => 'general',
                    'sleeper class' => 'sleeper',
                    'sleeper' => 'sleeper',
                    '3ac' => 'ac3',
                    '2ac' => 'ac2',
                    '1ac' => 'ac1'
                ];
                if (isset($trainMap[$sLower]) && isset($trainCat[$trainMap[$sLower]])) {
                    $subData = $trainCat[$trainMap[$sLower]];
                    $result['min'] = floatval($subData['min'] ?? 0);
                    $result['max'] = floatval($subData['max'] ?? 0);
                    $result['bill'] = $subData['bill_required'] ?? 'Yes';
                    $result['photo'] = $subData['photo_required'] ?? 'No';
                    $result['ruleType'] = 'actual';
                    return $result;
                }
            }

            // Flight categories
            if ($policy->flight_categories) {
                $flightCat = is_string($policy->flight_categories) ? json_decode($policy->flight_categories, true) : $policy->flight_categories;
                $flightMap = [
                    'economy' => 'economy',
                    'business class' => 'business',
                    'business' => 'business'
                ];
                if (isset($flightMap[$sLower]) && isset($flightCat[$flightMap[$sLower]])) {
                    $subData = $flightCat[$flightMap[$sLower]];
                    $result['min'] = floatval($subData['min'] ?? 0);
                    $result['max'] = floatval($subData['max'] ?? 0);
                    $result['bill'] = $subData['bill_required'] ?? 'Yes';
                    $result['photo'] = $subData['photo_required'] ?? 'No';
                    $result['ruleType'] = 'actual';
                    return $result;
                }
            }
        }

        if ($category === 'accommodation') {
            $map = [
                'budget' => 'basic',
                'basic' => 'basic',
                '1-2 star (budget)' => 'basic',
                '1-2 star' => 'basic',
                'business' => 'deluxe',
                'deluxe' => 'deluxe',
                '3-4 star (business)' => 'deluxe',
                '3-4 star' => 'deluxe',
                'premium' => 'premium',
                '5 star (premium)' => 'premium',
                '5 star' => 'premium'
            ];
            if (isset($map[$sLower])) {
                $key = $map[$sLower];
                $result['min'] = floatval($policy->{$key . '_min'} ?? 0);
                $result['max'] = floatval($policy->{$key . '_max'} ?? 0);
                $result['bill'] = $policy->{$key . '_bill_required'} ?? 'Yes';
                $result['photo'] = $policy->{$key . '_photo_required'} ?? 'No';
                $result['ruleType'] = 'actual';
                $result['rate'] = floatval($policy->{$key . '_max'} ?? 0);
                return $result;
            }
        }

        if ($category === 'food') {
            $map = [
                'breakfast' => 'breakfast',
                'lunch' => 'lunch',
                'dinner' => 'dinner',
                'two meals' => 'two_meals',
                'two meals (combined)' => 'two_meals',
                'three meals' => 'three_meals',
                'three meals (full day)' => 'three_meals'
            ];
            if (isset($map[$sLower])) {
                $key = $map[$sLower];
                $result['min'] = floatval($policy->{$key . '_min'} ?? 0);
                $result['max'] = floatval($policy->{$key . '_max'} ?? 0);
                $result['bill'] = $policy->individual_bill_required ?? $policy->{$key . '_bill_required'} ?? 'Yes';
                $result['photo'] = $policy->individual_photo_required ?? $policy->{$key . '_photo_required'} ?? 'No';
                $result['ruleType'] = 'actual';
                return $result;
            }
        }

        if ($category === 'custom') {
            $policyData = $policy->policy_data;
            if (is_string($policyData)) {
                $policyData = json_decode($policyData, true) ?? [];
            }
            if (is_array($policyData)) {
                foreach ($policyData as $rule) {
                    if (isset($rule['name']) && strtolower(trim($rule['name'])) === $sLower) {
                        $fields = $rule['fields'] ?? [];
                        $result['ruleType'] = $rule['type'] ?? 'actual';
                        $result['min'] = floatval($fields['min'] ?? $fields['min_amount'] ?? 0);
                        $result['max'] = floatval($fields['max'] ?? $fields['max_amount'] ?? 0);
                        $result['rate'] = floatval($fields['rate'] ?? $fields['rate_km'] ?? $fields['rate_night'] ?? $fields['rate_day'] ?? 0);
                        $result['unit'] = $fields['unit'] ?? null;
                        $result['bill'] = $rule['bill_required'] ?? 'Yes';
                        $result['photo'] = $rule['photo_required'] ?? 'No';
                        return $result;
                    }
                }
            }
        }

        // Fixed policies
        $result['min'] = floatval($policy->min_amount ?? 0);
        $result['max'] = floatval($policy->max_amount ?? 0);
        $result['bill'] = $policy->bill_required ?? 'Yes';
        $result['photo'] = $policy->photo_required ?? 'No';
        $result['ruleType'] = 'actual';

        return $result;
    }

    public function storeBulk(Request $request)
    {
        try {
            DB::beginTransaction();
            $context = $this->getInstituteContext();

            $claimsData = $request->input('claims');

            if (!$claimsData || !is_array($claimsData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No claims data provided'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'employee_id' => 'required',
                'claims' => 'required|array|min:1',
                'claims.*.reimbursement_policy_id' => 'required',
                'claims.*.claim_amount' => 'required|numeric|min:0',
                'claims.*.expense_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $employee = EmployeeDetails::where('employee_id', $request->employee_id)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if (!$employee) {
                return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
            }

            // Validate frequency limits
            $policyClaimsMap = [];
            foreach ($request->claims as $claimData) {
                $policyId = $claimData['reimbursement_policy_id'];
                if (!isset($policyClaimsMap[$policyId])) {
                    $policyClaimsMap[$policyId] = [];
                }
                $policyClaimsMap[$policyId][] = $claimData;
            }

            foreach ($policyClaimsMap as $policyId => $claims) {
                $policy = $this->findPolicyById($policyId, $context);
                if (!$policy)
                    continue;

                // ✅ Check policy status - only active policies can be claimed
                if ($policy->status !== 'active') {
                    $statusMessages = [
                        'upcoming' => "Policy '{$policy->policy_name}' is not yet active. Claims cannot be submitted against upcoming policies.",
                        'expired' => "Policy '{$policy->policy_name}' has expired. Claims cannot be submitted.",
                        'inactive' => "Policy '{$policy->policy_name}' is currently inactive."
                    ];
                    $message = $statusMessages[$policy->status] ?? "Policy '{$policy->policy_name}' is not active.";

                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }

                $frequencyType = $policy->frequency_type ?? 'unlimited';
                $frequencyValue = intval($policy->frequency_value ?? 1);

                if ($frequencyType !== 'unlimited') {
                    $claimExpenseDate = $claims[0]['expense_date'] ?? null;
                    $existingCount = $this->getExistingClaimCount(
                        $employee->employee_id,
                        $policyId,
                        $frequencyType,
                        $context,
                        $claimExpenseDate
                    );

                    $newClaimsCount = 1;

                    if (($existingCount + $newClaimsCount) > $frequencyValue) {
                        // Only add a warning, do not block
                        $warnings[] = "Policy '{$policy->policy_name}' frequency exceeded. Current: {$existingCount}, max: {$frequencyValue}.";
                        // continue processing
                    }
                }
            }

            $masterClaimId = $this->generateMasterRequestId();
            $createdClaims = [];
            $errors = [];
            $warnings = [];

            foreach ($request->claims as $index => $claimData) {
                try {
                    $policy = $this->findPolicyById($claimData['reimbursement_policy_id'], $context);
                    if (!$policy) {
                        $errors[] = "Claim #" . ($index + 1) . ": Policy not found";
                        continue;
                    }

                    // Validate calculation type limits
                    $calculationTypeValidation = $this->validateCalculationTypeLimits(
                        $policy,
                        $claimData,
                        $employee,
                        $context
                    );

                    $isExceeded = !$calculationTypeValidation['valid'];
                    $exceedReason = $isExceeded ? $calculationTypeValidation['message'] : null;

                    // Get assignment for approvers
                    $assignment = ReimbursementPolicyAssignment::where('reimbursement_policy_id', $claimData['reimbursement_policy_id'])
                        ->where('institute_id', $context['institute_id'])
                        ->where('status', 'active')
                        ->where(function ($query) use ($employee) {
                            $query->where('designation_id', $employee->designation_id)
                                ->orWhere('department_id', $employee->department_id)
                                ->orWhere('department_id', 'all')
                                ->orWhere('employee_id', $employee->employee_id);
                        })->first();

                    // Get approver details - FIXED: Only use columns that exist
                    $step1ApproverDesignationId = null;
                    $step1ApproverDesignationName = null;
                    $step2ApproverDesignationId = null;
                    $step2ApproverDesignationName = null;

                    if ($assignment) {
                        // Step 1 approver
                        $step1Val = $assignment->step1_approver;
                        if ($step1Val) {
                            // Only query by designation_id and designations columns
                            $desig = Designations::where('designation_id', $step1Val)
                                ->orWhere('designations', $step1Val)
                                ->first();
                            if ($desig) {
                                $step1ApproverDesignationId = $desig->designation_id;
                                $step1ApproverDesignationName = $desig->designations;
                            } else {
                                $step1ApproverDesignationId = $step1Val;
                                $step1ApproverDesignationName = $step1Val;
                            }
                        }

                        // Step 2 approver
                        $step2Val = $assignment->step2_approver;
                        if ($step2Val) {
                            $desig = Designations::where('designation_id', $step2Val)
                                ->orWhere('designations', $step2Val)
                                ->first();
                            if ($desig) {
                                $step2ApproverDesignationId = $desig->designation_id;
                                $step2ApproverDesignationName = $desig->designations;
                            } else {
                                $step2ApproverDesignationId = $step2Val;
                                $step2ApproverDesignationName = $step2Val;
                            }
                        }
                    }

                    $designation_name = $employee->designation ?? 'N/A';
                    $department_name = $employee->department ?? 'N/A';
                    $individualClaimId = $this->generateClaimId();

                    // Process sub-entries data
                    $subEntriesData = [];
                    if (isset($claimData['sub_entries'])) {
                        $subEntriesData = is_string($claimData['sub_entries'])
                            ? (json_decode($claimData['sub_entries'], true) ?? [])
                            : $claimData['sub_entries'];
                    }

                    // Handle bill and photo attachments
                    $allBillPaths = [];
                    $allBillOriginals = [];
                    $allPhotoPaths = [];
                    $allPhotoOriginals = [];

                    if ($request->hasFile("claims.{$index}.bill_attachments")) {
                        foreach ($request->file("claims.{$index}.bill_attachments") as $file) {
                            $allBillPaths[] = $file->store('reimbursement-bills', 'public');
                            $allBillOriginals[] = $file->getClientOriginalName();
                        }
                    }

                    if ($request->hasFile("claims.{$index}.photo_attachments")) {
                        foreach ($request->file("claims.{$index}.photo_attachments") as $file) {
                            $allPhotoPaths[] = $file->store('reimbursement-photos', 'public');
                            $allPhotoOriginals[] = $file->getClientOriginalName();
                        }
                    }

                    $calcType = $policy->calculation_type ?? 'per_claim';

                    $remLimitAtSub = isset($claimData['remaining_limit_at_submission']) ? floatval($claimData['remaining_limit_at_submission']) : null;
                    if ($remLimitAtSub === null && floatval($policy->max_amount ?? 0) > 0) {
                        $existingInPeriod = floatval(ReimbursementClaim::where('employee_id', $employee->employee_id)
                            ->where('reimbursement_policy_id', $claimData['reimbursement_policy_id'])
                            ->where('institute_id', $context['institute_id'])
                            ->where('status', '!=', 'rejected')
                            ->whereMonth('expense_date', date('m', strtotime($claimData['expense_date'])))
                            ->whereYear('expense_date', date('Y', strtotime($claimData['expense_date'])))
                            ->sum('claim_amount'));
                        $remLimitAtSub = max(0, floatval($policy->max_amount) - ($existingInPeriod + floatval($claimData['claim_amount'])));
                    }

                    // Create claim
                    $claimDataArray = [
                        'master_request_id' => $masterClaimId,
                        'reimbursement_request_id' => $individualClaimId,
                        'institute_id' => $context['institute_id'],
                        'branch_id' => $context['branch_id'],
                        'employee_id' => $employee->employee_id,
                        'name' => $employee->name ?? null,
                        'designation' => $designation_name,
                        'designation_id' => $employee->designation_id ?? null,
                        'department_id' => $employee->department_id ?? null,
                        'department' => $department_name,
                        'reimbursement_policy_id' => $claimData['reimbursement_policy_id'],
                        'policy_name' => $claimData['policy_name'] ?? $policy->policy_name ?? null,
                        'policy_category' => $claimData['policy_category'] ?? $policy->policy_category ?? null,
                        'claim_amount' => $claimData['claim_amount'],
                        'remaining_limit_at_submission' => $remLimitAtSub,
                        'calculated_amount' => $claimData['calculated_amount'] ?? $claimData['claim_amount'],
                        'calculation_type' => $calcType,
                        'expense_date' => $claimData['expense_date'],
                        'to_date' => $claimData['to_date'] ?? $claimData['expense_date'],
                        'submission_date' => Carbon::now()->toDateString(),
                        'remarks' => $claimData['remarks'] ?? null,
                        'bill_attachment' => !empty($allBillPaths) ? json_encode($allBillPaths) : null,
                        'bill_attachment_original' => !empty($allBillOriginals) ? json_encode($allBillOriginals) : null,
                        'photo_attachment' => !empty($allPhotoPaths) ? json_encode($allPhotoPaths) : null,
                        'photo_attachment_original' => !empty($allPhotoOriginals) ? json_encode($allPhotoOriginals) : null,
                        'bill_required' => $claimData['billRequired'] ?? true,
                        'photo_required' => $claimData['photoRequired'] ?? false,
                        'status' => 'pending',
                        'entry_order' => $index,
                        'created_by' => Auth::user()->employee_id ?? null,
                    ];

                    $claim = ReimbursementClaim::create($claimDataArray);

                    // Save sub-entry details
                    if (!empty($subEntriesData)) {
                        $allowedRanges = $this->buildFullAllowedRangesForPolicy($policy);
                        $rangeMaxMap = [];
                        foreach ($allowedRanges as $r) {
                            if (is_array($r) && !empty($r['name'])) {
                                $rangeMaxMap[$r['name']] = floatval($r['max'] ?? 0);
                            }
                        }

                        foreach ($subEntriesData as &$item) {
                            if (!is_array($item))
                                continue;
                            $rName = $item['range_name'] ?? 'Standard';
                            $sDate = $item['meal_date'] ?? $item['travel_date'] ?? $item['checkin_date'] ?? $item['expense_date'] ?? $item['date'] ?? $claimData['expense_date'];
                            $amt = floatval($item['amount'] ?? 0);
                            $rMax = floatval($item['max_limit'] ?? $rangeMaxMap[$rName] ?? 0);

                            // Calculate existing DB amount for this date + range
                            $existingDBSubAmount = 0;
                            $dbSubEntries = ReimbursementClaimSubEntry::where('employee_id', $employee->employee_id)
                                ->where('reimbursement_policy_id', $claimData['reimbursement_policy_id'])
                                ->where('institute_id', $context['institute_id'])
                                ->whereHas('claim', function ($q) {
                                    $q->where('status', '!=', 'rejected');
                                })->get();

                            foreach ($dbSubEntries as $dbSe) {
                                $dData = is_string($dbSe->sub_entry_data) ? json_decode($dbSe->sub_entry_data, true) : $dbSe->sub_entry_data;
                                if (is_array($dData)) {
                                    foreach ($dData as $dItem) {
                                        if (!is_array($dItem))
                                            continue;
                                        $itemRange = $dItem['range_name'] ?? 'Standard';
                                        $itemDate = $dItem['meal_date'] ?? $dItem['travel_date'] ?? $dItem['checkin_date'] ?? $dItem['expense_date'] ?? $dItem['date'] ?? null;
                                        if (!$itemDate && $dbSe->claim) {
                                            $itemDate = $dbSe->claim->expense_date;
                                        }
                                        if ($itemDate === $sDate && $itemRange === $rName) {
                                            $existingDBSubAmount += floatval($dItem['amount'] ?? 0);
                                        }
                                    }
                                }
                            }

                            $item['range_max_limit'] = $rMax;
                            $item['range_existing_claimed'] = $existingDBSubAmount;
                            $item['range_remaining_limit'] = $rMax > 0 ? max(0, $rMax - ($existingDBSubAmount + $amt)) : null;
                            $item['is_exceeded'] = $rMax > 0 ? (($existingDBSubAmount + $amt) > $rMax) : false;
                            $item['exceeded_amount'] = ($rMax > 0 && ($existingDBSubAmount + $amt) > $rMax) ? (($existingDBSubAmount + $amt) - $rMax) : 0;
                        }
                        unset($item);

                        ReimbursementClaimSubEntry::create([
                            'master_request_id' => $masterClaimId,
                            'reimbursement_request_id' => $individualClaimId,
                            'reimbursement_claim_id' => $claim->id,
                            'institute_id' => $context['institute_id'],
                            'branch_id' => $context['branch_id'],
                            'employee_id' => $employee->employee_id,
                            'reimbursement_policy_id' => $claimData['reimbursement_policy_id'],
                            'policy_category' => $claimData['policy_category'] ?? $policy->policy_category ?? null,
                            'sub_entry_data' => $subEntriesData,
                        ]);
                    }

                    // Create approval detail record
                    ReimbursementClaimApproval::create([
                        'master_request_id' => $masterClaimId,
                        'reimbursement_request_id' => $individualClaimId,
                        'reimbursement_claim_id' => $claim->id,
                        'institute_id' => $context['institute_id'],
                        'branch_id' => $context['branch_id'],
                        'employee_id' => $employee->employee_id,
                        'status' => 'pending',
                        'step1_approver_id' => $step1ApproverDesignationId,
                        'step1_approver_name' => $step1ApproverDesignationName,
                        'step2_approver_id' => $step2ApproverDesignationId,
                        'step2_approver_name' => $step2ApproverDesignationName,
                        'created_by' => Auth::user()->employee_id ?? null,
                    ]);

                    $createdClaims[] = $claim;

                } catch (\Exception $e) {
                    \Log::error('Error processing claim at index ' . $index . ': ' . $e->getMessage());
                    $errors[] = "Claim #" . ($index + 1) . ": " . $e->getMessage();
                }
            }

            if (empty($createdClaims)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create claims: ' . implode(' | ', $errors)
                ], 422);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($createdClaims) . ' claim(s) submitted successfully under batch ' . $masterClaimId,
                'data' => [
                    'master_request_id' => $masterClaimId,
                    'claims' => $createdClaims,
                    'total_claims' => count($createdClaims),
                    'errors' => $errors,
                    'warnings' => $warnings   // collect these when saving
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in storeBulk: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit claims: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getApprovalClaims(Request $request)
    {
        try {
            $context = $this->getInstituteContext();

            // Get designation_id from request (passed from frontend)
            $userDesignationId = $request->designation_id;

            // Fallback
            if (!$userDesignationId) {
                $employee = EmployeeDetails::where('user_id', Auth::user()->id)
                    ->where('institute_id', $context['institute_id'])
                    ->first();
                $userDesignationId = $employee->designation_id ?? null;
            }

            if (!$userDesignationId) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No designation found'
                ]);
            }

            // Get ALL approval records where this user is EITHER step1 OR step2 approver
            // This ensures user sees claims for ALL policies they are assigned to
            $approvalDetails = ReimbursementClaimApproval::where('institute_id', $context['institute_id'])
                ->where(function ($q) use ($userDesignationId) {
                    $q->where('step1_approver_id', $userDesignationId)
                        ->orWhere('step2_approver_id', $userDesignationId);
                });

            if ($request->status && $request->status !== 'all') {
                $approvalDetails->where('status', $request->status);
            }

            $approvalDetails = $approvalDetails->orderBy('created_at', 'desc')->get();

            // Get claims
            $claimIds = $approvalDetails->pluck('reimbursement_claim_id')->unique()->toArray();
            $claims = ReimbursementClaim::whereIn('id', $claimIds)
                ->orderBy('created_at', 'desc')
                ->get()
                ->keyBy('id');

            $result = [];
            foreach ($approvalDetails as $approval) {
                $claim = $claims->get($approval->reimbursement_claim_id);
                if (!$claim)
                    continue;

                // Determine user's role for THIS specific claim
                $isStep1Approver = (!empty($approval->step1_approver_id) && (string) $approval->step1_approver_id === (string) $userDesignationId);
                $isStep2Approver = (!empty($approval->step2_approver_id) && (string) $approval->step2_approver_id === (string) $userDesignationId);

                $canApprove = false;
                $currentStep = 'view_only';
                $hasStep2 = !empty($approval->step2_approver_id);

                // LOGIC:
                // - If user is Step 1 AND claim is pending → CAN APPROVE
                // - If user is Step 2 AND claim is step1_approved → CAN APPROVE
                // - If user is Step 2 AND claim is pending → CAN SEE but NOT approve (waiting for Step 1)
                // - If claim has only Step 1 (no Step 2) AND user is Step 1 → CAN APPROVE directly

                if ($isStep1Approver && $approval->status === 'pending') {
                    $canApprove = true;
                    $currentStep = 'step1';
                } elseif ($isStep2Approver && $approval->status === 'step1_approved' && $approval->step1_status === 'approved') {
                    $canApprove = true;
                    $currentStep = 'step2';
                } elseif ($isStep2Approver && $approval->status === 'pending') {
                    // Step 2 can see but waiting for Step 1
                    $currentStep = 'step2_waiting';
                }

                $claimData = $claim->toArray();
                $claimData['can_approve'] = $canApprove;
                $claimData['current_approval_step'] = $currentStep;
                $claimData['is_step1_approver'] = $isStep1Approver;
                $claimData['is_step2_approver'] = $isStep2Approver;
                $claimData['has_step2'] = $hasStep2;
                $claimData['step1_approver_name'] = $approval->step1_approver_name;
                $claimData['step1_approver_id'] = $approval->step1_approver_id;
                $claimData['step1_status'] = $approval->step1_status;
                $claimData['step1_remarks'] = $approval->step1_remarks;
                $claimData['step2_approver_name'] = $approval->step2_approver_name;
                $claimData['step2_approver_id'] = $approval->step2_approver_id;
                $claimData['step2_status'] = $approval->step2_status;
                $claimData['step2_remarks'] = $approval->step2_remarks;
                $claimData['approved_amount'] = $approval->total_approved_amount;
                $claimData['decline_reason'] = $approval->decline_reason;

                $result[] = $claimData;
            }

            return response()->json([
                'success' => true,
                'data' => $result,
                'approver_id' => $userDesignationId
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getApprovalClaims: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load approval claims: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $context = $this->getInstituteContext();

            $query = ReimbursementClaim::where('institute_id', $context['institute_id'])
                ->where('branch_id', $context['branch_id']);

            if ($request->employee_id) {
                $query->where('employee_id', $request->employee_id);
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->master_request_id) {
                $query->where('master_request_id', $request->master_request_id);
            }

            $claims = $query->orderBy('created_at', 'desc')->get();

            // Get all claim IDs
            $claimIds = $claims->pluck('id')->toArray();

            // Enrich with approval details
            $approvalDetails = ReimbursementClaimApproval::whereIn('reimbursement_claim_id', $claimIds)
                ->get()
                ->keyBy('reimbursement_claim_id');

            // ✅ ADD THIS: Get sub-entries for all claims
            $subEntries = ReimbursementClaimSubEntry::whereIn('reimbursement_claim_id', $claimIds)
                ->get()
                ->keyBy('reimbursement_request_id');

            foreach ($claims as $claim) {
                // Attach approval details
                $approval = $approvalDetails->get($claim->id);
                if ($approval) {
                    $claim->step1_approver_name = $approval->step1_approver_name;
                    $claim->step1_status = $approval->step1_status;
                    $claim->step1_remarks = $approval->step1_remarks;
                    $claim->step2_approver_name = $approval->step2_approver_name;
                    $claim->step2_status = $approval->step2_status;
                    $claim->step2_remarks = $approval->step2_remarks;
                    $claim->approved_amount = $approval->total_approved_amount;
                    $claim->decline_reason = $approval->decline_reason;
                }

                // ✅ ADD THIS: Attach sub-entries data to each claim
                $claim->sub_entries_data = null;
                if (isset($subEntries[$claim->reimbursement_request_id])) {
                    $subEntry = $subEntries[$claim->reimbursement_request_id];
                    $subEntryData = $subEntry->sub_entry_data;
                    if (is_string($subEntryData)) {
                        $subEntryData = json_decode($subEntryData, true);
                    }
                    $claim->sub_entries_data = $subEntryData;
                }

                // ✅ ADD THIS: Also get the policy to include policy-specific fields
                $policy = $this->findPolicyById($claim->reimbursement_policy_id, $context);
                if ($policy) {
                    $this->enrichClaimWithPolicyFields($claim, $policy);
                }
            }

            return response()->json([
                'success' => true,
                'data' => $claims
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load claims: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reviewBatch($masterRequestId)
    {
        try {
            $context = $this->getInstituteContext();

            $claims = ReimbursementClaim::where('master_request_id', $masterRequestId)
                ->where('institute_id', $context['institute_id'])
                ->where('branch_id', $context['branch_id'])
                ->orderBy('entry_order', 'asc')
                ->get();

            if ($claims->isEmpty()) {
                return redirect()->back()->with('error', 'No claims found for this batch');
            }

            $claimIds = $claims->pluck('id')->toArray();

            $subEntries = ReimbursementClaimSubEntry::whereIn('reimbursement_claim_id', $claimIds)
                ->get()
                ->keyBy('reimbursement_request_id');

            $approvalDetails = ReimbursementClaimApproval::whereIn('reimbursement_claim_id', $claimIds)
                ->get()
                ->keyBy('reimbursement_claim_id');

            // Get current user's designation ID
            $currentUserDesignationId = null;
            $employee = EmployeeDetails::where('user_id', Auth::user()->id)
                ->where('institute_id', $context['institute_id'])
                ->first();
            $currentUserDesignationId = $employee->designation_id ?? null;

            // if (!$currentUserDesignationId) {
            //     // $currentUserDesignationId = 'DESG-QR1H2FE8'; // proffessor
            //     // $currentUserDesignationId = 'DESG-CDK3CTK2'; // HOD 
            // }

            // Filter claims - only show claims where user is an approver for at least one sub-entry
            $visibleClaims = [];
            $grandTotalForApprover = 0;

            foreach ($claims as $claim) {
                // Sub-entries
                $claim->sub_entries_data = null;
                if (isset($subEntries[$claim->reimbursement_request_id])) {
                    $subEntry = $subEntries[$claim->reimbursement_request_id];
                    $subEntryData = $subEntry->sub_entry_data;
                    if (is_string($subEntryData)) {
                        $subEntryData = json_decode($subEntryData, true);
                    }
                    $claim->sub_entries_data = $subEntryData;
                }

                // Approval details
                $approval = $approvalDetails->get($claim->id);

                $isStep1Approver = false;
                $isStep2Approver = false;
                $canApproveAnyItem = false;
                $approverItemsTotal = 0;
                $approverItems = [];

                if ($approval) {
                    $isStep1Approver = ($currentUserDesignationId && !empty($approval->step1_approver_id) && (string) $approval->step1_approver_id === (string) $currentUserDesignationId);
                    $isStep2Approver = ($currentUserDesignationId && !empty($approval->step2_approver_id) && (string) $approval->step2_approver_id === (string) $currentUserDesignationId);

                    $hasStep2 = !empty($approval->step2_approver_id);
                    $claim->has_step2 = $hasStep2;

                    // Set approval info
                    $claim->step1_approver_name = $this->resolveDesignationName($approval->step1_approver_id, $approval->step1_approver_name);
                    $claim->step1_approver_id = $approval->step1_approver_id;
                    $claim->step1_status = $approval->step1_status;
                    $claim->step1_remarks = $approval->step1_remarks;
                    $claim->step1_approved_at = $approval->step1_approved_at;

                    $claim->step2_approver_name = $this->resolveDesignationName($approval->step2_approver_id, $approval->step2_approver_name);
                    $claim->step2_approver_id = $approval->step2_approver_id;
                    $claim->step2_status = $approval->step2_status;
                    $claim->step2_remarks = $approval->step2_remarks;
                    $claim->step2_approved_at = $approval->step2_approved_at;

                    $claim->approved_amount = $approval->total_approved_amount;
                    $claim->approved_amounts = $approval->approved_amounts;
                    $claim->sub_entry_approvals = $approval->sub_entry_approvals ?? [];
                    $claim->decline_reason = $approval->decline_reason;
                    $claim->settlement_mode = $approval->settlement_mode;
                    $claim->payout_amount = $approval->payout_amount;

                    // Determine which sub-entries this approver can act on
                    if ($claim->sub_entries_data && is_array($claim->sub_entries_data)) {
                        foreach ($claim->sub_entries_data as $si => $subEntry) {
                            $subAmount = floatval($subEntry['amount'] ?? 0);
                            $subMaxLimit = floatval($subEntry['max_limit'] ?? 0);

                            // Check if this sub-entry needs this approver's action
                            $needsStep1Action = $isStep1Approver && $claim->status === 'pending' &&
                                $approval->step1_status !== 'approved' && $approval->step1_status !== 'rejected';

                            $needsStep2Action = $isStep2Approver && $claim->status === 'step1_approved' &&
                                $approval->step2_status !== 'approved' && $approval->step2_status !== 'rejected';

                            if ($needsStep1Action || $needsStep2Action) {
                                $approverItems[] = [
                                    'sub_index' => $si,
                                    'range_name' => $subEntry['range_name'] ?? 'N/A',
                                    'amount' => $subAmount,
                                    'max_limit' => $subMaxLimit,
                                    'needs_step1' => $needsStep1Action,
                                    'needs_step2' => $needsStep2Action,
                                ];
                                $approverItemsTotal += $subAmount;
                                $canApproveAnyItem = true;
                            }
                        }
                    }
                }

                // Only show claim if user can approve at least one item
                if ($canApproveAnyItem || $isStep1Approver || $isStep2Approver) {
                    $claim->can_approve = $canApproveAnyItem;
                    $claim->current_approval_step = $isStep1Approver ? 'step1' : ($isStep2Approver ? ($claim->status === 'step1_approved' ? 'step2' : 'step2_waiting') : 'view_only');
                    $claim->is_step1_approver = $isStep1Approver;
                    $claim->is_step2_approver = $isStep2Approver;
                    $claim->approver_items = $approverItems;
                    $claim->approver_items_total = $approverItemsTotal;

                    $grandTotalForApprover += $approverItemsTotal;

                    // Policy ranges
                    $policy = $this->findPolicyById($claim->reimbursement_policy_id, $context);
                    if ($policy) {
                        $assignment = ReimbursementPolicyAssignment::where('reimbursement_policy_id', $claim->reimbursement_policy_id)
                            ->where('institute_id', $context['institute_id'])
                            ->where('branch_id', $context['branch_id'])
                            ->where('status', 'active')
                            ->first();

                        $assignedRanges = null;
                        if ($assignment && $assignment->allowed_ranges) {
                            $assignedRanges = is_string($assignment->allowed_ranges)
                                ? json_decode($assignment->allowed_ranges, true)
                                : $assignment->allowed_ranges;
                        }

                        $claim->policy_ranges = $this->buildFullAllowedRangesForPolicy($policy, $assignedRanges);
                        $claim->allow_actual_amount = $policy->allow_actual_amount ?? 'No';
                        $claim->calculation_type = $policy->calculation_type ?? 'per_claim';
                        $claim->max_amount = $policy->max_amount ?? 0;
                        $claim->settlement_mode = $policy->settlement_mode ?? 'bank_transfer';
                        $claim->submission_within = $policy->submission_within ?? 7;
                    }

                    $visibleClaims[] = $claim;
                }
            }

            $batchSummary = [
                'master_request_id' => $masterRequestId,
                'total_claims' => count($visibleClaims),
                'total_amount' => $grandTotalForApprover, // Show only approver's total
                'employee_name' => $claims->first()->name ?? 'N/A',
                'employee_id' => $claims->first()->employee_id ?? 'N/A',
                'designation' => $claims->first()->designation ?? 'N/A',
                'department' => $claims->first()->department ?? 'N/A',
                'submission_date' => $claims->first()->submission_date ?? 'N/A',
                'approver_designation' => $this->resolveDesignationName($currentUserDesignationId, ''),
            ];

            return view('instituteAdmin.Reimbursement.ReviewBulkClaims', [
                'claims' => $visibleClaims,
                'batchSummary' => $batchSummary,
                'isSettlementView' => false
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in reviewBatch: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load batch claims: ' . $e->getMessage());
        }
    }

    private function resolveDesignationName($designationId, $fallbackName)
    {
        if (empty($designationId))
            return $fallbackName ?: 'N/A';

        $designation = Designations::where('designation_id', $designationId)->first();
        if ($designation) {
            return $designation->designations ?? $fallbackName ?? $designationId;
        }

        return $fallbackName ?: $designationId;
    }

    private function determineApprovalStatus($claim, $approval = null)
    {
        // Get current user's designation
        $employee = EmployeeDetails::where('user_id', Auth::user()->id)
            ->where('institute_id', $claim->institute_id)
            ->first();

        $userDesignationId = $employee->designation_id ?? null;

        if (!$userDesignationId) {
            $claim->can_approve = false;
            $claim->current_approval_step = 'view_only';
            $claim->is_step1_approver = false;
            $claim->is_step2_approver = false;
            return $claim;
        }

        $isStep1Approver = false;
        $isStep2Approver = false;

        if ($approval) {
            if (!empty($approval->step1_approver_id) && (string) $approval->step1_approver_id === (string) $userDesignationId) {
                $isStep1Approver = true;
            }
            if (!empty($approval->step2_approver_id) && (string) $approval->step2_approver_id === (string) $userDesignationId) {
                $isStep2Approver = true;
            }
        }

        $canApprove = false;
        $currentStep = 'view_only';

        if ($isStep1Approver && $claim->status === 'pending') {
            $canApprove = true;
            $currentStep = 'step1';
        } elseif ($isStep2Approver && $claim->status === 'step1_approved') {
            $canApprove = true;
            $currentStep = 'step2';
        }

        $claim->can_approve = $canApprove;
        $claim->current_approval_step = $currentStep;
        $claim->is_step1_approver = $isStep1Approver;
        $claim->is_step2_approver = $isStep2Approver;

        return $claim;
    }


    public function bulkAction(Request $request)
    {
        try {
            DB::beginTransaction();
            $context = $this->getInstituteContext();
            $action = $request->action;
            $claimIds = $request->claim_ids ?? [];
            $approvedAmounts = $request->approved_amounts ?? [];
            $remarksInput = $request->remarks ?? '';

            if (empty($claimIds)) {
                return response()->json(['success' => false, 'message' => 'No claims selected'], 422);
            }

            // --- Obtain the current user's designation ID EXACTLY like reviewBatch does ---
            $approverId = null;
            $employee = EmployeeDetails::where('user_id', Auth::user()->id)
                ->where('institute_id', $context['institute_id'])
                ->first();
            $approverId = $employee->designation_id ?? null;

            // ❌ No hardcoded fallback! If null, we will report it clearly.
            // ------------------------------------------------------------------------

            $claims = ReimbursementClaim::whereIn('id', $claimIds)
                ->where('institute_id', $context['institute_id'])
                ->get();

            $processedClaims = [];
            $skippedClaims = [];
            $processedCount = 0;

            foreach ($claims as $claim) {
                $approvalDetail = ReimbursementClaimApproval::where('reimbursement_claim_id', $claim->id)->first();

                if (!$approvalDetail) {
                    $skippedClaims[] = [
                        'claim_id' => $claim->id,
                        'reason' => 'No approval record found.'
                    ];
                    continue;
                }

                // If no designation, we cannot authorise – stop here with a clear message
                if (empty($approverId)) {
                    $skippedClaims[] = [
                        'claim_id' => $claim->id,
                        'reason' => 'Your user account is not linked to any employee designation. Please set up your employee details.'
                    ];
                    continue;
                }

                // Strict check: only match if BOTH sides are non‑empty
                $isStep1 = !empty($approvalDetail->step1_approver_id) && (string) $approvalDetail->step1_approver_id === (string) $approverId;
                $isStep2 = !empty($approvalDetail->step2_approver_id) && (string) $approvalDetail->step2_approver_id === (string) $approverId;

                if (!$isStep1 && !$isStep2) {
                    $skippedClaims[] = [
                        'claim_id' => $claim->id,
                        'reason' => 'You are not designated as an approver for this claim. Your designation ID: ' . $approverId
                    ];
                    continue;
                }

                // Verify correct approval step and status
                if ($isStep1 && $claim->status === 'pending' && $approvalDetail->step1_status !== 'approved' && $approvalDetail->step1_status !== 'rejected') {
                    // allowed
                } elseif ($isStep2 && $claim->status === 'step1_approved' && $approvalDetail->step2_status !== 'approved' && $approvalDetail->step2_status !== 'rejected') {
                    // allowed
                } else {
                    $reason = '';
                    if ($isStep1) {
                        $reason = "Step 1 requires claim status 'pending' and step1 not yet acted. Current: status={$claim->status}, step1_status={$approvalDetail->step1_status}";
                    } else {
                        $reason = "Step 2 requires claim status 'step1_approved' and step2 not yet acted. Current: status={$claim->status}, step2_status={$approvalDetail->step2_status}";
                    }
                    $skippedClaims[] = [
                        'claim_id' => $claim->id,
                        'reason' => $reason
                    ];
                    continue;
                }

                // Remarks (handle both string and array)
                $claimRemark = '';
                if (is_array($remarksInput)) {
                    $claimRemark = $remarksInput[$claim->id] ?? ($action === 'approve' ? 'Approved' : 'Declined');
                } else {
                    $claimRemark = $remarksInput ?: ($action === 'approve' ? 'Approved' : 'Declined');
                }

                // Approved amounts
                $totalApproved = 0;
                $approvedAmountsArray = [];
                $claimApprovedAmounts = is_array($approvedAmounts) ? ($approvedAmounts[$claim->id] ?? null) : null;
                if ($claimApprovedAmounts && is_array($claimApprovedAmounts)) {
                    foreach ($claimApprovedAmounts as $subIndex => $amount) {
                        $approvedAmount = floatval($amount);
                        $totalApproved += $approvedAmount;
                        $approvedAmountsArray[] = [
                            'sub_index' => intval($subIndex),
                            'approved' => $approvedAmount,
                        ];
                    }
                }

                try {
                    if ($action === 'approve') {
                        $this->processApproval($claim, $approvalDetail, $approvedAmountsArray, $totalApproved, $claimRemark, $approverId, '', $isStep1, $isStep2);
                    } elseif ($action === 'reject') {
                        $this->processRejection($claim, $approvalDetail, $claimRemark, $approverId, '', $isStep1, $isStep2);
                    } else {
                        $skippedClaims[] = ['claim_id' => $claim->id, 'reason' => 'Invalid action'];
                        continue;
                    }
                    $processedClaims[] = ['claim_id' => $claim->id, 'status' => $action];
                    $processedCount++;
                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log::error('Error processing claim ' . $claim->id . ': ' . $e->getMessage());
                    return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "$processedCount claim(s) " . ($action === 'approve' ? 'approved' : 'declined'),
                'data' => [
                    'processed' => $processedClaims,
                    'skipped' => $skippedClaims,
                    'approver_id_used' => $approverId   // will be null if not found, but skipped messages explain why
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in bulkAction: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function processApproval($claim, $approvalDetail, $approvedAmountsArray, $totalApproved, $remarks, $approverId, $approverName, $isStep1, $isStep2)
    {
        $now = Carbon::now();
        $hasStep2 = !empty($approvalDetail->step2_approver_id);

        if ($isStep1 && $claim->status === 'pending') {
            // Step 1 approval
            $newStatus = $hasStep2 ? 'step1_approved' : 'approved';

            $approvalDetail->update([
                'step1_status' => 'approved',
                'step1_remarks' => $remarks,
                'step1_approved_at' => $now,
                'approved_amounts' => $approvedAmountsArray,
                'total_approved_amount' => $totalApproved,
                'status' => $newStatus,
                'updated_by' => Auth::id() ?? null,
            ]);

            $claim->update([
                'approved_amount' => $totalApproved,
                'status' => $newStatus,
            ]);

            \Log::info('Step 1 approved claim ' . $claim->id, [
                'new_status' => $newStatus,
                'total_approved' => $totalApproved
            ]);

        } elseif ($isStep2 && $claim->status === 'step1_approved') {
            // Step 2 approval - final approval
            // Keep the existing approved amounts from step 1, but allow step 2 to reduce
            $step1ApprovedAmounts = $approvalDetail->approved_amounts ?? [];

            // Merge: use step 2 values where provided, otherwise keep step 1 values
            $finalAmounts = $step1ApprovedAmounts;
            $finalTotal = floatval($approvalDetail->total_approved_amount ?? 0);

            if (!empty($approvedAmountsArray)) {
                $finalTotal = 0;
                $finalAmounts = $approvedAmountsArray;
                foreach ($approvedAmountsArray as $item) {
                    $finalTotal += floatval($item['approved'] ?? 0);
                }
            }

            $approvalDetail->update([
                'step2_status' => 'approved',
                'step2_remarks' => $remarks,
                'step2_approved_at' => $now,
                'approved_amounts' => $finalAmounts,
                'total_approved_amount' => $finalTotal,
                'status' => 'approved',
                'updated_by' => Auth::id() ?? null,
            ]);

            $claim->update([
                'approved_amount' => $finalTotal,
                'status' => 'approved',
            ]);

            \Log::info('Step 2 approved claim ' . $claim->id, [
                'final_total' => $finalTotal
            ]);
        }
    }

    private function processRejection($claim, $approvalDetail, $remarks, $approverId, $approverName, $isStep1, $isStep2)
    {
        $now = Carbon::now();

        if ($isStep1 && $claim->status === 'pending') {
            $approvalDetail->update([
                'step1_status' => 'rejected',
                'step1_remarks' => $remarks,
                'step1_approved_at' => $now,
                'decline_reason' => $remarks,
                'status' => 'rejected',
                'updated_by' => Auth::user()->id ?? null,
            ]);
            $claim->update(['status' => 'rejected']);

        } elseif ($isStep2 && $claim->status === 'step1_approved') {
            $approvalDetail->update([
                'step2_status' => 'rejected',
                'step2_remarks' => $remarks,
                'step2_approved_at' => $now,
                'decline_reason' => $remarks,
                'status' => 'rejected',
                'updated_by' => Auth::user()->id ?? null,
            ]);
            $claim->update(['status' => 'rejected']);
        }
    }

    private function getExistingClaimCount($employeeId, $policyId, $frequencyType, $context, $expenseDate = null)
    {
        $query = ReimbursementClaim::where('employee_id', $employeeId)
            ->where('reimbursement_policy_id', $policyId)
            ->where('institute_id', $context['institute_id'])
            ->where('branch_id', $context['branch_id'])
            ->where('status', '!=', 'rejected'); // Don't count rejected claims

        $date = $expenseDate ? Carbon::parse($expenseDate) : Carbon::now();

        switch ($frequencyType) {
            case 'day':
                $query->whereDate('expense_date', $date->toDateString());
                break;
            case 'week':
                $query->whereBetween('expense_date', [$date->copy()->startOfWeek()->toDateString(), $date->copy()->endOfWeek()->toDateString()]);
                break;
            case 'month':
                $query->whereMonth('expense_date', $date->month)
                    ->whereYear('expense_date', $date->year);
                break;
            case 'quarter':
                $start = $date->copy()->startOfQuarter();
                $end = $date->copy()->endOfQuarter();
                $query->whereBetween('expense_date', [$start->toDateString(), $end->toDateString()]);
                break;
            case 'year':
                $query->whereYear('expense_date', $date->year);
                break;
        }

        // Count distinct master_request_ids (each batch submission = 1 claim)
        return $query->distinct('master_request_id')->count('master_request_id');
    }


    private function getFrequencyLabel($frequencyType)
    {
        $labels = [
            'day' => 'today',
            'week' => 'this week',
            'month' => 'this month',
            'quarter' => 'this quarter',
            'year' => 'this year',
        ];
        return $labels[$frequencyType] ?? 'in this period';
    }

    private function getExistingClaimCountForDisplay($employeeId, $policyId, $frequencyType, $context, $expenseDate = null)
    {
        if ($frequencyType === 'unlimited') {
            return 0;
        }

        $query = ReimbursementClaim::where('employee_id', $employeeId)
            ->where('reimbursement_policy_id', $policyId)
            ->where('institute_id', $context['institute_id'])
            ->where('branch_id', $context['branch_id'])
            ->where('status', '!=', 'rejected');

        $date = $expenseDate ? Carbon::parse($expenseDate) : Carbon::now();

        switch ($frequencyType) {
            case 'day':
                $query->whereDate('expense_date', $date->toDateString());
                break;
            case 'week':
                $query->whereBetween('expense_date', [$date->copy()->startOfWeek()->toDateString(), $date->copy()->endOfWeek()->toDateString()]);
                break;
            case 'month':
                $query->whereMonth('expense_date', $date->month)
                    ->whereYear('expense_date', $date->year);
                break;
            case 'quarter':
                $start = $date->copy()->startOfQuarter();
                $end = $date->copy()->endOfQuarter();
                $query->whereBetween('expense_date', [$start->toDateString(), $end->toDateString()]);
                break;
            case 'year':
                $query->whereYear('expense_date', $date->year);
                break;
        }

        return $query->distinct('master_request_id')->count('master_request_id');
    }


    private function resolveEffectivePolicyMaxAmount($policy, $allowedRanges = null)
    {
        $maxAmount = floatval($policy->max_amount ?? 0);
        if ($maxAmount > 0) {
            return $maxAmount;
        }

        $category = $policy->policy_category ?? '';
        if ($category === 'food') {
            if (!empty($policy->three_meals_max) && floatval($policy->three_meals_max) > 0) {
                return floatval($policy->three_meals_max);
            }
            if (!empty($policy->two_meals_max) && floatval($policy->two_meals_max) > 0) {
                return floatval($policy->two_meals_max);
            }
            if (!empty($policy->lunch_max) && floatval($policy->lunch_max) > 0) {
                return floatval($policy->lunch_max);
            }
        }

        if (!empty($allowedRanges) && is_array($allowedRanges)) {
            $maxFromRanges = 0;
            foreach ($allowedRanges as $r) {
                if (is_array($r) && isset($r['max']) && floatval($r['max']) > 0) {
                    $maxFromRanges = max($maxFromRanges, floatval($r['max']));
                }
            }
            if ($maxFromRanges > 0) {
                return $maxFromRanges;
            }
        }

        return 0;
    }


    private function validateCalculationTypeLimits($policy, $claimData, $employee, $context)
    {
        $calcType = $policy->calculation_type ?? 'per_claim';
        $category = $policy->policy_category ?? '';
        $maxAmount = $this->resolveEffectivePolicyMaxAmount($policy);
        $claimAmount = floatval($claimData['claim_amount'] ?? 0);
        $expenseDate = $claimData['expense_date'] ?? null;

        // Parse sub-entries if present
        $subEntries = [];
        if (isset($claimData['sub_entries'])) {
            $subEntries = is_string($claimData['sub_entries'])
                ? (json_decode($claimData['sub_entries'], true) ?? [])
                : (is_array($claimData['sub_entries']) ? $claimData['sub_entries'] : []);
        }

        // Build map of range max limits from policy or allowed_ranges
        $allowedRanges = $this->buildFullAllowedRangesForPolicy($policy);
        $rangeMaxMap = [];
        foreach ($allowedRanges as $r) {
            if (is_array($r) && !empty($r['name'])) {
                $rangeMaxMap[$r['name']] = floatval($r['max'] ?? 0);
            }
        }

        // 1. Check Sub-Category Range Limits for (Same Date + Same Range)
        if (!empty($subEntries)) {
            $dateRangeGroups = [];
            foreach ($subEntries as $sub) {
                $subDate = $sub['meal_date'] ?? $sub['travel_date'] ?? $sub['checkin_date'] ?? $sub['expense_date'] ?? $sub['date'] ?? $expenseDate;
                $rangeName = $sub['range_name'] ?? 'Standard';
                $subAmount = floatval($sub['amount'] ?? 0);
                $rMax = floatval($sub['max_limit'] ?? $rangeMaxMap[$rangeName] ?? 0);

                if ($subDate && $subAmount > 0) {
                    $key = "{$subDate}|{$rangeName}";
                    if (!isset($dateRangeGroups[$key])) {
                        $dateRangeGroups[$key] = [
                            'date' => $subDate,
                            'range' => $rangeName,
                            'max' => $rMax,
                            'total' => 0
                        ];
                    }
                    $dateRangeGroups[$key]['total'] += $subAmount;
                }
            }

            foreach ($dateRangeGroups as $key => $grp) {
                $sDate = $grp['date'];
                $rName = $grp['range'];
                $rMax = $grp['max'];
                $currentAmt = $grp['total'];

                if ($rMax > 0) {
                    // Fetch existing DB sub-entries amount for this date + range
                    $existingDBSubAmount = 0;
                    $dbSubEntries = ReimbursementClaimSubEntry::where('employee_id', $employee->employee_id)
                        ->where('reimbursement_policy_id', $policy->reimbursement_policy_id)
                        ->where('institute_id', $context['institute_id'])
                        ->whereHas('claim', function ($q) {
                            $q->where('status', '!=', 'rejected');
                        })->get();

                    foreach ($dbSubEntries as $dbSe) {
                        $dData = is_string($dbSe->sub_entry_data) ? json_decode($dbSe->sub_entry_data, true) : $dbSe->sub_entry_data;
                        if (is_array($dData)) {
                            foreach ($dData as $item) {
                                if (!is_array($item))
                                    continue;
                                $itemRange = $item['range_name'] ?? 'Standard';
                                $itemDate = $item['meal_date'] ?? $item['travel_date'] ?? $item['checkin_date'] ?? $item['expense_date'] ?? $item['date'] ?? null;
                                if (!$itemDate && $dbSe->claim) {
                                    $itemDate = $dbSe->claim->expense_date;
                                }
                                if ($itemDate === $sDate && $itemRange === $rName) {
                                    $existingDBSubAmount += floatval($item['amount'] ?? 0);
                                }
                            }
                        }
                    }

                    $combinedRangeAmt = $existingDBSubAmount + $currentAmt;
                    if ($combinedRangeAmt > $rMax) {
                        return [
                            'valid' => false,
                            'message' => "Limit exceeded for '{$rName}' on {$sDate}! Total claimed ₹" . number_format($combinedRangeAmt, 2) . " (including ₹" . number_format($existingDBSubAmount, 2) . " existing) exceeds max limit of ₹" . number_format($rMax, 2) . " by ₹" . number_format($combinedRangeAmt - $rMax, 2)
                        ];
                    }
                }
            }
        }

        // 2. Food Policy Meal Combination Limit Checks
        if ($category === 'food' && !empty($subEntries)) {
            $dailyMealGroups = [];
            foreach ($subEntries as $sub) {
                $subDate = $sub['meal_date'] ?? $sub['date'] ?? $sub['checkin_date'] ?? $expenseDate;
                $subAmount = floatval($sub['amount'] ?? 0);
                if ($subDate && $subAmount > 0) {
                    if (!isset($dailyMealGroups[$subDate])) {
                        $dailyMealGroups[$subDate] = ['count' => 0, 'total' => 0];
                    }
                    $dailyMealGroups[$subDate]['count'] += 1;
                    $dailyMealGroups[$subDate]['total'] += $subAmount;
                }
            }

            foreach ($dailyMealGroups as $dateKey => $group) {
                $count = $group['count'];
                $total = $group['total'];
                if ($count == 2 && !empty($policy->two_meals_max) && floatval($policy->two_meals_max) > 0) {
                    $twoMax = floatval($policy->two_meals_max);
                    if ($total > $twoMax) {
                        return [
                            'valid' => false,
                            'message' => "Daily total ₹" . number_format($total, 2) . " for 2 meals on {$dateKey} exceeds two-meals limit of ₹" . number_format($twoMax, 2)
                        ];
                    }
                } elseif ($count >= 3 && !empty($policy->three_meals_max) && floatval($policy->three_meals_max) > 0) {
                    $threeMax = floatval($policy->three_meals_max);
                    if ($total > $threeMax) {
                        return [
                            'valid' => false,
                            'message' => "Daily total ₹" . number_format($total, 2) . " for 3 meals on {$dateKey} exceeds full-day three-meals limit of ₹" . number_format($threeMax, 2)
                        ];
                    }
                }
            }
        }

        // 3. Calculation Type Checks
        switch ($calcType) {
            case 'per_claim':
                if ($maxAmount > 0 && $claimAmount > $maxAmount) {
                    return [
                        'valid' => false,
                        'message' => "Claim amount ₹{$claimAmount} exceeds per-claim limit of ₹{$maxAmount}"
                    ];
                }
                break;

            case 'per_day':
                $datesToCheck = [];
                if (!empty($subEntries)) {
                    foreach ($subEntries as $sub) {
                        $d = $sub['date'] ?? $sub['meal_date'] ?? $sub['checkin_date'] ?? $sub['travel_date'] ?? $expenseDate;
                        $amt = floatval($sub['amount'] ?? 0);
                        if ($d && $amt > 0) {
                            if (!isset($datesToCheck[$d])) {
                                $datesToCheck[$d] = 0;
                            }
                            $datesToCheck[$d] += $amt;
                        }
                    }
                }
                if (empty($datesToCheck) && $expenseDate) {
                    $datesToCheck[$expenseDate] = $claimAmount;
                }

                foreach ($datesToCheck as $dateKey => $currentDailyAmount) {
                    $dailyTotalDB = ReimbursementClaim::where('employee_id', $employee->employee_id)
                        ->where('reimbursement_policy_id', $policy->reimbursement_policy_id)
                        ->where('institute_id', $context['institute_id'])
                        ->where('expense_date', $dateKey)
                        ->where('status', '!=', 'rejected')
                        ->sum('claim_amount');

                    $combinedDaily = floatval($dailyTotalDB) + $currentDailyAmount;

                    if ($maxAmount > 0 && $combinedDaily > $maxAmount) {
                        return [
                            'valid' => false,
                            'message' => "Total claims for {$dateKey} would be ₹" . number_format($combinedDaily, 2) . " which exceeds daily limit of ₹" . number_format($maxAmount, 2)
                        ];
                    }
                }
                break;

            case 'per_month':
                if ($expenseDate && $maxAmount > 0) {
                    $month = date('m', strtotime($expenseDate));
                    $year = date('Y', strtotime($expenseDate));

                    $monthlyTotal = ReimbursementClaim::where('employee_id', $employee->employee_id)
                        ->where('reimbursement_policy_id', $policy->reimbursement_policy_id)
                        ->where('institute_id', $context['institute_id'])
                        ->whereMonth('expense_date', $month)
                        ->whereYear('expense_date', $year)
                        ->where('status', '!=', 'rejected')
                        ->sum('claim_amount');

                    $monthlyTotal = floatval($monthlyTotal) + $claimAmount;

                    if ($monthlyTotal > $maxAmount) {
                        return [
                            'valid' => false,
                            'message' => "Total claims for " . date('F Y', strtotime($expenseDate)) . " would be ₹" . number_format($monthlyTotal, 2) . " which exceeds monthly limit of ₹" . number_format($maxAmount, 2)
                        ];
                    }
                }
                break;

            case 'per_quarter':
                if ($expenseDate && $maxAmount > 0) {
                    $date = Carbon::parse($expenseDate);
                    $quarterStart = $date->copy()->startOfQuarter();
                    $quarterEnd = $date->copy()->endOfQuarter();

                    $quarterlyTotal = ReimbursementClaim::where('employee_id', $employee->employee_id)
                        ->where('reimbursement_policy_id', $policy->reimbursement_policy_id)
                        ->where('institute_id', $context['institute_id'])
                        ->whereBetween('expense_date', [$quarterStart->toDateString(), $quarterEnd->toDateString()])
                        ->where('status', '!=', 'rejected')
                        ->sum('claim_amount');

                    $quarterlyTotal = floatval($quarterlyTotal) + $claimAmount;

                    if ($quarterlyTotal > $maxAmount) {
                        return [
                            'valid' => false,
                            'message' => "Total claims for this quarter would be ₹" . number_format($quarterlyTotal, 2) . " which exceeds quarterly limit of ₹" . number_format($maxAmount, 2)
                        ];
                    }
                }
                break;

            case 'per_year':
                if ($expenseDate && $maxAmount > 0) {
                    $year = date('Y', strtotime($expenseDate));

                    $yearlyTotal = ReimbursementClaim::where('employee_id', $employee->employee_id)
                        ->where('reimbursement_policy_id', $policy->reimbursement_policy_id)
                        ->where('institute_id', $context['institute_id'])
                        ->whereYear('expense_date', $year)
                        ->where('status', '!=', 'rejected')
                        ->sum('claim_amount');

                    $yearlyTotal = floatval($yearlyTotal) + $claimAmount;

                    if ($yearlyTotal > $maxAmount) {
                        return [
                            'valid' => false,
                            'message' => "Total claims for {$year} would be ₹" . number_format($yearlyTotal, 2) . " which exceeds yearly limit of ₹" . number_format($maxAmount, 2)
                        ];
                    }
                }
                break;

            case 'lumpsum':
                $existingCount = ReimbursementClaim::where('employee_id', $employee->employee_id)
                    ->where('reimbursement_policy_id', $policy->reimbursement_policy_id)
                    ->where('institute_id', $context['institute_id'])
                    ->where('status', '!=', 'rejected')
                    ->count();

                if ($existingCount > 0) {
                    return [
                        'valid' => false,
                        'message' => "This is a one-time (lumpsum) policy. You have already claimed under this policy."
                    ];
                }

                if ($maxAmount > 0 && $claimAmount > $maxAmount) {
                    return [
                        'valid' => false,
                        'message' => "Claim amount ₹" . number_format($claimAmount, 2) . " exceeds lumpsum limit of ₹" . number_format($maxAmount, 2)
                    ];
                }
                break;
        }

        return ['valid' => true];
    }


    private function enrichClaimWithPolicyFields($claim, $policy)
    {
        $category = $claim->policy_category;

        // Add travel-specific fields
        if ($category === 'travel') {
            $claim->two_wheeler_min = $policy->two_wheeler_min ?? null;
            $claim->two_wheeler_max = $policy->two_wheeler_max ?? null;
            $claim->two_wheeler_rate_km = $policy->two_wheeler_rate_km ?? null;
            $claim->car_min = $policy->car_min ?? null;
            $claim->car_max = $policy->car_max ?? null;
            $claim->car_rate_km = $policy->car_rate_km ?? null;
            $claim->auto_min = $policy->auto_min ?? null;
            $claim->auto_max = $policy->auto_max ?? null;
            $claim->auto_rate_km = $policy->auto_rate_km ?? null;
            $claim->bus_categories = $policy->bus_categories ?? null;
            $claim->train_categories = $policy->train_categories ?? null;
            $claim->flight_categories = $policy->flight_categories ?? null;
        }

        // Add accommodation-specific fields
        if ($category === 'accommodation') {
            $claim->basic_min = $policy->basic_min ?? null;
            $claim->basic_max = $policy->basic_max ?? null;
            $claim->deluxe_min = $policy->deluxe_min ?? null;
            $claim->deluxe_max = $policy->deluxe_max ?? null;
            $claim->premium_min = $policy->premium_min ?? null;
            $claim->premium_max = $policy->premium_max ?? null;
        }

        // Add food-specific fields
        if ($category === 'food') {
            $claim->breakfast_min = $policy->breakfast_min ?? null;
            $claim->breakfast_max = $policy->breakfast_max ?? null;
            $claim->lunch_min = $policy->lunch_min ?? null;
            $claim->lunch_max = $policy->lunch_max ?? null;
            $claim->dinner_min = $policy->dinner_min ?? null;
            $claim->dinner_max = $policy->dinner_max ?? null;
            $claim->two_meals_min = $policy->two_meals_min ?? null;
            $claim->two_meals_max = $policy->two_meals_max ?? null;
            $claim->three_meals_min = $policy->three_meals_min ?? null;
            $claim->three_meals_max = $policy->three_meals_max ?? null;
        }
    }


    private function findPolicyById($policyId, $context)
    {
        $models = [
            ReimbursementTravelPolicy::class,
            ReimbursementAccommodationPolicy::class,
            ReimbursementFoodPolicy::class,
            ReimbursementOtherPolicies::class,
        ];

        foreach ($models as $model) {
            $policy = $model::where('reimbursement_policy_id', $policyId)
                ->where('institute_id', $context['institute_id'])
                ->where('branch_id', $context['branch_id'])
                ->first();
            if ($policy)
                return $policy;
        }

        return null;
    }

    private function buildFullAllowedRangesForPolicy($policy, $assignmentRanges = null): array
    {
        $category = $policy->policy_category ?? 'other';
        $ranges = [];

        // If assignment has specific allowed_ranges, use those allowed_ranges with their exact values!
        if (!empty($assignmentRanges) && is_array($assignmentRanges)) {
            foreach ($assignmentRanges as $r) {
                if (!is_array($r))
                    continue;
                $rName = $r['name'] ?? $r['claim_name'] ?? 'Standard';

                // Look up model defaults to complement missing keys
                $modelData = $this->extractRangeFromPolicy($policy, $rName, $category);

                $minVal = floatval($r['min'] ?? $r['min_amount'] ?? $modelData['min'] ?? 0);
                $maxVal = floatval($r['max'] ?? $r['max_amount'] ?? $modelData['max'] ?? 0);
                $rateVal = floatval($r['rate'] ?? $r['rate_km'] ?? $modelData['rate'] ?? 0);
                $ruleType = $r['ruleType'] ?? $r['type'] ?? $modelData['ruleType'] ?? ($rateVal > 0 ? 'per_km' : 'actual');

                $ranges[] = [
                    'name' => $rName,
                    'min' => $minVal,
                    'max' => $maxVal,
                    'rate' => $rateVal > 0 ? $rateVal : null,
                    'ruleType' => $ruleType,
                    'unit' => $r['unit'] ?? $modelData['unit'] ?? ($ruleType === 'per_km' ? 'Kilometers (KM)' : null),
                    'bill' => $r['bill'] ?? $r['bill_required'] ?? $modelData['bill'] ?? 'Yes',
                    'photo' => $r['photo'] ?? $r['photo_required'] ?? $modelData['photo'] ?? 'No',
                    'includes_food' => $r['includes_food'] ?? false
                ];
            }
            if (!empty($ranges)) {
                return $ranges;
            }
        }

        // If no assignment allowed_ranges array, extract explicitly configured ranges from policy model
        if ($category === 'travel') {
            $vehicles = ['two_wheeler' => 'Two Wheeler', 'car' => 'Car', 'auto' => 'Auto'];
            foreach ($vehicles as $key => $displayName) {
                $minVal = floatval($policy->{$key . '_min'} ?? 0);
                $maxVal = floatval($policy->{$key . '_max'} ?? 0);
                $rateVal = floatval($policy->{$key . '_rate_km'} ?? 0);

                if ($minVal > 0 || $maxVal > 0 || $rateVal > 0) {
                    $ranges[] = [
                        'name' => $displayName,
                        'min' => $minVal,
                        'max' => $maxVal,
                        'rate' => $rateVal > 0 ? $rateVal : null,
                        'ruleType' => $rateVal > 0 ? 'per_km' : 'actual',
                        'unit' => 'Kilometers (KM)',
                        'bill' => $policy->{$key . '_bill_required'} ?? 'Yes',
                        'photo' => $policy->{$key . '_photo_required'} ?? 'No'
                    ];
                }
            }

            if ($policy->bus_categories) {
                $busCat = is_string($policy->bus_categories) ? json_decode($policy->bus_categories, true) : $policy->bus_categories;
                if (is_array($busCat)) {
                    $busNames = ['general' => 'General Bus', 'seater_ac' => 'Seater AC', 'seater_nonac' => 'Seater Non-AC', 'sleeper_ac' => 'Sleeper AC', 'sleeper_nonac' => 'Sleeper Non-AC'];
                    foreach ($busCat as $k => $bus) {
                        if (!is_array($bus))
                            continue;
                        $ranges[] = [
                            'name' => $busNames[$k] ?? ucfirst(str_replace('_', ' ', $k)),
                            'min' => floatval($bus['min'] ?? 0),
                            'max' => floatval($bus['max'] ?? 0),
                            'rate' => null,
                            'ruleType' => 'actual',
                            'bill' => $bus['bill_required'] ?? 'Yes',
                            'photo' => $bus['photo_required'] ?? 'No'
                        ];
                    }
                }
            }

            if ($policy->train_categories) {
                $trainCat = is_string($policy->train_categories) ? json_decode($policy->train_categories, true) : $policy->train_categories;
                if (is_array($trainCat)) {
                    $trainNames = ['general' => 'General', 'sleeper' => 'Sleeper Class', 'ac3' => '3AC', 'ac2' => '2AC', 'ac1' => '1AC'];
                    foreach ($trainCat as $k => $train) {
                        if (!is_array($train))
                            continue;
                        $ranges[] = [
                            'name' => $trainNames[$k] ?? ucfirst(str_replace('_', ' ', $k)),
                            'min' => floatval($train['min'] ?? 0),
                            'max' => floatval($train['max'] ?? 0),
                            'rate' => null,
                            'ruleType' => 'actual',
                            'bill' => $train['bill_required'] ?? 'Yes',
                            'photo' => $train['photo_required'] ?? 'No'
                        ];
                    }
                }
            }

            if ($policy->flight_categories) {
                $flightCat = is_string($policy->flight_categories) ? json_decode($policy->flight_categories, true) : $policy->flight_categories;
                if (is_array($flightCat)) {
                    $flightNames = ['economy' => 'Economy', 'business' => 'Business Class'];
                    foreach ($flightCat as $k => $flight) {
                        if (!is_array($flight))
                            continue;
                        $ranges[] = [
                            'name' => $flightNames[$k] ?? ucfirst(str_replace('_', ' ', $k)),
                            'min' => floatval($flight['min'] ?? 0),
                            'max' => floatval($flight['max'] ?? 0),
                            'rate' => null,
                            'ruleType' => 'actual',
                            'bill' => $flight['bill_required'] ?? 'Yes',
                            'photo' => $flight['photo_required'] ?? 'No'
                        ];
                    }
                }
            }

        } else if ($category === 'accommodation') {
            $rooms = ['basic' => '1-2 Star (Budget)', 'deluxe' => '3-4 Star (Business)', 'premium' => '5 Star (Premium)'];
            foreach ($rooms as $key => $displayName) {
                $minVal = floatval($policy->{$key . '_min'} ?? 0);
                $maxVal = floatval($policy->{$key . '_max'} ?? 0);
                $includesFood = $policy->{$key . '_includes_food'} === 'Yes' || $policy->{$key . '_includes_food'} === true || $policy->{$key . '_includes_food'} === 1;

                if ($minVal > 0 || $maxVal > 0) {
                    $ranges[] = [
                        'name' => $displayName,
                        'min' => $minVal,
                        'max' => $maxVal,
                        'rate' => $maxVal,
                        'ruleType' => 'actual',
                        'includes_food' => $includesFood,
                        'bill' => $policy->{$key . '_bill_required'} ?? 'Yes',
                        'photo' => $policy->{$key . '_photo_required'} ?? 'No'
                    ];
                }
            }

        } else if ($category === 'food') {
            $meals = ['breakfast' => 'Breakfast', 'lunch' => 'Lunch', 'dinner' => 'Dinner', 'two_meals' => 'Two Meals', 'three_meals' => 'Three Meals'];
            foreach ($meals as $key => $displayName) {
                $minVal = floatval($policy->{$key . '_min'} ?? 0);
                $maxVal = floatval($policy->{$key . '_max'} ?? 0);

                if ($minVal > 0 || $maxVal > 0) {
                    $ranges[] = [
                        'name' => $displayName,
                        'min' => $minVal,
                        'max' => $maxVal,
                        'rate' => null,
                        'ruleType' => 'actual',
                        'bill' => $policy->individual_bill_required ?? 'Yes',
                        'photo' => $policy->individual_photo_required ?? 'No'
                    ];
                }
            }

        } else if ($category === 'custom') {
            $policyData = $policy->policy_data;
            if (is_string($policyData)) {
                $policyData = json_decode($policyData, true) ?? [];
            }
            if (is_array($policyData)) {
                foreach ($policyData as $rule) {
                    if (!is_array($rule))
                        continue;
                    $rName = $rule['name'] ?? $rule['claim_name'] ?? 'Custom Rule';
                    $fields = $rule['fields'] ?? [];
                    $ruleType = $rule['type'] ?? 'actual';
                    $rateVal = floatval($fields['rate'] ?? $fields['rate_km'] ?? $fields['rate_night'] ?? $fields['rate_day'] ?? $fields['rate_unit'] ?? 0);

                    $ranges[] = [
                        'name' => $rName,
                        'min' => floatval($fields['min'] ?? $fields['min_amount'] ?? 0),
                        'max' => floatval($fields['max'] ?? $fields['max_amount'] ?? 0),
                        'rate' => $rateVal > 0 ? $rateVal : null,
                        'ruleType' => $ruleType,
                        'unit' => $fields['unit'] ?? null,
                        'bill' => $rule['bill_required'] ?? 'Yes',
                        'photo' => $rule['photo_required'] ?? 'No'
                    ];
                }
            }
        }

        if (empty($ranges)) {
            $ranges[] = [
                'name' => 'Standard',
                'min' => floatval($policy->min_amount ?? 0),
                'max' => floatval($policy->max_amount ?? 0),
                'rate' => null,
                'ruleType' => 'actual',
                'bill' => $policy->bill_required ?? 'Yes',
                'photo' => $policy->photo_required ?? 'No'
            ];
        }

        return $ranges;
    }


    public function show($id)
    {
        try {
            $context = $this->getInstituteContext();
            $claim = ReimbursementClaim::where('id', $id)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if (!$claim) {
                return response()->json(['success' => false, 'message' => 'Claim not found'], 404);
            }

            // Get approval details
            $approval = ReimbursementClaimApproval::where('reimbursement_claim_id', $claim->id)->first();
            if ($approval) {
                $claim->step1_approver_name = $approval->step1_approver_name;
                $claim->step1_status = $approval->step1_status;
                $claim->step1_remarks = $approval->step1_remarks;
                $claim->step2_approver_name = $approval->step2_approver_name;
                $claim->step2_status = $approval->step2_status;
                $claim->step2_remarks = $approval->step2_remarks;
                $claim->approved_amount = $approval->total_approved_amount;
                $claim->decline_reason = $approval->decline_reason;
            }

            return response()->json(['success' => true, 'data' => $claim]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function getByMasterRequest($masterRequestId)
    {
        try {
            $context = $this->getInstituteContext();
            $claims = ReimbursementClaim::where('master_request_id', $masterRequestId)
                ->where('institute_id', $context['institute_id'])
                ->where('branch_id', $context['branch_id'])
                ->orderBy('entry_order', 'asc')
                ->get();

            if ($claims->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No claims found for this batch'], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $claims,
                'master_request_id' => $masterRequestId,
                'total_claims' => $claims->count(),
                'total_amount' => $claims->sum('claim_amount')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load claims: ' . $e->getMessage()
            ], 500);
        }
    }


    public function settlementView($masterRequestId)
    {
        try {
            $context = $this->getInstituteContext();

            $claims = ReimbursementClaim::where('master_request_id', $masterRequestId)
                ->where('institute_id', $context['institute_id'])
                ->where('branch_id', $context['branch_id'])
                ->orderBy('entry_order', 'asc')
                ->get();

            if ($claims->isEmpty()) {
                return redirect()->back()->with('error', 'No claims found for this batch');
            }

            $claimIds = $claims->pluck('id')->toArray();

            $subEntries = ReimbursementClaimSubEntry::whereIn('reimbursement_claim_id', $claimIds)
                ->get()
                ->keyBy('reimbursement_request_id');

            $approvalDetails = ReimbursementClaimApproval::whereIn('reimbursement_claim_id', $claimIds)
                ->get()
                ->keyBy('reimbursement_claim_id');

            // Calculate totals
            $grandTotalClaimed = 0;
            $grandTotalApproved = 0;
            $grandTotalRejected = 0;
            $grandTotalPending = 0;
            $settlementAmount = 0;

            foreach ($claims as $claim) {
                $claim->sub_entries_data = null;
                if (isset($subEntries[$claim->reimbursement_request_id])) {
                    $subEntry = $subEntries[$claim->reimbursement_request_id];
                    $subEntryData = $subEntry->sub_entry_data;
                    if (is_string($subEntryData)) {
                        $subEntryData = json_decode($subEntryData, true);
                    }
                    $claim->sub_entries_data = $subEntryData;
                }

                $approval = $approvalDetails->get($claim->id);
                $claim->approval_detail = $approval;

                // Calculate per-item statuses
                $claimedAmount = floatval($claim->claim_amount ?? 0);
                $approvedAmount = floatval($approval->total_approved_amount ?? 0);

                $grandTotalClaimed += $claimedAmount;

                if ($claim->status === 'approved') {
                    $grandTotalApproved += $approvedAmount;
                    $settlementAmount += $approvedAmount;
                } elseif ($claim->status === 'rejected') {
                    $grandTotalRejected += $claimedAmount;
                } elseif (in_array($claim->status, ['pending', 'step1_approved'])) {
                    $grandTotalPending += $claimedAmount;
                }
            }

            $batchSummary = [
                'master_request_id' => $masterRequestId,
                'total_claims' => $claims->count(),
                'total_claimed' => $grandTotalClaimed,
                'total_approved' => $grandTotalApproved,
                'total_rejected' => $grandTotalRejected,
                'total_pending' => $grandTotalPending,
                'settlement_amount' => $settlementAmount,
                'employee_name' => $claims->first()->name ?? 'N/A',
                'employee_id' => $claims->first()->employee_id ?? 'N/A',
                'designation' => $claims->first()->designation ?? 'N/A',
                'department' => $claims->first()->department ?? 'N/A',
                'submission_date' => $claims->first()->submission_date ?? 'N/A',
            ];

            return view('instituteAdmin.Reimbursement.ReimbursementSettlementView', [
                'claims' => $claims,
                'batchSummary' => $batchSummary,
                'isSettlementView' => true
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in settlementView: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load settlement view');
        }
    }

    public function getPolicyUsage(Request $request)
    {
        try {
            $context = $this->getInstituteContext();
            $policyId = $request->policy_id;
            $employeeId = $request->employee_id;
            $expenseDate = $request->expense_date;

            if (!$policyId || !$employeeId || !$expenseDate) {
                return response()->json(['success' => false, 'message' => 'Missing required parameters'], 422);
            }

            $policy = $this->findPolicyById($policyId, $context);
            if (!$policy) {
                return response()->json(['success' => false, 'message' => 'Policy not found'], 404);
            }

            $calcType = $policy->calculation_type ?? 'per_claim';
            $freqType = $policy->frequency_type ?? 'unlimited';
            $freqValue = intval($policy->frequency_value ?? 1);

            $category = $policy->policy_category;
            $fixedCategories = ['mobile', 'internet', 'entertainment', 'miscellaneous'];
            $allowedRanges = [];

            $employee = EmployeeDetails::where('employee_id', $employeeId)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if ($employee) {
                $assignment = ReimbursementPolicyAssignment::where('reimbursement_policy_id', $policyId)
                    ->where('institute_id', $context['institute_id'])
                    ->where('branch_id', $context['branch_id'])
                    ->where('status', 'active')
                    ->where(function ($query) use ($employee) {
                        $query->where('designation_id', $employee->designation_id)
                            ->orWhere('department_id', $employee->department_id)
                            ->orWhere('department_id', 'all')
                            ->orWhere('employee_id', $employee->employee_id);
                    })->first();

                if ($assignment && $assignment->allowed_ranges) {
                    $assignedRangesRaw = is_string($assignment->allowed_ranges)
                        ? json_decode($assignment->allowed_ranges, true)
                        : $assignment->allowed_ranges;
                    $allowedRanges = $this->buildFullAllowedRangesForPolicy($policy, $assignedRangesRaw);
                }
            }
            if (empty($allowedRanges)) {
                $allowedRanges = $this->buildFullAllowedRangesForPolicy($policy);
            }

            $maxAmount = $this->resolveEffectivePolicyMaxAmount($policy, $allowedRanges);

            // Query for amount (based on calculation_type)
            $amountQuery = ReimbursementClaim::where('employee_id', $employeeId)
                ->where('reimbursement_policy_id', $policyId)
                ->where('institute_id', $context['institute_id'])
                ->where('branch_id', $context['branch_id'])
                ->where('status', '!=', 'rejected');

            // Apply date filter only if not per_claim or lumpsum
            if (!in_array($calcType, ['per_claim', 'lumpsum'])) {
                $date = Carbon::parse($expenseDate);
                switch ($calcType) {
                    case 'per_day':
                        $amountQuery->whereDate('expense_date', $expenseDate);
                        break;
                    case 'per_month':
                        $amountQuery->whereMonth('expense_date', $date->month)
                            ->whereYear('expense_date', $date->year);
                        break;
                    case 'per_quarter':
                        $start = $date->copy()->startOfQuarter();
                        $end = $date->copy()->endOfQuarter();
                        $amountQuery->whereBetween('expense_date', [$start->toDateString(), $end->toDateString()]);
                        break;
                    case 'per_year':
                        $amountQuery->whereYear('expense_date', $date->year);
                        break;
                    default:
                    // no filter
                }
            }

            $existingAmount = $amountQuery->sum('claim_amount');

            // Query for count (based on frequency_type)
            $countQuery = ReimbursementClaim::where('employee_id', $employeeId)
                ->where('reimbursement_policy_id', $policyId)
                ->where('institute_id', $context['institute_id'])
                ->where('branch_id', $context['branch_id'])
                ->where('status', '!=', 'rejected');

            if ($freqType !== 'unlimited') {
                $date = Carbon::parse($expenseDate);
                switch ($freqType) {
                    case 'day':
                        $countQuery->whereDate('expense_date', $date->toDateString());
                        break;
                    case 'week':
                        $countQuery->whereBetween('expense_date', [$date->copy()->startOfWeek()->toDateString(), $date->copy()->endOfWeek()->toDateString()]);
                        break;
                    case 'month':
                        $countQuery->whereMonth('expense_date', $date->month)
                            ->whereYear('expense_date', $date->year);
                        break;
                    case 'quarter':
                        $start = $date->copy()->startOfQuarter();
                        $end = $date->copy()->endOfQuarter();
                        $countQuery->whereBetween('expense_date', [$start->toDateString(), $end->toDateString()]);
                        break;
                    case 'year':
                        $countQuery->whereYear('expense_date', $date->year);
                        break;
                    default:
                    // no filter
                }
            }

            $existingCount = $countQuery->distinct('master_request_id')->count('master_request_id');
            $remainingLimit = $maxAmount > 0 ? max(0, $maxAmount - floatval($existingAmount)) : null;

            // Query DB sub-entries breakdown for (date + range)
            $dbSubEntries = ReimbursementClaimSubEntry::where('employee_id', $employeeId)
                ->where('reimbursement_policy_id', $policyId)
                ->where('institute_id', $context['institute_id'])
                ->where('branch_id', $context['branch_id'])
                ->whereHas('claim', function ($q) {
                    $q->where('status', '!=', 'rejected');
                })
                ->get();

            $dailyRangeUsage = [];
            foreach ($dbSubEntries as $se) {
                $dData = is_string($se->sub_entry_data) ? json_decode($se->sub_entry_data, true) : $se->sub_entry_data;
                if (is_array($dData)) {
                    foreach ($dData as $item) {
                        if (!is_array($item))
                            continue;
                        $rName = $item['range_name'] ?? 'Standard';
                        $sDate = $item['meal_date'] ?? $item['travel_date'] ?? $item['checkin_date'] ?? $item['expense_date'] ?? $item['date'] ?? null;
                        if (!$sDate && $se->claim) {
                            $sDate = $se->claim->expense_date;
                        }
                        $amt = floatval($item['amount'] ?? 0);
                        if ($sDate && $amt > 0) {
                            if (!isset($dailyRangeUsage[$sDate])) {
                                $dailyRangeUsage[$sDate] = ['_total' => 0, '_meal_count' => 0];
                            }
                            if (!isset($dailyRangeUsage[$sDate][$rName])) {
                                $dailyRangeUsage[$sDate][$rName] = 0;
                            }
                            $dailyRangeUsage[$sDate][$rName] += $amt;
                            $dailyRangeUsage[$sDate]['_total'] += $amt;
                            $dailyRangeUsage[$sDate]['_meal_count'] += 1;
                        }
                    }
                }
            }

            $rangeMaxLimits = [];
            if (!empty($allowedRanges) && is_array($allowedRanges)) {
                foreach ($allowedRanges as $r) {
                    if (is_array($r) && !empty($r['name'])) {
                        $rangeMaxLimits[$r['name']] = floatval($r['max'] ?? 0);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'existing_amount' => floatval($existingAmount),
                    'existing_count' => $existingCount,
                    'remaining_limit' => $remainingLimit,
                    'max_amount' => $maxAmount,
                    'frequency_value' => $freqValue,
                    'frequency_type' => $freqType,
                    'calculation_type' => $calcType,
                    'daily_range_usage' => $dailyRangeUsage,
                    'range_max_limits' => $rangeMaxLimits,
                    'food_limits' => [
                        'breakfast_max' => floatval($policy->breakfast_max ?? 0),
                        'lunch_max' => floatval($policy->lunch_max ?? 0),
                        'dinner_max' => floatval($policy->dinner_max ?? 0),
                        'two_meals_max' => floatval($policy->two_meals_max ?? 0),
                        'three_meals_max' => floatval($policy->three_meals_max ?? 0),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getPolicyUsage: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch policy usage: ' . $e->getMessage()
            ], 500);
        }
    }


    public function approvalListForReviewer(Request $request)
    {
        try {
            $context = $this->getInstituteContext();

            // Get current user's designation ID
            $userDesignationId = $request->designation_id;

            // Fallback: get from logged-in user's employee record
            if (!$userDesignationId) {
                $employee = EmployeeDetails::where('user_id', Auth::user()->id)
                    ->where('institute_id', $context['institute_id'])
                    ->first();
                $userDesignationId = $employee->designation_id ?? null;
            }

            if (!$userDesignationId) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No designation found for current user'
                ]);
            }

            // Get claims where current user is an approver
            $approvalDetails = ReimbursementClaimApproval::where('institute_id', $context['institute_id'])
                ->where(function ($q) use ($userDesignationId) {
                    $q->where('step1_approver_id', $userDesignationId)
                        ->orWhere('step2_approver_id', $userDesignationId);
                })
                ->get();

            $claimIds = $approvalDetails->pluck('reimbursement_claim_id')->unique()->toArray();

            if (empty($claimIds)) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No claims assigned for review'
                ]);
            }

            $claims = ReimbursementClaim::whereIn('id', $claimIds)
                ->where('institute_id', $context['institute_id'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Enrich claims with approval and sub-entry data
            $subEntries = ReimbursementClaimSubEntry::whereIn('reimbursement_claim_id', $claimIds)
                ->get()
                ->keyBy('reimbursement_request_id');

            foreach ($claims as $claim) {
                $approval = $approvalDetails->where('reimbursement_claim_id', $claim->id)->first();

                if ($approval) {
                    $claim->step1_approver_name = $this->resolveDesignationName(
                        $approval->step1_approver_id,
                        $approval->step1_approver_name
                    );
                    $claim->step1_status = $approval->step1_status;
                    $claim->step1_remarks = $approval->step1_remarks;
                    $claim->step2_approver_name = $this->resolveDesignationName(
                        $approval->step2_approver_id,
                        $approval->step2_approver_name
                    );
                    $claim->step2_status = $approval->step2_status;
                    $claim->step2_remarks = $approval->step2_remarks;
                    $claim->approved_amount = $approval->total_approved_amount;
                    $claim->decline_reason = $approval->decline_reason;
                }

                // Attach sub-entries
                $claim->sub_entries_data = null;
                if (isset($subEntries[$claim->reimbursement_request_id])) {
                    $subEntryData = $subEntries[$claim->reimbursement_request_id]->sub_entry_data;
                    $claim->sub_entries_data = is_string($subEntryData)
                        ? json_decode($subEntryData, true)
                        : $subEntryData;
                }

                // Add policy-specific fields
                $policy = $this->findPolicyById($claim->reimbursement_policy_id, $context);
                if ($policy) {
                    $this->enrichClaimWithPolicyFields($claim, $policy);
                }
            }

            return response()->json([
                'success' => true,
                'data' => $claims,
                'approver_id' => $userDesignationId
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in approvalListForReviewer: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load claims: ' . $e->getMessage()
            ], 500);
        }
    }


    public function adminClaims(Request $request)
    {
        try {
            $context = $this->getInstituteContext();

            $query = ReimbursementClaim::where('institute_id', $context['institute_id'])
                ->where('branch_id', $context['branch_id']);

            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }

            if ($request->filled('search')) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('employee_id', 'LIKE', "%{$search}%")
                        ->orWhere(
                            'reimbursement_request_id',
                            'LIKE',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'master_request_id',
                            'LIKE',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'policy_name',
                            'LIKE',
                            "%{$search}%"
                        );
                });
            }

            $claims = $query
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $claims
            ]);

        } catch (\Exception $e) {

            \Log::error('Admin claims error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to load admin claims.'
            ], 500);
        }
    }


    // public function getEmployeeClaims(Request $request, $id = null)
    // {
    //     try {
    //         $context = $this->getInstituteContext();

    //         // Get the logged-in employee
    //         $loggedInEmployee = EmployeeDetails::where('user_id', Auth::user()->id)
    //             ->where('institute_id', $context['institute_id'])
    //             ->first();

    //         if (!$loggedInEmployee) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Employee not found for logged-in user'
    //             ], 404);
    //         }

    //         // Determine which employee ID to use
    //         // If the route parameter {id} is provided, use it
    //         // Otherwise, use the logged-in employee's ID
    //         $employeeId = $id ?? $request->employee_id ?? $loggedInEmployee->employee_id;

    //         // IMPORTANT: Authorization check - employees can only see their OWN claims
    //         // If the requested employee ID is different from the logged-in user's employee ID,
    //         // check if the user has admin privileges to view others' claims
    //         // if ($employeeId !== $loggedInEmployee->employee_id) {
    //         //     // Check if the logged-in user has admin/manager privileges
    //         //     // You can implement this check based on your role system
    //         //     $isAdmin = $this->checkIfUserHasAdminPrivileges($loggedInEmployee);

    //         //     if (!$isAdmin) {
    //         //         return response()->json([
    //         //             'success' => false,
    //         //             'message' => 'Unauthorized: You can only view your own claims'
    //         //         ], 403);
    //         //     }
    //         // }

    //         // Fetch claims for the determined employee ID
    //         $claims = ReimbursementClaim::where('institute_id', $context['institute_id'])
    //             ->where('employee_id', $employeeId)
    //             ->orderBy('created_at', 'desc')
    //             ->get();

    //         // Get all claim IDs
    //         $claimIds = $claims->pluck('id')->toArray();

    //         // Enrich with approval details
    //         $approvalDetails = ReimbursementClaimApproval::whereIn('reimbursement_claim_id', $claimIds)
    //             ->get()
    //             ->keyBy('reimbursement_claim_id');

    //         // Get sub-entries
    //         $subEntries = ReimbursementClaimSubEntry::whereIn('reimbursement_claim_id', $claimIds)
    //             ->get()
    //             ->keyBy('reimbursement_request_id');

    //         foreach ($claims as $claim) {
    //             // Attach approval details
    //             $approval = $approvalDetails->get($claim->id);
    //             if ($approval) {
    //                 $claim->step1_approver_name = $this->resolveDesignationName(
    //                     $approval->step1_approver_id,
    //                     $approval->step1_approver_name
    //                 );
    //                 $claim->step1_status = $approval->step1_status;
    //                 $claim->step1_remarks = $approval->step1_remarks;
    //                 $claim->step2_approver_name = $this->resolveDesignationName(
    //                     $approval->step2_approver_id,
    //                     $approval->step2_approver_name
    //                 );
    //                 $claim->step2_status = $approval->step2_status;
    //                 $claim->step2_remarks = $approval->step2_remarks;
    //                 $claim->approved_amount = $approval->total_approved_amount;
    //                 $claim->decline_reason = $approval->decline_reason;
    //                 $claim->settlement_mode = $approval->settlement_mode;
    //                 $claim->payout_amount = $approval->payout_amount;
    //             }

    //             // Attach sub-entries data
    //             $claim->sub_entries_data = null;
    //             if (isset($subEntries[$claim->reimbursement_request_id])) {
    //                 $subEntryData = $subEntries[$claim->reimbursement_request_id]->sub_entry_data;
    //                 $claim->sub_entries_data = is_string($subEntryData)
    //                     ? json_decode($subEntryData, true)
    //                     : $subEntryData;
    //             }

    //             // Add policy-specific fields
    //             $policy = $this->findPolicyById($claim->reimbursement_policy_id, $context);
    //             if ($policy) {
    //                 $this->enrichClaimWithPolicyFields($claim, $policy);
    //             }
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'data' => $claims,
    //             'viewing_own_claims' => ($employeeId === $loggedInEmployee->employee_id)
    //         ]);

    //     } catch (\Exception $e) {
    //         \Log::error('Error in getEmployeeClaims: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to load claims: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }



public function getEmployeeClaims(Request $request)
{
    try {

        $context = $this->getInstituteContext();
        $user = Auth::user();

        \Log::info('===== MY CLAIMS DEBUG START =====', [
            'user_id' => $user?->id,
            'user_employee_id' => $user?->employee_id ?? null,
            'user_institute_id' => $user?->institute_id ?? null,
            'context_institute_id' => $context['institute_id'] ?? null,
            'context_branch_id' => $context['branch_id'] ?? null,
            'request_employee_id' => $request->employee_id ?? null,
        ]);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User is not authenticated.'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | 1. Find logged-in employee
        |--------------------------------------------------------------------------
        */

        $employee = EmployeeDetails::where('institute_id', $context['institute_id'])
            ->where(function ($query) use ($user) {

                $query->where('user_id', $user->id);

                if (!empty($user->employee_id)) {
                    $query->orWhere(
                        'employee_id',
                        $user->employee_id
                    );
                }

            })
            ->first();

        if (!$employee) {

            \Log::error('MY CLAIMS - Employee not found', [
                'user_id' => $user->id,
                'user_employee_id' => $user->employee_id ?? null,
                'institute_id' => $context['institute_id'] ?? null,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Employee record not found for the logged-in user.'
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. IMPORTANT
        | Always use employee_id from EmployeeDetails.
        | Do NOT use request employee_id for My Claims.
        |--------------------------------------------------------------------------
        */

        $employeeId = $employee->employee_id;

        \Log::info('MY CLAIMS - Employee resolved', [
            'employee_table_id' => $employee->id,
            'employee_id' => $employee->employee_id,
            'employee_user_id' => $employee->user_id,
            'employee_institute_id' => $employee->institute_id,
            'employee_branch_id' => $employee->branch_id ?? null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3. Fetch employee's claims
        |--------------------------------------------------------------------------
        */

        $claimsQuery = ReimbursementClaim::query()
            ->where('institute_id', $context['institute_id'])
            ->where('employee_id', $employeeId);

        /*
        |--------------------------------------------------------------------------
        | 4. Branch filter
        |
        | Only apply branch filter when a valid branch context exists.
        | This prevents My Claims from becoming empty because branch_id
        | is NULL/different while the employee and institute are correct.
        |--------------------------------------------------------------------------
        */

        if (!empty($context['branch_id'])) {
            $claimsQuery->where('branch_id', $context['branch_id']);
        }

        $claims = $claimsQuery
            ->orderBy('created_at', 'desc')
            ->get();

        \Log::info('MY CLAIMS - Claims fetched', [
            'employee_id' => $employeeId,
            'institute_id' => $context['institute_id'],
            'branch_id' => $context['branch_id'] ?? null,
            'claim_count' => $claims->count(),
            'claim_ids' => $claims->pluck('id')->toArray(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5. Approval details
        |--------------------------------------------------------------------------
        */

        $claimIds = $claims->pluck('id')->toArray();

        $approvalDetails = ReimbursementClaimApproval::whereIn(
                'reimbursement_claim_id',
                $claimIds
            )
            ->get()
            ->keyBy('reimbursement_claim_id');

        /*
        |--------------------------------------------------------------------------
        | 6. Sub entries
        |--------------------------------------------------------------------------
        */

        $subEntries = ReimbursementClaimSubEntry::whereIn(
                'reimbursement_claim_id',
                $claimIds
            )
            ->get()
            ->keyBy('reimbursement_request_id');

        /*
        |--------------------------------------------------------------------------
        | 7. Enrich claims
        |--------------------------------------------------------------------------
        */

        foreach ($claims as $claim) {

            $approval = $approvalDetails->get($claim->id);

            if ($approval) {

                $claim->step1_approver_name =
                    $this->resolveDesignationName(
                        $approval->step1_approver_id,
                        $approval->step1_approver_name
                    );

                $claim->step1_status =
                    $approval->step1_status;

                $claim->step1_remarks =
                    $approval->step1_remarks;

                $claim->step2_approver_name =
                    $this->resolveDesignationName(
                        $approval->step2_approver_id,
                        $approval->step2_approver_name
                    );

                $claim->step2_status =
                    $approval->step2_status;

                $claim->step2_remarks =
                    $approval->step2_remarks;

                $claim->approved_amount =
                    $approval->total_approved_amount;

                $claim->decline_reason =
                    $approval->decline_reason;

                $claim->settlement_mode =
                    $approval->settlement_mode;

                $claim->payout_amount =
                    $approval->payout_amount;
            }

            /*
            |------------------------------------------------------------------
            | Sub entries
            |------------------------------------------------------------------
            */

            $claim->sub_entries_data = null;

            if (isset($subEntries[$claim->reimbursement_request_id])) {

                $subEntryData =
                    $subEntries[$claim->reimbursement_request_id]
                        ->sub_entry_data;

                $claim->sub_entries_data =
                    is_string($subEntryData)
                        ? json_decode($subEntryData, true)
                        : $subEntryData;
            }

            /*
            |------------------------------------------------------------------
            | Policy fields
            |------------------------------------------------------------------
            */

            $policy = $this->findPolicyById(
                $claim->reimbursement_policy_id,
                $context
            );

            if ($policy) {
                $this->enrichClaimWithPolicyFields(
                    $claim,
                    $policy
                );
            }
        }

        \Log::info('===== MY CLAIMS DEBUG END =====');

        return response()->json([
            'success' => true,
            'data' => $claims,
            'employee_id' => $employeeId,
            'claim_count' => $claims->count(),
            'viewing_own_claims' => true
        ]);

    } catch (\Exception $e) {

        \Log::error('MY CLAIMS ERROR', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to load claims: ' . $e->getMessage()
        ], 500);
    }
}



}
