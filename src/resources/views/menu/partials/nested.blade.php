@foreach ($items as $one)
    @include('SimpleMenu::menu.partials.r_params')
    
    <li>
        <a href="{{ SimpleMenu::urlRoute() }}" class="{{ request()->url() == SimpleMenu::urlRoute() ? 'is-active' : '' }}">{{ $one->title }}</a>
        
        @if (count($childs = $one->getImmediateDescendants()))
            <ul>
                @include('SimpleMenu::menu.partials.nested', ['items' => $childs])
            </ul>
        @endif
    </li>
@endforeach