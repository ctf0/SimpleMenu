@if (isset($PAGES))
    <ul>
        @foreach ($PAGES as $page)

            @include('SimpleMenu::menu.partials.r_params')

            <li>
                <a href="{{ SimpleMenu::routeUrl() }}" class="{{ SimpleMenu::isActiveRoute() ? 'is-active' : '' }}">{{ $page->title }}</a>

                @if ($childs = $page->nests)
                    @include('SimpleMenu::menu.partials.nested', ['items' => $childs])
                @endif
            </li>
        @endforeach
    </ul>
@endif
