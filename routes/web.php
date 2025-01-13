<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AcademicianController;
use App\Http\Controllers\GrantController;
use App\Http\Controllers\MilestoneController;
use Illuminate\Support\Facades\Route;

// Welcome Page
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (Shared Across Roles)
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'Admin') {
        return redirect()->route('admin.dashboard');
    } elseif (auth()->user()->role === 'ProjectLeader') {
        return redirect()->route('leader.dashboard');
    } elseif (auth()->user()->role === 'Academic') {
        return redirect()->route('academic.dashboard');
    }
    abort(403, 'Unauthorized'); // Unauthorized access
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile Routes (Authenticated Users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Academicians CRUD
    Route::resource('academicians', AcademicianController::class);

   
    // Grants CRUD
    Route::post('/grants', [GrantController::class, 'store'])->name('grants.store');
Route::resource('grants', GrantController::class);


});

// Project Leader Routes
Route::middleware(['auth', 'role:ProjectLeader'])->group(function () {
    Route::get('/leader/dashboard', [GrantController::class, 'leaderDashboard'])->name('leader.dashboard');
    Route::get('/leader/grants', [GrantController::class, 'leaderDashboard'])->name('leader.grants');

    // Milestones Management
    Route::get('grants/{grant}/milestones/create', [MilestoneController::class, 'create'])->name('milestones.create');
    Route::get('/grants/{grant}/milestones', [MilestoneController::class, 'index'])->name('milestones.index');

    Route::post('milestones', [MilestoneController::class, 'store'])->name('milestones.store');
    Route::patch('/milestones/{milestone}/status', [MilestoneController::class, 'updateStatus'])->name('milestones.updateStatus');
    Route::get('milestones/{milestone}/edit', [MilestoneController::class, 'edit'])->name('milestones.edit');
    Route::put('milestones/{milestone}', [MilestoneController::class, 'update'])->name('milestones.update');
    Route::delete('milestones/{milestone}', [MilestoneController::class, 'destroy'])->name('milestones.destroy');
});


// Academic Routes
Route::middleware(['auth', 'role:Academic'])->group(function () {
    Route::get('/academic/dashboard', [GrantController::class, 'academicDashboard'])->name('academic.dashboard');
});

// Authentication Routes
require __DIR__.'/auth.php';
