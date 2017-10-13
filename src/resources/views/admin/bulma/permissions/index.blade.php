@extends("SimpleMenu::admin.$css_fw.shared")
@section('title', 'Permissions')

@section('sub')
    <index-comp inline-template :count="{{ count($permissions) }}">
        <div>
            <h3 class="title">
                @lang('SimpleMenu::messages.permissions.title') "<span>@{{ itemsCount }}</span>"
                <a href="{{ route($crud_prefix.'.permissions.create') }}" class="button is-success is-pulled-right">@lang('SimpleMenu::messages.app_add_new')</a>
            </h3>

            <table class="table is-narrow is-fullwidth is-bordered">
                <thead>
                    <tr>
                        <th>@lang('SimpleMenu::messages.permissions.fields.name')</th>
                        <th>@lang('SimpleMenu::messages.ops')</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($permissions as $permission)
                        <tr id="item-{{ $permission->id }}">
                            <td>{{ $permission->name }}</td>
                            <td>
                                <a href="{{ route($crud_prefix.'.permissions.edit',[$permission->id]) }}" class="button is-link is-inline-block">
                                    @lang('SimpleMenu::messages.app_edit')
                                </a>

                                @php
                                    $check = in_array($permission->name, auth()->user()->permissions->pluck('name')->toArray());
                                @endphp

                                <a class="is-inline-block">
                                    {{ Form::open(['method' => 'DELETE', 'route' => [$crud_prefix.'.permissions.destroy', $permission->id],'data-id'=>'item-'.$permission->id ,'@submit.prevent'=>'DelItem($event,"'.$permission->name.'")']) }}
                                        {{ Form::submit(trans('SimpleMenu::messages.app_delete'), ['class' => 'button is-danger', 'disabled' => $check ? true : false]) }}
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
