@foreach ($items as $one)
    @include('SimpleMenu::partials.r_params')
    
    <li>
        <a href="{{ SimpleMenu::urlRoute() }}" class="{{ request()->url() == SimpleMenu::urlRoute() ? 'is-active' : '' }}">{{ $one->title }}</a>
        
        @if (count($childs = $one->getImmediateDescendants()))
            <ul>
                @include('SimpleMenu::partials.nested', ['items' => $childs])
            </ul>
        @endif
    </li>
@endforeach