<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $userModel = app(config('simpleMenu.models.user'));
        $pageModel = app(config('simpleMenu.models.page'));
        $roles     = ['guest', 'admin', 'user'];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        $perms = ['guest', 'access_backend'];
        foreach ($perms as $perm) {
            Permission::create(['name' => $perm]);
        }

        // foreach ($pageModel->all() as $page) {
        //     $page->givePermissionTo('guest');
        //     $page->assignRole('guest');
        // }

        $userModel->first()->assignRole('admin');
        $userModel->first()->givePermissionTo('access_backend');
    }
}
