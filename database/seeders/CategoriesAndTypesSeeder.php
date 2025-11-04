<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesAndTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, truncate tables to avoid duplicates
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('types')->truncate();
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insert categories
        $categories = [
            ['nom' => 'Honoraires'],
            ['nom' => 'Frais'],
            ['nom' => 'Débours'],
        ];

        $categoryIds = [];
        foreach ($categories as $category) {
            $id = DB::table('categories')->insertGetId([
                'nom' => $category['nom'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $categoryIds[$category['nom']] = $id;
        }

        // Insert types with their respective category_id
        $types = [
            // Honoraires types
            'Honoraires' => [
                'Honoraire Forfait',
                'Honoraire Temps passé',
                'Honoraire Résultat',
                'Honoraire Provision',
                'Honoraire Remise',
            ],
            
            // Frais types
            'Frais' => [
                'Copies',
                'Frais postaux',
                'Indemnités Km',
                'Repas',
                'Documentation',
                'Divers',
                'Avion',
                'Train',
                'Louage',
                'Taxi',
                'Métro',
                'Essence',
                'Cotisation',
                'Avocat exterieur',
                'Apporteur',
                'Frais bancaire',
                'Traduction',
                'Ouverture de dossier',
                'Timbre d\'avocat',
                'Frais de communation',
                'Frais de déplacement',
            ],
            
            // Débours types
            'Débours' => [
                'Frais d\'huissier',
                'Frais de notaire',
                'Publicité légale',
                'Taxe d\'enrollement',
                'Taxe d\'appel',
                'Taxe de Cassation',
                'Divers',
                'Frais administratif',
                'Frais de consignation',
            ],
        ];

        foreach ($types as $categoryName => $typeNames) {
            foreach ($typeNames as $typeName) {
                DB::table('types')->insert([
                    'nom' => $typeName,
                    'categorie_id' => $categoryIds[$categoryName],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Categories and Types seeded successfully!');
        $this->command->info('Categories created: ' . count($categories));
        $this->command->info('Types created: ' . array_sum(array_map('count', $types)));
    }
}