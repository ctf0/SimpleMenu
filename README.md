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

## Configuration

```php
return [
    /*
     * the menu list classes to be used for "render()"
     */
    'listClasses' => [
        'ul' => 'menu-list',
        'li' => 'list-item',
        'a'  => 'is-active',
    ],

    /*
     * the global variable to be used across views
     */
    'viewVar' => 'menu',

    /*
     * the path where we will save the route list for multiLocal route resolving
     */
    'routeListPath' => storage_path('logs/simpleMenu.php'),

    /*
     * if url is empty should we use a slugged title instead ?
     */
    'useTitleForUrl' => false,
];
```

## Usage
- ***for language switcher.*** `$menu->getUrl($crntRouteName, $langCode)`
    ```blade
    {{ $menu->getUrl(Route::currentRouteName(), 'en') }}
    ```

- ***for resolving routes & params for the menu list.*** `$menu->getRoute($pageRouteName, $another = null, $params = null)`

    - and you can use it in more than one way, ex.
        ```php
        @php
            // same as "route($page->route_name)"
            $route = $menu->getRoute($page->route_name);

            // check if ($page->route_name = 'something-else') and return its "link" or "route($page->route_name)"
            $route = $menu->getRoute($page->route_name, 'something-else');

            // resolve a single route with params
            $route = $menu->getRoute($page->route_name, $another, ['key'=>'value']);

            // resolve multi routes with params
            $route = $menu->getRoute($page->route_name, [
                'about'      => ['name'=>'hello'],
                'contact-us' => ['name'=>'there'],
            ]);
        @endphp
        ```

- ***for automatic menu list render.*** `$menu->render($pages, $menuClasses = null, $routeParams = null, $url = null)`

    ```php
    // the $menuClasses could either
    // be "null" for not including any classes
    // or "config" for using the ones under "simpleMenu.listClasses"
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

<br>

- use `bread-crump.example` to display the current page breadCrumps.
- use `menu.example` for manually formating the menu list the way you want.
    - internally we get all the available menuNames and it corresponding pages then we check if there is a view file equal to the menu name ex.`menu.hero` & if not we use `menu.example` instead, in the view file you will get 2 variables

        - PAGES : all the menu pages
        - menuName : to make sure each menu item have its correct descendants.

- use `lang-switcher.example` to display the supported locales while resolving the route Params during redirection.

    - note that for this to work properly, make sure the enabled locales are the same as the ones in the db.
        ```php
        // config/laravel-localization
        [
            supportedLocales => [
                'en',
                'fr',
                'etc'
            ]
        ]

        // db
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

- check the included db/(migrations & seeds) for more details.
- if `action` is added, the page `url & prefix` wont be slugged.
- `action` **default namespace** is `App\Http\Controllers`, so all your controllers should be available under that.
- <s>route name should be equal to `$page->title` under whatever your `config('app.locale')` value is</s>.
- empty url could be replaced with ***slugged title*** by using `simpleMenu.useTitleForUrl => true`.

# ToDo

* [ ] Views for editing.
* [ ] Turn into Package.
