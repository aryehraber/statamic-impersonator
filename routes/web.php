<?php

use Illuminate\Support\Facades\Route;
use AryehRaber\Impersonator\ImpersonatorController;

Route::get(config('statamic.routes.action').'/impersonator/terminate', [ImpersonatorController::class, 'destroy'])
    ->name('statamic.cp.impersonator.terminate');
