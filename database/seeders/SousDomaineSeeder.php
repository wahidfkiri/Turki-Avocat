<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SousDomaineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        // Récupérer les domaines pour avoir leurs IDs
        $domaines = DB::table('domaines')->get()->pluck('id', 'nom')->toArray();
        
        $sousDomaines = [
            // Droit de la famille, des personnes et de leur patrimoine
            'Droit de la famille, des personnes et de leur patrimoine' => [
                'Mariage, divorce, séparation',
                'Régimes matrimoniaux',
                'Filiation, adoption',
                'Successions et libéralités',
                'Tutelle',
                'Changement, correction d\'état civil'
            ],
            
            // Droit du travail
            'Droit du travail' => [
                'Contrat de travail',
                'Licenciement',
                'Harcèlement',
                'Relations collectives de travail',
                'Prud\'hommes'
            ],
            
            // Droit social
            'Droit social' => [
                'Droit de la sécurité sociale',
                'Protection sociale',
                'Retraite, prévoyance',
                'Accident du travail, maladie professionnelle'
            ],
            
            // Droit commercial, des affaires et de la concurrence
            'Droit commercial, des affaires et de la concurrence' => [
                'Contrats commerciaux',
                'Fonds de commerce',
                'Concurrence déloyale',
                'Baux commerciaux',
                'Droit de la distribution',
                'Franchise'
            ],
            
            // Droit des sociétés
            'Droit des sociétés' => [
                'Constitution et gestion de sociétés',
                'Cessions de parts, fusions, acquisitions',
                'Gouvernance d\'entreprise',
                'Liquidation'
            ],
            
            // Droit bancaire et boursier
            'Droit bancaire et boursier' => [
                'Crédits, prêts',
                'Contentieux bancaires',
                'Instruments financiers',
                'Réglementation boursière'
            ],
            
            // Droit fiscal et droit douanier
            'Droit fiscal et droit douanier' => [
                'Fiscalité des particuliers',
                'Fiscalité des entreprises',
                'Contrôle et contentieux fiscal',
                'Contrôle et contentieux douanier',
                'TVA, droits d\'accise'
            ],
            
            // Droit immobilier
            'Droit immobilier' => [
                'Vente immobilière',
                'Construction',
                'Copropriété',
                'Urbanisme',
                'Baux d\'habitation'
            ],
            
            // Droit de l'environnement
            'Droit de l\'environnement' => [
                'Pollutions, nuisances',
                'Protection de la nature',
                'Droit de l\'énergie'
            ],
            
            // Droit public
            'Droit public' => [
                'Fonction publique',
                'Responsabilité administrative',
                'Marchés publics',
                'Urbanisme'
            ],
            
            // Droit pénal
            'Droit pénal' => [
                'Droit pénal général',
                'Droit pénal des affaires',
                'Droit pénal du travail'
            ],
            
            // Droit des étrangers et de la nationalité
            'Droit des étrangers et de la nationalité' => [
                'Titres de séjour',
                'Naturalisation',
                'Asile'
            ],
            
            // Droit international
            'Droit international' => [
                'Contrats internationaux',
                'Droit International des affaires',
                'Reconnaissance des jugements étrangers'
            ],
            
            // Droit de la propriété intellectuelle
            'Droit de la propriété intellectuelle' => [
                'Marques, brevets, dessins et modèles',
                'Droit d\'auteur',
                'Noms de domaine',
                'Contentieux de la contrefaçon'
            ],
            
            // Droit des nouvelles technologies, de l'informatique et de la communication
            'Droit des nouvelles technologies, de l\'informatique et de la communication' => [
                'Protection des données personnelles (RGPD)',
                'Cybercriminalité',
                'Contrats IT',
                'Commerce électronique'
            ],
            
            // Droit des assurances
            'Droit des assurances' => [
                'Contrats d\'assurance',
                'Assurance vie',
                'Sinistres, indemnisations'
            ],
            
            // Droit rural
            'Droit rural' => [
                'Baux ruraux',
                'Statut de l\'agriculteur',
                'Droit des exploitations agricoles'
            ],
        ];
        
        $sousDomainesData = [];
        
        foreach ($sousDomaines as $domaineNom => $sousDomainesList) {
            $domaineId = $domaines[$domaineNom];
            
            foreach ($sousDomainesList as $sousDomaineNom) {
                $sousDomainesData[] = [
                    'nom' => $sousDomaineNom,
                    'domaine_id' => $domaineId,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
        }
        
        DB::table('sous_domaines')->insert($sousDomainesData);
    }
}