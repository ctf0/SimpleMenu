<ul class="menu-list">
    @foreach ($PAGES as $one)
        @php
            $title = $one->title;
            $route = route(slugfy($one->getTranslation('title', 'en')));
        @endphp
        
        <li>
            <a href="{{ $route }}" class="{{ request()->url() == $route ? 'is-active' : '' }}">{{ $title }}</a>
            
            @php
                $items = $menu->getChilds($menuName,$one->id);
            @endphp
            
            @if (count($items))
                <ul>
                    @include('menu._nested', ['items' => $items, 'menuName'=>$menuName])
                </ul>
            @endif
        </li>
    @endforeach
</ul>