@extends('SimpleMenu::pages.'.config('simpleMenu.framework').'.shared')
@section('title'){{ "Edit '$permission->name'" }}@endsection

@section('sub')
    <h3 class="title">
        <a href="{{ route('admin.permissions.index') }}">Go Back</a>
    </h3>
    
    {{ Form::model($permission, ['method' => 'PUT', 'route' => ['admin.permissions.update', $permission->id]]) }}
        
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
        
        {{ Form::submit(trans('SimpleMenu::messages.app_update'), ['class' => 'button is-warning']) }}
    {{ Form::close() }}
@stop