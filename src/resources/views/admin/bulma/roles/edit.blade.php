@extends("SimpleMenu::admin.$css_fw.shared")
@section('title'){{ trans('SimpleMenu::messages.edit') }} "{{ $role->name }}"@endsection

@section('sub')
    <div class="level">
        <div class="level-left">
            <h3 class="title">
                <a href="{{ route($crud_prefix.'.roles.index') }}">{{ trans('SimpleMenu::messages.go_back') }}</a>
            </h3>
        </div>
        <div class="level-right">
            <a href="{{ route($crud_prefix.'.roles.create') }}"
                class="button is-success">
                {{ trans('SimpleMenu::messages.add_new') }}
            </a>
        </div>
    </div>

    {{ Form::model($role, ['method' => 'PUT', 'route' => [$crud_prefix.'.roles.update', $role->id]]) }}

        {{-- name --}}
        <div class="field">
            {{ Form::label('name', trans('SimpleMenu::messages.name'), ['class' => 'label']) }}
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
            {{ Form::label('permissions', trans('SimpleMenu::messages.permissions'), ['class' => 'label']) }}
            <div class="control">
                {{ Form::select(
                    'permissions[]',
                    $permissions,
                    $role->permissions->pluck('name', 'name'),
                    ['class' => 'select2', 'multiple' => 'multiple']
                ) }}
            </div>
            @if($errors->has('permissions'))
                <p class="help is-danger">
                    {{ $errors->first('permissions') }}
                </p>
            @endif
        </div>

        {{ Form::submit(trans('SimpleMenu::messages.update'), ['class' => 'button is-warning']) }}
    {{ Form::close() }}
@endsection
