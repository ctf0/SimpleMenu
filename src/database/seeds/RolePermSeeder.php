<?php

use Illuminate\Database\Seeder;

class RolePermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $userModel       = app(config('simpleMenu.models.user'));
        $pageModel       = app(config('simpleMenu.models.page'));
        $roleModel       = app(config('permission.models.role'));
        $permissionModel = app(config('permission.models.permission'));

        $roles = ['guest', 'admin', 'user'];
        $perms = ['guest', 'access_backend'];

        foreach ($roles as $role) {
            $roleModel->create(['name' => $role]);
        }

        foreach ($perms as $perm) {
            $permissionModel->create(['name' => $perm]);
        }

        // foreach ($pageModel->all() as $page) {
        //     $page->givePermissionTo('guest');
        //     $page->assignRole('guest');
        // }

        $userModel->first()->assignRole('admin');
        $userModel->first()->givePermissionTo('access_backend');
    }
}
