<?php

namespace AryehRaber\Impersonator;

use Statamic\Facades\User;
use Illuminate\Events\NullDispatcher;
use Illuminate\Support\Facades\Auth;

class Impersonator
{
    public static function impersonate($user)
    {
        session()->put('impersonator_id', Auth::user()->getAuthIdentifier());

        static::loginQuietly($user);
    }

    public static function terminate()
    {
        if (! $user = User::find(session('impersonator_id'))) {
            return false;
        }

        static::loginQuietly($user);

        session()->forget('impersonator_id');

        return true;
    }

    public static function redirectTo($user)
    {
        if ($url = config('impersonator.redirect_url')) {
            return $url;
        }

        return $user->can('access cp') ? cp_route('dashboard') : '/';
    }

    public static function loginQuietly($user)
    {
        $dispatcher = Auth::getDispatcher();

        if ($dispatcher) {
            Auth::setDispatcher(new NullDispatcher($dispatcher));
        }

        try {
            Auth::loginUsingId($user->getAuthIdentifier());
        } finally {
            if ($dispatcher) {
                Auth::setDispatcher($dispatcher);
            }
        }
    }
}
