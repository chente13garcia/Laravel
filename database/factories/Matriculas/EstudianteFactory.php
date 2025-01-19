<?php

namespace Database\Factories\Matriculas;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Matriculas\Estudiante>
 */
class EstudianteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'prrimer_nombre' => $this->faker->word(),
            'segundo_nombre' => $this->faker->word(),
            'primer_apellido' => $this->faker->word(),
            'segundo_apellido' => $this->faker->word(),
            'fecha_nacimiento' => $this->faker->date(),
            'sexo' => $this->faker->enum(),
            'institucion' => $this->faker->numberBetween(1, 10),
            'estado' => $this->faker->boolean()
        ];
    }
}