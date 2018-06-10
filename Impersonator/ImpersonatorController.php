<?php

namespace Statamic\Addons\Impersonator;

use Auth;
use Statamic\API\User;
use Statamic\Extend\Controller;

class ImpersonatorController extends Controller
{
    private $impersonatorId;

    public function init()
    {
        $this->impersonatorId = session()->get('impersonator_id');

        if ($this->isUnauthorised()) {
            throw new \Exception('Unauthorized.');
        }
    }

    public function index()
    {
        $users = User::all()->reject(function ($user) {
            return $user->isSuper();
        });

        $data = [
            'users' => $users,
            'action_path' => $this->actionUrl('go'),
            'is_impersonating' => !! $this->impersonatorId,
        ];

        return $this->view('index', $data);
    }

    public function postGo()
    {
        if (! $user = User::find(request('id'))) {
            return back()->withErrors('Impersonation failed: user not found.');
        }

        if (! $this->impersonatorId) {
            session()->put('impersonator_id', Auth::user()->id());
        }

        Auth::loginUsingId($user->id());

        return redirect()->route('cp');
    }

    public function getTerminate()
    {
        if (! $user = User::find($this->impersonatorId)) {
            return back()->withErrors('Error logging back into other account. Please log back in manually.');
        }

        Auth::loginUsingId($user->id());

        session()->forget('impersonator_id');

        return redirect()->route('cp');
    }

    private function isUnauthorised()
    {
        return ! $this->impersonatorId && (! Auth::user() || ! Auth::user()->isSuper());
    }
}
