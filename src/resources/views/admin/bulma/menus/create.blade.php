@extends("SimpleMenu::admin.$css_fw.shared")
@section('title', 'Create new Menu')

@section('sub')
    <h3 class="title">
        <a href="{{ url()->previous() }}">Go Back</a>
    </h3>

    {{ Form::open(['method' => 'POST', 'route' => $crud_prefix.'.menus.store']) }}

        {{-- name --}}
        <div class="field">
            {{ Form::label('name', 'Name', ['class' => 'label']) }}
        </div>
        <div class="field has-addons">
            <div class="control is-expanded">
                {{ Form::text('name', null, ['class' => 'input', 'placeholder'=>'name']) }}
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
@endsection
