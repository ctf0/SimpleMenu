@if (isset($breadCrumb) && SimpleMenu::checkForBC($breadCrumb))
    <nav class="breadcrumb">
        <ul>
            @foreach ($breadCrumb as $page)
                @include('SimpleMenu::menu.partials.r_params')

                <li class="{{ SimpleMenu::urlRouteCheck() ? 'is-active' : '' }}">
                    <a href="{{ SimpleMenu::urlRoute() }}">
                        {{ $page->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
    <hr>
@endif