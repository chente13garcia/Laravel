<?php

namespace Database\Factories\Matriculas;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Matriculas\Escala>
 */
class EscalaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'escala_cuantitativa' => $this->faker->numberBetween(1,10),
            'escala_cualitativa' => $this->faker->text(),
            'descripcion' => $this->faker->text(),
            'periodo_id' => $this->faker->numberBetween(1,10),
            'descripcion_escala_id' => $this->faker->numberBetween(1,10)
        ];
    }
}
