<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\institute\Frontend\InstituteOnboardController;   
use App\Http\Controllers\institute\Frontend\addressController;   


require_once __DIR__."/campusdunia.php";

Route::get('/about', function () {
    return view('campusdunia/about');
});
Route::get('/contact', function () {
    return view('campusdunia/contact');
});
Route::get('/creditLine', function () {
    return view('campusdunia/creditLine');
});
Route::get('/how-It-works', function () {
    return view('campusdunia/howItWorks');
});
Route::get('/', function () {
    return view('campusdunia/index');
});
Route::get('/institute', function () {
    return view('campusdunia/institute');
});
Route::get('/privacy', function () {
    return view('campusdunia/privacy'); 
});
Route::get('/smart-Card', function () {
    return view('campusdunia/smartCard');
});
Route::get('/student', function () {
    return view('campusdunia/student');
});
Route::get('/terms', function () {
    return view('campusdunia/terms');
});
Route::get('/pay-fee-new', function () {
    return view('campusdunia/pay-fee-new');
});
Route::get('/erp', function () {
    return view('campusdunia/erp');
});
Route::get('/login_page', function () {
    return view('campusdunia/loginPage');
});
Route::get('/register-otp', function () {
    return view('campusdunia/registerOtp');
});
Route::get('/register-institute', function () {
    return view('campusdunia/registerInstitute');
});
Route::post('/institute/store', [InstituteOnboardController::class, 'store'])->name('institute.store');
Route::post('/get-states',[addressController::class, 'getStatesByCountry'])->name('get.states');
Route::post('/get-cities',[addressController::class, 'getCitiesByState'])->name('get.cities');
// Route::post('/api/institute/register', [InstituteController::class, 'register'])->name('institute.register');