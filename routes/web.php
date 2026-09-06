<?php

use App\Http\Controllers\AcademicianController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GrantController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::pattern('grant', '[0-9]+');
Route::pattern('milestone', '[0-9]+');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'redirect'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/grants/{grant}', [GrantController::class, 'show'])->name('grants.show');
});

Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('academicians', AcademicianController::class)->except(['show']);
    Route::resource('grants', GrantController::class)->except(['show']);
});

Route::middleware(['auth', 'role:Leader'])->group(function () {
    Route::get('/leader/dashboard', [DashboardController::class, 'leader'])->name('leader.dashboard');
    Route::get('/leader/grants', [DashboardController::class, 'leaderGrants'])->name('leader.grants');
    Route::get('grants/{grant}/milestones/create', [MilestoneController::class, 'create'])->name('milestones.create');
    Route::get('/grants/{grant}/milestones', [MilestoneController::class, 'index'])->name('milestones.index');
    Route::post('milestones', [MilestoneController::class, 'store'])->name('milestones.store');
    Route::patch('/milestones/{milestone}/status', [MilestoneController::class, 'updateStatus'])->name('milestones.updateStatus');
    Route::get('milestones/{milestone}/edit', [MilestoneController::class, 'edit'])->name('milestones.edit');
    Route::put('milestones/{milestone}', [MilestoneController::class, 'update'])->name('milestones.update');
    Route::delete('milestones/{milestone}', [MilestoneController::class, 'destroy'])->name('milestones.destroy');
});

Route::middleware(['auth', 'role:Academic'])->group(function () {
    Route::get('/academic/dashboard', [DashboardController::class, 'academic'])->name('academic.dashboard');
    Route::get('/academic/grants', [DashboardController::class, 'academicGrants'])->name('academic.grants');
});

require __DIR__.'/auth.php';
