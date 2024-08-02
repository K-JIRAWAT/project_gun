<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $item =[
            ['name' => 'SuperAdmin', 'created_at' => now()],
            ['name' => 'เจ้าหน้าที่ตรวจการ', 'created_at' => now()],
            ['name' => 'เจ้าหน้าที่', 'created_at' => now()],
        ];
        DB::table('roles')->insert($item);
    }
}
