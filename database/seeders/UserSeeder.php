<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['id' => 1, 'name' => 'superuser'],
            ['id' => 2, 'name' => 'staff'],
            ['id' => 3, 'name' => 'siteadmin'],
            ['id' => 4, 'name' => 'sitestaff'],
        ];
        Role::insert($roles);

        $admin = [
            'firstname' => 'Admin',
            'lastname' => 'Person',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];

        $colchesterUser = [
            'firstname' => 'Penny',
            'lastname' => 'Colchester',
            'email' => 'penny@colchester.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];

        User::insert($admin);

        User::insert($colchesterUser);

        User::factory()
            ->count(20)
            ->create();

        $roles = Role::all();

        User::all()->each(function ($user) use ($roles) {
            $user->roles()->attach(
                $roles->random(rand(1, 2))->pluck('id')->toArray()
            );
        });
    }
}
