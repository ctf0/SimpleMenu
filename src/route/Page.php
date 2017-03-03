<?php

Route::get('/', function () {
    return redirect()->action('PageController@home');
});

(new App\Http\Controllers\PageController())->createRoutes();
