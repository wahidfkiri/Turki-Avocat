<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['nom' => 'Honoraires'],
            ['nom' => 'Frais de déplacement'],
            ['nom' => 'Frais de dossier'],
            ['nom' => 'Frais d\'expertise'],
            ['nom' => 'Frais de justice'],
            ['nom' => 'Frais de photocopie'],
            ['nom' => 'Frais de communication'],
            ['nom' => 'Frais de timbre'],
            ['nom' => 'Frais d\'huissier'],
            ['nom' => 'Frais d\'enregistrement'],
            ['nom' => 'Frais de publication'],
            ['nom' => 'Frais de traduction'],
            ['nom' => 'Frais de consultation'],
            ['nom' => 'Frais de médiation'],
            ['nom' => 'Frais divers'],
        ];

        foreach ($types as $type) {
            DB::table('types')->insert([
                'nom' => $type['nom'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}