@extends("SimpleMenu::admin.$css_fw.shared")
@section('title', trans('SimpleMenu::messages.users'))

@section('sub')
    <sm-index inline-template :count="{{ count($users) }}">
        <div>
            <div class="level">
                <div class="level-left">
                    <h3 class="title">
                        {{ trans('SimpleMenu::messages.users') }} "<span>@{{ itemsCount }}</span>"
                    </h3>
                </div>
                <div class="level-right">
                    <a href="{{ route($crud_prefix.'.users.create') }}"
                        class="button is-success">
                        {{ trans('SimpleMenu::messages.app_add_new') }}
                    </a>
                </div>
            </div>

            <table class="table is-hoverable is-fullwidth is-bordered" id="table">
                <thead>
                    <tr>
                        <th class="is-dark sort link" data-sort="data-sort-name">{{ trans('SimpleMenu::messages.name') }}</th>
                        <th class="is-dark sort link" data-sort="data-sort-email">{{ trans('SimpleMenu::messages.email') }}</th>
                        <th class="is-dark sort link" data-sort="data-sort-roles">{{ trans('SimpleMenu::messages.roles') }}</th>
                        <th class="is-dark sort link" data-sort="data-sort-permissions">{{ trans('SimpleMenu::messages.permissions') }}</th>
                        <th class="is-dark">{{ trans('SimpleMenu::messages.ops') }}</th>
                    </tr>
                </thead>

                <tbody class="list">
                    @foreach ($users as $user)
                        <tr id="item-{{ $user->id }}">
                            <td class="data-sort-name">{{ $user->name }}</td>
                            <td class="data-sort-email">{{ $user->email }}</td>
                            <td class="data-sort-roles">
                                @foreach ($user->roles as $role)
                                    <span class="tag is-rounded is-medium is-link">
                                        <a href="{{ route($crud_prefix.'.roles.edit',[$role->id]) }}" class="is-white">{{ $role->name }}</a>
                                    </span>
                                @endforeach
                            </td>
                            <td class="data-sort-permissions">
                                @foreach ($user->permissions as $perm)
                                    <span class="tag is-rounded is-medium is-link">
                                        <a href="{{ route($crud_prefix.'.permissions.edit',[$perm->id]) }}" class="is-white">{{ $perm->name }}</a>
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route($crud_prefix.'.users.edit',[$user->id]) }}"
                                    class="button is-link is-inline-block">
                                    {{ trans('SimpleMenu::messages.app_edit') }}
                                </a>

                                @php
                                    $check = $user->id == auth()->user()->id ? 'disabled' : '';
                                @endphp

                                <a class="is-inline-block">
                                    {{ Form::open([
                                        'method' => 'DELETE',
                                        'route' => [$crud_prefix.'.users.destroy', $user->id],
                                        'data-id'=>'item-'.$user->id,
                                        '@submit.prevent'=>'DelItem($event,"'.$user->name.'")'
                                    ]) }}
                                        <button type="submit" class="button is-danger" {{ $check }}>
                                            {{ trans('SimpleMenu::messages.app_delete') }}
                                        </button>
                                    {{ Form::close() }}
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    <tr v-show="itemsCount == 0">
                        <td colspan="5">{{ trans('SimpleMenu::messages.app_no_entries') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </sm-index>
@endsection
