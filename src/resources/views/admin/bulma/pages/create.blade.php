@extends("SimpleMenu::admin.$css_fw.shared")
@section('title', 'Create new Page')

@php
    $locales = SimpleMenu::AppLocales();
@endphp

@section('sub')
    <h3 class="title">
        <a href="{{ route($crud_prefix.'.pages.index') }}">Go Back</a>
    </h3>
    <hr>

    <page-comp inline-template select-first="{{ LaravelLocalization::getCurrentLocale() }}">
        <div>
            {{ Form::open(['method' => 'POST', 'route' => $crud_prefix.'.pages.store']) }}

                {{-- Control --}}
                <div class="columns">
                    <div class="column is-2">
                        <h3 class="title">Control</h3>
                    </div>
                    <div class="column is-10">
                        {{-- action --}}
                        <div class="field">
                            {{ Form::label('action', 'Action', ['class' => 'label']) }}
                            <div class="control">
                                {{ Form::text('action', null, ['class' => 'input', 'placeholder'=>"SomeController@index"]) }}
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
                                {{ Form::text('template', null, ['class' => 'input','placeholder'=>"hero"]) }}
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
                                {{ Form::text('route_name', null, ['class' => 'input','placeholder'=>"route-name"]) }}
                            </div>
                            @if($errors->has('route_name'))
                                <p class="help is-danger">
                                    {{ $errors->first('route_name') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                <hr>

                {{-- Content --}}
                <div class="columns">
                    <div class="column is-2">
                        <h3 class="title">Content</h3>
                    </div>
                    <div class="column is-10">
                        {{-- title --}}
                        <div class="field">
                            {{ Form::label('title', 'Title', ['class' => 'label']) }}
                            <div class="control input-box">
                                <div class="select toggle-locale">
                                    <select v-model="title">
                                        @foreach ($locales as $code)
                                            <option value="{{ $code }}">{{ $code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @foreach ($locales as $code)
                                    <input type="text" name="title[{{ $code }}]" class="input" v-show="showTitle('{{ $code }}')" value="{{ old('title.'.$code) }}" placeholder="Some Title">
                                @endforeach
                            </div>
                            @if($errors->has('title'))
                                <p class="help is-danger">
                                    {{ $errors->first('title') }}
                                </p>
                            @endif
                        </div>

                        {{-- body --}}
                        <div class="field">
                            {{ Form::label('body', 'Body', ['class' => 'label']) }}
                            <div class="control input-box">
                                <div class="select toggle-locale">
                                    <select v-model="body">
                                        @foreach ($locales as $code)
                                            <option value="{{ $code }}">{{ $code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @foreach ($locales as $code)
                                    <textarea id="body-{{ $code }}" name="body[{{ $code }}]" class="textarea" v-show="showBody('{{ $code }}')">{{ old('body.'.$code) }}</textarea>
                                @endforeach
                            </div>
                            @if($errors->has('body'))
                                <p class="help is-danger">
                                    {{ $errors->first('body') }}
                                </p>
                            @endif
                        </div>

                        {{-- desc --}}
                        <div class="field">
                            {{ Form::label('desc', 'Description', ['class' => 'label']) }}
                            <div class="control input-box">
                                <div class="select toggle-locale">
                                    <select v-model="desc">
                                        @foreach ($locales as $code)
                                            <option value="{{ $code }}">{{ $code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @foreach ($locales as $code)
                                    <textarea id="desc-{{ $code }}" name="desc[{{ $code }}]" class="textarea" v-show="showDesc('{{ $code }}')">{{ old('desc.'.$code) }}</textarea>
                                @endforeach
                            </div>
                            @if($errors->has('desc'))
                                <p class="help is-danger">
                                    {{ $errors->first('desc') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                <hr>

                {{-- Access --}}
                <div class="columns">
                    <div class="column is-2">
                        <h3 class="title">Access</h3>
                    </div>
                    <div class="column is-10">
                        {{-- prefix --}}
                        <div class="field">
                            {{ Form::label('prefix', 'Url Prefix', ['class' => 'label']) }}
                            <div class="control input-box">
                                <div class="select toggle-locale">
                                    <select v-model="prefix">
                                        @foreach ($locales as $code)
                                            <option value="{{ $code }}">{{ $code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @foreach ($locales as $code)
                                    <input type="text" name="prefix[{{ $code }}]" class="input" v-show="showPrefix('{{ $code }}')" value="{{ old('prefix.'.$code) }}" placeholder="abc">
                                @endforeach
                            </div>
                            @if($errors->has('prefix'))
                                <p class="help is-danger">
                                    {{ $errors->first('prefix') }}
                                </p>
                            @endif
                        </div>

                        {{-- url --}}
                        <div class="field">
                            {{ Form::label('url', 'Url', ['class' => 'label']) }}
                            <div class="control input-box">
                                <div class="select toggle-locale">
                                    <select v-model="url">
                                        @foreach ($locales as $code)
                                            <option value="{{ $code }}">{{ $code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @foreach ($locales as $code)
                                    <input type="text" name="url[{{ $code }}]" class="input" v-show="showUrl('{{ $code }}')" value="{{ old('url.'.$code) }}" placeholder="xyz/{someParam}">
                                @endforeach
                            </div>
                            @if($errors->has('url'))
                                <p class="help is-danger">
                                    {{ $errors->first('url') }}
                                </p>
                            @endif
                        </div>

                        {{-- menus --}}
                        <div class="field">
                            {{ Form::label('menus', 'Menus', ['class' => 'label']) }}
                            <div class="control">
                                {{ Form::select('menus[]', $menus, null, ['class' => 'select2', 'multiple' => 'multiple']) }}
                            </div>
                            @if($errors->has('menus'))
                                <p class="help is-danger">
                                    {{ $errors->first('menus') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                <hr>

                {{-- Guards --}}
                <div class="columns">
                    <div class="column is-2">
                        <h3 class="title">Guards</h3>
                    </div>
                    <div class="column is-10">
                        {{-- roles --}}
                        <div class="field">
                            {{ Form::label('roles', 'Roles', ['class' => 'label']) }}
                            <div class="control">
                                {{ Form::select('roles[]', $roles, null, ['class' => 'select2', 'multiple' => 'multiple']) }}
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
                                {{ Form::select('permissions[]', $permissions, null, ['class' => 'select2', 'multiple' => 'multiple']) }}
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
                        {{ Form::submit(trans('SimpleMenu::messages.app_save'), ['class' => 'button is-success is-fullwidth']) }}
                    </div>
                </div>

            {{ Form::close() }}
        </div>
    </page-comp>
@stop
