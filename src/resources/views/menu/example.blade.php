<ul>
    @foreach ($PAGES as $page)

        @include('SimpleMenu::menu.partials.r_params')

        <li>
            <a href="{{ SimpleMenu::urlRoute() }}" class="{{ SimpleMenu::urlRouteCheck() ? 'is-active' : '' }}">{{ $page->title }}</a>

            @if (count($childs = $page->getImmediateDescendants()))
                <ul>
                    @include('SimpleMenu::menu.partials.nested', ['items' => $childs])
                </ul>
            @endif
        </li>
    @endforeach
</ul>
