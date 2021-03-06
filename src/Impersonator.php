<?php

namespace AryehRaber\Impersonator;

use Statamic\Facades\User;
use Illuminate\Support\Facades\Auth;

class Impersonator
{
    public static function impersonate($user)
    {
        session()->put('impersonator_id', Auth::user()->getAuthIdentifier());

        Auth::loginUsingId($user->getAuthIdentifier());
    }

    public static function terminate()
    {
        if (! $user = User::find(session('impersonator_id'))) {
            return false;
        }

        Auth::loginUsingId($user->getAuthIdentifier());

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
}
