<?php

namespace AryehRaber\Impersonator;

use Illuminate\Routing\Router;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\Utility;
use Statamic\Providers\AddonServiceProvider;

class ImpersonatorServiceProvider extends AddonServiceProvider
{
    protected $viewNamespace = 'impersonator';

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

    public function bootAddon()
    {
        Utility::extend(function () {
            Utility::register('impersonator')
                ->title(__('Impersonator'))
                ->icon('revealer')
                ->description(__('Authenticate as another user.'))
                ->routes(function (Router $router) {
                    $router->get('/', [ImpersonatorController::class, 'index'])->name('index');
                    $router->post('/', [ImpersonatorController::class, 'store'])->name('store');
                });
        });

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
