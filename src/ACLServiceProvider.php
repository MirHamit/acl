<?php

namespace MirHamit\ACL;

use Exception;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use MirHamit\ACL\Middleware\PermissionMiddleware;
use MirHamit\ACL\Middleware\RoleMiddleware;
use MirHamit\ACL\Models\Permission;


class ACLServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBladeExtensions();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'acl');

        $this->registerGate();

        $this->configureMiddleware();
    }

    protected function registerPublishables(): void
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'migrations');
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/acl'),
        ], 'lang');
    }

    protected function registerGate()
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishables();
        } else {
            try {
                Permission::get()->map(function ($permission) {
                    Gate::define($permission->slug, function ($user) use ($permission) {
                        return $user->hasPermissionTo($permission);
                    });
                });
            } catch (Exception $e) {
                report($e);
                return false;
            }
        }
    }


    protected function registerBladeExtensions()
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {

            $bladeCompiler->directive('role', function ($arguments) {
                return "<?php if(auth()->check() && auth()->user()->hasRole($arguments)) : ?>";
            });

            $bladeCompiler->directive('endrole', function () {
                return '<?php endif; ?>';
            });
        });
    }

    /**
     * Configure the Sanctum middleware and priority.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function configureMiddleware()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('permission', PermissionMiddleware::class);
        $router->aliasMiddleware('role', RoleMiddleware::class);
    }
}
