<?php

namespace Database\Factories;

use App\Models\Classes;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Students>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $section = Section::inRandomOrder()->get()
            ->filter(function ($section) {
                return $section->class->count() > 0;
            })
            ->first();

        $section_id = $section->id;
        $class_id = $section->class->id;

        return [
            'class_id' => $class_id,
            'section_id' => $section_id,
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'address' => $this->faker->address(),
            'phone_number' => $this->faker->phoneNumber(),
        ];
    }
}
