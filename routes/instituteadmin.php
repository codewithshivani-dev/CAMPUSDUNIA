<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\institute\frontend\InstituteTypeController;
use App\Http\Controllers\institute\frontend\CourseDetailsController;
use App\Http\Controllers\institute\frontend\InstituteDetailsController;
use App\Http\Controllers\institute\frontend\CourseController;
use App\Http\Controllers\institute\createaccout_controller;
use App\Http\Controllers\institute\modalloginController;
use App\Http\Controllers\Admin\getInstituteTypeDataController;
use App\Http\Controllers\Admin\getInstituteDetailsController;
use App\Http\Controllers\Admin\getCourseDataController;
use App\Http\Controllers\Admin\getCourseDetailsController;
use App\Http\Controllers\frontend\EducationController;
use App\Http\Controllers\institute\Admin\DashboardController;
use App\Http\Controllers\LoanSchemaConfigurationController;
use App\Http\Controllers\institute\Admin\FincapMerchantSubCategoriesController;
use App\Http\Controllers\institute\Admin\FincapMerchantController;
use App\Http\Controllers\institute\Admin\ProductDetailsController;
use App\Http\Controllers\institute\Admin\AdminUsersController;
use App\Http\Controllers\institute\Admin\StudentonboardController;
use App\Http\Controllers\institute\Admin\AssignSiblingController;
use App\Http\Controllers\institute\Admin\TransportDetailsController;
use App\Http\Controllers\institute\Admin\AssignTransportFeeController;
use App\Http\Controllers\institute\Admin\EmployeeDetailsController;
use App\Http\Controllers\institute\Admin\SubjectCoursewiseController;
use App\Http\Controllers\institute\Admin\AssignSubjectController;
use App\Http\Controllers\institute\Admin\SubjectAController;
use App\Http\Controllers\institute\Admin\CalendarController;
use App\Http\Controllers\institute\Admin\NotificationController;
use App\Http\Controllers\institute\Admin\GalleryController;
use App\Http\Controllers\institute\Admin\EmployeeAttendanceController;
use App\Http\Controllers\institute\Admin\MultipleAuthController;
use App\Http\Controllers\institute\Admin\GoogleCalendarController;
use App\Http\Controllers\institute\Admin\LeaveController;
use App\Http\Controllers\institute\Admin\ManualNotificationController;
use App\Http\Controllers\institute\Admin\AssignmentController;
use App\Http\Controllers\institute\Admin\StudentAssignmentController;
use App\Http\Controllers\institute\Admin\AdminAssignmentController;
use App\Http\Controllers\institute\Admin\StudentCardController;
use App\Http\Controllers\institute\Admin\ApplicantController;
use App\Http\Controllers\institute\Admin\LibraryController;
use App\Http\Controllers\institute\Admin\FeeCollectionController;
use App\Http\Controllers\institute\Admin\PaymentTransactionController;
use App\Http\Controllers\institute\Admin\PaymentLinkController;
use App\Http\Controllers\institute\Admin\ShiftController;
use App\Http\Controllers\institute\Admin\StudentLeaveController;
use App\Http\Controllers\institute\Admin\SyllabusController;
use App\Http\Controllers\institute\Admin\AddDepartmentController;
use App\Http\Controllers\institute\Admin\EmployeeProfileController;
use App\Http\Controllers\institute\Admin\MarkStudentAttendanceController;
use App\Http\Controllers\institute\Admin\StudentAttendanceViewController;
use App\Http\Controllers\institute\Admin\HolidayEventController;
use App\Http\Controllers\institute\Admin\StudentFeeController;
use App\Http\Controllers\institute\Admin\AdminStudentsController;
use App\Http\Controllers\institute\Admin\BulkStudentsUploadController;
use App\Http\Controllers\institute\Admin\BulkEmployeeUploadController;
use App\Http\Controllers\institute\Admin\AjaxfunctionscallController;
use App\Http\Controllers\institute\Admin\DesignationController;
use App\Http\Controllers\institute\Admin\EmployeeShiftController;
use App\Http\Controllers\institute\Admin\EditStudentDetailsController;
use App\Http\Controllers\institute\Admin\GetPaymentgateTransactionController;
use App\Http\Controllers\institute\Admin\AdminFeeController;
use App\Http\Controllers\institute\Admin\StudentShiftController;
use App\Http\Controllers\institute\Admin\EmployeeSubjectsController;
use App\Http\Controllers\institute\Admin\AdminController\AdminDashboardController;
use App\Http\Controllers\institute\Admin\AdminController\StudentDashboardController;
use App\Http\Controllers\institute\Admin\StudentSubjectAssignmentController;
use App\Http\Controllers\institute\Admin\StudentSubjectsController;
use App\Http\Controllers\institute\Admin\HostelFeeController;
use App\Http\Controllers\institute\Admin\AssignHostelFeeController;
use App\Http\Controllers\institute\Admin\InstituteCustomFeeController;
use App\Http\Controllers\institute\Admin\AssignCustomFeeController;
// use App\Http\Controllers\institute\Admin\InventoryController;
use App\Http\Controllers\institute\Admin\PayrollPolicy\ProvidentFundPolicyController;
use App\Http\Controllers\institute\Admin\PayrollPolicy\PayrollpolicyAllowanceController;
use App\Http\Controllers\institute\Admin\PayrollPolicy\PayrollPolicyOtherDeductionController;
use App\Http\Controllers\institute\Admin\PayrollPolicy\PayrollPolicyTaxDeductionController;
use App\Http\Controllers\institute\Admin\PayrollPolicy\EmployeeSalaryStructureController;
use App\Http\Controllers\institute\Admin\PayrollPolicy\EmployeeSalaryStructureCtcController;
use App\Http\Controllers\institute\Admin\PayrollPolicy\SalarySlipController;
use App\Http\Controllers\institute\Admin\PayrollPolicy\ManuallyPayrollExecuteController;
use App\Http\Controllers\institute\Admin\AdminController\EmployeeDashboardController;
use App\Http\Controllers\institute\Admin\Khatabook\KhataBookController;
use App\Http\Controllers\institute\Admin\Visitor\VisitorController;
use App\Http\Controllers\institute\Admin\Visitor\VisitorAttendentLogController;
use App\Http\Controllers\institute\Admin\Visitor\CheckOutController;
use App\Http\Controllers\institute\Admin\Visitor\generatePassController;

use App\Http\Controllers\institute\Admin\StudentExamScheduleController;
use App\Http\Controllers\institute\Admin\EmployeeExamMarkingController;
use App\Http\Controllers\institute\Admin\ShowExamStructureController;
use App\Http\Controllers\institute\Admin\CreateExamStructureController;
use App\Http\Controllers\institute\Admin\OtpController;
use App\Http\Controllers\institute\Admin\NoticeController;
use App\Http\Controllers\institute\Admin\EmployeeResponsibilityController;
use App\Http\Controllers\institute\Admin\GradeSystemController;
use App\Http\Controllers\institute\Admin\HomeworkController;
use App\Http\Controllers\institute\Admin\AdminController\AdminSettingsController;
use App\Http\Controllers\institute\Admin\ReportCardController;
use App\Http\Controllers\institute\Admin\ExamNameController;
use App\Http\Controllers\institute\Admin\EditCourseFeeStructureController;
use App\Http\Controllers\institute\Admin\DiscountController;
use App\Http\Controllers\institute\Admin\AssignDiscountonCourseFeeController;
use App\Http\Controllers\institute\Admin\RegistrationController;
use App\Http\Controllers\institute\Admin\LeadsController;
use App\Http\Controllers\institute\Admin\AdmissionConfiguration\AdmissionProcessConfigurationController;
use App\Http\Controllers\institute\Admin\BuildingManagementController\AddBuildingController;
use App\Http\Controllers\institute\Admin\BuildingManagementController\AddFloorController;
use App\Http\Controllers\institute\Admin\BuildingManagementController\AddRoomsController;
use App\Http\Controllers\institute\Admin\BuildingManagementController\AddBlockController;
use App\Http\Controllers\institute\Admin\AssignDutiesController;
use App\Http\Controllers\institute\Admin\EmployeeIdCardController;
use App\Http\Controllers\institute\Admin\LibraryFineRuleController;
use App\Http\Controllers\institute\Admin\LibraryCopiesController;
use App\Http\Controllers\institute\Admin\IDCardFieldController;
use App\Http\Controllers\institute\Admin\EmployeeFieldController;
use App\Http\Controllers\institute\Admin\OutPassController\OutPassController;
use App\Http\Controllers\institute\Admin\OutPassController\OutPassStudentsController;
use App\Http\Controllers\institute\Admin\OutPassController\OutPassEmployeeController;
use App\Http\Controllers\institute\Admin\OutPassController\OutPassHistoryController;
use App\Http\Controllers\institute\Admin\OutPassController\OutPassApprovalController;
use App\Http\Controllers\institute\Admin\OutPassController\OutPassVehicleController;
use App\Http\Controllers\institute\Admin\OutPassController\OutPassPassengerController;
use App\Http\Controllers\institute\Admin\OutPassController\OutPassAccompanyPersonController;
use App\Http\Controllers\institute\Admin\OutPassController\OutPassPdfGenerationController;
use App\Http\Controllers\institute\Admin\StudentNotificationController;
use App\Http\Controllers\institute\Admin\EmployeeNotificationController;
use App\Http\Controllers\institute\Admin\TransportRouteController;
use App\Http\Controllers\institute\Admin\InterviewConfigurationController;
use App\Http\Controllers\institute\Admin\CandidateController;
use App\Http\Controllers\institute\Admin\RoundStatusController;
use App\Http\Controllers\institute\Admin\ApplicantJourneyController;
use App\Http\Controllers\institute\Admin\SetRolesAmissionOrInterviewController\RolesForAdmissionInterviewController;
use App\Http\Controllers\institute\Admin\AdmissionConfiguration\PreviewController;
use App\Http\Controllers\institute\Admin\BulkFeeAssignmentController;
use App\Http\Controllers\institute\Admin\StudentPromotionController;
use App\Http\Controllers\institute\Admin\ActiveDynamicLinkController;
use App\Http\Controllers\institute\Admin\LectureReassignmentController;
use App\Http\Controllers\institute\Admin\FeeAnalyticsController;
use App\Http\Controllers\institute\Admin\LoanJourney\LoanRequestController;
use App\Http\Controllers\institute\Admin\LoanRepayment\LoanRepaymentController;
use App\Http\Controllers\institute\Admin\LoanRepayment\PayEmiController;
use App\Http\Controllers\institute\Admin\AdmissionFormController;
use App\Http\Controllers\institute\Admin\BonafideCertificateController;
use App\Http\Controllers\institute\Admin\CertificatesController;
use App\Http\Controllers\institute\Admin\CertificateViewController;
use App\Http\Controllers\institute\Admin\MultiReportCardController;
use App\Http\Controllers\institute\Admin\AttendanceReviewController;
use App\Http\Controllers\institute\Admin\PayrollPolicy\SalaryReviewController;
use App\Http\Controllers\institute\Admin\PayrollPolicy\SalarySlipViewController;
use App\Http\Controllers\institute\Admin\PayrollPolicy\PayrollExecutionController;
use App\Http\Controllers\institute\Admin\LeaveDeductionController;
use App\Http\Controllers\institute\Admin\LeaveManagementController;
use App\Http\Controllers\institute\Admin\PayrollPolicy\PayrollPolicyLogsController;
use App\Http\Controllers\institute\Admin\PayrollPolicy\SalaryStructureLogsController;
use App\Http\Controllers\institute\Admin\PayrollPolicy\EmployeePayrollDashboardController;
use App\Http\Controllers\institute\Admin\PayrollPolicy\FinalSalarySlipController;
use App\Http\Controllers\institute\Admin\TimetableController;
use App\Http\Controllers\institute\Admin\EmployeeCalendarController;
use App\Http\Controllers\institute\Admin\StudentCalendarController;
use App\Http\Controllers\institute\Admin\StudentTimetableController;
use App\Http\Controllers\institute\Admin\AdminController\EmployeeProfileSettingController;
use App\Http\Controllers\institute\Admin\AdminController\StudentProfileSettingController;
use App\Http\Controllers\institute\Admin\StudentSuspendController;

use App\Http\Controllers\institute\Admin\ProbationEmployeeController;
use App\Http\Controllers\institute\Admin\EmployeeSuspendController;
use App\Http\Controllers\institute\Admin\EmployeeUpdateController;
use App\Http\Controllers\institute\Admin\PromoteEmployee\EmployeePromotionController;

use App\Http\Controllers\institute\Frontend\BranchCampusController;
use App\Http\Controllers\institute\Frontend\BranchDetailsController;
use App\Http\Controllers\institute\Frontend\BranchOverviewController;

use App\Http\Controllers\institute\Admin\EmployeeExitPolicyController;
use App\Http\Controllers\institute\Admin\EmployeeExitController;
use App\Http\Controllers\institute\Admin\EmployeeResignController;
use App\Http\Controllers\institute\Admin\EmployeeExitApprovalController;

use App\Http\Controllers\institute\Admin\EmployeePersonalAttendanceController;

use App\Http\Controllers\institute\Admin\WFHRequestController;
use App\Http\Controllers\institute\Admin\AdminWFHRequestController;
 //Superadmin
use  App\Http\Controllers\SuperAdmin\MenuController;

//Letter
use App\Http\Controllers\institute\Admin\LetterDesignSettingController;
use App\Http\Controllers\institute\Admin\LetterController;
use App\Http\Controllers\institute\Admin\LetterTemplateController;

use App\Http\Controllers\institute\Admin\StudentExitController;

use App\Http\Controllers\institute\Admin\AdminExitRequestController;
use App\Http\Controllers\institute\Admin\StudentExitRequestController;

use App\Http\Controllers\institute\Admin\Inventory\InventoryDashboardController;
use App\Http\Controllers\institute\Admin\Inventory\InventoryConfigurationController;
use App\Http\Controllers\institute\Admin\Inventory\InventoryCategoryController;
use App\Http\Controllers\institute\Admin\Inventory\InventorySubCategoryController;
use App\Http\Controllers\institute\Admin\Inventory\InventoryWarehouseController;
use App\Http\Controllers\institute\Admin\Inventory\InventoryStoreController;
use App\Http\Controllers\institute\Admin\Inventory\InventoryItemController;
use App\Http\Controllers\institute\Admin\Inventory\InventoryStockOutController;
use App\Http\Controllers\institute\Admin\Inventory\InventoryUnitController;
use App\Http\Controllers\institute\Admin\Inventory\InventoryReceiptOutController;
use App\Http\Controllers\institute\Admin\Inventory\InventoryReceiptInController;
use App\Http\Controllers\institute\Admin\Inventory\InventoryTransferController;
use App\Http\Controllers\institute\Admin\Inventory\InventoryTransactionController;

use App\Http\Controllers\institute\Admin\Reimbursement\ReimbursementAssignmentController;
use App\Http\Controllers\institute\Admin\Reimbursement\ReimbursementPolicyController;
use App\Http\Controllers\institute\Admin\Reimbursement\ReimbursementClaimController;

use App\Http\Controllers\institute\Admin\ExitTaskManagementController;

use App\Http\Controllers\institute\Admin\IdCardTemplateController;
use App\Http\Controllers\institute\Admin\StudentIdCardController;

use App\Http\Controllers\institute\Admin\LetterVariableController;

use App\Http\Controllers\institute\Admin\LessonController;
use App\Http\Controllers\institute\Admin\LessonPlanController;

use App\Models\DepartmentCategory;
use App\Models\EmployeeDetails;
use App\Models\Departments;

use App\Http\Controllers\institute\Admin\EmployeeJourneyController;
use App\Http\Controllers\institute\Admin\EmployeeSelfJourneyController;
use App\Http\Controllers\institute\Admin\EmployeeDocumentRequestController;
use App\Http\Controllers\institute\Admin\RollNumberController;

use App\Http\Controllers\institute\Admin\CampusAmenitiesController;
use App\Http\Controllers\institute\Admin\AmenitiesController;
use App\Http\Controllers\institute\Admin\AssetCategoryController;

use App\Http\Controllers\institute\Admin\AdmitCardController;

use App\Http\Controllers\institute\Admin\AssetManagementController;

require_once __DIR__."/instituteadmin.php";
    // Employee responsibility management
    Route::prefix('employees/{employee}')->name('employees.')->group(function () {
        // Show assign responsibilities form
        Route::get('responsibilities/assign', 
            [EmployeeResponsibilityController::class, 'assignForm'])
            ->name('assign-responsibilities.form');
        
        // Store assigned responsibilities
        Route::post('responsibilities/assign', 
            [EmployeeResponsibilityController::class, 'assign'])
            ->name('assign.responsibilities.store');
        
        // Reset to default
        Route::post('responsibilities/reset', 
            [EmployeeResponsibilityController::class, 'resetToDefault'])
            ->name('reset-responsibilities');
        
        // Preview menu
        Route::post('responsibilities/preview', 
            [EmployeeResponsibilityController::class, 'getEmployeeMenu'])
            ->name('preview-menu');
        
        // Get employee responsibilities (API)
        Route::get('responsibilities', 
            [EmployeeResponsibilityController::class, 'getEmployeeResponsibilities'])
            ->name('get-responsibilities');
    });
    //Razorpay Payment Routes       
Route::post('/create-order', [PaymentTransactionController::class, 'createOrder'])
    ->name('create.order');

Route::post('/verify-payment', [PaymentTransactionController::class, 'verifyPayment'])
    ->name('verify.payment');
//Razorpay Payment Routes end 
    // Menu items API
    Route::get('menu-items', 
        [EmployeeResponsibilityController::class, 'getAllMenuItems'])
        ->name('menu-items.all');
//...............................
//...............................
// Route::get('/superadmin/dashboard', function () {
//     return "Merchant Dashboard";
// })->middleware(['auth', 'role:superadmin'])->name('superadmin.dashboard');
Route::get('/superadmin/dashboard', [DashboardController::class, 'getdataforinstitutedashboard'])
->middleware(['auth'])->name('superadmin.dashboard');

// Route::get('/admin/dashboard', function () {
//     return view('instituteAdmin/DashboardMainPagesFiles/admin');
// })->middleware(['auth'])->name('admin.dashboard');
Route::get('/admin/dashboard', [AdminDashboardController::class, 'adminDashboardIndex'])
->middleware(['auth'])->name('admin.dashboard');
// Route::get('/employee/dashboard', function () {
//     return view('instituteAdmin/DashboardMainPagesFiles/employee');
// })->middleware(['auth'])->name('employee.dashboard');
Route::get('/employee/dashboard', [EmployeeDashboardController::class, 'getemployeeDashboard'])
    ->middleware(['auth'])
    ->name('employee.dashboard');
    
Route::post('/send/otp', [OtpController::class, 'sendMobileOtp'])->name('send.otp');
Route::post('/verify/otp', [OtpController::class, 'verifyOtp'])->name('verify.otp');
// Route::get('/manager/dashboard', function () {
//     return view('instituteAdmin/DashboardMainPagesFiles/manager');
// })->middleware(['auth', 'role:manager'])->name('manager.dashboard');

// Route::get('/supervisor/dashboard', function () {
//     return view('instituteAdmin/DashboardMainPagesFiles/supervisor');
// })->middleware(['auth', 'role:employee'])->name('supervisor.dashboard');

// Route::get('/student/dashboard', function () {
//     return view('instituteAdmin/DashboardMainPagesFiles/student');
// })->middleware(['auth'])->name('student.dashboard');
Route::get('/student/dashboard', [StudentDashboardController::class, 'studentdashboard'])
    ->middleware(['auth'])
    ->name('student.dashboard');

Route::get('/institute/dashboard', [DashboardController::class, 'getdataforinstitutedashboard'])
    ->middleware(['auth'])
    ->name('dashboard');

// Route::get('/institute/admin/admin-dashboard', function () {
//         return view('instituteAdmin/DashboardMainPagesFiles/admin');
//     })->name('leaves.form');
// Route::get('/institute/admin/employee-dashboard', function () {
//         return view('instituteAdmin/DashboardMainPagesFiles/employee');
//     })->name('leaves.form');
// Route::get('/institute/admin/student-dashboard', function () {
//         return view('instituteAdmin/DashboardMainPagesFiles/student');
//     })->name('leaves.form');
// Route::get('/institute/admin/parent-dashboard', function () {
//         return view('instituteAdmin/DashboardMainPagesFiles/parent');
//     })->name('leaves.form');
Route::get('/institute/admin/view-details', [FincapMerchantController::class, 'getFincapMerchantDetails']);
// Route::get('/institute/admin/edit-Details', function () {
//         return view('instituteAdmin/EditinstituteDetails');
//      });
Route::get('/institute/admin/view-users', [AdminUsersController::class, 'getusersdata']);     
Route::get('/loan-status-data', [DashboardController::class, 'getLoanStatusDatachart']);
Route::get('/loan-data', [DashboardController::class, 'getLoanDataforchart']);
Route::get('/institute/admin/add-courses', [FincapMerchantSubCategoriesController::class, 'getallsubCategories']);
Route::post('/institute/admin/add-courses', [FincapMerchantSubCategoriesController::class, 'AddFincapMerchantSubCategories'])->name('AddInstitutecourses');
Route::get('/institute/admin/view-courses', [FincapMerchantSubCategoriesController::class, 'ViewInstitutecourses'])->name('institute-course.view');;
Route::prefix('institute/admin')->name('institute.admin.')->group(function () {
    Route::get('/course-details', [FincapMerchantSubCategoriesController::class, 'coursedetailsbehalfonID'])->name('courses.details');
    Route::post('/course-edit', [FincapMerchantSubCategoriesController::class, 'editCourse'])->name('courses.edit');
    Route::post('/course-delete', [FincapMerchantSubCategoriesController::class, 'deleteCourse'])->name('courses.delete');
});
// Route::get('/institute/admin/edit-courses/{finacp_merchant_sub_category_id}', [FincapMerchantSubCategoriesController::class, 'editInstitutecourses'])->name('institute-course.edit');
// Route::post('/institute/admin/update-courses/{finacp_merchant_sub_category_id}', [FincapMerchantSubCategoriesController::class, 'updateInstitutecourses'])->name('institute-course.update');
// Route::post('/institute/admin/delete-courses/{finacp_merchant_sub_category_id}', [FincapMerchantSubCategoriesController::class, 'deleteInstitutecourse'])->name('institute-course.delete');

Route::get('/institute/admin/addcourse-details', [ProductDetailsController::class, 'getCoursesname'])->name('course.basic.form');
Route::post('/save-course-basic-details', [ProductDetailsController::class, 'saveCourseBasicDetails'])->name('save.course.basic.details');
// Common routes

Route::get('/get-courses-by-department', [ProductDetailsController::class, 'getCoursesByDepartment'])->name('get.courses.by.department');
Route::get('/editproduct-details/{id}', [ProductDetailsController::class, 'editProductDetails'])->name('product-details.edit');
Route::post('/updateproduct-details/{id}', [ProductDetailsController::class, 'updateProductDetails'])->name('product.update');
// Route::get('/institute/admin/view-courses', [FincapMerchantSubCategoriesController::class, 'ViewInstitutecourses'])->name('institute-course.view');
// Batch-based Fee Structure Routes
Route::get('/add-course-fee-structure', [ProductDetailsController::class, 'showCourseFeeForm'])->name('course.fee.form');
Route::post('/save-course-fee-structure', [ProductDetailsController::class, 'saveCourseFeeStructure'])->name('save.course.fee.structure');
Route::get('/get-branch-details-for-fee', [ProductDetailsController::class, 'getBranchDetailsForFee'])->name('get.branch.details.for.fee');

Route::get('/fee-structure/view', [ProductDetailsController::class, 'viewFeeStructure'])->name('fee.structure.view');

//Fee Structure Download Route
Route::get('/course/fee-structure/download', [ProductDetailsController::class, 'downloadFeeStructure']);

//Course Download Route
Route::get('/download-courses', [ProductDetailsController::class, 'downloadCourses']);

// Route to display the form
Route::get('/institute/admin/edit-Details', [FincapMerchantController::class, 'showEditForm'])
    ->name('fincap.merchant.edit');

// Stamp & Signature upload routes (without prefix)
Route::post('/institute/upload-signature-stamp', [FincapMerchantController::class, 'uploadSignatureStamp'])->name('institute.upload.signature.stamp');
Route::delete('/institute/delete-signature-stamp', [FincapMerchantController::class, 'deleteSignatureStamp'])->name('institute.delete.signature.stamp');

// Route to handle form submission
Route::post('/fincap/merchant/update/{fincap_merchant_id}', [FincapMerchantController::class, 'updatemerchantdetails'])
    ->name('fincap.merchant.update');

// Transport Routes
Route::prefix('admin/transport')->name('admin.transport.')->group(function () {
    Route::get('/routes', [TransportRouteController::class, 'index'])->name('routes.index');
    Route::get('/routes/create', [TransportRouteController::class, 'create'])->name('routes.create');
    Route::post('/routes', [TransportRouteController::class, 'store'])->name('routes.store');
    Route::get('/routes/{id}', [TransportRouteController::class, 'show'])->name('routes.show');
    Route::get('/routes/{id}/edit', [TransportRouteController::class, 'edit'])->name('routes.edit');
    Route::put('/routes/{id}', [TransportRouteController::class, 'update'])->name('routes.update');
    Route::delete('/routes/{id}', [TransportRouteController::class, 'destroy'])->name('routes.destroy');
    Route::get('/get-routes', [TransportRouteController::class, 'getRoutesForDropdown'])->name('get.routes');
    Route::get('/get-route-details/{id}', [TransportRouteController::class, 'getRouteDetails'])->name('get.route.details');
});

// Transport Routes
Route::prefix('institute/admin')->group(function () {
    Route::get('add-transport', [TransportDetailsController::class, 'create'])->name('admin.transport.add');
    Route::post('store-transport', [TransportDetailsController::class, 'store'])->name('admin.transport.store');
    Route::get('all-transport-details', [TransportDetailsController::class, 'index'])->name('view.transport.details');
    Route::get('/transport/{id}/details', [TransportDetailsController::class, 'showDetails'])->name('admin.transport.details');
    Route::get('/transport/{id}/fee-details', [TransportDetailsController::class, 'getFeeDetails'])->name('admin.transport.fee.details');
    Route::get('transport-fees', [TransportDetailsController::class, 'feeIndex'])->name('admin.transport.fees.index');
    Route::get('/transport/{id}/stops', [TransportDetailsController::class, 'getBusStops'])->name('admin.transport.stops');
});

//Transport Download Routes
Route::get('/transport-data/download', [TransportDetailsController::class, 'downloadTransportData']);

Route::get('/transport/assign-fee', [AssignTransportFeeController::class, 'assignFeeForm'])->name('admin.transport.assign-fee.form');
Route::post('/transport/assign-fee/store', [AssignTransportFeeController::class, 'storeAssignedFee'])->name('admin.transport.assign-fee.store');
Route::get('/ajax/transport/stops', [AssignTransportFeeController::class, 'getTransportStops'])->name('ajax.transport.stops');
Route::get('/ajax/student-academic-year', [AssignTransportFeeController::class, 'getStudentAcademicYear'])
    ->name('ajax.student.academic-year');
Route::get('/ajax/employee/academic-year-fortraport', [AssignTransportFeeController::class, 'getAcademicYearFromTransportFees'])->name('ajax.employee.academic-year.transport');
Route::get('admin/transport/get-buses-by-route', [AssignTransportFeeController::class, 'getBusesByRoute'])->name('ajax.transport.buses-by-route');
Route::get('admin/transport/get-bus-details', [AssignTransportFeeController::class, 'getBusDetails'])->name('ajax.transport.bus-details');

Route::prefix('/institute/admin/custom-fees')->name('admin.custom-fees.')->group(function () {
    Route::get('/', [InstituteCustomFeeController::class, 'index'])->name('index');
    Route::get('/create', [InstituteCustomFeeController::class, 'create'])->name('create');
    Route::post('/', [InstituteCustomFeeController::class, 'store'])->name('store');
    Route::get('/{id}', [InstituteCustomFeeController::class, 'show'])->name('show');
});
Route::get('/assign/custom-fee', [AssignCustomFeeController::class, 'assignFeeForm'])->name('admin.custom-fees.assign.form');
Route::post('/assign/store', [AssignCustomFeeController::class, 'storeAssignedFee'])->name('admin.custom-fees.assign.store');
Route::get('/ajax/employee/academic-year', [AssignCustomFeeController::class, 'getAcademicYearForEmployee'])->name('ajax.employee.academic-year.custom');
Route::get('/custom-fees/available-discounts', [AssignCustomFeeController::class, 'getAvailableDiscounts'])
    ->name('ajax.custom.available.discounts');

Route::prefix('institute-admin')->group(function () {
    // Hostel Fee Management
    Route::get('hostel-fees', [HostelFeeController::class, 'index'])->name('hostel.fees.index');
    Route::get('hostel-fees/create', [HostelFeeController::class, 'create'])->name('hostel.fees.create');
    Route::post('hostel-fees', [HostelFeeController::class, 'store'])->name('hostel.fees.store');
    Route::get('ajax/hostel-fees/data', [HostelFeeController::class, 'getHostelFeesData'])->name('ajax.hostel.fees.data');
});

// Download Hostel Fee
Route::get('/hostel-fees/download',[HostelFeeController::class, 'downloadHostelFeeData'])->name('hostel.fees.download');

// Hostel Fee Assignment Routes
Route::get('institute-admin/assign/hostel-fees', [AssignHostelFeeController::class, 'assignFeeForm'])->name('admin.hostel-fees.assign.form');
Route::post('/hostel-fees/assign/store', [AssignHostelFeeController::class, 'storeAssignedFee'])->name('admin.hostel-fees.assign.store');
Route::get('/ajax/hostel/details', [AssignHostelFeeController::class, 'getHostelDetails'])->name('ajax.hostel.details');
Route::get('/ajax/employee/academic-year/hostel', [AssignHostelFeeController::class, 'getEmployeeAcademicYear'])->name('ajax.employee.academic-year.hostel');
Route::get('/ajax/hostel/available-discounts', [AssignHostelFeeController::class, 'getAvailableDiscounts'])
->name('ajax.hostel.available.discounts');

Route::get('/institute/admin/add-employee-type', function () {
    return view('instituteAdmin/EmployeeFiles/AddEmployeeType');
});
Route::get('/institute/admin/add-employee-type', function () {
    return view('instituteAdmin/EmployeeFiles/AddEmployeeType');
});
// Route::get('/institute/admin/employee-profile', function () {
//     return view('instituteAdmin/EmployeeFiles/profile');
// });

Route::get('/institute/admin/add-students1', [StudentonboardController::class, 'getTransportDetails'])->name('students.create');
Route::get('/institute/admin/{student_hash_id}/edit', [StudentonboardController::class, 'editstudent'])->name('students.onboard.edit');
Route::put('/institute/admin/{student_hash_id}', [StudentonboardController::class, 'updatestudent'])->name('students.update');
Route::post('/save-student-onboarding-details', [StudentonboardController::class, 'handleStudentFormSubmission']);
// routes/web.php

Route::get('/institute/admin/students', [StudentonboardController::class, 'allStudentsData'])->name('students.index');
Route::get('/get-academic-details/{productId}', [StudentonboardController::class, 'getAcademicDetails']);
Route::get('/student-details/{hash_id}', [StudentonboardController::class, 'getStudentDetails']);

// Add this route inside your institute admin group
Route::get('/institute/admin/get-sections/{productId}', [StudentonboardController::class, 'getSections'])->name('admin.get.sections');

Route::post('/student/{student_hash_id}/exit', [StudentonboardController::class, 'exitStudent'])->name('student.exit');

//Download Students
Route::get('/students/bulk-download',[StudentonboardController::class, 'downloadStudentsData'])->name('student.bulk.download');
    
Route::get('/institute/admin/viewcourse-details', [ProductDetailsController::class, 'getcoursedetails'])->name('courses.index');
Route::get('/course-details/{id}', [ProductDetailsController::class, 'coursedetailsbehalfonID'])->name('courses.show');
Route::get('/get-departments-by-category/{categoryId}', [EmployeeDetailsController::class, 'getDepartmentsByCategory']);
Route::post('/employees', [EmployeeDetailsController::class, 'storeemployeedetails'])->name('employees.store');
// Route::put('/employees/{id}', [EmployeeDetailsController::class, 'updateEmployeeDetails'])->name('employees.update');
Route::get('/institute/admin/addemployees', [EmployeeDetailsController::class, 'getemployee'])->name('employees.index');
Route::get('/employee-details/{id}', [EmployeeDetailsController::class, 'getemployeeDetailsbyID']);
//latest employee routes
Route::get('/institute/admin/addemployeesdetails', [EmployeeDetailsController::class, 'AddemployeeDetails'])->name('addemployees.details');

Route::get('/employees/bulk-download',[EmployeeDetailsController::class, 'downloadEmployees'])->name('employees.bulk.download');

// Employee Card
Route::get('/employee/{employee}/card', [EmployeeIdCardController::class, 'employeeIdCardView'])->name('employee.card.show');
Route::get('/employee/{employee}/card/pdf', [EmployeeIdCardController::class, 'employeeIdCardDownloadPdf'])->name('employee.card.pdf');

// routes/employee.php
Route::prefix('employee')->name('employee.')->group(function () {
    Route::get('/shift', [EmployeeShiftController::class, 'showemployeeshift'])->name('shift.dashboard');
    Route::get('/shift-history', [EmployeeShiftController::class, 'getShiftHistory'])->name('employee.shift.history');
});

//Download Shift
Route::get('/course/fee-structure/download', [ProductDetailsController::class, 'downloadFeeStructure']);
Route::get('/shift/download', [ShiftController::class, 'downloadShifts']);
//Download Shift Schedule
Route::get('/shift-schedule/download', [ShiftController::class, 'downloadShiftSchedule'])->name('shift.schedule.download');

// Route::get('/get-courses/{departmentId}', [SubjectCoursewiseController::class, 'getCoursesByDepartment']);
// Route::post('/get-subtypes-by-course', [SubjectCoursewiseController::class, 'getSubTypesByCourse']);
Route::get('/institute/admin/subject-coursewise', [SubjectCoursewiseController::class, 'index'])->name('subject-coursewise.index');
Route::post('/institute/admin/subject-coursewise', [SubjectCoursewiseController::class, 'store'])->name('subject-coursewise.store');
// Route::get('/get-semesters/{subTypeId}', [SubjectCoursewiseController::class, 'getSemesters']);
// Subject Coursewise routes
Route::get('/subject-coursewisebyid/{id}', [SubjectCoursewiseController::class, 'show'])->name('subject-coursewisebyid.show');
Route::get('/subject-coursewise/{subjectId}/sub-subjects', [SubjectCoursewiseController::class, 'getSubSubjects'])->name('subject-coursewise.sub-subjects');

// Download Subject Coursewise routes
Route::get('/download-subjects-coursewise', [SubjectCoursewiseController::class, 'downloadSubjectsCoursewise']);

Route::get('/institute/admin/add-new-subject',[SubjectCoursewiseController::class, 'AddNewSubject'])->name('subject.add');
Route::get('/institute/admin/assign-subjects-employee', [AssignSubjectController::class,'EmployeeAssignsubjectForm'])->name('assign-subjects.toemployee');
Route::get('/institute/admin/student-subject-assignments-form', [StudentSubjectAssignmentController::class, 'showassignsubjectStudentForm'])->name('student-subject-assignments.form');

Route::get('/institute/admin/assign-subjects', [AssignSubjectController::class,'getsubjectsdata'])->name('assign-subjects.index');
Route::get('/assign-subjects/{id}', [AssignSubjectController::class,'showassignedsubjectsbyid']);
// Student Subject Assignment Routes
Route::get('/student-subject-assignments', [StudentSubjectAssignmentController::class, 'index'])->name('student-subject-assignments.index');
Route::post('/student-subject-assignments', [StudentSubjectAssignmentController::class, 'store'])->name('student-subject-assignments.store');
Route::get('/student-subject-assignments/{studentId}', [StudentSubjectAssignmentController::class, 'show'])->name('student-subject-assignments.show');
//Student-Subjects Download Route
Route::get('/download/student-subjects', [StudentSubjectAssignmentController::class, 'downloadSubjects']);

Route::get('/student/my-subjects', [StudentSubjectsController::class, 'studentSubjectsView'])->name('student.my-subjects');
// Route::get('/student/my-subjects-data', [StudentSubjectsController::class, 'getStudentSubjectsData'])
//     ->name('student.my-subjects.data');
Route::get('/student/my-subjects-data', [StudentSubjectsController::class, 'getStudentSubjectsWithSyllabus'])
    ->name('student.my-subjects.data');

Route::get('/get-subjectsforassignment/{productId}', [AssignSubjectController::class,'getSubjectsforassignment']);
Route::get('/get-employees-by-department/{id}', [AssignSubjectController::class, 'getEmployeesByDepartment']);
Route::get('/get-course-types-by-department/{id}', [AssignSubjectController::class, 'getCourseTypesByDepartment']);
Route::post('/get-subtypesforassignment-sub', [AssignSubjectController::class, 'getSubTypesForAssignment']);
Route::get('/get-sections-by-product/{productId}', [AssignSubjectController::class, 'getSectionsByProduct']);

Route::post('/assignments', [AssignSubjectController::class, 'storeassignsubject'])->name('assign-subjects.store');
Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
Route::get('/lectures/events', [AssignSubjectController::class, 'getLectures'])->name('lectures.events');
Route::get('/get-semesters-by-subtype/{productId}', [AssignSubjectController::class, 'getSemestersBySubType']);
Route::post('/get-subjectsforassignment', [AssignSubjectController::class, 'getSubjectsForAssignment']);
//Employee-Subjects Download Route
Route::get('/employee-subjects/download',[AssignSubjectController::class, 'downloadEmployeeSubjects']);

Route::get('/add-department-categories', function () {
        return view('instituteAdmin/DashboardFiles/AddDepartmentsCategories');
     });
Route::get('/departments', [AddDepartmentController::class, 'showdepartmentpage'])->name('departments.page');
Route::post('/department-categories', [AddDepartmentController::class, 'AddDepartmentCategory'])->name('department-categories.store');
Route::post('/departments', [AddDepartmentController::class, 'AddDepartments'])->name('departments.store');
Route::get('/category/{categoryId}', [AddDepartmentController::class, 'getCategoryDetails'])->name('category.details');
Route::get('/departments/view-all', [AddDepartmentController::class, 'viewAllCategoriesAndDepartments'])->name('departments.view-all');
// EDIT/DELETE Department Routes
Route::post('/departments/edit', [AddDepartmentController::class, 'editDepartment'])->name('departments.edit');
Route::post('/departments/delete', [AddDepartmentController::class, 'deleteDepartment'])->name('departments.delete');

Route::get('/get-subtypes/{courseType}', [EmployeeDetailsController::class, 'getSubTypes']);
// Show all notifications
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/get', [NotificationController::class, 'getNotifications'])->name('notifications.get');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications/delete/all', [NotificationController::class, 'destroyAll'])->name('notifications.delete-all');
    Route::post('/notifications/settings', [NotificationController::class, 'saveSettings'])->name('notifications.settings');
    Route::get('/notifications/settings', [NotificationController::class, 'getSettings'])->name('notifications.get-settings');
});

Route::prefix('/institute/admin')->group(function(){
    Route::get('gallery', [GalleryController::class, 'index'])->name('gallery.index');
    Route::post('gallery/upload', [GalleryController::class, 'store'])->name('gallery.store');
    Route::delete('gallery/{id}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
    Route::post('gallery/sort', [GalleryController::class,'sort'])->name('gallery.sort');
    Route::post('gallery/bulk-delete', [GalleryController::class,'bulkDelete'])->name('gallery.bulkDelete');
    Route::get('gallery/folder/images', [GalleryController::class, 'getFolderImages'])->name('gallery.folder.images');
});

Route::post('/create-multiple-user', [MultipleAuthController::class, 'createMultipleregister'])->name('users.multipleregister');
Route::get('/institute/admin/google/calendar', [GoogleCalendarController::class, 'googleCalendarEvents']);
Route::get('/institute/admin/employee-attendance', [EmployeeAttendanceController::class, 'showAttendance'])->name('attendance.show');
Route::post('/attendance/check-in', [EmployeeAttendanceController::class, 'checkIn'])->name('attendance.checkin');
Route::post('/attendance/check-out', [EmployeeAttendanceController::class, 'checkOut'])->name('attendance.checkout');
Route::get('/institute/admin/monthly-attendance', [EmployeeAttendanceController::class, 'monthlyAttendance'])->name('attendance.monthly');
//Route::get('/institute/admin/my-attendance', [EmployeeAttendanceController::class, 'myAttendance'])->name('employee.myAttendance');
// Employee's own attendance view (monthly calendar)
Route::get('/institute/admin/my-attendance', [EmployeePersonalAttendanceController::class, 'myAttendance'])
        ->name('employee.myAttendance');
Route::get('/attendance/details/{employeeId}/{date}', [EmployeeAttendanceController::class, 'getAttendanceDetails'])->name('attendance.details');

//Download Attendance
Route::get('/attendance/download', [EmployeeAttendanceController::class, 'downloadMonthlyAttendance'])
->name('attendance.download');

// Route::get('institute/admin/applyleaves', [LeaveController::class, 'showApplyLeaveForm'])->name('leaves.form');
// Route::post('/leave/apply', [LeaveController::class, 'applyLeave'])->name('leaves.apply');
// Approval list + filters
// Route::get('/leave/approvals', [LeaveController::class, 'getLeaveApproval'])->name('leaves.approvals');
Route::prefix('leaves')->group(function () {
    Route::get('/approvals', [LeaveController::class, 'getLeaveApproval'])
        ->name('leaves.approvals');
    Route::put('/approval/{id}', [LeaveController::class, 'updateLeaveApproval'])
        ->name('leaves.approval.update');
});
// Approve / Reject
// Route::put('/approve/{id}', [LeaveController::class, 'updateLeaveApproval'])->name('leaves.approve');
// Route::get('/leave/assign', [LeaveController::class, 'assignLeaveForm'])->name('leaves.assign.form');
// Route::post('/leave/assign', [LeaveController::class, 'assignLeaveStore'])->name('leaves.assign.store');
// Route::get('/employee/leave-summary', [LeaveController::class, 'viewEmployeeLeaveSummary'])
//     ->name('employee.leave.summary');
Route::get('/employee/leave-summary', [LeaveController::class, 'viewEmployeeLeaveSummary'])
    ->name('employee.leave.summary');
Route::prefix('instituteAdmin')->group(function() {
    Route::get('/leaves/apply', [LeaveController::class, 'showApplyLeaveForm'])->name('leaves.apply.form');
    Route::post('/leaves/apply', [LeaveController::class, 'applyLeave'])->name('leaves.apply');
    Route::get('/leaves/policies', [LeaveController::class, 'managePolicies'])->name('leaves.policies');
    Route::post('/leaves/policies/save', [LeaveController::class, 'savePolicy'])->name('leaves.policies.save');
    Route::delete('/leaves/policies/{id}', [LeaveController::class, 'deletePolicy'])->name('leaves.policies.delete');
    Route::get('/leaves/assign', [LeaveController::class, 'assignLeaveForm'])->name('leaves.assign.form');
    Route::post('/leaves/assign', [LeaveController::class, 'assignLeaveStore'])->name('leaves.assign.store');
});

// Policy assignment routes
Route::prefix('instituteAdmin')->group(function() {
    Route::get('/policies/assign', [PolicyAssignmentController::class, 'assignPolicyForm'])->name('policies.assign.form');
    Route::post('/policies/assign', [PolicyAssignmentController::class, 'assignPolicy'])->name('policies.assign');
    Route::get('/policies/assignments', [PolicyAssignmentController::class, 'viewAssignments'])->name('policies.assignments.view');
    Route::delete('/policies/assignments/{id}/deactivate', [PolicyAssignmentController::class, 'deactivateAssignment'])->name('policies.assignments.deactivate');
});

Route::get('/notifications/create', [ManualNotificationController::class, 'create'])->name('notifications.create');
Route::post('/notifications', [ManualNotificationController::class, 'store'])->name('notifications.store');
Route::get('/notifications/inbox', [ManualNotificationController::class, 'inbox'])->name('notifications.inbox');
Route::get('/notifications/{id}/read', [ManualNotificationController::class, 'markAsRead'])->name('notifications.read');

// Assignment Routes
Route::get('/assignments/{assignment}/students', [AssignmentController::class, 'getAssignmentStudents'])->name('assignments.students');
Route::get('/assignments/{assignment}/files', [AssignmentController::class, 'getAssignmentFiles'])->name('assignments.files');
Route::get('/assignments/{assignment}/details', [AssignmentController::class, 'getAssignmentDetails'])->name('assignments.details');
Route::get('/assignments/{assignment}/assign-students', [AssignmentController::class, 'assignStudentsForm'])->name('assignments.assignStudentsForm');
Route::post('/assignments/{assignment}/assign-students', [AssignmentController::class, 'assignStudents'])->name('assignments.assignStudents');
Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
Route::get('/create-assignment-view', [AssignmentController::class, 'create'])->name('assignments.create');
Route::post('/add-assigments', [AssignmentController::class, 'store'])->name('assignments.store');
Route::get('/assignments/{assignment}/submissions', [AssignmentController::class, 'viewSubmissions'])->name('assignments.view.submissions');
Route::get('/assignments/student/{assignmentStudent}/submission', [AssignmentController::class, 'viewStudentSubmission'])->name('assignments.view.student.submission');
Route::post('/assignments/{assignmentStudent}/grade', [AssignmentController::class, 'gradeSubmission'])->name('assignments.grade.submission');
Route::put('/assignments/{assignmentStudent}/grade', [AssignmentController::class, 'gradeSubmission']); // For updating grades
Route::get('/assignments/submission/{submission}/download', [AssignmentController::class, 'downloadSubmissionFile'])->name('assignments.download.submission');
// Student side
Route::get('/get-assignment', [StudentAssignmentController::class, 'index'])->name('student.assignments.index');
Route::get('/{assignmentStudents}/submit', [StudentAssignmentController::class, 'submitForm'])->name('student.assignments.submit');
Route::post('/{assignmentStudents}/upload', [StudentAssignmentController::class, 'upload'])->name('student.assignments.upload');
Route::get('/student/assignments/{assignment}/details', [StudentAssignmentController::class, 'getAssignmentDetailsforstudents'])->name('assignments.details.students');
Route::get('/student/assignments/{assignmentStudent}/grade-details', [StudentAssignmentController::class, 'getGradeDetails'])->name('student.assignments.grade.details');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Assignment management
    Route::get('/assignments', [AdminAssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/statistics', [AdminAssignmentController::class, 'statistics'])->name('assignments.statistics');
    Route::get('/assignments/{assignment}', [AdminAssignmentController::class, 'show'])->name('assignments.show');
    Route::get('/assignments/{assignment}/submissions', [AdminAssignmentController::class, 'viewSubmissions'])->name('assignments.submissions');
    Route::get('/assignments/{assignment}/download/{file}', [AdminAssignmentController::class, 'downloadFile'])->name('assignments.download.file');
    Route::delete('/assignments/{assignment}', [AdminAssignmentController::class, 'destroy'])->name('assignments.destroy');
    // Teacher-specific assignments
    Route::get('/assignments/teacher/{employee}', [AdminAssignmentController::class, 'byTeacher'])->name('assignments.by.teacher');
});

// Student Card create urls
Route::get('/students/{student}/card', [StudentCardController::class, 'show'])->name('students.card.show');
Route::get('/students/{student}/card/pdf', [StudentCardController::class, 'downloadPdf'])->name('students.card.pdf');

Route::get('/applicants-details', [ApplicantController::class, 'applicantdetails'])->name('applicants.index');
Route::get('/applicant-details/{id}', [ApplicantController::class, 'applicantdetailsbyID']);
Route::get('/applicant-fee-details', [ApplicantController::class, 'applicantFeedetails'])
    ->name('applicantFeeDetails.index');
Route::get('/applicant-fee-details/{id}', [ApplicantController::class, 'applicantFeedetailsbyID'])
    ->name('applicantFeeDetails.show');
Route::get('/AllFee-Collections', [FeeCollectionController::class, 'getFeeCollectionData'])->name('library.index');  
Route::get('/paymentgateway/transactions', [GetPaymentgateTransactionController::class, 'getPaymentgatewayFeeTransaction'])->name('get.paymentgateway.transaction');
Route::get('/student/transactions', [GetPaymentgateTransactionController::class, 'getPaymentgatewayFeeTransactionUserWise'])->name('get.paymentgateway.transaction.user.wise');
Route::get('/course/fee/collection', [AdminFeeController::class, 'getCurseFeeStructure'])->name('get.course.fee');
Route::get('/hostel/fee/collection', [AdminFeeController::class, 'getHostelFeeStructure'])->name('get.hostel.fee');
Route::get('/transport/fee/collection', [AdminFeeController::class, 'getTransportFeeStructure'])->name('get.transport.fee');
Route::get('/registration/fee/collection', [AdminFeeController::class, 'getRegistrationFeeStructure'])->name('get.registration.fee');
Route::get('/miscellaneous/fee/collection', [AdminFeeController::class, 'getMiscellaneousFeeStructure'])->name('get.miscellaneous.fee');
Route::get('/custom/fee/collection', [AdminFeeController::class, 'getCustomFeeStructure'])->name('get.custom.fee');
Route::post('/admin/payments/confirm', [AdminFeeController::class, 'manualPaymentConfirmationFee'])->middleware(['auth'])->name('admin.payments.confirm');

Route::get('/student-installments/{student_hash}', [AdminFeeController::class, 'studentCourseInstallments'])->name('student.installments');

Route::get('/admin/custom-fee-installments/{student_hash}', [AdminFeeController::class, 'studentCustomFeeInstallments'])->name('admin.custom.fee.installments');
Route::get('/admin/transport-fee-installments/{student_hash}', [AdminFeeController::class, 'studentTransportFeeInstallments'])->name('admin.transport.fee.installments');

Route::get('admin/fee-analytics', [FeeAnalyticsController::class, 'feeAnalytics'])->name('admin.fee.analytics');

Route::get('/institute/admin/available-credit-limit', function () {
    return view('instituteAdmin/CreditLimit/AvailableCreditLimit');
});    

//assign shifts to employees
// Library url
Route::get('/library/add-books', [LibraryController::class, 'createBooks'])->name('library.create.books');
Route::post('/library/store-books', [LibraryController::class, 'storeBooks'])->name('library.store.books');
Route::get('/library', [LibraryController::class, 'librarydata'])->name('library.data');

// Issue Book
Route::get('/library/issue', [LibraryController::class, 'createIssue'])->name('library.issue.create');
Route::post('/library/issue', [LibraryController::class, 'storeIssue'])->name('library.issue.store');

// Books Categiries
Route::get('/library/book-categories', [LibraryController::class, 'getBookCategory'])->name('library.book.category');
Route::get('/library/add-category', [LibraryController::class, 'createBookCategory'])->name('library.category.create');
Route::post('/library/store-category', [LibraryController::class, 'storeBookCategory'])->name('library.category.store');
Route::get('/library/categories/{book_categories_id}/edit', [LibraryController::class, 'editBookCategory'])->name('library.category.edit');
Route::put('/library/categories/{book_categories_id}', [LibraryController::class, 'updateBookCategory'])->name('library.category.update');

// Return Book
Route::get('/library/return', [LibraryController::class, 'createReturn'])->name('library.return.create');
Route::post('/library/return', [LibraryController::class, 'storeReturn'])->name('library.return.store');
Route::get('/library/fine-list', [LibraryController::class, 'getFineList'])->name('library.get.list');
Route::post('/payment/capture', [PaymentTransactionController::class, 'capturePayment'])->name('payment.capture');

//Get book
Route::get('/library/books/{bookId}/copies', [LibraryController::class, 'getBookCopies'])
    ->name('library.book.copies');

Route::get('/library/books/{bookId}/details', [LibraryController::class, 'getBookDetails'])
    ->name('library.book.details');
Route::get('/library/books/{bookId}/available-copies', [LibraryController::class, 'getAvailableCopies'])
    ->name('library.books.available-copies');    
Route::get('/library/books/{bookId}/copies', [LibraryCopiesController::class, 'showCopies'])
    ->name('library.books.copies');
Route::get('/library/copies/{id}/edit', [LibraryCopiesController::class, 'editCopy'])
    ->name('library.copies.edit');
Route::put('/library/copies/{id}', [LibraryCopiesController::class, 'updateCopy'])
    ->name('library.copies.update');
Route::get('/library/books/{bookId}/add-copy', [LibraryCopiesController::class, 'createCopy'])
    ->name('library.books.add-copy');
Route::post('/library/books/{bookId}/store-copy', [LibraryCopiesController::class, 'storeCopy'])
    ->name('library.books.store-copy');
    
Route::prefix('library/fine-rules')->name('library.fine-rules.')->group(function () {
    Route::get('/', [LibraryFineRuleController::class, 'index'])->name('index');
    Route::get('/create', [LibraryFineRuleController::class, 'create'])->name('create');
    Route::post('/', [LibraryFineRuleController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [LibraryFineRuleController::class, 'edit'])->name('edit');
    Route::put('/{id}', [LibraryFineRuleController::class, 'update'])->name('update');
    Route::post('/{id}/toggle-status', [LibraryFineRuleController::class, 'toggleStatus'])->name('toggle-status');
    Route::delete('/{id}', [LibraryFineRuleController::class, 'destroy'])->name('destroy');
});

Route::post('/library/get-copy-details', [LibraryController::class, 'getCopyDetails'])->name('library.get.copy.details');
Route::post('/library/get-fine-amount', [LibraryFineRuleController::class, 'getFineAmount'])
    ->name('library.get.fine.amount');
Route::get('/library/my-issued-books', [LibraryController::class, 'myIssuedBooks'])->name('library.my.issued');

Route::post('/payment/create-link', [PaymentLinkController::class, 'createPaymentLink'])->name('payment.link.create');
Route::post('/payment/course-link', [PaymentLinkController::class, 'createCoursePaymentLink'])->name('payment.Courselink.create');
Route::get('/payment/callback', [PaymentLinkController::class, 'paymentCallback'])->name('payment.link.callback');
Route::get('/course-payment/callback', [PaymentLinkController::class, 'paymentCourseCallback'])->name('payment.courselink.callback');
// Student leave applies
Route::get('/student/leave/apply', function() {return view('instituteAdmin.StudentLeave.ApplyLeave');})->name('student.leave.form');
Route::post('/student/leave/apply', [StudentLeaveController::class, 'applyLeave'])->name('student.leave.apply');
// Admin/teacher view and manage
Route::get('/student/leave-requests', [StudentLeaveController::class, 'viewLeaveRequests'])
    ->name('student.leave.view');
Route::put('/student/leave/{id}/status', [StudentLeaveController::class, 'updateStatus'])
    ->name('student.leave.status');

Route::prefix('syllabus')->group(function () {
    Route::get('/create', [SyllabusController::class, 'create'])->name('instituteAdmin.syllabus.create');
    Route::post('/store', [SyllabusController::class, 'store'])->name('instituteAdmin.syllabus.store');
    Route::get('/view', [SyllabusController::class, 'viewAllsyllabus'])->name('instituteAdmin.syllabus.viewAll');
    Route::get('/detail/{subjectId}', [SyllabusController::class, 'show'])->name('instituteAdmin.syllabus.detail');
    Route::get('/edit/{subjectId}', [SyllabusController::class, 'edit'])->name('instituteAdmin.syllabus.edit');
    Route::post('/update/{subjectId}', [SyllabusController::class, 'update'])->name('instituteAdmin.syllabus.update');
    Route::get('/download/{id}', [SyllabusController::class, 'download'])->name('instituteAdmin.syllabus.download');
    Route::delete('/delete/{id}', [SyllabusController::class, 'destroy'])->name('instituteAdmin.syllabus.destroy');
    Route::delete('/topic/{id}', [SyllabusController::class, 'destroyTopic'])->name('instituteAdmin.syllabus.topic.destroy');
});
Route::prefix('instituteAdmin/syllabus')->group(function () {
    Route::get('/get-subjects/{productId}', [SyllabusController::class, 'getSubjects']);
    Route::get('/get-subject-status/{subjectId}', [SyllabusController::class, 'getSubjectSyllabusStatus']);
    Route::get('/list-json', [SyllabusController::class, 'listJson']);
    Route::get('/files-by-subject/{subjectId}', [SyllabusController::class, 'filesBySubject']);
});
Route::post('/syllabus/update-ajax/{subjectId}', [SyllabusController::class, 'updateAjax'])->name('instituteAdmin.syllabus.updateAjax');

// API: subject details for lesson planner (syllabus + assigned employees + shifts)
Route::get('/instituteAdmin/subject/{subjectId}/details', [LessonController::class, 'subjectDetails']);
Route::get('/student/syllabus', [SyllabusController::class, 'studentView'])->name('student.syllabus.view');

// Download syllabus
Route::get('/syllabus/download', [SyllabusController::class, 'downloadAllSyllabus'])->name('syllabus.download');

Route::prefix('instituteAdmin/syllabus')->group(function () {
    Route::get('/get-subjects/{productId}', [SyllabusController::class, 'getSubjects']);
});
Route::get('/student/syllabus', [SyllabusController::class, 'studentView'])->name('student.syllabus.view');

// Download syllabus
Route::get('/syllabus/download', [SyllabusController::class, 'downloadAllSyllabus'])->name('syllabus.download');

Route::get('/institute/admin/available-credit-limit', function () {
    return view('instituteAdmin/CreditLimit/AvailableCreditLimit');
});
Route::get('/institute/admin/loan-agreement', function () {
    return view('instituteAdmin/CreditLimit/loanAgreement');
});
Route::get('/institute/admin/credit-limit-transaction', function () {
    return view('instituteAdmin/CreditLimit/CreditLimitTransaction');
});
Route::get('/institute/admin/emi', function () {
    return view('instituteAdmin/CreditLimit/Emi');
});
Route::get('/institute/admin/loan-agreement-two', function () {
    return view('instituteAdmin/CreditLimit/loanAgreementTwo');
});
Route::get('/institute/admin/create-report-card', function () {
    return view('instituteAdmin/CreditLimit/reportCardSystem');
});
Route::get('/institute/admin/beneficiary', function () {
    return view('instituteAdmin/CreditLimit/beneficiary');
});
Route::get('/institute/admin/payLater', function () {
    return view('instituteAdmin/CreditLimit/payLater');
});
Route::get('/institute/admin/introCreditLimit', function () {
    return view('instituteAdmin/CreditLimit/CreditLimitApplication');
});
Route::get('/institute/admin/signAgreement', function () {
    return view('instituteAdmin/CreditLimit/signAgreement');
});
// Route::get('/institute/admin/khataBook', function () {
//     return view('instituteAdmin/CreditLimit/khataBook');
// });
Route::get('/institute/admin/employee-salary-advance', function () {
    return view('instituteAdmin/EmployeeFiles/EmployeeSalaryAdvance');
});
Route::get('/institute/admin/advance-salary-request', function () {
    return view('instituteAdmin/CreditLimit/SalaryAdvanceRequest');
});
// Route::get('/institute/admin/loan-repayment-details', function () {
//     return view('instituteAdmin/LoanFiles/LoanRepaymentStructure');
// });
Route::get('/employee/profile', [EmployeeProfileController::class, 'myProfile'])->name('employee.profile');
Route::put('/employee/profile/update', [EmployeeProfileController::class, 'updateProfile'])->name('employee.updateProfile');

Route::get('/employee/mark-students-attendance', [MarkStudentAttendanceController::class, 'index'])->name('employee.attendance.index');
Route::get('/employee/attendance/students/{lectureId}', [MarkStudentAttendanceController::class, 'getStudentsForLecturePage'])->name('employee.attendance.students');
Route::get('/students/{lectureId}', [MarkStudentAttendanceController::class, 'getStudentsForLecture'])->name('employee.attendance.students');

Route::prefix('student')->name('student.')->group(function () {
    Route::get('/attendance', [StudentAttendanceViewController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/by-date', [StudentAttendanceViewController::class, 'getAttendanceByDate'])->name('attendance.by-date');
    Route::get('/attendance/by-subject', [StudentAttendanceViewController::class, 'getAttendanceBySubject'])->name('attendance.by-subject');
    Route::get('/attendance/statistics', [StudentAttendanceViewController::class, 'getAttendanceStatisticsData'])->name('attendance.statistics');
});

Route::get('/bulk-upload-students', [BulkStudentsUploadController::class, 'getUploadPage'])->name('bulk.upload.page');
Route::post('/bulk-upload-students', [BulkStudentsUploadController::class, 'uploadBulkStudents'])->name('student.bulk.upload');
Route::get('/bulk-upload-success', [BulkStudentsUploadController::class, 'showSuccessPage'])->name('bulk.upload.success');
Route::get('/download-bulk-template', [BulkStudentsUploadController::class, 'downloadTemplate'])->name('bulk.upload.template');

// Route::get('/update-student-email', [BulkStudentsUploadController::class, 'updateStudentEmail'])->name('update.student.email');

Route::get('/bulk-upload-employee', [BulkEmployeeUploadController::class, 'getEmployeeUploadPage'])->name('employee.bulk.upload.page');
Route::post('/bulk-upload-employee', [BulkEmployeeUploadController::class, 'uploadBulkEmployees'])->name('employee.bulk.upload');
Route::get('/bulk-upload-employee-success', [BulkEmployeeUploadController::class, 'showEmployeeSuccessPage'])->name('employee.bulk.upload.success');
Route::get('/bulk-upload-employee-template', [BulkEmployeeUploadController::class, 'downloadEmployeeTemplate'])->name('employee.bulk.upload.template');

Route::middleware(['auth'])->get('/admin/all-student-list', [AdminStudentsController::class, 'adminStudentFeeStructures'])->name('admin.student.fee.structures');
Route::middleware(['auth'])->get('fee-structure-for-students', [StudentFeeController::class, 'studentFeeStructure'])
    ->name('student.fee.structure');
//Student Fee Structure Routes
Route::get('admin/student-fee/{student_hash_id}', [StudentOnboardController::class, 'showStudentFee'])->name('admin.student.fee');
Route::get('/student/fee-structure', [StudentFeeController::class, 'show'])->name('login.student.fee.structure');

// Student receipt routes
Route::post('student/receipt/fetch', [StudentFeeController::class, 'fetchReceipt'])->name('student.receipt.fetch');
Route::post('student/custom/receipt/fetch', [StudentFeeController::class, 'fetchCustomReceipt'])->name('student.custom.receipt.fetch');

Route::middleware(['auth'])->prefix('ajax')->group(function () {
    // Department Category Routes
    Route::get('/departments-by-category', [AjaxfunctionscallController::class, 'getDepartmentsByCategory'])->name('ajax.departments.by.category');
    Route::get('/category-full-data/{categoryId}', [AjaxfunctionscallController::class, 'getCategoryWithFullData']);
    // Department Routes - REMOVE DUPLICATE 'ajax/' PREFIX
    Route::get('/employees-by-department', [AjaxfunctionscallController::class, 'getEmployeesByDepartmentAjax'])->name('ajax.employees.by.department');
    Route::get('/course-types-by-department', [AjaxfunctionscallController::class, 'getCourseTypesByDepartmentAjax'])->name('ajax.course.types.by.department');
    Route::get('/get-branches-by-course', [AjaxfunctionscallController::class, 'getBranchesByCourse'])->name('ajax.branches.by.course');
    Route::get('/students-by-department', [AjaxfunctionscallController::class, 'getStudentsByDepartmentAjax'])->name('ajax.students.by.department');
    Route::get('/subjects/semesters/{productId}', [SubjectCoursewiseController::class, 'getSemesters'])->name('subjects.semesters');
    // Add this route
    Route::get('/get-semesters/{productId}', [AjaxfunctionscallController::class, 'getSemesters']);
    Route::get('/get-academic-years-by-product', [AjaxfunctionscallController::class, 'getAcademicYearsByProduct'])->name('ajax.academic.years.by.product');

    Route::get('/get-subjects-by-course', [AjaxfunctionscallController::class, 'getSubjectsByCourse'])->name('ajax.subjects.by.course');
    Route::get('/get-subjects-by-course-semester', [AjaxfunctionscallController::class, 'getSubjectsByCourseSemester'])->name('ajax.subjects.by.course.semester');
    Route::get('/get-subject-course-info', [AjaxfunctionscallController::class, 'getSubjectCourseInfo'])->name('ajax.subject.course.info');
    
    Route::get('/department-full-data', [AjaxfunctionscallController::class, 'getDepartmentFullData']);
    Route::get('/department-stats', [AjaxfunctionscallController::class, 'getDepartmentStats']);
    Route::get('/verify-department/{departmentId}', [AjaxfunctionscallController::class, 'verifyDepartmentInScope']);
    Route::get('/verify-category/{categoryId}', [AjaxfunctionscallController::class, 'verifyCategoryInScope']);
    Route::get('/employee-full-data', [AjaxfunctionscallController::class, 'getEmployeeFullData']);
    Route::get('/employee/lectures/{subjectId}', [MarkStudentAttendanceController::class, 'getLectures'])->name('ajax.employee.lectures');
    Route::post('/employee/attendance/save', [MarkStudentAttendanceController::class, 'saveAttendance'])->name('ajax.employee.attendance.save');
    // Search Routes
    Route::get('/search-employees', [AjaxfunctionscallController::class, 'searchEmployees']);
    Route::get('/search-departments', [AjaxfunctionscallController::class, 'searchDepartments']);
    // Debug route (optional)
    Route::get('/debug-employee-structure', [AjaxfunctionscallController::class, 'debugEmployeeStructure']);
    Route::get('/students-by-product', [AjaxfunctionscallController::class, 'getStudentsByProduct'])->name('ajax.students.by.product');
    Route::get('/subjects-by-course', [StudentSubjectAssignmentController::class, 'getSubjectsByCourse'])->name('ajax.subjects.by.course');
    Route::get('/get-sections-by-branch', [AjaxfunctionscallController::class, 'getSectionsByBranch']);
    // Building-Block-Floor Routes
    Route::get('/get-buildings', [AjaxfunctionscallController::class, 'getBuildingsAjax'])->name('ajax.get.buildings');
    Route::post('/get-blocks-by-building', [AjaxfunctionscallController::class, 'getBlocksByBuildingAjax'])->name('ajax.get.blocks.by.building');
    Route::post('/get-floors-by-block', [AjaxfunctionscallController::class, 'getFloorsByBlockAjax'])->name('ajax.get.floors.by.block');
    Route::post('/get-rooms-by-floor', [AjaxfunctionscallController::class, 'getRoomsByFloorAjax'])->name('ajax.get.rooms.by.floor');
    Route::get('/get-departments-by-institute', [AjaxfunctionscallController::class, 'getDepartmentsByInstitute'])->name('ajax.departments.by.institute');
    Route::get('/getsehema-branch-by-curse', [AjaxfunctionscallController::class, 'getBranchesByCourseSchema'])->name('ajax.schema.branch.by.course');
});  

// Designations Management Routes
Route::prefix('designations')->group(function () {
    Route::get('/create', [DesignationController::class, 'create'])->name('designations.create');
    Route::get('/view', [DesignationController::class, 'view'])->name('designations.view');
    Route::post('/', [DesignationController::class, 'store'])->name('designations.store');   
    // AJAX routes
    Route::get('/{id}/details', [DesignationController::class, 'details'])->name('designations.details');
    Route::get('/search', [DesignationController::class, 'search'])->name('designations.search');
   
});

Route::prefix('institute/admin')->group(function () {
    // Shift Management Routes
    Route::prefix('shifts')->group(function () {
        // Main Shift Management
        Route::get('/list', [ShiftController::class, 'index'])->name('manage.shifts.index');
        Route::get('/create', [ShiftController::class, 'create'])->name('manage.shifts.create');
        Route::post('/', [ShiftController::class, 'store'])->name('manage.shifts.store');
        Route::get('/{id}', [ShiftController::class, 'show'])->name('manage.shifts.show');
        Route::put('/{id}', [ShiftController::class, 'update'])->name('manage.shifts.update');
        Route::delete('/{id}', [ShiftController::class, 'destroy'])->name('manage.shifts.destroy');
        Route::post('/bulk-delete', [ShiftController::class, 'bulkDelete'])->name('manage.shifts.bulk-delete');
        Route::post('/bulk-update-status', [ShiftController::class, 'bulkUpdateStatus'])->name('manage.shifts.bulk-update-status');
        Route::get('/download', [ShiftController::class, 'downloadShifts'])->name('manage.shifts.download');
    });

    // Shift Assignment Routes
        Route::get('/assign-shift', [ShiftController::class, 'assignShiftForm'])->name('shifts.assignform');
        Route::post('/assign-shift', [ShiftController::class, 'assignShift'])->name('shifts.assign');
        Route::get('/shift-schedule', [ShiftController::class, 'shiftSchedule'])->name('shifts.schedule');
        Route::get('/shift-schedule/download', [ShiftController::class, 'downloadShiftSchedule'])->name('shifts.schedule.download');
        Route::get('/departments-by-categories', [ShiftController::class, 'getmultiDepartmentsByCategories'])->name('ajax.multidepartments.by.categories');
        Route::get('/employees-by-departments', [ShiftController::class, 'getmultiEmployeesByDepartments'])->name('ajax.multiemployees.by.departments');

});

Route::prefix('shifts')->group(function () {
    // Form page
    Route::get('/assign-to-students', [StudentShiftController::class, 'assignForm'])
        ->name('student.shifts.assign.form');
    // Assignment action
    Route::post('/assign-to-students', [StudentShiftController::class, 'assignShift'])
        ->name('student.shifts.assign');
    // AJAX endpoint for students (using academic_transport_details)
    Route::get('/ajax/students-by-departments', [StudentShiftController::class, 'getStudentsByDepartments'])
        ->name('student.shifts.ajax.students.by.departments');
    // Alternative endpoint with details
    Route::get('/ajax/students-by-departments-with-details', [StudentShiftController::class, 'getStudentsByDepartmentsWithDetails'])
        ->name('student.shifts.ajax.students.by.departments.details');
});
// Student Shift Routes
Route::prefix('student')->group(function () {
    Route::get('/my-shift', [StudentShiftController::class, 'getCurrentShift'])->name('student.shift.current');
    Route::get('/shift-history', [StudentShiftController::class, 'getShiftHistory'])->name('student.shift.history');
    Route::get('/shift-history/{studentHashId}', [StudentShiftController::class, 'getShiftHistory']);
});

// Route::get('/institute/admin/settings', function () { return view('instituteAdmin/DashboardFiles/Setting');})->name('admin.transport.create');
Route::get('/settings', [AdminSettingsController::class, 'AdminSettingsController']);
Route::put('/authorized-user/photo', [AdminSettingsController::class, 'uploadAuthorizedPhoto'])->name('authorized.photo.upload');
Route::delete('/authorized-user/photo', [AdminSettingsController::class, 'deleteAuthorizedPhoto'])->name('authorized.photo.delete');

Route::get('/institute/admin/logout', function () { return view('instituteAdmin/DashboardFiles/logoutPage');})->name('admin.transport.create');
Route::get('/institute/admin/exam-structure', function () { return view('instituteAdmin/DashboardFiles/examStructure');})->name('admin.transport.create');
Route::get('/institute/admin/report-card', function () { return view('instituteAdmin/DashboardFiles/reportCard');})->name('admin.transport.create');

//Exam Structure
Route::get('/exam-structure-offline', [CreateExamStructureController::class, 'create'])->name('institute-admin.exam-structure.create');
Route::post('/save-multiple-exams', [CreateExamStructureController::class, 'store']);
Route::prefix('institute-admin')->group(function () {
    Route::get('/exam-management', [ShowExamStructureController::class, 'examManagement'])->name('institute-admin.exam-management');
    //  Route::get('/exam-management', [ShowExamStructureController::class, 'examManagement'])->name('institute-admin.exam-management');
    Route::get('/exam-management/data', [ShowExamStructureController::class, 'getExamsData'])->name('institute-admin.exam-management.data');
    Route::get('/exam-management/{id}/details', [ShowExamStructureController::class, 'getExamDetails'])->name('institute-admin.exam-management.details');
    Route::put('/exam-management/{id}/update', [ShowExamStructureController::class, 'updateExam'])->name('institute-admin.exam-management.update');
    Route::delete('/exam-management/{id}/delete', [ShowExamStructureController::class, 'deleteExam'])->name('institute-admin.exam-management.delete');
    Route::get('/exam-management/export', [ShowExamStructureController::class, 'exportExams'])->name('institute-admin.exam-management.export');
});

//Download exam Structure
Route::get('/offline-exams/download',[ShowExamStructureController::class, 'downloadOfflineExams']);

Route::prefix('grade-systems')->group(function () {
    Route::get('/', [GradeSystemController::class, 'index'])->name('grade-systems.index');
    Route::get('/create', [GradeSystemController::class, 'create'])->name('grade-systems.create');
    Route::post('/', [GradeSystemController::class, 'store'])->name('grade-systems.store');
    Route::get('/{id}/edit', [GradeSystemController::class, 'edit'])->name('grade-systems.edit');
    Route::put('/{id}', [GradeSystemController::class, 'update'])->name('grade-systems.update');
    Route::delete('/{id}', [GradeSystemController::class, 'destroy'])->name('grade-systems.destroy');
    Route::get('/list', [GradeSystemController::class, 'getGradeSystems'])->name('grade-systems.list');
});

// Grade System Download
Route::get('/download-grade-systems', [GradeSystemController::class, 'downloadGradeSystems']);

Route::get('/ajax/exam-names', [ExamNameController::class, 'getExamNames'])->name('ajax.exam-names');
Route::get('/anees-report-card', function () { return view('instituteAdmin/ReportCard/reportCardDesign');})->name('admin.transport.create');
Route::get('/sample-student-id-card', function () { return view('instituteAdmin/ReportCard/StudentIdCardDesign');})->name('student.transport.create');
Route::get('/sample-employee-id-card', function () { return view('instituteAdmin/ReportCard/employeeIdCard');})->name('employee.transport.create');
Route::prefix('report-card')->group(function () {
    Route::get('/', [ReportCardController::class, 'index'])->name('report.card.hierarchical');
    Route::get('/student-report', [ReportCardController::class, 'showStudentReport'])->name('report.card.student');
    Route::post('/generate-pdf', [ReportCardController::class, 'generatePDF'])->name('report.card.generate.pdf');
});
Route::get('/ajax/get-students-by-branch', [ReportCardController::class, 'getStudentsByBranch'])->name('ajax.students.by.branch');
Route::get('/ajax/get-student-exam-marks', [ReportCardController::class, 'getStudentExamMarks'])->name('ajax.student.exam.marks');

// Student Exam Schedule Routes
Route::prefix('student')->group(function () {
    Route::get('/exam-schedule', [StudentExamScheduleController::class, 'studentExamScheduleView'])
         ->name('student.exam-schedule');
    Route::get('/exam-schedule/data', [StudentExamScheduleController::class, 'getStudentExamSchedule'])
         ->name('student.exam-schedule.data');
    Route::get('/exam-schedule/{examId}/details', [StudentExamScheduleController::class, 'getExamDetails'])
         ->name('student.exam-schedule.details');
});

// Employee Exam Marking Routes
Route::prefix('employee')->name('employee.')->group(function () {
    // Exam marking dashboard
    Route::get('/exam-marking', [EmployeeExamMarkingController::class, 'employeeExamMarkingView'])
        ->name('exam.marking.view');
    // Get exams for employee
    Route::get('/exams', [EmployeeExamMarkingController::class, 'getEmployeeExams'])
        ->name('exams.data');
    // Get students for exam marking
    Route::get('/exam/{examId}/students', [EmployeeExamMarkingController::class, 'getStudentsForExamMarking'])
        ->name('exam.students');
    // Save exam marks
    Route::post('/exam/marks/save', [EmployeeExamMarkingController::class, 'saveExamMarks'])
        ->name('exam.marks.save');
    // Get exam marks summary
    Route::get('/exam/{examId}/summary', [EmployeeExamMarkingController::class, 'getExamMarksSummary'])
        ->name('exam.summary');
    // Export marks
    Route::get('/exam/{examId}/export/{format?}', [EmployeeExamMarkingController::class, 'exportExamMarks'])
        ->name('exam.export');
    Route::get('/exam/marking/{examId}', [EmployeeExamMarkingController::class, 'examMarkingPage'])
        ->name('exam.marking.page');
    Route::get('/exam/{examId}/students-with-marks', [EmployeeExamMarkingController::class, 'getStudentsWithMarks'])
        ->name('exam.students.with.marks');
    Route::put('/exam/marks/update/{studentHashId}', [EmployeeExamMarkingController::class, 'updateExamMarks'])
        ->name('exam.marks.update');
    Route::post('/exam/marks/bulk-update', [EmployeeExamMarkingController::class, 'bulkUpdateExamMarks'])
        ->name('exam.marks.bulk.update');
});


Route::middleware(['auth'])->prefix('institute/admin')->group(function () {
    Route::get('/holiday-events', [HolidayEventController::class, 'index'])->name('institute-admin.holiday-events.index');
    Route::post('/holiday-events', [HolidayEventController::class, 'store'])->name('institute-admin.holiday-events.store');
    Route::get('/holiday-events/{id}/edit', [HolidayEventController::class, 'edit'])->name('institute-admin.holiday-events.edit');
    Route::put('/holiday-events/{id}', [HolidayEventController::class, 'update'])->name('institute-admin.holiday-events.update');
    Route::delete('/holiday-events/{id}', [HolidayEventController::class, 'destroy'])->name('institute-admin.holiday-events.destroy');
    Route::get('/holiday-events/calendar', [HolidayEventController::class, 'getCalendarEvents'])->name('institute-admin.holiday-events.calendar');
    Route::get('/google/calendar', [GoogleCalendarController::class, 'googleCalendarEvents'])->name('institute-admin.google-calendar');
    Route::get('/google/calendar/events', [GoogleCalendarController::class, 'getCalendarEventsApi'])->name('institute-admin.google-calendar.events');
});

// Route::get('/institute/admin/InventoryManagement', function () { return view('instituteAdmin/DashboardFiles/InventoryManagement');})->name('admin.inventory.create');
// Route::prefix('institute/admin')->middleware('auth')->group(function () {
//     // Inventory
//     Route::get('/inventory', [InventoryController::class,'index'])->name('inventory.index');
//     Route::get('/inventory/items', [InventoryController::class,'getItems'])->name('inventory.items');
//     Route::get('/inventory/item/{id}', [InventoryController::class,'show'])->name('inventory.item.show');
//     Route::post('/inventory/item', [InventoryController::class,'store'])->name('inventory.item.store');
//     Route::post('/inventory/item/{item}', [InventoryController::class,'update'])->name('inventory.item.update');
//     Route::post('/inventory/item/{item}/stock', [InventoryController::class,'adjustStock'])->name('inventory.item.stock');
//     Route::post('/inventory/item/{item}/delete', [InventoryController::class,'destroy'])->name('inventory.item.delete');
//     // Categories
//     Route::get('/inventory/categories', [InventoryController::class,'getCategories'])->name('inventory.categories');
//     Route::post('/inventory/category/store', [InventoryController::class,'storeCategory'])->name('inventory.category.store');
//     Route::post('/inventory/category/{id}/delete', [InventoryController::class,'deleteCategory'])->name('inventory.category.delete');
//     // Logs (GLOBAL)
//     Route::get('/inventory/logs', [InventoryController::class,'getActivityLogs'])
//         ->name('inventory.activity.logs');
//     Route::get('/inventory/transactions', [InventoryController::class,'getinventorytransactions'])
//         ->name('inventory.transactions');
//     // Item-specific
//     Route::get('/inventory/item/{id}/activity', [InventoryController::class,'getItemActivityHistory'])
//         ->name('inventory.item.activity');
//     Route::get('/inventory/item/{id}/stock-history', [InventoryController::class,'getItemStockHistory'])
//         ->name('inventory.item.stock.history');
// });

    // New Inventory Routes
    // Inventory Dashboard Routes
    Route::get('/inventory', [InventoryDashboardController::class, 'index'])->name('inventory.dashboard');
    Route::prefix('inventory')->group(function () {
        // ============================================================
        // INVENTORY CONFIGURATION ROUTES
        // ============================================================
        Route::get('/configuration', [InventoryConfigurationController::class, 'index'])->name('inventory.configuration');
        Route::get('/configuration/create', [InventoryConfigurationController::class, 'create'])->name('inventory.configuration.create');
        Route::post('/configuration/store', [InventoryConfigurationController::class, 'store'])->name('inventory.configuration.store');
        Route::get('/configuration/view/{id}', [InventoryConfigurationController::class, 'view'])->name('inventory.configuration.view');
        Route::get('/configuration/edit/{id}', [InventoryConfigurationController::class, 'edit'])->name('inventory.configuration.edit');
        Route::put('/configuration/update/{id}', [InventoryConfigurationController::class, 'update'])->name('inventory.configuration.update');
        Route::delete('/configuration/delete/{id}', [InventoryConfigurationController::class, 'destroy'])->name('inventory.configuration.delete');
        Route::get('/configuration/logs/{id}', [InventoryConfigurationController::class, 'logs'])->name('inventory.configuration.logs');
        Route::post('/configuration/toggle-status/{id}', [InventoryConfigurationController::class, 'toggleStatus'])->name('inventory.configuration.toggle-status');        
        Route::get('/configuration/check-delete/{id}', [InventoryConfigurationController::class, 'checkDelete'])->name('inventory.configuration.check-delete');

        // ============================================================
        // INVENTORY CATEGORY ROUTES
        // ============================================================
        Route::get('/categories', [InventoryCategoryController::class, 'index'])->name('inventory.categories.index');
        Route::get('/categories/create', [InventoryCategoryController::class, 'create'])->name('inventory.categories.create');
        Route::post('/categories/store', [InventoryCategoryController::class, 'store'])->name('inventory.categories.store');
        Route::get('/categories/edit/{id}', [InventoryCategoryController::class, 'edit'])->name('inventory.categories.edit');
        Route::post('/categories/update/{id}', [InventoryCategoryController::class, 'update'])->name('inventory.categories.update');
        Route::delete('/categories/delete/{id}', [InventoryCategoryController::class, 'destroy'])->name('inventory.categories.delete');
        Route::get('/categories/{id}/logs', [InventoryCategoryController::class, 'logs'])->name('inventory.categories.log');
        Route::get('/categories/view/{id}', [InventoryCategoryController::class, 'view'])->name('inventory.categories.view');
        Route::post('/categories/toggle-status/{id}', [InventoryCategoryController::class, 'toggleStatus'])->name('inventory.categories.toggle-status');
    
        // ============================================================
        // TAX & GST ROUTES (NEW)
        // ============================================================
        Route::get('/categories/get-tax-slabs', [InventoryCategoryController::class, 'getTaxSlabs'])->name('inventory.categories.get-tax-slabs');
        Route::get('/categories/get-hsn-codes', [InventoryCategoryController::class, 'getHSNCodes'])->name('inventory.categories.get-hsn-codes');
        Route::get('/categories/get-gst-rates/{categoryType}', [InventoryCategoryController::class, 'getGSTRates'])->name('inventory.categories.get-gst-rates');
        Route::post('/categories/calculate-tax', [InventoryCategoryController::class, 'calculateTax'])->name('inventory.categories.calculate-tax');
        Route::get('/categories/check-delete/{id}', [InventoryCategoryController::class, 'checkDelete'])->name('inventory.categories.check-delete');
        Route::get('/categories/{id}/tax-details', [InventoryCategoryController::class, 'getTaxDetails'])->name('inventory.categories.tax-details');
    
        // ============================================================
        // INVENTORY SUB-CATEGORY ROUTES
        // ============================================================
        Route::get('/subcategories', [InventorySubCategoryController::class, 'index'])->name('inventory.subcategories.index');
        Route::get('/subcategories/create', [InventorySubCategoryController::class, 'create'])->name('inventory.subcategories.create');
        Route::post('/subcategories/store', [InventorySubCategoryController::class, 'store'])->name('inventory.subcategories.store');
        Route::get('/subcategories/edit/{id}', [InventorySubCategoryController::class, 'edit'])->name('inventory.subcategories.edit');
        Route::post('/subcategories/update/{id}', [InventorySubCategoryController::class, 'update'])->name('inventory.subcategories.update');
        Route::delete('/subcategories/delete/{id}', [InventorySubCategoryController::class, 'destroy'])->name('inventory.subcategories.delete');
        Route::get('/subcategories/by-category/{categoryId}', [InventorySubCategoryController::class, 'getByCategory'])->name('inventory.subcategories.by-category');

        // ============================================================
        // INVENTORY WAREHOUSE ROUTES
        // ============================================================
        Route::prefix('warehouses')->group(function () {
            // Main CRUD routes
            Route::get('/', [InventoryWarehouseController::class, 'index'])->name('inventory.warehouses.index');
            Route::get('/create', [InventoryWarehouseController::class, 'create'])->name('inventory.warehouses.create');
            Route::post('/store', [InventoryWarehouseController::class, 'store'])->name('inventory.warehouses.store');
            Route::get('/edit/{id}', [InventoryWarehouseController::class, 'edit'])->name('inventory.warehouses.edit');
            Route::post('/update/{id}', [InventoryWarehouseController::class, 'update'])->name('inventory.warehouses.update');
            Route::delete('/delete/{id}', [InventoryWarehouseController::class, 'destroy'])->name('inventory.warehouses.delete');
            
            // Step form routes
            Route::get('/{warehouse}/add-store', [InventoryWarehouseController::class, 'addStore'])->name('inventory.warehouses.add-store');
            Route::post('/{warehouse}/store-store', [InventoryWarehouseController::class, 'storeStore'])->name('inventory.warehouses.store-store');
            
            // Additional routes
            Route::get('/logs/{id}', [InventoryWarehouseController::class, 'logs'])->name('inventory.warehouses.logs');
            Route::get('/stock-summary/{id}', [InventoryWarehouseController::class, 'stockSummary'])->name('inventory.warehouses.stock-summary');
            Route::get('/view/{id}', [InventoryWarehouseController::class, 'view'])->name('inventory.warehouses.view');
            Route::post('/toggle-status/{id}', [InventoryWarehouseController::class, 'toggleStatus'])->name('inventory.warehouses.toggle-status');
            Route::post('/update-utilization/{id}', [InventoryWarehouseController::class, 'updateUtilization'])->name('inventory.warehouses.update-utilization');
        });

        // ============================================================
        // INVENTORY STORE MANAGEMENT ROUTES (Standalone)
        // ============================================================
        Route::prefix('stores')->group(function () {
            Route::get('/', [InventoryStoreController::class, 'index'])->name('inventory.stores.index');
            Route::get('/create', [InventoryStoreController::class, 'create'])->name('inventory.stores.create');
            Route::post('/store', [InventoryStoreController::class, 'store'])->name('inventory.stores.store');
            Route::get('/view/{id}', [InventoryStoreController::class, 'view'])->name('inventory.stores.view');
            Route::get('/edit/{id}', [InventoryStoreController::class, 'edit'])->name('inventory.stores.edit');
            Route::post('/update/{id}', [InventoryStoreController::class, 'update'])->name('inventory.stores.update');
            Route::delete('/delete/{id}', [InventoryStoreController::class, 'destroy'])->name('inventory.stores.delete');
            Route::post('/toggle-status/{id}', [InventoryStoreController::class, 'toggleStatus'])->name('inventory.stores.toggle-status');
            Route::get('/logs/{id}', [InventoryStoreController::class, 'logs'])->name('inventory.stores.logs');
            Route::get('/{id}/stock-summary', [InventoryStoreController::class, 'stockSummary'])->name('inventory.stores.stock-summary');
        });

        // ============================================================
        // INVENTORY ITEM/Stock-In ROUTES 
        // ============================================================
        Route::prefix('items')->name('inventory.items.')->group(function () {
            Route::get('/', [InventoryItemController::class, 'index'])->name('index');
            Route::get('/create', [InventoryItemController::class, 'create'])->name('create');
            Route::post('/store', [InventoryItemController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [InventoryItemController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [InventoryItemController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [InventoryItemController::class, 'destroy'])->name('delete');
            Route::post('/toggle-status/{id}', [InventoryItemController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/step1', [InventoryItemController::class, 'storeStep1'])->name('step1');
            Route::post('/step2', [InventoryItemController::class, 'storeStep2'])->name('step2');
            Route::post('/step3', [InventoryItemController::class, 'storeStep3'])->name('step3');
            Route::post('/step4', [InventoryItemController::class, 'storeStep4'])->name('step4');
            Route::post('/step5', [InventoryItemController::class, 'storeStep4'])->name('step5');
            Route::post('/step6', [InventoryItemController::class, 'storeStep4'])->name('step6');
            Route::get('/view/{id}', [InventoryItemController::class, 'view'])->name('view');
            Route::get('/fetch-transfer/{transferId}', [InventoryItemController::class, 'fetchTransfer'])->name('fetch-transfer');
            Route::get('/{id}/batches', [InventoryItemController::class, 'getItemBatches'])->name('get-batches');
            Route::get('/{id}/assets', [InventoryItemController::class, 'getItemAssets'])->name('get-assets');
            Route::get('/{id}/tax-details', [InventoryItemController::class, 'getTaxDetails'])->name('get-tax-details');
        });

        Route::get('/subcategory-by-category/{categoryId}', [InventoryItemController::class, 'subCategoryByCategory'])->name('inventory.subcategory.by.category');

        // ============================================================
        // INVENTORY STOCK OUT ROUTES (Transfer & Sell)
        // ============================================================
        Route::prefix('stock-out')->name('inventory.stock-out.')->group(function () {
            Route::get('/', [InventoryStockOutController::class, 'index'])->name('index');
            Route::get('/create', [InventoryStockOutController::class, 'create'])->name('create');
            Route::post('/store', [InventoryStockOutController::class, 'store'])->name('store');
            Route::get('/{id}', [InventoryStockOutController::class, 'show'])->name('show'); // Changed from show/{id}
            Route::get('/{id}/edit', [InventoryStockOutController::class, 'edit'])->name('edit'); // Changed from edit/{id}
            Route::put('/{id}', [InventoryStockOutController::class, 'update'])->name('update'); // Changed from update/{id}
            Route::delete('/{id}', [InventoryStockOutController::class, 'destroy'])->name('destroy'); // Changed from delete/{id}
            Route::post('/{id}/approve', [InventoryStockOutController::class, 'approve'])->name('approve');
            Route::post('/{id}/complete', [InventoryStockOutController::class, 'complete'])->name('complete');
            Route::post('/{id}/cancel', [InventoryStockOutController::class, 'cancel'])->name('cancel');
            Route::get('/{id}/print', [InventoryStockOutController::class, 'print'])->name('print');
            Route::get('/item-details/{id}', [InventoryStockOutController::class, 'getItemDetails'])->name('item-details');
        });

        // ============================================================
        // INVENTORY RECEIPT OUT ROUTES
        // ============================================================
        Route::prefix('receipts/out')->name('inventory.receipts.out.')->group(function () {
            Route::get('/', [InventoryReceiptOutController::class, 'index'])->name('index');
            Route::get('/create', [InventoryReceiptOutController::class, 'create'])->name('create');
            Route::post('/store', [InventoryReceiptOutController::class, 'store'])->name('store');
            Route::get('/{id}', [InventoryReceiptOutController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [InventoryReceiptOutController::class, 'edit'])->name('edit');
            Route::put('/{id}', [InventoryReceiptOutController::class, 'update'])->name('update');
            Route::delete('/{id}', [InventoryReceiptOutController::class, 'destroy'])->name('delete');
            Route::post('/{id}/approve', [InventoryReceiptOutController::class, 'approve'])->name('approve');
            Route::post('/{id}/complete', [InventoryReceiptOutController::class, 'complete'])->name('complete');
            Route::post('/{id}/cancel', [InventoryReceiptOutController::class, 'cancel'])->name('cancel');
            Route::get('/{id}/print', [InventoryReceiptOutController::class, 'print'])->name('print');
        });

        // ============================================================
        // INVENTORY RECEIPT IN ROUTES
        // ============================================================
        Route::prefix('receipts/in')->name('inventory.receipts.in.')->group(function () {
            Route::get('/', [InventoryReceiptInController::class, 'index'])->name('index');
            Route::get('/create', [InventoryReceiptInController::class, 'create'])->name('create');
            Route::post('/store', [InventoryReceiptInController::class, 'store'])->name('store');
            Route::get('/{id}', [InventoryReceiptInController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [InventoryReceiptInController::class, 'edit'])->name('edit');
            Route::put('/{id}', [InventoryReceiptInController::class, 'update'])->name('update');
            Route::delete('/{id}', [InventoryReceiptInController::class, 'destroy'])->name('delete');
            Route::post('/{id}/approve', [InventoryReceiptInController::class, 'approve'])->name('approve');
            Route::post('/{id}/complete', [InventoryReceiptInController::class, 'complete'])->name('complete');
            Route::post('/{id}/cancel', [InventoryReceiptInController::class, 'cancel'])->name('cancel');
            Route::get('/{id}/print', [InventoryReceiptInController::class, 'print'])->name('print');
        });

        // ============================================================
        // DEPRECATED: WAREHOUSE TRANSFER ROUTES (Redirect to Stock Out)
        // ============================================================
        Route::prefix('transfers')->name('inventory.transfers.')->group(function () {
            Route::get('/', function() {
                return redirect()->route('inventory.stock-out.index');
            })->name('index');
            Route::get('/create', function() {
                return redirect()->route('inventory.stock-out.create');
            })->name('create');
            Route::get('/{id}', function($id) {
                return redirect()->route('inventory.stock-out.show', $id);
            })->name('show');
            // Keep these for backward compatibility but redirect
            Route::get('/{id}/edit', function($id) {
                return redirect()->route('inventory.stock-out.edit', $id);
            })->name('edit');
        });

        // ============================================================
        // INVENTORY DASHBOARD AJAX ROUTES
        // ============================================================
        Route::prefix('dashboard')->name('inventory.dashboard.')->group(function () {
            Route::get('/alerts', [InventoryDashboardController::class, 'stockAlerts'])->name('alerts');
            Route::get('/warehouse-distribution', [InventoryDashboardController::class, 'warehouseStockDistribution'])->name('warehouse-distribution');
            Route::get('/quick-stats', [InventoryDashboardController::class, 'quickStats'])->name('quick-stats');
            Route::get('/monthly-movement', [InventoryDashboardController::class, 'monthlyMovement'])->name('monthly-movement');
            Route::get('/activity-feed', [InventoryDashboardController::class, 'activityFeed'])->name('activity-feed');
            Route::get('/filter', [InventoryDashboardController::class, 'filter'])->name('filter');
        });

        // ============================================================
        // INVENTORY TRANSACTIONS ROUTES
        // ============================================================
        Route::prefix('transactions')->name('inventory.transactions.')->group(function () {
            Route::get('/', [InventoryTransactionController::class, 'index'])->name('index');
            Route::get('/{id}', [InventoryTransactionController::class, 'show'])->name('show');
            Route::post('/{id}/payment-status', [InventoryTransactionController::class, 'updatePaymentStatus'])->name('update-payment-status');
            Route::post('/{id}/refund', [InventoryTransactionController::class, 'processRefund'])->name('refund');
            Route::post('/{id}/logistics', [InventoryTransactionController::class, 'updateLogistics'])->name('logistics');
            Route::get('/{id}/print', [InventoryTransactionController::class, 'printReceipt'])->name('print');
            Route::post('/{id}/approve', [InventoryTransactionController::class, 'approveTransaction'])->name('approve');
            Route::post('/{id}/complete', [InventoryTransactionController::class, 'completeTransfer'])->name('complete');
            Route::post('/{id}/cancel', [InventoryTransactionController::class, 'cancelTransaction'])->name('cancel');
            Route::get('/export', [InventoryTransactionController::class, 'export'])->name('export');
        });

        Route::prefix('vendors')->group(function () {
            Route::get('/', [InventoryVendorController::class, 'index'])->name('inventory.vendors.index');
            Route::get('/create', [InventoryVendorController::class, 'create'])->name('inventory.vendors.create');
            Route::post('/', [InventoryVendorController::class, 'store'])->name('inventory.vendors.store');
            Route::get('/{id}/edit', [InventoryVendorController::class, 'edit'])->name('inventory.vendors.edit');
            Route::put('/{id}', [InventoryVendorController::class, 'update'])->name('inventory.vendors.update');
            Route::delete('/{id}', [InventoryVendorController::class, 'destroy'])->name('inventory.vendors.destroy');
            Route::post('/{id}/toggle-status', [InventoryVendorController::class, 'toggleStatus'])->name('inventory.vendors.toggle-status');
        });
    });


Route::get('/instituteAdmin/assignleavesto-students', [StudentLeaveController::class, 'assignLeaveForm']);
Route::get('/instituteAdmin/get-students/{department_id}', [StudentLeaveController::class, 'getStudentsByDepartment'])
    ->name('get.students.by.department');
Route::post('/instituteAdmin/assign-leave-to-students', [StudentLeaveController::class, 'assignStudentLeaveStore'])
    ->name('student.leave.assign.store');
Route::get('/employee/schedule', [EmployeeSubjectsController::class, 'getEmployeeSchedule'])
    ->name('employee.schedule')
    ->middleware('auth');


 // Payroll Management Routes without Controller New Routes Dynamic
    Route::prefix('institute/admin/payroll')->group(function () {

    // Individual pages
    Route::get('/payroll-configuration', function () {
        return view('instituteAdmin.Payroll.PayrollConfiguration');
    })->name('institute.payroll.configuration');

    // UPDATED: Policy Management route - now uses controller method
    Route::get('/policy-management', [ProvidentFundPolicyController::class, 'viewPolicyManagement'])
        ->name('institute.payroll.policy.management');

    Route::get('/policy-logs', [PayrollPolicyLogsController::class, 'index'])
        ->name('institute.payroll.policy.logs');

    // NEW: Policy Details view route (replaces the old edit route)
    Route::get('/policy-details/{policyId}', [ProvidentFundPolicyController::class, 'viewPolicyDetails'])
        ->name('institute.payroll.policy.details');

    // Keep the edit route for actual editing
    Route::get('/payroll-configuration/{policyId}', function ($policyId) {
        return view('instituteAdmin.Payroll.PayrollConfiguration', [
            'policyId' => $policyId,
            'isEditMode' => true
        ]);
    })->name('institute.payroll.policy.edit');

    // Edit policy page
    Route::get('/policy-edit/{policyId}', [ProvidentFundPolicyController::class, 'editPolicy'])
        ->name('institute.payroll.policy.edit');

    // API endpoints for editing
    Route::get('/api/policy-data/{policyId}', [ProvidentFundPolicyController::class, 'getPolicyData']);
    Route::post('/api/update-policy/{policyId}', [ProvidentFundPolicyController::class, 'updatePolicy']);
    Route::post('/api/update-allowances/{policyId}', [ProvidentFundPolicyController::class, 'updateAllowances']);
    Route::post('/api/update-tax-deductions/{policyId}', [ProvidentFundPolicyController::class, 'updateTaxDeductions']);
    Route::post('/api/update-other-deductions/{policyId}', [ProvidentFundPolicyController::class, 'updateOtherDeductions']);


    Route::get('/salary-structure', function () {
        return view('instituteAdmin.Payroll.SalaryStructure');
    })->name('institute.payroll.structure');

    // UPDATED: Salary Management route - now uses controller method for department view
    Route::get('/salary-management', [EmployeeSalaryStructureController::class, 'viewSalaryManagement'])
        ->name('institute.payroll.salary.management');

    // NEW: Salary Details view route
    Route::get('/salary-details/{salaryStructureId}', [EmployeeSalaryStructureController::class, 'viewSalaryDetails'])
        ->name('institute.payroll.salary.details');

    Route::get('/salary-slips', function () {
        return view('instituteAdmin.Payroll.SalarySlips');
    })->name('institute.payroll.slips');

    Route::get('/reports-analytics', function () {
        return view('instituteAdmin.Payroll.ReportsAnalytics');
    })->name('institute.payroll.reports');

    Route::get('/employee-salary-slips', function () {
        return view('instituteAdmin.Payroll.EmployeeSalarySlips');
    })->name('institute.payroll.slips');
});


Route::get('/get-departments-with-policies', [ProvidentFundPolicyController::class, 'getDepartmentsWithPolicies']);
Route::get('/get-policies-by-department', [ProvidentFundPolicyController::class, 'getPoliciesByDepartment']);
Route::get('/get-full-policy-details/{payrollPolicyId}', [ProvidentFundPolicyController::class, 'getFullPolicyDetails']);
Route::get('/get-employees-by-department-for-structure/{departmentId}', [ProvidentFundPolicyController::class, 'getEmployeesByDepartmentForStructure']);
Route::post('/check-existing-structures', [EmployeeSalaryStructureController::class, 'checkExistingStructures'])
    ->name('salary.checkExisting');
Route::get('/get-existing-structures', [EmployeeSalaryStructureController::class, 'getExistingStructuresByDepartment']);

Route::get('/get-payroll/employees-with-policies', [ProvidentFundPolicyController::class, 'getEmployeesWithPolicies']);
Route::get('/get-payroll/departments-with-policies', [ProvidentFundPolicyController::class, 'getDepartmentsWithPoliciesForDropdown']);

Route::post('/provident-fund-policy/check-duplicate', [ProvidentFundPolicyController::class, 'checkDuplicate']);
Route::get('/get-payroll/employee-details/{employeeId}', [ProvidentFundPolicyController::class, 'getEmployeeDetails']);


Route::get('/institute/admin/payroll/policy/edit/{policyId}', [ProvidentFundPolicyController::class, 'editPolicyWithWarning'])->name('payroll.policy.edit.with.warning');
Route::get('/institute/admin/payroll/api/check-policy-structures/{policyId}', [ProvidentFundPolicyController::class, 'checkPolicyStructures']);

Route::get('/institute/payroll/salary-logs', [SalaryStructureLogsController::class, 'index'])->name('institute.payroll.salary.logs')->middleware('auth');
    

//  Payroll Policies Routes
        Route::post(
            '/provident-fund-policy/store',
            [\App\Http\Controllers\institute\Admin\PayrollPolicy\ProvidentFundPolicyController::class, 'store']
        );
        Route::get(
            '/get-provident-fund-policy/{payroll_policy_id}',
            [ProvidentFundPolicyController::class, 'show']
        );
        
        Route::get(
            '/get-provident-fund-policies',
            [ProvidentFundPolicyController::class, 'index']
        );

        Route::get(
            '/payroll-policy-dropdown',
            [ProvidentFundPolicyController::class, 'payrollPolicyDropdown']
        );

 // Allowances api routes
            Route::post(
            '/payroll-policy-allowances/store',
            [\App\Http\Controllers\institute\Admin\PayrollPolicy\PayrollpolicyAllowanceController::class, 'storeAllowance']
        );

        Route::get(
            '/get-payroll-policy-allowance/{payroll_policy_id}',
            [PayrollpolicyAllowanceController::class, 'getAllowance']
        );
        Route::get(
            '/get-payroll-policy-allowances',
            [PayrollpolicyAllowanceController::class, 'getAllAllowances']
        );

// Other deductions api routes
        Route::post(
            '/payroll-policy/other-deductions/save',
            [\App\Http\Controllers\institute\Admin\PayrollPolicy\PayrollPolicyOtherDeductionController::class, 'storeOtherDeductions']
        );

        Route::get(
            '/get-payroll-policy-other-deduction/{payroll_policy_id}',
            [PayrollPolicyOtherDeductionController::class, 'getOtherDeduction']
        );
        Route::get(
            '/get-payroll-policy-other-deductions',
            [PayrollPolicyOtherDeductionController::class, 'getAllOtherDeduction']
        );

    // tax deduction api routes
        Route::post(
            '/payroll-policy/tax-deductions/save',
            [\App\Http\Controllers\institute\Admin\PayrollPolicy\PayrollPolicyTaxDeductionController::class, 'storeTaxDeductions']
        );

        Route::get(
            '/get-payroll-policy-tax-deduction/{payroll_policy_id}',
            [PayrollPolicyTaxDeductionController::class, 'getTaxDeduction']
        );
        Route::get(
            '/get-payroll-policy-tax-deductions',
            [PayrollPolicyTaxDeductionController::class, 'getAllTaxDeduction']
        );
      
    // Salary structure api routes

        Route::post(
            '/employee/salary-structure/save',
            [\App\Http\Controllers\institute\Admin\PayrollPolicy\EmployeeSalaryStructureController::class, 'storesalarystructure']
        );

        Route::post('/salary-structure/allowances', [EmployeeSalaryStructureController::class, 'storeSalaryAllowances']);

        Route::post(
            '/salary-structure/bonus',
            [EmployeeSalaryStructureController::class, 'storeSalaryBonus']
        );
        Route::post(
            '/salary-structure/overtime',
            [EmployeeSalaryStructureController::class, 'storeSalaryOvertime']
        );
        Route::post(
            '/salary-structure/deductions',
            [EmployeeSalaryStructureController::class, 'storeSalaryDeductions']
        );
        Route::post(
            '/salary-structure/preview',
            [EmployeeSalaryStructureController::class, 'storeSalaryPreview']
        );

    // Salary structure api routes (GET) 

        Route::get('get-salary-structure/', [EmployeeSalaryStructureController::class, 'getAllSalaryStructures']);
        
        Route::get(
            'salary-structure/{salary_structure_id}',
            [EmployeeSalaryStructureController::class, 'getSalaryStructure']
        );

        Route::get('get-salary-structure/allowances', [EmployeeSalaryStructureController::class, 'getAllSalaryAllowances']);
        
        Route::get(
            'salary-structure/{salary_structure_id}/allowances',
            [EmployeeSalaryStructureController::class, 'getSalaryAllowances']
        );

        Route::get('get-salary-structure/bonuses', [EmployeeSalaryStructureController::class, 'getAllSalaryBonuses']);
       
        Route::get(
            'salary-structure/{salary_structure_id}/bonuses',
            [EmployeeSalaryStructureController::class, 'getSalaryBonuses']
        );

        Route::get('get-salary-structure/overtime', [EmployeeSalaryStructureController::class, 'getAllSalaryOvertime']);
        
        Route::get(
            'salary-structure/{salary_structure_id}/overtime',
            [EmployeeSalaryStructureController::class, 'getSalaryOvertime']
        );

        Route::get('get-salary-structure/deductions', [EmployeeSalaryStructureController::class, 'getAllSalaryDeductions']);
        
        Route::get(
            'salary-structure/{salary_structure_id}/deductions',
            [EmployeeSalaryStructureController::class, 'getSalaryDeductions']
        );

        Route::get('get-salary-structure/preview', [EmployeeSalaryStructureController::class, 'getAllSalaryPreviews']);
        
        Route::get(
            'salary-structure/{salary_structure_id}/preview',
            [EmployeeSalaryStructureController::class, 'getSalaryPreview']
        );
        
        // Employee Payroll Policy View
        Route::get('/my-payroll-policy', [EmployeePayrollDashboardController::class, 'myPayrollPolicy'])
            ->name('employee.myPayrollPolicy');
    
        // Employee Salary Structure View
        Route::get('/my-salary-structure', [EmployeePayrollDashboardController::class, 'mySalaryStructure'])
            ->name('employee.mySalaryStructure');
        // Employee Salary Slip Archive
        Route::get('/my-salary-slips', [EmployeePayrollDashboardController::class, 'mySalarySlips'])
            ->name('employee.mySalarySlips');
        Route::get('/employee/salary-slip/download', [EmployeePayrollDashboardController::class, 'downloadSalarySlip'])
            ->name('employee.salary-slip.download');
        
        // department and employee routes
        Route::get('/get-payroll-departments', [ProvidentFundPolicyController::class, 'getPayrollDepartment']);
        Route::get('/get-payroll-departments-by-id/{departmentId}', [ProvidentFundPolicyController::class, 'getPayrollDepartmentsById']);
        // Route::get('/get-payroll-employee-by-id/{employeeId}', [ProvidentFundPolicyController::class, 'getPayrollEmployee']);
        Route::get('get-payroll/employees', [ProvidentFundPolicyController::class, 'getPayrollEmployee']);
        Route::get('get-payroll/employees/{employeeId}', [ProvidentFundPolicyController::class, 'getPayrollEmployee']);
        Route::get('/get-payroll-employee-by-department/{departmentId}', [ProvidentFundPolicyController::class, 'getEmployeesByDepartment']);
        //Salary Slips routes
        Route::post(
            '/salary-slips/store',
            [SalarySlipController::class, 'storeSalarySlip']
        );
        Route::get('/get-salary-slips', [SalarySlipController::class, 'getAllSalarySlips']);
        Route::get('/get-salary-slips/employee/{employee_id}', [SalarySlipController::class, 'getSalarySlipByEmployeeId']);
        
        Route::prefix('institute-admin/khata')->name('institute-admin.khata.')->group(function () {
                Route::get('/book', function () {
                return view('instituteAdmin/khatabook/khataBook');
            });
            Route::get('/', [KhataBookController::class, 'index'])->name('index');
            
            // API Routes
            Route::prefix('api')->name('api.')->group(function () {
                Route::get('/initial-data', [KhataBookController::class, 'getInitialData'])->name('initial-data');
                Route::post('/add-money', [KhataBookController::class, 'addMoney'])->name('add-money');
                Route::post('/add-transaction', [KhataBookController::class, 'addTransaction'])->name('add-transaction');
                Route::get('/transactions', [KhataBookController::class, 'getTransactions'])->name('transactions');
                Route::get('/customers', [KhataBookController::class, 'getCustomers'])->name('customers');
                Route::post('/add-customer', [KhataBookController::class, 'addCustomer'])->name('add-customer');
                Route::get('/customers/{id}', [KhataBookController::class, 'getCustomerDetails'])->name('customer.details');
                Route::post('/customers/{id}/transactions', [KhataBookController::class, 'addCustomerTransaction'])->name('customer.transactions');
                Route::post('/clear-all-data', [KhataBookController::class, 'clearAllData'])->name('clear-all-data');
                Route::get('/export-data', [KhataBookController::class, 'exportData'])->name('export-data');
               });
        });
        
    // Edit salary structure routes
    Route::get('/institute/admin/payroll/salary-structure/edit/{salaryStructureId}', [EmployeeSalaryStructureController::class, 'editSalaryStructure'])
        ->name('institute.payroll.salary.structure.edit');
    
    Route::put('/institute/admin/payroll/salary-structure/update/{salaryStructureId}', [EmployeeSalaryStructureController::class, 'updateSalaryStructure'])
        ->name('institute.payroll.salary.structure.update');
    
    Route::put('/institute/admin/payroll/salary-structure/allowances/{salaryStructureId}', [EmployeeSalaryStructureController::class, 'updateSalaryAllowances'])
        ->name('institute.payroll.salary.structure.allowances.update');
    
    Route::put('/institute/admin/payroll/salary-structure/deductions/{salaryStructureId}', [EmployeeSalaryStructureController::class, 'updateSalaryDeductions'])
        ->name('institute.payroll.salary.structure.deductions.update');
    
    Route::put('/institute/admin/payroll/salary-structure/preview/{salaryStructureId}', [EmployeeSalaryStructureController::class, 'updateSalaryPreview'])
        ->name('institute.payroll.salary.structure.preview.update');
        
    // Bonus & Overtime routes
    Route::post('/payroll-policy/bonus-overtime/save', [ProvidentFundPolicyController::class, 'saveBonusOvertime']);
    Route::get('/payroll-policy/{payrollPolicyId}/bonus-overtime', [ProvidentFundPolicyController::class, 'getBonusOvertime']);        
        
    Route::prefix('institute/admin/ctc-salary-configuration')->group(function () {

        Route::get('/salary-structure/create', [EmployeeSalaryStructureCtcController::class, 'createCtcSalaryStructure'])
            ->name('institute.ctc.salary-structure.create');
    
        Route::get('/salary-structure/edit/{salaryStructureId}', [EmployeeSalaryStructureCtcController::class, 'editCtcSalaryStructure'])
            ->name('institute.ctc.salary-structure.edit');
    

        Route::get('/get-employees-by-department-for-ctc-salary/{departmentId}', [EmployeeSalaryStructureCtcController::class, 'getCtcSalaryEmployeesByDepartment']);
        Route::get('/get-existing-ctc-structures', [EmployeeSalaryStructureCtcController::class, 'getCtcExistingStructuresByDepartment']);

        Route::post('/employee/ctc-salary-structure/save', [EmployeeSalaryStructureCtcController::class, 'storeCtcSalaryStructure']);

        Route::post('/sync-ctc-employment-type', [EmployeeSalaryStructureCtcController::class, 'syncCtcEmploymentTypeWithStructures']);
        
        Route::post('/employee/update-ctc-employment-type/{employeeId}', [EmployeeSalaryStructureCtcController::class, 'updateCtcEmployeeEmploymentType']);

        Route::get('/ctc-salary-management', [EmployeeSalaryStructureCtcController::class, 'viewCtcSalaryManagement'])
            ->name('institute.ctc.salary-management');
            
        Route::get('/ctc-salary-details/{salaryStructureId}', [EmployeeSalaryStructureCtcController::class, 'viewCtcSalaryDetails'])
            ->name('institute.ctc.salary.details');
    
        Route::get('/employee/{employeeId}/salary-structures', [EmployeeSalaryStructureCtcController::class, 'viewCtcEmployeeSalaryStructures'])
            ->name('institute.ctc.employee.salary.structures');
    
        Route::get('/employee/{employeeId}/salary-structures/download', [EmployeeSalaryStructureCtcController::class, 'downloadCtcEmployeeSalaryStructures'])
            ->name('institute.ctc.salary.structures.download');
    
    });
    
    Route::post('/ctc-salary-structure/ai-suggestion',
        [EmployeeSalaryStructureCtcController::class, 'ctcAiSuggestion'])
        ->name('ctc.salary.structure.ai.suggestion');        
        
    
    // visitor routes start
    Route::get('/institute/admin/visitor/check-in', function () { return view('instituteAdmin/VisitorManagement/checkInForm');})->name('admin.transport.create');
    Route::get('/institute/admin/visitor/attendant', function () { return view('instituteAdmin/VisitorManagement/employeeAttendant');})->name('admin.transport.create');
    Route::get('/institute/admin/visitor/front-desk', function () { return view('instituteAdmin/VisitorManagement/frontDeskForm');})->name('admin.transport.create');
    Route::get('/institute/admin/visitor/registration', function () { return view('instituteAdmin/VisitorManagement/newRegisterationForm');})->name('admin.transport.create');
    Route::get('/institute/admin/visitor/generate-gate-pass', function () { return view('instituteAdmin/VisitorManagement/generateGatePass');})->name('generate.gate.pass');
    Route::post('/visitor/register', [VisitorController::class, 'visitorRegister'])->name('visitor.register');
    Route::get('/visitor/register/list', [VisitorController::class, 'visitorRegister'])->name('show.visitor.register');
    Route::get('/institute/admin/visitor/check-out', function () { return view('instituteAdmin/VisitorManagement/checkOut');})->name('admin.transport.create');
    // Visitor Routes
    Route::prefix('visitors')->name('visitors.')->group(function () {
        Route::get('/', [VisitorController::class, 'index'])->name('index');
        // Route::get('/create', [VisitorController::class, 'create'])->name('create');
        // Route::post('/', [VisitorController::class, 'store'])->name('store');
        Route::get('/{id}', [VisitorController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [VisitorController::class, 'edit'])->name('edit');
        Route::put('/{id}', [VisitorController::class, 'update'])->name('update');
        Route::delete('/{id}', [VisitorController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/check-in', [VisitorController::class, 'checkIn'])->name('check-in');
        Route::post('/{id}/check-out', [VisitorController::class, 'checkOut'])->name('check-out');
        Route::post('/check-in', [VisitorController::class, 'visitorcheckIn'])->name('check-visitor-in');
        Route::post('/fetch-by-code', [VisitorController::class, 'fetchByCode'])->name('fetch-by-code');
    
        Route::post('/visitor-attendent-log', [VisitorAttendentLogController::class, 'visitorLogsStore'])->name('visitor-logs-store');
        Route::post('/checkout', [CheckOutController::class, 'visitorCheckoutStore'])->name('visitor-check-out');
        Route::post('/meetings', [VisitorAttendentLogController::class, 'storeMeeting']);
        Route::post('/get-meetings', [VisitorAttendentLogController::class, 'getMeetingSchedule']);
        Route::post('/generate-out-pass', [generatePassController::class, 'generateOutPass']);
        // Route::post('/get-attendent-employee', [VisitorAttendentLogController::class, 'getVisitorEmployee']);
        Route::get('/employee/{employeeId}', [VisitorAttendentLogController::class, 'getVisitorEmployee']);
        Route::post('/meetings/{meetingId}/confirmation', [VisitorAttendentLogController::class, 'updateAttendentVerification']);
    });
    Route::get('/get-departments', [VisitorAttendentLogController::class, 'getVisitorDepartment']);
    // end visitor
    Route::prefix('notice-board')->group(function () {
        Route::get('/create', [NoticeController::class, 'create'])->name('notice-board.create');
        Route::get('/view', [NoticeController::class, 'view'])->name('notice-board.view');
        Route::post('/', [NoticeController::class, 'store'])->name('notice-board.store');
        Route::get('/{id}/edit', [NoticeController::class, 'edit'])->name('notice.board.edit');
        Route::put('/{id}', [NoticeController::class, 'update'])->name('notice-board.update');
    });
        
    Route::get('/course/fee-structure/{fee_structure_id}/edit', [EditCourseFeeStructureController::class, 'editCourseFeeStructure'])->name('course.fee.structure.edit');
    Route::put('/course/fee-structure/{fee_structure_id}', [EditCourseFeeStructureController::class, 'updateCourseFeeStructure'])->name('course.fee.structure.update');
        
    Route::get('/batch-details', [EditCourseFeeStructureController::class, 'viewBatchDetails'])
        ->name('batch.details.view');
    
    Route::prefix('home-work')->group(function () {
        Route::get('/create', [HomeworkController::class, 'create'])->name('homework.create');
        
        // AJAX routes
        Route::post('/store', [HomeworkController::class, 'store'])->name('homework.store');
        Route::post('/assign', [HomeworkController::class, 'assign'])->name('homework.assign');
        Route::get('/list', [HomeworkController::class, 'getHomeworks'])->name('homework.list');
        Route::get('/{id}', [HomeworkController::class, 'show'])->name('homework.show');
        Route::put('/{id}', [HomeworkController::class, 'update'])->name('homework.update');
        Route::delete('/{id}', [HomeworkController::class, 'destroy'])->name('homework.destroy');
    });
    
    // Discount routes
    Route::prefix('discounts')->group(function () {
        Route::get('/', [DiscountController::class, 'index'])->name('discounts.list');
        Route::get('/create', [DiscountController::class, 'create'])->name('discounts.create');
        Route::post('/', [DiscountController::class, 'store'])->name('discounts.store');
        Route::get('/get-custom-fees', [DiscountController::class, 'getCustomFees'])
        ->name('get.custom.fees');
    });
    
    // Add this route in web.php
    Route::get('/ajax/transport-available-discounts', [DiscountController::class, 'getAvailableTransportDiscounts'])
        ->name('ajax.transport.available.discounts');
    
    Route::prefix('course-fee-discount')->group(function () {
      Route::get('/form', [AssignDiscountonCourseFeeController::class, 'showDiscountForm'])->name('course.fee.discount.form');
      Route::get('/get-course-fee-structure', [AssignDiscountonCourseFeeController::class, 'getCourseFeeStructure'])->name('get.course.fee.structure');
      Route::get('/get-available-course-discounts', [AssignDiscountonCourseFeeController::class, 'getAvailableCourseDiscountsAjax'])->name('ajax.discounts.course');
     Route::post('/assign-discount-to-students', [AssignDiscountonCourseFeeController::class, 'assignDiscountToStudents'])->name('course-fee.discount.assign');
    });
    Route::get('/assigned-discounts', [AssignDiscountonCourseFeeController::class, 'showAssignedDiscounts'])
    ->name('course-fee.discounts.assigned');
    Route::post('/revoke-discount/{id}', [AssignDiscountonCourseFeeController::class, 'revokeDiscount'])
    ->name('course-fee.discount.revoke');
    Route::get('/ajax/discounts/course', [AssignDiscountonCourseFeeController::class, 'getAvailableDiscounts'])->name('ajax.discounts.course');
    
    Route::get('main-form', function () { return view('instituteAdmin/RegistrationSystem/MainForm');})->name('admin.main.create');
    Route::get('admission-form',[RegistrationController::class, 'getAdmissionForm'])->name('admin.admission.create');
    Route::get('interview-form',[RegistrationController::class, 'getInterviewForm'])->name('admin.interview.create');
    Route::post('/save-admission', [RegistrationController::class, 'saveAdmission']);
    Route::post('/update-admission/{id}', [AdmissionFormController::class, 'update'])->name('update.admission');
    Route::post('/save-interview', [RegistrationController::class, 'saveInterview']);
    Route::post('/save-preferences', [RegistrationController::class, 'savePreferences']);
    Route::get('lead-created', function () { return view('instituteAdmin/CreateLead/LeadCreated');})
    ->name('admin.leads.create');
    Route::get('/get-profiles-by-department/{department}', [RegistrationController::class, 'getProfilesByDepartment'])->name('get.profiles.by.department');
    Route::post('/get-skills-by-selection', [RegistrationController::class, 'getSkillsBySelection'])->name('get.skills.by.selection');
    
    Route::get('/interview-enroll',[RegistrationController::class, 'getInterviewEnroll'])->name('admin.interview.enroll');
    
    Route::prefix('leads')->group(function () {
        // 1. FIRST: Static routes
        Route::get('/', [LeadsController::class, 'index'])->name('leads.index');
        Route::post('/', [LeadsController::class, 'store'])->name('leads.store');
        Route::get('/stats', [LeadsController::class, 'stats'])->name('leads.stats');
        Route::get('/counsellor', [LeadsController::class, 'counselorView'])->name('leads.counselor');
        Route::get('/agent', [LeadsController::class, 'agentView'])->name('leads.agent.view');
        Route::post('/{lead}/remarks', [LeadsController::class, 'saveRemarks'])->name('leads.remarks');
        // 2. SECOND: The show route for viewing leads
        Route::get('/{lead}', [LeadsController::class, 'show'])->name('leads.show');
        // 3. THIRD: Other dynamic routes (must come AFTER show route)
        Route::get('/{lead}/edit', [LeadsController::class, 'edit'])->name('leads.edit');
        Route::put('/{lead}', [LeadsController::class, 'update'])->name('leads.update');
        Route::delete('/{lead}', [LeadsController::class, 'destroy'])->name('leads.destroy');
        // Field-specific update routes
        Route::patch('/{lead}/status', [LeadsController::class, 'updateStatus'])->name('leads.update.status');
        Route::patch('/{lead}/assignee', [LeadsController::class, 'updateAssignee'])->name('leads.update.assignee');
        Route::patch('/{lead}/followup', [LeadsController::class, 'updateFollowup'])->name('leads.update.followup');
        Route::patch('/{lead}/name', [LeadsController::class, 'updateName'])->name('leads.update.name');
        // Other routes
        Route::get('/{lead}/json', [LeadsController::class, 'getLeadJson'])->name('leads.json');
        Route::get('/counselor/leads/{id}', [LeadsController::class, 'counselorShow'])->name('leads.counselor.view');
        Route::get('/lead-student-view/{id}', [LeadsController::class, 'studentView'])->name('lead.student.view');
        // Existing routes (kept for backward compatibility)
        Route::post('/{lead}/assign-counselor', [LeadsController::class, 'assignCounselor'])->name('leads.assign.counselor');
        Route::post('/{lead}/update-followup', [LeadsController::class, 'updateFollowup'])->name('leads.update.followup.old');
        Route::post('/{lead}/update-steps', [LeadsController::class, 'updateSteps'])->name('leads.update.steps');
        Route::post('/{lead}/move-step', [LeadsController::class, 'moveStep'])->name('leads.move.step');
        Route::post('/{lead}/complete-step', [LeadsController::class, 'completeStep'])->name('leads.complete.step');
    });

    
    Route::post('/leads/{lead}/update-test-marks', [LeadsController::class, 'updateTestMarks'])->name('leads.update-test-marks');
    Route::post('/leads/{id}/update-registration-fee', [LeadsController::class, 'updateRegistrationFee'])->name('leads.updateRegistrationFee');
    Route::post('/leads/{id}/update-entrance-fee', [LeadsController::class, 'updateEntranceFee'])->name('leads.updateEntranceFee');
    Route::post('/leads/{id}/update-step-status', [LeadsController::class, 'updateStepStatus'])->name('leads.update-step-status');
    Route::post('/leads/{id}/update-contact', [LeadsController::class, 'updateContact'])->name('leads.update-contact');

    // Route::get('/lead-view/{id}', [LeadsController::class, 'show'])->name('lead.view');

    Route::prefix('institute-admin')->group(function () {
        Route::post('/leads/{id}/update-contact', [LeadsController::class, 'updateContact'])->name('institute-admin.leads.update-contact');
    });
    
    // Route::get('/lead-view/{id}', [LeadsController::class, 'show'])->name('lead.view');
    // Route::post('/leads/{id}/assign-counselor', [LeadsController::class, 'assignCounselor'])->name('leads.assign-counselor');
    // Route::post('/leads/{id}/update-followup', [LeadsController::class, 'updateFollowup'])->name('leads.update-followup');
    // Route::post('/leads/{id}/update-steps', [LeadsController::class, 'updateSteps'])->name('leads.update-steps');
    // Route::post('/leads/{id}/move-step', [LeadsController::class, 'moveStep'])->name('leads.move-step');
    // Route::post('/leads/{id}/complete-step', [LeadsController::class, 'completeStep'])->name('leads.complete-step');
    
    Route::get('interview-configuration', [InterviewConfigurationController::class, 'getInterviewConfiguration'])
            ->name('configuration.interview');
    // Save data
    Route::post('interview-configuration/save', [InterviewConfigurationController::class, 'store']);

    // Get all configurations
    Route::get('interview-configuration/all', [InterviewConfigurationController::class, 'index']);

    // Get single configuration
    Route::get('interview-configuration/get/{id}', [InterviewConfigurationController::class, 'show']);

    // Update configuration
    Route::post('interview-configuration/update/{id}', [InterviewConfigurationController::class, 'update']);

    // Delete configuration
    Route::get('interview-configuration/delete/{id}', [InterviewConfigurationController::class, 'destroy']);
    
    Route::get('interview-configuration/find', [InterviewConfigurationController::class, 'findByDepartmentAndYear']);

    Route::prefix('interview-leads')->name('interview.')->group(function () {
        // Get interview leads data
        Route::get('/', [LeadsController::class, 'getInterviewData'])
            ->name('data');
            
        // Update interview lead status
        Route::post('/{id}/status', [LeadsController::class, 'updateInterviewStatus'])
            ->name('update.status');
        
        // Update interview lead follow-up
        Route::post('/{id}/followup', [LeadsController::class, 'updateInterviewFollowup'])
            ->name('update.followup');
        
        // Upload resume for interview lead
        Route::post('/{id}/upload-resume', [LeadsController::class, 'uploadInterviewResume'])
            ->name('upload.resume');
            
        Route::get('/{id}/logs', [LeadsController::class, 'getInterviewEditLogs'])
            ->name('log');
        
        // Get interview lead details
        Route::get('/{id}', [LeadsController::class, 'getInterviewLead'])
            ->name('show');
        
        // Get interview stats
        Route::get('/stats', [LeadsController::class, 'getInterviewStats'])
            ->name('stats');
    });
    Route::get('interviewer', [LeadsController::class, 'getHRInterviewData'])->name('hr.interview.data');

    // Candidate Interview Journey Routes
    Route::get('/candidate-view/{leadId}', [LeadsController::class, 'candidateView'])->name('candidate.view');
    // Add to mannual Roles sets end
    // Route::post('/institute-admin/round-status/initialize/{leadId}', [RoundStatusController::class, 'initializeRoundStatus']);
    // // Route::post('/institute-admin/round-status/initialize-all', [RoundStatusController::class, 'initializeAllRoundStatus']);
    // Route::get('/institute-admin/leads/{id}', [LeadsController::class, 'showInterviewLeadDetails'])->name('institute-admin.leads.show');
    // Route::post('/round-status/{id}/status', [LeadsController::class, 'updateRoundStatus'])->name('round.status.update');
    // // For web routes
    // // Route::post('/round-status/{id}/status', [RoundStatusController::class, 'updateStatus'])->name('round-status.update');
    // Route::get('/round-status/lead/{leadId}', [RoundStatusController::class, 'getRoundsByLead'])->name('round-status.by-lead');
    // Route::post('/round-status/bulk-update', [RoundStatusController::class, 'bulkUpdate'])->name('round-status.bulk-update');
    // Route::post('/interview-leads/{id}/journey-status', [App\Http\Controllers\institute\Admin\LeadsController::class, 'updateJourneyStatus'])->name('leads.update-journey-status');
    // Route::post('/round-status/{id}/marks', [RoundStatusController::class, 'updateMarks'])->name('round-status.update-marks');
    
    Route::post('/institute-admin/round-status/initialize/{leadId}', [RoundStatusController::class, 'initializeRoundStatus']);
    // Route::post('/institute-admin/round-status/initialize-all', [RoundStatusController::class, 'initializeAllRoundStatus']);
    Route::get('/institute-admin/leads/{id}', [LeadsController::class, 'showInterviewLeadDetails'])->name('institute-admin.leads.show');
    Route::post('/round-status/{id}/status', [LeadsController::class, 'updateRoundStatus'])->name('round.status.update');
    // For web routes
    // Route::post('/round-status/{id}/status', [RoundStatusController::class, 'updateStatus'])->name('round-status.update');
    Route::get('/round-status/lead/{leadId}', [RoundStatusController::class, 'getRoundsByLead'])->name('round-status.by-lead');
    Route::post('/round-status/bulk-update', [RoundStatusController::class, 'bulkUpdate'])->name('round-status.bulk-update');
    Route::post('/interview-leads/{id}/journey-status', [App\Http\Controllers\institute\Admin\LeadsController::class, 'updateJourneyStatus'])->name('leads.update-journey-status');
    Route::post('/round-status/{id}/marks', [RoundStatusController::class, 'updateMarks'])->name('round-status.update-marks');
    Route::post('/round-status/{id}/start', [RoundStatusController::class, 'startRound'])->name('round-status.start');
    Route::post('/round-status/{id}/complete', [RoundStatusController::class, 'completeRound'])->name('round-status.complete');
    Route::post('/round-status/{id}/reschedule', [RoundStatusController::class, 'rescheduleRound'])->name('round-status.reschedule');
    
    Route::prefix('admission-process')->group(function () {
        Route::get('/configuration', [AdmissionProcessConfigurationController::class, 'index'])->name('admission-process.config');
        Route::get('/get-config', [AdmissionProcessConfigurationController::class, 'getConfig'])->name('admission-process.get-config');
        Route::post('/save', [AdmissionProcessConfigurationController::class, 'save'])->name('admission-process.save');
        Route::post('/toggle/{step}', [AdmissionProcessConfigurationController::class, 'toggleStep'])->name('admission-process.toggle-step');
        Route::post('/reset', [AdmissionProcessConfigurationController::class, 'resetToDefault'])->name('admission-process.reset');
    });
    
    Route::prefix('institute-admin')->group(function () {
        Route::get('certificates/school-leaving', [CertificatesController::class, 'schoolLeavingCertificate'])->name('certificates.schoolLeavingIndex');
        Route::get('/certificates', [CertificatesController::class, 'index'])->name('certificates.index');
        Route::get('/certificates/character', [CertificatesController::class, 'characterCertificate'])->name('certificates.character');
        Route::post('/certificates/character/generate', [CertificatesController::class, 'generateCharacter'])->name('certificates.character.generate');
        Route::post('/certificates/character/store', [CertificatesController::class, 'storeCharacter'])->name('certificates.character.store');
        Route::get('/certificates/get-student-details/{searchId}', [CertificatesController::class, 'getStudentDetails'])->name('certificates.getStudentDetails');
        Route::get('/certificates/get-hod/{studentHashId}', [CertificatesController::class, 'getHod'])->name('certificates.getHod');
        // Certificate Routes - Course Completion Certificate
        Route::get('/certificates/course-completion', [CertificatesController::class, 'courseCompletionCertificate'])->name('certificates.course-completion');
        Route::post('/certificates/course-completion/generate', [CertificatesController::class, 'generateCourseCompletion'])->name('certificates.course-completion.generate');
        Route::post('/certificates/course-completion/store', [CertificatesController::class, 'storeCourseCompletion'])->name('certificates.course-completion.store');
        // Certificate Routes - Trainer Certificate
        Route::get('/certificates/transfer', [CertificatesController::class, 'transferCertificate'])->name('certificates.transfer');
        Route::post('/certificates/transfer/generate', [CertificatesController::class, 'generateTransfer'])->name('certificates.transfer.generate');
        Route::post('/certificates/transfer/store', [CertificatesController::class, 'storeTransfer'])->name('certificates.transfer.store');
        // Certificate Routes - School Leaving Certificate
        Route::get('/certificates/school-leaving', [CertificatesController::class, 'schoolLeavingCertificate'])->name('certificates.schoolLeaving');
        Route::post('/certificates/school-leaving/store', [CertificatesController::class, 'storeSchoolLeaving'])->name('certificates.schoolLeaving.store');
        // Unified Student Details Fetching API
        Route::get('/certificates/student/{searchId}', [CertificatesController::class, 'getStudentDetails'])->name('certificates.getStudentDetails');
        Route::post('/certificates/generate', [CertificatesController::class, 'generate'])->name('certificates.generate');
        // Certificate Routes - Bonafide Certificate
        Route::get('/bonafide', [BonafideCertificateController::class, 'index'])->name('certificates.bonafide');
        Route::post('/certificates/store', [BonafideCertificateController::class, 'store']);
        Route::get('/certificates/get-bonafide-student-details/{query}', [BonafideCertificateController::class, 'getStudentDetails'])->name('certificates.bonafide.getStudentDetails');
        Route::get('/certificates/get-student-details/{query}', [BonafideCertificateController::class, 'getStudentDetails']);
        
        Route::get('/certificates/no-due', function () {return view('instituteAdmin.Certificates.nodue');})->name('certificates.noDue');
        Route::post('/certificates/no-due/store', [BonafideCertificateController::class, 'storeNoDue'])->name('certificates.noDue.store');
    });
    
    // Certificate Routes - Outside prefix for direct access
    Route::get('/bonafide', [BonafideCertificateController::class, 'index'])->name('certificates.bonafide');
    Route::get('/certificates/character', [CertificatesController::class, 'characterCertificate'])->name('certificates.character');
    Route::get('/certificates/course-completion', [CertificatesController::class, 'courseCompletionCertificate'])->name('certificates.course-completion');
    Route::get('/certificates/transfer', [CertificatesController::class, 'transferCertificate'])->name('certificates.transfer');
    Route::get('/certificates/school-leaving', [CertificatesController::class, 'schoolLeavingCertificate'])->name('certificates.school-leaving');
    
    // Certificate API Routes - Outside prefix for direct access
    Route::get('/certificates/get-student-details/{searchId}', [CertificatesController::class, 'getStudentDetails'])->name('certificates.getStudentDetails');
    Route::get('/certificates/get-hod/{studentHashId}', [CertificatesController::class, 'getHod'])->name('certificates.getHod');
    
    // Certificate Store Routes - Outside prefix for direct access (POST)
    Route::post('/certificates/character/store', [CertificatesController::class, 'storeCharacter'])->name('certificates.character.store');
    Route::post('/certificates/course-completion/store', [CertificatesController::class, 'storeCourseCompletion'])->name('certificates.course-completion.store');
    Route::post('/certificates/transfer/store', [CertificatesController::class, 'storeTransfer'])->name('certificates.transfer.store');
    Route::post('/certificates/school-leaving/store', [CertificatesController::class, 'storeSchoolLeaving'])->name('certificates.school-leaving.store');
    Route::post('/certificates/store', [BonafideCertificateController::class, 'store'])->name('certificates.bonafide.store');
    
    // Certificate View Routes - Outside prefix for direct access
    Route::get('/certificates-view', [certificateViewController::class, 'index'])->name('certificates.view');
    Route::post('/certificates/filter', [certificateViewController::class, 'filter'])->name('certificates.filter');
    Route::post('/certificates/download', [certificateViewController::class, 'download'])->name('certificates.download');
    Route::post('/certificates/update-count', [certificateViewController::class, 'updateDownloadCount'])->name('certificates.updateCount');
    Route::get('/institute-admin/certificates/get-data', [certificateViewController::class, 'getCertificateData'])->name('certificates.getDataQuery');
    Route::get('/institute-admin/certificates/get-data/{cert_number}', [certificateViewController::class, 'getCertificateData'])->name('certificates.getData')->where('cert_number', '.*');
    Route::post('/certificates/view-image', [certificateViewController::class, 'viewImage'])->name('certificates.view-image');
    
    Route::get('/admission-enroll', [AdmissionFormController::class, 'create'])->name('admission.form');
    Route::post('/enrollment/store', [AdmissionFormController::class, 'store'])->name('enrollment.store');
    Route::get('/admin/admission/edit/{id}', [AdmissionFormController::class, 'edit'])->name('admin.admission.edit');
    Route::post('/admin/admission/update/{id}', [AdmissionFormController::class, 'update'])->name('admin.admission.update');
    Route::get('/admin/admission/logs/{leadId}', [AdmissionFormController::class, 'getEditLogs'])->name('admin.admission.logs');
    
    
    //otp
    Route::post('/send-email-otp', [AdmissionFormController::class, 'sendEmailOtp'])->name('send.email.otp');
    Route::post('/verify-email-otp', [AdmissionFormController::class, 'verifyEmailOtp'])->name('verify.email.otp');
    Route::post('/send-interview-email-otp', [RegistrationController::class, 'sendInterviewEmailOtp'])->name('send.interview.email.otp');
    Route::post('/verify-interview-email-otp', [RegistrationController::class, 'verifyInterviewEmailOtp'])->name('verify.interview.email.otp');
    
    // Duplicate Routes
    // Route::get('/admission', [AdmissionFormController::class, 'create'])->name('admission.form');
    // Route::post('/enrollment/store', [AdmissionFormController::class, 'store'])->name('enrollment.store');
    // Route::get('/students/edit', [EditStudentDetailsController::class, 'editstudentdetails'])->name('student.edit');
    // Route::get('/student/profile', [EditStudentDetailsController::class, 'studentProfile'])->name('student.profile');
    
    Route::get('/students/edit', [EditStudentDetailsController::class, 'editstudentdetails'])->name('student.edit');
    Route::get('/student/profile', [EditStudentDetailsController::class, 'studentProfile'])->name('student.profile');
    // Route::get('certificates-view', function () {
    //     return view('instituteAdmin/Certificates/certificatesView');
    // });
    Route::post('/certificates/download-logs', [CertificatesController::class, 'getDownloadLogs'])->name('certificates.download-logs');
    
    Route::get('/preview-tab', [PreviewController::class, 'index'])->name('config.list');
    Route::get('/preview-detail/{id}', [PreviewController::class, 'show'])->name('config.details');
    Route::resource('roles', RolesForAdmissionInterviewController::class);
    Route::get('/roles/{id}/edit', [RolesForAdmissionInterviewController::class, 'edit'])->name('roles.edit');
    // AJAX routes
    Route::get('/ajax/load-classes-by-department', [RolesForAdmissionInterviewController::class, 'loadClassesByDepartment'])->name('ajax.load-classes');
    Route::get('/ajax/employees-by-type', [RolesForAdmissionInterviewController::class, 'getEmployeesByType'])->name('ajax.employees-by-type');
    
        Route::get('/institute/admin/addclassroom', function () {
        return view('instituteAdmin/CreateBuildings/addClassroom');
    });
    Route::get('/institute/admin/building-management-system', function () {
        return view('instituteAdmin/CreateBuildings/buildingManagement');
    });
    
    Route::get('/institute/admin/building-management-main-menu', function () {
        return view('instituteAdmin/CreateBuildings/buildingManagementMainMenu');
    })->name('infrastructure.main');
    // Route::get('/institute/admin/building-management-buildings', function () {
    //     return view('instituteAdmin/CreateBuildings/buildingManagementBuildings');
    // })->name('buildings.page');
    Route::get('/campus/infrastructure', [AddBuildingController::class, 'infrastructure'])->name('campus.infrastructure');
    Route::get('/institute/admin/building-management-buildings', [AddBuildingController::class, 'Buildingpage'])->name('buildings.page');
    Route::get('/institute/admin/building-management-blocks', [AddBlockController::class, 'page'])->name('blocks.page');
    Route::get('/institute/admin/building-management-floors', [AddFloorController::class, 'page'])->name('floors.page');
    Route::get('/institute/admin/building-management-rooms', [AddRoomsController::class, 'showRoomsPage'])->name('rooms.page');
    Route::get('/institute/admin/building-management-washrooms', function () {
        return view('instituteAdmin/CreateBuildings/buildingManagementWashrooms');
    })->name('washrooms.page');
    Route::get('/institute/admin/building-management-lifts', function () {
        return view('instituteAdmin/CreateBuildings/buildingManagementLifts');
    })->name('lifts.page');
    Route::get('/institute/admin/building-management-rooms-names', function () {
        return view('instituteAdmin/CreateBuildings/buildingManagementRoomNames');
    })->name('room-names.page');
    Route::get('/institute/admin/building-management-summary', function () {
        return view('instituteAdmin/CreateBuildings/buildingManagementSummary');
    })->name('summary.page');
    // Building List Page
    Route::get('/list-buildings', function () {
        return view('instituteAdmin/CreateBuildings/buildingsList');
    })->name('buildings.list');

    // Building Details Page
    Route::get('/view-building/{id}', [AddBuildingController::class, 'showData'])->name('buildings.view.details');

    // Route::get('/buildings/{id}/edit', function ($id) {
    // return view('instituteAdmin/CreateBuildings/buildingsEdit');
    // })->name('buildings.edit');
    Route::get('/buildings/{id}/edit', [AddBuildingController::class, 'edit'])->name('buildings.edit');
    // Building Edit Page - GET route using controller
    // Route::get('/buildings/{id}/edit', [AddBuildingController::class, 'edit'])->name('buildings.edit');

    Route::get('/list-blocks', function () {
        return view('instituteAdmin/CreateBuildings/blocksList');
    })->name('blocks.list');
    // Block Details Page
    Route::get('/view-block/{id}', [AddBlockController::class, 'showDetails'])->name('blocks.view.details');
    // Block Edit Page
    // Route::get('/blocks/{id}/edit', function ($id) {
    // return view('instituteAdmin/CreateBuildings/blocksEdit');
    // })->name('blocks.edit');
    Route::get('/blocks/{id}/edit', [AddBlockController::class, 'edit'])->name('blocks.edit');
// Route::get('/institute/admin/view-floors', function () { return view('instituteAdmin/CreateBuildings/floorsView'); })->name('floors.view');
    // Floor List Page
    Route::get('/list-floors', function () {
        return view('instituteAdmin/CreateBuildings/floorsList');
    })->name('floors.list');
    // Floor Details Page
    Route::get('/view-floor/{id}', [AddFloorController::class, 'showDetails'])->name('floors.view.details');
    // Route::get('/floors/{id}/edit', function ($id) { return view('instituteAdmin/CreateBuildings/floorsEdit'); })->name('floors.edit');
    Route::get('/floors/{id}/edit', [AddFloorController::class, 'edit'])->name('floors.edit');
    Route::get('/list-rooms', function () {
        return view('instituteAdmin/CreateBuildings/roomsList');
    })->name('rooms.list');
    // Room Details Page
    Route::get('/view-room/{id}', [AddRoomsController::class, 'showDetails'])->name('rooms.view.details');
    // Route::get('/rooms/{id}/edit', function ($id) {
    // return view('instituteAdmin/CreateBuildings/roomsEdit', ['roomId' => $id]);
    // })->name('rooms.edit');
    Route::get('/rooms/{id}/edit', [AddRoomsController::class, 'edit'])->name('rooms.edit');

    Route::post('/buildings/sync-campus', [AddBuildingController::class, 'syncCampusToBuilding'])
    ->name('buildings.syncCampus');

    Route::prefix('buildings')->group(function () {
        Route::get('/', [AddBuildingController::class, 'index']);
        Route::post('/', [AddBuildingController::class, 'store']);
        Route::get('/{id}', [AddBuildingController::class, 'show']);
        Route::put('/{id}', [AddBuildingController::class, 'update']);
        Route::delete('/{id}', [AddBuildingController::class, 'destroy']);
        Route::post('/{id}/restore', [AddBuildingController::class, 'restore']);
        Route::delete('/{id}/force', [AddBuildingController::class, 'forceDelete']);
        Route::get('/statistics', [AddBuildingController::class, 'statistics']);
        Route::get('/search', [AddBuildingController::class, 'search']);
    });
    Route::prefix('blocks')->group(function () {
        Route::get('/', [AddBlockController::class, 'index']);
        Route::post('/', [AddBlockController::class, 'store']);
        Route::get('/{id}', [AddBlockController::class, 'show']);
        Route::put('/{id}', [AddBlockController::class, 'update']);
        Route::delete('/{id}', [AddBlockController::class, 'destroy']);
        Route::post('/{id}/restore', [AddBlockController::class, 'restore']);
        Route::post('/{id}/force-delete', [AddBlockController::class, 'forceDelete']);
        Route::get('/statistics', [AddBlockController::class, 'statistics']);
        Route::get('/by-building/{buildingId}', [AddBlockController::class, 'getByBuilding']);
        Route::post('/{id}/update-status', [AddBlockController::class, 'updateStatus']);
        Route::get('/search', [AddBlockController::class, 'search']);
    });
    Route::get('/blocks/{id}/details', [AddBlockController::class, 'getBlockWithAllocations'])->name('blocks.details');
    Route::prefix('floors')->group(function () {
        Route::get('/', [AddFloorController::class, 'index']);
        Route::post('/bulk', [AddFloorController::class, 'store']);
        Route::get('/{id}', [AddFloorController::class, 'show']);
        Route::put('/{id}', [AddFloorController::class, 'update']);
        Route::delete('/{id}', [AddFloorController::class, 'destroy']);
        Route::post('/{id}/restore', [AddFloorController::class, 'restore']);
        Route::post('/{id}/force-delete', [AddFloorController::class, 'forceDelete']);
        Route::get('/statistics', [AddFloorController::class, 'statistics']);
        Route::get('/by-building/{buildingId}', [AddFloorController::class, 'getByBuilding']);
        Route::get('/by-block/{blockId}', [AddFloorController::class, 'getByBlock']);
        Route::get('/blocks-by-building/{buildingId}', [AddFloorController::class, 'getBlocksByBuilding']);
        Route::post('/{id}/update-status', [AddFloorController::class, 'updateStatus']);
        Route::post('/{id}/update-room-stats', [AddFloorController::class, 'updateRoomStats']);
        Route::get('/search', [AddFloorController::class, 'search']);
        Route::get('/{floorId}/details', [AddRoomsController::class, 'getByFloor']);
    });
    // Building routes - Add this BEFORE the existing room routes
    Route::prefix('buildings')->group(function () {
    Route::get('/all', [AddBuildingController::class, 'getAllBuildings']);
    Route::get('/{id}', [AddBuildingController::class, 'show']);
    Route::get('/', [AddBuildingController::class, 'index']);
    Route::post('/', [AddBuildingController::class, 'store']);
    Route::post('/sync', [AddBuildingController::class, 'syncCampusToBuilding']);
    Route::put('/{id}', [AddBuildingController::class, 'update']);
    Route::delete('/{id}', [AddBuildingController::class, 'destroy']);
    });
    Route::prefix('rooms')->group(function () {
        Route::get('/', [AddRoomsController::class, 'index']);
        Route::post('/bulk', [AddRoomsController::class, 'bulkStore']);
        Route::get('/{id}', [AddRoomsController::class, 'show']);
        Route::put('/{id}', [AddRoomsController::class, 'update']);
        Route::delete('/{id}', [AddRoomsController::class, 'destroy']);

        // Room-specific routes
        Route::get('/building/{buildingId}', [AddRoomsController::class, 'getByBuilding']);
        Route::get('/block/{blockId}', [AddRoomsController::class, 'getByBlock']);
        Route::get('/by-floor/{floorId}', [AddRoomsController::class, 'getByFloor']);
        Route::patch('/{id}/status', [AddRoomsController::class, 'updateStatus']);
        Route::patch('/{id}/occupancy-status', [AddRoomsController::class, 'updateOccupancyStatus']);
        Route::get('/maintenance/due', [AddRoomsController::class, 'getMaintenanceDue']);
        Route::get('/search', [AddRoomsController::class, 'search']);
        Route::get('/statistics', [AddRoomsController::class, 'statistics']);

        // Additional room routes
        Route::get('/blocks/{buildingId}', [AddRoomsController::class, 'getBlocks']);
        Route::get('/floors-by-block/{blockId}', [AddRoomsController::class, 'getFloorsByBlock']);
        Route::get('/search-rooms', [AddRoomsController::class, 'searchRooms']);
        Route::get('/room-statistics', [AddRoomsController::class, 'getRoomStatistics']);

        // Restore and force delete
        Route::post('/{id}/restore', [AddRoomsController::class, 'restore']);
        Route::delete('/{id}/force', [AddRoomsController::class, 'forceDelete']);
    });
    Route::get('/room-types', [AddRoomsController::class, 'getRoomTypes'])->name('room.types');
    
    Route::middleware('auth')->prefix('duties')->name('institute.duties.')->group(function () {
        Route::get('/', [AssignDutiesController::class, 'index'])->name('index');
        Route::get('/create', [AssignDutiesController::class, 'create'])->name('create');
        Route::post('/', [AssignDutiesController::class, 'store'])->name('store');
        Route::get('/{id}', [AssignDutiesController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AssignDutiesController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AssignDutiesController::class, 'update'])->name('update');
        Route::delete('/{id}', [AssignDutiesController::class, 'destroy'])->name('destroy');
        Route::post('/duty-type', [AssignDutiesController::class, 'storeDutyType'])->name('duty-type.store');  
    });
    Route::get('/institute/admin/out-pass-generate', function () {
        return view('instituteAdmin/VisitorManagement/outPassGenerate');
    });
    
    // Out Pass routes
    Route::prefix('out-pass')->name('out-pass.')->group(function () {
        Route::post('/submit', [OutPassController::class, 'storeFromForm'])->name('submit');
        Route::post('/approve/{passCode}', [OutPassController::class, 'approvePass'])->name('approve');
        Route::post('/reject/{passCode}', [OutPassController::class, 'rejectPass'])->name('reject');
        Route::post('/cancel/{passCode}', [OutPassController::class, 'cancelPass'])->name('cancel');
        Route::post('/return/{passCode}', [OutPassController::class, 'markReturned'])->name('return');
        Route::post('/save-pdf', [OutPassController::class, 'savePdfGeneration'])->name('save-pdf');
        Route::get('/history/{passCode}', [OutPassController::class, 'getHistory'])->name('history');
        Route::get('/statistics', [OutPassController::class, 'statistics'])->name('statistics');
        Route::get('/search', [OutPassController::class, 'search'])->name('search');
        Route::post('/check-expired', [OutPassController::class, 'checkExpired'])->name('check-expired');
    });
    // Student routes
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [OutPassStudentsController::class, 'index'])->name('out-pass.index');
        Route::get('/create', [OutPassStudentsController::class, 'create'])->name('create');
        Route::post('/store', [OutPassStudentsController::class, 'store'])->name('store');
        Route::get('/{id}', [OutPassStudentsController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [OutPassStudentsController::class, 'edit'])->name('edit');
        Route::put('/{id}', [OutPassStudentsController::class, 'update'])->name('update');
        Route::delete('/{id}', [OutPassStudentsController::class, 'destroy'])->name('destroy');
          // AJAX routes
        Route::post('/fetch', [OutPassStudentsController::class, 'fetchStudent'])->name('fetch');
        Route::get('/search', [OutPassStudentsController::class, 'search'])->name('search');
        Route::patch('/{id}/toggle-status', [OutPassStudentsController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/class/{class}', [OutPassStudentsController::class, 'getByClass'])->name('by-class');
        Route::get('/statistics', [OutPassStudentsController::class, 'statistics'])->name('statistics');
    });
    
    // Employee routes
    Route::prefix('employees')->name('employees.out-pass')->group(function () {
        Route::get('/', [OutPassEmployeeController::class, 'index'])->name('index');
        Route::get('/create', [OutPassEmployeeController::class, 'create'])->name('create');
        Route::post('/store', [OutPassEmployeeController::class, 'store'])->name('store');
        Route::get('/{id}', [OutPassEmployeeController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [OutPassEmployeeController::class, 'edit'])->name('edit');
        Route::put('/{id}', [OutPassEmployeeController::class, 'update'])->name('update');
        Route::delete('/{id}', [OutPassEmployeeController::class, 'destroy'])->name('destroy');
    
        // AJAX routes
        Route::post('/out-pass/fetch', [OutPassEmployeeController::class, 'fetchEmployee'])->name('fetch');
        Route::get('/search', [OutPassEmployeeController::class, 'search'])->name('search');
        Route::patch('/{id}/toggle-status', [OutPassEmployeeController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/department/{department}', [OutPassEmployeeController::class, 'getByDepartment'])->name('by-department');
        Route::get('/statistics', [OutPassEmployeeController::class, 'statistics'])->name('statistics');
    });
    
    
    // VehicleController routes
    Route::prefix('vehicles')->group(function () {
        Route::get('/', [OutPassVehicleController::class, 'index'])->name('api.vehicles.index');
        Route::post('/out-pass-vehicles', [OutPassVehicleController::class, 'store'])->name('api.vehicles.store');
        Route::get('/{id}', [OutPassVehicleController::class, 'show'])->name('api.vehicles.show');
        Route::put('/{id}', [OutPassVehicleController::class, 'update'])->name('api.vehicles.update');
        Route::delete('/{id}', [OutPassVehicleController::class, 'destroy'])->name('api.vehicles.destroy');
    
        // Custom routes
        Route::patch('/{id}/toggle-status', [OutPassVehicleController::class, 'toggleStatus'])->name('api.vehicles.toggle-status');
        Route::get('/search', [OutPassVehicleController::class, 'search'])->name('api.vehicles.search');
        Route::get('/number/{number}', [OutPassVehicleController::class, 'getByNumber'])->name('api.vehicles.by-number');
        Route::get('/owner/{ownerType}/{ownerId}', [OutPassVehicleController::class, 'getByOwner'])->name('api.vehicles.by-owner');
        Route::get('/expiring/documents', [OutPassVehicleController::class, 'expiringDocuments'])->name('api.vehicles.expiring');
        Route::get('/statistics', [OutPassVehicleController::class, 'statistics'])->name('api.vehicles.statistics');
        Route::post('/bulk-import', [OutPassVehicleController::class, 'bulkImport'])->name('api.vehicles.bulk-import');
    });
    
    Route::post('/duties/check-availability', [AssignDutiesController::class, 'checkAvailability'])->name('institute.duties.check.availability');
    
    Route::get('/assign-siblings',[AssignSiblingController::class, 'assignSiblingsPage'])->name('assign.siblings.page');
    Route::post('/assign-siblings',[AssignSiblingController::class, 'storeAssignedSiblings'])->name('assign.siblings.store');
    Route::get('/get-student-by-reg/{registrationNumber}', [AssignSiblingController::class, 'getStudentByReg'])->name('get.student.by.reg');
    Route::post('/get-student-by-reg-or-email', [AssignSiblingController::class, 'getStudentByRegOrEmail'])->name('get.student.by.reg.email');
    
    // Employee Card Settings
    Route::post('/institute/save-employee-card-settings', [EmployeeIdCardController::class, 'saveCardSettings'])
        ->name('institute.save-employee-card-settings');
    Route::get('/institute/get-employee-field-settings', [EmployeeFieldController::class, 'getFieldSettings'])
        ->name('institute.get-employee-field-settings');
    Route::post('/institute/save-employee-field-settings', [EmployeeFieldController::class, 'saveFieldSettings'])
        ->name('institute.save-employee-field-settings'); 
    Route::post('/institute/reset-employee-field-settings', [EmployeeFieldController::class, 'resetToDefault'])
        ->name('institute.reset-employee-field-settings');
        
    Route::post('/save-card-settings', [StudentCardController::class, 'saveCardSettings'])->name('institute.save-card-settings');
    Route::get('/institute/get-field-settings', [IDCardFieldController::class, 'getFieldSettings'])
        ->name('institute.get-field-settings');
    Route::post('/institute/save-field-settings', [IDCardFieldController::class, 'saveFieldSettings'])
        ->name('institute.save-field-settings');
    Route::post('/institute/reset-field-settings', [IDCardFieldController::class, 'resetToDefault'])
        ->name('institute.reset-field-settings');
        
    // Student Notification Routes
    Route::prefix('student')->name('student.')->middleware(['auth'])->group(function () {
        Route::get('/notifications', [StudentNotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/get', [StudentNotificationController::class, 'getNotifications'])->name('notifications.get');
        Route::get('/notifications/unread-count', [StudentNotificationController::class, 'unreadCount'])->name('notifications.unread-count');
        Route::post('/notifications/{id}/mark-as-read', [StudentNotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
        Route::post('/notifications/mark-all-read', [StudentNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::delete('/notifications/{id}', [StudentNotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::delete('/notifications/delete/all', [StudentNotificationController::class, 'destroyAll'])->name('notifications.delete-all');
    });
    
    // Employee Notification Routes
    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('/notifications', [EmployeeNotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/get', [EmployeeNotificationController::class, 'getNotifications'])->name('notifications.get');
        Route::get('/notifications/unread-count', [EmployeeNotificationController::class, 'unreadCount'])->name('notifications.unread-count');
        Route::post('/notifications/{id}/mark-as-read', [EmployeeNotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
        Route::post('/notifications/mark-all-read', [EmployeeNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::delete('/notifications/{id}', [EmployeeNotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::delete('/notifications/delete/all', [EmployeeNotificationController::class, 'destroyAll'])->name('notifications.delete-all');
        Route::post('/notifications/settings', [EmployeeNotificationController::class, 'saveSettings'])->name('notifications.settings');
        Route::get('/notifications/export/pdf', [EmployeeNotificationController::class, 'exportPDF'])->name('notifications.export.pdf');
        Route::get('/notifications/export/excel', [EmployeeNotificationController::class, 'exportExcel'])->name('notifications.export.excel');
    });
    
    
    Route::prefix('admin')->middleware(['auth'])->group(function () {
        // Bulk fee assignment routes
        Route::get('/students/bulk-fee-assignment', [BulkFeeAssignmentController::class, 'bulkFeeAssignment'])
            ->name('institute.admin.students.bulk-fee-assignment');
        Route::post('/students/get-for-bulk-fee', [BulkFeeAssignmentController::class, 'getStudentsForBulkFee'])
            ->name('institute.admin.students.get-for-bulk-fee');
        Route::post('/students/assign-bulk-fee', [BulkFeeAssignmentController::class, 'assignBulkFee'])
            ->name('institute.admin.students.assign-bulk-fee');
        Route::post('/students/preview-fee', [BulkFeeAssignmentController::class, 'getFeeStructurePreview'])
            ->name('institute.admin.students.preview-fee');
    });
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/promote-students', [StudentPromotionController::class, 'showPromotionForm'])->name('promote.students.form');
        // AJAX routes for promotion
        Route::prefix('ajax')->group(function () {
            Route::get('/get-classes-by-department', [StudentPromotionController::class, 'getClassesByDepartment'])->name('ajax.classes.by.department');
            Route::get('/get-sections-by-class', [StudentPromotionController::class, 'getSectionsByClass'])->name('ajax.sections.by.class');
            Route::get('/get-students-for-promotion', [StudentPromotionController::class, 'getStudentsForPromotion'])->name('ajax.students.for.promotion');
            Route::post('/promote-students', [StudentPromotionController::class, 'promoteStudents'])->name('ajax.promote.students');
            Route::post('/promote-all-students', [StudentPromotionController::class, 'promoteAllStudents'])->name('ajax.promote.all.students');
            Route::get('/validate-promotion', [StudentPromotionController::class, 'validatePromotion'])->name('ajax.validate.promotion');
            Route::get('/student-promotion-history/{studentHashId}', [StudentPromotionController::class, 'getStudentPromotionHistory'])->name('ajax.student.promotion.history');
        });
    });
    
    Route::prefix('institute/lecture-reassignment')->name('institute.lecture-reassignment.')->middleware(['auth'])->group(function () {
        Route::get('/', [LectureReassignmentController::class, 'index'])->name('index');
        Route::post('/employees', [LectureReassignmentController::class, 'getEmployeesByDepartment'])->name('employees');
        Route::post('/schedule', [LectureReassignmentController::class, 'getEmployeeSchedule'])->name('schedule');
        Route::post('/reassign', [LectureReassignmentController::class, 'reassignLecture'])->name('reassign');
        Route::post('/available-employees', [LectureReassignmentController::class, 'getAvailableEmployees'])->name('available-employees');
    });
    
    
    Route::get('/admission', function () {
        return view('instituteAdmin/DashboardFiles/admission');
     });
     
     Route::get('/job-application-form', function () {
        return view('instituteAdmin/DashboardFiles/JopApplicationForm');
    });

    // Dynamic Link routes
    Route::get('/create/dynamic/links', function () {
        return view('instituteAdmin/CreateDynamicLink/GenerateLink');
    })->name('create.dynamic.link');
    Route::post('/generate-activation-link', [ActiveDynamicLinkController::class, 'generate'])->name('activate.generate');

    Route::get('/activate/{token}', [ActiveDynamicLinkController::class, 'activate'])
        ->name('activate.link');
    // Route::get('/admission/send-otp', function () {
    //     return view('instituteAdmin.CreateDynamicLink.studentVerificationSet');
    // })->name('admission.send.otp');
    Route::get('/admission/send-otp', [ActiveDynamicLinkController::class, 'showAdmissionOtp'])
    ->name('admission.send.otp');
    
    Route::post('/applicant-otp-verify', [ActiveDynamicLinkController::class, 'student_admission_verify_otp'])->name('student.admission.verifyotp');
    Route::post('/applicant-otp-send', [ActiveDynamicLinkController::class, 'student_admission_send_otp'])->name('student.admission.sendotp');
    Route::get('applicant-verify-otp', function () {
        return view('instituteAdmin/CreateDynamicLink/studentAdmissionVerifyOtp');
    })->name('lifts.page');
    
    
    Route::prefix('institute-admin')->middleware(['auth'])->group(function () {
        Route::get('/multi-report-cards', [MultiReportCardController::class, 'index'])->name('report-cards.index');
        Route::post('/report-cards/get-students', [MultiReportCardController::class, 'getStudents'])->name('report-cards.get-students');
        Route::post('/report-cards/get-exam-names', [MultiReportCardController::class, 'getExamNames'])->name('report-cards.get-exam-names');
        Route::post('/report-cards/get-student-marks', [MultiReportCardController::class, 'getStudentExamMarks'])->name('report-cards.get-student-marks');
    });
    Route::post('report-cards/download-pdf', [MultiReportCardController::class, 'downloadPdf'])->name('report-cards.download-pdf');
    
    // Salary Review Routes
    Route::prefix('salary/review')->group(function () {
        Route::get('/', [SalaryReviewController::class, 'index'])->name('salary.review.index');
        Route::get('/show', [SalaryReviewController::class, 'show'])->name('salary.review.show');
        Route::get('/details', [SalaryReviewController::class, 'getSalaryDetails'])->name('salary.review.details');
        Route::post('/save', [SalaryReviewController::class, 'saveSalaryReview'])->name('salary.review.save');
        Route::post('/finalize', [SalaryReviewController::class, 'finalizeSalary'])->name('salary.review.finalize');
        Route::get('/finalized-details', [SalaryReviewController::class, 'getFinalizedSalary'])->name('salary.review.finalized.details');
        Route::post('/bulk-finalize', [SalaryReviewController::class, 'bulkFinalizeSalary'])->name('salary.review.bulk.finalize');
    });
    // Attendance Review Routes
    Route::prefix('attendance/review')->group(function () {
        Route::get('/', [AttendanceReviewController::class, 'index'])->name('attendance.review.index');
        Route::post('/finalize', [AttendanceReviewController::class, 'finalizeAttendance'])->name('attendance.review.finalize');
        Route::post('/bulk', [AttendanceReviewController::class, 'bulkFinalize'])->name('attendance.review.bulk');
        Route::get('/details', [AttendanceReviewController::class, 'getAttendanceDetails'])->name('attendance.review.details');
        Route::get('/finalized-details', [AttendanceReviewController::class, 'getFinalizedAttendance'])->name('attendance.review.finalized.details');
        Route::get('/salary-slip', [AttendanceReviewController::class, 'getSalarySlip'])->name('attendance.review.salary.slip');
        Route::get('/salary-review', [AttendanceReviewController::class, 'getSalaryReview'])->name('attendance.review.salary.review');
    });
    Route::get('/attendance/review/{employeeId}', [AttendanceReviewController::class, 'review'])->name('attendance.review.review');
        
    Route::get('/salary/review/view-finalized', [SalaryReviewController::class, 'viewFinalizedSalaryPage'])->name('salary.review.viewFinalized');
    // Payroll Routes
    Route::prefix('payroll')->group(function () {
        Route::get('/executionpage', [PayrollExecutionController::class, 'index'])->name('payroll.index');
        // Route::get('/execution-view', [PayrollExecutionController::class, 'viewPage'])->name('payroll.viewPage');
        Route::post('/execute', [PayrollExecutionController::class, 'saveConfiguration'])->name('payroll.config.save');
        Route::get('/executions', [PayrollExecutionController::class, 'getConfigurations'])->name('payroll.executions');
        Route::get('/executions/{id}', [PayrollExecutionController::class, 'getConfiguration']);
        Route::delete('/executions/{id}', [PayrollExecutionController::class, 'deleteConfiguration']);
    });
    // Salary Slip Generation Routes
    Route::prefix('salary-slip')->group(function () {
        Route::get('/generation', [SalarySlipGenerationController::class, 'index'])->name('salary-slip.generation');
        Route::post('/generate', [SalarySlipGenerationController::class, 'generate'])->name('salary-slip.generate');
        Route::get('/list', [SalarySlipGenerationController::class, 'getSalarySlips'])->name('salary-slip.list');
        Route::get('/finalized-employees', [SalarySlipGenerationController::class, 'getFinalizedEmployees'])->name('salary-slip.finalized-employees');
    });
    
    // Final Salary Slip Routes
    Route::prefix('final-salary-slips')->name('final-salary-slips.')->group(function () {
        Route::get('/', [FinalSalarySlipController::class, 'index'])->name('index');
        Route::get('/{slipId}', [FinalSalarySlipController::class, 'show'])->name('show');
        Route::post('/{slipId}/mark-paid', [FinalSalarySlipController::class, 'markAsPaid'])->name('mark-paid');
    });
    
    // Payroll Execution Routes
    Route::prefix('payroll')->group(function () {
        // View all executions (index/list page) - NO ID parameter
        Route::get('/execution-view', [PayrollExecutionController::class, 'viewPage'])->name('payroll.viewPage');
        // View specific execution details (requires ID)
        Route::get('/execution-view/{id}', [ManuallyPayrollExecuteController::class, 'viewExecution'])->name('execution.view.details');
        // History page
        Route::get('/execution-history', [ManuallyPayrollExecuteController::class, 'history'])->name('execution.history');
        // Execute form and processing
        Route::get('/execution/execute-form', [ManuallyPayrollExecuteController::class, 'showExecuteForm'])->name('execution.execute.form');
        Route::post('/execution/execute', [ManuallyPayrollExecuteController::class, 'executePayroll'])->name('execution.execute');
    });
    
    // Manual Payroll Execution Routes
    Route::prefix('salary')->group(function () {
         Route::get('/can-execute-manually/{employee_id}/{year}/{month}', [ManuallyPayrollExecuteController::class, 'canExecuteManually'])
        ->name('salary.can.execute.manually');
        Route::post('/execute-manual-payroll', [ManuallyPayrollExecuteController::class, 'executeManualPayroll'])
            ->name('salary.execute.manual');
    });
    
    //route for loan journey
    Route::get('/loan/journey/show-multiple-loan-requests', [LoanRequestController::class, 'showMultipleLoanRequests'])->name('loan.journey.show.multiple.loan.requests');
    Route::get('/loan/journey/loan-request-details', [LoanRequestController::class, 'selectloanRequestDetails'])->name('loan.select.request.details');
    Route::post('/loan-request-submit', [LoanRequestController::class, 'submitRequest'])->name('loan.request.submit');
    Route::get('/loan/journey/user-conformation', [LoanRequestController::class, 'loanRequestDetails'])->name('loan.request.details');
    Route::get('/loan/journey/waiting-for-approval/{loan_request_id}', [LoanRequestController::class, 'loanRequestApproval'])->name('loan.approval.waiting');
    Route::post('/loan/journey/pre-loan-sanction-request-details', [LoanRequestController::class, 'preLoanSanctionRequestDetails'])->name('loan.journey.pre.loan.sanction.request.details');
    Route::get('/loan/journey/get-bank-details/{loan_request_id}', [LoanRequestController::class, 'showBankdetails'])->name('loan.journey.bank.details');
    Route::post('/loan/journey/post-bank-details', [LoanRequestController::class, 'postBankdetails'])->name('loan.journey.post.bank.details');
    Route::get('/loan/journey/get-user-selfie/{loan_request_id}', [LoanRequestController::class, 'showSelfie'])->name('loan.journey.selfie');
    Route::post('/loan/journey/post-user-selfie', [LoanRequestController::class, 'postSelfie'])->name('loan.journey.post.selfie');
    Route::get('/loan/journey/journey/show/{loan_request_id}', [LoanRequestController::class, 'loanJourneyList'])->name('loan.journey.list');
    Route::get('/loan/request/details', [LoanRequestController::class, 'getLoanByUserWise'])->name('loan.user.list');
    Route::get('/loan/repayment/details', [LoanRepaymentController::class, 'getLoanDetailsByuser'])->name('loan.repayment.details');
    Route::get('/loan/repaymen/emi-details/{loan_id}', [LoanRepaymentController::class, 'getLoanEmiStructureByLoan'])->name('loan.emi.repayment.details');
    Route::get('/loan/journey/ajivika-loan-journey/{loan_request_id}', [LoanRequestController::class, 'redirectToAjivikaLoanJourney'])->name('ajivika.loan.redirection.page');
    Route::post('/loan/pay/emi', [PayEmiController::class, 'payEmiAmount']);
    Route::post('/loan/pay/update-emi', [PayEmiController::class, 'updatePayEmiStructure']);
    
    // Leave Management Routes
    Route::prefix('institute-admin')->middleware(['auth'])->group(function () {
        // Leave Types Management
        Route::get('/leave-types', [LeaveManagementController::class, 'leaveTypes'])->name('leave.types.index');
        Route::post('/leave-types', [LeaveManagementController::class, 'storeLeaveType'])->name('leave.types.store');
        Route::delete('/leave-types/{id}', [LeaveManagementController::class, 'destroyLeaveType'])->name('leave.types.destroy');
        // Attendance Deductions
        Route::get('/attendance-deductions', [LeaveManagementController::class, 'attendanceDeductions'])->name('attendance.deductions.index');
        Route::post('/attendance-deductions/bulk-update', [LeaveManagementController::class, 'bulkUpdateDeductions'])->name('attendance.deductions.bulk-update');
    });
    
    // Leave Quota Management (Admin View)
    Route::get('/leaves/quota-management', [LeaveController::class, 'quotaManagement'])->name('leaves.quota.management');
    Route::get('/ajax/employee-leave-details/{employeeId}', [LeaveController::class, 'ajaxEmployeeLeaveDetails'])->name('ajax.employee.leave.details');
    Route::get('/my-leave-status', [LeaveController::class, 'myLeaveStatus'])->name('employee.leave.status');

    Route::get('/loan/journey/show', function () {
        return view('instituteAdmin/LoanFiles/LoanJourney');
    })->name('loan.journey');
    Route::get('/loan/journey/loan-approval-details', function () {
        return view('instituteAdmin/LoanFiles/LoanApprovalPage');
    })->name('loan.journey.loan.approval.details');
    //end of loan journey route
    
    // Employee Salary Status Routes
    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('/payroll/dashboard', [EmployeePayrollDashboardController::class, 'index'])->name('payroll.dashboard');
        Route::get('/attendance/details', [EmployeePayrollDashboardController::class, 'getAttendanceDetails'])->name('attendance.details');
        Route::get('/salary-slip', [EmployeePayrollDashboardController::class, 'getSalarySlip'])->name('salary-slip');
    });
    
    Route::prefix('institute-admin')->middleware(['auth'])->group(function () {
        // Holiday Management Routes
        Route::get('/holidays/list', [GoogleCalendarController::class, 'getHolidaysList'])->name('institute-admin.holidays.list');
        Route::post('/holidays/toggle/{id}', [GoogleCalendarController::class, 'toggleHolidayStatus'])->name('institute-admin.holidays.toggle');
        Route::post('/holidays/bulk-update', [GoogleCalendarController::class, 'bulkUpdateHolidayStatus'])->name('institute-admin.holidays.bulk-update');
        Route::delete('/holidays/{id}', [GoogleCalendarController::class, 'deleteHoliday'])->name('institute-admin.holidays.delete');
        Route::put('/holidays/{id}', [GoogleCalendarController::class, 'editHoliday'])->name('institute-admin.holidays.edit');
        Route::post('/holidays/resync', [GoogleCalendarController::class, 'resyncHolidays'])->name('institute-admin.holidays.resync');
    });
    
    // Employee Calendar Routes
    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('/calendar', [EmployeeCalendarController::class, 'index'])->name('calendar');
        Route::get('/calendar/events', [EmployeeCalendarController::class, 'getCalendarEvents'])->name('calendar.events');
    });
    
    // Student Calendar Routes
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/calendar', [StudentCalendarController::class, 'index'])->name('calendar');
        Route::get('/calendar/events', [StudentCalendarController::class, 'getCalendarEvents'])->name('calendar.events');
    });
    
    // Calendar Routes
    Route::prefix('institute-admin')->middleware(['auth'])->group(function () {
        Route::get('/calendar', [TimetableController::class, 'index'])->name('institute-admin.calendar.index');
        Route::get('/calendar/events', [TimetableController::class, 'getCalendarEventsApi'])->name('institute-admin.calendar.events');
    });
    
    // Timetable routes
    // Route::get('institute-admin/timetable-data', [TimetableController::class, 'getTimetableData'])->name('institute-admin.timetable.data');
    Route::get('institute-admin/timetable-filters', [TimetableController::class, 'getFilterOptions'])->name('institute-admin.timetable.filters');
    
    // Lecture mode override routes
    Route::prefix('institute-admin/timetable')->group(function () {
        Route::get('/data', [TimetableController::class, 'getTimetableData'])->name('institute-admin.timetable.data');
        Route::post('/save-override', [TimetableController::class, 'saveLectureModeOverride'])->name('institute-admin.timetable.save-override');
        Route::delete('/delete-override', [TimetableController::class, 'deleteLectureModeOverride'])->name('institute-admin.timetable.delete-override');
        Route::get('/get-overrides', [TimetableController::class, 'getLectureOverrides'])->name('institute-admin.timetable.get-overrides');
    });
    
    // Holiday Sync Routes
    Route::post('/google-calendar/sync-holidays', [GoogleCalendarController::class, 'syncPublicHolidays'])->name('google.calendar.sync.holidays');
    Route::get('/google-calendar/sync-status', [GoogleCalendarController::class, 'getSyncStatus'])->name('google.calendar.sync.status');
    
    // Student Timetable Routes
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/timetable', [StudentTimetableController::class, 'index'])->name('timetable');
        Route::get('/timetable/events', [StudentTimetableController::class, 'getTimetableEvents'])->name('timetable.events');
        Route::get('/timetable/lecture/{id}', [StudentTimetableController::class, 'getLectureDetails'])->name('timetable.lecture.details');
    });
    
    Route::get('student/timetable/data', [StudentTimetableController::class, 'getTimetableData'])->name('student.timetable.data');
    
    Route::prefix('employee/profile-settings')->middleware(['auth'])->group(function () {
        // Profile settings (single page with tabs)
        Route::get('/', [EmployeeProfileSettingController::class, 'index'])->name('employee.profile.index');
        // Name update (no password required)
        Route::post('/update-name', [EmployeeProfileSettingController::class, 'updateName'])->name('employee.profile.updateName');
        // Email update with OTP
        Route::post('/send-email-otp', [EmployeeProfileSettingController::class, 'sendEmailOTP'])->name('employee.profile.sendEmailOTP');
        Route::post('/verify-email-otp', [EmployeeProfileSettingController::class, 'verifyEmailOTP'])->name('employee.profile.verifyEmailOTP');
        // Phone update with OTP
        Route::post('/send-phone-otp', [EmployeeProfileSettingController::class, 'sendPhoneOTP'])->name('employee.profile.sendPhoneOTP');
        Route::post('/verify-phone-otp', [EmployeeProfileSettingController::class, 'verifyPhoneOTP'])->name('employee.profile.verifyPhoneOTP');
        // Password update with CAPTCHA (no current password)
        Route::post('/update-password', [EmployeeProfileSettingController::class, 'updatePassword'])->name('employee.profile.updatePassword');
        Route::post('/refresh-captcha', [EmployeeProfileSettingController::class, 'refreshCaptcha'])->name('employee.profile.refreshCaptcha');
        // Resend OTP
        Route::post('/resend-otp', [EmployeeProfileSettingController::class, 'resendOTP'])->name('employee.profile.resendOTP');
    });
    
    // Student Profile Settings Routes
    Route::prefix('student/profile-settings')->middleware(['auth'])->group(function () {
        Route::get('/', [StudentProfileSettingController::class, 'index'])->name('student.profile.index');
        // Name update
        Route::post('/update-name', [StudentProfileSettingController::class, 'updateName'])->name('student.profile.updateName');
        // Email update with OTP
        Route::post('/send-email-otp', [StudentProfileSettingController::class, 'sendEmailOTP'])->name('student.profile.sendEmailOTP');
        Route::post('/verify-email-otp', [StudentProfileSettingController::class, 'verifyEmailOTP'])->name('student.profile.verifyEmailOTP');
        // Phone update with OTP
        Route::post('/send-phone-otp', [StudentProfileSettingController::class, 'sendPhoneOTP'])->name('student.profile.sendPhoneOTP');
        Route::post('/verify-phone-otp', [StudentProfileSettingController::class, 'verifyPhoneOTP'])->name('student.profile.verifyPhoneOTP');
        // Password update with CAPTCHA
        Route::post('/update-password', [StudentProfileSettingController::class, 'updatePassword'])->name('student.profile.updatePassword');
        Route::post('/refresh-captcha', [StudentProfileSettingController::class, 'refreshCaptcha'])->name('student.profile.refreshCaptcha');
        // Resend OTP
        Route::post('/resend-otp', [StudentProfileSettingController::class, 'resendOTP'])->name('student.profile.resendOTP');
    });
    
    // Settings routes
    Route::post('/admin/search/employee', [AdminSettingsController::class, 'searchEmployee'])->name('admin.search.employee');
    Route::post('/admin/search/student', [AdminSettingsController::class, 'searchStudent'])->name('admin.search.student');
    Route::post('/admin/refresh-captcha', [AdminSettingsController::class, 'refreshCaptcha'])->name('admin.refresh.captcha');
    Route::post('/admin/change-admin-password', [AdminSettingsController::class, 'changeAdminPassword'])->name('admin.change.admin.password');
    Route::post('/admin/change-employee-password', [AdminSettingsController::class, 'changeEmployeePassword'])->name('admin.change.employee.password');
    Route::post('/admin/change-student-password', [AdminSettingsController::class, 'changeStudentPassword'])->name('admin.change.student.password');
    Route::post('/authorized/profile/update', [AdminSettingsController::class, 'updateProfile'])->name('authorized.profile.update');
    Route::post('/authorized/photo/upload', [AdminSettingsController::class, 'uploadAuthorizedPhoto'])->name('authorized.photo.upload');
    Route::delete('/authorized/photo/delete', [AdminSettingsController::class, 'deleteAuthorizedPhoto'])->name('authorized.photo.delete');
    Route::post('/admin/save-notifications', [AdminSettingsController::class, 'saveNotifications'])->name('admin.save.notifications');
    Route::get('/admin/get-notifications', [AdminSettingsController::class, 'getNotifications'])->name('admin.get.notifications');
    
    // Notification settings routes
    Route::prefix('admin')->middleware(['auth'])->group(function () {
        Route::get('/get-notification-settings', [AdminSettingsController::class, 'getNotificationSettings'])->name('admin.get.notification.settings');
        Route::post('/save-notification-settings', [AdminSettingsController::class, 'saveNotificationSettings'])->name('admin.save.notification.settings');
    });
    
    
    // Super Admin Notification Module Routes
    Route::prefix('superadmin')->middleware(['auth'])->group(function () {
        // Module list and assignment
        Route::get('/notification-modules', [App\Http\Controllers\SuperAdmin\NotificationModuleController::class, 'index'])
            ->name('superadmin.notification-modules.index');
        Route::get('/notification-modules/assign', [App\Http\Controllers\SuperAdmin\NotificationModuleController::class, 'assignForm'])
            ->name('superadmin.notification-modules.assign');
        Route::post('/notification-modules/assign', [App\Http\Controllers\SuperAdmin\NotificationModuleController::class, 'storeAssignments'])
            ->name('superadmin.notification-modules.store-assignments');
        // Module management
        Route::get('/notification-modules/institute/{instituteId}', [App\Http\Controllers\SuperAdmin\NotificationModuleController::class, 'getInstituteModules'])
            ->name('superadmin.notification-modules.by-institute');
        Route::post('/notification-modules/{id}/toggle', [App\Http\Controllers\SuperAdmin\NotificationModuleController::class, 'toggleStatus'])
            ->name('superadmin.notification-modules.toggle');
        Route::delete('/notification-modules/{id}', [App\Http\Controllers\SuperAdmin\NotificationModuleController::class, 'deleteModule'])
            ->name('superadmin.notification-modules.delete');
    });
    Route::prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
        Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');
        Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
        Route::get('/menus/{menu}', [MenuController::class, 'show'])->name('menus.show');
        Route::get('/menus/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
        Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
        Route::patch('/menus/{menu}', [MenuController::class, 'update']);
        Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
        // Custom Routes
        Route::post('menus/bulk-delete', [MenuController::class, 'bulkDelete'])
            ->name('menus.bulk-delete');
        
        Route::post('menus/reorder', [MenuController::class, 'reorder'])
            ->name('menus.reorder');
        
        Route::patch('menus/{menu}/toggle-status', [MenuController::class, 'toggleStatus'])
            ->name('menus.toggle-status');
        
        Route::get('sidebar-menu', [MenuController::class, 'getSidebarMenu'])
            ->name('sidebar-menu');
    });
    
    Route::get('student-registration-installments/{student_hash}', [AdminFeeController::class, 'studentRegistrationInstallments'])->name('student.registration.installments');
    
    Route::post('/student/{student_hash_id}/suspend', [StudentSuspendController::class, 'suspendStudent'])->name('student.suspend');
    Route::post('/student/{student_hash_id}/unsuspend', [StudentSuspendController::class, 'unsuspendStudent'])->name('student.unsuspend');
    Route::get('/student/{student_hash_id}/check-credits', [StudentSuspendController::class, 'checkCreditsAccess'])->name('student.check-credits');
    Route::get('/student/{student_hash_id}/suspension-history', [StudentSuspendController::class, 'getSuspensionHistory'])->name('student.suspension-history');
    Route::post('/student/{student_hash_id}/suspend', [StudentSuspendController::class, 'suspendStudent']);
    Route::post('/student/{student_hash_id}/unsuspend', [StudentSuspendController::class, 'unsuspendStudent']);
    
    Route::get('/no-due', function () {return view('instituteAdmin/Certificates/nodue');})->name('nodue');
    Route::post('/no-due/store', [BonafideCertificateController::class, 'storeNoDue'])->name('nodue.store');
    Route::get('/certificate-verification', function () {return view('instituteAdmin.Certificates.verifyCertificate');})->name('certificate.verification');

    Route::get('/certificate-verification/data', [CertificatesController::class, 'verifyCertificateData'])->name('certificate.verification.data');
    
    Route::post('/employees/{id}/promote-to-fulltime', [EmployeeDetailsController::class, 'promoteToFullTime'])
    ->name('employees.promote-to-fulltime');

    
      // Routes for Probation Employees
    Route::prefix('probation-employees')->group(function () {
        Route::get('/', [ProbationEmployeeController::class, 'index'])->name('probation.employees');
        Route::post('/{id}/promote', [ProbationEmployeeController::class, 'promote'])->name('probation.employee.promote');
        Route::post('/bulk-promote', [ProbationEmployeeController::class, 'bulkPromote'])->name('probation.bulk.promote');
        Route::get('/statistics', [ProbationEmployeeController::class, 'getStatistics'])->name('probation.statistics');
        Route::get('/check-salary-structure', [ProbationEmployeeController::class, 'checkAwaitingSalaryStructures'])->name('institute.probation.check-salary-structure');
    });
    
    // Employee Promotion Routes
    Route::prefix('employee-promotion')->name('employee.promotion.')->middleware(['auth'])->group(function () {
        Route::get('/', [EmployeePromotionController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [EmployeePromotionController::class, 'edit'])->name('edit');
        Route::get('/{id}/history', [EmployeePromotionController::class, 'history'])->name('history');
    
        // Update routes
        Route::put('/{id}/update-employment-type', [EmployeePromotionController::class, 'updateEmploymentType'])->name('update.employment-type');
        Route::put('/{id}/update-designation', [EmployeePromotionController::class, 'updateDesignation'])->name('update.designation');
    
        // Salary structure routes
        Route::post('/{id}/inactivate-salary-structure', [EmployeePromotionController::class, 'inactivateSalaryStructure'])->name('inactivate-salary-structure');
        Route::get('/{id}/assign-salary-structure', [EmployeePromotionController::class, 'assignSalaryStructure'])->name('assign-salary-structure');
    
        // Confirmation token
        Route::get('/confirmation-token', [EmployeePromotionController::class, 'getConfirmationToken'])->name('confirmation-token');
    });
    // Employee Update Routes
    Route::prefix('employees')->group(function () {
        // Show edit form
        Route::get('/edit/{id}', [EmployeeUpdateController::class, 'edit'])
            ->name('employees.edit');
    
        // Update employee (full update)
        Route::put('/update/{id}', [EmployeeUpdateController::class, 'update'])
            ->name('employees.update');
    
        // Partial update (step-by-step)
        Route::post('/update-partial/{id}', [EmployeeUpdateController::class, 'updatePartial'])
            ->name('employees.update-partial');
    
        // Get change history
        Route::get('/{id}/changes', [EmployeeUpdateController::class, 'getChangeHistory'])
            ->name('employees.changes');
    
        // Get change summary
        Route::get('/{id}/changes/summary', [EmployeeUpdateController::class, 'getChangeSummary'])
            ->name('employees.changes-summary');
    });

    // Exit single employee
    Route::post('/employees/{id}/exit', [EmployeeDetailsController::class, 'exitEmployee'])
        ->name('employees.exit')
        ->where('id', '[0-9]+');
    
    // Bulk exit employees
    Route::post('/employees/bulk-exit', [EmployeeDetailsController::class, 'bulkExitEmployees'])
        ->name('employees.bulk-exit');
    
    // Employee Suspension Routes
    Route::post('/employees/{id}/suspend', [EmployeeSuspendController::class, 'suspendEmployee'])
        ->name('employees.suspend')
        ->where('id', '[0-9]+');
    
    Route::post('/employees/{id}/unsuspend', [EmployeeSuspendController::class, 'unsuspendEmployee'])
        ->name('employees.unsuspend')
        ->where('id', '[0-9]+');
    
    Route::get('/employees/{id}/suspension-history', [EmployeeSuspendController::class, 'getEmployeeSuspensionHistory'])
        ->name('employees.suspension-history')
        ->where('id', '[0-9]+');
        
        
    // Branch management routes
   Route::get('/institute/admin/branch-campus', [BranchCampusController::class, 'index'])->name('branch.index');
    Route::post('/institute/branch/store', [BranchCampusController::class, 'storeBranchDetails'])->name('branch.store.details');
    
    Route::prefix('institute')->middleware(['auth'])->group(function () {
        // View branches listing
        Route::get('/admin/view-branch-campus', [BranchDetailsController::class, 'index'])
            ->name('admin.branch.index');
    
        Route::get('/branch/overview/{id}', [BranchOverviewController::class, 'showOverviewPage'])
            ->name('branch.overview.page');
    
        // View single branch details - this should come AFTER the overview route
        Route::get('/branch/{id}', [BranchDetailsController::class, 'show'])
            ->name('branch.show');
    
        // API endpoint for overview data (different URL pattern)
        Route::get('/branch/overview-data/{id}', [BranchOverviewController::class, 'getBranchOverview'])
            ->name('branch.overview.data');
    
        // API endpoints for AJAX
        Route::get('/branches/data', [BranchDetailsController::class, 'getBranchesData'])
            ->name('branches.data');
        Route::get('/branch-data/{id}', [BranchDetailsController::class, 'getBranchData'])
            ->name('branch.data');
        Route::patch('/branch/{id}/status', [BranchDetailsController::class, 'updateStatus'])
            ->name('branch.update.status');
        Route::delete('/branch/{id}', [BranchDetailsController::class, 'destroy'])
            ->name('branch.destroy');
    });
    Route::post('/branch-login/{branchId}', [BranchCampusController::class, 'branchLogin'])
    ->name('branch.login');
    
    Route::post('/back-to-main-institute', [BranchCampusController::class, 'backToMainInstitute'])
    ->name('branch.back.main');

    // Route::prefix('institute')->group(function () {
    //     // Branch overview routes
    //     Route::get('/branch/overview/{id}', [BranchOverviewController::class, 'getBranchOverview']);
    //     Route::get('/branches/overview/all', [BranchOverviewController::class, 'getAllBranchesOverview']);
    // });
    
    Route::prefix('exit-policies')->name('exit-policies.')->group(function () {
        // List all policies
        Route::get('/', [EmployeeExitPolicyController::class, 'index'])->name('index');
        // Create policy (Step 1)
        Route::get('/create', [EmployeeExitPolicyController::class, 'create'])->name('create');
        Route::post('/store', [EmployeeExitPolicyController::class, 'store'])->name('store');
        // Assign policy (Step 2)
        Route::get('/assign/{id}', [EmployeeExitPolicyController::class, 'showAssign'])->name('assign');
        Route::post('/assign/store', [EmployeeExitPolicyController::class, 'storeAssignment'])->name('assign.store');
        // View, Edit, Update, Delete
        Route::get('/{id}', [EmployeeExitPolicyController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [EmployeeExitPolicyController::class, 'edit'])->name('edit');
        Route::put('/{id}', [EmployeeExitPolicyController::class, 'update'])->name('update');
        Route::delete('/{id}', [EmployeeExitPolicyController::class, 'destroy'])->name('destroy');
        // Toggle status
        Route::post('/toggle-status/{id}', [EmployeeExitPolicyController::class, 'toggleStatus'])->name('toggle-status');
        // Statistics
        Route::get('/statistics', [EmployeeExitPolicyController::class, 'getStatistics'])->name('statistics');
    });

    Route::post('exit-policies/check-conflicts', [EmployeeExitPolicyController::class, 'checkConflicts'])->name('exit-policies.check-conflicts');
    Route::post('exit-policies/toggle-status/{id}', [EmployeeExitPolicyController::class, 'toggleStatus'])->name('exit-policies.toggle-status');
    
    Route::get('/employee/exit/policy', [EmployeeResignController::class, 'showPolicy'])->name('employee.exit.policy');
    
    Route::prefix('institute/admin')->group(function () {
        // Employee Exit Routes
        Route::prefix('employee-exit')->group(function () {
            // Initiate Exit
            Route::get('/initiate/{employeeId}', [EmployeeExitController::class, 'initiateExit'])
                ->name('employee.exit.initiate');
            Route::post('/process/{employeeId}', [EmployeeExitController::class, 'processExit'])
                ->name('employee.exit.process');
            // Exit Management
            Route::post('/approve/{exitId}', [EmployeeExitController::class, 'approveExit'])
                ->name('employee.exit.approve');
            Route::post('/complete/{exitId}', [EmployeeExitController::class, 'completeExit'])
                ->name('employee.exit.complete');
            Route::post('/cancel/{exitId}', [EmployeeExitController::class, 'cancelExit'])
                ->name('employee.exit.cancel');
            // Clearance Update
            Route::post('/clearance/{exitId}', [EmployeeExitController::class, 'updateClearance'])
                ->name('employee.exit.clearance');
            // AJAX Endpoints
            Route::get('/details/{exitId}', [EmployeeExitController::class, 'getExitDetails'])
                ->name('employee.exit.details');
            Route::get('/history/{employeeId}', [EmployeeExitController::class, 'getExitHistory'])
                ->name('employee.exit.history');
            Route::get('/policy/{employeeId}', [EmployeeExitController::class, 'getExitPolicyDetails'])
                ->name('employee.exit.policy.details');
        });
    });
    
    // Employee resign Routes
    Route::prefix('employee')->group(function () {
        Route::get('/exit/dashboard', [EmployeeResignController::class, 'index'])->name('employee.exit.dashboard');
        Route::get('/exit/resignation', [EmployeeResignController::class, 'showResignationForm'])->name('employee.exit.resignation.form');
        Route::post('/exit/resignation', [EmployeeResignController::class, 'submitResignation'])->name('employee.exit.resignation.submit');
    });

    Route::get('/employee/exit/policy', [EmployeeResignController::class, 'showPolicy'])->name('employee.exit.policy');
    
    // Exit Approvals with filters
    Route::get('/institute/admin/exit-approvals', [EmployeeExitApprovalController::class, 'index'])
        ->name('exit.approvals');
    // Process approval (AJAX)
    Route::post('/institute/admin/exit-approval/{id}', [EmployeeExitApprovalController::class, 'updateApproval'])
        ->name('exit.approval.update');
    // Task assignment page
    Route::get('/institute/admin/exit-assign-tasks/{exitId}', [EmployeeExitApprovalController::class, 'showAssignTasks'])
        ->name('exit.assign.tasks');
    // Assign task (AJAX)
    Route::post('/institute/admin/exit-task-assign', [EmployeeExitApprovalController::class, 'assignTasks'])
        ->name('exit.task.assign');
    // Update task (AJAX)
    Route::post('/institute/admin/exit-task-update', [EmployeeExitApprovalController::class, 'updateTask'])
        ->name('exit.task.update');
    // Save all tasks (AJAX)
    Route::post('/institute/admin/exit-tasks-save-all', [EmployeeExitApprovalController::class, 'saveAllTasks'])
        ->name('exit.tasks.save.all');
    // Get assignable employees (AJAX)
    Route::get('/institute/admin/exit-assignable-employees', [EmployeeExitApprovalController::class, 'getAssignableEmployees'])
        ->name('exit.assignable.employees');
    
    // Exit Task Management Routes
    Route::prefix('institute/admin')->middleware(['auth'])->group(function () {
        // Task Management
        Route::get('/exit-tasks', [ExitTaskManagementController::class, 'index'])->name('exit.tasks.manage');
        Route::get('/exit-task/{id}', [ExitTaskManagementController::class, 'getTaskDetails'])->name('exit.task.details');

        // Requirement Management
        Route::post('/exit-task/toggle-requirement', [ExitTaskManagementController::class, 'toggleRequirement'])->name('exit.task.toggle.requirement');
        Route::post('/exit-task/add-requirement-note', [ExitTaskManagementController::class, 'addRequirementNote'])->name('exit.task.add.requirement.note');

        // Task Status
        Route::post('/exit-task/update-status', [ExitTaskManagementController::class, 'updateTaskStatus'])->name('exit.task.update.status');
        Route::post('/exit-task/bulk-update', [ExitTaskManagementController::class, 'bulkUpdateStatus'])->name('exit.task.bulk.update');

        // Export
        Route::get('/exit-tasks/export', [ExitTaskManagementController::class, 'exportTasks'])->name('exit.tasks.export');

        // API
        Route::get('/exit-tasks/statistics', [ExitTaskManagementController::class, 'getStatistics'])->name('exit.tasks.statistics');
        Route::get('/exit-tasks/employee-policy/{employeeId}', [ExitTaskManagementController::class, 'getEmployeePolicyApi'])->name('exit.tasks.employee.policy');
    });
    
    // Employee WFH Request Routes
    Route::prefix('employee')->name('employee.')->middleware(['auth'])->group(function() {
    
        // AJAX route to get shifts for date range - MUST be first
        Route::get('/wfh-requests/get-shifts', [WFHRequestController::class, 'getShiftsForDateRange'])
            ->name('wfh-requests.get-shifts');
    
        // Main tracking/index page
        Route::get('/wfh-requests', [WFHRequestController::class, 'index'])
            ->name('wfh-requests.trackrequest');
    
        // Create new request
        Route::get('/wfh-requests/create', [WFHRequestController::class, 'create'])
            ->name('wfh-requests.create');
    
        // Store request
        Route::post('/wfh-requests', [WFHRequestController::class, 'store'])
            ->name('wfh-requests.store');
    
        // View specific request - This should come AFTER all other GET routes
        Route::get('/wfh-requests/{id}', [WFHRequestController::class, 'show'])
            ->name('wfh-requests.show');
    
        // Cancel request
        Route::post('/wfh-requests/{id}/cancel', [WFHRequestController::class, 'cancel'])
            ->name('wfh-requests.cancel');
    });
    // routes/web.php - Add these routes
    
    Route::prefix('institute-admin')->name('institute-admin.')->middleware(['auth'])->group(function() {
    
        // WFH Requests Routes
        Route::get('/wfh-requests', [AdminWFHRequestController::class, 'index'])
            ->name('wfh-requests.index');
    
        Route::get('/wfh-requests/create', [AdminWFHRequestController::class, 'create'])
            ->name('wfh-requests.create');
    
        Route::post('/wfh-requests', [AdminWFHRequestController::class, 'store'])
            ->name('wfh-requests.store');
    
        Route::get('/wfh-requests/{id}', [AdminWFHRequestController::class, 'show'])
            ->name('wfh-requests.show');
    
        Route::post('/wfh-requests/{id}/update-status', [AdminWFHRequestController::class, 'updateStatus'])
            ->name('wfh-requests.update-status');
    
        Route::post('/wfh-requests/bulk-update', [AdminWFHRequestController::class, 'bulkUpdate'])
            ->name('wfh-requests.bulk-update');
    
        Route::get('/wfh-requests/export', [AdminWFHRequestController::class, 'export'])
            ->name('wfh-requests.export');
    
        Route::get('/wfh-requests/{id}/details', [AdminWFHRequestController::class, 'getDetails'])
            ->name('wfh-requests.details');
    });
    
    // Notice Period Employees
    Route::get('/erp/employees/notice-period', [EmployeeDetailsController::class, 'noticePeriodEmployees'])
    ->name('employees.notice-period');
    
    // ============================================================
    // LETTER BUILDER ROUTES
    // ============================================================
    
    // ---------- API ENDPOINTS (Return JSON) ----------
    
    // Templates API - MUST be defined BEFORE the view route
    // Route::get('/letter-templates', function () {return view('instituteAdmin.VisitorManagement/letterTemplate');})->name('letter.template');
    // Route::get('/letter-templates', [LetterController::class, 'getTemplates'])->name('letter.templates.api');
    Route::get('/letter-template/{key}', [LetterController::class, 'getTemplate'])->name('letter.template.api');
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('a');
    Route::get('/letter-templates', [LetterTemplateController::class, 'index']);
    Route::post('/letter-templates', [LetterTemplateController::class, 'store']);
    Route::put('/letter-templates/{id}', [LetterTemplateController::class, 'update']);
    Route::delete('/letter-templates/{id}', [LetterTemplateController::class, 'destroy']);
    
    // Letter API endpoints
    Route::prefix('letter-builder')->group(function () {
        // Get letter data as JSON
        Route::get('/letter/{id}', [LetterController::class, 'getLetter'])->name('letter.get');
        Route::get('/letter/{id}/get-design', [LetterDesignSettingController::class, 'get'])->name('letter-design.get');
        
        // Save operations
        Route::post('/preview', [LetterController::class, 'preview']);
        Route::post('/save', [LetterController::class, 'storeLetter']);
        Route::post('/save-template', [LetterController::class, 'saveTemplate'])->name('letter-builder.save-template');
        Route::post('/save-template', [LetterController::class, 'save']);
        Route::post('/letter/{letterId}/save-design', [LetterDesignSettingController::class, 'save'])->name('letter-design.save');
        
        // PDF operations
        Route::post('/letter/save-pdf', [LetterController::class, 'savePDF'])->name('letter.save-pdf');
        Route::post('/letter/{id}/save-pdf', [LetterController::class, 'savePDF'])->name('letter.save-pdf.old');
        
        // CRUD operations
        Route::get('/letters', [LetterController::class, 'index']);
        Route::put('/letter/{id}', [LetterController::class, 'update']);
        Route::delete('/letter/{id}', [LetterController::class, 'destroy']);
        
        Route::get('/employee/{employeeId}/variables', [LetterVariableController::class, 'getEmployeeLetterVariables'])
            ->name('letter.employee.variables');
    });
    
    // Design settings API (standalone)
    Route::get('/letter-design-setting/{letterId}/get', [LetterDesignSettingController::class, 'get']);
    
    // ---------- VIEW ROUTES (Return HTML) ----------
    // Letter Builder main view
    Route::get('/letter-builder', function () {
        return view('instituteAdmin.VisitorManagement/letterTemplate');
    })->name('letter.builder');
    
    // Letter PREVIEW LIST view (shows all letters)
    Route::get('/letter-preview', function () {
        return view('instituteAdmin.VisitorManagement.letterPreview');
    })->name('letter.preview');
    
    // Letter preview modal
    Route::get('/letter-preview-modal', function () {
        return view('instituteAdmin.VisitorManagement.letterModal');
    })->name('letter.preview');
    
    // Letter template list view
    Route::get('/letter-templates-view', function () {
        return view('instituteAdmin.VisitorManagement/letterPreview');
    })->name('letter.template');
    
    // Letter view/edit pages
    Route::prefix('letter-builder')->group(function () {
        Route::get('/letter/{id}/view', [LetterController::class, 'view'])->name('letter.view');
        Route::get('/letter/{id}/edit', [LetterController::class, 'edit'])->name('letter.edit');
    });
    Route::get('/letter-design-settings', [LetterController::class, 'getAllDesignSettings'])->name('letter.design.settings');
    
    Route::post('institute/upload-stamp', [App\Http\Controllers\institute\Admin\FincapMerchantController::class, 'uploadStamp'])
    ->name('institute.upload.stamp');

    Route::delete('institute/delete-stamp', [App\Http\Controllers\institute\Admin\FincapMerchantController::class, 'deleteStamp'])
        ->name('institute.delete.stamp');
    
    // Signature routes
    Route::post('institute/upload-signature', [App\Http\Controllers\institute\Admin\FincapMerchantController::class, 'uploadSignature'])
        ->name('institute.upload.signature');
    
    Route::delete('institute/delete-signature', [App\Http\Controllers\institute\Admin\FincapMerchantController::class, 'deleteSignature'])
        ->name('institute.delete.signature');
    
    // ---------- EMPLOYEE LETTER ROUTES ----------
    // Employee letter view (saved letters from letters table)
    Route::get('/employee/letter/{id}', [LetterController::class, 'employeeLetterView'])->name('employee.letter.view');
    Route::get('/employee/letter/{id}/pdf', [LetterController::class, 'viewLetterPdf'])->name('employee.letter.pdf');
    Route::post('/employee/letter/{id}/cancel', [LetterController::class, 'cancelLetter'])->name('employee.letter.cancel');
    
    // Get letter by letter_id (custom ID like "LTR-ITE7")
    Route::get('/letter-builder/letter-by-id/{letterId}', [LetterController::class, 'getLetterByLetterId']);
    
    // ---------- EMPLOYEE ROUTES ----------
    Route::get('/institute/admin/employees/{employee_id}/edit', [EmployeeDetailsController::class, 'editemployee'])->name('institute.employee.edit');
    
    // Student Exit Routes
    Route::prefix('institute-admin')->middleware(['auth'])->group(function () {
        // Show exit form
        Route::get('/student/{student_hash_id}/exit', [StudentExitController::class, 'showExitForm'])->name('institute.admin.student.exit.form');
        // Process exit
        Route::post('/student/{student_hash_id}/exit', [StudentExitController::class, 'processExit'])->name('institute.admin.student.exit.process');
        // Get student details for exit (AJAX)
        Route::get('/student/{student_hash_id}/exit-details', [StudentExitController::class, 'getStudentExitDetails'])->name('institute.admin.student.exit.details');
    });
    
    Route::get('/institute-admin/exited-students', [StudentExitController::class, 'exitedStudentsList'])->name('institute.admin.exited.students');
    // Get exit details for modal
    Route::get('/institute/admin/exit-details/{exitId}', [StudentExitController::class, 'getExitDetails'])->name('institute.admin.exit.details');
    
    // Student Exit Request Routes
    Route::prefix('student')->middleware(['auth'])->group(function () {
        Route::get('/exit-request/form', [StudentExitRequestController::class, 'showExitRequestForm'])->name('student.exit.request.form');
        Route::post('/exit-request/submit', [StudentExitRequestController::class, 'submitExitRequest'])->name('student.exit.request.submit');
        Route::get('/exit-request/status', [StudentExitRequestController::class, 'showExitRequestStatus'])->name('student.exit.request.status');
        Route::post('/exit-request/{requestId}/cancel', [StudentExitRequestController::class, 'cancelExitRequest'])->name('student.exit.request.cancel');
    });
    
    // Admin Exit Request Management
    Route::prefix('admin')->middleware(['auth'])->group(function () {
        Route::get('/exit-requests', [AdminExitRequestController::class, 'index'])->name('admin.exit.requests.index');
        Route::get('/exit-requests/{id}', [AdminExitRequestController::class, 'show'])->name('admin.exit.requests.show');
        Route::post('/exit-requests/{id}/approve', [AdminExitRequestController::class, 'approve'])->name('admin.exit.requests.approve');
        Route::post('/exit-requests/{id}/reject', [AdminExitRequestController::class, 'reject'])->name('admin.exit.requests.reject');
    });
    
    
    
    // Web routes for AJAX requests
    Route::prefix('institute-admin')->group(function () {
        // Frontend view routes
        Route::view('/create-reimbursement-policy', 'instituteAdmin.Reimbursement.CreateReimbursementPolicy')
            ->name('reimbursement.policies.create');
        Route::view('/edit-reimbursement-policy/{id}', 'instituteAdmin.Reimbursement.EditReimbursementPolicy')
            ->name('reimbursement.policies.edit');
    
        Route::view('/assign-reimbursement-policy', 'instituteAdmin.Reimbursement.AssignReimbursementPolicy')
            ->name('assign.reimbursement.policies');
    
        Route::view('/create-reimbursement-claim', 'instituteAdmin.Reimbursement.CreateReimbursementClaim')
            ->name('reimbursement.claims.create');
            
        Route::get('/reimbursement-dashboard', [ReimbursementClaimController::class, 'dashboard'])
        ->name('reimbursement.dashboard');
    
    
        // AJAX routes for policy operations
        Route::post('/reimbursement-policies/store', [ReimbursementPolicyController::class, 'store'])
            ->name('reimbursement.policies.store');
        Route::get('/reimbursement-policies', [ReimbursementPolicyController::class, 'index'])
            ->name('reimbursement.policies.ajax.index');
        Route::post('/reimbursement-policies/{id}', [ReimbursementPolicyController::class, 'update'])
            ->name('reimbursement.policies.ajax.update');
        Route::delete('/reimbursement-policies/{id}', [ReimbursementPolicyController::class, 'destroy'])
            ->name('reimbursement.policies.ajax.destroy');
    
        // Assignment routes
        Route::get('/reimbursement-assignments/departments', [ReimbursementAssignmentController::class, 'getDepartments'])
            ->name('reimbursement.assignments.departments');
        Route::get('/reimbursement-assignments/department-categories', [ReimbursementAssignmentController::class, 'getDepartmentCategories'])
            ->name('reimbursement.assignments.categories');
        Route::get('/reimbursement-assignments/designations-by-category', [ReimbursementAssignmentController::class, 'getDesignationsByCategory'])
            ->name('reimbursement.assignments.designations.by.category');
        Route::get('/reimbursement-assignments/all-designations', [ReimbursementAssignmentController::class, 'getAllDesignations'])
            ->name('reimbursement.assignments.all.designations');
        Route::get('/reimbursement-assignments/employees-by-department', [ReimbursementAssignmentController::class, 'getEmployeesByDepartment']);
    
        Route::get('/reimbursement-assignments/list', [ReimbursementAssignmentController::class, 'getAssignmentsJSON'])
            ->name('reimbursement.assignments.list');
        Route::post('/reimbursement-assignments/store', [ReimbursementAssignmentController::class, 'store'])
            ->name('reimbursement.assignments.store');
        Route::delete('/reimbursement-assignments/{id}', [ReimbursementAssignmentController::class, 'destroy'])
            ->name('reimbursement.assignments.destroy');
        Route::get('/reimbursement-assignments/policy-details', [ReimbursementAssignmentController::class, 'getPolicyDetails'])
            ->name('reimbursement.assignments.policy.details');
    
        //ajax route of create claim
        Route::get('/reimbursement-claims/get-allowed-ranges-details', [ReimbursementClaimController::class, 'getPolicyRangesDetails'])
            ->name('reimbursement.claims.allowed-ranges');
    
        Route::get('/reimbursement-claims/approval-list', [ReimbursementClaimController::class, 'getApprovalClaims'])
            ->name('reimbursement.claims.approval-list');
        Route::get('/reimbursement-claims/employee-details', [ReimbursementClaimController::class, 'getEmployeeDetails'])
            ->name('reimbursement.claims.employee-details');
        Route::get('/reimbursement-claims/assigned-policies', [ReimbursementClaimController::class, 'getAssignedPolicies'])
            ->name('reimbursement.claims.assigned-policies');
        Route::get('/reimbursement-claims/policy-usage', [ReimbursementClaimController::class, 'getPolicyUsage'])
            ->name('reimbursement.claims.policy-usage');
        Route::post('/reimbursement-claims/store', [ReimbursementClaimController::class, 'store'])
            ->name('reimbursement.claims.store');
        Route::get('/reimbursement-claims', [ReimbursementClaimController::class, 'index'])
            ->name('reimbursement.claims.index');
        Route::get('/reimbursement-claims/{id}', [ReimbursementClaimController::class, 'getEmployeeClaims'])
            ->name('reimbursement.claims.show');
        Route::get('/reimbursement-claims/my-claims', [ReimbursementClaimController::class, 'getEmployeeClaims'])
            ->name('reimbursement.claims.my');        
        Route::post('/reimbursement-claims/{id}/approve', [ReimbursementClaimController::class, 'approve'])
            ->name('reimbursement.claims.approve');
        Route::post('/reimbursement-claims/{id}/reject', [ReimbursementClaimController::class, 'reject'])
            ->name('reimbursement.claims.reject');
    
        Route::post('/reimbursement-claims/store-bulk', [ReimbursementClaimController::class, 'storeBulk']);
    
        Route::get('/reimbursement-claims/master/{masterRequestId}', [ReimbursementClaimController::class, 'getByMasterRequest']);
    });
    
    
    // In routes/web.php or routes/admin.php
    Route::get('/institute-admin/reimbursement-claims/review/{masterRequestId}', [ReimbursementClaimController::class, 'reviewBatch'])
        ->name('institute.admin.reimbursement.review-batch');
    
    Route::post('/institute-admin/reimbursement-claims/bulk-action', [ReimbursementClaimController::class, 'bulkAction'])
        ->name('institute.admin.reimbursement.bulk-action');
    
    Route::get('/institute-admin/reimbursement-claims/settlement/{masterRequestId}', [ReimbursementClaimController::class, 'settlementView'])
        ->name('institute.admin.reimbursement.settlement');
    
    Route::get('/institute-admin/reimbursement-claims/remaining-limits/{id}', [ReimbursementClaimController::class, 'getRemainingLimits'])
        ->name('reimbursement.remaining-limits');
    
    Route::view('/institute-admin/view-all-claims-for-admin', 'instituteAdmin.Reimbursement.AdminAllClaimsView')
        ->name('reimbursement.claims.admin.view');
    
    Route::get('/institute-admin/reimbursement-claims-admin', [ReimbursementClaimController::class, 'adminClaims'])
        ->name('reimbursement.claims.admin.list');
    
    Route::get('/institute-admin/view-all-claims', function () {
        return view('instituteAdmin.Reimbursement.ViewAllClaimsList');
    })->name('view.all.claims');
    
    Route::get('/institute-admin/view-employee-claims', function () {
        return view('instituteAdmin.Reimbursement.ViewEmployeeClaims');
    })->name('view.employee.claims');
    
    // Serve reimbursement files
    Route::get('/reimbursement-file/{path}', function ($path) {
        $filePath = storage_path('app/public/' . $path);
        if (file_exists($filePath)) {
            return response()->file($filePath);
        }
        abort(404);
    })->where('path', '.*')->name('reimbursement.file');
    
    
    Route::prefix('institute')->middleware(['auth'])->group(function () {
        // ID Card Templates
        Route::prefix('id-card-templates')->group(function () {
            Route::get('/', [IdCardTemplateController::class, 'index'])->name('id-card-templates.index');
            Route::get('/create', [IdCardTemplateController::class, 'create'])->name('id-card-templates.create');
            Route::post('/store', [IdCardTemplateController::class, 'store'])->name('id-card-templates.store');
            Route::post('/preview', [IdCardTemplateController::class, 'preview'])->name('id-card-templates.preview');
            Route::get('/{id}/edit', [IdCardTemplateController::class, 'edit'])->name('id-card-templates.edit');
            Route::put('/{id}', [IdCardTemplateController::class, 'update'])->name('id-card-templates.update');
            Route::delete('/{id}', [IdCardTemplateController::class, 'destroy'])->name('id-card-templates.destroy');
            Route::post('/{id}/set-default', [IdCardTemplateController::class, 'setDefault'])->name('id-card-templates.set-default');
            Route::get('/{id}/preview-template', [IdCardTemplateController::class, 'previewTemplate'])->name('id-card-templates.preview-template');
            Route::post('/{id}/reset', [IdCardTemplateController::class, 'resetToDefault'])->name('id-card-templates.reset');
    
            // Add this route for image serving
            Route::get('/image/{path}', [IdCardTemplateController::class, 'image'])->name('id-card.image')->where('path', '.*');
        });
    
        // Generate ID Card - Employee specific
        Route::get('/employee/{employee}/generate-card', [IdCardTemplateController::class, 'showGeneratePage'])->name('id-card.generate');
        Route::post('/preview-card', [IdCardTemplateController::class, 'previewCard'])->name('id-card.preview');
        Route::post('/generate-card', [IdCardTemplateController::class, 'generateCard'])->name('id-card.generate.store');
        Route::post('/regenerate-card/{employee}', [IdCardTemplateController::class, 'regenerateCard'])->name('regenerate-card');
    
        // Generated Cards
        Route::prefix('generated-cards')->group(function () {
            Route::get('/', [IdCardTemplateController::class, 'generatedCards'])->name('generated-cards.index');
            Route::get('/{id}/view', [IdCardTemplateController::class, 'viewCard'])->name('generated-cards.view');
            Route::get('/{id}/download', [IdCardTemplateController::class, 'downloadCard'])->name('generated-cards.download');
        });
    
        // Image route (alternative)
        Route::get('/image/{path}', [IdCardTemplateController::class, 'image'])->name('image')->where('path', '.*');
    });
    
    Route::prefix('institute')->middleware(['auth'])->group(function () {
        // Student ID Card Templates
        Route::prefix('student-id-card-templates')->name('student-id-card-templates.')->group(function () {
            Route::get('/', [StudentIdCardController::class, 'index'])->name('index');
            Route::get('/create', [StudentIdCardController::class, 'create'])->name('create');
            Route::post('/store', [StudentIdCardController::class, 'store'])->name('store');
            Route::post('/preview', [StudentIdCardController::class, 'preview'])->name('preview');
            Route::get('/{id}/edit', [StudentIdCardController::class, 'edit'])->name('edit');
            Route::post('/{id}/update', [StudentIdCardController::class, 'update'])->name('update');
            Route::delete('/{id}', [StudentIdCardController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/set-default', [StudentIdCardController::class, 'setDefault'])->name('set-default');
            Route::get('/{id}/preview', [StudentIdCardController::class, 'previewTemplate'])->name('preview-template');
            Route::post('/{id}/reset', [StudentIdCardController::class, 'resetToDefault'])->name('reset');
        });
    
        // Student ID Card Generation
        Route::prefix('student-id-card')->name('student-id-card.')->group(function () {
            Route::get('/generate/{student}', [StudentIdCardController::class, 'showGeneratePage'])->name('generate');
            Route::post('/preview-card', [StudentIdCardController::class, 'previewCard'])->name('preview');
            Route::post('/generate', [StudentIdCardController::class, 'generateCard'])->name('generate.store');
            Route::post('/regenerate/{student}', [StudentIdCardController::class, 'regenerateCard'])->name('regenerate');
        });
    
        // Generated Student ID Cards
        Route::prefix('generated-student-cards')->name('generated-student-cards.')->group(function () {
            Route::get('/', [StudentIdCardController::class, 'generatedCards'])->name('index');
            Route::get('/{card}/view', [StudentIdCardController::class, 'viewCard'])->name('view');
            Route::get('/{card}/download', [StudentIdCardController::class, 'downloadCard'])->name('download');
        });
    });
    // Image serving
    Route::get('/image/{path}', [StudentIdCardController::class, 'image'])->name('image');
    
    Route::prefix('student/lesson-planner')->name('student.lesson-planner.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', fn() => view('instituteAdmin.student.Lesson-planner.dashboard'))->name('dashboard');
        Route::get('/plans', [LessonPlanController::class, 'studentPlans'])->name('plans');
        Route::get('/performance', [LessonPlanController::class, 'studentPerformance'])->name('performance');
        Route::get('/student-details', [LessonPlanController::class, 'studentDetails'])->name('student-details');
        Route::get('/plan-detail/{id}', fn($id) => view('instituteAdmin.student.Lesson-planner.plan-detail', ['planId' => $id]))->name('plan-detail');
        Route::get('/coverage', fn() => view('instituteAdmin.student.Lesson-planner.coverage'))->name('coverage');
        Route::post('/media-view', [LessonPlanController::class, 'recordStudentMediaView'])->name('media-view');

        Route::get('/', fn() => redirect()->route('student.lesson-planner.dashboard'));

        Route::get('/student-dashboard', fn() => redirect()->route('student.lesson-planner.dashboard'));
        Route::get('/student-suplans', fn() => redirect()->route('student.lesson-planner.plans'));
        Route::get('/student-plan-detail/{id}', fn($id) => redirect()->route('student.lesson-planner.plan-detail', $id));
        Route::get('/student-coverage', fn() => redirect()->route('student.lesson-planner.coverage'));

        Route::get('/data', function () {
            try {
                $controller = app(\App\Http\Controllers\institute\Admin\LessonPlanController::class);
                if (method_exists($controller, 'getStudentPlans')) {
                    $plans = $controller->getStudentPlans();
                    return response()->json($plans);
                }
            } catch (\Exception $e) {
                \Log::error('Student lesson planner data error: ' . $e->getMessage());
            }

            return response()->json([]);
        })->name('data');
    });
    
    Route::prefix('admin-lesson-planner')->name('lesson-planner.')->group(function () {
        Route::view('/', 'instituteAdmin.Lesson-planner.dashboard')->name('dashboard');
        Route::view('/plans', 'instituteAdmin.Lesson-planner.plans')->name('plans');
        Route::get('/calendar-data', [LessonPlanController::class, 'calendarData'])->name('calendar.data');
        Route::get('/coverage-data', [LessonPlanController::class, 'coverageData'])->name('coverage.data');
        Route::patch('/{id}/status', [LessonPlanController::class, 'updateStatus'])->name('status.update');
        Route::post('/store', [LessonPlanController::class, 'store'])->name('store');
        Route::patch('/topic/{topicId}/coverage', [LessonPlanController::class, 'updateTopicCoverage'])->name('topic.coverage.update');
        Route::get('/file/download/{topicId}/{fileIndex}', [LessonPlanController::class, 'downloadFile'])->name('file.download');
    
        // Test data endpoints (for development/testing)
        Route::get('/test-data', [LessonPlanController::class, 'generateTestData'])->name('test-data.generate');
        Route::delete('/test-data', [LessonPlanController::class, 'clearTestData'])->name('test-data.clear');
    
        Route::get('/new', function () {
            $user = Auth::user();
            $categories = collect();
            $employeeDepartments = collect();
    
            if ($user && $user->institute_id) {
                $categories = DepartmentCategory::where('institute_id', $user->institute_id)->get();
    
                if ($user->hasRole('employee') || $user->hasRole('Teacher')) {
                    $employee = EmployeeDetails::where('user_id', $user->id)->where('institute_id', $user->institute_id)->first();
                    if ($employee) {
                        $employeeDepartments = Departments::where('department_id', $employee->department_id)
                            ->where('institute_id', $user->institute_id)
                            ->get();
                    }
                }
            }
            return view('instituteAdmin.Lesson-planner.new', compact('categories', 'employeeDepartments'));
        })->name('new');
        
        Route::get('/review', [LessonPlanController::class, 'review'])->name('review');
        Route::get('/studentDetail', [LessonPlanController::class, 'studentDetail'])->name('studentDetail');
        Route::view('/coverage', 'instituteAdmin.Lesson-planner.coverage')->name('coverage');
        Route::view('/coverage/{id}/edit', 'instituteAdmin.Lesson-planner.coverage_edit')->name('coverage.edit');
        Route::get('/reports', [LessonPlanController::class, 'reports'])->name('reports');
        Route::get('/reports/csv', [LessonPlanController::class, 'exportReportsCsv'])->name('reports.csv');
        Route::get('/reports/pdf', [LessonPlanController::class, 'exportReportsPdf'])->name('reports.pdf');
    });

    Route::get('student/Lesson-planner/{any?}', function ($any = null) {
        if ($any) {
            return redirect('/student/lesson-planner/' . $any);
        }
        return redirect('/student/lesson-planner/dashboard');
    })->where('any', '.*');
    
    Route::prefix('roll-numbers')->middleware(['auth'])->group(function () {
        // Main page
        Route::get('/', [RollNumberController::class, 'index'])->name('roll-numbers.index');
    
        // AJAX endpoints
        Route::get('/get-courses', [RollNumberController::class, 'getCourses'])->name('roll-numbers.get-courses');
        Route::get('/get-batches', [RollNumberController::class, 'getBatches'])->name('roll-numbers.get-batches');
        Route::get('/get-academic-years', [RollNumberController::class, 'getAcademicYears'])->name('roll-numbers.get-academic-years');
        Route::get('/get-sections', [RollNumberController::class, 'getSections'])->name('roll-numbers.get-sections');
        Route::get('/get-students', [RollNumberController::class, 'getStudents'])->name('roll-numbers.get-students');
    
        // Assignment endpoints
        Route::post('/assign-single', [RollNumberController::class, 'assignSingle'])->name('roll-numbers.assign-single');
        Route::post('/bulk-assign', [RollNumberController::class, 'bulkAssign'])->name('roll-numbers.bulk-assign');
        Route::post('/assign-all', [RollNumberController::class, 'assignAll'])->name('roll-numbers.assign-all');
        Route::post('/reassign-section', [RollNumberController::class, 'reassignSection'])->name('roll-numbers.reassign-section');
    
        // Export
        Route::get('/export', [RollNumberController::class, 'export'])->name('roll-numbers.export');
    });
    
    
    // Employee Journey
Route::prefix('institute-admin')->group(function () {
    Route::get('/employee-journey/{id}', [EmployeeJourneyController::class, 'index'])->name('employees.journey');
    Route::get('/employee-journey/api/{id}', [EmployeeJourneyController::class, 'getJourneyData'])->name('employees.journey.api');
});
// Employee Self Journey
    Route::get('/employee-self-journey/', [EmployeeSelfJourneyController::class, 'index'])->name('employees.self.journey');
    Route::get('/employee-journey/api/{id}', [EmployeeSelfJourneyController::class, 'getJourneyData'])->name('employees.self.journey.api');
// Employee Document Request Routes
Route::prefix('employee/document-requests')->middleware(['auth'])->group(function () {
    Route::post('/store', [EmployeeDocumentRequestController::class, 'store'])->name('employee.document.request.store');
    Route::get('/{employeeId}', [EmployeeDocumentRequestController::class, 'getEmployeeRequests'])->name('employee.document.requests.list');
    Route::get('/status', [EmployeeDocumentRequestController::class, 'getRequestStatus'])->name('employee.document.request.status');
    Route::put('/{requestId}/cancel', [EmployeeDocumentRequestController::class, 'cancel'])->name('employee.document.request.cancel');
});

// Admin Document Request Routes
Route::prefix('admin/document-requests')->middleware(['auth'])->group(function () {
    Route::get('/pending', [EmployeeDocumentRequestController::class, 'getPendingRequests'])->name('admin.document.requests.pending');
    Route::put('/{requestId}/process', [EmployeeDocumentRequestController::class, 'process'])->name('admin.document.requests.process');
    Route::get('/statistics', [EmployeeDocumentRequestController::class, 'getStatistics'])->name('admin.document.requests.statistics');
});

Route::get('/employee/document-requests/usage/{employeeId}', [EmployeeDocumentRequestController::class, 'getUsageStats'])
    ->name('employee.document.requests.usage');
Route::get('/api/employee/document-requests/usage/{employeeId}', [EmployeeDocumentRequestController::class, 'getUsageStats']);



    // External Onboarding Routes (No auth required - Public)
    Route::prefix('external')->name('external.')->group(function () {
    // Show the self-onboarding form
    Route::get('/onboarding', [EmployeeDetailsController::class, 'showExternalOnboardingForm'])->name('form');

    // Store the self-onboarding data
    Route::post('/Storeonboarding', [EmployeeDetailsController::class, 'storeExternalOnboarding'])
        ->name('onboarding.store');

    // Success page after onboarding
    Route::get('/onboarding/success', [EmployeeDetailsController::class, 'externalOnboardingSuccess'])
        ->name('onboarding.success');

    // OTP verification routes
    Route::post('/onboarding/send-mobile-otp', [EmployeeDetailsController::class, 'sendExternalMobileOtp'])
        ->name('onboarding.send-mobile-otp');
    Route::post('/onboarding/verify-mobile-otp', [EmployeeDetailsController::class, 'verifyExternalMobileOtp'])
        ->name('onboarding.verify-mobile-otp');
    Route::post('/onboarding/send-email-otp', [EmployeeDetailsController::class, 'sendExternalEmailOtp'])
        ->name('onboarding.send-email-otp');
    Route::post('/onboarding/verify-email-otp', [EmployeeDetailsController::class, 'verifyExternalEmailOtp'])
        ->name('onboarding.verify-email-otp');
            
        //STudent routes
        Route::get('/student-onboarding', [StudentonboardController::class, 'showExternalStudentOnboarding'])
        ->name('student.onboarding.form');
        Route::post('/student-onboarding/save', [StudentonboardController::class, 'handleExternalStudentFormSubmission'])
            ->name('student.onboarding.store');
        Route::post('/student-onboarding/send-mobile-otp', [EmployeeDetailsController::class, 'sendExternalMobileOtp'])
            ->name('student.onboarding.send-mobile-otp');
        Route::post('/student-onboarding/verify-mobile-otp', [EmployeeDetailsController::class, 'verifyExternalMobileOtp'])
            ->name('student.onboarding.verify-mobile-otp');
        Route::post('/student-onboarding/send-email-otp', [EmployeeDetailsController::class, 'sendExternalEmailOtp'])
            ->name('student.onboarding.send-email-otp');
        Route::post('/student-onboarding/verify-email-otp', [EmployeeDetailsController::class, 'verifyExternalEmailOtp'])
            ->name('student.onboarding.verify-email-otp');
    });
    
    
    Route::post('/external-onboarding/verify-pan', [EmployeeDetailsController::class, 'verifyPan'])->name('external.onboarding.verify-pan');
    
    
    Route::get('/face-api/{filename}', function ($filename) {
        $path = public_path('face-api/' . $filename);
    
        abort_unless(file_exists($path), 404);
    
        return response()->file($path, [
            'Content-Type' => str_ends_with($filename, '.json')
                ? 'application/json'
                : 'application/octet-stream',
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    })->where('filename', '.*');

    // ==========================================
    // CAMPUS AMENITIES ROUTES
    // ==========================================
    
    // Main Campus Amenities Page
    Route::get('/campus/amenities', [CampusAmenitiesController::class, 'index'])
        ->name('institute.admin.campus.amenities');
    
    // Get amenities for a building
    Route::get('/institute/admin/campus/amenities/{buildingId}', [CampusAmenitiesController::class, 'getAmenities'])
        ->name('institute.admin.campus.amenities.get');
    
    // Update amenity count
    Route::put('/campus/amenities/{amenityId}/count', [CampusAmenitiesController::class, 'updateCount'])
        ->name('institute.admin.campus.amenities.count.update');
    
    // Get amenity units
    Route::get('/institute/admin/campus/amenities/{amenityId}/units', [CampusAmenitiesController::class, 'getAmenityUnits'])
        ->name('institute.admin.campus.amenities.units.get');
    
    // Create units for an amenity
    Route::post('/institute/admin/campus/amenities/{amenityId}/units', [CampusAmenitiesController::class, 'createUnits'])
        ->name('institute.admin.campus.amenities.units.create');
    
    // Get a single unit
    Route::get('/campus/amenity-unit/{unitId}', [CampusAmenitiesController::class, 'getUnit'])
        ->name('institute.admin.campus.amenity.unit.get');

    
    
    // Update unit specifications
    Route::put('/institute/admin/campus/amenity-unit/{unitId}/specifications', [CampusAmenitiesController::class, 'updateUnitSpecifications'])
        ->name('institute.admin.campus.amenity.unit.specifications.update');
    
    // Show unit specifications page
    Route::get('/institute/admin/campus/amenity-unit/{unitId}/specifications', [CampusAmenitiesController::class, 'showUnitSpecificationsPage'])
        ->name('institute.admin.campus.amenity.unit.specifications.page');
        
    // View Units Page
    Route::get('/institute/admin/campus/amenities/{amenityId}/units/view', [CampusAmenitiesController::class, 'viewUnitsPage'])
        ->name('institute.admin.campus.amenities.units.view');
        
    
    Route::prefix('institute/admin')->group(function () {
    
    // ==========================================
    // ASSET CATEGORIES ROUTES
    // ==========================================
    Route::get('/asset-categories', [AssetCategoryController::class, 'index'])
        ->name('institute.admin.asset-categories.index');
    
    Route::get('/asset-categories/{id}/assets', [AssetCategoryController::class, 'showAssets'])
        ->name('institute.admin.asset-categories.assets');
    
    Route::get('/asset-categories/{id}', [AssetCategoryController::class, 'show']);
    Route::post('/asset-categories', [AssetCategoryController::class, 'store']);
    Route::put('/asset-categories/{id}', [AssetCategoryController::class, 'update']);
    Route::delete('/asset-categories/{id}', [AssetCategoryController::class, 'destroy']);

    // ==========================================
    // AMENITIES ROUTES
    // ==========================================
    
    // Main Amenities Page
    Route::get('/amenities', [AmenitiesController::class, 'index'])
        ->name('institute.admin.amenities.index');
    
    // API Routes
    Route::get('/amenities/data', [AmenitiesController::class, 'getAmenitiesData'])
        ->name('institute.admin.amenities.data');
    
    Route::post('/amenities/save', [AmenitiesController::class, 'saveAmenities'])
        ->name('institute.admin.amenities.save');
    
    Route::post('/amenities/sync', [AmenitiesController::class, 'syncAmenities'])
        ->name('institute.admin.amenities.sync');
    
    // Selected Amenities Routes
    Route::get('/selected-amenities', [AmenitiesController::class, 'showSelectedAmenities'])
        ->name('institute.admin.selected.amenities');
    
    Route::get('/selected-amenities/data', [AmenitiesController::class, 'getSelectedAmenitiesData'])
        ->name('institute.admin.selected.amenities.data');
    
    Route::delete('/selected-amenities/{amenityId}', [AmenitiesController::class, 'removeSelectedAmenity'])
        ->name('institute.admin.selected.amenities.remove');
    
    // Category Assets View
    Route::get('/category/{categoryId}/amenities', [AmenitiesController::class, 'showCategoryAssets'])
        ->name('institute.admin.category.amenities');
    
    // Asset Routes
    Route::get('/amenities/asset/{assetId}', [AmenitiesController::class, 'getAsset'])
        ->name('institute.admin.amenities.asset');
    
    Route::put('/amenities/asset/{assetId}/specifications', [AmenitiesController::class, 'updateSpecifications'])
        ->name('institute.admin.amenities.update.specifications');
    
    // Building Amenities Routes
    Route::get('/buildings/{buildingId}/amenities', [AmenitiesController::class, 'getBuildingAmenities'])
        ->name('institute.admin.buildings.amenities');
    
    Route::put('/amenities/{amenityId}/count', [AmenitiesController::class, 'updateCount'])
        ->name('institute.admin.amenities.update.count');
    
    // Amenity Units Routes (Original)
    Route::post('/amenities/{amenityId}/units', [AmenitiesController::class, 'createUnits'])
        ->name('institute.admin.amenities.units.create');
    
    Route::get('/amenities/{amenityId}/units', [AmenitiesController::class, 'getAmenityUnits'])
        ->name('institute.admin.amenities.units.get');
    
    Route::get('/amenity-unit/{unitId}', [AmenitiesController::class, 'getUnit'])
        ->name('institute.admin.amenity.unit.get');
    
    Route::put('/amenity-unit/{unitId}/specifications', [AmenitiesController::class, 'updateUnitSpecifications'])
        ->name('institute.admin.amenity.unit.specifications.update');
    
    Route::get('/amenity-unit/{unitId}/specifications', [AmenitiesController::class, 'showUnitSpecificationsPage'])
        ->name('institute.admin.amenity.unit.specifications.page');
    
        
    // Amenities Management Page (NEW)
    Route::get('/amenities-management', [AmenitiesController::class, 'amenitiesManagement'])
    ->name('institute.admin.amenities.management');

    // Get amenities for a building (AJAX)
    Route::get('/amenities/{buildingId?}', [AmenitiesController::class, 'getAmenities'])
        ->name('institute.admin.amenities.get');

    // Assignment Page
    Route::get('/amenities/{amenityId}/assign', [AmenitiesController::class, 'showAssignmentPage'])
        ->name('institute.admin.amenities.assign');

    // Assign units (POST)
    Route::post('/amenities/assign', [AmenitiesController::class, 'assignUnits'])
        ->name('institute.admin.amenities.assign.units');

    Route::get('/campus/amenity-unit-specifications/{unitId}/', [AmenitiesController::class, 'getUnitSpecifications'])
        ->name('institute.admin.campus.amenity.unit.specifications.get');

    // Unassign a unit
    Route::post('/amenities/units/{unitId}/unassign', [AmenitiesController::class, 'unassignUnit'])
        ->name('institute.admin.amenities.units.unassign');

    // Get assignment history for a unit
    Route::get('/amenities/units/{unitId}/history', [AmenitiesController::class, 'getAssignmentHistory'])
        ->name('institute.admin.amenities.units.history');
});

Route::get('/institute/admin/amenities/assigned/refresh', [AmenitiesController::class, 'assignedAmenities'])
    ->name('institute.admin.amenities.assigned.refresh');
// View assignment details by assignment ID
Route::get('/institute/admin/amenities/assigned/view/{assignmentId}', [AmenitiesController::class, 'viewAssignment'])
    ->name('institute.admin.amenities.assigned.view');

// View assignment details by unit ID (fallback)
Route::get('/institute/admin/amenities/assigned/view/unit/{unitId}', [AmenitiesController::class, 'viewAssignmentByUnit'])
    ->name('institute.admin.amenities.assigned.view.unit');
// Assigned Amenities listing

Route::get('/institute/admin/amenities/assigned', [AmenitiesController::class, 'assignedAmenities'])
    ->name('institute.admin.amenities.assigned');
    
    
    /*
    |--------------------------------------------------------------------------
    | Admit Card Management
    |--------------------------------------------------------------------------
    */
    
    // 1. Published examination list
    Route::get('/admit-cards',[AdmitCardController::class, 'index'])->name('admit-cards.index');
    
    Route::get('/student/admit-cards',[AdmitCardController::class, 'studentIndex'])->middleware('auth')
    ->name('student.admit-cards.index');
    
    // 2. Students appearing in selected examination
    Route::get('/admit-cards/{examNameId}/{academicYear}/students',[AdmitCardController::class, 'students']
    )->name('admit-cards.students');
    
    // 3. Generate admit card for one student
    Route::post('/admit-cards/{studentHashId}/generate',[AdmitCardController::class, 'generate']
    )->name('admit-cards.generate');
    
    // 4. Generate admit cards for all eligible students
    Route::post('/admit-cards/generate-all',[AdmitCardController::class, 'generateAll']
    )->name('admit-cards.generateAll');
    
    // 5. Publish one student's admit card
    Route::post('/admit-cards/{studentHashId}/publish',[AdmitCardController::class, 'publish']
    )->name('admit-cards.publish');
    
    // 6. Publish all generated admit cards
    Route::post('/admit-cards/publish-all',[AdmitCardController::class, 'publishAll']
    )->name('admit-cards.publishAll');
    
    // 7. Preview individual admit card
    Route::get('/admit-cards/{studentHashId}/preview',[AdmitCardController::class, 'preview']
    )->name('admit-cards.preview');
    
    // 8. Download individual admit card
    Route::get('/admit-cards/{studentHashId}/download',[AdmitCardController::class, 'download']
    )->name('admit-cards.download');
    
    // 9. Print individual admit card
    Route::get('/admit-cards/{studentHashId}/print',[AdmitCardController::class, 'print']
    )->name('admit-cards.print');
    
    // 10. Download all admit cards
    Route::get('/admit-cards/download-all',[AdmitCardController::class, 'downloadAll']
    )->name('admit-cards.downloadAll');
    
    
    Route::get('/student/admit-cards/{admitCardId}/download',
        [AdmitCardController::class, 'studentDownload']
    )->name('admit-card.student.download');
    
    Route::get(
        '/student/admit-cards/{admitCardId}/view',
        [AdmitCardController::class, 'studentView']
    )->name('admit-cards.student.view');
    
    // 9. Print individual admit card
    Route::get('/student/admit-cards/{admitCardId}/print',
        [AdmitCardController::class, 'studentPrint']
    )->name('admit-cards.student.print');
    
    
    Route::prefix('institute/exam-names')->name('institute.exam-names.')->group(function () {
        Route::get('/', [ExamNameController::class, 'index'])->name('index');
        Route::get('/create', [ExamNameController::class, 'create'])->name('create');
        Route::post('/', [ExamNameController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ExamNameController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ExamNameController::class, 'update'])->name('update');
        Route::delete('/{id}', [ExamNameController::class, 'destroy'])->name('destroy');
    });
    
    //Asset Management routes
    Route::prefix('institute/admin/asset-management')->name('asset-management.')->middleware('auth')->group(function () {
        Route::get('/', [AssetManagementController::class, 'dashboard'])->name('dashboard');

        Route::get('/categories', [AssetManagementController::class, 'categories'])->name('categories.index');
        Route::post('/categories', [AssetManagementController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{id}', [AssetManagementController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{id}', [AssetManagementController::class, 'destroyCategory'])->name('categories.destroy');
        Route::get('/categories/{categoryId}/assets', [AssetManagementController::class, 'categoryAssets'])->name('categories.assets');

        Route::get('/assets', [AssetManagementController::class, 'assets'])->name('assets.index');
        Route::get('/assets/create', [AssetManagementController::class, 'createAsset'])->name('assets.create');
        Route::post('/assets', [AssetManagementController::class, 'storeAsset'])->name('assets.store');
        Route::get('/assets/{assetId}', [AssetManagementController::class, 'showAsset'])->name('assets.show');
        Route::put('/assets/{assetId}', [AssetManagementController::class, 'updateAsset'])->name('assets.update');

        Route::get('/assets/{assetId}/units', [AssetManagementController::class, 'units'])->name('units.index');
        Route::post('/assets/{assetId}/units', [AssetManagementController::class, 'storeUnits'])->name('units.store');
        Route::put('/units/{unitId}', [AssetManagementController::class, 'updateUnit'])->name('units.update');

        // LOCATION ALLOCATION
        Route::get('/allocation/location-options', [AssetManagementController::class, 'locationOptions'])->name('allocation.locations');
        Route::get('/allocation/{assetId?}', [AssetManagementController::class, 'allocation'])->name('allocation');
        Route::post('/allocation', [AssetManagementController::class, 'allocate'])->name('allocation.store');

        // PERSON / DEPARTMENT ASSIGNMENT
        Route::get('/assignment', [AssetManagementController::class, 'assignment'])->name('assignment');
        Route::get('/assignment/students', [AssetManagementController::class, 'students'])->name('assignment.students');
        Route::post('/assignment', [AssetManagementController::class, 'assign'])->name('assignment.store');
        Route::post('/assignment/{unitId}/unassign', [AssetManagementController::class, 'unassign'])->name('assignment.unassign');

        Route::get('/reports', [AssetManagementController::class, 'reports'])->name('reports');
        Route::get('/allocation-history', [AssetManagementController::class, 'allocationHistory'])->name('allocation-history');
        Route::get('/assignment-history', [AssetManagementController::class, 'assignmentHistory'])->name('assignment-history');
    });

    // End Dynamic Link routes
    
Route::get('/image/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!File::exists($fullPath)) {
        abort(404);
    }
    return Response::file($fullPath);
})->where('path', '.*')->name('image');
// Loan Schema Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/loan-schema-form', [LoanSchemaConfigurationController::class, 'showForm'])->name('loan-schema.form');
    Route::post('/store-loan-schema', [LoanSchemaConfigurationController::class, 'storeDetails'])->name('loan-schema.store');
    Route::get('/get-saved-configuration', [LoanSchemaConfigurationController::class, 'getSavedConfiguration'])->name('loan-schema.get-saved');
});
Route::get('/employee/shifts-by-date-range', [EmployeeShiftController::class, 'getShiftsByDateRange'])
    ->name('employee.shifts.by.date.range');
Route::get('/employee/upcoming-shifts', [EmployeeShiftController::class, 'getUpcomingShifts']);
