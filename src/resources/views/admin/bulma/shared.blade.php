<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', '')">
    <title>@yield('title', '')</title>

    {{-- styles --}}
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bulma/0.6.0/css/bulma.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/SimpleMenu/style.css') }}"/>
</head>

<body>
    <section id="app">

        {{-- notif --}}
        <div class="notif-container">
            <my-notification></my-notification>
        </div>

        {{-- Body --}}
        <div class="container">
            <div class="columns">
                {{-- Sidebar --}}
                <div class="column is-2">
                    <aside class="menu">
                        <ul class="menu-list">
                            <li><a class="{{ URL::has("$crud_prefix/users") ? 'is-active' : '' }}" href="{{ route($crud_prefix.'.users.index') }}">Users</a></li>
                            <li><a class="{{ URL::has("$crud_prefix/roles") ? 'is-active' : '' }}" href="{{ route($crud_prefix.'.roles.index') }}">Roles</a></li>
                            <li><a class="{{ URL::has("$crud_prefix/permissions") ? 'is-active' : '' }}" href="{{ route($crud_prefix.'.permissions.index') }}">Permissions</a></li>
                            <li><a class="{{ URL::has("$crud_prefix/pages")  ? 'is-active' : '' }}" href="{{ route($crud_prefix.'.pages.index') }}">Pages</a></li>
                            <li>
                                <a class="{{ URL::has("$crud_prefix/menus") ? 'is-active' : '' }}" href="{{ route($crud_prefix.'.menus.index') }}">Menus</a>
                                <ul>
                                    @foreach (app('cache')->tags('sm')->get('menus') as $menu)
                                        <li data-id="menu-{{ $menu->id }}">
                                            <a class="{{ URL::is($crud_prefix.'.menus.edit',['id'=>$menu->id]) ? 'is-active' : '' }}" href="{{ route($crud_prefix.'.menus.edit',[$menu->id]) }}">{{ $menu->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                    </aside>
                </div>

                {{-- Pages --}}
                <div class="column is-10">
                    @yield('sub')
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    {{-- Scripts --}}
    <script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/tinymce.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/plugins/lists/plugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/plugins/link/plugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/plugins/image/plugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/plugins/fullscreen/plugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/plugins/media/plugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/plugins/table/plugin.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/plugins/preview/plugin.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/plugins/autoresize/plugin.min.js"></script>
    <script>
        // role & perm
        $('.select2').select2({
            placeholder: 'Select an option',
            allowClear: true
        })
    </script>
</body>
</html>
