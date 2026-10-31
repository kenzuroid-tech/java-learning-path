<?php

use App\Http\Controllers\Admin\BadgeController as AdminBadgeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ExamGroupController as AdminExamGroupController;
use App\Http\Controllers\Admin\MaterialController as AdminMaterialController;
use App\Http\Controllers\Admin\PraktikumController as AdminPraktikumController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Admin\StageController as AdminStageController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Siswa\BadgeController;
use App\Http\Controllers\Siswa\DashboardController;
use App\Http\Controllers\Siswa\MateriController;
use App\Http\Controllers\Siswa\PlacementController;
use App\Http\Controllers\Siswa\StageController;
use App\Http\Controllers\Siswa\UjianController;
use Illuminate\Support\Facades\Route;

// ── Landing page ──────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ── Auth routes (login, register, password reset, etc.) ───────
require __DIR__.'/auth.php';

// ── Siswa routes ──────────────────────────────────────────────
Route::middleware(['auth', 'siswa'])->group(function () {

    // Placement test (accessible before placement_done)
    Route::get('/placement', [PlacementController::class, 'show'])->name('placement.show');
    Route::post('/placement', [PlacementController::class, 'submit'])->name('placement.submit');
    Route::get('/placement/result', [PlacementController::class, 'result'])->name('placement.result');

    // All other siswa routes require placement to be done
    Route::middleware('placement.done')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('siswa.dashboard');

        // Stage detail
        Route::get('/stage/{stage}', [StageController::class, 'show'])->name('siswa.stage.show');

        // Materi
        Route::get('/materi', [MateriController::class, 'index'])->name('siswa.materi.index');
        Route::get('/materi/{material}', [MateriController::class, 'show'])->name('siswa.materi.show');
        Route::post('/materi/{material}/selesai', [MateriController::class, 'markDone'])->name('siswa.materi.done');

        // Ujian
        Route::get('/ujian', [UjianController::class, 'index'])->name('siswa.ujian.index');

        // Badges
        Route::get('/badges', [BadgeController::class, 'index'])->name('siswa.badges.index');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Admin (Superadmin) routes ─────────────────────────────────
Route::middleware(['auth', 'superadmin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // CRUD resources
        Route::resource('stages',      AdminStageController::class);
        Route::resource('materials',   AdminMaterialController::class);
        Route::resource('questions',   AdminQuestionController::class);
        Route::resource('exam-groups', AdminExamGroupController::class);
        Route::resource('praktikum',   AdminPraktikumController::class);
        Route::resource('badges',      AdminBadgeController::class);
        Route::resource('users',       AdminUserController::class);
    });


