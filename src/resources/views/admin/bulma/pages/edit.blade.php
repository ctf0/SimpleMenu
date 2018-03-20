@extends("SimpleMenu::admin.shared")
@section('title'){{ trans('SimpleMenu::messages.edit') }} "{{ empty($page->title) ? collect($page->getTranslations('title'))->first() : $page->title }}"@endsection

@section('sub')
    <div class="level">
        <div class="level-left">
            <h3 class="title">
                <a href="{{ route($crud_prefix.'.pages.index') }}">{{ trans('SimpleMenu::messages.go_back') }}</a>
            </h3>
        </div>
        <div class="level-right">
            {{-- create new --}}
            <div class="level-item">
                <a href="{{ route($crud_prefix.'.pages.create') }}"
                    class="button is-success">
                    {{ trans('SimpleMenu::messages.add_new') }}
                </a>
            </div>

            @if ($page->trashed())
                {{-- restore --}}
                <div class="level-item">
                    {{ Form::open(['method' => 'PUT', 'route' => [$crud_prefix.'.pages.restore', $page->id]]) }}
                        <button type="submit" class="button is-link">
                            {{ trans('SimpleMenu::messages.restore') }}
                        </button>
                    {{ Form::close() }}
                </div>

                {{-- soft delete --}}
                <div class="level-item">
                    {{ Form::open(['method' => 'DELETE', 'route' => [$crud_prefix.'.pages.destroy_force', $page->id]]) }}
                        <button type="submit" class="button is-danger">
                            {{ trans('SimpleMenu::messages.perm_delete') }}
                        </button>
                    {{ Form::close() }}
                </div>
            @else
                <div class="level-item">
                    {{-- delete --}}
                    @php
                        $check = $page->route_name == $crud_prefix ? 'disabled' : '';
                    @endphp

                    {{ Form::open(['method' => 'DELETE', 'route' => [$crud_prefix.'.pages.destroy', $page->id]]) }}
                        <button type="submit" class="button is-danger" {{ $check }}>
                            {{ trans('SimpleMenu::messages.delete') }}
                        </button>
                    {{ Form::close() }}
                </div>
            @endif
        </div>
    </div>

    <sm-page inline-template select-first="{{ LaravelLocalization::getCurrentLocale() }}">
        <div>
            {{ Form::model($page, ['method' => 'PUT', 'route' => [$crud_prefix.'.pages.update', $page->id], 'files' => true]) }}

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
                                        value="{{ $page->getTranslationWithoutFallback('meta', $code) }}"
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
                                    $page->action, 
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

                        {{-- controller file --}}
                        @if ($controllerFile)
                            <div id="ace-editor">{{{ $controllerFile }}}</div>
                            <input type="hidden" name="controllerFile" ref="controllerFile">
                        @endif

                        {{-- template --}}
                        <div class="field">
                            {{ Form::label('template', trans('SimpleMenu::messages.template'), ['class' => 'label']) }}
                            <div class="control">
                                {{ Form::text(
                                    'template', 
                                    $page->template, 
                                    ['class' => 'input', 
                                    'placeholder' => "ex.'folder.hero' or 'Vendor::xyz'", 
                                    'ref' => 'template'])
                                }}
                            </div>
                            @if (count($templates))
                                <div class="tags m-t-5">
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
                                {{ Form::text(
                                    'route_name', 
                                    $page->route_name, 
                                    ['class' => 'input', 'placeholder' => "route-name"])
                                }}
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
                                    $page->middlewares, 
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
                        {{-- preview --}}
                        @if ($page->cover)
                            <img src="{{ $page->cover }}">
                        @endif
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
                                    <input type="text" name="title[{{ $code }}]"
                                        class="input toggle-pad"
                                        v-show="showTitle('{{ $code }}')"
                                        value="{{ $page->getTranslationWithoutFallback('title', $code) }}"
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
                                        {{ $page->getTranslationWithoutFallback('body', $code) }}
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
                                        {{ $page->getTranslationWithoutFallback('desc', $code) }}
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
                                        value="{{ $page->getTranslationWithoutFallback('prefix', $code) }}"
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
                                        value="{{ $page->getTranslationWithoutFallback('url', $code) }}"
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
                                {{ Form::select(
                                    'menus[]', 
                                    $menus, 
                                    $page->menus->pluck('id', 'name'), 
                                    ['class' => 'select2', 'multiple' => 'multiple'])
                                }}
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
                                {{ Form::select(
                                    'roles[]', 
                                    $roles, 
                                    $page->roles->pluck('name', 'name'), 
                                    ['class' => 'select2', 'multiple' => 'multiple'])
                                }}
                            </div>
                        </div>

                        {{-- permissions --}}
                        <div class="field">
                            {{ Form::label('permissions', trans('SimpleMenu::messages.permissions'), ['class' => 'label']) }}
                            <div class="control">
                                {{ Form::select(
                                    'permissions[]', 
                                    $permissions, 
                                    $page->permissions->pluck('name', 'name'), 
                                    ['class' => 'select2', 'multiple' => 'multiple'])
                                }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="columns">
                    <div class="column is-2"></div>
                    <div class="column is-2">
                        {{ Form::submit(trans('SimpleMenu::messages.update'), ['class' => 'button is-warning is-fullwidth']) }}
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </sm-page>
@endsection
