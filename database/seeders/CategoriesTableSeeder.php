<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['nom' => 'Consultation juridique'],
            ['nom' => 'Rédaction d\'actes'],
            ['nom' => 'Représentation en justice'],
            ['nom' => 'Négociation'],
            ['nom' => 'Recherche juridique'],
            ['nom' => 'Réunion client'],
            ['nom' => 'Audience'],
            ['nom' => 'Déplacement'],
            ['nom' => 'Formation'],
            ['nom' => 'Administratif'],
            ['nom' => 'Correspondance'],
            ['nom' => 'Téléphone'],
            ['nom' => 'Expertise'],
            ['nom' => 'Médiation'],
            ['nom' => 'Arbitrage'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'nom' => $category['nom'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}