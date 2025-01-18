<?php

namespace Database\Factories\Matriculas;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Matriculas\ActivacionNota>
 */
class ActivacionNotaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fecha_inicio' => $this->faker->dateTime(),
            'fecha_fin' => $this->faker->dateTime(),
            'tipo_nota_id' => $this->faker->numberBetween(1, 10)
        ];
    }
}