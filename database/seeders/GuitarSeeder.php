<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guitar;

class GuitarSeeder extends Seeder
{
    public function run(): void
    {
        Guitar::factory()->count(9)->create(); // Or use static data
    }
}
