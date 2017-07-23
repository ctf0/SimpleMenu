@extends('SimpleMenu::pages.'.config('simpleMenu.framework').'.shared')
@section('title'){{ "Edit '$menu->name'" }}@endsection

@section('sub')
    <h3 class="title">
        <a href="{{ route('admin.menus.index') }}">Go Back</a>
    </h3>

    <menu-comp inline-template del-route="{{ route('admin.menus.removePage',['id'=>$menu->id]) }}" pages-route="{{ route('admin.menus.getPages',['id'=>$menu->id]) }}">
        <div>
            {{ Form::model($menu, ['method' => 'PUT', 'route' => ['admin.menus.update', $menu->id]]) }}

                {{-- name --}}
                <div class="field">
                    {{ Form::label('name', 'Name', ['class' => 'label']) }}
                </div>
                <div class="field has-addons">
                    <div class="control is-expanded">
                        {{ Form::text('name', $menu->name, ['class' => 'input']) }}
                    </div>
                    @if($errors->has('name'))
                        <p class="help is-danger">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                    <div class="control">
                        {{ Form::submit(trans('SimpleMenu::messages.app_update'), ['class' => 'button is-warning']) }}
                    </div>
                </div>

                {{-- pages --}}
                <div class="column is-4">
                    <ul v-sortable="{ onUpdate: onUpdate }">
                        <li v-for="item in list" :key="item.id">
                            <div class="notification is-info menu-item">
                                <p v-html="item.title.{{ LaravelLocalization::getCurrentLocale() }}"></p>
                                <button class="delete" @click.prevent="deleteItem(item)"></button>
                            </div>
                        </li>
                    </ul>
                </div>

                <input type="hidden" name="saveList" v-model="JSON.stringify(saveList)">
            {{ Form::close() }}
        </div>
    </menu-comp>
@stop
