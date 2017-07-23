- Dependencies
    > - Permissions
    >   - https://github.com/spatie/laravel-permission

    > - MultiLocale
    >   - https://github.com/spatie/laravel-translatable
    >   - https://github.com/mcamara/laravel-localization

    > - Menu Nested Set
    >   - https://github.com/gazsp/baum

- Javascript
    + [Vue](https://vuejs.org/)
    + [jQuery](https://jquery.com/)
    + [vue-sortable](https://github.com/sagalbot/vue-sortable/pull/17#issuecomment-260562645)

## Installation

- `composer require ctf0/simple-menu`

- add the service provider & facade to `config/app.php`

    - all the package dependencies "serviceProvider & aliases" are registerd with the package.

```php
'providers' => [
    ctf0\SimpleMenu\SimpleMenuServiceProvider::class,
]

'aliases' => [
    'SimpleMenu' => ctf0\SimpleMenu\Facade\SimpleMenu::class,
]
```

- publish the packages assets with `php artisan vendor:publish`
    - for simpleMenu [Wiki](https://github.com/ctf0/simple-menu/wiki/Publish)
    - also check the **Dependencies** packages pages for "config/options/migrations".

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
     * where to search for the template views relative to "resources\views" folder
     */
    'templatePath' => 'pages',

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

    /*
     * css farmework
     */
    'framework'=> 'bulma',
];
```

## Usage
[Wiki](https://github.com/ctf0/simple-menu/wiki/Usage)

### MiddleWares
- the package automatically register 4 middlewares to handle all the routes but incase you want to use them anywhere else, they are
    - `localizationRedirect`
    - `localeSessionRedirect`
    - `role:roleName`
    - `perm:permName`

### Crud Views
[Wiki](https://github.com/ctf0/SimpleMenu/wiki/Crud-Views)

# ToDo

* [ ] Menu Pages Hierarchy "nesting" Creation.
