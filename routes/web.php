<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContractAddendumController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractRenewalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OfferingLetterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::redirect('/contract-employees', '/employees');

    // 1. Employee Management
    Route::get('employees/{employee}/renew', [ContractRenewalController::class, 'create'])->name('employees.renew');
    Route::post('employees/{employee}/renew', [ContractRenewalController::class, 'store'])->name('employees.renew.store');
    Route::resource('employees', EmployeeController::class);

    // 2. Offering Letters (Surat Penawaran Kerja)
    Route::get('offering-letters', [OfferingLetterController::class, 'index'])->name('offering-letters.index');
    Route::get('employees/{employee}/offering-letters/create', [OfferingLetterController::class, 'create'])->name('offering-letters.create');
    Route::post('employees/{employee}/offering-letters', [OfferingLetterController::class, 'store'])->name('offering-letters.store');
    Route::get('offering-letters/{offering_letter}', [OfferingLetterController::class, 'show'])->name('offering-letters.show');
    Route::get('offering-letters/{offering_letter}/edit', [OfferingLetterController::class, 'edit'])->name('offering-letters.edit');
    Route::put('offering-letters/{offering_letter}', [OfferingLetterController::class, 'update'])->name('offering-letters.update');
    Route::patch('offering-letters/{offering_letter}/status', [OfferingLetterController::class, 'updateStatus'])->name('offering-letters.status');
    Route::get('offering-letters/{offering_letter}/print', [OfferingLetterController::class, 'print'])->name('offering-letters.print');

    // 3. Contracts (PKWT, MT, MAGANG)
    Route::get('contracts', [ContractController::class, 'index'])->name('contracts.index');
    Route::get('contracts/create', [ContractController::class, 'create'])->name('contracts.create');
    Route::post('contracts', [ContractController::class, 'store'])->name('contracts.store');
    Route::get('contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');
    Route::get('contracts/{contract}/print', [ContractController::class, 'print'])->name('contracts.print');

    // 4. Contract Addendums (Adendum Kontrak)
    Route::get('contract-addendums', [ContractAddendumController::class, 'index'])->name('addendums.index');
    Route::get('contracts/{contract}/addendums/create', [ContractAddendumController::class, 'create'])->name('addendums.create');
    Route::post('contracts/{contract}/addendums', [ContractAddendumController::class, 'store'])->name('addendums.store');
    Route::get('contract-addendums/{addendum}', [ContractAddendumController::class, 'show'])->name('addendums.show');
    Route::get('contract-addendums/{addendum}/print', [ContractAddendumController::class, 'print'])->name('addendums.print');

    // 5. User Management (Khusus Admin)
    Route::resource('users', UserController::class)->except(['show']);
});
