@extends("SimpleMenu::admin.$css_fw.shared")
@section('title', trans('SimpleMenu::messages.permissions'))

@section('sub')
    <sm-index inline-template :count="{{ count($permissions) }}">
        <div>
            <div class="level">
                <div class="level-left">
                    <h3 class="title">
                        {{ trans('SimpleMenu::messages.permissions') }} "<span>@{{ itemsCount }}</span>"
                    </h3>
                </div>
                <div class="level-right">
                    <a href="{{ route($crud_prefix.'.permissions.create') }}"
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
                    @foreach ($permissions as $permission)
                        <tr id="item-{{ $permission->id }}">
                            <td class="data-sort-name">{{ $permission->name }}</td>
                            <td>
                                <a href="{{ route($crud_prefix.'.permissions.edit',[$permission->id]) }}"
                                    class="button is-link is-inline-block">
                                    {{ trans('SimpleMenu::messages.app_edit') }}
                                </a>

                                @php
                                    $check = in_array($permission->name, auth()->user()->permissions->pluck('name')->toArray()) ? 'disabled' : '';
                                @endphp

                                <a class="is-inline-block">
                                    {{ Form::open([
                                        'method' => 'DELETE',
                                        'route' => [$crud_prefix.'.permissions.destroy', $permission->id],
                                        'data-id'=>'item-'.$permission->id,
                                        '@submit.prevent'=>'DelItem($event,"'.$permission->name.'")'
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
                        <td colspan="2">{{ trans('SimpleMenu::messages.app_no_entries') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </sm-index>
@endsection
