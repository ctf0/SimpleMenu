- Package Uses
    > - Permissions
    >   - https://github.com/spatie/laravel-permission

    > - MultiLocale
    >   - https://github.com/spatie/laravel-translatable
    >   - https://github.com/mcamara/laravel-localization

    > - Menu Nested Set
    >   - https://github.com/gazsp/baum

## Installation

- `composer require ctf0/simple-menu`

- add the service provider & facade to `config/app.php`
```php
'providers' => [
    ctf0\SimpleMenu\SimpleMenuServiceProvider::class,
]

'aliases' => [
    'SimpleMenu' => ctf0\SimpleMenu\Facade\SimpleMenu::class,
]
```

- publish the package assets with `php artisan vendor:publish` [Wiki](https://github.com/ctf0/simple-menu/wiki/Publish)

## Config
**config/simpleMenu.php**
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
     * the path where we will save the route list for multiLocal route resolving
     */
    'routeListPath' => storage_path('logs/simpleMenu.php'),

    /*
     * what happens when a route is available in one locale "en" but not in another "fr", add either
     * 'home' = '/' or
     * 'error' = '404'
     */
    'unFoundLocalizedRoute' => 'home',

    /*
     * pages controller namespace
     */
    'pagesControllerNS'=> 'App\Http\Controllers',
];
```

## Usage
[Wiki](https://github.com/ctf0/simple-menu/wiki/Usage)

### MiddleWares
- the package automatically register 2 middlewares `role & perm` to handle all the routes, however to use them on any other routes, use
    ```php
    Route::group(['middleware' => ['role:admin','perm:access_backend']], function () {
        // ...
    });
    ```

# ToDo

* [ ] CRUD Views for (roles/perms/pages/menus).
