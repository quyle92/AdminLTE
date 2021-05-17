<?php

namespace App\Providers;

use App\User;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
         $this->app->singleton('MySessionGuardFacade', function($app){
            return new \App\MyFacades\MySessionGuard();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        SessionGuard::macro('myOnceBasic', function ($field = 'name', $extraConditions = []) {

            $credentials = $this->basicCredentials($this->getRequest(), $field);

            if (!$this->once(array_merge($credentials, $extraConditions))) {
                return$this->failedBasicResponse();
            }
        });

        Collection::macro('makeKebab', function () {
            return $this->map(function ($value) {
                return Str::kebab($value);
            });
        });

        Str::macro('isLength', function ($str, $length) {

            return static::length($str) == $length;
        });
    }
}
