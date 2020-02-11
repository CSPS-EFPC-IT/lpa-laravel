<?php

use App\Models\Lists\ContactFormSubject;
use Illuminate\Database\Seeder;

class ContactFormSubjectTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            ['name_en' => 'Need Help', 'name_fr' => 'Besoin d\'aide'],
            ['name_en' => 'Report an issue', 'name_fr' => 'Rapporter un problème'],
            ['name_en' => 'Feedback', 'name_fr' => 'Commentaires'],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contact_form_subjects')->truncate();

        ContactFormSubject::createFrom($this->data());
    }
}
