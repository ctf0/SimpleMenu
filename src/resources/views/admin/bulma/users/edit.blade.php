@extends('SimpleMenu::admin.'.config('simpleMenu.framework').'.shared')
@section('title'){{ "Edit '$user->name'" }}@endsection

@section('sub')
    <h3 class="title">
        <a href="{{ route('admin.users.index') }}">Go Back</a>
    </h3>

    {{ Form::model($user, ['method' => 'PUT', 'route' => ['admin.users.update', $user->id]]) }}

        {{-- name --}}
        <div class="field">
            {{ Form::label('name', 'Name', ['class' => 'label']) }}
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
            {{ Form::label('email', 'Email', ['class' => 'label']) }}
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
            {{ Form::label('password', 'Password', ['class' => 'label']) }}
            <div class="control">
                {{ Form::password('password', ['class' => 'input', 'placeholder'=>'******']) }}
            </div>
            @if($errors->has('password'))
                <p class="help is-danger">
                    {{ $errors->first('password') }}
                </p>
            @endif
        </div>

        <div class="field">
            {{ Form::label('roles', 'Roles', ['class' => 'label']) }}
            <div class="control">
                {{ Form::select('roles[]', $roles, $user->roles->pluck('name', 'name'), ['class' => 'select2', 'multiple' => 'multiple']) }}
            </div>
            @if($errors->has('roles'))
                <p class="help is-danger">
                    {{ $errors->first('roles') }}
                </p>
            @endif
        </div>

        {{-- permissions --}}
        <div class="field">
            {{ Form::label('permissions', 'Permissions', ['class' => 'label']) }}
            <div class="control">
                {{ Form::select('permissions[]', $permissions, $user->permissions->pluck('name', 'name'), ['class' => 'select2', 'multiple' => 'multiple']) }}
            </div>
            @if($errors->has('permissions'))
                <p class="help is-danger">
                    {{ $errors->first('permissions') }}
                </p>
            @endif
        </div>

        {{ Form::submit(trans('SimpleMenu::messages.app_update'), ['class' => 'button is-warning']) }}
    {{ Form::close() }}
@stop