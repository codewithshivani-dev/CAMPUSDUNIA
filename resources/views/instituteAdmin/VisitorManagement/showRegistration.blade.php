@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <title>Visitor Management System</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #4bb543;
            --warning-color: #ff9e00;
            --danger-color: #e63946;
        }
        
        .card {
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: none;
            margin-bottom: 25px;
        }
        
        .card-header {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            padding: 20px 25px;
            border-radius: 12px 12px 0 0 !important;
            border: none;
        }
        
        .card-header h4 {
            margin: 0;
            font-weight: 600;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #3a56d4, #2f0b8f);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-registered { background-color: #d1fae5; color: #065f46; }
        .status-checked-in { background-color: #dbeafe; color: #1e40af; }
        .status-checked-out { background-color: #fef3c7; color: #92400e; }
        .status-expired { background-color: #f3f4f6; color: #374151; }
        
        .visitor-code {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #4361ee;
            background: #f0f4ff;
            padding: 3px 8px;
            border-radius: 4px;
            border: 1px solid #e0e7ff;
        }
        
        /*.table th {*/
        /*    background-color: #f8f9fa;*/
        /*    color: #4361ee;*/
        /*    font-weight: 600;*/
        /*    border-bottom: 2px solid #dee2e6;*/
        /*}*/
        
        .table td {
            vertical-align: middle;
            padding: 15px 12px;
        }
        
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .photo-thumbnail {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e0e7ff;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.3s;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
        }
        
        .view-btn { background-color: #dbeafe; color: #1d4ed8; }
        .view-btn:hover { background-color: #bfdbfe; }
        
        .edit-btn { background-color: #fef3c7; color: #d97706; }
        .edit-btn:hover { background-color: #fde68a; }
        
        .delete-btn { background-color: #fee2e2; color: #dc2626; }
        .delete-btn:hover { background-color: #fecaca; }
        
        .checkin-btn { background-color: #d1fae5; color: #059669; }
        .checkin-btn:hover { background-color: #a7f3d0; }
        
        .checkout-btn { background-color: #fef3c7; color: #d97706; }
        .checkout-btn:hover { background-color: #fde68a; }
        
        .search-box {
            position: relative;
            width: 300px;
        }
        
        .search-box input {
            padding-left: 40px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .header-section {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            border-radius: 12px;
            padding: 25px 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .system-logo {
            font-size: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .system-logo i {
            margin-right: 15px;
            font-size: 28px;
        }
        
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }
        
        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }
        
        .stats-icon.registered { background: #d1fae5; color: #065f46; }
        .stats-icon.checked-in { background: #dbeafe; color: #1e40af; }
        .stats-icon.checked-out { background: #fef3c7; color: #d97706; }
        .stats-icon.total { background: #f3e8ff; color: #7c3aed; }
        
        .stats-number {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stats-label {
            color: #6c757d;
            font-size: 14px;
        }
        
        .pagination .page-link {
            color: #4361ee;
            border: 1px solid #dee2e6;
        }
        
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            border-color: #4361ee;
        }
        
        @media (max-width: 768px) {
            .search-box {
                width: 100%;
                margin-bottom: 15px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }
            
            .action-btn {
                width: 30px;
                height: 30px;
                font-size: 12px;
            }
        }
        .table-responsive{
            overflow-x: hidden;
        }
        
        /* ERP Table Styles */
        .erp-table {
            width: 100%;
            background: #fff;
            border-collapse: collapse;
        }
        
        .erp-table thead {
            background: var(--primary-gradient);
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .erp-table th {
            padding: 15px 8px;
            font-weight: 600;
            color: white;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            cursor: pointer;
            user-select: none;
            transition: background-color 0.2s;
            position: relative;
        }

        .erp-table th h6 {
            color: rgba(255,255,255,0.9);
            font-size: 11px;
            margin: 2px 0 0;
        }

        .erp-table th:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .erp-table th.sortable {
            padding-right: 30px;
        }

        .sort-icons {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sort-icon {
            color: rgba(255,255,255,0.5);
            font-size: 12px;
            line-height: 1;
        }

        .sort-icon.active {
            color: white;
        }

        .erp-table td {
            padding: 15px 8px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 14px;
            vertical-align: middle;
        }

        .erp-table tbody tr {
            transition: background-color 0.2s, transform 0.2s;
        }

        .erp-table tbody tr:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .erp-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .erp-table thead .sticky-main,
        .erp-table tbody .sticky-main{
            left: 28px;
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="header-section">
            <div class="system-logo">
                <i class="fas fa-users"></i>
                <div>
                    Visitor Management System
                    <div class="fs-6 fw-normal">Manage and track all visitors</div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="stats-icon total">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-number">{{ $totalVisitors }}</div>
                    <div class="stats-label">Total Visitors</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="stats-icon registered">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="stats-number">{{ $registeredVisitors }}</div>
                    <div class="stats-label">Registered</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="stats-icon checked-in">
                        <i class="fas fa-sign-in-alt"></i>
                    </div>
                    <div class="stats-number">{{ $checkedInVisitors }}</div>
                    <div class="stats-label">Checked In</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="stats-icon checked-out">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                    <div class="stats-number">{{ $checkedOutVisitors }}</div>
                    <div class="stats-label">Checked Out</div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4><i class="fas fa-list me-2"></i>Visitor List</h4>
                    <div class="d-flex gap-3">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchInput" class="form-control" placeholder="Search visitors...">
                        </div>
                        <a href="institute/admin/visitor/registration" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add New Visitor
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                    <table class="erp-table table table-hover" id="visitorsTable">
                        <thead>
                            <tr>
                                <th class="sticky-checkbox">#</th>
                                <th class="sticky-main sortable">Visitor Code</th>
                                <th class="sortable">Photo</th>
                                <th class="sortable">Name</th>
                                <th class="sortable">Contact</th>
                                <th class="sortable">Purpose</th>
                                <th class="sortable">Vehicle</th>
                                <th class="sortable">Registration Time</th>
                                <th class="sortable">Status</th>
                                <th class="sortable">OTP Verified</th>
                                <th class="sortable">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($visitors as $index => $visitor)
                            <tr>
                                <td class="sticky-checkbox">{{ $index + 1 }}</td>
                                <td class="sticky-main">
                                    <span class="visitor-code">{{ $visitor->visitor_code }}</span>
                                </td>
                                <td>
                                    @if($visitor->visitor_photo)
                                        <img src="{{ asset('storage/' . $visitor->visitor_photo) }}" 
                                             alt="Photo" 
                                             class="photo-thumbnail"
                                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($visitor->name) }}&background=4361ee&color=fff&size=40'">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($visitor->name) }}&background=4361ee&color=fff&size=40" 
                                             alt="Photo" 
                                             class="photo-thumbnail">
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $visitor->name }}</strong><br>
                                    <small class="text-muted">{{ $visitor->email }}</small>
                                </td>
                                <td>{{ $visitor->contact_number }}</td>
                                <td>{{ $visitor->purpose }}</td>
                                <td>
                                    @if($visitor->vehicle_type)
                                        <small>{{ $visitor->vehicle_type }}</small><br>
                                        @if($visitor->vehicle_number)
                                            <small class="text-muted">{{ $visitor->vehicle_number }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($visitor->registration_time)->format('d M Y') }}<br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($visitor->registration_time)->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($visitor->status) }}">
                                        {{ $visitor->status }}
                                    </span>
                                </td>
                                <td>
                                    @if($visitor->otp_verified)
                                        <span class="text-success">
                                            <i class="fas fa-check-circle"></i> Yes
                                        </span>
                                    @else
                                        <span class="text-danger">
                                            <i class="fas fa-times-circle"></i> No
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('visitors.show', $visitor->id) }}" 
                                           class="action-btn view-btn"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <!--<a href="{{ route('visitors.edit', $visitor->id) }}" -->
                                        <!--   class="action-btn edit-btn"-->
                                        <!--   title="Edit">-->
                                        <!--    <i class="fas fa-edit"></i>-->
                                        <!--</a>-->
                                        
                                        @if($visitor->status == 'Registered')
                                        <form action="{{ route('visitors.check-in', $visitor->id) }}" 
                                              method="POST" 
                                              style="display: inline;">
                                            @csrf
                                            <button type="submit" 
                                                    class="action-btn checkin-btn"
                                                    title="Check In"
                                                    onclick="return confirm('Check in this visitor?')">
                                                <i class="fas fa-sign-in-alt"></i>
                                            </button>
                                        </form>
                                        @elseif($visitor->status == 'Checked In')
                                        <form action="{{ route('visitors.check-out', $visitor->id) }}" 
                                              method="POST" 
                                              style="display: inline;">
                                            @csrf
                                            <button type="submit" 
                                                    class="action-btn checkout-btn"
                                                    title="Check Out"
                                                    onclick="return confirm('Check out this visitor?')">
                                                <i class="fas fa-sign-out-alt"></i>
                                            </button>
                                        </form>
                                        @endif
                                        
                                        <!--<form action="{{ route('visitors.destroy', $visitor->id) }}" -->
                                        <!--      method="POST" -->
                                        <!--      style="display: inline;">-->
                                        <!--    @csrf-->
                                        <!--    @method('DELETE')-->
                                        <!--    <button type="submit" -->
                                        <!--            class="action-btn delete-btn"-->
                                        <!--            title="Delete"-->
                                        <!--            onclick="return confirm('Are you sure you want to delete this visitor?')">-->
                                        <!--        <i class="fas fa-trash"></i>-->
                                        <!--    </button>-->
                                        <!--</form>-->
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Floating Horizontal Scrollbar -->
                <div class="table-scroll-top" id="tableScrollTop">
                    <div class="table-scroll-inner"></div>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Showing {{ $visitors->firstItem() }} to {{ $visitors->lastItem() }} of {{ $visitors->total() }} entries
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            {{ $visitors->links() }}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-bar me-2"></i>Quick Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <h3 class="text-primary">{{ $todayVisitors }}</h3>
                                <p class="text-muted">Today's Visitors</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <h3 class="text-success">{{ $weekVisitors }}</h3>
                                <p class="text-muted">This Week</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <h3 class="text-info">{{ $monthVisitors }}</h3>
                                <p class="text-muted">This Month</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <h3 class="text-warning">{{ $otpVerifiedVisitors }}</h3>
                                <p class="text-muted">OTP Verified</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTable
            const table = $('#visitorsTable').DataTable({
                "pageLength": 25,
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search visitors..."
                },
                "order": [[0, 'desc']]
            });
            
            // Custom search input
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });
            
            // Style DataTables search box
            $('.dataTables_filter input').addClass('form-control').attr('placeholder', 'Search...');
            
            // Status filter buttons
            const statusFilters = document.createElement('div');
            statusFilters.className = 'btn-group mb-3';
            statusFilters.innerHTML = `
                <button class="btn btn-outline-primary active" data-filter="">All</button>
                <button class="btn btn-outline-primary" data-filter="Registered">Registered</button>
                <button class="btn btn-outline-primary" data-filter="Checked In">Checked In</button>
                <button class="btn btn-outline-primary" data-filter="Checked Out">Checked Out</button>
                <button class="btn btn-outline-primary" data-filter="Expired">Expired</button>
            `;
            
            document.querySelector('.card-body').insertBefore(statusFilters, document.querySelector('.table-responsive'));
            
            // Status filter functionality
            statusFilters.addEventListener('click', function(e) {
                if (e.target.tagName === 'BUTTON') {
                    // Remove active class from all buttons
                    statusFilters.querySelectorAll('button').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    
                    // Add active class to clicked button
                    e.target.classList.add('active');
                    
                    // Filter table
                    const filter = e.target.getAttribute('data-filter');
                    table.column(8).search(filter).draw();
                }
            });
            
            // Export buttons
            const exportButtons = document.createElement('div');
            exportButtons.className = 'd-flex gap-2 mb-3';
            exportButtons.innerHTML = `
                <button class="btn btn-success" id="exportCSV">
                    <i class="fas fa-file-csv me-2"></i>Export CSV
                </button>
                <button class="btn btn-danger" id="exportPDF">
                    <i class="fas fa-file-pdf me-2"></i>Export PDF
                </button>
            `;
            
            statusFilters.insertAdjacentElement('afterend', exportButtons);
            
            // Export functionality
            document.getElementById('exportCSV').addEventListener('click', function() {
                exportTableToCSV('visitors.csv');
            });
            
            document.getElementById('exportPDF').addEventListener('click', function() {
                exportTableToPDF();
            });
            
            function exportTableToCSV(filename) {
                let csv = [];
                let rows = document.querySelectorAll("#visitorsTable tr");
                
                for (let i = 0; i < rows.length; i++) {
                    let row = [], cols = rows[i].querySelectorAll("td, th");
                    
                    for (let j = 0; j < cols.length; j++) {
                        // Skip action column
                        if (j === cols.length - 1) continue;
                        
                        // Clean up data
                        let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, "").replace(/(\s\s)/gm, " ");
                        row.push('"' + data + '"');
                    }
                    
                    csv.push(row.join(","));
                }
                
                // Download CSV file
                let csvFile = new Blob([csv.join("\n")], {type: "text/csv"});
                let downloadLink = document.createElement("a");
                downloadLink.download = filename;
                downloadLink.href = window.URL.createObjectURL(csvFile);
                downloadLink.style.display = "none";
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
            }
            
            function exportTableToPDF() {
                alert('PDF export functionality would be implemented here.\nFor now, you can use the browser print function (Ctrl+P).');
            }
            
            // Auto-refresh every 30 seconds
            setInterval(function() {
                console.log('Auto-refreshing visitor list...');
                // You can implement AJAX refresh here
                // table.ajax.reload();
            }, 30000);
        });
    </script>
@endsection