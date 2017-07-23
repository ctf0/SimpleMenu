@extends('SimpleMenu::pages.'.config('simpleMenu.framework').'.shared')
@section('title'){{ 'Create new Permission' }}@endsection

@section('sub')
    <h3 class="title">
        <a href="{{ route('admin.permissions.index') }}">Go Back</a>
    </h3>
    
    {{ Form::open(['method' => 'POST', 'route' => 'admin.permissions.store']) }}
        
        {{-- name --}}
        <div class="field">
            {{ Form::label('name', 'Name', ['class' => 'label']) }}
            <div class="control">
                {{ Form::text('name', null, ['class' => 'input']) }}
            </div>
            @if($errors->has('name'))
                <p class="help is-danger">
                    {{ $errors->first('name') }}
                </p>
            @endif
        </div>
        
        {{ Form::submit(trans('SimpleMenu::messages.app_save'), ['class' => 'button is-success']) }}
    {{ Form::close() }}
@stop