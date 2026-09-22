<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentSurveyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SurveyController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AiAnalyticsController;
use App\Http\Controllers\Admin\ProfileController;

// ─── Public Routes ────────────────────────────────────────────────────────────

Route::get('/', function () {
    return redirect()->route('admin.login');
})->name('home');

// Student Survey Form (public)
Route::get('/s/{id}', [StudentSurveyController::class, 'show'])->name('student.survey');
Route::post('/s/{id}/submit', [StudentSurveyController::class, 'submit'])->name('student.survey.submit');

// ─── Admin Authentication ─────────────────────────────────────────────────────

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// ─── Admin Protected Routes ───────────────────────────────────────────────────

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Profile / Account Settings
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Survey Management CRUD
    Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys.index');
    Route::get('/surveys/new', [SurveyController::class, 'create'])->name('surveys.create');
    Route::get('/surveys/builder/{id}', [SurveyController::class, 'builder'])->name('surveys.builder');
    Route::put('/surveys/{id}', [SurveyController::class, 'update'])->name('surveys.update');
    Route::post('/surveys/{id}/status', [SurveyController::class, 'updateStatus'])->name('surveys.status');
    Route::post('/surveys/{id}/toggle-acceptance', [SurveyController::class, 'toggleAcceptingResponses'])->name('surveys.toggle-acceptance');
    Route::delete('/surveys/{id}', [SurveyController::class, 'destroy'])->name('surveys.destroy');

    // Question Management (JSON API endpoint for builder)
    Route::delete('/questions/{id}', [SurveyController::class, 'destroyQuestion'])->name('questions.destroy');

    // Analytics & Respondent Management
    Route::get('/surveys/{id}/analytics', [AnalyticsController::class, 'show'])->name('surveys.analytics');
    Route::get('/surveys/{id}/print-ai', [AnalyticsController::class, 'printAi'])->name('surveys.print-ai');
    Route::get('/surveys/{id}/export', [AnalyticsController::class, 'export'])->name('surveys.export');
    Route::delete('/respondents/{id}', [AnalyticsController::class, 'destroyRespondent'])->name('respondents.destroy');

    // Dedicated AI Analytics & Intelligence Panel
    Route::get('/ai-analytics', [AiAnalyticsController::class, 'index'])->name('ai-analytics.index');
    Route::get('/ai-analytics/{id}', [AiAnalyticsController::class, 'show'])->name('ai-analytics.show');
    Route::post('/ai-analytics/{id}/generate', [AiAnalyticsController::class, 'generate'])->name('ai-analytics.generate');
    Route::delete('/ai-analytics/{id}/delete', [AiAnalyticsController::class, 'deleteAnalysis'])->name('ai-analytics.delete');

    // Document & Excel Business AI Analytics
    Route::get('/document-analytics', [\App\Http\Controllers\Admin\DocumentAnalysisController::class, 'index'])->name('document-analytics.index');
    Route::post('/document-analytics', [\App\Http\Controllers\Admin\DocumentAnalysisController::class, 'store'])->name('document-analytics.store');
    Route::get('/document-analytics/{id}', [\App\Http\Controllers\Admin\DocumentAnalysisController::class, 'show'])->name('document-analytics.show');
    Route::post('/document-analytics/{id}/regenerate', [\App\Http\Controllers\Admin\DocumentAnalysisController::class, 'regenerate'])->name('document-analytics.regenerate');
    Route::post('/document-analytics/{id}/chat', [\App\Http\Controllers\Admin\DocumentAnalysisController::class, 'chat'])->name('document-analytics.chat');
    Route::post('/document-analytics/{id}/chat/rewind', [\App\Http\Controllers\Admin\DocumentAnalysisController::class, 'rewindChat'])->name('document-analytics.chat.rewind');
    Route::delete('/document-analytics/{id}/chat', [\App\Http\Controllers\Admin\DocumentAnalysisController::class, 'clearChat'])->name('document-analytics.chat.clear');
    Route::delete('/document-analytics/{id}', [\App\Http\Controllers\Admin\DocumentAnalysisController::class, 'destroy'])->name('document-analytics.destroy');
});
