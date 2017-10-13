@extends("SimpleMenu::admin.$css_fw.shared")
@section('title', 'Users')

@section('sub')
    <index-comp inline-template :count="{{ count($users) }}">
        <div>
            <h3 class="title">
                @lang('SimpleMenu::messages.users.title') "<span>@{{ itemsCount }}</span>"
                <a href="{{ route($crud_prefix.'.users.create') }}" class="button is-success is-pulled-right">@lang('SimpleMenu::messages.app_add_new')</a>
            </h3>

            <table class="table is-narrow is-fullwidth is-bordered">
                <thead>
                    <tr>
                        <th>@lang('SimpleMenu::messages.users.fields.name')</th>
                        <th>@lang('SimpleMenu::messages.users.fields.email')</th>
                        <th>@lang('SimpleMenu::messages.users.fields.roles')</th>
                        <th>@lang('SimpleMenu::messages.users.fields.permissions')</th>
                        <th>@lang('SimpleMenu::messages.ops')</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                        <tr id="item-{{ $user->id }}">
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach ($user->roles as $role)
                                    <span class="tag is-medium is-link">
                                        <a href="{{ route($crud_prefix.'.roles.edit',[$role->id]) }}" class="is-white">{{ $role->name }}</a>
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($user->permissions as $perm)
                                    <span class="tag is-medium is-link">
                                        <a href="{{ route($crud_prefix.'.permissions.edit',[$perm->id]) }}" class="is-white">{{ $perm->name }}</a>
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route($crud_prefix.'.users.edit',[$user->id]) }}" class="button is-link is-inline-block">@lang('SimpleMenu::messages.app_edit')</a>

                                @php
                                    $check = $user->id == auth()->user()->id;
                                @endphp

                                <a class="is-inline-block">
                                    {{ Form::open(['method' => 'DELETE', 'route' => [$crud_prefix.'.users.destroy', $user->id],'data-id'=>'item-'.$user->id ,'@submit.prevent'=>'DelItem($event,"'.$user->name.'")']) }}
                                        {{ Form::submit(trans('SimpleMenu::messages.app_delete'), ['class' => 'button is-danger', 'disabled' => $check ? true : false]) }}
                                    {{ Form::close() }}
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    <tr v-show="itemsCount == 0">
                        <td colspan="5">@lang('SimpleMenu::messages.app_no_entries_in_table')</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </index-comp>
@endsection
