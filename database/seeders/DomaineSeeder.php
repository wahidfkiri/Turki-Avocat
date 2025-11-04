<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DomaineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        $domaines = [
            ['nom' => 'Droit de la famille, des personnes et de leur patrimoine'],
            ['nom' => 'Droit du travail'],
            ['nom' => 'Droit social'],
            ['nom' => 'Droit commercial, des affaires et de la concurrence'],
            ['nom' => 'Droit des sociétés'],
            ['nom' => 'Droit bancaire et boursier'],
            ['nom' => 'Droit fiscal et droit douanier'],
            ['nom' => 'Droit immobilier'],
            ['nom' => 'Droit de l\'environnement'],
            ['nom' => 'Droit public'],
            ['nom' => 'Droit pénal'],
            ['nom' => 'Droit des étrangers et de la nationalité'],
            ['nom' => 'Droit international'],
            ['nom' => 'Droit de la propriété intellectuelle'],
            ['nom' => 'Droit des nouvelles technologies, de l\'informatique et de la communication'],
            ['nom' => 'Droit des assurances'],
            ['nom' => 'Droit rural'],
        ];

        foreach ($domaines as &$domaine) {
            $domaine['created_at'] = $now;
            $domaine['updated_at'] = $now;
        }

        DB::table('domaines')->insert($domaines);
    }
}