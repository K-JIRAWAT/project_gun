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
            ['name' => 'เจ้าหน้าที่สรรพาวุธ', 'created_at' => now()],
            ['name' => 'ผู้บังคับบัญชา', 'created_at' => now()],
            ['name' => 'ข้าราชการพัน.อย.', 'created_at' => now()],
        ];
        DB::table('roles')->insert($item);
    }
}
