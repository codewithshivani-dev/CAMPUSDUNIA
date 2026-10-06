<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <!-- Select2 CSS -->
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" /> -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons (keeping for compatibility) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    @yield('top')
    
<style>
    :root {
        --primary-color: #4e73df;
        --primary-dark: #224abe;
        --secondary-color: #858796;
        --success-color: #1cc88a;
        --info-color: #36b9cc;
        --warning-color: #f6c23e;
        --danger-color: #e74a3b;
        --light-color: #f8f9fc;
        --dark-color: #5a5c69;
        --sidebar-width: 260px;
        --sidebar-collapsed-width: 80px;
        --sidebar-bg: #ffffff;
        --sidebar-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        --menu-hover-bg: #f8f9fc;
        --menu-active-bg: #eef2f9;
        --menu-text: #3a3b45;
        --menu-icon: #858796;
        --menu-active-color: #4e73df;
    }

    body {
        font-family: 'Nunito', sans-serif;
        background-color: #f8f9fc;
        overflow-x: hidden;
    }

    /* Wrapper */
    #wrapper {
        display: flex;
        width: 100%;
        height: 100vh;
        overflow: visible !important;
    }

    /* Sidebar Styles - Updated */
    #accordionSidebar {
        width: var(--sidebar-width);
        background: var(--sidebar-bg) !important;
        box-shadow: var(--sidebar-shadow);
        transition: all 0.3s ease;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
        position: relative;
        z-index: 1000;
        flex-shrink: 0;
    }

    /* Custom Scrollbar with Blue Gradient */
    #accordionSidebar::-webkit-scrollbar {
        width: 6px;
    }

    #accordionSidebar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    #accordionSidebar::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #4e73df 0%, #224abe 100%);
        border-radius: 10px;
    }

    #accordionSidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #224abe 0%, #1a3a8f 100%);
    }

    /* COLLAPSED SIDEBAR */
    #accordionSidebar.collapsed {
        width: 80px;
    }

    #accordionSidebar.collapsed .nav-link span,
    #accordionSidebar.collapsed .sidebar-brand-text {
        display: none;
    }

    #accordionSidebar.collapsed .nav-link {
        justify-content: center;
        position: relative;
    }

    #accordionSidebar.collapsed .nav-link i {
        font-size: 1.3rem;
        margin: 0 !important;
    }

    /* Hide default collapse */
    #accordionSidebar.collapsed .collapse {
        display: none !important;
    }

    /* Tablet default collapsed state */
    @media (min-width: 768px) and (max-width: 991.98px) {
        #accordionSidebar {
            width: var(--sidebar-collapsed-width) !important;
        }
        
        #accordionSidebar:not(.show) {
            transform: translateX(-100%);
        }
        
        #accordionSidebar.show {
            width: var(--sidebar-width) !important;
            transform: translateX(0);
        }
        
        #accordionSidebar.collapsed .nav-link span,
        #accordionSidebar.collapsed .sidebar-brand-text {
            display: none;
        }
        
        #accordionSidebar .nav-link {
            justify-content: center;
            padding: 0.75rem !important;
        }
        
        #accordionSidebar .nav-link i {
            margin: 0;
            font-size: 1.2rem;
        }
        
        #accordionSidebar.show .nav-link span,
        #accordionSidebar.show .sidebar-brand-text {
            display: inline-block;
        }
        
        #accordionSidebar.show .nav-link {
            justify-content: flex-start;
            padding: 0.75rem 1rem !important;
        }
    }

    /* Mobile/Tablet Sidebar */
    @media (max-width: 991.98px) {
        #accordionSidebar {
            position: fixed;
            left: 0;
            top: 0;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            box-shadow: var(--sidebar-shadow);
        }

        #accordionSidebar.show {
            transform: translateX(0);
        }

        #accordionSidebar.collapsed {
            width: var(--sidebar-width) !important;
        }
    }

    /* Mobile hover tooltips for collapsed sidebar */
    #accordionSidebar.collapsed .nav-item {
        position: relative;
    }

    #accordionSidebar.collapsed .nav-item:hover .nav-link span {
        display: block !important;
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        background: var(--primary-color);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0 4px 4px 0;
        white-space: nowrap;
        z-index: 1000;
        margin-left: 5px;
        box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
    }

    /* Hover dropdown for collapsed sidebar submenus */
    #accordionSidebar.collapsed .nav-item.dropdown-hover {
        position: relative;
    }

    #accordionSidebar.collapsed .nav-item:hover .hover-dropdown-menu {
        display: block !important;
        position: absolute;
        left: 100%;
        top: 0;
        background: white;
        min-width: 200px;
        border-radius: 4px;
        box-shadow: var(--sidebar-shadow);
        z-index: 1001;
        margin-left: 5px;
        padding: 0.5rem 0;
    }

    .hover-dropdown-menu {
        display: none;
    }

    .hover-dropdown-menu .dropdown-item {
        padding: 0.5rem 1rem;
        color: var(--menu-text);
        text-decoration: none;
        display: block;
        white-space: nowrap;
    }

    .hover-dropdown-menu .dropdown-item:hover {
        background: var(--menu-hover-bg);
        color: var(--menu-active-color);
    }

    .hover-dropdown-menu .dropdown-item i {
        width: 20px;
        margin-right: 10px;
        color: var(--primary-color);
    }

    /* Mobile overlay */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }

    .sidebar-overlay.show {
        display: block;
    }

    /* Sidebar Brand - Updated */
    .sidebar-brand {
        height: auto;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 1rem;
        font-weight: 800;
        padding: 1.5rem 1rem;
        text-transform: uppercase;
        letter-spacing: 0.05rem;
        color: var(--menu-text);
        border-bottom: 1px solid #e3e6f0;
        white-space: nowrap;
    }

    .sidebar-brand:hover {
        color: var(--menu-text);
        text-decoration: none;
    }

    .sidebar-brand img {
        max-width: 120px;
        height: auto;
        transition: all 0.3s ease;
    }

    #accordionSidebar {
        will-change: transform;
    }

    #accordionSidebar.collapsed .sidebar-brand img {
        max-width: 40px;
    }

    /* Sidebar Items - Updated */
    .sidebar-nav {
        padding-top: 1rem;
    }

    .sidebar-nav .nav-item {
        position: relative;
        margin-bottom: 0.25rem;
    }

    .sidebar-nav .nav-link {
        color: var(--menu-text) !important;
        padding: 0.75rem 1rem !important;
        display: flex !important;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.2s;
        white-space: nowrap;
        text-decoration: none;
        border-radius: 0;
        font-weight: 600;
    }

    .sidebar-nav .nav-link i {
        width: 1.5rem;
        text-align: center;
        color: var(--menu-icon);
        font-size: 1rem;
        flex-shrink: 0;
        transition: all 0.2s;
    }

    .sidebar-nav .nav-link span {
        font-size: 0.9rem;
        transition: opacity 0.3s ease;
    }

    .sidebar-nav .nav-link:hover {
        color: var(--menu-active-color) !important;
        background: var(--menu-hover-bg);
    }

    .sidebar-nav .nav-link:hover i {
        color: var(--menu-active-color) !important;
    }

    .sidebar-nav .nav-link.active {
        color: var(--menu-active-color) !important;
        font-weight: 700;
        background: var(--menu-active-bg);
        border-left: 3px solid var(--primary-color);
    }

    .sidebar-nav .nav-link.active i {
        color: var(--menu-active-color) !important;
    }

    /* Dropdown/Submenu Styles - Updated */
    .sidebar-nav .nav-link[data-bs-toggle="collapse"]::after {
        content: '\f107';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        margin-left: auto;
        transition: transform 0.2s;
        font-size: 0.8rem;
        color: var(--menu-icon);
    }

    .sidebar-nav .nav-link[data-bs-toggle="collapse"].collapsed::after {
        transform: rotate(-90deg);
    }

    #accordionSidebar.collapsed .nav-link[data-bs-toggle="collapse"]::after {
        display: none;
    }

    /* Submenu Styles - Updated */
    .collapse-menu {
        background: #cececf40;
        margin: 0.25rem 0.5rem;
        border-radius: 0.35rem;
        padding: 0.5rem 0;
        list-style: none;
        border-left: 2px solid var(--primary-color);
    }

    #accordionSidebar.collapsed .collapse-menu {
        display: none !important;
    }

    .collapse-item {
        display: block;
        padding: 0.5rem 1rem 0.5rem 2.5rem;
        color: var(--menu-text);
        text-decoration: none;
        font-size: 0.85rem;
        border-radius: 0;
        margin: 0.15rem 0;
        white-space: nowrap;
        transition: all 0.2s;
        position: relative;
        font-weight: 500;
    }

    /* .collapse-item::before {
        content: '';
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--menu-icon);
        opacity: 0.5;
    } */

    .collapse-item:hover {
        background: var(--menu-hover-bg);
        color: var(--menu-active-color);
    }

    .collapse-item:hover::before {
        background: var(--menu-active-color);
        opacity: 1;
    }

    .collapse-item.active {
        color: var(--menu-active-color);
        font-weight: 600;
        background: var(--menu-active-bg);
    }

    .collapse-item.active::before {
        background: var(--menu-active-color);
        opacity: 1;
        width: 6px;
        height: 6px;
    }

    /* Sidebar Toggler - Updated */
    .sidebar-toggler {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        background: var(--menu-hover-bg);
        border: 1px solid #e3e6f0;
        color: var(--menu-icon);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 1rem auto;
        cursor: pointer;
        transition: all 0.2s;
    }

    .sidebar-toggler:hover {
        background: var(--menu-active-bg);
        color: var(--menu-active-color);
        border-color: var(--primary-color);
    }

    /* Divider - Updated */
    #accordionSidebar .dropdown-divider {
        border-top-color: #e3e6f0 !important;
        opacity: 1 !important;
    }

    /* Content Wrapper */
    #content-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        background-color: #f8f9fc;
        transition: margin-left 0.3s ease;
    }

    /* Topbar */
    .topbar {
        height: 4.375rem;
        background: #fff;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);    
        padding: 20px 0px;
    }

    .dropdown-menu {
        z-index: 2000 !important;
    }

    .topbar .navbar-nav .nav-link {
        padding: 0 0.75rem;
        display: flex;
        align-items: center;
        color: #858796;
    }

    .topbar .navbar-nav .nav-link:hover {
        color: #5a5c69;
    }

    .topbar .navbar-nav .nav-link span{
        color: #383838;
    }
    
    .topbar .nav-link:hover i{
        color:#000 !important;
    }

    .topbar .navbar-nav .nav-link i {
        font-size: 1.1rem;
        color: #383838;
    }

    .topbar-divider {
        width: 0;
        border-right: 1px solid #e3e6f0;
        height: calc(4.375rem - 2rem);
        margin: auto 1rem;
    }

    /* Institute Header - Mobile Visible */
    .institute-header {
        padding: 0 1.5rem;
    }

    .institute-name {
        font-size: 1.3rem !important;
        font-weight: 700 !important;
        color: #0d47a1 !important;
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
    }

    .institute-name i {
        color: #1976d2;
        font-size: 1.2rem;
    }

    .institute-name a {
        text-decoration: none;
        color: inherit;
    }

    .institute-name a:hover {
        text-decoration: underline;
    }

    #sidebarToggleTop,
    #sidebarToggleMobile{
        padding:10px !important;
    }

    .institute-meta {
        display: flex;
        gap: 8px;
        font-size: 0.7rem;
        color: #000;
        font-weight: 600;
    }

    .meta-item {
        align-items: center;
        gap: 6px;
    }

    .meta-item i {
        color: #365dcd;
        font-size: 0.8rem;
    }

    /* Mobile Institute Header */
    .mobile-institute-header {
        display: none;
        padding: 0.75rem 1rem;
        background: #fff;
        border-bottom: 1px solid #e3e6f0;
    }

    .mobile-institute-name {
        font-weight: 700;
        color: #0d47a1;
    }

    .mobile-institute-meta {
        font-size: 0.75rem;
        color: #6c757d;
    }

    @media (max-width: 767.98px) {
        .desktop-institute-header {
            display: none !important;
        }
        
        .mobile-institute-header {
            display: block;
        }
    }

    /* Notifications */
    .notification-dropdown {
        width: 360px !important;
        padding: 0 !important;
        right: 0 !important;
        left: auto !important;
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .notification-dropdown .dropdown-item {
        white-space: normal;
        border-bottom: 1px solid #f1f1f1;
        padding: 12px 15px;
    }

    .notification-dropdown .dropdown-item:last-child {
        border-bottom: none;
    }

    .notification-dropdown .dropdown-item.unread {
        background-color: #f0f7ff;
    }

    .notification-dropdown .dropdown-item:hover {
        background-color: #f8f9fc;
    }

    .notification-thumbnail {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        object-fit: cover;
    }

    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .notification-badge {
        position: absolute;
        top: 10px;
        right: 5px;
        font-size: 0.6rem;
        padding: 0.2rem 0.4rem;
    }

    .bg-purple {
        background: linear-gradient(135deg, #6f42c1, #9b6fe0);
    }

    .bg-green{
        background: green;
    }
    
    /* Footer */
    .sticky-footer {
        background: #fff;
        padding: 1rem 0;
        border-top: 1px solid #e3e6f0;
    }

    .scroll-to-top {
        position: fixed;
        right: 1rem;
        bottom: 1rem;
        width: 2.75rem;
        height: 2.75rem;
        background: rgba(90, 92, 105, 0.5);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        text-decoration: none;
        z-index: 1000;
        transition: all 0.2s;
        cursor: pointer;
        border: none;
    }

    .scroll-to-top:hover {
        background: #5a5c69;
        color: #fff;
    }

    /* Global Scrollbar Styles */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #4e73df 0%, #224abe 100%);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #224abe 0%, #1a3a8f 100%);
    }

    /* Tablet Floating Menu */
    .floating-menu-toggle {
        display: none;
        position: fixed;
        left: 1rem;
        bottom: 1rem;
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        z-index: 1001;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .floating-menu-toggle:hover {
        background: var(--primary-dark);
        transform: scale(1.05);
        color: white;
    }

    .floating-menu {
        display: none;
        position: fixed;
        left: 1rem;
        bottom: 5rem;
        width: 240px;
        max-height: 400px;
        overflow-y: auto;
        background: white;
        border-radius: 12px;
        box-shadow: var(--sidebar-shadow);
        z-index: 1001;
        padding: 0.5rem;
    }

    .floating-menu.show {
        display: block;
    }

    .floating-menu .nav-item {
        margin-bottom: 0.25rem;
    }

    .floating-menu .nav-link {
        color: var(--menu-text) !important;
        padding: 0.75rem 1rem !important;
        border-radius: 8px;
        background: transparent;
    }

    .floating-menu .nav-link i {
        color: var(--menu-icon);
    }

    .floating-menu .nav-link:hover {
        background: var(--menu-hover-bg);
        color: var(--menu-active-color) !important;
    }

    .floating-menu .collapse-menu {
        background: #f8f9fa;
        margin: 8px;
        border-radius: 6px;
    }

    .floating-menu .collapse-item {
        color: var(--menu-text);
        padding: 0.5rem 1rem;
    }

    .floating-menu .collapse-item:hover {
        background: var(--menu-hover-bg);
    }

    /* Desktop collapsed state */
    @media (min-width: 992px) {
        #accordionSidebar.collapsed .nav-link span,
        #accordionSidebar.collapsed .sidebar-brand-text {
            display: none;
        }

        #accordionSidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.75rem !important;
        }

        #accordionSidebar.collapsed .nav-link i {
            margin: 0;
            font-size: 1.2rem;
        }

        #accordionSidebar.collapsed .collapse-menu {
            display: none !important;
        }

        #accordionSidebar.collapsed .nav-link[data-bs-toggle="collapse"]::after {
            display: none;
        }
        
        #accordionSidebar.collapsed .nav-item:hover .hover-dropdown-menu {
            display: block;
        }

        .hover-dropdown-menu {
            position: absolute;
            left: 100%;
            top: 0;
            background: #fff;
            min-width: 220px;
            border-radius: 6px;
            box-shadow: var(--sidebar-shadow);
            z-index: 2000;
        }

        .hover-dropdown-menu .collapse-item {
            color: var(--menu-text);
        }
    }

    /* Tablet and Mobile */
    @media (max-width: 991.98px) {
        .floating-menu-toggle {
            display: flex;
        }

        #sidebarToggleTop {
            display: none !important;
        }

        #sidebarToggleMobile {
            display: block !important;
        }
        
        .topbar .navbar-nav {
            flex-direction: row !important;
        }
        
        .topbar .navbar-nav .nav-link {
            padding: 0 0.5rem;
        }
        
        .btn {
            width: auto !important;
        }
    }

    @media (max-width: 767.98px) {
        .desktop-institute-header {
            display: none;
        }
        
        .mobile-institute-header {
            display: block;
        }
        
        .topbar .navbar-nav .nav-link {
            padding: 0 0.5rem;
        }
        
        .notification-dropdown {
            width: 300px !important;
            right: -70px !important;
        }

        .floating-menu-toggle {
            width: 3rem;
            height: 3rem;
            bottom: 0.75rem;
            left: 0.75rem;
        }

        .floating-menu {
            width: 200px;
            left: 0.75rem;
            bottom: 4rem;
        }

        .btn {
            width: auto !important;
        }
    }

    @media (max-width: 425px){
        .notification-dropdown {
            width: 300px !important;
            right: -70px !important;
        }
    }

    /* Utility Classes */
    .bg-gradient-primary {
        background: var(--sidebar-bg) !important;
    }

    .btn-secondary {
        background: white;
        color: #475569;
        border: 2px solid #e0e0e0;
    }

    .btn-secondary:hover {
        color: #475569 !important;
        background: #f8fafc !important;
        border-color: #4361ee !important;
    }
    
    .navbar-nav {
        align-items: center;
    }
    
    .navbar-nav .nav-item {
        margin: 0;
    }
    
    .navbar-nav .nav-link {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .sortable{
        min-width: 200px !important;
        font-weight: 700 !important;
    }
</style>

</head>

<body>
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        @include('partials.dynamic-sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light topbar px-0">
                <div class="container-fluid">
                    <!-- Sidebar Toggle (Desktop) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-none d-lg-inline-block text-dark">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Mobile Toggle -->
                    <button id="sidebarToggleMobile" class="btn btn-link d-lg-none text-dark rounded-circle" style="">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Desktop Institute Header -->
                    @php
                        $serviceInstitutedetails = $serviceInstitutedetails ?? null;
                        if (!empty($serviceInstitutedetails->website)) {
                            $url = $serviceInstitutedetails->website;
                            if (strpos($url, 'www.') === 0) {
                                $url = 'https://' . substr($url, 4);
                            }
                        } else {
                            $url = '#';
                        }
                    @endphp
                    
                    <div class="institute-header desktop-institute-header d-none d-md-block">
                        <div class="institute-name">
                            <i class="fas fa-school"></i>
                            <a href="{{ $url }}" target="_blank">
                                {{ $serviceInstitutedetails->name ?? 'Institute' }}
                            </a>
                        </div>

                        <div class="institute-meta">
                            <div class="meta-item">
                                <i class="fas fa-location-dot"></i>
                                <span>{{ $serviceInstitutedetails->address_line_1 ?? 'Address not available' }}</span>
                                <span>{{ $serviceInstitutedetails->address_line_2 ?? '' }}</span>
                                <span>{{ $serviceInstitutedetails->state ?? '' }}</span>
                                <span>{{ $serviceInstitutedetails->city ?? '' }}</span>
                                <span>{{ $serviceInstitutedetails->pincode ?? '' }}</span>
                            </div>

                            <div class="meta-item d-flex">
                                <i class="fas fa-phone"></i>
                                <span>{{ $serviceInstitutedetails->contact_number ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Navbar Items -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Notifications -->
                        <li class="nav-item dropdown">
                            <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge" style="display: none;">0</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown">
                                <div class="dropdown-header bg-light py-3 px-3 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-bell me-2"></i>Notifications
                                    </h6>
                                    <div class="d-flex">
                                        <button class="btn btn-sm btn-link mark-all-read-btn" title="Mark all as read" type="button">
                                            <i class="fas fa-check-double"></i>
                                        </button>
                                        <button class="btn btn-sm btn-link refresh-notifications" title="Refresh" type="button">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="notification-list-wrapper" style="max-height: 400px; overflow-y: auto;">
                                    <div class="list-group list-group-flush notification-list" id="notificationList">
                                        <div class="notification-loading text-center p-4">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <span class="ms-2">Loading notifications...</span>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $notificationsRoute = '#';
                                    $routeExists = false;
                                    $routeName = '';

                                    if(auth()->check()) {
                                        $user = auth()->user();

                                        if($user->hasRole('superadmin')) {
                                            $routeName = 'superadmin.notifications.index';
                                        } elseif($user->hasRole('admin')) {
                                            $routeName = 'notifications.index';
                                        } elseif($user->hasRole('employee')) {
                                            $routeName = 'employee.notifications.index';
                                        } elseif($user->hasRole('student')) {
                                            $routeName = 'student.notifications.index';
                                        }

                                        $routeExists = Route::has($routeName);

                                        if($routeExists) {
                                            $notificationsRoute = route($routeName);
                                        }
                                    }
                                @endphp
                                <div class="dropdown-footer text-center p-2 border-top">
                                    <a href="{{ $notificationsRoute }}" class="text-primary text-decoration-none d-block py-2">
                                        View All Notifications
                                        <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </li>

                        <!-- Divider -->
                        <li class="nav-item d-none d-sm-block">
                            <span class="topbar-divider"></span>
                        </li>

                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle fa-fw d-lg-none"></i>
                                <span class="d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name ?? 'User' }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                                @guest
                                    @if (Route::has('login'))
                                        <li>
                                            <a class="dropdown-item" href="{{ route('login') }}">
                                                <i class="fas fa-sign-in-alt fa-sm fa-fw me-2 text-gray-400"></i>
                                                {{ __('Login') }}
                                            </a>
                                        </li>
                                    @endif
                                @else
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-power-off fa-sm fa-fw me-2 text-gray-400"></i>
                                            {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                @endguest
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- End of Topbar -->

            <!-- Mobile Institute Header -->
            <div class="mobile-institute-header">
                <div class="mobile-institute-name">
                    <i class="fas fa-school me-2"></i>
                    <a href="{{ $url }}" target="_blank" class="text-decoration-none text-dark">
                        {{ $serviceInstitutedetails->name ?? 'Institute' }}
                    </a>
                </div>
                <div class="mobile-institute-meta mt-1">
                    <i class="fas fa-location-dot me-1"></i>
                    <span>{{ $serviceInstitutedetails->address_line_1 ?? 'Address not available' }}</span>
                    <span>{{ $serviceInstitutedetails->address_line_2 ?? '' }}</span>
                    <span>{{ $serviceInstitutedetails->state ?? '' }}</span>
                    <span>{{ $serviceInstitutedetails->city ?? '' }}</span>
                    <!-- <span>{{ $serviceInstitutedetails->pincode ?? '' }}</span>                     -->
                    <span class="mx-2">|</span>
                    <i class="fas fa-phone me-1"></i>
                    <span>{{ $serviceInstitutedetails->contact_number ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Main Content -->
            <div class="container-fluid py-4">
                @yield('content')
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer mt-auto">
                <div class="container text-center">
                    <span class="text-muted small">Copyright &copy; Cerebrox Tech. Solutions Pvt. Ltd. 2025</span>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button -->
    <button class="scroll-to-top" id="scrollToTop">
        <i class="fas fa-angle-up"></i>
    </button>

    <!-- Floating Menu Toggle (Tablet) -->
    <button class="floating-menu-toggle d-none" id="floatingMenuToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Floating Menu (Tablet) -->
    <div class="floating-menu" id="floatingMenu"></div>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Scripts -->
    <script src="/instituteadmin-vendor/jquery/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="/instituteadmin-vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="/instituteadmin-vendor/chart.js/Chart.min.js"></script>

    @php
        $notificationBaseUrl = '/notifications';
        if(auth()->check()) {
            if(auth()->user()->hasRole('employee')) {
                $notificationBaseUrl = '/employee/notifications';
            } elseif(auth()->user()->hasRole('student')) {
                $notificationBaseUrl = '/student/notifications';
            }
        }
    @endphp

    <script>
        // Notification functions
        let notificationsCache = null;
        let lastFetchTime = 0;
        const CACHE_DURATION = 30000;
        const notificationBaseUrl = "{{ $notificationBaseUrl }}";
        
        $(document).ready(function() {
            // Initialize
            loadNotificationsWithCache();
            initializeSidebar();
            initializeFloatingMenu();
            initializeHoverMenus();
            
            // Set tablet default collapsed state
            if (window.innerWidth >= 768 && window.innerWidth <= 991.98) {
                $('#accordionSidebar').addClass('collapsed');
            }
            
            $('#notificationDropdown').on('click', function(e) {
                loadNotificationsWithCache();
            });
            
            $('.dropdown-footer .view-all-link').on('click', function(e) {
                e.stopPropagation();
                var href = $(this).attr('href');
                if (href && href !== '#') {
                    window.location.href = href;
                }
            });

            $('.select2').select2({
                width: '100%'
            });
        });

        function initializeSidebar() {
            // Desktop toggle
            $('#sidebarToggleTop').on('click', function(e) {
                e.preventDefault();
                $('#accordionSidebar').toggleClass('collapsed');
                initializeHoverMenus();
                // Update toggle icon
                const icon = $(this).find('i');
                if ($('#accordionSidebar').hasClass('collapsed')) {
                    icon.removeClass('fa-bars').addClass('fa-arrow-right');
                } else {
                    icon.removeClass('fa-arrow-right').addClass('fa-bars');
                }
            });

            // Mobile toggle
            $('#sidebarToggleMobile').on('click', function(e) {
                e.preventDefault();

                $('#accordionSidebar').toggleClass('show');

                // ALWAYS remove collapsed on mobile
                if ($('#accordionSidebar').hasClass('show')) {
                    $('#accordionSidebar').removeClass('collapsed');
                }

                $('#sidebarOverlay').toggleClass('show');
            });

            // Close sidebar when clicking overlay
            $('#sidebarOverlay').on('click', function() {
                $('#accordionSidebar').removeClass('show');
                $('#floatingMenu').removeClass('show');
                $(this).removeClass('show');
                
                // Restore collapsed state for tablet
                if (window.innerWidth >= 768 && window.innerWidth <= 991.98) {
                    $('#accordionSidebar').addClass('collapsed');
                }
            });

            // Close floating menu when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#floatingMenuToggle, #floatingMenu').length) {
                    $('#floatingMenu').removeClass('show');
                }
            });

            // Scroll to top
            $('#scrollToTop').on('click', function(e) {
                e.preventDefault();
                $('#content-wrapper').animate({ scrollTop: 0 }, 300);
            });

            // Show/hide scroll to top
            $('#content-wrapper').on('scroll', function() {
                if ($(this).scrollTop() > 100) {
                    $('#scrollToTop').fadeIn();
                } else {
                    $('#scrollToTop').fadeOut();
                }
            });


            // Auto collapse other menus when opening a new one
            $('#accordionSidebar .collapse').on('show.bs.collapse', function () {
                if (!$(this).closest('#accordionSidebar').hasClass('collapsed')) {
                    $('#accordionSidebar .collapse.show').not(this).collapse('hide');
                }
            });
            
            // Handle window resize
            $(window).on('resize', function() {
                if (window.innerWidth >= 768 && window.innerWidth <= 991.98) {
                    if (!$('#accordionSidebar').hasClass('show')) {
                        $('#accordionSidebar').addClass('collapsed');
                    }
                } else if (window.innerWidth > 991.98) {
                    $('#accordionSidebar').removeClass('show');
                }
            });
        }

        function initializeFloatingMenu() {
            // Clone menu items for floating menu
            function updateFloatingMenu() {
                const menuItems = $('#accordionSidebar .sidebar-nav').clone();
                $('#floatingMenu').html(menuItems);
                
                // Fix collapse IDs for floating menu
                $('#floatingMenu [data-bs-toggle="collapse"]').each(function() {
                    const originalId = $(this).attr('href').substring(1);
                    const newId = 'floating-' + originalId;
                    $(this).attr('href', '#' + newId);
                    
                    // Update the collapse element ID
                    const targetElement = $('#floatingMenu').find('#' + originalId);
                    if (targetElement.length) {
                        targetElement.attr('id', newId);
                    }
                });

                // Reinitialize Bootstrap collapses for floating menu
                $('#floatingMenu .collapse').each(function() {
                    new bootstrap.Collapse(this, { toggle: false });
                });

                // Add click handlers for floating menu items
                $('#floatingMenu .nav-link').on('click', function(e) {
                    if (!$(this).attr('data-bs-toggle')) {
                        $('#floatingMenu').removeClass('show');
                    }
                });
            }

            // Initial population
            updateFloatingMenu();

            // Floating menu toggle
            $('#floatingMenuToggle').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#floatingMenu').toggleClass('show');
                updateFloatingMenu(); // Refresh menu content
            });
        }

        function initializeHoverMenus() {
            $('#accordionSidebar .hover-dropdown-menu').remove();

            if (!$('#accordionSidebar').hasClass('collapsed')) return;

            $('#accordionSidebar .nav-item').each(function () {
                const $navItem = $(this);
                const $toggle = $navItem.find('[data-bs-toggle="collapse"]');

                if ($toggle.length) {
                    const targetId = $toggle.attr('href');
                    const $originalMenu = $(targetId).find('.collapse-menu');

                    if ($originalMenu.length) {
                        const $hoverMenu = $('<div class="hover-dropdown-menu"></div>');
                        $hoverMenu.append($originalMenu.clone().show());

                        $navItem.append($hoverMenu);
                    }
                }
            });
        }
        
        // Notification functions (keep all your existing notification functions)
        function loadNotificationsWithCache(forceRefresh = false) {
            const now = Date.now();
    
            if (!forceRefresh && notificationsCache && (now - lastFetchTime) < CACHE_DURATION) {
                updateNotificationList(notificationsCache.notifications);
                updateNotificationBadge(notificationsCache.unread_count);
                return Promise.resolve();
            }
    
            if (!notificationsCache) {
                showLoadingState();
            }
    
            return loadNotifications(forceRefresh);
        }
        
        function showLoadingState() {
            $('#notificationList').html(`
                <div class="text-center p-4">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span class="ms-2">Loading notifications...</span>
                </div>
            `);
        }
    
        function loadNotifications(forceRefresh = false) {
            let notificationsUrl = notificationBaseUrl + '/get';
            
            @if(auth()->check() && auth()->user()->hasRole('employee'))
                notificationsUrl = '/employee/notifications/get';
            @endif
            
            return $.ajax({
                url: notificationsUrl,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        notificationsCache = {
                            notifications: response.notifications,
                            unread_count: response.unread_count
                        };
                        lastFetchTime = Date.now();
                        
                        updateNotificationList(response.notifications);
                        updateNotificationBadge(response.unread_count);
                    }
                },
                error: function(xhr) {
                    console.error('Error loading notifications:', xhr);
                    $('#notificationList').html(`
                        <div class="text-center p-4">
                            <i class="fas fa-exclamation-circle text-danger mb-3"></i>
                            <p class="text-muted mb-2">Failed to load notifications</p>
                        </div>
                    `);
                }
            });
        }
    
        function markAllAsRead() {
            let $btn = $('.mark-all-read-btn');
            let originalHtml = $btn.html();
            $btn.html('<i class="fas fa-spinner fa-spin"></i>');
            
            let markAllUrl = '/notifications/mark-all-read';
            
            @if(auth()->check() && auth()->user()->hasRole('employee'))
                markAllUrl = '/employee/notifications/mark-all-read';
            @endif
            
            $.ajax({
                url: markAllUrl,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        notificationsCache = null;
                        loadNotifications(true);
                    }
                },
                error: function(xhr) {
                    console.error('Error marking all as read:', xhr);
                },
                complete: function() {
                    $btn.html(originalHtml);
                }
            });
        }
    
        function updateNotificationList(notifications) {
            let html = '';
    
            if (notifications && notifications.length > 0) {
                notifications.forEach(function(notification) {
                    let icon = notification.icon || 'bell';
                    let color = notification.color || 'secondary';
                    let unreadClass = !notification.is_read ? 'unread' : '';
                    let timeAgo = notification.time || 'Just now';
    
                    let thumbnailHtml = notification.thumbnail ?
                        `<img src="${notification.thumbnail}" class="notification-thumbnail" alt="thumb">` :
                        `<div class="notification-icon bg-${color} text-white"><i class="fas fa-${icon}"></i></div>`;
    
                    html += `
                    <a href="${notification.action_url}" class="dropdown-item ${unreadClass}" data-id="${notification.id}">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                ${thumbnailHtml}
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">${timeAgo}</small>
                                    ${!notification.is_read ? '<span class="badge bg-danger badge-pill">New</span>' : ''}
                                </div>
                                <p class="mb-1 small ${!notification.is_read ? 'fw-bold' : ''}">${notification.message}</p>
                                <small class="text-primary">${notification.title}</small>
                            </div>
                        </div>
                    </a>
                `;
                });
            } else {
                html = `
                <div class="text-center p-4">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">No notifications</p>
                </div>
            `;
            }
    
            $('#notificationList').html(html);
        }
    
        function updateNotificationBadge(count) {
            if (count > 0) {
                $('.notification-badge').text(count).show();
            } else {
                $('.notification-badge').hide();
            }
        }
    
        $(document).on('click', '#notificationList .dropdown-item', function(e) {
            e.preventDefault();
            var $this = $(this);
            var id = $this.data('id');
            var url = $this.attr('href');
    
            var originalHtml = $this.html();
            $this.html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
    
            $.ajax({
                url: notificationBaseUrl + '/' + id + '/mark-as-read',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        if (notificationsCache) {
                            notificationsCache.unread_count = Math.max(0, notificationsCache.unread_count - 1);
                        }
                        window.location.href = url;
                    }
                },
                error: function(xhr) {
                    console.error('Error marking as read:', xhr);
                    $this.html(originalHtml);
                    showNotification('error', 'Failed to open notification');
                }
            });
        });
    
        function showNotification(type, message) {
            if (typeof toastr !== 'undefined') {
                toastr[type](message);
            }
        }
    
        $('.mark-all-read-btn').on('click', markAllAsRead);
        
        $('.refresh-notifications').on('click', function() {
            notificationsCache = null;
            loadNotifications(true);
        });

        // Active link highlighting
        document.addEventListener("DOMContentLoaded", function() {
            const currentPath = window.location.pathname;
            
            document.querySelectorAll("#accordionSidebar a, #floatingMenu a").forEach(link => {
                const href = link.getAttribute("href");
                if (href && href !== "#" && !href.startsWith("javascript") && href === currentPath) {
                    link.classList.add("active");
                    
                    // Open parent collapse
                    let collapse = link.closest(".collapse");
                    while (collapse) {
                        collapse.classList.add("show");
                        const parentTrigger = document.querySelector(`[href="#${collapse.id}"]`);
                        if (parentTrigger) {
                            parentTrigger.classList.remove("collapsed");
                            parentTrigger.setAttribute("aria-expanded", "true");
                        }
                        collapse = collapse.parentElement?.closest(".collapse");
                    }
                }
            });

            // Icon mapping
            const iconMap = {
                "Superadmin Dashboard": "fa-user-shield",
                "Admin Dashboard": "fa-user-cog",
                "Empolyee Dashboard": "fa-user-tie",
                "Student Dashboard": "fa-user-graduate",
                "Transaction": "fa-right-left",
                "Expense": "fa-money-bill-wave",
                "Branch": "fa-building",
                "Employees": "fa-user-tie",
                "Employee Leaves": "fa-calendar-minus",
                "Working Capital Limit": "fa-credit-card",
                "Profile": "fa-user",
                "Students": "fa-users",
                "Courses": "fa-book-open",
                "Subject Details": "fa-book",
                "Institutes": "fa-school",
                "Leaves": "fa-calendar-days",
                "Library": "fa-book",
                "Gallery": "fa-images",
                "Loans": "fa-file-invoice",
                "CreditLimit": "fa-credit-card",
                "Transport": "fa-bus",
                "Shifts": "fa-clock",
                "Account": "fa-wallet",
                "Syllabus": "fa-book-open",
                "Assignments": "fa-file-pen",
                "Logout": "fa-sign-out-alt",
                "Mark Attendance": "fa-user-check",
                "Attendance Report": "fa-calendar-check",
                "Apply Leaves": "fa-calendar-days",
                "Leave Summary": "fa-list-check",
                "Mark Student Attendance": "fa-user-edit",
                "Assign Subjects": "fa-book",
                "Fee Structure": "fa-money-bill",
                "Library Books": "fa-book-reader",
            };

            document.querySelectorAll(".nav-link").forEach(item => {
                const icon = item.querySelector("i");
                const text = item.querySelector("span")?.textContent.trim() || item.textContent.trim();
                
                if (icon && iconMap[text]) {
                    icon.className = `fas fa-fw ${iconMap[text]}`;
                }
            });
        });
    </script>

    @yield('scripts')
</body>
</html>

