## Installation

- install
    > - Permissions
    >   - https://github.com/spatie/laravel-permission

    > - MultiLocale
    >   - https://github.com/spatie/laravel-translatable
    >   - https://github.com/mcamara/laravel-localization

    > - Menu Nested Set
    >   - https://github.com/gazsp/baum#installation

- Kernel.php

```php
protected $routeMiddleware = [
    // ...
    'role' => \App\Http\Middleware\RoleMiddleware::class,
    'perm' => \App\Http\Middleware\PermissionMiddleware::class,
];
```

## Usage

- the package register a global variable **$menu** to use inside your views, which can be changed at `config('simpleMenu.viewVar')` and it gives you 3 methods
    1. `$menu->getUrl($crntRouteName, $langCode)` ***for language switcher.***
        ```blade
        {{ $menu->getUrl(Route::currentRouteName(), 'en') }}
        ```

    2. `$menu->getRoute($pageRouteName, $another = null, $params = null)` ***for resolving routes & params for the menu list, and you can use it in more than one way, ex.***
        ```php
        @php
            $routeName = $page->route_name;

            // same as "route($routeName)"
            $route = $menu->getRoute($routeName);

            // check if ($routeName = 'something-else') and return its "link" or "route($routeName)"
            $route = $menu->getRoute($routeName, 'something-else');

            // resolve a single route with params
            $route = $menu->getRoute($routeName, $another, ['key'=>'value']);

            // resolve multi routes with params
            $route = $menu->getRoute($routeName, [
                'about'      => ['name'=>'hello'],
                'contact-us' => ['name'=>'there'],
            ]);
        @endphp
        ```

    3. `$menu->render($pages, $menuClasses = null, $routeParams = null, $url = null)` ***for automatic menu list render.***
        ```php
        // the $menuClasses could either
        // be "null" for not including any classes
        // or "config" for using the ones under "config('simpleMenu.listClasses')"
        // or added manually as below
        {!! $menu->render (
            $PAGES,
            ['ul' => 'menu-list', 'li' => 'list-item', 'a' => 'is-active'],
            [
                'contact-us' => ['name'=>'test']
            ],
            request()->url()
        ) !!}
        ```

- use `bread-crump.example` to display the current page breadCrumps.
- use `menu.example` for manually formating the menu list the way you want.

- use `lang-switcher.example` to display the supported locales while resolving the route Params during redirection.

    ***note that for this to work properly, make sure the enabled locales are the same as the ones in the db.***
    ```php
    // config/laravel-localization
    [
        hideDefaultLocaleInURL => false, // to avoid getting an error when navigating back to the default locale.
        supportedLocales => [
            'en',
            'fr',
            'etc'
        ]
    ]

    // db/laravel-translatable
    Page::create([
        'title' => [
            'en'  => '...',
            'fr'  => '...',
            'etc' => '...'
        ],
        // ...
    ])
    ```

## Notes

- if `action` is added, the page `url & prefix` wont be slugged.
- `action` **default namespace** is `App\Http\Controllers`, so all your controllers should be available under that.
- route name should be equal to `$page->title` under whatever your `config('app.locale')` value is.
- empty url could be replaced by slugged title by changing the config under `config('SimpleMenu.useTitleForUrl')` to *true*.

# ToDo

* [ ] Views for editing.
* [ ] Add the ability to work with `hideDefaultLocaleInURL => true`.
* [ ] Turn into Package.
