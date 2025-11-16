<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Repositories\CollectionPointRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Services\CollectionPointService;
use App\Http\Services\ProfileService;
use App\Http\Services\GooglePlacesService;
use App\Models\CollectionPoint;
use App\Models\User;

class RepositoryServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(CollectionPointRepository::class, function ($app) {
            return new CollectionPointRepository(new CollectionPoint());
        });

        $this->app->bind(UserRepository::class, function ($app) {
            return new UserRepository(new User());
        });

        $this->app->bind(GooglePlacesService::class, function ($app) {
            return new GooglePlacesService();
        });

        $this->app->bind(CollectionPointService::class, function ($app) {
            return new CollectionPointService(
                $app->make(CollectionPointRepository::class),
                $app->make(GooglePlacesService::class)
            );
        });

        $this->app->bind(ProfileService::class, function ($app) {
            return new ProfileService($app->make(UserRepository::class));
        });
    }

    public function boot(): void
    {
        //
    }
}
