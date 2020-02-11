<?php

use App\Models\Lists\MaterialItem;
use Illuminate\Database\Seeder;

class MaterialItemTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            ['name_en' => 'Computer', 'name_fr' => 'Ordinateur'],
            ['name_en' => 'DVD Player', 'name_fr' => 'Lecteur DVD'],
            ['name_en' => 'Eraser', 'name_fr' => 'Gomme à effacer'],
            ['name_en' => 'Flip Chart (with paper)', 'name_fr' => 'Tableau à feuilles (avec papier)'],
            ['name_en' => 'Flip Chart Marker', 'name_fr' => 'Marqueur pour tableau à feuilles'],
            ['name_en' => 'Highlighter', 'name_fr' => 'Surligneur'],
            ['name_en' => 'Index Cards', 'name_fr' => 'Fiches de carton'],
            ['name_en' => 'Interactive Touch Screen', 'name_fr' => 'Écran tactile interactif'],
            ['name_en' => 'Internet Access', 'name_fr' => 'Accès Internet'],
            ['name_en' => 'Lined Paper', 'name_fr' => 'Feuilles lignées'],
            ['name_en' => 'Paper Pad ', 'name_fr' => 'Calepin '],
            ['name_en' => 'Pen', 'name_fr' => 'Stylo'],
            ['name_en' => 'Pencil', 'name_fr' => 'Crayon'],
            ['name_en' => 'Pencil Sharpener', 'name_fr' => 'Taille-crayon'],
            ['name_en' => 'Post-It ', 'name_fr' => 'Papillons adhésif (Post-It)'],
            ['name_en' => 'Presentation Remote Control ', 'name_fr' => 'Télécommande pour présentation '],
            ['name_en' => 'Projector and Screen', 'name_fr' => 'Projecteur et écran '],
            ['name_en' => 'Scissors', 'name_fr' => 'Ciseaux'],
            ['name_en' => 'Speaker', 'name_fr' => 'Haut-parleur'],
            ['name_en' => 'Tape Roll', 'name_fr' => 'Ruban adhésif'],
            ['name_en' => 'Table', 'name_fr' => 'Table'],
            ['name_en' => 'Tent Card (Name Card)', 'name_fr' => 'Porte-nom'],
            ['name_en' => 'TV Screen', 'name_fr' => 'Écran de télévision'],
            ['name_en' => 'VHS Player', 'name_fr' => 'Lecteur VHS'],
            ['name_en' => 'Whiteboard', 'name_fr' => 'Tableau blanc'],
            ['name_en' => 'Whiteboard Markers', 'name_fr' => 'Marqueur pour tableau blanc'],
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
        DB::table('material_items')->truncate();

        MaterialItem::createFrom($this->data());
    }
}
