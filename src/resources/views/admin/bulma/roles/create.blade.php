@extends("SimpleMenu::admin.shared")
@section('title'){{ trans('SimpleMenu::messages.create_new') }} {{ trans('SimpleMenu::messages.roles') }}@endsection

@section('sub')
    <h3 class="title">
        <a href="{{ route($crud_prefix.'.roles.index') }}">{{ trans('SimpleMenu::messages.go_back') }}</a>
    </h3>

    <sm-page inline-template select-first="{{ LaravelLocalization::getCurrentLocale() }}">
        {{ Form::open(['method' => 'POST', 'route' => $crud_prefix.'.roles.store']) }}

            {{-- name --}}
            <div class="field">
                {{ Form::label('name', trans('SimpleMenu::messages.name'), ['class' => 'label']) }}
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
                {{ Form::label('permissions', trans('SimpleMenu::messages.permissions'), ['class' => 'label']) }}
                <div class="control">
                    {{ Form::select('permissions[]', $permissions, null, ['class' => 'select2', 'multiple' => 'multiple']) }}
                </div>
                @if($errors->has('permissions'))
                    <span class="help is-danger">
                        {{ $errors->first('permissions') }}
                    </span>
                @endif
            </div>

            {{ Form::submit(trans('SimpleMenu::messages.save'), ['class' => 'button is-success']) }}
        {{ Form::close() }}
    </sm-page>
@endsection
