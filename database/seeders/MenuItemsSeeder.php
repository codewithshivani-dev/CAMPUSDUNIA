<?php
// database/seeders/MenuItemsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;

class MenuItemsSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        MenuItem::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // $menuItems = [
        //     [
        //         'name' => 'Admin Dashboard',
        //         'route' => '/admin/dashboard',
        //         'icon' => 'fas fa-fw fa-tachometer-alt',
        //         'order' => 1,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'dashboard.view'
        //     ],
        //     [
        //         'name' => 'Profile',
        //         'route' => '/institute/admin/view-details',
        //         'icon' => 'fas fa-fw fa-tachometer-alt',
        //         'order' => 2,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'profile.view'
        //     ],
        //     [
        //         'name' => 'Institute',
        //         'icon' => 'fas fa-fw fa-cog',
        //         'order' => 3,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'institute.manage',
        //         'children' => [
        //             [
        //                 'name' => 'View Institute',
        //                 'route' => '/institute/admin/view-details',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'institute.view'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Calendar',
        //         'route' => '/institute/admin/google/calendar',
        //         'icon' => 'fas fa-fw fa-tachometer-alt',
        //         'order' => 4,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'calendar.view'
        //     ],
        //     [
        //         'name' => 'Departments',
        //         'icon' => 'fas fa-fw fa-cog',
        //         'order' => 5,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'departments.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Department Categories',
        //                 'route' => '/add-department-categories',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'departments.categories.manage'
        //             ],
        //             [
        //                 'name' => 'Add Departments',
        //                 'route' => '/departments',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'departments.create'
        //             ],
        //             [
        //                 'name' => 'View Departments',
        //                 'route' => '/departments/view-all',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 3,
        //                 'permission_name' => 'departments.view'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Designations',
        //         'icon' => 'fas fa-fw fa-cog',
        //         'order' => 6,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'designations.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Add Designations',
        //                 'route' => '/designations/create',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'designations.create'
        //             ],
        //             [
        //                 'name' => 'View Designations',
        //                 'route' => '/designations/view',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'designations.view'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Employees',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 7,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'employees.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Add Employee Details',
        //                 'route' => '/institute/admin/addemployees',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'employees.create'
        //             ],
        //             [
        //                 'name' => 'Employee Leave Requests',
        //                 'route' => '/leaves/approvals',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'leaves.manage'
        //             ],
        //             [
        //                 'name' => 'View Employee Attendance',
        //                 'route' => '/institute/admin/monthly-attendance',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 3,
        //                 'permission_name' => 'attendance.view'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Courses',
        //         'icon' => 'fas fa-fw fa-wrench',
        //         'order' => 8,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'courses.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Add Courses',
        //                 'route' => '/institute/admin/add-courses',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'courses.create'
        //             ],
        //             [
        //                 'name' => 'View Courses',
        //                 'route' => '/institute/admin/view-courses',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'courses.view'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Branch',
        //         'icon' => 'fas fa-fw fa-wrench',
        //         'order' => 9,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'branch.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Add Details',
        //                 'route' => '/institute/admin/addcourse-details',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'branch.create'
        //             ],
        //             [
        //                 'name' => 'View Branch Details',
        //                 'route' => '/institute/admin/viewcourse-details',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'branch.view'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Fee Structure',
        //         'icon' => 'fas fa-fw fa-wrench',
        //         'order' => 10,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'fees.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Add Structure',
        //                 'route' => '/add-course-fee-structure',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'fees.create'
        //             ],
        //             [
        //                 'name' => 'View Structure',
        //                 'route' => '/fee-structure/view',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'fees.view'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Students',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 11,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'students.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Add Student Details',
        //                 'route' => '/institute/admin/add-students1',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'students.create'
        //             ],
        //             [
        //                 'name' => 'View Student Details',
        //                 'route' => '/institute/admin/students',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'students.view'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Shifts',
        //         'icon' => 'fas fa-fw fa-users-cog',
        //         'order' => 12,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'shifts.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Create Shift',
        //                 'route' => '/institute/admin/shifts',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'shifts.create'
        //             ],
        //             [
        //                 'name' => 'Assign Shifts - Employee',
        //                 'route' => '/institute/admin/assign-shift',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'shifts.assign.employee'
        //             ],
        //             [
        //                 'name' => 'Assign Shifts - Students',
        //                 'route' => '/shifts/assign-to-students',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 3,
        //                 'permission_name' => 'shifts.assign.students'
        //             ],
        //             [
        //                 'name' => 'View Shift Schedule',
        //                 'route' => '/institute/admin/shift-schedule',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 4,
        //                 'permission_name' => 'shifts.view'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Subject Details',
        //         'icon' => 'fas fa-fw fa-book',
        //         'order' => 13,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'subjects.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Add Subjects',
        //                 'route' => '/institute/admin/subject-coursewise',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'subjects.create'
        //             ],
        //             [
        //                 'name' => 'Assign Subjects - Employee',
        //                 'route' => '/institute/admin/assign-subjects',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'subjects.assign.employee'
        //             ],
        //             [
        //                 'name' => 'Assign Subjects - Student',
        //                 'route' => '/student-subject-assignments',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 3,
        //                 'permission_name' => 'subjects.assign.students'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Assign Leaves',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 14,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'leaves.assign',
        //         'children' => [
        //             [
        //                 'name' => 'Assign To Employees',
        //                 'route' => '/instituteAdmin/leaves/assign',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'leaves.assign.employee'
        //             ],
        //             [
        //                 'name' => 'Assign To Students',
        //                 'route' => '/instituteAdmin/assignleavesto-students',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'leaves.assign.students'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Transport',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 15,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'transport.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Add Transport Details',
        //                 'route' => '/institute/admin/add-transport',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'transport.create'
        //             ],
        //             [
        //                 'name' => 'View Transport Details',
        //                 'route' => '/institute/admin/all-transport-details',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'transport.view'
        //             ],
        //             [
        //                 'name' => 'Assign Transport',
        //                 'route' => '/transport/assign-fee',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 3,
        //                 'permission_name' => 'transport.assign.create'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Hostel Management',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 16,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'hostel.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Add Hostel Fee',
        //                 'route' => '/institute-admin/hostel-fees/create',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'hostel.fees.create'
        //             ],
        //             [
        //                 'name' => 'View Hostel Fee',
        //                 'route' => '/institute-admin/hostel-fees',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'hostel.fees.view'
        //             ],
        //             [
        //                 'name' => 'Assign Hostel Fee',
        //                 'route' => '/institute-admin/assign/hostel-fees',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'hostel.fees.assign.create'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Institutional Charges',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 17,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'institutional.charges.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Add Additional Fee',
        //                 'route' => '/institute/admin/custom-fees/create',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'institutional.charges.create'
        //             ],
        //             [
        //                 'name' => 'View Additional Fee',
        //                 'route' => '/institute/admin/custom-fees',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'institutional.charges.view'
        //             ],
        //             [
        //                 'name' => 'Assign Additional Fee',
        //                 'route' => '/assign/custom-fee',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 3,
        //                 'permission_name' => 'institutional.charges.assign.create'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Assignments',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 18,
        //         'allowed_roles' => ['admin', 'superadmin', 'employee'],
        //         'permission_name' => 'assignments.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Assignments',
        //                 'route' => '/assignments',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'assignments.view'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Syllabus',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 19,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'syllabus.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Add Syllabus',
        //                 'route' => '/syllabus/create',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'syllabus.create'
        //             ],
        //             [
        //                 'name' => 'View All Syllabuses',
        //                 'route' => '/syllabus/view',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'syllabus.view'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Gallery',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 20,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'gallery.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Gallery',
        //                 'route' => '/institute/admin/gallery',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'gallery.view'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Library',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 21,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'library.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Categories',
        //                 'route' => '/library/book-categories',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'library.categories.manage'
        //             ],
        //             [
        //                 'name' => 'All Books',
        //                 'route' => '/library',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'library.books.view'
        //             ],
        //             [
        //                 'name' => 'Add New Book',
        //                 'route' => '/library/add-books',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 3,
        //                 'permission_name' => 'library.books.create'
        //             ],
        //             [
        //                 'name' => 'Issue Books',
        //                 'route' => '/library/issue',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 4,
        //                 'permission_name' => 'library.books.issue'
        //             ],
        //             [
        //                 'name' => 'Return Books',
        //                 'route' => '/library/return',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 5,
        //                 'permission_name' => 'library.books.return'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Collection',
        //         'icon' => 'fas fa-fw fa-coins',
        //         'order' => 22,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'collection.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Course Fees',
        //                 'route' => '/course/fee/collection',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'collection.course.fees'
        //             ],
        //             [
        //                 'name' => 'Registration Fees',
        //                 'route' => '/registration/fee/collection',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'collection.registration.fees'
        //             ],
        //             [
        //                 'name' => 'Hostel Fees',
        //                 'route' => '/hostel/fee/collection',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 3,
        //                 'permission_name' => 'collection.hostel.fees'
        //             ],
        //             [
        //                 'name' => 'Miscellaneous Fees',
        //                 'route' => '/miscellaneous/fee/collection',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 4,
        //                 'permission_name' => 'collection.miscellaneous.fees'
        //             ],
        //             [
        //                 'name' => 'Transportation Fees',
        //                 'route' => '/transport/fee/collection',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 5,
        //                 'permission_name' => 'collection.transport.fees'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Inventory Management',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 23,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'inventory.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Inventory',
        //                 'route' => '/institute/admin/InventoryManagement',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'inventory.view'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Transaction',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 24,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'transactions.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Payment Gateway',
        //                 'route' => '/paymentgateway/transactions',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'transactions.payment.gateway'
        //             ],
        //             [
        //                 'name' => 'Auto Debit',
        //                 'route' => '/paymentgateway/transactions',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'transactions.auto.debit'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Visitor Management',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 25,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'visitor.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Visitor Registration',
        //                 'route' => '/institute/admin/visitor/registration',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'visitor.registration'
        //             ],
        //             [
        //                 'name' => 'Visitor Check In',
        //                 'route' => '/institute/admin/visitor/check-in',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'visitor.check.in'
        //             ],
        //             [
        //                 'name' => 'Visitor Front Desk',
        //                 'route' => '/institute/admin/visitor/front-desk',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 3,
        //                 'permission_name' => 'visitor.front.desk'
        //             ],
        //             [
        //                 'name' => 'Visitor Attendant',
        //                 'route' => '/institute/admin/visitor/attendant',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 4,
        //                 'permission_name' => 'visitor.attendant'
        //             ],
        //             [
        //                 'name' => 'Visitor Check Out',
        //                 'route' => '/institute/admin/visitor/check-out',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 5,
        //                 'permission_name' => 'visitor.check.out'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Exam Structure',
        //         'route' => '/institute/admin/exam-structure',
        //         'icon' => 'fas fa-fw fa-cog',
        //         'order' => 26,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'exam.structure.manage'
        //     ],
        //     [
        //         'name' => 'Report Card',
        //         'route' => '/institute/admin/create-report-card',
        //         'icon' => 'fas fa-fw fa-cog',
        //         'order' => 27,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'report.card.manage'
        //     ],
        //     [
        //         'name' => 'Working Capital Limit',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 28,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'credit.limit.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Introduction',
        //                 'route' => '/institute/admin/introCreditLimit',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'credit.limit.intro'
        //             ],
        //             [
        //                 'name' => 'Working Capital',
        //                 'route' => '/institute/admin/available-credit-limit',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 2,
        //                 'permission_name' => 'credit.limit.view'
        //             ],
        //             [
        //                 'name' => 'Credit Limit Transaction',
        //                 'route' => '/institute/admin/credit-limit-transaction',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 3,
        //                 'permission_name' => 'credit.limit.transaction'
        //             ],
        //             [
        //                 'name' => 'Salary Advance Request',
        //                 'route' => '/institute/admin/advance-salary-request',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 4,
        //                 'permission_name' => 'salary.advance.request'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Loans',
        //         'icon' => 'fas fa-fw fa-users-cog',
        //         'order' => 29,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'loans.manage',
        //         'children' => [
        //             [
        //                 'name' => 'Education Loan',
        //                 'route' => '/institute/admin/loan-repayment-details',
        //                 'icon' => 'fas fa-chart-bar',
        //                 'order' => 1,
        //                 'permission_name' => 'loans.education.manage'
        //             ]
        //         ]
        //     ],
        //     [
        //         'name' => 'Settings',
        //         'route' => '/institute/admin/settings',
        //         'icon' => 'fas fa-fw fa-cog',
        //         'order' => 30,
        //         'allowed_roles' => ['admin', 'superadmin'],
        //         'permission_name' => 'settings.manage'
        //     ],
        //     [
        //         'name' => 'Logout',
        //         'route' => '/institute/admin/logout',
        //         'icon' => 'fas fa-sign-out-alt',
        //         'order' => 31,
        //         'allowed_roles' => ['admin', 'superadmin', 'employee', 'student'],
        //         'permission_name' => 'auth.logout'
        //     ],
        //     [
        //         'name' => 'Employee Dashboard',
        //         'route' => '/employee/dashboard',
        //         'icon' => 'fas fa-fw fa-tachometer-alt',
        //         'order' => 32,
        //         'allowed_roles' => ['employee'],
        //         'permission_name' => 'employee.dashboard.view'
        //     ],
        //     [
        //         'name' => 'Profile',
        //         'route' => '/employee/profile',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 33,
        //         'allowed_roles' => ['employee'],
        //         'permission_name' => 'employee.profile.view'
        //     ],
        //     [
        //         'name' => 'Subjects Timetable',
        //         'route' => '/employee/schedule',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 34,
        //         'allowed_roles' => ['employee'],
        //         'permission_name' => 'employee.schedule.view'
        //     ],
        //     [
        //         'name' => 'Shift',
        //         'route' => '/employee/shift',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 35,
        //         'allowed_roles' => ['employee'],
        //         'permission_name' => 'employee.shift.view'
        //     ],
        //     [
        //         'name' => 'Mark Attendance',
        //         'route' => '/institute/admin/employee-attendance',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 36,
        //         'allowed_roles' => ['employee'],
        //         'permission_name' => 'employee.attendance.mark'
        //     ],
        //     [
        //         'name' => 'Attendance Report',
        //         'route' => '/institute/admin/my-attendance',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 37,
        //         'allowed_roles' => ['employee'],
        //         'permission_name' => 'employee.attendance.view'
        //     ],
        //     [
        //         'name' => 'Apply Leaves',
        //         'route' => '/instituteAdmin/leaves/apply',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 38,
        //         'allowed_roles' => ['employee'],
        //         'permission_name' => 'employee.leaves.apply'
        //     ],
        //     [
        //         'name' => 'Leave Summary',
        //         'route' => '/employee/leave-summary',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 39,
        //         'allowed_roles' => ['employee'],
        //         'permission_name' => 'employee.leaves.summary'
        //     ],
        //     [
        //         'name' => 'Mark Student Attendance',
        //         'route' => '/employee/mark-students-attendance',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 40,
        //         'allowed_roles' => ['employee'],
        //         'permission_name' => 'student.attendance.mark'
        //     ],
        //     [
        //         'name' => 'Student Dashboard',
        //         'route' => '/student/dashboard',
        //         'icon' => 'fas fa-fw fa-tachometer-alt',
        //         'order' => 41,
        //         'allowed_roles' => ['student'],
        //         'permission_name' => 'student.dashboard.view'
        //     ],
        //     [
        //         'name' => 'Profile',
        //         'route' => '/student/profile',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 42,
        //         'allowed_roles' => ['student'],
        //         'permission_name' => 'student.profile.view'
        //     ],
        //     [
        //         'name' => 'Course and Subjects',
        //         'route' => '/student/my-subjects',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 43,
        //         'allowed_roles' => ['student'],
        //         'permission_name' => 'student.subjects.view'
        //     ],
        //     [
        //         'name' => 'My Shifts',
        //         'route' => '/student/my-shift',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 44,
        //         'allowed_roles' => ['student'],
        //         'permission_name' => 'student.shifts.view'
        //     ],
        //     [
        //         'name' => 'Fee Structure',
        //         'route' => '/fee-structure-for-students',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 45,
        //         'allowed_roles' => ['student'],
        //         'permission_name' => 'student.fees.view'
        //     ],
        //     [
        //         'name' => 'Attendance Report',
        //         'route' => '/student/attendance',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 46,
        //         'allowed_roles' => ['student'],
        //         'permission_name' => 'student.attendance.view'
        //     ],
        //     [
        //         'name' => 'Apply Leaves',
        //         'route' => '/student/leave/apply',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 47,
        //         'allowed_roles' => ['student'],
        //         'permission_name' => 'student.leaves.apply'
        //     ],
        //     [
        //         'name' => 'Syllabus',
        //         'route' => '/student/syllabus',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 48,
        //         'allowed_roles' => ['student'],
        //         'permission_name' => 'student.syllabus.view'
        //     ],
        //     [
        //         'name' => 'Assignments',
        //         'route' => '/get-assignment',
        //         'icon' => 'fas fa-fw fa-user-tie',
        //         'order' => 49,
        //         'allowed_roles' => ['student'],
        //         'permission_name' => 'student.assignments.view'
        //     ]
        // ];
        $menuItems = [
            [
                'name' => 'Admin Dashboard',
                'route' => '/admin/dashboard',
                'icon' => 'fas fa-fw fa-tachometer-alt',
                'order' => 1,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'dashboard.view'
            ],
            [
                'name' => 'Profile',
                'route' => '/institute/admin/view-details',
                'icon' => 'fas fa-fw fa-tachometer-alt',
                'order' => 2,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'profile.view'
            ],
            [
                'name' => 'Institute',
                'icon' => 'fas fa-fw fa-cog',
                'order' => 3,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'institute.manage',
                'children' => [
                    [
                        'name' => 'View Institute',
                        'route' => '/institute/admin/view-details',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'institute.view'
                    ]
                ]
            ],
            [
                'name' => 'Calendar',
                'route' => '/institute/admin/google/calendar',
                'icon' => 'fas fa-fw fa-tachometer-alt',
                'order' => 4,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'calendar.view'
            ],
            [
                'name' => 'Departments',
                'icon' => 'fas fa-fw fa-cog',
                'order' => 5,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'departments.manage',
                'children' => [
                    [
                        'name' => 'Department Categories',
                        'route' => '/add-department-categories',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'departments.categories.manage'
                    ],
                    [
                        'name' => 'Add Departments',
                        'route' => '/departments',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'departments.create'
                    ],
                    [
                        'name' => 'View Departments',
                        'route' => '/departments/view-all',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 3,
                        'permission_name' => 'departments.view'
                    ]
                ]
            ],
            [
                'name' => 'Designations',
                'icon' => 'fas fa-fw fa-cog',
                'order' => 6,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'designations.manage',
                'children' => [
                    [
                        'name' => 'Add Designations',
                        'route' => '/designations/create',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'designations.create'
                    ],
                    [
                        'name' => 'View Designations',
                        'route' => '/designations/view',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'designations.view'
                    ]
                ]
            ],
            [
                'name' => 'Employees',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 7,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'employees.manage',
                'children' => [
                    [
                        'name' => 'Add Employee Details',
                        'route' => '/institute/admin/addemployeesdetails',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'employees.create'
                    ],
                    [
                        'name' => 'View Employee Details',
                        'route' => '/institute/admin/addemployees',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'employees.view'
                    ],
                    [
                        'name' => 'Employee Leave Requests',
                        'route' => '/leaves/approvals',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 3,
                        'permission_name' => 'leaves.manage'
                    ],
                    [
                        'name' => 'View Employee Attendance',
                        'route' => '/institute/admin/monthly-attendance',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 4,
                        'permission_name' => 'attendance.view'
                    ]
                ]
            ],
            [
                'name' => 'Courses',
                'icon' => 'fas fa-fw fa-wrench',
                'order' => 8,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'courses.manage',
                'children' => [
                    [
                        'name' => 'Add Courses',
                        'route' => '/institute/admin/add-courses',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'courses.create'
                    ],
                    [
                        'name' => 'View Courses',
                        'route' => '/institute/admin/view-courses',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'courses.view'
                    ]
                ]
            ],
            [
                'name' => 'Branch',
                'icon' => 'fas fa-fw fa-wrench',
                'order' => 9,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'branch.manage',
                'children' => [
                    [
                        'name' => 'Add Details',
                        'route' => '/institute/admin/addcourse-details',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'branch.create'
                    ],
                    [
                        'name' => 'View Branch Details',
                        'route' => '/institute/admin/viewcourse-details',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'branch.view'
                    ]
                ]
            ],
            [
                'name' => 'Fee Structure',
                'icon' => 'fas fa-fw fa-wrench',
                'order' => 10,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'fees.manage',
                'children' => [
                    [
                        'name' => 'Add Structure',
                        'route' => '/add-course-fee-structure',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'fees.create'
                    ],
                    [
                        'name' => 'View Structure',
                        'route' => '/fee-structure/view',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'fees.view'
                    ]
                ]
            ],
            [
                'name' => 'Students',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 11,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'students.manage',
                'children' => [
                    [
                        'name' => 'Add Student Details',
                        'route' => '/institute/admin/add-students1',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'students.create'
                    ],
                    [
                        'name' => 'View Student Details',
                        'route' => '/institute/admin/students',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'students.view'
                    ]
                ]
            ],
            [
                'name' => 'Shifts',
                'icon' => 'fas fa-fw fa-users-cog',
                'order' => 12,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'shifts.manage',
                'children' => [
                    [
                        'name' => 'Create Shift',
                        'route' => '/institute/admin/shifts/',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'shifts.create'
                    ],
                    [
                        'name' => 'Assign Shifts - Employee',
                        'route' => '/institute/admin/assign-shift',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'shifts.assign.employee'
                    ],
                    [
                        'name' => 'Assign Shifts - Students',
                        'route' => '/shifts/assign-to-students',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 3,
                        'permission_name' => 'shifts.assign.students'
                    ],
                    [
                        'name' => 'View Shift Schedule',
                        'route' => '/institute/admin/shift-schedule',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 4,
                        'permission_name' => 'shifts.view'
                    ]
                ]
            ],
            [
                'name' => 'Subject Details',
                'icon' => 'fas fa-fw fa-book',
                'order' => 13,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'subjects.manage',
                'children' => [
                    [
                        'name' => 'Add Subjects',
                        'route' => '/institute/admin/subject-coursewise',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'subjects.create'
                    ],
                    [
                        'name' => 'Assign Subjects - Employee',
                        'route' => '/institute/admin/assign-subjects',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'subjects.assign.employee'
                    ],
                    [
                        'name' => 'Assign Subjects - Student',
                        'route' => '/student-subject-assignments',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 3,
                        'permission_name' => 'subjects.assign.students'
                    ]
                ]
            ],
            [
                'name' => 'Assign Leaves',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 14,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'leaves.assign',
                'children' => [
                    [
                        'name' => 'Assign To Employees',
                        'route' => '/instituteAdmin/leaves/assign',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'leaves.assign.employee'
                    ],
                    [
                        'name' => 'Assign To Students',
                        'route' => '/instituteAdmin/assignleavesto-students',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'leaves.assign.students'
                    ]
                ]
            ],
            [
                'name' => 'Transport',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 15,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'transport.manage',
                'children' => [
                    [
                        'name' => 'Add Transport Details',
                        'route' => '/institute/admin/add-transport',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'transport.create'
                    ],
                    [
                        'name' => 'View Transport Details',
                        'route' => '/institute/admin/all-transport-details',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'transport.view'
                    ],
                    [
                        'name' => 'Assign Transport',
                        'route' => '/transport/assign-fee',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 3,
                        'permission_name' => 'transport.assign.create'
                    ]
                ]
            ],
            [
                'name' => 'Hostel Management',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 16,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'hostel.manage',
                'children' => [
                    [
                        'name' => 'Add Hostel Fee',
                        'route' => '/institute-admin/hostel-fees/create',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'hostel.fees.create'
                    ],
                    [
                        'name' => 'View Hostel Fee',
                        'route' => '/institute-admin/hostel-fees',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'hostel.fees.view'
                    ],
                    [
                        'name' => 'Assign Hostel Fee',
                        'route' => '/institute-admin/assign/hostel-fees',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 3,
                        'permission_name' => 'hostel.fees.assign.create'
                    ]
                ]
            ],
            [
                'name' => 'Custom Fee',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 17,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'custom.fees.manage',
                'children' => [
                    [
                        'name' => 'Add Custom Fee',
                        'route' => '/institute/admin/custom-fees/create',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'custom.fees.create'
                    ],
                    [
                        'name' => 'View Custom Fee',
                        'route' => '/institute/admin/custom-fees',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'custom.fees.view'
                    ],
                    [
                        'name' => 'Assign Custom Fee',
                        'route' => '/assign/custom-fee',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 3,
                        'permission_name' => 'custom.fees.assign.create'
                    ]
                ]
            ],
            [
                'name' => 'Assignments',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 18,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'assignments.manage',
                'children' => [
                    [
                        'name' => 'Assignments',
                        'route' => '/admin/assignments',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'assignments.view'
                    ]
                ]
            ],
            [
                'name' => 'Syllabus',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 19,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'syllabus.manage',
                'children' => [
                    [
                        'name' => 'Add Syllabus',
                        'route' => '/syllabus/create',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'syllabus.create'
                    ],
                    [
                        'name' => 'View All Syllabuses',
                        'route' => '/syllabus/view',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'syllabus.view'
                    ]
                ]
            ],
            [
                'name' => 'Gallery',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 20,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'gallery.manage',
                'children' => [
                    [
                        'name' => 'Gallery',
                        'route' => '/institute/admin/gallery',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'gallery.view'
                    ]
                ]
            ],
            [
                'name' => 'Library',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 21,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'library.manage',
                'children' => [
                    [
                        'name' => 'Categories',
                        'route' => '/library/book-categories',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'library.categories.manage'
                    ],
                    [
                        'name' => 'All Books',
                        'route' => '/library',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'library.books.view'
                    ],
                    [
                        'name' => 'Add New Book',
                        'route' => '/library/add-books',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 3,
                        'permission_name' => 'library.books.create'
                    ],
                    [
                        'name' => 'Issue Books',
                        'route' => '/library/issue',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 4,
                        'permission_name' => 'library.books.issue'
                    ],
                    [
                        'name' => 'Return Books',
                        'route' => '/library/return',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 5,
                        'permission_name' => 'library.books.return'
                    ]
                ]
            ],
            [
                'name' => 'Collection',
                'icon' => 'fas fa-fw fa-coins',
                'order' => 22,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'collection.manage',
                'children' => [
                    [
                        'name' => 'Course Fees',
                        'route' => '/course/fee/collection',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'collection.course.fees'
                    ],
                    [
                        'name' => 'Registration Fees',
                        'route' => '/registration/fee/collection',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'collection.registration.fees'
                    ],
                    [
                        'name' => 'Hostel Fees',
                        'route' => '/hostel/fee/collection',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 3,
                        'permission_name' => 'collection.hostel.fees'
                    ],
                    [
                        'name' => 'Miscellaneous Fees',
                        'route' => '/miscellaneous/fee/collection',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 4,
                        'permission_name' => 'collection.miscellaneous.fees'
                    ],
                    [
                        'name' => 'Transportation Fees',
                        'route' => '/transport/fee/collection',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 5,
                        'permission_name' => 'collection.transport.fees'
                    ]
                ]
            ],
            [
                'name' => 'Expense',
                'icon' => 'fas fa-fw fa-cog',
                'order' => 212,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'expense.manage',
                'children' => [
                    [
                        'name' => 'Expense Management',
                        'route' => '/institute-admin/khata/book',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'expense.view'
                    ]
                ]
            ],
            [
                'name' => 'Inventory Management',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 24,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'inventory.manage',
                'children' => [
                    [
                        'name' => 'Inventory',
                        'route' => '/institute/admin/InventoryManagement',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'inventory.view'
                    ]
                ]
            ],
            [
                'name' => 'Transaction',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 25,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'transactions.manage',
                'children' => [
                    [
                        'name' => 'Payment Gateway',
                        'route' => '/paymentgateway/transactions',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'transactions.payment.gateway'
                    ],
                    [
                        'name' => 'Auto Debit',
                        'route' => '/paymentgateway/transactions',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'transactions.auto.debit'
                    ]
                ]
            ],
            [
                'name' => 'Visitor Management',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 211,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'visitor.manage',
                'children' => [
                    [
                        'name' => 'Visitors List',
                        'route' => '/visitors',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'visitor.list.view'
                    ],
                    [
                        'name' => 'Visitor Registration',
                        'route' => '/institute/admin/visitor/registration',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'visitor.registration'
                    ],
                    [
                        'name' => 'Visitor Check In',
                        'route' => '/institute/admin/visitor/check-in',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 3,
                        'permission_name' => 'visitor.check.in'
                    ],
                    [
                        'name' => 'Visitor Front Desk',
                        'route' => '/institute/admin/visitor/front-desk',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 4,
                        'permission_name' => 'visitor.front.desk'
                    ],
                    [
                        'name' => 'Visitor Attendant',
                        'route' => '/institute/admin/visitor/attendant',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 5,
                        'permission_name' => 'visitor.attendant'
                    ],
                    [
                        'name' => 'Generate Out Pass',
                        'route' => '/institute/admin/visitor/generate-gate-pass',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 6,
                        'permission_name' => 'visitor.gate.pass.generate'
                    ],
                    [
                        'name' => 'Visitor Check Out',
                        'route' => '/institute/admin/visitor/check-out',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 7,
                        'permission_name' => 'visitor.check.out'
                    ]
                ]
            ],
            [
                'name' => 'Payroll Management',
                'icon' => 'fas fa-fw fa-money-check-alt',
                'order' => 27,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'payroll.manage',
                'children' => [
                    [
                        'name' => 'Payroll Configuration',
                        'route' => '/institute/admin/payroll/payroll-configuration',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'payroll.configuration'
                    ],
                    [
                        'name' => 'Policy Management',
                        'route' => '/institute/admin/payroll/policy-management',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'payroll.policy.manage'
                    ],
                    [
                        'name' => 'Salary Structure',
                        'route' => '/institute/admin/payroll/salary-structure',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 3,
                        'permission_name' => 'payroll.salary.structure'
                    ],
                    [
                        'name' => 'Salary Management',
                        'route' => '/institute/admin/payroll/salary-management',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 4,
                        'permission_name' => 'payroll.salary.manage'
                    ],
                    [
                        'name' => 'Salary Slips',
                        'route' => '/institute/admin/payroll/salary-slips',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 5,
                        'permission_name' => 'payroll.salary.slips'
                    ],
                    [
                        'name' => 'Employee Salary Slips',
                        'route' => '/institute/admin/payroll/employee-salary-slips',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 6,
                        'permission_name' => 'payroll.employee.salary.slips'
                    ],
                    [
                        'name' => 'Reports & Analytics',
                        'route' => '/institute/admin/payroll/reports-analytics',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 7,
                        'permission_name' => 'payroll.reports'
                    ]
                ]
            ],
            [
                'name' => 'Exam Structure',
                'icon' => 'fas fa-fw fa-cog',
                'order' => 28,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'exam.structure.manage',
                'children' => [
                    [
                        'name' => 'Create Exam',
                        'route' => '/exam-structure-offline',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'exam.create'
                    ],
                    [
                        'name' => 'Manage Exams',
                        'route' => '/institute-admin/exam-management',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'exam.manage'
                    ],
                    [
                        'name' => 'Report Card',
                        'route' => '/institute/admin/create-report-card',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 3,
                        'permission_name' => 'report.card.manage'
                    ]
                ]
            ],
            [
                'name' => 'Working Capital Limit',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 29,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'credit.limit.manage',
                'children' => [
                    [
                        'name' => 'Introduction',
                        'route' => '/institute/admin/introCreditLimit',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'credit.limit.intro'
                    ],
                    [
                        'name' => 'Working Capital',
                        'route' => '/institute/admin/available-credit-limit',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'credit.limit.view'
                    ],
                    [
                        'name' => 'Credit Limit Transaction',
                        'route' => '/institute/admin/credit-limit-transaction',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 3,
                        'permission_name' => 'credit.limit.transaction'
                    ],
                    [
                        'name' => 'Salary Advance Request',
                        'route' => '/institute/admin/advance-salary-request',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 4,
                        'permission_name' => 'salary.advance.request'
                    ]
                ]
            ],
            [
                'name' => 'Loans',
                'icon' => 'fas fa-fw fa-users-cog',
                'order' => 30,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'loans.manage',
                'children' => [
                    [
                        'name' => 'Education Loan',
                        'route' => '/institute/admin/loan-repayment-details',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'loans.education.manage'
                    ]
                ]
            ],
            [
                'name' => 'Notice Board',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 31,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'notice.board.manage',
                'children' => [
                    [
                        'name' => 'Create Notice',
                        'route' => '/notice-board/create',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'notice.create'
                    ],
                    [
                        'name' => 'View Notices',
                        'route' => '/notice-board/view',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'notice.view'
                    ]
                ]
            ],
            [
                'name' => 'Settings',
                'route' => '/institute/admin/settings',
                'icon' => 'fas fa-fw fa-cog',
                'order' => 32,
                'allowed_roles' => ['admin', 'superadmin'],
                'permission_name' => 'settings.manage'
            ],
            [
                'name' => 'Logout',
                'route' => '/institute/admin/logout',
                'icon' => 'fas fa-sign-out-alt',
                'order' => 213,
                'allowed_roles' => ['admin', 'superadmin', 'employee', 'student'],
                'permission_name' => 'auth.logout'
            ],

            // Employee Menu Items
            [
                'name' => 'Employee Dashboard',
                'route' => '/employee/dashboard',
                'icon' => 'fas fa-fw fa-tachometer-alt',
                'order' => 101,
                'allowed_roles' => ['employee'],
                'permission_name' => 'employee.dashboard.view'
            ],
            [
                'name' => 'Profile',
                'route' => '/employee/profile',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 102,
                'allowed_roles' => ['employee'],
                'permission_name' => 'employee.profile.view'
            ],
            [
                'name' => 'Subjects Timetable',
                'route' => '/employee/schedule',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 103,
                'allowed_roles' => ['employee'],
                'permission_name' => 'employee.schedule.view'
            ],
            [
                'name' => 'Shift',
                'route' => '/employee/shift',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 104,
                'allowed_roles' => ['employee'],
                'permission_name' => 'employee.shift.view'
            ],
            [
                'name' => 'Mark Attendance',
                'route' => '/institute/admin/employee-attendance',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 105,
                'allowed_roles' => ['employee'],
                'permission_name' => 'employee.attendance.mark'
            ],
            [
                'name' => 'Attendance Report',
                'route' => '/institute/admin/my-attendance',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 106,
                'allowed_roles' => ['employee'],
                'permission_name' => 'employee.attendance.view'
            ],
            [
                'name' => 'Apply Leaves',
                'route' => '/instituteAdmin/leaves/apply',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 107,
                'allowed_roles' => ['employee'],
                'permission_name' => 'employee.leaves.apply'
            ],
            [
                'name' => 'Leave Summary',
                'route' => '/employee/leave-summary',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 108,
                'allowed_roles' => ['employee'],
                'permission_name' => 'employee.leaves.summary'
            ],
            [
                'name' => 'Mark Student Attendance',
                'route' => '/employee/mark-students-attendance',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 109,
                'allowed_roles' => ['employee'],
                'permission_name' => 'student.attendance.mark'
            ],
            [
                'name' => 'Assign Subjects',
                'route' => '/institute/admin/assign-subjects',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 110,
                'allowed_roles' => ['employee'],
                'permission_name' => 'employee.assign.subjects'
            ],
            [
                'name' => 'Assignments',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 111,
                'allowed_roles' => ['employee'],
                'permission_name' => 'employee.assignments.manage',
                'children' => [
                    [
                        'name' => 'Create Assignment',
                        'route' => '/create-assignment-view',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 1,
                        'permission_name' => 'employee.assignments.create'
                    ],
                    [
                        'name' => 'View Assignments',
                        'route' => '/assignments',
                        'icon' => 'fas fa-chart-bar',
                        'order' => 2,
                        'permission_name' => 'employee.assignments.view'
                    ]
                ]
            ],
            [
                'name' => 'Exams',
                'route' => '/employee/exam-marking',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 112,
                'allowed_roles' => ['employee'],
                'permission_name' => 'employee.exams.mark'
            ],

            // Student Menu Items
            [
                'name' => 'Student Dashboard',
                'route' => '/student/dashboard',
                'icon' => 'fas fa-fw fa-tachometer-alt',
                'order' => 201,
                'allowed_roles' => ['student'],
                'permission_name' => 'student.dashboard.view'
            ],
            [
                'name' => 'Profile',
                'route' => '/student/profile',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 202,
                'allowed_roles' => ['student'],
                'permission_name' => 'student.profile.view'
            ],
            [
                'name' => 'Course and Subjects',
                'route' => '/student/my-subjects',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 203,
                'allowed_roles' => ['student'],
                'permission_name' => 'student.subjects.view'
            ],
            [
                'name' => 'My Shifts',
                'route' => '/student/my-shift',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 204,
                'allowed_roles' => ['student'],
                'permission_name' => 'student.shifts.view'
            ],
            [
                'name' => 'Fee Structure',
                'route' => '/fee-structure-for-students',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 205,
                'allowed_roles' => ['student'],
                'permission_name' => 'student.fees.view'
            ],
            [
                'name' => 'Attendance Report',
                'route' => '/student/attendance',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 206,
                'allowed_roles' => ['student'],
                'permission_name' => 'student.attendance.view'
            ],
            [
                'name' => 'Apply Leaves',
                'route' => '/student/leave/apply',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 207,
                'allowed_roles' => ['student'],
                'permission_name' => 'student.leaves.apply'
            ],
            [
                'name' => 'Syllabus',
                'route' => '/student/syllabus',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 208,
                'allowed_roles' => ['student'],
                'permission_name' => 'student.syllabus.view'
            ],
            [
                'name' => 'Assignments',
                'route' => '/get-assignment',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 209,
                'allowed_roles' => ['student'],
                'permission_name' => 'student.assignments.view'
            ],
            [
                'name' => 'Exams',
                'route' => '/student/exam-schedule',
                'icon' => 'fas fa-fw fa-user-tie',
                'order' => 210,
                'allowed_roles' => ['student'],
                'permission_name' => 'student.exams.schedule'
            ]
        ];

        foreach ($menuItems as $item) {
            $this->createMenuItem($item);
        }
    }

    private function createMenuItem($item, $parentId = null)
    {
        $children = $item['children'] ?? [];
        unset($item['children']);

        $menuItem = MenuItem::create([
            'name' => $item['name'],
            'route' => $item['route'] ?? null,
            'icon' => $item['icon'],
            'parent_id' => $parentId,
            'order' => $item['order'],
            'is_active' => true,
            'permission_name' => $item['permission_name'],
            'allowed_roles' => $item['allowed_roles'] ?? null,
            'description' => $item['description'] ?? null
        ]);

        foreach ($children as $child) {
            $this->createMenuItem($child, $menuItem->id);
        }
    }
}