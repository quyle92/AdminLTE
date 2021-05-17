<?php

namespace App\Providers;

use App\Policies\PostPolicy;
use App\Policies\UserPolicy;
use App\Post;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Post' => 'App\Policies\PostPolicy',
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Gate::define('isAdmin', function (User $user) {
            return $user->type === 'admin';
        });

        Gate::define('isAuthor', function (User $user) {
            return $user->type === 'author';
        });

        Gate::define('isUser', function (User $user) {
            return $user->type === 'user';
        });

        // Gate::before(function (User $user){
        //     if( $user->type = 'admin' ){
        //         return true;
        //     }
        // });
        
        // Gate::define('update-post', function ($user, $post) {
        //     return $user->id == $post->user_id;
        // });
        


    }


}
