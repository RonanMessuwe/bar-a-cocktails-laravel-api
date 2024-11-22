<?php

namespace Database\Seeders;

use App\Models\Drink;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DrinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drinks = [
            ['drink_id' => Str::ulid(), 'name' => 'Mojito'],
            ['drink_id' => Str::ulid(), 'name' => 'Margarita'],
            ['drink_id' => Str::ulid(), 'name' => 'Old Fashioned'],
            ['drink_id' => Str::ulid(), 'name' => 'Piña Colada'],
            ['drink_id' => Str::ulid(), 'name' => 'Martini'],
            ['drink_id' => Str::ulid(), 'name' => 'Cosmopolitan'],
            ['drink_id' => Str::ulid(), 'name' => 'Negroni'],
            ['drink_id' => Str::ulid(), 'name' => 'Daiquiri'],
            ['drink_id' => Str::ulid(), 'name' => 'Manhattan'],
            ['drink_id' => Str::ulid(), 'name' => 'Spritz'],
        ];
        DB::table('drinks')->insert($drinks);
        
    }
}
