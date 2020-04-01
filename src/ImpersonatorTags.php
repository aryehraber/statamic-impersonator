<?php

namespace AryehRaber\Impersonator;

use Statamic\Tags\Tags;

class ImpersonatorTags extends Tags
{
    protected static $handle = 'impersonator';

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
