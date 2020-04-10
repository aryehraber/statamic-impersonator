<?php

namespace AryehRaber\Impersonator;

use Statamic\Facades\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Statamic\Http\Controllers\Controller;

class ImpersonatorController extends Controller
{
    public function index()
    {
        $users = User::all()->reject(function ($user) {
            return Auth::user()->getAuthIdentifier() === $user->getAuthIdentifier();
        });

        return view('impersonator::index', ['users' => $users]);
    }

    public function store()
    {
        if (session('impersonator_id')) {
            return back()->with('error', 'Impersonation failed: you are already impersonating another user.');
        }

        if (! $user = User::find(request('user_id'))) {
            return back()->with('error', 'Impersonation failed: user not found.');
        }

        Impersonator::impersonate($user);

        $route = $user->can('access cp') ? cp_route('dashboard') : '/';

        return redirect($route)->with('success', 'Impersonation session started!');
    }

    public function destroy()
    {
        if (! Impersonator::terminate()) {
            return back()->with('error', 'Error logging back into original account. Please log back in manually.');
        }

        return redirect(cp_route('utilities.impersonator.index'))->with('success', 'Welcome back!');
    }
}
