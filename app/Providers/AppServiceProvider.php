<?php

namespace App\Providers;

use App\DataMappers\User\UserMapper;
use App\DataMappers\User\UserMapperInterface;
use App\Utils\AnalyticsStorage\AnalyticsStorage;
use App\Utils\AnalyticsStorage\SlowAnalyticsStorage;
use App\Utils\Database\Database;
use App\Utils\Database\JsonDatabase;
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
        $this->app->bind(UserMapperInterface::class, UserMapper::class);
        $this->app->bind(Database::class, JsonDatabase::class);
    }
}
