<?php

namespace AryehRaber\Impersonator;

use Statamic\Facades\CP\Nav;
use Statamic\Facades\Utility;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Statamic\Providers\AddonServiceProvider;
use AryehRaber\Impersonator\Tags\Impersonator;

class ImpersonatorServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        Impersonator::class,
    ];

    protected $middleware = [
        'web' => [
            ImpersonatorMiddleware::class,
        ],
    ];

    public function boot()
    {
        parent::boot();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'impersonator');

        $this->mergeConfigFrom(__DIR__.'/../config/impersonator.php', 'impersonator');

        $this->publishes([
            __DIR__.'/../config/impersonator.php' => config_path('impersonator.php'),
        ], 'config');

        $this->registerWebRoutes(function () {
            Route::get(config('statamic.routes.action').'/impersonator/terminate', [ImpersonatorController::class, 'destroy'])
                ->name('statamic.cp.impersonator.terminate');
        });

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
                $nav->create('Back to my account')
                    ->section('Impersonator')
                    ->route('impersonator.terminate')
                    ->active('utilities/impersonator')
                    ->icon('revealer');
            }
        });
    }
}
