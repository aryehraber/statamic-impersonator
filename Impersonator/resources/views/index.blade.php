@extends('layout')

@section('content')
<div>

    <div class="flex items-center mb-3">
        <h1 class="w-full md:w-auto md:flex-1 mb-2 md:mb-0 text-center md:text-left ">
            Impersonator
        </h1>
    </div>

    <div class="card flat-top flat-bottom">
        <form class="picker" method="POST" action="{{ $action_path }}">
            {{ csrf_field() }}

            <div class="publish-fields">
                <div class="form-group width-50">
                    <label class="block">Users</label>

                    <select-fieldtype
                        name="id"
                        data=""
                        :options="[
                            { value: '', text: 'Pick a user...' },
                            @foreach($users as $user)
                                {
                                    value: '{{ $user->id() }}',
                                    text: '{{ $user->username() }}'
                                },
                            @endforeach
                        ]">
                    </select-fieldtype>

                    <label class="block">
                        <br><button class="btn btn-primary" type="submit">Go!</button>
                    </label>
                </div>
            </div>

        </form>
    </div>

</div>
@endsection
