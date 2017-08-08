@extends('SimpleMenu::admin.'.config('simpleMenu.framework').'.shared')
@section('title')
    Edit "{{ empty($page->title) ? collect($page->getTranslations('title'))->first() : $page->title }}"
@endsection

@section('sub')
    <h3 class="title">
        <a href="{{ route('admin.pages.index') }}">Go Back</a>
    </h3>

    <page-comp inline-template select-first="{{ LaravelLocalization::getCurrentLocale() }}">
        <div>
            {{ Form::model($page, ['method' => 'PUT', 'route' => ['admin.pages.update', $page->id]]) }}

                {{-- action --}}
                <div class="field">
                    {{ Form::label('action', 'Action', ['class' => 'label']) }}
                    <div class="control">
                        {{ Form::text('action', $page->action, ['class' => 'input', 'placeholder'=>"SomeController@index"]) }}
                    </div>
                    @if($errors->has('action'))
                        <p class="help is-danger">
                            {{ $errors->first('action') }}
                        </p>
                    @endif
                </div>

                {{-- template --}}
                <div class="field">
                    {{ Form::label('template', 'Template', ['class' => 'label']) }}
                    <div class="control">
                        {{ Form::text('template', $page->template, ['class' => 'input','placeholder'=>"hero"]) }}
                    </div>
                    @if($errors->has('template'))
                        <p class="help is-danger">
                            {{ $errors->first('template') }}
                        </p>
                    @endif
                </div>

                {{-- route_name --}}
                <div class="field">
                    {{ Form::label('route_name', 'Route Name', ['class' => 'label']) }}
                    <div class="control">
                        {{ Form::text('route_name', $page->route_name, ['class' => 'input','placeholder'=>"route-name"]) }}
                    </div>
                    @if($errors->has('route_name'))
                        <p class="help is-danger">
                            {{ $errors->first('route_name') }}
                        </p>
                    @endif
                </div>

                {{-- multi --}}
                @include('SimpleMenu::admin.'.config('simpleMenu.framework').'.pages._multi-edit')
                {{-- end multi --}}

                {{-- roles --}}
                <div class="field">
                    {{ Form::label('roles', 'Roles', ['class' => 'label']) }}
                    <div class="control">
                        {{ Form::select('roles[]', $roles, $page->roles->pluck('name', 'name'), ['class' => 'select2', 'multiple' => 'multiple']) }}
                    </div>
                    @if($errors->has('roles'))
                        <p class="help is-danger">
                            {{ $errors->first('roles') }}
                        </p>
                    @endif
                </div>

                {{-- permissions --}}
                <div class="field">
                    {{ Form::label('permissions', 'Permissions', ['class' => 'label']) }}
                    <div class="control">
                        {{ Form::select('permissions[]', $permissions, $page->permissions->pluck('name', 'name'), ['class' => 'select2', 'multiple' => 'multiple']) }}
                    </div>
                    @if($errors->has('permissions'))
                        <p class="help is-danger">
                            {{ $errors->first('permissions') }}
                        </p>
                    @endif
                </div>

                {{-- menus --}}
                <div class="field">
                    {{ Form::label('menus', 'Menus', ['class' => 'label']) }}
                    <div class="control">
                        {{ Form::select('menus[]', $menus, $page->menuNames->pluck('id', 'name'), ['class' => 'select2', 'multiple' => 'multiple']) }}
                    </div>
                    @if($errors->has('menus'))
                        <p class="help is-danger">
                            {{ $errors->first('menus') }}
                        </p>
                    @endif
                </div>

                {{ Form::submit(trans('SimpleMenu::messages.app_update'), ['class' => 'button is-warning']) }}
            {{ Form::close() }}
        </div>
    </page-comp>
@stop