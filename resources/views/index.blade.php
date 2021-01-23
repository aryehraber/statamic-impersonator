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
            {{ __('Impersonation Session Active') }}
        </h2>

        <p class="text-sm text-grey mb-2">
            {{ __('You are currently impersonating another user, please') }}
            <a href="{{ cp_route('impersonator.terminate') }}">{{ __('return to your own account') }}</a>
            {{ __('to switch to another user.') }}
        </p>
    @else
        <p class="mb-2 text-sm text-grey leading-normal">
            {{ __('Select a user below and click ":login" to start your Impersonation session.', ['login' => __('Log in')]) }}<br>
            {{ __('To terminate your session, click the ":link" link', ['link' => __('Back to my account')]) }} (<a href="https://github.com/aryehraber/statamic-impersonator/#usage">{{ __('see Docs') }}</a>).
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

                <button class="btn-primary ml-1">{{ __('Log in') }}</button>
            </form>
        </div>
    @endif

@stop
