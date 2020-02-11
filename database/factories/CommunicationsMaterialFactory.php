<?php

use App\Models\LearningProduct\CommunicationsReview\CommunicationsMaterial;
use Faker\Generator as Faker;

$factory->define(CommunicationsMaterial::class, function (Faker $faker) {

    $faker->addProvider(new App\Support\CustomFaker($faker));

    return [
        'comments'                      => $faker->paragraph(),
        'description_en'                => $faker->paragraph(),
        'description_fr'                => $faker->paragraph(),
        'disclaimer_en'                 => $faker->paragraph(),
        'disclaimer_fr'                 => $faker->paragraph(),
        'keywords_en'                   => $faker->paragraph(),
        'keywords_fr'                   => $faker->paragraph(),
        'linguistic_services_consulted' => $faker->boolean(),
        'material_original_language'    => $faker->language(),
        'note_en'                       => $faker->paragraph(),
        'note_fr'                       => $faker->paragraph(),
        'summary_en'                    => $faker->paragraph(),
        'summary_fr'                    => $faker->paragraph(),
        'title_en'                      => $faker->sentenceNoDot(),
        'title_fr'                      => $faker->sentenceNoDot(),
    ];
});
