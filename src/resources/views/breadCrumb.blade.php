<ul>
    @foreach ($items as $page)
        @include('SimpleMenu::menu.partials.r_params')

        <li class="{{ SimpleMenu::urlRouteCheck() ? 'is-active' : '' }}">
            <a href="{{ SimpleMenu::urlRoute() }}">
                {{ $page->title }}
            </a>
        </li>
        @if (!$loop->last)
            <li><span>/</span></li>
        @endif
    @endforeach
</ul>
<hr>
