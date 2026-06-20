<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Middleware\AttachJwtFromCookie;
use App\Http\Middleware\AuthenticateUser;
use App\Http\Controllers\Admin\Auth\AdminAuthController as AdminViewAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;


Route::get('/', function () {
    $dbConnected = false;
    $dbName = 'Unknown';
    try {
        DB::connection()->getPdo();
        $dbConnected = true;
        $dbName = DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        $dbConnected = false;
    }

    $projectInfo = [
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'db_connected' => $dbConnected,
        'db_name' => $dbName,
        'server_time' => now()->toDateTimeString(),
    ];

    // AI Info
    $aiInfo = [
        'name' => 'zsi.ai',
        'tagline' => 'Advanced Agentic Intelligence for ZilMoney',
        'capabilities' => ['Autonomous Task Execution', 'Smart Code Refactoring', 'API Optimization'],
    ];

    return view('welcome', compact('projectInfo', 'aiInfo'));
});

Route::get('/db-check', function () {
    if (!app()->environment('local')) {
        abort(403, 'Database check is only allowed in local development.');
    }
    try {
        DB::connection()->getPdo(); // DB connection check
        $databaseName = DB::connection()->getDatabaseName();
        return "✅ Database connected successfully! Database name: " . $databaseName;
    } catch (\Exception $e) {
        return "❌ Database connection failed: " . $e->getMessage();
    }
});

Route::get('/run-migrate', function () {
    if (!app()->environment('local')) {
        abort(403, 'Migrations can only be run via command line or in local development.');
    }
    Artisan::call('migrate', ['--force' => true]);
    return "Migrations completed!";
});

// For web routes
Route::get('/clear-cache', function () {
    if (!app()->environment('local')) {
        abort(403, 'Cache clearing via public URL is only allowed in local development.');
    }
    return app(App\Http\Controllers\Common\SystemSettings\SystemSettingController::class)->clearCache();
});

Route::get('/payment/test-intent', function () {
    if (!app()->environment('local')) {
        abort(403, 'Stripe test intent is only allowed in local development.');
    }
    $stripeKey = config('services.stripe.key');
    return view('payment.stripe-intent', compact('stripeKey'));
});

Route::get('/payment/success', function () {
    return "<h1>Payment Successful!</h1><p>Thank you for your purchase. You can now close this window or return to your dashboard.</p><a href='/user/dashboard'>Back to Dashboard</a>";
})->name('payment.success');

Route::get('/payment/cancel', function () {
    return "<h1>Payment Cancelled</h1><p>Your payment process was cancelled. No charges were made.</p><a href='/user/dashboard'>Back to Dashboard</a>";
})->name('payment.cancel');

// Admins Routes


// Admin Dashboard Views
Route::prefix('admin')->group(function () {

    // Login View
    Route::get('/login', [AdminViewAuthController::class, 'showLoginForm'])->name('admin.login.view');

    Route::middleware([AttachJwtFromCookie::class, AuthenticateAdmin::class])->group(function () {

        // Dashboard & Terminal
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/terminal', [AdminDashboardController::class, 'terminal'])->name('admin.terminal');
        Route::post('/terminal/run-command', [AdminDashboardController::class, 'runArtisanCommand'])->name('admin.terminal.run-command');

        // Settings View
        Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('admin.settings');

        // Allowed Origins View
        Route::get('/origins', function () {
            // Pass empty array, view will fetch via API
            return view('admin.origins.index', ['origins' => []]);
        })->name('admin.origins.index');

        // Stripe Info View
        Route::get('/stripe/webhook', [AdminDashboardController::class, 'stripeInfo'])->name('admin.stripe.webhook');

        // Plans & Features Management Views
        Route::get('/plan/features', function () {
            return view('admin.plans.features');
        })->name('admin.plans.features');

        Route::get('/plans', function () {
            return view('admin.plans.index');
        })->name('admin.plans.index');

        // Coupons Management
        Route::get('/coupons', function () {
            return view('admin.coupons.index');
        })->name('admin.coupons.index');

        // Subscriptions & Payments
        Route::get('/subscriptions', function () {
            return view('admin.subscriptions.index');
        })->name('admin.subscriptions.index');

        Route::get('/payments', function () {
            return view('admin.payments.index');
        })->name('admin.payments.index');

        // User Management
        Route::get('/users', function () {
            return view('admin.users.index');
        })->name('admin.users.index');

        // API Documentation
        Route::get('/docs', function () {
            return view('admin.docs.index');
        })->name('admin.docs.index');

        // Support Tickets
        Route::get('/support', function () {
            return view('admin.support.index');
        })->name('admin.support.index');

        // Email Templates Management (View only - index)
        Route::get('/email-templates', [\App\Http\Controllers\Admins\EmailTemplateController::class, 'index'])->name('admin.email-templates.index');

        // Email Sender (View only)
        Route::get('/email-sender', [\App\Http\Controllers\Admins\EmailSenderController::class, 'index'])->name('admin.email-sender.index');

        // Email Logs / History (View only)
        Route::get('/email-history', [\App\Http\Controllers\Admins\EmailLogController::class, 'index'])->name('admin.email-logs.index');

        // Notifications View
        Route::get('/notifications', function () {
            return view('admin.notifications.index');
        })->name('admin.notifications.index');
    });
});

// User Side Routes
Route::prefix('user')->middleware([AttachJwtFromCookie::class, AuthenticateUser::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('user.index');
    })->name('user.dashboard');

    Route::get('/plans', function () {
        return view('user.plans');
    })->name('user.plans');

    Route::get('/billing', function () {
        return view('user.billing');
    })->name('user.billing');

    Route::get('/support', function () {
        return view('user.support');
    })->name('user.support');

    Route::get('/support/{id}', function ($id) {
        return view('user.support-details', compact('id'));
    })->name('user.support.details');

    Route::get('/notifications', function () {
        return view('user.notifications');
    })->name('user.notifications');

    Route::get('/settings', function () {
        return view('user.settings');
    })->name('user.settings');

    Route::get('/docs', function () {
        return view('user.docs');
    })->name('user.docs');
});
