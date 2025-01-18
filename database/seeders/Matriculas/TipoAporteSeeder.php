<?php

namespace Database\Seeders\Matriculas;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Matriculas\TipoAporte;

class TipoAporteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoAporte::factory(10)->create();
    }
}
