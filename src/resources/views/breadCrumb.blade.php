<ul>
    @foreach ($items as $one)
        @include('SimpleMenu::menu.partials.r_params')
        
        <li class="{{ request()->url() == SimpleMenu::urlRoute() ? 'is-active' : '' }}">
            <a href="{{ SimpleMenu::urlRoute() }}">
                {{ $one->title }}
            </a>
        </li>
        @if (!$loop->last)
            <li><span>/</span></li>
        @endif
    @endforeach
</ul>
<hr>