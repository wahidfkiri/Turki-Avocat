<?php
// database/seeders/AgendaCategoriesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AgendaCategory;

class AgendaCategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'nom' => 'Rendez-vous',
                'couleur' => '#3c8dbc',
                'description' => 'Rendez-vous professionnels',
                'ordre' => 1,
                'actif' => true
            ],
            [
                'nom' => 'Audience',
                'couleur' => '#f39c12',
                'description' => 'Audiences judiciaires',
                'ordre' => 2,
                'actif' => true
            ],
            [
                'nom' => 'Délai',
                'couleur' => '#00a65a',
                'description' => 'Délais légaux et administratifs',
                'ordre' => 3,
                'actif' => true
            ],
            [
                'nom' => 'Tâche',
                'couleur' => '#dd4b39',
                'description' => 'Tâches à accomplir',
                'ordre' => 4,
                'actif' => true
            ]
        ];

        foreach ($categories as $category) {
            AgendaCategory::create($category);
        }
    }
}