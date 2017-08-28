@extends("SimpleMenu::admin.$css_fw.shared")
@section('title'){{ 'Create new Role' }}@endsection

@section('sub')
    <h3 class="title">
        <a href="{{ route($crud_prefix.'.roles.index') }}">Go Back</a>
    </h3>

    {{ Form::open(['method' => 'POST', 'route' => $crud_prefix.'.roles.store']) }}

        {{-- name --}}
        <div class="field">
            {{ Form::label('name', 'Name', ['class' => 'label']) }}
            <div class="control">
                {{ Form::text('name', null, ['class' => 'input']) }}
            </div>
            @if($errors->has('name'))
                <span class="help is-danger">
                    {{ $errors->first('name') }}
                </span>
            @endif
        </div>

        {{-- permissions --}}
        <div class="field">
            {{ Form::label('permissions', 'Permissions', ['class' => 'label']) }}
            <div class="control">
                {{ Form::select('permissions[]', $permissions, null, ['class' => 'select2', 'multiple' => 'multiple']) }}
            </div>
            @if($errors->has('permissions'))
                <span class="help is-danger">
                    {{ $errors->first('permissions') }}
                </span>
            @endif
        </div>

        {{ Form::submit(trans('SimpleMenu::messages.app_save'), ['class' => 'button is-success']) }}
    {{ Form::close() }}
@stop
