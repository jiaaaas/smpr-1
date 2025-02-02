<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\QrcodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PerformanceReportController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Employee Management
Route::get('/users', [UserController::class, 'index'])->name('users.index'); 
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');

// QrCode
Route::get('/qrcodes', [QrcodeController::class, 'show'])->name('qrcodes.index');
Route::get('qrcodes/regenerate', [QrcodeController::class, 'regenerate'])->name('qrcodes.regenerate');

// Leave
Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
Route::put('/leaves/{leave}/status', [LeaveController::class, 'updateStatus'])->name('leaves.updateStatus');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Absent List
Route::get('/absent-users', [DashboardController::class, 'getAbsentUsers'])->name('absent.users');

// Leave List
Route::get('/leave-applications', [DashboardController::class, 'getLeaveApplications'])->name('leave.applications');

// Attendance
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::post('/attendance/update', [AttendanceController::class, 'update'])->name('attendance.update');

// Export
Route::get('/attendance/export/excel', [AttendanceController::class, 'exportExcel'])->name('attendance.export.excel');
Route::get('/attendance/export/pdf', [AttendanceController::class, 'exportPDF'])->name('attendance.export.pdf');

// Report
Route::get('/performance-report', [PerformanceReportController::class, 'index'])->name('performanceReport.index');
Route::post('/performance-report/generate', [PerformanceReportController::class, 'generate'])->name('performanceReport.generate');
Route::get('/performance-report/{id}', [PerformanceReportController::class, 'show'])->name('performanceReport.show');
Route::post('/performance-report/calculate-average-performance', [PerformanceReportController::class, 'calculateAveragePerformance'])->name('performanceReport.calculateAveragePerformance');
Route::get('/performance-report/{id}/export-pdf', [PerformanceReportController::class, 'exportPDF'])->name('performanceReport.exportPDF');
Route::get('/performance-report/{id}/export-excel', [PerformanceReportController::class, 'exportExcel'])->name('performanceReport.exportExcel');



