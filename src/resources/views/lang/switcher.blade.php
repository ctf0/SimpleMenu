<ul>
    @foreach(array_keys(LaravelLocalization::getSupportedLocales()) as $code)
        <li>
            <a href="{{ $menu->getUrl(Route::currentRouteName(), $code) }}"
                rel="alternate"
                hreflang="{{ $code }}">
                {{ $code }}
            </a>
        </li>
        @if (!$loop->last)
            <li><span>/</span></li>
        @endif
    @endforeach
</ul>