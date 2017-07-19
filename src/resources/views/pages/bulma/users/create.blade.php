@extends('SimpleMenu::pages.'.config('simpleMenu.framework').'.shared')
@section('title'){{ 'Create new User' }}@endsection

@section('sub')
    <h3 class="title">
        <a href="{{ route('admin.users.index') }}">Go Back</a>
    </h3>
    
    {{ Form::open(['method' => 'POST', 'route' => 'admin.users.store']) }}
        
        {{-- name --}}
        <div class="field">
            {{ Form::label('name', 'Name', ['class' => 'label']) }}
            <div class="control">
                {{ Form::text('name', old('name'), ['class' => 'input']) }}
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
                {{ Form::email('email', old('email'), ['class' => 'input']) }}
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
                {{ Form::password('password', ['class' => 'input','placeholder'=>'******']) }}
            </div>
            @if($errors->has('password'))
                <p class="help is-danger">
                    {{ $errors->first('password') }}
                </p>
            @endif
        </div>
        
        {{-- roles --}}
        <div class="field">
            {{ Form::label('roles', 'Roles', ['class' => 'label']) }}
            <div class="control">
                {{ Form::select('roles[]', $roles, old('roles'), ['class' => 'select2', 'multiple' => 'multiple']) }}
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
                {{ Form::select('permissions[]', $permissions, old('permissions'), ['class' => 'select2', 'multiple' => 'multiple']) }}
            </div>
            @if($errors->has('permissions'))
                <p class="help is-danger">
                    {{ $errors->first('permissions') }}
                </p>
            @endif
        </div>
        
        {{ Form::submit(trans('SimpleMenu::messages.app_save'), ['class' => 'button is-success']) }}
    {{ Form::close() }}
@stop