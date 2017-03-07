@foreach ($items as $one)
    @php
        $routeName = $one->route_name;
        $route = $menu->getRoute($routeName);
    @endphp
    
    <li>
        <a href="{{ $route }}" class="{{ request()->url() == $route ? 'is-active' : '' }}">{{ $one->title }}</a>
        
        @if (count($childs = $one->getImmediateDescendants()))
            <ul>
                @include('menu._nested', ['items' => $childs, 'menuName' => $menuName])
            </ul>
        @endif
    </li>
@endforeach