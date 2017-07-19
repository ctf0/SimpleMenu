<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', '')">
    <title>@yield('title', '')</title>
    
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    
    <script src="//code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
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
        $('.select2').select2({
        placeholder: 'Select an option',
        allowClear: true,
        tag: true
        });
    </script>
</body>
</html>