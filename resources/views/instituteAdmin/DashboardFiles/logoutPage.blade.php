@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Institute Onboarding – Logout</title>
        <!-- <link rel="stylesheet" href="../CSS/logoutPage.css" /> -->
    </head>
    <style>
        /* Background */
        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            /* background: #f3f7ff; */
        }

        /* Containers */
        .logout-container {
            padding: 30px;
            /* max-width: 550px; */
            margin: auto;
        }

        .logout-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 22px;
            color: #0848a8;
        }

        /* USER CARD */
        .logout-user-card {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #ffffff;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 57, 166, 0.08);
            margin-bottom: 25px;
            border-left: 5px solid #0a69ff;
        }

        .logout-user-img {
            width: 199px;
            height: auto;
            border-radius: 50%;
        }

        .logout-username {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            color: #063b8f;
        }

        .logout-user-role {
            font-size: 13px;
            margin: 0;
            color: #666;
        }

        .logout-user-id {
            font-size: 12px;
            margin: 0;
            color: #999;
            margin-top: 7px;
        }

        /* CARDS */
        .logout-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 5px 18px rgba(0, 57, 166, 0.06);
            margin-bottom: 25px;
        }

        .logout-section-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 14px;
            color: #0848a8;
        }

        .logout-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }

        /* List */
        .logout-list {
            padding-left: 20px;
            font-size: 14px;
            color: #444;
        }

        /* Activity */
        .logout-activity-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #e5ecff;
        }
        .logout-activity-item:last-child {
            border-bottom: none;
        }

        /* Final logout card */
        .logout-final-card {
            background: #ffffff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 57, 166, 0.1);
            text-align: center;
        }

        .logout-big-icon {
            width: 65px;
            margin-bottom: 15px;
            filter: brightness(0) saturate(0);
        }

        .logout-confirm-title {
            font-size: 19px;
            font-weight: 700;
            color: #063b8f;
        }

        .logout-confirm-text {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        /* Buttons */
        .two-btn {
            display: flex;
            justify-content: space-around;
        }

        .cancel-btn,
        .logout-btn {
            width: 29%;
            padding: 11px;
            border-radius: 10px;
            border: none;
            font-size: 15px;
            cursor: pointer;
            transition: 0.2s ease;
            font-weight: 600;
        }

        .cancel-btn {
            background: #e6eeff;
            color: #0a49b4;
        }

        .cancel-btn:hover {
            background: #d2dfff;
        }

        .logout-btn {
            background: #0a63f3;
            color: white;
            box-shadow: 0 3px 12px rgba(0, 57, 166, 0.2);
        }

        .logout-btn:hover {
            background: #084cc6;
        }

        /* Active Dot */
        .active-dot {
            width: 10px;
            height: 10px;
            background: #0bd691;
            border-radius: 50%;
            margin-right: 6px;
            animation: blink 1s infinite;
        }

        .flex-center {
            display: flex;
            align-items: center;
        }

        @keyframes blink {
            50% {
                opacity: 0.4;
            }
        }
        .search-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 15px;
        }

        .search-activity-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .search-item {
            display: flex;
            justify-content: space-between;
            padding: 12px;
            border: 1px solid #eee;
            border-radius: 10px;
            background: #fafafa;
            font-size: 14px;
        }
    </style>
    <body>
        <div class="logout-container">
            <h2 class="logout-title">Logout</h2>

            <!-- INSTITUTE CARD -->
            <div class="logout-user-card">
                <img
                    src="{{ asset('image/logo.jpg') }}"
                    class="logout-user-img"
                />

                <div>
                    <h4 class="logout-username">ABC Institute of Technology</h4>
                    <p class="logout-user-role">Onboarding In Progress</p>
                    <p class="logout-user-id">Institute ID: INST-2025-001234</p>
                </div>
            </div>

            <!-- CONTACT DETAILS -->
            <div class="logout-card">
                <h5 class="logout-section-title">Registered Contact Details</h5>

                <div class="logout-row">
                    <span>Contact Person:</span> <strong>Muskaan rajput</strong>
                </div>
                <div class="logout-row">
                    <span>Email:</span> <strong>muskaan732002@gmail.com</strong>
                </div>
                <div class="logout-row">
                    <span>Mobile:</span> <strong>+91 9778885483</strong>
                </div>
            </div>

            <!-- SESSION CARD -->
            <div class="logout-card">
                <h5 class="logout-section-title">Session Information</h5>

                <div class="logout-row">
                    <span>Session Status:</span>
                    <strong class="status-flex">
                        <span class="active-dot"></span>
                        Active
                    </strong>
                </div>

                <div class="logout-row">
                    <span>Logged In At:</span>
                    <strong>12 Feb 2025, 02:45 PM</strong>
                </div>
            </div>

            <!-- DEVICE DETAILS -->
            <div class="logout-card">
                <h5 class="logout-section-title">Current Device Details</h5>

                <div class="logout-row">
                    <span>Device:</span> <strong>Desktop</strong>
                </div>
                <div class="logout-row">
                    <span>Browser:</span> <strong>Chrome (Windows)</strong>
                </div>
                <div class="logout-row">
                    <span>IP Address:</span> <strong>192.168.1.22</strong>
                </div>
                <div class="logout-row">
                    <span>Location:</span> <strong>India</strong>
                </div>
            </div>

            <!-- IMPORTANT NOTES -->
            <div class="logout-card">
                <h5 class="logout-section-title">Important Notes</h5>

                <ul class="logout-list">
                    <li>
                        Complete all onboarding steps to activate the Institute
                        Dashboard.
                    </li>
                    <li>Ensure submitted documents are clear and valid.</li>
                    <li>Keep your Institute credentials confidential.</li>
                </ul>
            </div>

            <!-- 🔍 SEARCH ACTIVITY SECTION (Embedded Here) -->
            <!-- 🔍 SEARCH ACTIVITY SECTION (Dynamic from LocalStorage) -->
            <div class="logout-card">
                <h5 class="logout-section-title">Recent Activity</h5>

                <div id="activityList" class="search-activity-list"></div>

                <button
                    id="viewMoreBtn"
                    style="
                        margin-top: 10px;
                        background: #e8f0ff;
                        color: #0a49b4;
                        border: none;
                        padding: 8px 14px;
                        border-radius: 8px;
                        cursor: pointer;
                        font-size: 14px;
                        font-weight: 600;
                        display: none;
                    "
                >
                    View More
                </button>

                <button
                    id="viewLessBtn"
                    style="
                        margin-top: 10px;
                        background: #e8f0ff;
                        color: #0a49b4;
                        border: none;
                        padding: 8px 14px;
                        border-radius: 8px;
                        cursor: pointer;
                        font-size: 14px;
                        font-weight: 600;
                        display: none;
                    "
                >
                    View Less
                </button>
            </div>

            <!-- END SEARCH ACTIVITY -->

            <!-- FINAL LOGOUT -->
            <div class="logout-final-card">
                <img
                    src="https://cdn-icons-png.flaticon.com/512/1828/1828479.png"
                    class="logout-big-icon"
                />

                <h4 class="logout-confirm-title">Do you want to logout?</h4>
                <p class="logout-confirm-text">
                    Your onboarding progress will be saved. You can continue
                </p>

                <div class="two-btn">
                    <button class="btn cancel-btn">Cancel</button>
                    <button class="btn logout-btn" onclick="logoutInstitute()">
                        Logout
                    </button>
                </div>
            </div>
        </div>

        <script>
            function loadRecentActivity() {
                let activities = localStorage.getItem("userActivity");

if (!activities) {
    const defaultActivities = [
        {
            activity: "Visited Dashboard",
            opened_at: new Date().toISOString()
        },
        {
            activity: "Viewed Institute Profile",
            opened_at: new Date().toISOString()
        },
        {
            activity: "Checked Notifications",
            opened_at: new Date().toISOString()
        },
        {
            activity: "Opened Settings Page",
            opened_at: new Date().toISOString()
        }
    ];

    const listContainer = document.getElementById("activityList");

    defaultActivities.forEach((item) => {
        listContainer.innerHTML += `
            <div class="search-item">
                <strong>${item.activity}</strong>
                <small>${formatTime(item.opened_at)}</small>
            </div>
        `;
    });

    // Hide buttons since static list is always 4
    document.getElementById("viewMoreBtn").style.display = "none";
    document.getElementById("viewLessBtn").style.display = "none";

    return;
}


                activities = JSON.parse(activities);

                // Sort newest first
                activities.reverse();

                const listContainer = document.getElementById("activityList");
                const viewMoreBtn = document.getElementById("viewMoreBtn");
                const viewLessBtn = document.getElementById("viewLessBtn");

                const maxVisible = 4;

                function renderLimited() {
                    listContainer.innerHTML = "";

                    activities.slice(0, maxVisible).forEach((item) => {
                        listContainer.innerHTML += `
            <div class="search-item">
                <strong>${item.activity}</strong>
                <small>${formatTime(item.opened_at)}</small>
            </div>
        `;
                    });

                    // Show View More only if extra items exist
                    viewMoreBtn.style.display =
                        activities.length > maxVisible
                            ? "inline-block"
                            : "none";
                    viewLessBtn.style.display = "none";
                }

                function renderFull() {
                    listContainer.innerHTML = "";

                    activities.forEach((item) => {
                        listContainer.innerHTML += `
            <div class="search-item">
                <strong>${item.activity}</strong>
                <small>${formatTime(item.opened_at)}</small>
            </div>
        `;
                    });

                    viewMoreBtn.style.display = "none";
                    viewLessBtn.style.display = "inline-block";
                }

                // Button Click Events
                viewMoreBtn.onclick = renderFull;
                viewLessBtn.onclick = renderLimited;

                // Load default: Only 4 items
                renderLimited();
            }

            // Time formatting
            function formatTime(timeString) {
                const date = new Date(timeString);
                const now = new Date();
                const diffMs = now - date;

                const mins = Math.floor(diffMs / (1000 * 60));
                const hrs = Math.floor(mins / 60);
                const days = Math.floor(hrs / 24);

                if (mins < 1) return "Just now";
                if (mins < 60) return `${mins} mins ago`;
                if (hrs < 24) return `${hrs} hours ago`;
                return `${days} days ago`;
            }

            loadRecentActivity();
        </script>
    </body>
</html>

@endsection