@if ($breadCrumb = SimpleMenu::getBC())
    <nav class="breadcrumb">
        <ul>
            @foreach ($breadCrumb as $page)
                @break(!$page->title)
                @include('SimpleMenu::menu.partials.r_params')

                <li class="{{ SimpleMenu::isActiveRoute() ? 'is-active' : '' }}">
                    <a href="{{ SimpleMenu::routeUrl() }}">
                        {{ $page->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
    <hr>
@endif
