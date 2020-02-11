<?php

use Faker\Generator as Faker;
use App\Models\LearningProduct\Development\OfferingAndEnrolmentEstimates;
use App\Models\LearningProduct\Development\OfferingRegion;

$factory->define(OfferingAndEnrolmentEstimates::class, function (Faker $faker) {

    $faker->addProvider(new App\Support\CustomFaker($faker));

    return [
        'offering_regions' => function () {
            return factory(OfferingRegion::class, rand(1, 4))->make()->toArray();
        },
        'comments'                                    => $faker->paragraph(),
    ];
});
