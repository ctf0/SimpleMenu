@extends("SimpleMenu::admin.$css_fw.shared")
@section('title', "Edit '$role->name'")

@section('sub')
    <h3 class="title">
        <a href="{{ url()->previous() }}">Go Back</a>
    </h3>

    {{ Form::model($role, ['method' => 'PUT', 'route' => [$crud_prefix.'.roles.update', $role->id]]) }}

        {{-- name --}}
        <div class="field">
            {{ Form::label('name', 'Name', ['class' => 'label']) }}
            <div class="control">
                {{ Form::text('name', $role->name, ['class' => 'input']) }}
            </div>
            @if($errors->has('name'))
                <p class="help is-danger">
                    {{ $errors->first('name') }}
                </p>
            @endif
        </div>

        {{-- permissions --}}
        <div class="field">
            {{ Form::label('permissions', 'Permissions', ['class' => 'label']) }}
            <div class="control">
                {{ Form::select('permissions[]', $permissions, $role->permissions->pluck('name', 'name'), ['class' => 'select2', 'multiple' => 'multiple']) }}
            </div>
            @if($errors->has('permissions'))
                <p class="help is-danger">
                    {{ $errors->first('permissions') }}
                </p>
            @endif
        </div>

        {{ Form::submit(trans('SimpleMenu::messages.app_update'), ['class' => 'button is-warning']) }}
    {{ Form::close() }}
@endsection
