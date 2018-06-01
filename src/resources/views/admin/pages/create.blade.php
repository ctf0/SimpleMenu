@extends("SimpleMenu::admin.shared")
@section('title'){{ trans('SimpleMenu::messages.create_new') }} {{ trans('SimpleMenu::messages.pages') }}@endsection

@section('sub')
    <h3 class="title">
        <a href="{{ route($crud_prefix.'.pages.index') }}">{{ trans('SimpleMenu::messages.go_back') }}</a>
    </h3>

    <sm-page inline-template select-first="{{ LaravelLocalization::getCurrentLocale() }}">
        <div>
            {{ Form::open(['method' => 'POST', 'route' => $crud_prefix.'.pages.store', 'files' => true]) }}

                {{-- Meta --}}
                <div class="columns">
                    <div class="column is-2">
                        <h3 class="title">{{ trans('SimpleMenu::messages.meta') }}</h3>
                    </div>
                    <div class="column is-10">
                        {{-- key --}}
                        <div class="field">
                            <div class="control input-box">
                                <div class="select toggle-locale">
                                    <select v-model="meta">
                                        @foreach ($locales as $code)
                                            <option value="{{ $code }}">{{ $code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @foreach ($locales as $code)
                                    <input type="text"
                                        name="meta[{{ $code }}]"
                                        class="input toggle-pad"
                                        v-show="showMeta('{{ $code }}')"
                                        value="{{ old('meta.'.$code) }}"
                                        placeholder="keyword1, etc..">
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                {{-- Control --}}
                <div class="columns">
                    <div class="column is-2">
                        <h3 class="title link" @click="toggleControl = !toggleControl">
                            {{ trans('SimpleMenu::messages.control') }}
                        </h3>
                    </div>
                    <div class="column is-10" v-show="toggleControl">
                        {{-- action --}}
                        <div class="field">
                            {{ Form::label('action', trans('SimpleMenu::messages.action'), ['class' => 'label']) }}
                            <div class="control">
                                {{ Form::text(
                                    'action', 
                                    null, 
                                    ['class' => 'input', 
                                    'placeholder' => "Any\Name\Space\SomeController@methodName", 
                                    'ref' => 'action'])
                                }}
                            </div>
                            <div class="tag link is-primary m-t-5"
                                data-value="App\Http\Controllers\"
                                @click="updateValue($event, 'action')">
                                App\Http\Controllers\
                            </div>
                            @if($errors->has('action'))
                                <p class="help is-danger">
                                    {{ $errors->first('action') }}
                                </p>
                            @endif
                        </div>

                        {{-- template --}}
                        <div class="field">
                            {{ Form::label('template', trans('SimpleMenu::messages.template'), ['class' => 'label']) }}
                            <div class="control">
                                {{ Form::text(
                                    'template', 
                                    null, 
                                    ['class' => 'input', 
                                    'placeholder' => "ex.'folder.abc' or 'Vendor::xyz'", 
                                    'ref' => 'template'])
                                }}
                            </div>
                            @if (count($templates))
                                <div class="tags m-t-5 m-b-0">
                                    @foreach ($templates as $one)
                                        <div class="tag link is-primary"
                                            data-value="{{ $one }}"
                                            @click="updateValue($event, 'template')">
                                            {{ $one }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @if($errors->has('template'))
                                <p class="help is-danger">
                                    {{ $errors->first('template') }}
                                </p>
                            @endif
                        </div>

                        {{-- route_name --}}
                        <div class="field">
                            {{ Form::label('route_name', trans('SimpleMenu::messages.route_name'), ['class' => 'label']) }}
                            <div class="control">
                                {{ Form::text('route_name', null, ['class' => 'input', 'placeholder' => "route-name"]) }}
                            </div>
                            @if($errors->has('route_name'))
                                <p class="help is-danger">
                                    {{ $errors->first('route_name') }}
                                </p>
                            @endif
                        </div>

                        {{-- middleware --}}
                        <div class="field">
                            {{ Form::label('middlewares', trans('SimpleMenu::messages.middlewares'), ['class' => 'label']) }}
                            <div class="control">
                                {{ Form::text(
                                    'middlewares', 
                                    null, 
                                    ['class' => 'input', 'placeholder' => "some, other, middleware"])
                                }}
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                {{-- cover --}}
                <div class="columns">
                    <div class="column is-2">
                        <h3 class="title link" @click="toggleCover = !toggleCover">
                            {{ trans('SimpleMenu::messages.cover') }}
                        </h3>
                    </div>
                    <div class="column is-10" v-show="toggleCover">
                        {{-- upload --}}
                        <div class="field">
                            <input type="file" name="cover">
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="columns">
                    {{-- data --}}
                    <div class="column is-2">
                        <h3 class="title link" @click="toggleContent = !toggleContent">
                            {{ trans('SimpleMenu::messages.content') }}
                        </h3>
                    </div>
                    <div class="column is-10" v-show="toggleContent">
                        {{-- icon --}}
                        <div class="field">
                            {{ Form::label('icon', trans('SimpleMenu::messages.icon'), ['class' => 'label']) }}
                            <div class="control">
                                {{ Form::text(
                                    'icon', 
                                    null, 
                                    ['class' => 'input', 'placeholder' => "icon-name or html"])
                                }}
                            </div>
                        </div>

                        {{-- title --}}
                        <div class="field">
                            {{ Form::label('title', trans('SimpleMenu::messages.title'), ['class' => 'label']) }}
                            <div class="control input-box">
                                <div class="select toggle-locale">
                                    <select v-model="title">
                                        @foreach ($locales as $code)
                                            <option value="{{ $code }}">{{ $code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @foreach ($locales as $code)
                                    <input type="text"
                                        name="title[{{ $code }}]"
                                        class="input toggle-pad"
                                        v-show="showTitle('{{ $code }}')"
                                        value="{{ old('title.'.$code) }}"
                                        placeholder="Some Title">
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
                            {{ Form::label('body', trans('SimpleMenu::messages.body'), ['class' => 'label']) }}
                            <div class="control input-box">
                                <div class="select toggle-locale">
                                    <select v-model="body">
                                        @foreach ($locales as $code)
                                            <option value="{{ $code }}">{{ $code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @foreach ($locales as $code)
                                    <textarea id="body-{{ $code }}"
                                        name="body[{{ $code }}]"
                                        class="textarea"
                                        v-show="showBody('{{ $code }}')">
                                        {{ old('body.'.$code) }}
                                    </textarea>
                                @endforeach
                            </div>
                        </div>

                        {{-- desc --}}
                        <div class="field">
                            {{ Form::label('desc', trans('SimpleMenu::messages.desc'), ['class' => 'label']) }}
                            <div class="control input-box">
                                <div class="select toggle-locale">
                                    <select v-model="desc">
                                        @foreach ($locales as $code)
                                            <option value="{{ $code }}">{{ $code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @foreach ($locales as $code)
                                    <textarea id="desc-{{ $code }}"
                                        name="desc[{{ $code }}]"
                                        class="textarea"
                                        v-show="showDesc('{{ $code }}')">
                                        {{ old('desc.'.$code) }}
                                    </textarea>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                {{-- Access --}}
                <div class="columns">
                    <div class="column is-2">
                        <h3 class="title link" @click="toggleAccess = !toggleAccess">
                            {{ trans('SimpleMenu::messages.access') }}
                        </h3>
                    </div>
                    <div class="column is-10" v-show="toggleAccess">
                        {{-- prefix --}}
                        <div class="field">
                            {{ Form::label('prefix', trans('SimpleMenu::messages.url_prefix'), ['class' => 'label']) }}
                            <div class="control input-box">
                                <div class="select toggle-locale">
                                    <select v-model="prefix">
                                        @foreach ($locales as $code)
                                            <option value="{{ $code }}">{{ $code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @foreach ($locales as $code)
                                    <input type="text"
                                        name="prefix[{{ $code }}]"
                                        class="input toggle-pad"
                                        v-show="showPrefix('{{ $code }}')"
                                        value="{{ old('prefix.'.$code) }}"
                                        placeholder="abc">
                                @endforeach
                            </div>
                        </div>

                        {{-- url --}}
                        <div class="field">
                            {{ Form::label('url', trans('SimpleMenu::messages.url'), ['class' => 'label']) }}
                            <div class="control input-box">
                                <div class="select toggle-locale">
                                    <select v-model="url">
                                        @foreach ($locales as $code)
                                            <option value="{{ $code }}">{{ $code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @foreach ($locales as $code)
                                    <input type="text"
                                        name="url[{{ $code }}]"
                                        class="input toggle-pad"
                                        v-show="showUrl('{{ $code }}')"
                                        value="{{ old('url.'.$code) }}"
                                        placeholder="xyz/{someParam}">
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
                            {{ Form::label('menus', trans('SimpleMenu::messages.menus'), ['class' => 'label']) }}
                            <div class="control">
                                {{ Form::select('menus[]', $menus, null, ['class' => 'select2', 'multiple' => 'multiple']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                {{-- Guards --}}
                <div class="columns">
                    <div class="column is-2">
                        <h3 class="title link" @click="toggleGuards = !toggleGuards">
                            {{ trans('SimpleMenu::messages.guards') }}
                        </h3>
                    </div>
                    <div class="column is-10" v-show="toggleGuards">
                        {{-- roles --}}
                        <div class="field">
                            {{ Form::label('roles', trans('SimpleMenu::messages.roles'), ['class' => 'label']) }}
                            <div class="control">
                                {{ Form::select('roles[]', $roles, null, ['class' => 'select2', 'multiple' => 'multiple']) }}
                            </div>
                        </div>

                        {{-- permissions --}}
                        <div class="field">
                            {{ Form::label('permissions', trans('SimpleMenu::messages.permissions'), ['class' => 'label']) }}
                            <div class="control">
                                {{ Form::select('permissions[]', $permissions, null, ['class' => 'select2', 'multiple' => 'multiple']) }}
                            </div>
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
        </div>
    </sm-page>
@endsection