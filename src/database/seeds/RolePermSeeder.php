<?php

use App\User;
use ctf0\SimpleMenu\Models\Page;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $roles = ['guest', 'admin', 'user'];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        $perms = ['guest', 'access_backend'];
        foreach ($perms as $perm) {
            Permission::create(['name' => $perm]);
        }

        $pages = Page::all();
        foreach ($pages as $page) {
            $page->givePermissionTo('guest');
            $page->assignRole('guest');
        }

        User::first()->assignRole('admin');
        User::first()->givePermissionTo('access_backend');
    }
}
