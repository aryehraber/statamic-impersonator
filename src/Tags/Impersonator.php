<?php

namespace AryehRaber\Impersonator\Tags;

use Statamic\Tags\Tags;

class Impersonator extends Tags
{
    /**
     * The {{ impersonator:active }} tag
     *
     * @return bool
     */
    public function active()
    {
        return session()->has('impersonator_id');
    }

    /**
     * The {{ impersonator:terminate }} tag
     *
     * @return string
     */
    public function terminate()
    {
        return route('statamic.cp.impersonator.terminate');
    }
}
