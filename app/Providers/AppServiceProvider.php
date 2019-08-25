<?php

namespace App\Providers;

use App\Utils\AnalyticsStorage\AnalyticsStorage;
use App\Utils\AnalyticsStorage\SlowAnalyticsStorage;
use Illuminate\Support\ServiceProvider;
use SocialTech\SlowStorage;
use SocialTech\StorageInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(StorageInterface::class, SlowStorage::class);
        $this->app->bind(AnalyticsStorage::class, SlowAnalyticsStorage::class);
    }
}
