# SimpleMenu
create a menu with nested items that support (multiLocal "title, url, prefix", template, static/dynamic, roles & permissions) pages

1 - install
  > - Permissions
  >
  - https://github.com/spatie/laravel-permission

  > - MultiLocale
  >
  - https://github.com/spatie/laravel-translatable
  - https://github.com/mcamara/laravel-localization

  > - Menu Nested Set
  >
  - https://github.com/gazsp/baum#installation

2 - Kernel.php

```php
protected $routeMiddleware = [
    // ...
    'role' => \App\Http\Middleware\RoleMiddleware::class,
    'perm' => \App\Http\Middleware\PermissionMiddleware::class,
];
```

3 - include `menu.main` anywhere to display the menu.

4 - include `lang.switcher` anywhere to switch locale and redirect to the same page.
- for this to work properly, make sure the enabled locales are the same as the ones in the db.

<table>
<tr>
<th>config/laravel-localization</th>
<th>db/laravel-translatable</th>
</tr>
<tr>
<td>
<pre>
[
  'en',
  'fr',
  'etc'
]
</pre>
</td>
<td>
<pre>
Page::create([
  'title' => [
    'en'  => '...',
    'fr'  => '...',
    'etc' => '...'
  ]
])
</pre>
</td>
</tr>
</table>

## Notes
- if `url` is empty, it will be a slug of title.
- if `action` is added, this page `url & prefix` wont be slugged.
- `action` **default namespace** is `App\Http\Controllers`, so all your controllers should be available under that.
- route name is equal to `$page->title` under **en** or whatever ur `config('app.locale')` value is.
- to check & register route params, in the menu view you can use

```php
$routeName = $item->route_name;

// same as "route($routeName)"
$route = $menu->getRoute($routeName);

// check if ($routeName = 'something') and return its "link" or "route($routeName)"
$route = $menu->getRoute($routeName, $another);

// you can also resolve route params while checking
$route = $menu->getRoute($routeName, $another, ['key'=>'value']);

// if you have more than one route in the same view, you can pass an array instead
$route = $menu->getRoute($routeName, [
  'about'      => ['name'=>'hello'],
  'contact-us' => ['name'=>'there'],
]);
```

# ToDo

* [ ] Views for editing.
* [ ] Turn into Package.
