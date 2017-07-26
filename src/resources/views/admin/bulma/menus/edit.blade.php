@extends('SimpleMenu::admin.'.config('simpleMenu.framework').'.shared')
@section('title'){{ "Edit '$menu->name'" }}@endsection

@section('sub')
    <h3 class="title">
        <a href="{{ route('admin.menus.index') }}">Go Back</a>
    </h3>

    <menu-comp inline-template
        pages-route="{{ route('admin.menus.getMenuPages',['id'=>$menu->id]) }}"
        del-route="{{ route('admin.menus.removePage',['id'=>$menu->id]) }}"
        locale="{{ LaravelLocalization::getCurrentLocale() }}">
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

                <div class="columns">
                    {{-- pages --}}
                    <draggable v-model="pages"
                        class="column is-4 menu-list"
                        style="min-height: 35px; display: block"
                        :options="{draggable:'.item', group:'pages'}"
                        :element="'ul'"
                        @change="checkAdded">
                        <li v-for="item in pages" :key="item.id" class="item">
                            <div class="notification is-info menu-item" :class="{'is-warning' : !item.updated_at}">
                                <span class="icon is-small"><i class="fa fa-caret-right"></i></span>
                                <span v-html="getTitle(item.title)"></span>

                                <button type="button" v-if="item.updated_at" class="delete" @click.prevent="deleteItem(item)"></button>
                                <button type="button" v-else class="delete" @click="undoItem(item)"></button>
                            </div>

                            {{-- childs --}}
                            <menu-child v-if="item.children" :childs="item.children" :locale="locale"></menu-child>
                        </li>
                    </draggable>

                    {{-- all_pages --}}
                    <draggable v-model="allPages"
                        class="column"
                        :element="'ul'"
                        :options="{draggable:'.item', group:{name:'pages', put:false}, chosenClass:'is-warning'}">
                        <li v-for="item in allPages" :key="item.id" class="item notification is-info menu-item" style="cursor: default">
                            <span class="icon is-small"><i class="fa fa-caret-right"></i></span>
                            <span v-html="getTitle(item.title)"></span>
                        </li>
                    </draggable>
                </div>

                <input type="hidden" name="saveList" v-model="JSON.stringify(saveList)">
            {{ Form::close() }}
        </div>
    </menu-comp>
@stop