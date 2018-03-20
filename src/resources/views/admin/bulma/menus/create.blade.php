@extends("SimpleMenu::admin.shared")
@section('title'){{ trans('SimpleMenu::messages.create_new') }} {{ trans('SimpleMenu::messages.menus') }}@endsection

@section('sub')
    <h3 class="title">
        <a href="{{ route($crud_prefix.'.menus.index') }}">{{ trans('SimpleMenu::messages.go_back') }}</a>
    </h3>

    {{ Form::open(['method' => 'POST', 'route' => $crud_prefix.'.menus.store']) }}

        {{-- name --}}
        <div class="field">
            {{ Form::label('name', trans('SimpleMenu::messages.name'), ['class' => 'label']) }}
        </div>
        <div class="field has-addons">
            <div class="control is-expanded">
                {{ Form::text('name', null, ['class' => 'input', 'placeholder' => 'name']) }}
                @if($errors->has('name'))
                    <p class="help is-danger">
                        {{ $errors->first('name') }}
                    </p>
                @endif
            </div>
            <div class="control">
                {{ Form::submit(trans('SimpleMenu::messages.save'), ['class' => 'button is-success']) }}
            </div>
        </div>

    {{ Form::close() }}
@endsection
