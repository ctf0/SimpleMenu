@extends("SimpleMenu::admin.shared")
@section('title'){{ trans('SimpleMenu::messages.edit') }} "{{ $user->name }}"@endsection

@section('sub')
    <div class="level">
        <div class="level-left">
            <h3 class="title">
                <a href="{{ route($crud_prefix.'.users.index') }}">{{ trans('SimpleMenu::messages.go_back') }}</a>
            </h3>
        </div>
        <div class="level-right">
            {{-- create new --}}
            <div class="level-item">
                <a href="{{ route($crud_prefix.'.users.create') }}"
                    class="button is-success">
                    {{ trans('SimpleMenu::messages.add_new') }}
                </a>
            </div>
            {{-- delete --}}
            <div class="level-item">
                @php
                    $check = $user->id == auth()->user()->id ? 'disabled' : '';
                @endphp

                {{ Form::open(['method' => 'DELETE', 'route' => [$crud_prefix.'.users.destroy', $user->id]]) }}
                    <button type="submit" class="button is-danger" {{ $check }}>
                        {{ trans('SimpleMenu::messages.delete') }}
                    </button>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    <sm-page inline-template v-cloak select-first="{{ LaravelLocalization::getCurrentLocale() }}">
        {{ Form::model($user, ['method' => 'PUT', 'route' => [$crud_prefix.'.users.update', $user->id], 'files' => true]) }}

            {{-- avatar --}}
            <div class="columns">
                <div class="column is-2">
                    <h3 class="title">{{ trans('SimpleMenu::messages.avatar') }}</h3>
                </div>
                <div class="column is-10">
                    {{-- preview --}}
                    @if ($user->avatar)
                        <img src="{{ $user->avatar }}">
                    @endif
                    {{-- upload --}}
                    <div class="field">
                        <input type="file" name="avatar">
                    </div>
                </div>
            </div>

            {{-- Account --}}
            <div class="columns">
                {{-- data --}}
                <div class="column is-2">
                    <h3 class="title">{{ trans('SimpleMenu::messages.account') }}</h3>
                </div>
                <div class="column is-10">
                    {{-- name --}}
                    <div class="field">
                        {{ Form::label('name', trans('SimpleMenu::messages.name'), ['class' => 'label']) }}
                        <div class="control">
                            {{ Form::text('name', $user->name, ['class' => 'input']) }}
                        </div>
                        @if($errors->has('name'))
                            <p class="help is-danger">
                                {{ $errors->first('name') }}
                            </p>
                        @endif
                    </div>

                    {{-- email --}}
                    <div class="field">
                        {{ Form::label('email', trans('SimpleMenu::messages.email'), ['class' => 'label']) }}
                        <div class="control">
                            {{ Form::email('email', $user->email, ['class' => 'input']) }}
                        </div>
                        @if($errors->has('email'))
                            <p class="help is-danger">
                                {{ $errors->first('email') }}
                            </p>
                        @endif
                    </div>

                    {{-- password --}}
                    <div class="field">
                        {{ Form::label('password', trans('SimpleMenu::messages.password'), ['class' => 'label']) }}
                        <div class="control">
                            {{ Form::password('password', ['class' => 'input', 'placeholder' => '******']) }}
                        </div>
                        @if($errors->has('password'))
                            <p class="help is-danger">
                                {{ $errors->first('password') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            <hr>

            {{-- Guards --}}
            <div class="columns">
                <div class="column is-2">
                    <h3 class="title">{{ trans('SimpleMenu::messages.guards') }}</h3>
                </div>
                <div class="column is-10">
                    {{-- roles --}}
                    <div class="field">
                        {{ Form::label('roles', trans('SimpleMenu::messages.roles'), ['class' => 'label']) }}
                        <div class="control">
                            {{ Form::select(
                                'roles[]', 
                                $roles, 
                                $user->roles->pluck('name', 'name'), 
                                ['class' => 'select2', 'multiple' => 'multiple']
                            ) }}
                        </div>
                        @if($errors->has('roles'))
                            <p class="help is-danger">
                                {{ $errors->first('roles') }}
                            </p>
                        @endif
                    </div>

                    {{-- permissions --}}
                    <div class="field">
                        {{ Form::label('permissions', trans('SimpleMenu::messages.permissions'), ['class' => 'label']) }}
                        <div class="control">
                            {{ Form::select(
                                'permissions[]', 
                                $permissions, 
                                $user->permissions->pluck('name', 'name'), 
                                ['class' => 'select2', 'multiple' => 'multiple']
                            ) }}
                        </div>
                        @if($errors->has('permissions'))
                            <p class="help is-danger">
                                {{ $errors->first('permissions') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="columns">
                <div class="column is-2"></div>
                <div class="column is-2">
                    {{ Form::submit(trans('SimpleMenu::messages.update'), ['class' => 'button is-warning is-fullwidth']) }}
                </div>
            </div>

        {{ Form::close() }}
    </sm-page>
@endsection
