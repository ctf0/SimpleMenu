<ul>
    @foreach ($items as $item)
        @php
            $routeName = $item->route_name;
            $route = $menu->getRoute($routeName);
        @endphp
        
        <li>
            <a href="{{ $route }}" class="{{ request()->url() == $route ? 'is-active' : '' }}">
                {{ $item->title }}
            </a>
        </li>
        @if (!$loop->last)
            <li><span>/</span></li>
        @endif
    @endforeach
</ul>
<hr>