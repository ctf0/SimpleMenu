@extends("SimpleMenu::admin.$css_fw.shared")
@section('title', "Edit '$permission->name'")

@section('sub')
    <h3 class="title">
        <a href="{{ url()->previous() }}">Go Back</a>
        <a href="{{ route($crud_prefix.'.permissions.create') }}" class="button is-success">@lang('SimpleMenu::messages.app_add_new')</a>
    </h3>

    {{ Form::model($permission, ['method' => 'PUT', 'route' => [$crud_prefix.'.permissions.update', $permission->id]]) }}

        {{-- name --}}
        <div class="field">
            {{ Form::label('name', 'Name', ['class' => 'label']) }}
        </div>
        <div class="field has-addons">
            <div class="control is-expanded">
                {{ Form::text('name', $permission->name, ['class' => 'input']) }}
                @if($errors->has('name'))
                    <p class="help is-danger">
                        {{ $errors->first('name') }}
                    </p>
                @endif
            </div>
            <div class="control">
                {{ Form::submit(trans('SimpleMenu::messages.app_update'), ['class' => 'button is-warning']) }}
            </div>
        </div>

    {{ Form::close() }}
@endsection
