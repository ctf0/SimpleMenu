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
     * by default when removing a nested page, all of its 'Descendants' gets cleared ex.
     *
     * root
     *   | child 1
     *     | child 2 "delete" (now "child 2" & "child 3" wont be a descendants of any page)
     *       | child 3
     *
     * but what about when removing the root, do you also want the same behavior ?
     */
    'clearRootDescendants' => false,

    /*
     * crud views url prefix ex.'admin/pages'
     * this is also the same name for the route name ex.'admin.pages.*'
     */
    'crud_prefix' => 'admin',

    /*
     * css farmework for admin pages
     */
    'framework' => 'bulma',
];
