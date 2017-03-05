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
- route name is equal to `$page->title` under `defaultLocale`
- atm we can either pass route params through the `lang switcher` or `the menu items` but not both as it will make code messy.

# ToDo

* [ ] Views for editing.
* [ ] Turn into Package.
