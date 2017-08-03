@extends('SimpleMenu::admin.'.config('simpleMenu.framework').'.shared')
@section('title'){{ 'Create new Permission' }}@endsection

@section('sub')
    <h3 class="title">
        <a href="{{ route('admin.permissions.index') }}">Go Back</a>
    </h3>

    {{ Form::open(['method' => 'POST', 'route' => 'admin.permissions.store']) }}

        {{-- name --}}
        <div class="field">
            {{ Form::label('name', 'Name', ['class' => 'label']) }}
        </div>
        <div class="field has-addons">
            <div class="control is-expanded">
                {{ Form::text('name', null, ['class' => 'input']) }}
                @if($errors->has('name'))
                    <p class="help is-danger">
                        {{ $errors->first('name') }}
                    </p>
                @endif
            </div>
            <div class="control">
                {{ Form::submit(trans('SimpleMenu::messages.app_save'), ['class' => 'button is-success']) }}
            </div>
        </div>

    {{ Form::close() }}
@stop