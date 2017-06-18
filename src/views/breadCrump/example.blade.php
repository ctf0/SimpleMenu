<ul>
    @foreach ($items as $item)
        @php
            $route = $menu->getRoute($item->route_name);
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