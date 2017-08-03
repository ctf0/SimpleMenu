<ul>
    @foreach(SimpleMenu::AppLocales() as $code)
        <li>
            <a href="{{ SimpleMenu::getUrl($code) }}"
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