<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Contracts\InvoiceRepositoryInterface;
use App\Repositories\Contracts\MeterReadingRepositoryInterface;
use App\Repositories\Contracts\NewsRepositoryInterface;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\Contracts\TariffRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\InvoiceRepository;
use App\Repositories\Eloquent\MeterReadingRepository;
use App\Repositories\Eloquent\NewsRepository;
use App\Repositories\Eloquent\NotificationRepository;
use App\Repositories\Eloquent\PaymentRepository;
use App\Repositories\Eloquent\TariffRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(MeterReadingRepositoryInterface::class, MeterReadingRepository::class);
        $this->app->bind(InvoiceRepositoryInterface::class, InvoiceRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(NewsRepositoryInterface::class, NewsRepository::class);
        $this->app->bind(TariffRepositoryInterface::class, TariffRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
    }
}
