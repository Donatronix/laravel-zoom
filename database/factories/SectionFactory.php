<?php

namespace Database\Factories;

use App\Models\Classes;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sections>
 */
class SectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $sections = ['A', 'B', ];
        return [
            'name' => 'Section ' . $sections[\array_rand($sections)],
            'class_id' => Classes::inRandomOrder()->first()->id,
        ];
    }
}
