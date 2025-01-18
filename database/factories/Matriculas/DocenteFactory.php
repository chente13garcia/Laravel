<?php

namespace Database\Factories\Matriculas;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Matriculas\TipoAporte>
 */
class TipoAporteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'primer_nombre' => $this->faker->word(),
            'segundo_nombre'  => $this->faker->word(),
            'primer_apellido'  => $this->faker->word(),
            'segundo_apellido'  => $this->faker->word(),
            'fecha_nacimiento'  => $this->faker->dateTime(),
            'sexo' => $this->faker->enum(),
            'estado' => $this->faker->boolean()
        ];
    }
}

