<?php

namespace AryehRaber\Impersonator;

use Closure;
use Statamic\Support\Str;
use Illuminate\Http\Response;

class ImpersonatorMiddleware
{
    protected $response;

    public function handle($request, Closure $next)
    {
        $this->response = $next($request);

        if (! $this->shouldInjectTerminateLink()) {
            return $this->response;
        }

        if (! $content = $this->response->getContent()) {
            return $this->response;
        }

        if (($pos = strripos($content, '</body>')) === false) {
            return $this->response;
        }

        $this->injectTerminateLink($content, $pos);

        return $this->response;
    }

    protected function shouldInjectTerminateLink()
    {
        if (! session()->has('impersonator_id')) {
            return false;
        }

        if (! $this->response instanceof Response) {
            return false;
        }

        if (! Str::contains($this->response->headers->get('Content-Type'), 'text/html')) {
            return false;
        }

        return config('impersonator.inject_terminate_link');
    }

    protected function injectTerminateLink($content, $pos)
    {
        $link = view('impersonator::link', [
            'url' => route('statamic.cp.impersonator.terminate')
        ])->render();

        $this->response->setContent(
            substr($content, 0, $pos) . $link . substr($content, $pos)
        );
    }
}
