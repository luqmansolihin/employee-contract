<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContractRenewalController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [EmployeeController::class, 'index'])->name('home');
    Route::redirect('/contract-employees', '/employees');

    Route::get('employees/{employee}/renew', [ContractRenewalController::class, 'create'])->name('employees.renew');
    Route::post('employees/{employee}/renew', [ContractRenewalController::class, 'store'])->name('employees.renew.store');

    Route::resource('employees', EmployeeController::class);
});
