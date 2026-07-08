<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Admin\AdminDashboardController;
use App\Http\Controllers\Api\V1\Admin\AdminMeterReadingController;
use App\Http\Controllers\Api\V1\Admin\AdminNewsController;
use App\Http\Controllers\Api\V1\Admin\AdminTariffController;
use App\Http\Controllers\Api\V1\Admin\AdminUserController;
use App\Http\Controllers\Api\V1\AnalyticsController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\PasswordResetController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\MeterReadingController;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\ProfileController;
use Illuminate\Support\Facades\Route;

// ─── API v1 ──────────────────────────────────────────────────────────────────
Route::prefix('v1')->name('v1.')->group(function () {

    // ── Public Routes ─────────────────────────────────────────────────────────
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('register', RegisterController::class)->name('register');
        Route::post('login', LoginController::class)->name('login')
             ->middleware('throttle:10,1');
        Route::post('forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('forgot-password');
        Route::post('reset-password', [PasswordResetController::class, 'reset'])->name('reset-password');
    });

    // Public news
    Route::prefix('news')->name('news.')->group(function () {
        Route::get('/', [NewsController::class, 'index'])->name('index');
        Route::get('{news}', [NewsController::class, 'show'])->name('show');
    });

    // Midtrans webhook (no auth — uses signature verification internally)
    Route::post('payments/webhook', [PaymentController::class, 'webhook'])->name('payments.webhook');

    // ── Authenticated Routes ───────────────────────────────────────────────────
    Route::middleware(['auth:sanctum'])->group(function () {

        Route::post('auth/logout', LogoutController::class)->name('auth.logout');

        // Profile
        Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

        // Addresses
        Route::prefix('addresses')->name('addresses.')->group(function () {
            Route::get('/', [AddressController::class, 'index'])->name('index');
            Route::post('/', [AddressController::class, 'store'])->name('store');
            Route::put('{address}', [AddressController::class, 'update'])->name('update');
            Route::delete('{address}', [AddressController::class, 'destroy'])->name('destroy');
        });

        // Meter Readings (OCR Submission)
        Route::prefix('meter-readings')->name('meter-readings.')->group(function () {
            Route::get('/', [MeterReadingController::class, 'index'])->name('index');
            Route::post('/', [MeterReadingController::class, 'store'])->name('store');
            Route::get('{meterReading}', [MeterReadingController::class, 'show'])->name('show');
            Route::post('simulate', [MeterReadingController::class, 'simulate'])->name('simulate');
        });

        // Invoices
        Route::prefix('invoices')->name('invoices.')->group(function () {
            Route::get('/', [InvoiceController::class, 'index'])->name('index');
            Route::get('{invoice}', [InvoiceController::class, 'show'])->name('show');
        });

        // Payments
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/', [PaymentController::class, 'index'])->name('index');
            Route::post('invoices/{invoice}/create', [PaymentController::class, 'create'])->name('create');
        });

        // Notifications
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::patch('{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
            Route::post('read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        });

        // Analytics
        Route::get('analytics/usage', [AnalyticsController::class, 'usage'])->name('analytics.usage');

        // Customer Dashboard
        Route::get('dashboard', DashboardController::class)->name('dashboard');

        // ── Admin Routes ───────────────────────────────────────────────────────
        Route::middleware('can:admin')->prefix('admin')->name('admin.')->group(function () {

            Route::get('dashboard', AdminDashboardController::class)->name('dashboard');

            // User management
            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/', [AdminUserController::class, 'index'])->name('index');
                Route::get('{user}', [AdminUserController::class, 'show'])->name('show');
                Route::put('{user}', [AdminUserController::class, 'update'])->name('update');
            });

            // Meter reading approval
            Route::prefix('meter-readings')->name('meter-readings.')->group(function () {
                Route::get('/', [AdminMeterReadingController::class, 'index'])->name('index');
                Route::patch('{meterReading}/approve', [AdminMeterReadingController::class, 'approve'])->name('approve');
                Route::patch('{meterReading}/reject', [AdminMeterReadingController::class, 'reject'])->name('reject');
            });

            // Tariffs
            Route::prefix('tariffs')->name('tariffs.')->group(function () {
                Route::get('groups', [AdminTariffController::class, 'indexGroups'])->name('groups.index');
                Route::post('groups', [AdminTariffController::class, 'storeGroup'])->name('groups.store');
                Route::put('groups/{tariffGroup}', [AdminTariffController::class, 'updateGroup'])->name('groups.update');
                Route::delete('groups/{tariffGroup}', [AdminTariffController::class, 'destroyGroup'])->name('groups.destroy');
                Route::post('rates', [AdminTariffController::class, 'storeRate'])->name('rates.store');
                Route::put('rates/{tariffRate}', [AdminTariffController::class, 'updateRate'])->name('rates.update');
                Route::delete('rates/{tariffRate}', [AdminTariffController::class, 'destroyRate'])->name('rates.destroy');
            });

            // News management
            Route::prefix('news')->name('news.')->group(function () {
                Route::get('/', [AdminNewsController::class, 'index'])->name('index');
                Route::post('/', [AdminNewsController::class, 'store'])->name('store');
                Route::put('{news}', [AdminNewsController::class, 'update'])->name('update');
                Route::delete('{news}', [AdminNewsController::class, 'destroy'])->name('destroy');
            });
        });
    });
});
