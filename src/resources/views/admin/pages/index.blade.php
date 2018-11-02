@extends("SimpleMenu::admin.shared")
@section('title', trans('SimpleMenu::messages.pages'))

@section('sub')
    <sm-index inline-template v-cloak :count="{{ count($pages) }}">
        <div>
            <div class="level">
                <div class="level-left"></div>
                <div class="level-right">
                    {{-- delete multi --}}
                    <div class="level-item">
                        <template v-if="ids.length > 1">
                            {{ Form::open(['route' => $crud_prefix.'.pages.destroy_multi']) }}
                                <input type="hidden" name="ids" :value="ids">
                                <button type="submit" class="button is-danger">
                                    {{ trans('SimpleMenu::messages.delete_selected') }} "<span>@{{ ids.length }}</span>"
                                </button>
                            {{ Form::close() }}
                        </template>
                    </div>

                    {{-- add new --}}
                    <div class="level-item">
                        <a href="{{ route($crud_prefix.'.pages.create') }}"
                            class="button is-success">
                            {{ trans('SimpleMenu::messages.add_new') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="level">
                <div class="level-left">
                    <h3 class="title">
                        {{ trans('SimpleMenu::messages.pages') }} "<span>@{{ itemsCount }}</span>"
                    </h3>
                </div>

                {{-- search --}}
                <div class="level-right">
                    <div class="field has-addons">
                        <p class="control has-icons-left">
                            <input class="input"
                            type="text"
                            v-model="searchFor"
                            placeholder="{{ trans('SimpleMenu::messages.find') }}">
                            <span class="icon is-left">
                                <icon name="search"></icon>
                            </span>
                        </p>
                        <p class="control">
                            <button class="button is-black" :disabled="!searchFor"
                                @click="resetSearch()">
                                <span class="icon"><icon name="times"></icon></span>
                            </button>
                        </p>
                    </div>
                </div>
            </div>

            <table class="table is-hoverable is-fullwidth is-bordered" id="table">
                <thead>
                    <tr>
                        <th class="is-dark link static-cell"
                            @click="selectAll()"
                            v-text="ids.length > 0
                            ? '{{ trans('SimpleMenu::messages.select_non') }}'
                            : '{{ trans('SimpleMenu::messages.select_all') }}'"></th>
                        <th class="is-dark sort link" data-sort="data-sort-name">{{ trans('SimpleMenu::messages.title') }}</th>
                        <th class="is-dark sort link" data-sort="data-sort-route">{{ trans('SimpleMenu::messages.route') }}</th>
                        <th class="is-dark sort link" data-sort="data-sort-url">{{ trans('SimpleMenu::messages.url') }}</th>
                        <th class="is-dark sort link static-cell" data-sort="data-sort-middlewares">{{ trans('SimpleMenu::messages.middlewares') }}</th>
                        <th class="is-dark sort link static-cell" data-sort="data-sort-roles">{{ trans('SimpleMenu::messages.roles') }}</th>
                        <th class="is-dark sort link static-cell" data-sort="data-sort-permissions">{{ trans('SimpleMenu::messages.permissions') }}</th>
                        <th class="is-dark sort link static-cell" data-sort="data-sort-menus">{{ trans('SimpleMenu::messages.menus') }}</th>
                        <th class="is-dark sort link static-cell" data-sort="data-sort-locals">{{ trans('SimpleMenu::messages.locals') }}</th>
                        <th class="is-dark sort link static-cell" data-sort="data-sort-template">{{ trans('SimpleMenu::messages.template') }}</th>
                        <th class="is-dark sort link static-cell" data-sort="data-sort-ops">{{ trans('SimpleMenu::messages.ops') }}</th>
                    </tr>
                </thead>

                <tbody class="list">
                    @foreach ($pages as $page)
                        @include('SimpleMenu::menu.partials.r_params')

                        <tr id="item-{{ $page->id }}">
                            <td class="has-text-centered link" @click="clickOnCkBox('sm-{{ $page->id }}')">
                                <input type="checkbox"
                                    id="sm-{{ $page->id }}"
                                    v-model="ids"
                                    class="cbx-checkbox"
                                    value="{{ $page->id }}"
                                    v-multi-ref:sm-ids>
                                <label for="sm-{{ $page->id }}" class="cbx is-marginless" @click.prevent>
                                    <svg width="14px" height="12px" viewBox="0 0 14 12"><polyline points="1 7.6 5 11 13 1"></polyline></svg>
                                </label>
                            </td>
                            <td class="static-cell">
                                @if (in_array(LaravelLocalization::getCurrentLocale(), $page->getTranslatedLocales('title')))
                                    <a class="data-sort-name" href="{{ SimpleMenu::routeUrl() }}">{{ $page->title }}</a>
                                @else
                                    <span class="data-sort-name">{{ empty($page->title) ? collect($page->getTranslations('title'))->first() : $page->title }}</span>
                                @endif
                            </td>
                            <td class="data-sort-route static-cell">{{ $page->route_name }}</td>
                            <td class="data-sort-url static-cell">{{ $page->prefix ? "$page->prefix/$page->url" : $page->url }}</td>
                            <td>
                                @if ($page->middlewares)
                                    <span class="tag is-rounded is-medium is-link data-sort-middlewares">{{ $page->middlewares }}</span>
                                @endif
                            </td>
                            <td class="data-sort-roles" data-roles="{{ $page->roles->count() }}">
                                @foreach ($page->roles as $role)
                                    <span class="tag is-rounded is-medium is-link">
                                        <a href="{{ route($crud_prefix.'.roles.edit', $role->id) }}" class="is-white">{{ $role->name }}</a>
                                    </span>
                                @endforeach
                            </td>
                            <td class="data-sort-permissions" data-permissions="{{ $page->permissions->count() }}">
                                @foreach ($page->permissions as $perm)
                                    <span class="tag is-rounded is-medium is-link">
                                        <a href="{{ route($crud_prefix.'.permissions.edit', $perm->id) }}" class="is-white">{{ $perm->name }}</a>
                                    </span>
                                @endforeach
                            </td>
                            <td class="data-sort-menus" data-menus="{{ $page->menus->count() }}">
                                @foreach ($page->menus as $menu)
                                    <span class="tag is-rounded is-medium is-link">
                                        <a href="{{ route($crud_prefix.'.menus.edit', $menu->id) }}" class="is-white">{{ $menu->name }}</a>
                                    </span>
                                @endforeach
                            </td>
                            <td class="data-sort-locals" data-locals="{{ count($page->getTranslatedLocales('title')) }}">
                                @foreach ($page->getTranslatedLocales('title') as $locale)
                                    <span class="tag is-rounded is-medium is-warning">{{ $locale }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if ($page->template)
                                    <span class="tag is-rounded is-medium is-primary data-sort-template">{{ $page->template }}</span>
                                @endif
                            </td>
                            <td class="data-sort-ops static-cell" data-ops="{{ $page->trashed() ? 'false' : 'true' }}">
                                <a href="{{ route($crud_prefix.'.pages.edit', $page->id) }}"
                                    class="button is-link is-inline-block">
                                    {{ trans('SimpleMenu::messages.edit') }}
                                </a>

                                {{-- soft delete --}}
                                @if ($page->trashed())
                                    <a class="is-inline-block">
                                        {{ Form::open(['method' => 'PUT', 'route' => [$crud_prefix.'.pages.restore', $page->id]]) }}
                                            <button type="submit" class="button is-success" {{ $check }}>
                                                {{ trans('SimpleMenu::messages.restore') }}
                                            </button>
                                        {{ Form::close() }}
                                    </a>

                                    <a class="is-inline-block">
                                        {{ Form::open(['method' => 'DELETE', 'route' => [$crud_prefix.'.pages.destroy_force', $page->id]]) }}
                                            <button type="submit" class="button is-danger is-outlined" {{ $check }}>
                                                {{ trans('SimpleMenu::messages.perm_delete') }}
                                            </button>
                                        {{ Form::close() }}
                                    </a>

                                {{-- delete --}}
                                @else
                                    @php
                                        $check = $page->route_name == $crud_prefix ? 'disabled' : '';
                                    @endphp

                                    <a class="is-inline-block">
                                        {{ Form::open(['method' => 'DELETE', 'route' => [$crud_prefix.'.pages.destroy', $page->id]]) }}
                                            <button type="submit" class="button is-danger" {{ $check }}>
                                                {{ trans('SimpleMenu::messages.delete') }}
                                            </button>
                                        {{ Form::close() }}
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    <tr v-if="itemsCount == 0">
                        <td colspan="11" style="text-align: center">{{ trans('SimpleMenu::messages.no_entries') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </sm-index>
@endsection
