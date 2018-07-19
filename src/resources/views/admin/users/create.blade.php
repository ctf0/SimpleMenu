@extends("SimpleMenu::admin.shared")
@section('title'){{ trans('SimpleMenu::messages.create_new') }} {{ trans('SimpleMenu::messages.users') }}@endsection

@section('sub')
    <h3 class="title">
        <a href="{{ route($crud_prefix.'.users.index') }}">{{ trans('SimpleMenu::messages.go_back') }}</a>
    </h3>

    <sm-page inline-template v-cloak select-first="{{ LaravelLocalization::getCurrentLocale() }}">
        {{ Form::open(['method' => 'POST', 'route' => $crud_prefix.'.users.store', 'files' => true]) }}

            {{-- avatar --}}
            <div class="columns">
                <div class="column is-2">
                    <h3 class="title">{{ trans('SimpleMenu::messages.avatar') }}</h3>
                </div>
                <div class="column is-10">
                    <input type="file" name="avatar">
                </div>
            </div>

            {{-- Account --}}
            <div class="columns">
                {{-- data --}}
                <div class="column is-2">
                    <h3 class="title">{{ trans('SimpleMenu::messages.account') }}</h3>
                </div>
                <div class="column is-10">
                    {{-- name --}}
                    <div class="field">
                        {{ Form::label('name', trans('SimpleMenu::messages.name'), ['class' => 'label']) }}
                        <div class="control">
                            {{ Form::text('name', null, ['class' => 'input']) }}
                        </div>
                        @if($errors->has('name'))
                            <p class="help is-danger">
                                {{ $errors->first('name') }}
                            </p>
                        @endif
                    </div>

                    {{-- email --}}
                    <div class="field">
                        {{ Form::label('email', trans('SimpleMenu::messages.email'), ['class' => 'label']) }}
                        <div class="control">
                            {{ Form::email('email', null, ['class' => 'input']) }}
                        </div>
                        @if($errors->has('email'))
                            <p class="help is-danger">
                                {{ $errors->first('email') }}
                            </p>
                        @endif
                    </div>

                    {{-- password --}}
                    <div class="field">
                        {{ Form::label('password', trans('SimpleMenu::messages.password'), ['class' => 'label']) }}
                        <div class="control">
                            {{ Form::password('password', ['class' => 'input', 'placeholder' => '******']) }}
                        </div>
                        @if($errors->has('password'))
                            <p class="help is-danger">
                                {{ $errors->first('password') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            <hr>

            {{-- Guards --}}
            <div class="columns">
                <div class="column is-2">
                    <h3 class="title">
                        {{ trans('SimpleMenu::messages.guards') }}
                    </h3>
                </div>
                <div class="column is-10">
                    {{-- roles --}}
                    <div class="field">
                        {{ Form::label('roles', trans('SimpleMenu::messages.roles'), ['class' => 'label']) }}
                        <div class="control">
                            {{ Form::select(
                                'roles[]', 
                                $roles, 
                                null, 
                                ['class' => 'select2', 'multiple' => 'multiple']
                            ) }}
                        </div>
                        @if($errors->has('roles'))
                            <p class="help is-danger">
                                {{ $errors->first('roles') }}
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
                                null, 
                                ['class' => 'select2', 'multiple' => 'multiple']
                            ) }}
                        </div>
                        @if($errors->has('permissions'))
                            <p class="help is-danger">
                                {{ $errors->first('permissions') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="columns">
                <div class="column is-2"></div>
                <div class="column is-2">
                    {{ Form::submit(trans('SimpleMenu::messages.save'), ['class' => 'button is-success is-fullwidth']) }}
                </div>
            </div>
        {{ Form::close() }}
    </sm-page>
@endsection
