# SimpleMenu
create a menu with nested items that support assigning (template,position,dynamic,role/perm) to pages

1 - install
- https://github.com/spatie/laravel-translatable
- https://github.com/spatie/laravel-permission

2 - Kernel.php

```php
protected $routeMiddleware = [
    // ...
    'role'                  => \App\Http\Middleware\RoleMiddleware::class,
    'perm'                  => \App\Http\Middleware\PermissionMiddleware::class,
];
```

3 - edit `route.Page` for `/` redirection.

4 - include `menu.main` in anywhere to display the menu.
