# SimpleMenu

[![Latest Stable Version](https://img.shields.io/packagist/v/ctf0/simple-menu.svg)](https://packagist.org/packages/ctf0/simple-menu) [![Total Downloads](https://img.shields.io/packagist/dt/ctf0/simple-menu.svg)](https://packagist.org/packages/ctf0/simple-menu)

Create menus & pages that support (multiLocale "title, url, body, ...", nesting, template, static & dynamic data, roles & permissions).

- package requires Laravel v5.5+
- package rely heavily on caching  so make sure to install one of the tag enabled drivers [Memcached / Redis](https://laravel.com/docs/5.5/cache)

<br>

## Installation

- `composer require ctf0/simple-menu`

- after installation, package will auto-add
    + package routes to `routes/web.php`
    + package assets compiling to `webpack.mix.js`

- publish the packages assets with `php artisan vendor:publish`
    - [simpleMenu](https://github.com/ctf0/simple-menu/wiki/Publish)
    - [laravel-permission](https://github.com/spatie/laravel-permission#laravel)
    - [laravel-translatable](https://github.com/spatie/laravel-translatable#installation)
    - [laravel-localization](https://github.com/mcamara/laravel-localization#config)

- install JS dependencies

    ```bash
    yarn add vue axios vue-tippy@v1 vuedraggable vue-notif vue-multi-ref vue-awesome@v2 list.js
    # or
    npm install vue axios vue-tippy@v1 vuedraggable vue-notif vue-multi-ref vue-awesome@v2 list.js --save
    ```

- add this one liner to your main js file and run `npm run watch` to compile your `js/css` files.
    + if you are having issues [Check](https://ctf0.wordpress.com/2017/09/12/laravel-mix-es6/).

    ```js
    // app.js

    window.Vue = require('vue')

    require('../vendor/SimpleMenu/js/manager')

    new Vue({
        el: '#app'
    })
    ```

<br>

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
     * the path where we will save the routes list
     */
    'routeListPath' => storage_path('logs/simpleMenu.php'),

    /*
     * where to redirect when a route is available in one locale "en" but not in another "fr" ?
     */
    'unFoundLocalizedRoute' => 'root',

    /*
     * package models
     */
    'models'=> [
        'user' => App\User::class,
        'page' => \ctf0\SimpleMenu\Models\Page::class,
        'menu' => \ctf0\SimpleMenu\Models\Menu::class,
    ],

    /*
     * when adding a page which is a nest of a nother to a menu, ex.
     *
     * root
     *   | child 1
     *     | child 2 "add this along with its childrens to another menu"
     *       | child 3
     *
     * do you want to clear its parent and make it a root ?
     */
    'clearPartialyNestedParent' => true,

    /*
     * when removing a root page from a menu, ex.
     *
     * root "remove"
     *   | child 1
     *     | child 2
     *       | child 3
     *
     * do you want clear all of its 'Descendants' ?
     */
    'clearRootDescendants' => false,

    /*
     * when removing a nest from a list, ex.
     *
     * root
     *   | child 1
     *     | child 2 "remove"
     *       | child 3
     *
     * do you want to reset its hierarchy ?
     */
    'clearNestDescendants'=> false,

    /*
     * when deleting a page "from the db", ex.
     *
     * page "delete/destroy"
     *   | nested child 1
     *     | nested child 2
     *       | nested child 3
     *
     * do you also want to delete all of its children ?
     */
    'deletePageAndNests' => false,

    /*
     * package routes url & route name prefix
     */
    'crud_prefix' => 'admin',

    /*
     * all the package controllers
     *
     * if you need to change anything, just create new controller
     * and extend from the below original
     * ex. "class ExampleController extends PagesController"
     */
    'controllers'=> [
        'admin'       => '\ctf0\SimpleMenu\Controllers\Admin\AdminController@index',
        'users'       => '\ctf0\SimpleMenu\Controllers\Admin\UsersController',
        'pages'       => '\ctf0\SimpleMenu\Controllers\Admin\PagesController',
        'roles'       => '\ctf0\SimpleMenu\Controllers\Admin\RolesController',
        'permissions' => '\ctf0\SimpleMenu\Controllers\Admin\PermissionsController',
        'menus'       => '\ctf0\SimpleMenu\Controllers\Admin\MenusController',
    ],
];
```

<br>

## Usage
> [Demo](https://github.com/ctf0/demos/tree/simple-menu)<br>
> [Usage](https://github.com/ctf0/simple-menu/wiki/Usage)<br>
> [Views](https://github.com/ctf0/SimpleMenu/wiki/Crud-Views)

- add `SMUsers` trait to your **User Model**

    ```php
    use ctf0\SimpleMenu\Models\Traits\SMUsers;

    // ...

    class User extends Authenticatable
    {
        use Notifiable, SMUsers;
    }
    ```

- visit `localhost:8000/admin`
