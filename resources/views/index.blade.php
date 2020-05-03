@extends('statamic::layout')
@section('title', Statamic::crumb(__('Impersonator'), __('Utilities')))

@section('content')

    <header class="mb-3">
        @include('statamic::partials.breadcrumb', [
            'url' => cp_route('utilities.index'),
            'title' => __('Utilities')
        ])
        <h1>{{ __('Impersonator') }}</h1>
    </header>

    @if(session()->has('impersonator_id'))
        <h2 class="mt-5 mb-1 font-bold text-lg">
            Impersonation Session Active
        </h2>

        <p class="text-sm text-grey mb-2">
            You are currently impersonating another user, please
            <a href="{{ cp_route('impersonator.terminate') }}">return to your own account</a>
            to switch to another user.
        </p>
    @else
        <p class="mb-2 text-sm text-grey leading-normal">
            Select a user below and click "Go" to start your Impersonation session.<br>
            To terminate your session, click the <strong>"Back to my account"</strong> link (<a href="https://github.com/aryehraber/statamic-impersonator/blob/statamic-3/README.md#usage">see Docs</a>).
        </p>

        <div class="card">
            <form class="flex" method="POST" action="{{ cp_route('utilities.impersonator.store') }}">
                @csrf

                <div class="select-input-container relative w-full">
                    <select class="pr-4" name="user_id">
                        <option value="" selected disabled>-</option>

                        @foreach($users as $user)
                            <option value="{{ $user->id() }}">{{ $user->email() }}</option>
                        @endforeach
                    </select>

                    <svg-icon name="chevron-down-xs" class="absolute inset-y-0 right-0 w-2 h-full mr-1.5 pointer-events-none"></svg-icon>
                </div>

                <button class="btn-primary ml-1">{{ __('Go') }}</button>
            </form>
        </div>
    @endif

@stop
