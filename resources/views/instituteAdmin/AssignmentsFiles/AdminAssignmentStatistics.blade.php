@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<style>
    .stats-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .chart-container {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e3e6f0;
        margin-bottom: 1.5rem;
        height: 400px;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e3e6f0;
    }

    .recent-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .recent-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #e3e6f0;
        transition: background 0.2s ease;
    }

    .recent-item:hover {
        background: #f8f9fc;
    }

    .recent-item:last-child {
        border-bottom: none;
    }

    .teacher-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border-bottom: 1px solid #e3e6f0;
    }

    .teacher-item:last-child {
        border-bottom: none;
    }

    .teacher-rank {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f8f9fc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
        margin-right: 1rem;
    }

    .teacher-rank.rank-1 { background: #fff3cd; color: #856404; }
    .teacher-rank.rank-2 { background: #d1ecf1; color: #0c5460; }
    .teacher-rank.rank-3 { background: #d4edda; color: #155724; }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .summary-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid #e3e6f0;
        transition: transform 0.3s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .summary-number {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .summary-label {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .percentage-change {
        font-size: 0.875rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }

    .percentage-change.positive { color: #1cc88a; }
    .percentage-change.negative { color: #e74a3b; }

    .department-chart {
        width: 100%;
        height: 300px;
    }

    .status-chart {
        width: 100%;
        height: 300px;
    }

    .empty-chart {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #6c757d;
        text-align: center;
    }

    .empty-chart i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .summary-grid {
            grid-template-columns: 1fr;
        }
        
        .chart-container {
            height: 300px;
        }
    }
</style>

<div class="container-fluid">
    <div class="statistics-container">
        <!-- Header -->
        <div class="stats-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-2">
                        <i class="fas fa-chart-bar me-2"></i>Assignment Statistics
                    </h3>
                    <p class="mb-0 opacity-75">Comprehensive overview of assignments across the institute</p>
                </div>
                <div>
                    <a href="{{ route('admin.assignments.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-1"></i>Back to Assignments
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-number text-primary">{{ $totalAssignments }}</div>
                <div class="summary-label">Total Assignments</div>
                <small class="text-muted">Created across all departments</small>
            </div>
            
            <div class="summary-card">
                <div class="summary-number text-success">{{ $totalStudents }}</div>
                <div class="summary-label">Students Assigned</div>
                <small class="text-muted">Total student assignments</small>
            </div>
            
            <div class="summary-card">
                <div class="summary-number text-info">{{ $submittedCount }}</div>
                <div class="summary-label">Submitted Work</div>
                <small class="text-muted">{{ $totalStudents > 0 ? round(($submittedCount/$totalStudents)*100, 1) : 0 }}% submission rate</small>
            </div>
            
            <div class="summary-card">
                <div class="summary-number text-warning">{{ $gradedCount }}</div>
                <div class="summary-label">Graded Work</div>
                <small class="text-muted">{{ $submittedCount > 0 ? round(($gradedCount/$submittedCount)*100, 1) : 0 }}% grading rate</small>
            </div>
        </div>

        <div class="row">
            <!-- Left Column - Charts -->
            <div class="col-lg-8">
                <!-- Assignments by Department -->
                <div class="chart-container">
                    <div class="chart-header">
                        <h5 class="mb-0">
                            <i class="fas fa-building me-2"></i>Assignments by Department
                        </h5>
                    </div>
                    @if($assignmentsByDepartment->count() > 0)
                        <div class="department-chart">
                            <canvas id="departmentChart"></canvas>
                        </div>
                    @else
                        <div class="empty-chart">
                            <div>
                                <i class="fas fa-chart-pie"></i>
                                <p>No department data available</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Assignments by Status -->
                <div class="chart-container">
                    <div class="chart-header">
                        <h5 class="mb-0">
                            <i class="fas fa-list-check me-2"></i>Student Assignments by Status
                        </h5>
                    </div>
                    @if($assignmentsByStatus->count() > 0)
                        <div class="status-chart">
                            <canvas id="statusChart"></canvas>
                        </div>
                    @else
                        <div class="empty-chart">
                            <div>
                                <i class="fas fa-chart-pie"></i>
                                <p>No status data available</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column - Lists -->
            <div class="col-lg-4">
                <!-- Recent Assignments -->
                <div class="chart-container">
                    <div class="chart-header">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>Recent Assignments
                        </h5>
                    </div>
                    @if($recentAssignments->count() > 0)
                        <ul class="recent-list">
                            @foreach($recentAssignments as $assignment)
                                <li class="recent-item">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $assignment->title }}</h6>
                                        <small class="text-muted">
                                            {{ $assignment->employee->name ?? 'Unknown' }} • 
                                            {{ $assignment->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <a href="{{ route('admin.assignments.show', $assignment->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="empty-chart">
                            <div>
                                <i class="fas fa-tasks"></i>
                                <p>No recent assignments</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Top Teachers -->
                <div class="chart-container">
                    <div class="chart-header">
                        <h5 class="mb-0">
                            <i class="fas fa-crown me-2"></i>Top Teachers
                        </h5>
                    </div>
                    @if($topTeachers->count() > 0)
                        <div>
                            @foreach($topTeachers as $index => $teacher)
                                <div class="teacher-item">
                                    <div class="d-flex align-items-center">
                                        <div class="teacher-rank rank-{{ $index + 1 }}">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <h6 class="mb-1">{{ $teacher->employee->name ?? 'Unknown Teacher' }}</h6>
                                            <small class="text-muted">{{ $teacher->employee->designation ?? 'Teacher' }}</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-primary">{{ $teacher->assignment_count }}</div>
                                        <small class="text-muted">assignments</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-chart">
                            <div>
                                <i class="fas fa-chalkboard-teacher"></i>
                                <p>No teacher data available</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Department Chart
    @if($assignmentsByDepartment->count() > 0)
        const departmentCtx = document.getElementById('departmentChart').getContext('2d');
        const departmentChart = new Chart(departmentCtx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($assignmentsByDepartment as $dept)
                        '{{ $dept->department->department ?? "Unknown" }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Assignments',
                    data: [
                        @foreach($assignmentsByDepartment as $dept)
                            {{ $dept->count }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(78, 115, 223, 0.5)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    @endif

    // Status Chart
    @if($assignmentsByStatus->count() > 0)
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach($assignmentsByStatus as $status)
                        '{{ ucfirst($status->status) }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($assignmentsByStatus as $status)
                            {{ $status->count }},
                        @endforeach
                    ],
                    backgroundColor: [
                        'rgba(246, 194, 62, 0.7)',  // Pending - Yellow
                        'rgba(54, 185, 204, 0.7)',   // Submitted - Cyan
                        'rgba(28, 200, 138, 0.7)',   // Graded - Green
                    ],
                    borderColor: [
                        'rgba(246, 194, 62, 1)',
                        'rgba(54, 185, 204, 1)',
                        'rgba(28, 200, 138, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    @endif

    // Print functionality
    window.printReport = function() {
        window.print();
    };
});
</script>
@endsection