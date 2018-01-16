@extends("SimpleMenu::admin.$css_fw.shared")
@section('title', trans('SimpleMenu::messages.permissions'))

@section('sub')
    <sm-index inline-template :count="{{ count($permissions) }}">
        <div>
            <div class="level">
                <div class="level-left"></div>
                <div class="level-right">
                    {{-- delete multi --}}
                    <div class="level-item">
                        <template v-if="ids.length > 1">
                            {{ Form::open(['route' => $crud_prefix.'.permissions.destroy_multi']) }}
                                <input type="hidden" name="ids" :value="ids">
                                <button type="submit" class="button is-danger">
                                    {{ trans('SimpleMenu::messages.delete_selected') }} "<span>@{{ ids.length }}</span>"
                                </button>
                            {{ Form::close() }}
                        </template>
                    </div>

                    {{-- add new --}}
                    <div class="level-item">
                        <a href="{{ route($crud_prefix.'.permissions.create') }}"
                            class="button is-success">
                            {{ trans('SimpleMenu::messages.add_new') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="level">
                <div class="level-left">
                    <h3 class="title">
                        {{ trans('SimpleMenu::messages.permissions') }} "<span>@{{ itemsCount }}</span>"
                    </h3>
                </div>
                <div class="level-right">
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
                        <th class="is-dark">{{ trans('SimpleMenu::messages.ops') }}</th>
                    </tr>
                </thead>

                <tbody class="list">
                    @foreach ($permissions as $permission)
                        <tr id="item-{{ $permission->id }}">
                            <td style="text-align: center;">
                                <input type="checkbox" id="sm-{{ $permission->id }}"
                                    v-model="ids"
                                    class="cbx-checkbox"
                                    value="{{ $permission->id }}"
                                    v-multi-ref="'sm-ids'">
                                <label for="sm-{{ $permission->id }}" class="cbx is-marginless">
                                    <svg width="14px" height="12px" viewBox="0 0 14 12"><polyline points="1 7.6 5 11 13 1"></polyline></svg>
                                </label>
                            </td>
                            <td class="data-sort-name">{{ $permission->name }}</td>
                            <td>
                                <a href="{{ route($crud_prefix.'.permissions.edit',[$permission->id]) }}"
                                    class="button is-link is-inline-block">
                                    {{ trans('SimpleMenu::messages.edit') }}
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
                                            {{ trans('SimpleMenu::messages.delete') }}
                                        </button>
                                    {{ Form::close() }}
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    <tr v-if="itemsCount == 0">
                        <td colspan="2">{{ trans('SimpleMenu::messages.no_entries') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </sm-index>
@endsection
