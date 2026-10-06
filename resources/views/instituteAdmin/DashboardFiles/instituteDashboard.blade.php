@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Education Loan Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
    #loanStatusChart{
    display: block;
    box-sizing: border-box;
    }
    .mainCards .card{
        height:150px;
    }
    
</style>
<body>
    <div class="container mt-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="
    font-style: oblique;
">{{$institute_name->institute_name}}</h2>
            <!-- <div>
                <label for="date-filter" class="me-2">Filter by Date:</label>
                <input type="date" id="date-filter" class="form-control d-inline-block" style="width: auto;">
            </div> -->
        </div>

        <!-- Key Metrics -->
        <div class="mainCards row text-center mb-4">
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h4>Total Students Registered</h4>
                        <p class="fs-3">{{$applicant_details}}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h4>Loans in Process</h4>
                        <p class="fs-3">{{$process_loans_count}}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h4>Completed Loans</h4>
                        <p class="fs-3">{{$completed_loans}}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h4>Cancelled Loans</h4>
                        <p class="fs-3">{{$cancelled_loans}}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row mb-4" style="align-items: center;">
            <div class="col-md-6">
                <canvas id="loanStatusChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="loanTrendChart"></canvas>
            </div>
        </div>

        <!-- Student Loan Applications Table -->
        <div class="card mb-4">
            <div class="card-header text-white" style="background-color:#4066d4;">
                <h4>Student Loan Applications</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Registration No</th>
                            <th>Date Of Birth</th>
                            <th>Gender</th>
                            <th>Loan Amount</th>
                            <th>Status</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($loan_applications as $loan_applications_array)
                        <tr>
                            <td>{{ $loan_applications_array->first_name }} {{ $loan_applications_array->middle_name }} {{ $loan_applications_array->last_name }}</td>
                            <td>{{ $loan_applications_array->registration_number }}</td>
                            <td>{{ $loan_applications_array->date_of_birth }}</td>
                            <td>{{ $loan_applications_array->gender }}</td>
                            <td>{{ $loan_applications_array->total_payable_fee	 }}</td>
                            <td>{{ $loan_applications_array->fee_request_status }}</td>
                            <!-- <td><button class="btn btn-primary btn-sm">View</button></td> -->
                        </tr>
                     @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Notifications Section -->
        <!-- <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                Notifications
            </div>
            <div class="card-body">
                <ul>
                    <li>Loan applications pending for over 30 days: 5</li>
                    <li>Upcoming loan due dates: 10</li>
                    <li>Recent loan completions: 15</li>
                </ul>
            </div>
        </div> -->

    </div>

    <!-- JavaScript for Charts -->
    <!-- <script>
        // Loan Status Chart
        const loanStatusCtx = document.getElementById('loanStatusChart').getContext('2d');
        new Chart(loanStatusCtx, {
            type: 'pie',
            data: {
                labels: ['Pending', 'Approved', 'Disbursed', 'Completed'],
                datasets: [{
                    data: [50, 30, 10, 10],
                    backgroundColor: ['#ffc107', '#007bff', '#28a745', '#dc3545'],
                }]
            }
        });

        // Loan Trend Chart
        const loanTrendCtx = document.getElementById('loanTrendChart').getContext('2d');
        new Chart(loanTrendCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Loans Processed',
                    data: [10, 20, 30, 40, 50, 60],
                    borderColor: '#007bff',
                    fill: false,
                }]
            }
        });
    </script> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    fetch('/loan-status-data')
        .then(response => response.json())
        .then(data => {
            // Prepare the data for the chart
            const chartData = {
                labels: ['Processing', 'Completed', 'Cancelled'],
                datasets: [{
                    data: [data.pending, data.completed, data.cancelled],
                    backgroundColor: ['#ffc107', '#28a745', '#dc3545'],
                }]
            };

            // Render the chart
            const loanStatusCtx = document.getElementById('loanStatusChart').getContext('2d');
            new Chart(loanStatusCtx, {
                type: 'pie',
                data: chartData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw || 0;
                                    return `${context.label}: ${value}`;
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching chart data:', error));

        
        // const loanTrendCtx = document.getElementById('loanTrendChart').getContext('2d');
        // new Chart(loanTrendCtx, {
        //     type: 'line',
        //     data: {
        //         labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        //         datasets: [{
        //             label: 'Loans Processed',
        //             data: [10, 20, 30, 40, 50, 60],
        //             borderColor: '#007bff',
        //             fill: false,
        //         }]
        //     }
        // });

        const loanTrendCtx = document.getElementById('loanTrendChart').getContext('2d');

        fetch('/loan-data')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => {
                    const month = new Date(0, item.month - 1).toLocaleString('default', { month: 'short' });
                    return month;
                });
                const loanCounts = data.map(item => item.count);

                new Chart(loanTrendCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Loans Processed',
                            data: loanCounts,
                            borderColor: '#007bff',
                            fill: false,
                        }]
                    }
                });
            })
            .catch(error => console.error('Error fetching loan data:', error));
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection
