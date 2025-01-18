<?php

namespace Database\Seeders\Matriculas;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Matriculas\ActivacionNota;

class ActivacionNotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ActivacionNota::factory(10)->create();
    }
}