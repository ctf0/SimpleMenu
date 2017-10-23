@extends("SimpleMenu::admin.$css_fw.shared")
@section('title', 'Roles')

@section('sub')
    <index-comp inline-template :count="{{ count($roles) }}">
        <div>
            <div class="level">
                <div class="level-left">
                    <h3 class="title">
                        @lang('SimpleMenu::messages.roles.title') "<span>@{{ itemsCount }}</span>"
                    </h3>
                </div>
                <div class="level-right">
                    <a href="{{ route($crud_prefix.'.roles.create') }}"
                        class="button is-success">
                        @lang('SimpleMenu::messages.app_add_new')
                    </a>
                </div>
            </div>

            <table class="table is-narrow is-fullwidth is-bordered">
                <thead>
                    <tr>
                        <th>@lang('SimpleMenu::messages.roles.fields.name')</th>
                        <th>@lang('SimpleMenu::messages.roles.fields.permission')</th>
                        <th>@lang('SimpleMenu::messages.ops')</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($roles as $role)
                        <tr id="item-{{ $role->id }}">
                            <td>{{ $role->name }}</td>
                            <td>
                                @foreach ($role->permissions as $perm)
                                    <span class="tag is-medium is-link">
                                        <a href="{{ route($crud_prefix.'.permissions.edit',[$perm->id]) }}" class="is-white">{{ $perm->name }}</a>
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route($crud_prefix.'.roles.edit',[$role->id]) }}"
                                    class="button is-link is-inline-block">
                                    @lang('SimpleMenu::messages.app_edit')
                                </a>

                                @php
                                    $check = in_array($role->name, auth()->user()->roles->pluck('name')->toArray());
                                @endphp

                                <a class="is-inline-block">
                                    {{ Form::open([
                                        'method' => 'DELETE',
                                        'route' => [$crud_prefix.'.roles.destroy', $role->id],
                                        'data-id'=>'item-'.$role->id,
                                        '@submit.prevent'=>'DelItem($event,"'.$role->name.'")'
                                    ]) }}
                                        {{ Form::submit(
                                            trans('SimpleMenu::messages.app_delete'),
                                            ['class' => 'button is-danger', 'disabled' => $check ? true : false]
                                        ) }}
                                    {{ Form::close() }}
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    <tr v-show="itemsCount == 0">
                        <td colspan="3">@lang('SimpleMenu::messages.app_no_entries_in_table')</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </index-comp>
@endsection
