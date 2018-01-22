<?php

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
     * dynamic pages controller namespace
     */
    'pagesControllerNS' => 'App\Http\Controllers',

    /*
     * where to redirect when a route is available in one locale "en" but not in another "fr" ?
     *
     * add
     * 'home' = '/' (or) 'error' = '404'
     */
    'unFoundLocalizedRoute' => 'home',

    /*
     * package models
     */
    'models'=> [
        'user' => App\User::class,
        'page' => \ctf0\SimpleMenu\Models\Page::class,
        'menu' => \ctf0\SimpleMenu\Models\Menu::class,
    ],

    /*
     * when adding a page which is a nest of another to a menu, ex.
     *
     * root
     *   | child 1
     *     | child 2 "add this along with its children to another menu"
     *       | child 3
     *
     * do you want to clear its parent and make it a root ?
     *
     * note that this will cause issues for breadcumb as to "what to show" and "what to hide"
     * if set to "false"
     */
    'clearPartialyNestedParent'=> true,

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
     * package routes url & route name prefix ex.
     * url = 'admin/pages'
     * route name = 'admin.pages.*'
     */
    'crud_prefix' => 'admin',

    /*
     * package controllers
     *
     * all the controllers are https://laravel.com/docs/5.5/controllers#resource-controllers
     * except admin which is using a single method "index" to return the admin page
     *
     * if you need to change anything, just create new controller
     * and extend from the below original
     * ex. "class ExampleController extends PagesController"
     *
     * for admin, the route name will be == "crud_prefix"
     */
    'controllers'=> [
        'admin'       => '\ctf0\SimpleMenu\Controllers\Admin\AdminController@index',
        'users'       => '\ctf0\SimpleMenu\Controllers\Admin\UsersController',
        'pages'       => '\ctf0\SimpleMenu\Controllers\Admin\PagesController',
        'roles'       => '\ctf0\SimpleMenu\Controllers\Admin\RolesController',
        'permissions' => '\ctf0\SimpleMenu\Controllers\Admin\PermissionsController',
        'menus'       => '\ctf0\SimpleMenu\Controllers\Admin\MenusController',
    ],

    /*
     * css farmework for admin pages
     * ex.
     * MIX_SM_FRAMEWORK=bulma
     */
    'framework'=> env('MIX_SM_FRAMEWORK'),
];
