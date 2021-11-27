<?php

namespace AryehRaber\Impersonator;

use Statamic\Facades\CP\Nav;
use Statamic\Facades\Utility;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Statamic\Providers\AddonServiceProvider;

class ImpersonatorServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        ImpersonatorTags::class,
    ];

    protected $middlewareGroups = [
        'web' => [
            ImpersonatorMiddleware::class,
        ],
    ];

    protected $routes = [
        'web' => __DIR__.'/../routes/web.php',
    ];

    public function register()
    {
        $this->loadJsonTranslationsFrom(__DIR__ . '/../resources/lang');
    }

    public function boot()
    {
        parent::boot();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'impersonator');

        $this->mergeConfigFrom(__DIR__.'/../config/impersonator.php', 'impersonator');

        $this->publishes([
            __DIR__.'/../config/impersonator.php' => config_path('impersonator.php'),
        ], 'config');

        Utility::make('impersonator')
            ->title(__('Impersonator'))
            ->icon('revealer')
            ->description(__('Authenticate as another user.'))
            ->routes(function (Router $router) {
                $router->get('/', [ImpersonatorController::class, 'index'])->name('index');
                $router->post('/', [ImpersonatorController::class, 'store'])->name('store');
            })
            ->register();

        Nav::extend(function ($nav) {
            if (session()->has('impersonator_id')) {
                $nav->create(__('Back to my account'))
                    ->section(__('Impersonator'))
                    ->route('impersonator.terminate')
                    ->active('utilities/impersonator')
                    ->icon('revealer');
            }
        });

        ImpersonatorAction::register();
    }
}
