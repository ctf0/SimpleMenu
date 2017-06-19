<?php

return [
    /*
     * the name of the main route '/',
     * make sure you have a row in ur db with a `route_name = the below`
     */
    'mainRouteName'=> 'home',

    /*
     * the menu list classes to be used for "render()"
     */
    'listClasses' => [
        'ul' => 'menu-list',
        'li' => 'list-item',
        'a'  => 'is-active',
    ],

    /*
     * the var name to share it across views
     */
    'viewVar' => 'menu',

    /*
     * the path where we will save the route list for multiLocal route resolving
     */
    'routeListPath' => storage_path('logs/simpleMenu.php'),

    /*
     * if url is empty (ex.'/') should we use a slugged title instead ?
     */
    'useTitleForUrl' => false,

    /*
     * what happens when a route is available in one locale "en" but not in another "fr"
     * add either 'home' or 'error'
     */
    'unFoundLocalizedRoute' => 'error',

    /*
     * pages controller namespace
     */
    'pagesControllerNS'=> 'App\Http\Controllers',
];
