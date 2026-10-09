<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\LostItemController;
use App\Http\Controllers\FoundItemController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RecoveryHistoryController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AdminSecurityController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\ChatbotController;

/*
|--------------------------------------------------------------------------
| Web Routes - SJUT Lost Property Recovery System
|--------------------------------------------------------------------------
*/

// Public Institutional Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('landing');

// Downloadable & Online User Guide
Route::get('/guide', [GuideController::class, 'index'])->name('guide.index');
Route::get('/download-guide', [GuideController::class, 'download'])->name('guide.download');

// Interactive Chatbot Assistant API
Route::post('/chatbot/query', [ChatbotController::class, 'query'])->name('chatbot.query');

// Public Safe QR Code Scan Page (No Auth Required to View Safe Info)
Route::get('/v/{token}', [QrCodeController::class, 'publicScan'])->name('qr.scan');

// Guest Auth Routes (Email Verification & Account Creation)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Registration Flow (Step 1: Enter Email -> Step 2: Verify OTP -> Step 3: Complete Details)
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register/send-code', [AuthController::class, 'sendVerificationCode'])->name('register.send-code');
    Route::get('/register/verify', [AuthController::class, 'showVerifyCode'])->name('register.verify');
    Route::post('/register/verify', [AuthController::class, 'verifyCode']);
    Route::post('/register/resend-code', [AuthController::class, 'resendCode'])->name('register.resend-code');
    Route::get('/register/complete', [AuthController::class, 'showCompleteRegister'])->name('register.complete');
    Route::post('/register/complete', [AuthController::class, 'completeRegister']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/workspace', [WorkspaceController::class, 'index'])->name('workspace');
    Route::redirect('/dashboard', '/workspace');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile Settings
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Lost Property Routes
    Route::resource('lost-items', LostItemController::class)->except(['edit', 'update']);
    Route::post('/lost-items/{id}/approve', [LostItemController::class, 'approve'])->middleware('role:admin,staff')->name('lost-items.approve');
    Route::post('/lost-items/{id}/reject', [LostItemController::class, 'reject'])->middleware('role:admin,staff')->name('lost-items.reject');
    Route::post('/lost-items/{id}/freeze', [LostItemController::class, 'freeze'])->middleware('role:admin')->name('lost-items.freeze');
    Route::post('/lost-items/{id}/unfreeze', [LostItemController::class, 'unfreeze'])->middleware('role:admin')->name('lost-items.unfreeze');

    // Found Property Routes
    Route::resource('found-items', FoundItemController::class)->except(['edit', 'update']);
    Route::post('/found-items/{id}/approve', [FoundItemController::class, 'approve'])->middleware('role:admin,staff')->name('found-items.approve');
    Route::post('/found-items/{id}/reject', [FoundItemController::class, 'reject'])->middleware('role:admin,staff')->name('found-items.reject');
    Route::post('/found-items/{id}/freeze', [FoundItemController::class, 'freeze'])->middleware('role:admin')->name('found-items.freeze');
    Route::post('/found-items/{id}/unfreeze', [FoundItemController::class, 'unfreeze'])->middleware('role:admin')->name('found-items.unfreeze');

    // Claims Routes
    Route::get('/claims', [ClaimController::class, 'index'])->name('claims.index');
    Route::post('/claims', [ClaimController::class, 'store'])->name('claims.store');
    Route::get('/claims/{id}', [ClaimController::class, 'show'])->name('claims.show');
    Route::patch('/claims/{id}/status', [ClaimController::class, 'updateStatus'])->middleware('role:admin,staff')->name('claims.status');
    Route::get('/claims/{id}/qr', [ClaimController::class, 'showQr'])->name('claims.qr');

    // Case Reviews / Feedback
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Staff Portal Routes
    Route::middleware('role:admin,staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/', [StaffController::class, 'index'])->name('index');
        Route::get('/pending', [StaffController::class, 'pendingReports'])->name('pending');
        Route::get('/intake', [StaffController::class, 'propertyIntake'])->name('intake');
        Route::get('/claims', [StaffController::class, 'claimsQueue'])->name('claims');

        // Staff QR Tag Management Routes
        Route::post('/qr/generate', [QrCodeController::class, 'generate'])->name('qr.generate');
        Route::get('/qr/print/{token}', [QrCodeController::class, 'printTag'])->name('qr.print');
        Route::post('/qr/{id}/regenerate', [QrCodeController::class, 'regenerate'])->name('qr.regenerate');
        Route::post('/qr/{id}/deactivate', [QrCodeController::class, 'deactivate'])->name('qr.deactivate');
    });

    // Admin Registered User Management Routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::put('/admin/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('/admin/security', [AdminSecurityController::class, 'index'])->name('admin.security.index');
    });

    // Property Release QR Verification Terminal
    Route::get('/admin/release-property', [ClaimController::class, 'showReleasePage'])->middleware('role:admin,staff')->name('admin.release');
    Route::post('/admin/verify-release', [ClaimController::class, 'verifyRelease'])->middleware('role:admin,staff')->name('admin.verify-release');

    // Chat / Direct Messaging Routes
    Route::get('/messages/{user?}', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}/fetch', [MessageController::class, 'fetchMessages'])->name('messages.fetch');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('role:admin')->name('categories.store');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->middleware('role:admin')->name('categories.destroy');

    // Notifications (Alerts)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Recovery History Audit Log
    Route::get('/recovery-history', [RecoveryHistoryController::class, 'index'])->name('recovery.index');
});
