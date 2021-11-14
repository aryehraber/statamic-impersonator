<?php

namespace AryehRaber\Impersonator;

use Statamic\Actions\Action;
use Statamic\Contracts\Auth\User;
use Illuminate\Support\Facades\Auth;

class ImpersonatorAction extends Action
{
    protected static $handle = 'Start Impersonation';

    public function visibleTo($item)
    {
        if (! $item instanceof User) {
            return false;
        }
        
        // Avoid super user impersonation if not super user currently
        if (!Auth::user()->isSuper() && $item->isSuper()) {
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

    public function visibleToBulk($items)
    {
        return false;
    }

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

        return Impersonator::redirectTo($user);
    }

    public function authorize($user, $item)
    {
        return $user->can('access impersonator utility');
    }
}
