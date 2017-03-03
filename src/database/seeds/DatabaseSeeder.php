<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Cache::flush();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        Role::create(['name' => 'guest']);

        Permission::create(['name' => 'access_backend']);
        Permission::create(['name' => 'guest']);

        User::first()->assignRole('admin');
        User::first()->givePermissionTo('access_backend');
    }
}
