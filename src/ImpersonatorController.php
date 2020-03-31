<?php

namespace AryehRaber\Impersonator;

use Statamic\Facades\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Statamic\Http\Controllers\Controller;

class ImpersonatorController extends Controller
{
    protected $impersonatorId;

    public function index()
    {
        $users = User::all()->reject(function ($user) {
            return Auth::user()->id() === $user->id();
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

        session()->put('impersonator_id', Auth::user()->id());

        Auth::loginUsingId($user->id());

        $route = $user->can('access cp') ? cp_route('dashboard') : '/';

        return redirect($route)->with('success', 'Impersonation session started!');
    }

    public function destroy()
    {
        if (! $user = User::find(session('impersonator_id'))) {
            return back()->with('error', 'Error logging back into original account. Please log back in manually.');
        }

        Auth::loginUsingId($user->id());

        session()->forget('impersonator_id');

        return redirect(cp_route('utilities.impersonator.index'))->with('success', 'Welcome back!');
    }
}
