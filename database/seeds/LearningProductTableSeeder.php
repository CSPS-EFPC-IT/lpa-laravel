<?php

use App\Models\LearningProduct\LearningProduct;
use Illuminate\Database\Seeder;

class LearningProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LearningProduct::truncate();

        // Creating a course from factory will also generate
        // its parent project approved.
        factory(entity_class('course'), 10)
        ->create()
        // Complete course development process for 5 courses.
        ->take(5)
        ->each(function ($course) {
            resolve('process.factory')
                ->startProcess('course-development', $course)
                ->complete();
        });
    }
}
