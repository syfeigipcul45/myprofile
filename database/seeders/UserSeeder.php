<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = Role::create(['name' => 'superadmin']);
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        $roleSuperadmin = User::create([
            'name' => 'Syafei Karim',
            'username' => 'syfeikarim',
            'email' => 'syfei.karim@'. env('APP_DOMAIN', 'gmail.com'),
            'password' => bcrypt('gipcul45')
        ]);
        $roleSuperadmin->syncRoles([$superadmin]);

        $roleAdmin = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@'. env('APP_DOMAIN', 'test.com'),
            'password' => bcrypt('@admin')
        ]);
        $roleAdmin->syncRoles([$admin]);

        $roleUser = User::create([
            'name' => 'User',
            'username' => 'user',
            'email' => 'user@'. env('APP_DOMAIN', 'test.com'),
            'password' => bcrypt('@user')
        ]);
        $roleUser->syncRoles([$user]);
    }
}
