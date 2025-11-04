<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FormeSociale;
use Illuminate\Support\Facades\DB;

class FormeSocialeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Désactiver les contraintes de clé étrangère temporairement
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        FormeSociale::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $formeSociales = [
            // Formes sociales pour personnes morales
            ['nom' => 'SARL', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'SA', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'SAS', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'SASU', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'SNC', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'SCS', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'SCA', 'created_at' => now(), 'updated_at' => now()],
            
            // Formes sociales pour professionnels
            ['nom' => 'EI', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'EURL', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'SEP', 'created_at' => now(), 'updated_at' => now()],
            
            // Formes sociales spécifiques au Maroc
            ['nom' => 'SA à directoire', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'SA à conseil d\'administration', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'SARL unipersonnelle', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'GIE', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'SCP', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'SELARL', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'SPF', 'created_at' => now(), 'updated_at' => now()],
            
            // Autres formes
            ['nom' => 'Association', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Fondation', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Coopérative', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Mutuelle', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Groupement d\'intérêt économique', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Société civile', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Société en participation', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Société étrangère', 'created_at' => now(), 'updated_at' => now()],
        ];

        // Insérer les données
        FormeSociale::insert($formeSociales);

        $this->command->info('Table FormeSociale peuplée avec ' . count($formeSociales) . ' enregistrements.');
    }
} 