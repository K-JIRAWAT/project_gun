<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $item =[
            ['name' => 'ร้อย.ทย.พัน.อย.บน.23', 'created_at' => now()],
            ['name' => 'ร้อย.ตอ.พัน.อย.บน.23', 'created_at' => now()],
            ['name' => 'ร้อย.รก.พัน.อย.บน.23', 'created_at' => now()],
            ['name' => 'ร้อย.สนน.พัน.อย.บน.23', 'created_at' => now()],
        ];
        DB::table('sectors')->insert($item);
    }
}
