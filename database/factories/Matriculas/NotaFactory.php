<?php

namespace Database\Factories\Matriculas;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Matriculas\Nota>
 */
class NotaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'estudiante_id' => $this->faker->numberBetween(1,10),
            'asignatura_id' => $this->faker->numberBetween(1,10),
            'id_periodo' => $this->faker->numberBetween(1,10),
            'docente_id' => $this->faker->numberBetween(1,10),
            'escala_cualitativa' => $this->faker->word(),
            'escala_cuantitativa' => $this->faker->randomFloat(2, 0, 10),
            'escala_id' => $this->faker->numberBetween(1,10),
            'tipo_aporte_id' => $this->faker->numberBetween(1,10),
            'etapa' => $this->faker->numberBetween(1,10),
            'nota' => $this->faker->randomFloat(2, 0, 10),
            'tipo_nota_id' => $this->faker->numberBetween(1,10),
            'observaciones' => $this->faker->sentence()
        ];
    }
}