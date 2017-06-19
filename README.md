- Package Uses
    > - Permissions
    >   - https://github.com/spatie/laravel-permission

    > - MultiLocale
    >   - https://github.com/spatie/laravel-translatable
    >   - https://github.com/mcamara/laravel-localization

    > - Menu Nested Set
    >   - https://github.com/gazsp/baum#installation

## Installation

- `composer require ctf0/simple-menu`

- add the service provider to `config/app.php`
```php
'providers' => [
    ctf0\SimpleMenu\SimpleMenuServiceProvider::class,
]
```

- publish the package assets with `php artisan vendor:publish` [Wiki](https://github.com/ctf0/simple-menu/wiki/Config)

### Usage
[Wiki](https://github.com/ctf0/simple-menu/wiki/Usage)

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
