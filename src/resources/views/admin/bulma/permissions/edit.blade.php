@extends("SimpleMenu::admin.shared")
@section('title'){{ trans('SimpleMenu::messages.edit') }} "{{ $permission->name }}"@endsection

@section('sub')
    <div class="level">
        <div class="level-left">
            <h3 class="title">
                <a href="{{ route($crud_prefix.'.permissions.index') }}">{{ trans('SimpleMenu::messages.go_back') }}</a>
            </h3>
        </div>
        <div class="level-right">
            {{-- create new --}}
            <div class="level-item">
                <a href="{{ route($crud_prefix.'.permissions.create') }}"
                    class="button is-success">
                    {{ trans('SimpleMenu::messages.add_new') }}
                </a>
            </div>

            {{-- delete --}}
            <div class="level-item">
                @php
                    $check = in_array($permission->name, auth()->user()->permissions->pluck('name')->toArray())
                        ? 'disabled'
                        : '';
                @endphp

                {{ Form::open(['method' => 'DELETE', 'route' => [$crud_prefix.'.permissions.destroy', $permission->id]]) }}
                    <button type="submit" class="button is-danger" {{ $check }}>
                        {{ trans('SimpleMenu::messages.delete') }}
                    </button>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    {{ Form::model($permission, ['method' => 'PUT', 'route' => [$crud_prefix.'.permissions.update', $permission->id]]) }}

        {{-- name --}}
        <div class="field">
            {{ Form::label('name', trans('SimpleMenu::messages.name'), ['class' => 'label']) }}
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
                {{ Form::submit(trans('SimpleMenu::messages.update'), ['class' => 'button is-warning']) }}
            </div>
        </div>

    {{ Form::close() }}
@endsection
