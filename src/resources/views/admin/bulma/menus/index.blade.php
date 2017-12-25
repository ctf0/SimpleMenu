@extends("SimpleMenu::admin.$css_fw.shared")
@section('title', trans('SimpleMenu::messages.menus'))

@section('sub')
    <sm-index inline-template :count="{{ count($menus) }}">
        <div>
            <div class="level">
                <div class="level-left">
                    <h3 class="title">{{ trans('SimpleMenu::messages.menus') }} "<span>@{{ itemsCount }}</span>"</h3>
                </div>
                <div class="level-right">
                    <a href="{{ route($crud_prefix.'.menus.create') }}"
                        class="button is-success">
                        {{ trans('SimpleMenu::messages.app_add_new') }}
                    </a>
                </div>
            </div>

            <table class="table is-hoverable is-fullwidth is-bordered" id="table">
                <thead>
                    <tr>
                        <th class="is-dark sort link" data-sort="data-sort-name">{{ trans('SimpleMenu::messages.name') }}</th>
                        <th class="is-dark">{{ trans('SimpleMenu::messages.ops') }}</th>
                    </tr>
                </thead>

                <tbody class="list">
                    @foreach ($menus as $menu)
                        <tr id="menu-{{ $menu->id }}">
                            <td class="data-sort-name">{{ $menu->name }}</td>
                            <td>
                                <a href="{{ route($crud_prefix.'.menus.edit',[$menu->id]) }}" class="button is-link is-inline-block">
                                    {{ trans('SimpleMenu::messages.app_edit') }}
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
                        <td colspan="2">{{ trans('SimpleMenu::messages.app_no_entries') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </sm-index>
@endsection
