@extends("SimpleMenu::admin.$css_fw.shared")
@section('title', 'Menus')

@section('sub')
    <index-comp inline-template :count="{{ count($menus) }}">
        <div>
            <h3 class="title">
                @lang('SimpleMenu::messages.menus.title') "<span>@{{ itemsCount }}</span>"
                <a href="{{ route($crud_prefix.'.menus.create') }}"
                    class="button is-success is-pulled-right">
                    @lang('SimpleMenu::messages.app_add_new')
                </a>
            </h3>

            <table class="table is-narrow is-fullwidth is-bordered">
                <thead>
                    <tr>
                        <th>@lang('SimpleMenu::messages.menus.fields.name')</th>
                        <th>@lang('SimpleMenu::messages.ops')</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($menus as $menu)
                        <tr id="menu-{{ $menu->id }}">
                            <td>{{ $menu->name }}</td>
                            <td>
                                <a href="{{ route($crud_prefix.'.menus.edit',[$menu->id]) }}" class="button is-link is-inline-block">
                                    @lang('SimpleMenu::messages.app_edit')
                                </a>
                                <a class="is-inline-block">
                                    {{ Form::open([
                                        'method' => 'DELETE',
                                        'route' => [$crud_prefix.'.menus.destroy', $menu->id],
                                        'data-id'=>'menu-'.$menu->id,
                                        '@submit.prevent'=>'DelItem($event,"'.$menu->name.'")'
                                    ]) }}
                                        {{ Form::submit(trans('SimpleMenu::messages.app_delete'), ['class' => 'button is-danger']) }}
                                    {{ Form::close() }}
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    <tr v-show="itemsCount == 0">
                        <td colspan="2">@lang('SimpleMenu::messages.app_no_entries_in_table')</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </index-comp>
@endsection
