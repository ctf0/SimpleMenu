@extends("SimpleMenu::admin.shared")
@section('title', trans('SimpleMenu::messages.users'))

@section('sub')
    <sm-index inline-template v-cloak :count="{{ count($users) }}">
        <div>
            <div class="level">
                <div class="level-left"></div>
                <div class="level-right">
                    {{-- delete multi --}}
                    <div class="level-item">
                        <template v-if="ids.length > 1">
                            {{ Form::open(['route' => $crud_prefix.'.users.destroy_multi']) }}
                                <input type="hidden" name="ids" :value="ids">
                                <button type="submit" class="button is-danger">
                                    {{ trans('SimpleMenu::messages.delete_selected') }} "<span>@{{ ids.length }}</span>"
                                </button>
                            {{ Form::close() }}
                        </template>
                    </div>

                    {{-- add new --}}
                    <div class="level-item">
                        <a href="{{ route($crud_prefix.'.users.create') }}"
                            class="button is-success">
                            {{ trans('SimpleMenu::messages.add_new') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="level">
                <div class="level-left">
                    <h3 class="title">
                        {{ trans('SimpleMenu::messages.users') }} "<span>@{{ itemsCount }}</span>"
                    </h3>
                </div>

                {{-- search --}}
                <div class="level-right">
                    <div class="field has-addons">
                        <p class="control has-icons-left">
                            <input class="input"
                            type="text"
                            v-model="searchFor"
                            placeholder="{{ trans('SimpleMenu::messages.find') }}">
                            <span class="icon is-left">
                                <icon name="search"></icon>
                            </span>
                        </p>
                        <p class="control">
                            <button class="button is-black" :disabled="!searchFor"
                                @click="resetSearch()">
                                <span class="icon"><icon name="times"></icon></span>
                            </button>
                        </p>
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
                            <td style="text-align: center;">
                                <input type="checkbox" id="sm-{{ $user->id }}"
                                    v-model="ids"
                                    class="cbx-checkbox"
                                    value="{{ $user->id }}"
                                    v-multi-ref="'sm-ids'">
                                <label for="sm-{{ $user->id }}" class="cbx is-marginless">
                                    <svg width="14px" height="12px" viewBox="0 0 14 12"><polyline points="1 7.6 5 11 13 1"></polyline></svg>
                                </label>
                            </td>
                            <td class="data-sort-name">{{ $user->name }}</td>
                            <td class="data-sort-email">{{ $user->email }}</td>
                            <td class="data-sort-roles">
                                @foreach ($user->roles as $role)
                                    <span class="tag is-rounded is-medium is-link">
                                        <a href="{{ route($crud_prefix.'.roles.edit', $role->id) }}" class="is-white">{{ $role->name }}</a>
                                    </span>
                                @endforeach
                            </td>
                            <td class="data-sort-permissions">
                                @foreach ($user->permissions as $perm)
                                    <span class="tag is-rounded is-medium is-link">
                                        <a href="{{ route($crud_prefix.'.permissions.edit', $perm->id) }}" class="is-white">{{ $perm->name }}</a>
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route($crud_prefix.'.users.edit', $user->id) }}"
                                    class="button is-link is-inline-block">
                                    {{ trans('SimpleMenu::messages.edit') }}
                                </a>

                                @php
                                    $check = $user->id == auth()->user()->id ? 'disabled' : '';
                                @endphp

                                <a class="is-inline-block">
                                    {{ Form::open([
                                        'method' => 'DELETE', 
                                        'route' => [$crud_prefix.'.users.destroy', $user->id], 
                                        'data-id' => 'item-'.$user->id, 
                                        '@submit.prevent' => 'DelItem($event, "'.$user->name.'")'
                                    ]) }}
                                        <button type="submit" class="button is-danger" {{ $check }}>
                                            {{ trans('SimpleMenu::messages.delete') }}
                                        </button>
                                    {{ Form::close() }}
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    <tr v-if="itemsCount == 0">
                        <td colspan="6" style="text-align: center">{{ trans('SimpleMenu::messages.no_entries') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </sm-index>
@endsection
