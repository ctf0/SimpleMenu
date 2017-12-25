@extends("SimpleMenu::admin.$css_fw.shared")
@section('title', trans('SimpleMenu::messages.roles'))

@section('sub')
    <sm-index inline-template :count="{{ count($roles) }}">
        <div>
            <div class="level">
                <div class="level-left">
                    <h3 class="title">
                        {{ trans('SimpleMenu::messages.roles') }} "<span>@{{ itemsCount }}</span>"
                    </h3>
                </div>
                <div class="level-right">
                    <a href="{{ route($crud_prefix.'.roles.create') }}"
                        class="button is-success">
                        {{ trans('SimpleMenu::messages.app_add_new') }}
                    </a>
                </div>
            </div>

            <table class="table is-hoverable is-fullwidth is-bordered" id="table">
                <thead>
                    <tr>
                        <th class="is-dark sort link" data-sort="data-sort-name">{{ trans('SimpleMenu::messages.name') }}</th>
                        <th class="is-dark sort link" data-sort="data-sort-permissions">{{ trans('SimpleMenu::messages.permissions') }}</th>
                        <th class="is-dark">{{ trans('SimpleMenu::messages.ops') }}</th>
                    </tr>
                </thead>

                <tbody class="list">
                    @foreach ($roles as $role)
                        <tr id="item-{{ $role->id }}">
                            <td class="data-sort-name">{{ $role->name }}</td>
                            <td class="data-sort-permissions">
                                @foreach ($role->permissions as $perm)
                                    <span class="tag is-rounded is-medium is-link">
                                        <a href="{{ route($crud_prefix.'.permissions.edit',[$perm->id]) }}" class="is-white">{{ $perm->name }}</a>
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route($crud_prefix.'.roles.edit',[$role->id]) }}"
                                    class="button is-link is-inline-block">
                                    {{ trans('SimpleMenu::messages.app_edit') }}
                                </a>

                                @php
                                    $check = in_array($role->name, auth()->user()->roles->pluck('name')->toArray()) ? 'disabled' : '';
                                @endphp

                                <a class="is-inline-block">
                                    {{ Form::open([
                                        'method' => 'DELETE',
                                        'route' => [$crud_prefix.'.roles.destroy', $role->id],
                                        'data-id'=>'item-'.$role->id,
                                        '@submit.prevent'=>'DelItem($event,"'.$role->name.'")'
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
                        <td colspan="3">{{ trans('SimpleMenu::messages.app_no_entries') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </sm-index>
@endsection
