<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'course' => $this->faker->randomElement(['TI', 'Administração', 'Contabilidade', 'Engenharia']),
            'enrolled_at' => $this->faker->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
        ];
    }
}
