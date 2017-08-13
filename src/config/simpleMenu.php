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
     * where to search for the template views ? (relative to "resources\views" folder)
     */
    'templatePath' => 'pages',

    /*
     * the path where we will save the routes list
     */
    'routeListPath' => storage_path('logs/simpleMenu.php'),

    /*
     * where to redirect when a route is available in one locale "en" but not in another "fr" ?
     *
     * add
     * 'home' = '/' (or) 'error' = '404'
     */
    'unFoundLocalizedRoute' => 'home',

    /*
     * pages action controller namespace
     */
    'pagesControllerNS' => 'App\Http\Controllers',

    /*
     * the user model we are going to use for the admin page
     */
    'userModel' => App\User::class,

    /*
     * when adding a page which is a nest of a nother to a menu
     *
     * root
     *   | child 1
     *     | child 2 "add this along with its childrens to another menu"
     *       | child 3
     *
     * do you want to clear its parent and make it a root ?
     */
    'clearPartialyNestedParent'=> false,

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
     * when deleteing a page "from the db", do you also want to delete all of its childrens ?
     */
    'deletePageAndNests' => false,

    /*
     * package routes url & name prefix ex.
     * url = 'admin/pages'
     * name = 'admin.pages.*'
     */
    'crud_prefix' => 'admin',

    /*
     * css farmework for admin pages
     */
    'framework' => 'bulma',
];
