@extends("SimpleMenu::admin.$css_fw.shared")
@section('title', trans('SimpleMenu::messages.pages'))

@section('sub')
    <sm-index inline-template :count="{{ count($pages) }}">
        <div>
            <div class="level">
                <div class="level-left">
                    <h3 class="title">
                        {{ trans('SimpleMenu::messages.pages') }} "<span>@{{ itemsCount }}</span>"
                    </h3>
                </div>
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

            <table class="table is-hoverable is-fullwidth is-bordered" id="table">
                <thead>
                    <tr>
                        <th width="1%" nowrap class="is-dark link"
                            @click="selectAll()"
                            v-text="ids.length > 0
                            ? '{{ trans('SimpleMenu::messages.select_non') }}'
                            : '{{ trans('SimpleMenu::messages.select_all') }}'"></th>
                        <th class="is-dark sort link" data-sort="data-sort-title">{{ trans('SimpleMenu::messages.title') }}</th>
                        <th class="is-dark sort link" data-sort="data-sort-route">{{ trans('SimpleMenu::messages.route') }}</th>
                        <th class="is-dark sort link" data-sort="data-sort-url">{{ trans('SimpleMenu::messages.url') }}</th>
                        <th class="is-dark sort link" data-sort="data-sort-roles">{{ trans('SimpleMenu::messages.roles') }}</th>
                        <th class="is-dark sort link" data-sort="data-sort-permissions">{{ trans('SimpleMenu::messages.permissions') }}</th>
                        <th class="is-dark sort link" data-sort="data-sort-menus">{{ trans('SimpleMenu::messages.menus') }}</th>
                        <th class="is-dark sort link" data-sort="data-sort-locals">{{ trans('SimpleMenu::messages.locals') }}</th>
                        <th class="is-dark sort link" data-sort="data-sort-template">{{ trans('SimpleMenu::messages.template') }}</th>
                        <th class="is-dark">{{ trans('SimpleMenu::messages.ops') }}</th>
                    </tr>
                </thead>

                <tbody class="list">
                    @foreach ($pages as $page)
                        @include('SimpleMenu::menu.partials.r_params')

                        <tr id="item-{{ $page->id }}">
                            <td style="text-align: center;">
                                <input type="checkbox" id="sm-{{ $page->id }}"
                                    v-model="ids"
                                    class="cbx-checkbox"
                                    value="{{ $page->id }}"
                                    v-multi-ref="'sm-ids'">
                                <label for="sm-{{ $page->id }}" class="cbx is-marginless">
                                    <svg width="14px" height="12px" viewBox="0 0 14 12"><polyline points="1 7.6 5 11 13 1"></polyline></svg>
                                </label>
                            </td>
                            <td>
                                @if (in_array(LaravelLocalization::getCurrentLocale(), $page->getTranslatedLocales('title')))
                                    <a class="data-sort-title" href="{{ SimpleMenu::routeUrl() }}">{{ $page->title }}</a>
                                @else
                                    <span class="data-sort-title">{{ empty($page->title) ? collect($page->getTranslations('title'))->first() : $page->title }}</span>
                                @endif
                            </td>
                            <td class="data-sort-route">{{ $page->route_name }}</td>
                            <td class="data-sort-url">{{ $page->prefix ? "$page->prefix/$page->url" : $page->url }}</td>
                            <td class="data-sort-roles">
                                @foreach ($page->roles as $role)
                                    <span class="tag is-rounded is-medium is-link">
                                        <a href="{{ route($crud_prefix.'.roles.edit',[$role->id]) }}" class="is-white">{{ $role->name }}</a>
                                    </span>
                                @endforeach
                            </td>
                            <td class="data-sort-permissions">
                                @foreach ($page->permissions as $perm)
                                    <span class="tag is-rounded is-medium is-link">
                                        <a href="{{ route($crud_prefix.'.permissions.edit',[$perm->id]) }}" class="is-white">{{ $perm->name }}</a>
                                    </span>
                                @endforeach
                            </td>
                            <td class="data-sort-menus">
                                @foreach ($page->menus as $menu)
                                    <span class="tag is-rounded is-medium is-link">
                                        <a href="{{ route($crud_prefix.'.menus.edit',[$menu->id]) }}" class="is-white">{{ $menu->name }}</a>
                                    </span>
                                @endforeach
                            </td>
                            <td class="data-sort-locals">
                                @foreach ($page->getTranslatedLocales('title') as $locale)
                                    <span class="tag is-rounded is-medium is-warning">{{ $locale }}</span>
                                @endforeach
                            </td>
                            <td class="data-sort-template">
                                @if ($page->template)
                                    <span class="tag is-rounded is-medium is-primary">{{ $page->template }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route($crud_prefix.'.pages.edit',[$page->id]) }}"
                                    class="button is-link is-inline-block">
                                    {{ trans('SimpleMenu::messages.edit') }}
                                </a>
                                <a class="is-inline-block">
                                    @if (config('simpleMenu.deletePageAndNests'))
                                        {{ Form::open(['method' => 'DELETE', 'route' => [$crud_prefix.'.pages.destroy', $page->id]]) }}
                                            <button type="submit" class="button is-danger">
                                                {{ trans('SimpleMenu::messages.delete') }}
                                            </button>
                                        {{ Form::close() }}
                                    @else
                                        {{ Form::open([
                                            'method' => 'DELETE',
                                            'route' => [$crud_prefix.'.pages.destroy', $page->id],
                                            'data-id'=>'item-'.$page->id,
                                            '@submit.prevent'=>'DelItem($event,"'.$page->title.'")'
                                        ]) }}
                                            <button type="submit" class="button is-danger">
                                                {{ trans('SimpleMenu::messages.delete') }}
                                            </button>
                                        {{ Form::close() }}
                                    @endif
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    <tr v-show="itemsCount == 0">
                        <td colspan="7">{{ trans('SimpleMenu::messages.no_entries') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </sm-index>
@endsection
