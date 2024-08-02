<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $item =[
            ['name' => 'ปืนยาว', 'created_at' => now()],
            ['name' => 'ปืนสั่น', 'created_at' => now()],
            ['name' => 'ปืนพก', 'created_at' => now()],
            ['name' => 'ซองบรรจุกระสุน', 'created_at' => now()],
            ['name' => 'มีด', 'created_at' => now()],
        ];
        DB::table('types')->insert($item);
    }
}
