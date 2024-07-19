<?php

namespace Database\Seeders;

use App\Models\Role\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
            ],

            [
                'name' => 'Admin',
                'slug' => 'admin',
            ],

            [
                'name' => 'Support User',
                'slug' => 'support-user',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $users = [
            [
                'first_name' => 'Mitch',
                'last_name' => 'Lusas',
                'email' => 'mitch@hiddenplanetproductions.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()->format('Y-m-d H:i:s'),
            ],

            [
                'first_name' => 'Nazar',
                'last_name' => null,
                'email' => 'nazar@hiddenplanetproductions.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()->format('Y-m-d H:i:s'),
            ],

            [
                'first_name' => 'Justin',
                'last_name' => null,
                'email' => 'jpsim039asva@gmail.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()->format('Y-m-d H:i:s'),
            ],

            [
                'first_name' => 'Victor',
                'last_name' => 'Mbachu',
                'email' => 'victor.c.mbachu@gmail.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now()->format('Y-m-d H:i:s'),
            ],
        ];

        foreach ($users as $user) {
            $created = User::create($user);
            if($created->email === 'mitch@hiddenplanetproductions.com'){
                $created->roles()->attach(1);
            } else {
                $created->roles()->attach(2);
            }
        }

    }
}
