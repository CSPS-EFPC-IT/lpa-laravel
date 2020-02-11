<?php

use App\Models\OrganizationalUnit;
use App\Repositories\UserRepository;
use Illuminate\Database\Seeder;

class OrganizationalUnitTableSeeder extends Seeder
{
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    protected function data()
    {
        return [
            [
                'owner'             => true,
                'name_key'          => 'owner-1',
                'name_en'           => 'Transferable Skills',
                'name_fr'           => 'Compétences transférables',
                'email'             => '',
                'director_username' => 'JMedcof',
            ],
            [
                'owner'             => true,
                'name_key'          => 'owner-2',
                'name_en'           => 'Indigenous Learning',
                'name_fr'           => 'Apprentissage autochtone',
                'email'             => '',
                'director_username' => 'MMONTRAT',
            ],
            [
                'owner'             => true,
                'name_key'          => 'owner-3',
                'name_en'           => 'Respectful and Inclusive Workplace',
                'name_fr'           => 'Milieu de travail respectueux et inclusif',
                'email'             => '',
                'director_username' => 'NLaviade',
            ],
            [
                'owner'             => true,
                'name_key'          => 'owner-4',
                'name_en'           => 'Government of Canada and Public Sector Skills',
                'name_fr'           => 'Gouvernement du Canada et compétences du secteur public',
                'email'             => '',
                'director_username' => 'ACHAMPAG',
            ],
            [
                'owner'             => true,
                'name_key'          => 'owner-5',
                'name_en'           => 'Digital Academy',
                'name_fr'           => 'Académie du numérique',
                'email'             => '',
                'director_username' => 'ACHAMPAG',
            ],
            [
                'owner'             => false,
                'name_key'          => 'curriculum-management',
                'name_en'           => 'Curriculum Management',
                'name_fr'           => 'Gestion du programme de cours',
                'email'             => 'csps.scic.cice.efpc@canada.ca',
                'director_username' => 'PPROULX',
            ],
            [
                'owner'             => false,
                'name_key'          => 'client-services',
                'name_en'           => 'Clients Services',
                'name_fr'           => 'Service aux clients',
                'email'             => 'csps.clientservices-servicesclients.efpc@canada.ca',
                'director_username' => 'LMACMILL',
            ],
            [
                'owner'             => false,
                'name_key'          => 'gccampus',
                'name_en'           => 'GCcampus',
                'name_fr'           => 'GCcampus',
                'email'             => 'csps.gccampus.efpc@canada.ca',
                'director_username' => 'DHILZ',
            ],
            [
                'owner'             => false,
                'name_key'          => 'ilms',
                'name_en'           => 'Integrated Learning Management System',
                'name_fr'           => 'Système harmonisé de gestion de l’apprentissage',
                'email'             => 'csps.ilmsbusinessoperationsoperationsdaffaireshga.efpc@canada.ca',
                'director_username' => 'DHILZ',
            ],
            [
                'owner'             => false,
                'name_key'          => 'nop',
                'name_en'           => 'National Operations Planning',
                'name_fr'           => 'Planification des opérations nationales',
                'email'             => 'csps.capacityplanningplanificationdelacapacite.efpc@canada.ca',
                'director_username' => 'LCYR',
            ],
            [
                'owner'             => false,
                'name_key'          => 'lsd',
                'name_en'           => 'Learning Solution Division',
                'name_fr'           => 'Division des solutions d\'apprentissage',
                'email'             => 'csps.learningsolutionsrequest-demandesolutionsapprentissage.efpc@canada.ca',
                'director_username' => 'LNOWOSIE',
            ],
            [
                'owner'             => false,
                'name_key'          => 'comms',
                'name_en'           => 'Communications, Marketing and Web Management',
                'name_fr'           => 'Communications, marketing et gestion du Web',
                'email'             => 'csps.communications.efpc@canada.ca',
                'director_username' => 'VVermett',
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
        DB::table('organizational_unit_user')->truncate();
        DB::table('organizational_units')->truncate();

        // Temporarily use Faker to generate some fake data.
        $faker = \Faker\Factory::create();

        // Generate Organization Units.
        foreach ($this->data() as $organizationalUnit) {
            // Get or create user to be set as director for organizational unit.
            $director = $this->users->findOrCreate($organizationalUnit['director_username']);

            OrganizationalUnit::create([
                'owner'      => $organizationalUnit['owner'],
                'email'      => $faker->email,
                'director'   => $director->id,
                'name_key'   => $organizationalUnit['name_key'],
                'name_en'    => $organizationalUnit['name_en'],
                'name_fr'    => $organizationalUnit['name_fr'],
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
        }
    }
}
