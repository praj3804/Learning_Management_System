<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/batch', [AdminController::class, 'createBatch'])->name('batch.create');
    Route::delete('/batch/{id}', [AdminController::class, 'deleteBatch'])->name('batch.delete');
    
    Route::post('/video', [AdminController::class, 'uploadVideo'])->name('video.upload');
    Route::delete('/video/{id}', [AdminController::class, 'deleteVideo'])->name('video.delete');
    
    Route::post('/quiz', [AdminController::class, 'createQuiz'])->name('quiz.create');
    Route::get('/quiz/{id}/edit', [AdminController::class, 'editQuiz'])->name('quiz.edit');
    Route::put('/quiz/{id}', [AdminController::class, 'updateQuiz'])->name('quiz.update');
    Route::delete('/quiz/{id}', [AdminController::class, 'deleteQuiz'])->name('quiz.delete');
});

// Student Routes
Route::middleware(['auth', 'role:Student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::post('/video/progress', [StudentController::class, 'updateProgress'])->name('video.progress');
    Route::get('/quiz/{id}', [StudentController::class, 'showQuiz'])->name('quiz.show');
    Route::post('/quiz/{id}/submit', [StudentController::class, 'submitQuiz'])->name('quiz.submit');
    Route::get('/certificate/download', [StudentController::class, 'downloadCertificate'])->name('certificate.download');
});
