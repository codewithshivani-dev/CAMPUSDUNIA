@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Admin Dashboard - Parents</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            *::-webkit-scrollbar {
                width: 5px;
            }
            *::-webkit-scrollbar-thumb {
                background: #007bff;
                border-radius: 10px;
            }
            .card {
                border: none;
                border-radius: 10px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            }

            .icon-box {
                font-size: 28px;
                border-radius: 50%;
                width: 50px;
                height: 50px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
            }

            .icon-bg1 {
                background: #ff6b6b;
            }

            .icon-bg2 {
                background: #a56bff;
            }

            .icon-bg3 {
                background: #feca57;
            }

            .icon-bg4 {
                background: #54a0ff;
            }

            .status-paid {
                background: #28a745;
                color: #fff;
                padding: 3px 10px;
                border-radius: 15px;
                font-size: 12px;
            }

            .status-due {
                background: #dc3545;
                color: #fff;
                padding: 3px 10px;
                border-radius: 15px;
                font-size: 12px;
            }

            .notification-dot {
                font-size: 12px;
                font-weight: 600;
            }

            .notification-item {
                border-left: 4px solid transparent;
                margin-bottom: 15px;
                padding-left: 10px;
            }

            .notification-green {
                border-color: #28a745;
            }

            .notification-yellow {
                border-color: #ffc107;
            }

            .notification-pink {
                border-color: #e83e8c;
            }

            .date-badge {
                font-size: 12px;
                font-weight: 600;
                border-radius: 20px;
                padding: 3px 8px;
                color: #fff;
                display: inline-block;
                margin-bottom: 5px;
            }

            .bg-green {
                background: #28a745;
            }

            .bg-yellow {
                background: #ffc107;
            }

            .bg-pink {
                background: #e83e8c;
            }

            .topTabs {
                display: flex;
                flex-wrap: wrap;
            }

            /* ===== Credit Limit, Wallet, Smart Card, Loan Card Section ===== */

            .card-custom {
                border: none;
                border-radius: 15px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
                padding: 20px;
                height: 180px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            /* Smart Card Styling */
            .smart-card {
                background: #000;
                color: #fff;
                border-radius: 15px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                padding: 20px;
                height: 180px;
            }

            .smart-card p {
                margin: 0;
            }

            /* Wallet Styling */
            .wallet-logo {
                height: 24px;
                margin-right: 8px;
            }

            .wallet-info {
                font-size: 14px;
            }

            .wallet-balance {
                font-size: 16px;
                font-weight: 600;
                color: #007bff;
            }

            /* Loan Card Styling */
            .loan-card h6 {
                font-weight: 700;
                margin-bottom: 5px;
            }

            .loan-card p {
                margin-bottom: 2px;
                font-size: 14px;
            }

            .amount {
                font-weight: 700;
                color: #000;
                font-size: 18px;
            }

            /* Responsive */
            @media (max-width: 991px) {
                .card-custom,
                .smart-card {
                    height: auto;
                    margin-bottom: 15px;
                }
            }

            #creditGauge {
                /* height: 120px !important;
                 width: 100% !important; */
                max-height: 130px;
            }
            .notification-card {
                max-height: 555px;
                overflow-y: scroll;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid p-4">
            <!-- Breadcrumb -->
            <div class="mb-3">
                <span class="text-muted">Home / Parents</span>
            </div>

            <!-- Top Cards -->
            <div class="row g-3 mb-4">
                <!-- Credit Limit -->
                <div class="col-lg-3 col-md-6">
                    <div class="card card-custom text-center">
                        <h6>Credit Limit</h6>
                        <canvas id="creditGauge" class="credit-chart"></canvas>
                    </div>
                </div>

                <!-- Wallet -->
                <div class="col-lg-3 col-md-6">
                    <div class="card card-custom">
                        <h5>
                            <img
                                src="/public/image/campusduniaLogo.png"
                                class="wallet-logo"
                            />
                        </h5>
                        <h6 class="mb-2">CampusDunia Wallet</h6>
                        <div class="wallet-info mt-3">
                            <p>
                                Wallet ID :
                                <span class="text-primary fw-semibold"
                                    >WLT123456789</span
                                >
                            </p>
                            <p>
                                Wallet Balance :
                                <span class="wallet-balance">₹5000</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Smart Card -->
                <div class="col-lg-3 col-md-6">
                    <div class="smart-card">
                        <h6>Smart Card</h6>
                        <div>
                            <p style="letter-spacing: 2px">
                                4629 5289 0000 0228
                            </p>
                            <p>Valid Thru <strong>06/29</strong></p>
                            <p class="fw-bold">TARUN DHIMAN</p>
                        </div>
                    </div>
                </div>

                <!-- Loan Card -->
                <div class="col-lg-3 col-md-6">
                    <div class="card card-custom loan-card">
                        <h6>Loan Card</h6>
                        <p class="fw-semibold mb-1">TARUN DHIMAN</p>
                        <p class="text-primary fw-bold mb-1">Personal Loan</p>
                        <p>XXXXXXXXXX1999</p>
                        <p class="text-secondary">@10%</p>
                        <div
                            class="d-flex justify-content-between align-items-center mt-2"
                        >
                            <span>Outstanding Amount</span>
                            <span class="amount">₹500000</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Stat Cards -->
            <div class="row g-3 mb-4 topTabs">
                <div class="col-md-6">
                    <div
                        class="card p-4 d-flex align-items-center justify-content-between flex-row"
                    >
                        <div>
                            <div class="fw-bold">Due Fees</div>
                            <div class="h5">₹4503</div>
                        </div>
                        <div class="icon-box icon-bg1">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div
                        class="card p-4 d-flex align-items-center justify-content-between flex-row"
                    >
                        <div>
                            <div class="fw-bold">Notifications</div>
                            <div class="h5">12</div>
                        </div>
                        <div class="icon-box icon-bg2">
                            <i class="bi bi-bell"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div
                        class="card p-4 d-flex align-items-center justify-content-between flex-row"
                    >
                        <div>
                            <div class="fw-bold">Result</div>
                            <div class="h5">16</div>
                        </div>
                        <div class="icon-box icon-bg3">
                            <i class="bi bi-mortarboard"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div
                        class="card p-4 d-flex align-items-center justify-content-between flex-row"
                    >
                        <div>
                            <div class="fw-bold">Expenses</div>
                            <div class="h5">₹193000</div>
                        </div>
                        <div class="icon-box icon-bg4">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Kids -->
            <div class="card p-4 mb-4">
                <h5 class="mb-3">My Kids</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="kidsImg">
                                <img
                                    src="https://cdn-icons-png.flaticon.com/512/219/219969.png"
                                    class="me-3"
                                    width="70"
                                />
                            </div>
                            <div>
                                <p><b>Name:</b> Jessia Rose</p>
                                <p><b>Gender:</b> Female</p>
                                <p><b>Class:</b> 2 "A"</p>
                                <p><b>Roll:</b> #2225</p>
                                <!-- <p><b>Section:</b> A</p> -->
                                <p><b>Admission Id:</b> #0021</p>
                                <p><b>Admission Date:</b> 07.08.2017</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="kidsImg">
                                <img
                                    src="https://cdn-icons-png.flaticon.com/512/219/219983.png"
                                    class="me-3"
                                    width="70"
                                />
                            </div>
                            <div>
                                <p><b>Name:</b> Jack Steve</p>
                                <p><b>Gender:</b> Male</p>
                                <p><b>Class:</b> 3 "A"</p>
                                <p><b>Roll:</b> #2205</p>
                                <!-- <p><b>Section:</b> A</p> -->
                                <p><b>Admission Id:</b> #0045</p>
                                <p><b>Admission Date:</b> 07.08.2017</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- All Expenses -->
            <div class="card p-4 mb-4">
                <h5 class="mb-3">All Expenses</h5>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Search by Exam..."
                        />
                    </div>
                    <div class="col-md-3">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Search by Subject..."
                        />
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" />
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-warning w-100">SEARCH</button>
                    </div>
                </div>
                <table class="table table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Expanse</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>E-Mail</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#0021</td>
                            <td>Exam Fees</td>
                            <td>₹150.00</td>
                            <td><span class="status-paid">Paid</span></td>
                            <td>ABCschool@gmail.com</td>
                            <td>22/02/2019</td>
                        </tr>
                        <tr>
                            <td>#0022</td>
                            <td>Semister Fees</td>
                            <td>₹350.00</td>
                            <td><span class="status-due">Due</span></td>
                            <td>ABCschool@gmail.com</td>
                            <td>22/02/2019</td>
                        </tr>
                        <tr>
                            <td>#0023</td>
                            <td>Exam Fees</td>
                            <td>₹150.00</td>
                            <td><span class="status-paid">Paid</span></td>
                            <td>ABCschool@gmail.com</td>
                            <td>22/02/2019</td>
                        </tr>
                        <tr>
                            <td>#0024</td>
                            <td>Exam Fees</td>
                            <td>₹150.00</td>
                            <td><span class="status-due">Due</span></td>
                            <td>ABCschool@gmail.com</td>
                            <td>22/02/2019</td>
                        </tr>
                        <tr>
                            <td>#0025</td>
                            <td>Exam Fees</td>
                            <td>₹150.00</td>
                            <td><span class="status-paid">Paid</span></td>
                            <td>ABCschool@gmail.com</td>
                            <td>22/02/2019</td>
                        </tr>
                        <tr>
                            <td>#0026</td>
                            <td>Semister Fees</td>
                            <td>₹350.00</td>
                            <td><span class="status-due">Due</span></td>
                            <td>ABCschool@gmail.com</td>
                            <td>22/02/2019</td>
                        </tr>
                        <tr>
                            <td>#0027</td>
                            <td>Exam Fees</td>
                            <td>₹150.00</td>
                            <td><span class="status-paid">Paid</span></td>
                            <td>ABCschool@gmail.com</td>
                            <td>22/02/2019</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Notifications + Exam Results -->
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card notification-card p-4">
                        <h6>Notifications</h6>
                        <div class="notification-item notification-green">
                            <span class="date-badge bg-green"
                                >16 June, 2019</span
                            >
                            <p>
                                Lorem ipsum dolor sit amet consectetur
                                adipisicing elit. Nobis architecto sequi
                                eligendi!
                            </p>
                            <small>Jenny / 5 min ago</small>
                        </div>
                        <div class="notification-item notification-yellow">
                            <span class="date-badge bg-yellow"
                                >16 June, 2019</span
                            >
                            <p>Lorem ipsum dolor sit amet.</p>
                            <small>Jenny / 5 min ago</small>
                        </div>
                        <div class="notification-item notification-pink">
                            <span class="date-badge bg-pink"
                                >16 June, 2019</span
                            >
                            <p>
                                Lorem ipsum dolor sit amet consectetur,
                                adipisicing elit. Omnis, in perferendis.
                            </p>
                            <small>Jenny / 5 min ago</small>
                        </div>
                        <div class="notification-item notification-pink">
                            <span class="date-badge bg-pink"
                                >16 June, 2019</span
                            >
                            <p>
                                Lorem ipsum dolor, sit amet consectetur
                                adipisicing elit.
                            </p>
                            <small>Jenny / 5 min ago</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card p-4">
                        <h6>All Exam Results</h6>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Search by Exam..."
                                />
                            </div>
                            <div class="col-md-3">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Search by Subject..."
                                />
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" />
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-warning w-100">
                                    SEARCH
                                </button>
                            </div>
                        </div>
                        <table class="table table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Exam Name</th>
                                    <th>Subject</th>
                                    <th>Class</th>
                                    <th>Roll</th>
                                    <th>Grade</th>
                                    <th>Percent</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#0021</td>
                                    <td>Class Test</td>
                                    <td>English</td>
                                    <td>2</td>
                                    <td>#0045</td>
                                    <td>A</td>
                                    <td>99.00</td>
                                </tr>
                                <tr>
                                    <td>#0022</td>
                                    <td>Class Test</td>
                                    <td>English</td>
                                    <td>1</td>
                                    <td>#0025</td>
                                    <td>A</td>
                                    <td>99.00</td>
                                </tr>
                                <tr>
                                    <td>#0023</td>
                                    <td>Class Test</td>
                                    <td>Drawing</td>
                                    <td>2</td>
                                    <td>#0045</td>
                                    <td>A</td>
                                    <td>99.00</td>
                                </tr>
                                <tr>
                                    <td>#0024</td>
                                    <td>Class Test</td>
                                    <td>English</td>
                                    <td>1</td>
                                    <td>#0048</td>
                                    <td>A</td>
                                    <td>99.00</td>
                                </tr>
                                <tr>
                                    <td>#0025</td>
                                    <td>Class Test</td>
                                    <td>Chemistry</td>
                                    <td>8</td>
                                    <td>#0025</td>
                                    <td>D</td>
                                    <td>70.00</td>
                                </tr>
                                <tr>
                                    <td>#0025</td>
                                    <td>Class Test</td>
                                    <td>Bangla</td>
                                    <td>4</td>
                                    <td>#0045</td>
                                    <td>C</td>
                                    <td>80.00</td>
                                </tr>
                                <tr>
                                    <td>#0025</td>
                                    <td>Class Test</td>
                                    <td>Drawing</td>
                                    <td>2</td>
                                    <td>#0045</td>
                                    <td>C</td>
                                    <td>80.00</td>
                                </tr>
                                <tr>
                                    <td>#0025</td>
                                    <td>Class Test</td>
                                    <td>English</td>
                                    <td>4</td>
                                    <td>#0048</td>
                                    <td>B</td>
                                    <td>99.00</td>
                                </tr>
                                <tr>
                                    <td>#0025</td>
                                    <td>First Semester</td>
                                    <td>English</td>
                                    <td>2</td>
                                    <td>#0045</td>
                                    <td>A</td>
                                    <td>99.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap Icons + JS -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
            rel="stylesheet"
        />
        <script>
            // ===== Gauge Chart for Credit Limit =====
            const ctxGauge = document
                .getElementById("creditGauge")
                .getContext("2d");
            new Chart(ctxGauge, {
                type: "doughnut",
                data: {
                    labels: ["Pending", "Used"],
                    datasets: [
                        {
                            data: [60, 40],
                            backgroundColor: ["#ff5733", "#d3d3d3"],
                            borderWidth: 0,
                            cutout: "75%",
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: true, position: "bottom" } },
                },
            });
        </script>
    </body>
</html>
@endsection