@php
    SimpleMenu::getRoute($one->route_name);
@endphp

{{-- example --}}
{{-- @php
    SimpleMenu::getRoute($one->route_name, [
    'about'      => ['name'=> isset($var) ? $var : 'default'],
    'contact-us' => ['name'=>'other'],
    ])
@endphp --}}