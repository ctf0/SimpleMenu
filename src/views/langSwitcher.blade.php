<ul>
    @foreach(array_keys(LaravelLocalization::getSupportedLocales()) as $code)
        <li>
            <a href="{{ SimpleMenu::getUrl(Route::currentRouteName(), $code) }}"
                class="{{ LaravelLocalization::getCurrentLocale() == $code ? 'is-active' : '' }}"
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