<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create data user
        $userCreate = User::create([
            'name'      => 'Superadmin',
            'email'     => 'admin@gmail.com',
            'password'  => bcrypt('password')
        ]);

        //assign permission to role
        $role = Role::find(1);
        $permissions = Permission::all();

        $role->syncPermissions($permissions);

        //assign role with permission to user
        $user = User::find(1);
        $user->assignRole($role->name);

        //create data user
        $userCreate = User::create([
            'name'      => 'Monkey D. Luffy',
            'email'     => 'monkey@gmail.com',
            'password'  => bcrypt('password')
        ]);

        //assign permission to role
        $role = Role::find(2);
        $permissions = Permission::all();

        $role->syncPermissions($permissions);

        //assign role with permission to user
        $user = User::find(2);
        $user->assignRole($role->name);
    }
}
