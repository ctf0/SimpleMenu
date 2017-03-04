@foreach ($items as $one)
    @php
        $routeName = slugfy($one->getTranslation('title', $menu->defLocale));
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

        @php
            $items = $menu->getChilds($menuName,$one->id);
        @endphp

        @if (count($items))
            <ul>
                @include('menu._nested', ['items' => $items, 'menuName' => $menuName])
            </ul>
        @endif
    </li>
@endforeach
