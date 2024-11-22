<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 1; $i <= 10; $i++) {
            DB::table('tables')->insert([
                'table_id' => Str::ulid(),
                'name' => "Table $i",
                'code' => "T$i"
            ]);
        }
    }
}
