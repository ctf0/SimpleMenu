@php
    SimpleMenu::getRoute($page->route_name);
@endphp

{{-- example --}}
{{-- @php
    SimpleMenu::getRoute($page->route_name, [
    'about'      => ['name'=> isset($var) ? $var : 'default'],
    'contact-us' => ['name'=>'other'],
    ])
@endphp --}}
