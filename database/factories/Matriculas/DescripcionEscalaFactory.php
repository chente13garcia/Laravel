<?php

namespace Database\Factories\Matriculas;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Matriculas\DescripcionEscala>
 */
class DescripcionEscalaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->word(),
            'descripcion' => $this->faker->sentence(),
            'equivalencia_id' => $this->faker->numberBetween(1,10)
        ];
    }
}

