<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $item =[
            [
                'role_id' => 1,
                'firstname' => 'Phongthep',
                'lastname' => 'Kanoksing',
                'username' => 'admin',
                'images' => null,
                'sector' => 1,
                'status' => 1,
                'email' => 'Phongthep17@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin'),
                'created_by' => 'system',
                'created_at' => now(),
                'updated_by' => 'system',
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'firstname' => 'Test',
                'lastname' => 'User2',
                'username' => 'Test2',
                'images' => null,
                'sector' => 1,
                'status' => 1,
                'email' => 'testuser2@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('test2'),
                'created_by' => 'system',
                'created_at' => now(),
                'updated_by' => 'system',
                'updated_at' => now(),
            ],
            [
                'role_id' => 3,
                'firstname' => 'Test',
                'lastname' => 'User1',
                'username' => 'Test1',
                'images' => null,
                'sector' => 1,
                'status' => 1,
                'email' => 'testuser1@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('test1'),
                'created_by' => 'system',
                'created_at' => now(),
                'updated_by' => 'system',
                'updated_at' => now(),
            ]
        ];
        DB::table('users')->insert($item);
    }
}
