<?php


namespace BdMehedi\LaravelPermission;

use BdMehedi\LaravelPermission\Middleware\CheckPermissionMiddleware;
use BdMehedi\LaravelPermission\Models\Permission;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;


class LaravelPermissionServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/laravel_permission.php', 'laravel_permission'
        );
    }

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/laravel_permission.php' => config_path('laravel_permission.php'),
        ], 'laravel_permission_config');
        $this->publishes([
            __DIR__.'/database/migrations' => database_path('migrations')
        ], 'laravel_permission_migration');

        $this->registerAuthGate();

        $this->registerMiddleware();
    }

    /**
     * Register all permissions as authorization gate
     * @return void
     */
    protected function registerAuthGate()
    {
        if (!App::runningInConsole()) {
            $permissions = Cache::remember('permissions', now()->addDay(), function () {
                return Permission::with('roles')->get();
            });

            $permissions->map(function ($permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission);
                });
            });
        }
    }

    public function registerMiddleware()
    {
        $this->app['router']->aliasMiddleware('allow', CheckPermissionMiddleware::class);
    }
}