<?php

namespace App\Providers;

use App\DataMappers\Token\TokenMapper;
use App\DataMappers\Token\TokenMapperInterface;
use App\DataMappers\User\UserMapper;
use App\DataMappers\User\UserMapperInterface;
use App\Utils\AnalyticsStorage\AnalyticsStorage;
use App\Utils\AnalyticsStorage\SlowAnalyticsStorage;
use App\Utils\Database\Database;
use App\Utils\Database\JsonDatabase;
use App\Utils\Jwt\AuthUserResolver;
use App\Utils\Jwt\AuthUserResolverInterface;
use App\Utils\Jwt\UserTokenFactory;
use App\Utils\Jwt\UserTokenFactoryInterface;
use App\Utils\NotRegisteredUsers\NotRegisteredUserIdGenerator;
use App\Utils\NotRegisteredUsers\NotRegisteredUserIdGeneratorInterface;
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
        $this->app->bind(UserTokenFactoryInterface::class, UserTokenFactory::class);
        $this->app->bind(TokenMapperInterface::class, TokenMapper::class);
        $this->app->bind(AuthUserResolverInterface::class, AuthUserResolver::class);
        $this->app->bind(NotRegisteredUserIdGeneratorInterface::class, NotRegisteredUserIdGenerator::class);
    }
}
