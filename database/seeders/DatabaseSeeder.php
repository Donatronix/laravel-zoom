<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\ClassesSeeder;
use Database\Seeders\SectionsSeeder;
use Database\Seeders\StudentsSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ClassesSeeder::class,
            SectionsSeeder::class,
            StudentsSeeder::class,
        ]);
    }
}
