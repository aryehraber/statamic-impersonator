<?php

return [


    /*
    |--------------------------------------------------------------------------
    | Redirect URL
    |--------------------------------------------------------------------------
    |
    | The URL where the Impersonator will be redirected after starting an
    | impersonation session. By default, the redirect URL will be the
    | CP Dashboard or the Homepage if the user doesn't have CP access.
    |
    */
    'redirect_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Inject Terminate Link
    |--------------------------------------------------------------------------
    |
    | Whether to inject the link to terminate an impersonation session on all
    | frontend pages, to make it easy to return back to the Impersonator's
    | own account.
    |
    */
    'inject_terminate_link' => true,

];
