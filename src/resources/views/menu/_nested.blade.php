@foreach ($items as $one)
    @php
        $routeName = $one->route_name;
        $route = route($routeName);

        // for nested items as well
        switch ($routeName) {
            case 'abc':
                $route = route($routeName, ['name'=>'test']);
            break;
            default:
                $route = route($routeName);
            break;
        }
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
