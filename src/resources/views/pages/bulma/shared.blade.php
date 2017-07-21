<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', '')">
    <title>@yield('title', '')</title>

    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

    {{-- jquery --}}
    <script src="//code.jquery.com/jquery-3.1.1.min.js"></script>
    {{-- select2 --}}
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    {{-- tinymce --}}
    <script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/tinymce.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/plugins/lists/plugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/plugins/link/plugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/plugins/image/plugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/plugins/spellchecker/plugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/plugins/fullscreen/plugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/plugins/media/plugin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/plugins/table/plugin.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/plugins/preview/plugin.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/plugins/contextmenu/plugin.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/plugins/autoresize/plugin.min.js"></script>
</head>

<body>
    <section id="app">
        {{-- Body --}}
        <div class="container">
            <div class="columns">
                {{-- Sidebar --}}
                <div class="column">
                    <aside class="menu">
                        <ul class="menu-list">
                            <li><a class="{{ URL::has('users') ? 'is-active' : '' }}" href="{{ route('admin.users.index') }}">Users</a></li>
                            <li><a class="{{ URL::has('roles') ? 'is-active' : '' }}" href="{{ route('admin.roles.index') }}">Roles</a></li>
                            <li><a class="{{ URL::has('permissions') ? 'is-active' : '' }}" href="{{ route('admin.permissions.index') }}">Permissions</a></li>
                            <li><a class="{{ URL::has('menus') ? 'is-active' : '' }}" href="{{ route('admin.menus.index') }}">Menus</a></li>
                            <li><a class="{{ URL::has('pages') ? 'is-active' : '' }}" href="{{ route('admin.pages.index') }}">Pages</a></li>
                        </ul>
                    </aside>
                </div>

                {{-- Pages --}}
                <div class="column">
                    @yield('sub')
                </div>
            </div>
        </div>
    </section>

    {{-- Scripts --}}
    <script>
        // role & perm
        $('.select2').select2({
            placeholder: 'Select an option',
            allowClear: true,
            tag: true
        });

        // body & desc
        tinymce.overrideDefaults({
            menubar: false,
            branding: false,
            height : "120",
            plugins: "lists link image spellchecker fullscreen media table preview contextmenu autoresize",
            toolbar: 'undo redo | link unlink | media image | styleselect removeformat | outdent indent | numlist bullist table | spellchecker preview fullscreen',
        });
    </script>
</body>
</html>
