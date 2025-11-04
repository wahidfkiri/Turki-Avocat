<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create Slim Turki role if it doesn't exist
        $role = Role::firstOrCreate(['name' => 'admin']);

        // Create the admin user
        $user = User::firstOrCreate(
            ['email' => 'slim.turki@turkiavocats.com'],
            [
                'name' => 'Slim Turki',
                'password' => Hash::make('12345678'),
            ]
        );

        // Assign Admin role
        $user->assignRole($role);

        // Give all permissions to Admin
        $permissions = Permission::all();
        $user->givePermissionTo($permissions);
    }
}
