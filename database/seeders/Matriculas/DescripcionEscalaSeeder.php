<?php

namespace Database\Seeders\Matriculas;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Matriculas\DescripcionEscala;

class DescripcionEscalaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DescripcionEscala::factory(10)->create();
    }
}
