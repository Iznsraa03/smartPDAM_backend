<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\AbnormalUsageDetected;
use App\Events\InvoiceGenerated;
use App\Events\MeterReadingSubmitted;
use App\Events\PaymentSettled;
use App\Listeners\GenerateInvoiceOnApproval;
use App\Listeners\SendBillingReminderNotification;
use App\Listeners\SendPaymentNotification;
use App\Models\Address;
use App\Models\Invoice;
use App\Models\MeterReading;
use App\Policies\AddressPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\MeterReadingPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(RepositoryServiceProvider::class);
    }

    public function boot(): void
    {
        // ─── Policies ─────────────────────────────────────────────────────────
        Gate::policy(Address::class, AddressPolicy::class);
        Gate::policy(Invoice::class, InvoicePolicy::class);
        Gate::policy(MeterReading::class, MeterReadingPolicy::class);

        // ─── Gates ────────────────────────────────────────────────────────────
        Gate::define('admin', fn ($user) => $user->isAdmin());
        Gate::define('super-admin', fn ($user) => $user->isSuperAdmin());

        // ─── Events ───────────────────────────────────────────────────────────
        Event::listen(MeterReadingSubmitted::class, GenerateInvoiceOnApproval::class);
        Event::listen(InvoiceGenerated::class, SendBillingReminderNotification::class);
        Event::listen(PaymentSettled::class, SendPaymentNotification::class);

        // ─── Scheduler ────────────────────────────────────────────────────────
        ResetPassword::createUrlUsing(fn ($user, $token) =>
            config('app.frontend_url') . "/reset-password?token={$token}&email={$user->email}"
        );
    }
}
