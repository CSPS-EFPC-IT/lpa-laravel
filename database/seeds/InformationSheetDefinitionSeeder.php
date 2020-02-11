<?php

use Illuminate\Database\Seeder;
use App\Models\InformationSheet\InformationSheetDefinition;

class InformationSheetDefinitionSeeder extends Seeder
{
    /**
     * Project information sheets.
     *
     * @return array
     */
    protected function projectInformationSheets()
    {
        return [
            [
                'name_key'         => 'details',
                'name_en'          => 'Project Details',
                'name_fr'          => 'Détails du projet',
                'display_sequence' => 1,
            ],
        ];
    }

    /**
     * Course information sheet definitions common for each types.
     *
     * @return array
     */
    protected function courseInformationSheets()
    {
        return [
            [
                'name_key'         => 'details',
                'name_en'          => 'Course Details',
                'name_fr'          => 'Détails du cours',
                'display_sequence' => 1,
            ],
            [
                'name_key'         => 'preparation-and-administration',
                'name_en'          => 'Preparation & Administration',
                'name_fr'          => 'Préparation et administration',
                'display_sequence' => 2,
            ],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate previous tables.
        DB::table('information_sheets')->truncate();

        // Create project information sheet definitions.
        foreach ($this->projectInformationSheets() as $infosheet) {
            InformationSheetDefinition::create([
                'entity_type'      => 'project',
                'name_key'         => $infosheet['name_key'],
                'name_en'          => $infosheet['name_en'],
                'name_fr'          => $infosheet['name_fr'],
                'display_sequence' => $infosheet['display_sequence'],
            ]);
        }

       // Create course information sheet definitions.
        foreach ($this->courseInformationSheets() as $infosheet) {
            InformationSheetDefinition::create([
                'entity_type'      => 'course',
                'name_key'         => $infosheet['name_key'],
                'name_en'          => $infosheet['name_en'],
                'name_fr'          => $infosheet['name_fr'],
                'display_sequence' => $infosheet['display_sequence'],
            ]);
        }
    }
}
