<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Helpers\PermissionHelper;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('create reservation', function ($user) {
            return PermissionHelper::hasPermission($user, 'create reservation');
        });
        Gate::define('view reservation', function ($user) {
            return PermissionHelper::hasPermission($user, 'view reservation');
        });
        Gate::define('update reservation', function ($user) {
            return PermissionHelper::hasPermission($user, 'update reservation');
        });
        Gate::define('delete reservation', function ($user) {
            return PermissionHelper::hasPermission($user, 'delete reservation');
        });
        Gate::define('accept reservation', function ($user) {
            return PermissionHelper::hasPermission($user, 'accept reservation');
        });
        Gate::define('refuse reservation', function ($user) {
            return PermissionHelper::hasPermission($user, 'refuse reservation');
        });
//
        Gate::define('create salle', function ($user) {
            return PermissionHelper::hasPermission($user, 'create salle');
        });
        Gate::define('view salle', function ($user) {
            return PermissionHelper::hasPermission($user, 'view salle');
        });
        Gate::define('update salle', function ($user) {
            return PermissionHelper::hasPermission($user, 'update salle');
        });
        Gate::define('delete salle', function ($user) {
            return PermissionHelper::hasPermission($user, 'delete salle');
        });
//
        Gate::define('create user', function ($user) {
            return PermissionHelper::hasPermission($user, 'create user');
        });
        Gate::define('view user', function ($user) {
            return PermissionHelper::hasPermission($user, 'view user');
        });

        Gate::define('update user', function ($user) {
            return PermissionHelper::hasPermission($user, 'update user');
        });

        Gate::define('delete user', function ($user) {
            return PermissionHelper::hasPermission($user, 'delete user');
        });
//
        Gate::define('create pays', function ($user) {
            return PermissionHelper::hasPermission($user, 'create pays');
        });
        Gate::define('delete pays', function ($user) {
            return PermissionHelper::hasPermission($user, 'delete pays');
        });
//
        Gate::define('create ville', function ($user) { 
            return PermissionHelper::hasPermission($user, 'create ville');
        });
        Gate::define('view ville', function ($user) {
            return PermissionHelper::hasPermission($user, 'view ville');
        });
        Gate::define('update ville', function ($user) {
            return PermissionHelper::hasPermission($user, 'update ville');
        });
        Gate::define('delete ville', function ($user) {
            return PermissionHelper::hasPermission($user, 'delete ville');
        });
//        
        Gate::define('create chreno', function ($user) {
            return PermissionHelper::hasPermission($user, 'create chreno');
        });
        Gate::define('update chreno', function ($user) {
            return PermissionHelper::hasPermission($user, 'update chreno');
        });

    }
}
