@extends("SimpleMenu::admin.$css_fw.shared")
@section('title', 'Pages')

@section('sub')

    <index-comp inline-template :count="{{ count($pages) }}">
        <div>
            <h3 class="title">
                @lang('SimpleMenu::messages.pages.title') "<span>@{{ itemsCount }}</span>"
                <a href="{{ route($crud_prefix.'.pages.create') }}" class="button is-success">@lang('SimpleMenu::messages.app_add_new')</a>
            </h3>

            <table class="table is-narrow is-fullwidth is-bordered">
                <thead>
                    <tr>
                        <th>@lang('SimpleMenu::messages.pages.fields.title')</th>
                        <th>@lang('SimpleMenu::messages.pages.fields.route')</th>
                        <th>@lang('SimpleMenu::messages.pages.fields.roles')</th>
                        <th>@lang('SimpleMenu::messages.pages.fields.permissions')</th>
                        <th>@lang('SimpleMenu::messages.pages.fields.menus')</th>
                        <th>@lang('SimpleMenu::messages.pages.fields.locals')</th>
                        <th>@lang('SimpleMenu::messages.pages.fields.template')</th>
                        <th>@lang('SimpleMenu::messages.ops')</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($pages as $page)
                        @include('SimpleMenu::menu.partials.r_params')

                        <tr id="item-{{ $page->id }}">
                            <td>
                                @if (in_array(LaravelLocalization::getCurrentLocale(), $page->getTranslatedLocales('title')))
                                    <a href="{{ SimpleMenu::routeUrl() }}">{{ $page->title }}</a>
                                @else
                                    {{ empty($page->title) ? collect($page->getTranslations('title'))->first() : $page->title }}
                                @endif
                            </td>
                            <td>
                                {{ $page->route_name }}
                            </td>
                            <td>
                                @foreach ($page->roles as $role)
                                    <span class="tag is-medium is-info">
                                        <a href="{{ route($crud_prefix.'.roles.edit',[$role->id]) }}" class="is-white">{{ $role->name }}</a>
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($page->permissions as $perm)
                                    <span class="tag is-medium is-info">
                                        <a href="{{ route($crud_prefix.'.permissions.edit',[$perm->id]) }}" class="is-white">{{ $perm->name }}</a>
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($page->menus as $menu)
                                    <span class="tag is-medium is-info">
                                        <a href="{{ route($crud_prefix.'.menus.edit',[$menu->id]) }}" class="is-white">{{ $menu->name }}</a>
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($page->getTranslatedLocales('title') as $locale)
                                    <span class="tag is-medium is-warning">{{ $locale }}</span>
                                @endforeach
                            </td>
                            <td><span class="tag is-medium is-primary">{{ $page->template }}</span></td>
                            <td>
                                <a href="{{ route($crud_prefix.'.pages.edit',[$page->id]) }}" class="button is-info is-inline-block">@lang('SimpleMenu::messages.app_edit')</a>
                                <a class="is-inline-block">
                                    @if (config('simpleMenu.deletePageAndNests'))
                                        {{ Form::open(['method' => 'DELETE', 'route' => [$crud_prefix.'.pages.destroy', $page->id]]) }}
                                            {{ Form::submit(trans('SimpleMenu::messages.app_delete'), ['class' => 'button is-danger']) }}
                                        {{ Form::close() }}
                                    @else
                                        {{ Form::open(['method' => 'DELETE', 'route' => [$crud_prefix.'.pages.destroy', $page->id], 'data-id'=>'item-'.$page->id ,'@submit.prevent'=>'DelItem($event,"'.$page->title.'")']) }}
                                            {{ Form::submit(trans('SimpleMenu::messages.app_delete'), ['class' => 'button is-danger']) }}
                                        {{ Form::close() }}
                                    @endif
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    <tr v-show="itemsCount == 0">
                        <td colspan="7">@lang('SimpleMenu::messages.app_no_entries_in_table')</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </index-comp>
@stop
