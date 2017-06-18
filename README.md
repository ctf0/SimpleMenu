- Package Uses
    > - Permissions
    >   - https://github.com/spatie/laravel-permission

    > - MultiLocale
    >   - https://github.com/spatie/laravel-translatable
    >   - https://github.com/mcamara/laravel-localization

    > - Menu Nested Set
    >   - https://github.com/gazsp/baum#installation

## Installation

- add the service provider to `config/app.php`
```php
'providers' => [
    ctf0\SimpleMenu\SimpleMenuServiceProvider::class,
]
```

- publish the package assets with `php artisan vendor:publish`

    * models (`app\Menu` & `app\Page`)
    * migrations (`pages`, `menus`, `menu_page`, `page_permissions`)
    * seeds (for testing)
    * views
        + `SimpleMenu::breadCrump.example` to display the current page breadCrumps.
        + `SimpleMenu::menu.example` for manually formating the menu list the way you want.
            + internally we get all the available ***menuNames*** and it corresponding pages then we check if there is a view file equal to the menu name ex.`menu.hero` & if not we use `menu.example` instead, in the view file you will get 2 variables

                + PAGES : all the menu pages
                + menuName : to make sure each menu item have its correct descendants.

        + `SimpleMenu::langSwitcher.example` to display the supported locales while resolving the route Params during redirection.
    * config (`simpleMenu.php`)

        ```php
        return [
            /* the menu list classes to be used for "render()" */
            'listClasses' => [
                'ul' => 'menu-list',
                'li' => 'list-item',
                'a'  => 'is-active',
            ],

            /* the global variable to be used across views */
            'viewVar' => 'menu',

            /* the path where we will save the route list for multiLocal route resolving */
            'routeListPath' => storage_path('logs/simpleMenu.php'),

            /* if url is empty "/" should we use a slugged title instead "home" ? */
            'useTitleForUrl' => false,
        ];
        ```

### Usage
- ***for language switcher.*** `$menu->getUrl($crntRouteName, $langCode)`
    ```blade
    {{ $menu->getUrl(Route::currentRouteName(), 'en') }}
    ```

- ***for resolving routes & params for the menu list.*** `$menu->getRoute($pageRouteName, $another = null, $params = null)`,
and you can use it in more than one way, ex.
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
    // the $menuClasses could be
    // "null" for not including any classes
    // "config" for using the ones under "simpleMenu.listClasses"
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

### MiddleWares
- the package automatically register 2 middlewares `role & perm` to handle all the routes, however to use them on any other routes, use
```php
Route::group(['middleware' => ['role:admin','perm:access_backend']], function () {
    // ...
});
```

## Notes

- for everything to work properly, make sure the enabled locales are the same as the ones in the db.
    ```php
    // config/laravel-localization
    // https://github.com/mcamara/laravel-localization
    [
        supportedLocales => [
            'en',
            'fr',
            'etc'
        ]
    ]

    // db
    // https://github.com/spatie/laravel-translatable
    Page::create([
        'title' => [
            'en'  => '...',
            'fr'  => '...',
            'etc' => '...'
        ],
        // ...
    ])
    ```

- if `action` is added, the page `url & prefix` wont be slugged.
- `action` **default namespace** is `App\Http\Controllers`, so all your controllers should be available under that.

# ToDo

* [ ] CRUD Views for (roles/pers/pages/menus).
