<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    
        Auth::provider('custom-users', function ($app, array $config) {
            return new \Illuminate\Auth\EloquentUserProvider($app['hash'], $app['config']['auth.providers.users.model']);
        });
    
        // Configura el password broker para usar tu tabla de usuarios
        Auth::extend('users', function ($app, $name, array $config) {
            return new \Illuminate\Auth\SessionGuard(
                'users',
                new \Illuminate\Auth\EloquentUserProvider($app['hash'], $config['model']),
                $app['session.store']
            );
        });
    }
}
