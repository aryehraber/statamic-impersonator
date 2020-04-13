<?php

namespace AryehRaber\Impersonator;

use Statamic\Actions\Action;
use Statamic\Contracts\Auth\User;
use Illuminate\Support\Facades\Auth;

class ImpersonatorAction extends Action
{
    protected static $handle = 'Start Impersonation';

    protected $bulk = false;

    public function run($items, $values)
    {
        if (! ($user = $items->first()) instanceof User) {
            return;
        }

        Impersonator::impersonate($user);
    }

    public function redirect($items, $values)
    {
        if (! ($user = $items->first()) instanceof User) {
            return;
        }

        return $user->can('access cp') ? cp_route('dashboard') : '/';
    }

    public function filter($item)
    {
        if (! $item instanceof User) {
            return false;
        }

        if (Auth::user()->getAuthIdentifier() === $item->getAuthIdentifier()) {
            return false;
        }

        if (session()->has('impersonator_id')) {
            return false;
        }

        return true;
    }

    public function authorize($user, $item)
    {
        return $user->can('impersonator');
    }
}
