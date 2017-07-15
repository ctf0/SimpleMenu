<ul>
    @foreach ($items as $one)
        @include('SimpleMenu::partials.r_params')
        
        <li>
            <a href="{{ SimpleMenu::urlRoute() }}" class="{{ request()->url() == SimpleMenu::urlRoute() ? 'is-active' : '' }}">
                {{ $one->title }}
            </a>
        </li>
        @if (!$loop->last)
            <li><span>/</span></li>
        @endif
    @endforeach
</ul>
<hr>