<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Employee\BookingController as EmployeeBookingController;
use App\Http\Controllers\Employee\CalendarController as EmployeeCalendarController;
use App\Http\Controllers\Employee\CheckinCheckoutController;
use App\Http\Controllers\Employee\CustomerController as EmployeeCustomerController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\ExpenseController as EmployeeExpenseController;
use App\Http\Controllers\Employee\PaymentController as EmployeePaymentController;
use App\Http\Controllers\Owner\ApprovalController as OwnerApprovalController;
use App\Http\Controllers\Owner\AuditLogController as OwnerAuditLogController;
use App\Http\Controllers\Owner\BookingController as OwnerBookingController;
use App\Http\Controllers\Owner\CalendarController as OwnerCalendarController;
use App\Http\Controllers\Owner\CustomerController as OwnerCustomerController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\EmployeeController as OwnerEmployeeController;
use App\Http\Controllers\Owner\ExpenseController as OwnerExpenseController;
use App\Http\Controllers\Owner\MaintenanceController as OwnerMaintenanceController;
use App\Http\Controllers\Owner\PaymentController as OwnerPaymentController;
use App\Http\Controllers\Owner\ReportController as OwnerReportController;
use App\Http\Controllers\Owner\VehicleController as OwnerVehicleController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/', fn () => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-pin', [AuthController::class, 'showForgotPin'])->name('forgot.pin');
Route::post('/forgot-pin/verify', [AuthController::class, 'verifyPhone'])->name('forgot.pin.verify');
Route::get('/reset-pin/{token}', [AuthController::class, 'showResetPinForm'])->name('reset.pin.form');
Route::post('/reset-pin/{token}', [AuthController::class, 'resetPin'])->name('reset.pin.post');

// Owner Routes
Route::middleware(['auth', 'owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/calendar', [OwnerCalendarController::class, 'index'])->name('calendar');
    Route::get('/calendar/events', [OwnerCalendarController::class, 'events'])->name('calendar.events');

    Route::resource('vehicles', OwnerVehicleController::class);
    Route::post('vehicles/{vehicle}/update-status', [OwnerVehicleController::class, 'updateStatus'])->name('vehicles.update-status');

    Route::resource('customers', OwnerCustomerController::class);
    Route::post('customers/{customer}/verify', [OwnerCustomerController::class, 'verify'])->name('customers.verify');
    Route::post('customers/{customer}/documents/{document}/verify', [OwnerCustomerController::class, 'verifyDocument'])->name('customers.documents.verify');

    Route::resource('bookings', OwnerBookingController::class)->only(['index', 'show']);
    Route::post('bookings/{booking}/cancel', [OwnerBookingController::class, 'cancel'])->name('bookings.cancel');

    Route::resource('payments', OwnerPaymentController::class)->only(['index', 'show', 'store']);
    Route::post('payments/{payment}/confirm', [OwnerPaymentController::class, 'confirm'])->name('payments.confirm');

    Route::resource('expenses', OwnerExpenseController::class);
    Route::post('expenses/{expense}/verify', [OwnerExpenseController::class, 'verify'])->name('expenses.verify');

    Route::resource('maintenance', OwnerMaintenanceController::class)->parameters(['maintenance' => 'record']);
    Route::post('maintenance/{record}/update-status', [OwnerMaintenanceController::class, 'updateStatus'])->name('maintenance.update-status');

    Route::resource('employees', OwnerEmployeeController::class);
    Route::post('employees/{employee}/toggle-active', [OwnerEmployeeController::class, 'toggleActive'])->name('employees.toggle-active');
    Route::post('employees/{employee}/reset-pin', [OwnerEmployeeController::class, 'resetPin'])->name('employees.reset-pin');

    Route::resource('approvals', OwnerApprovalController::class)->only(['index', 'show']);
    Route::post('approvals/{approval}/approve', [OwnerApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('approvals/{approval}/reject', [OwnerApprovalController::class, 'reject'])->name('approvals.reject');

    Route::get('/reports', [OwnerReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [OwnerReportController::class, 'export'])->name('reports.export');

    Route::get('/audit-logs', [OwnerAuditLogController::class, 'index'])->name('audit-logs.index');
});

// Employee Routes
Route::middleware(['auth', 'employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
    Route::get('/calendar', [EmployeeCalendarController::class, 'index'])->name('calendar');
    Route::get('/calendar/events', [EmployeeCalendarController::class, 'events'])->name('calendar.events');

    Route::resource('bookings', EmployeeBookingController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
    Route::post('bookings/{booking}/cancel', [EmployeeBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('bookings/{booking}/request-approval', [EmployeeBookingController::class, 'requestApproval'])->name('bookings.request-approval');

    Route::resource('customers', EmployeeCustomerController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
    Route::post('customers/{customer}/verify', [EmployeeCustomerController::class, 'verify'])->name('customers.verify');
    Route::post('customers/{customer}/documents/{document}/verify', [EmployeeCustomerController::class, 'verifyDocument'])->name('customers.documents.verify');
    Route::post('customers/{customer}/documents', [EmployeeCustomerController::class, 'storeDocument'])->name('customers.documents.store');

    Route::get('/checkin-checkout', [CheckinCheckoutController::class, 'index'])->name('checkin-checkout');
    Route::get('/checkin-checkout/{booking}/checkin', [CheckinCheckoutController::class, 'showCheckin'])->name('checkin-checkout.checkin');
    Route::post('/checkin-checkout/{booking}/checkin', [CheckinCheckoutController::class, 'processCheckin'])->name('checkin-checkout.process-checkin');
    Route::get('/checkin-checkout/{booking}/checkout', [CheckinCheckoutController::class, 'showCheckout'])->name('checkin-checkout.checkout');
    Route::post('/checkin-checkout/{booking}/checkout', [CheckinCheckoutController::class, 'processCheckout'])->name('checkin-checkout.process-checkout');
    Route::get('/checkin-checkout/{booking}/travel-doc', [CheckinCheckoutController::class, 'travelDocument'])->name('checkin-checkout.travel-doc');

    Route::get('/checkin-checkout/{booking}/checkin-show', [CheckinCheckoutController::class, 'showCheckin'])->name('checkin.show');
    Route::post('/checkin-checkout/{booking}/checkin-process', [CheckinCheckoutController::class, 'processCheckin'])->name('checkin.process');
    Route::get('/checkin-checkout/{booking}/checkout-show', [CheckinCheckoutController::class, 'showCheckout'])->name('checkout.show');
    Route::post('/checkin-checkout/{booking}/checkout-process', [CheckinCheckoutController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/checkin-checkout/{booking}/travel-doc-show', [CheckinCheckoutController::class, 'travelDocument'])->name('travel-doc');

    Route::resource('payments', EmployeePaymentController::class)->only(['index', 'show', 'store']);

    Route::resource('expenses', EmployeeExpenseController::class)->only(['index', 'create', 'store', 'show']);
});
