<?php

namespace Statamic\Addons\Impersonator;

use Auth;
use Statamic\API\Nav;
use Statamic\Extend\Listener;

class ImpersonatorListener extends Listener
{
    public $events = [
        'cp.add_to_head' => 'addToHead',
        'cp.nav.created' => 'addNavItem'
    ];

    public function addToHead()
    {
        if (request()->session()->get('impersonator_id')) {
            $html = $this->css->tag('impersonator');
            $html .= $this->js->tag('impersonator');
         
            return $html;
        }
    }

    public function addNavItem($nav)
    {
        if (Auth::user()->isSuper()) {
            $item = Nav::item('Impersonator')->route('impersonator')->icon('fingerprint');

            $nav->addTo('tools', $item);
        }
    }
}
