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
    <link href="//cdnjs.cloudflare.com/ajax/libs/choices.js/3.0.2/styles/css/choices.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bulma/0.6.1/css/bulma.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/SimpleMenu/style.css') }}"/>
</head>

<body>
    <section id="app" v-cloak>

        {{-- notif --}}
        <div class="notif-container">
            {{-- Status --}}
            @if (session('status'))
                <my-notification
                    title="Success"
                    body="{{ session('status') }}"
                    type="success"
                    duration="2">
                </my-notification>
            @endif

            <my-notification></my-notification>
        </div>

        {{-- Body --}}
        <div class="container">
            <div class="columns">
                {{-- Sidebar --}}
                <div class="column is-2">
                    <aside class="menu">
                        <ul class="menu-list">
                            <li><a class="{{ URL::has("$crud_prefix/users") ? 'is-active' : '' }}"
                                href="{{ route($crud_prefix.'.users.index') }}">
                                Users
                            </a></li>
                            <li><a class="{{ URL::has("$crud_prefix/roles") ? 'is-active' : '' }}"
                                href="{{ route($crud_prefix.'.roles.index') }}">
                                Roles
                            </a></li>
                            <li><a class="{{ URL::has("$crud_prefix/permissions") ? 'is-active' : '' }}"
                                href="{{ route($crud_prefix.'.permissions.index') }}">
                                Permissions
                            </a></li>
                            <li><a class="{{ URL::has("$crud_prefix/pages")  ? 'is-active' : '' }}"
                                href="{{ route($crud_prefix.'.pages.index') }}">
                                Pages
                            </a></li>
                            <li>
                                <a class="{{ URL::has("$crud_prefix/menus") ? 'is-active' : '' }}"
                                    href="{{ route($crud_prefix.'.menus.index') }}">
                                    Menus
                                </a>
                                <ul>
                                    @foreach (app('cache')->tags('sm')->get('menus') as $menu)
                                        <li data-id="menu-{{ $menu->id }}">
                                            <a class="{{ URL::is($crud_prefix.'.menus.edit', ['id' => $menu->id]) ? 'is-active' : '' }}"
                                                href="{{ route($crud_prefix.'.menus.edit', $menu->id) }}">
                                                {{ $menu->name }}
                                            </a>
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
    @include("SimpleMenu::admin.$css_fw.editors")
    {{-- app --}}
    <script src="{{ asset("path/to/app.js") }}"></script>
</body>
</html>
