<?php

use App\Models\LearningProduct\Development\OfferingRegion;
use App\Models\Lists\Region;
use Faker\Generator as Faker;

$factory->define(OfferingRegion::class, function (Faker $faker) {

    $faker->addProvider(new App\Support\CustomFaker($faker));

    return [
        'region_id'                                                   => $faker->randomList(Region::all()),
        'regional_annual_bilingual_offering_number'                   => $faker->numberBetween(0, 365),
        'regional_annual_english_offering_number'                     => $faker->numberBetween(0, 365),
        'regional_annual_french_offering_number'                      => $faker->numberBetween(0, 365),
        'regional_annual_simultaneous_interpretation_offering_number' => $faker->numberBetween(0, 365),
    ];
});
