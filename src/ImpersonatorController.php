<?php

namespace AryehRaber\Impersonator;

use Illuminate\Support\Facades\Auth;
use Statamic\Facades\User;
use Statamic\Http\Controllers\Controller;

class ImpersonatorController extends Controller
{
    public function index()
    {
        $users = User::all()->reject(function ($user) {
            return Auth::user()->getAuthIdentifier() === $user->getAuthIdentifier()
                || ! User::current()->isSuper() && $user->isSuper();
        });

        return view('impersonator::index', ['users' => $users]);
    }

    public function store()
    {
        if (session('impersonator_id')) {
            return back()->with('error', __('Impersonation failed: you are already impersonating another user.'));
        }

        if (! $user = User::find(request('user_id'))) {
            return back()->with('error', __('Impersonation failed: user not found.'));
        }

        if (! User::current()->isSuper() && $user->isSuper()) {
            return back()->with('error', __('Impersonation failed: non-Super Admin users cannot impersonate Super Admin users.'));
        }

        Impersonator::impersonate($user);

        return redirect(Impersonator::redirectTo($user))->with('success', __('Impersonation session started!'));
    }

    public function destroy()
    {
        if (! Impersonator::terminate()) {
            return back()->with('error', __('Error logging back into original account. Please log back in manually.'));
        }

        return redirect(cp_route('utilities.impersonator.index'))->with('success', __('Welcome back!'));
    }
}
