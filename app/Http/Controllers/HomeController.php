<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\Facture;
use App\Models\Intervenant;
use App\Models\Agenda;
use App\Models\Task;
use App\Models\TimeSheet;
use App\Models\Domaine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $stats = $this->getStats();
        $ca_mois = $this->getChiffreAffairesMois();
        $domaines = $this->getDossiersParDomaine();
        $dossiers_recents = $this->getDossiersRecents();
        $evenements = $this->getEvenementsProchains();
        if (auth()->user()->hasPermission('access_admin_panel')) {
            return view('home', compact('stats', 'ca_mois', 'domaines', 'dossiers_recents', 'evenements'));
        } else {
            // Map view permissions to route names (adjust route names if needed)
            $permissionRouteMap = [
            'view_intervenants' => 'intervenants.index',
            'view_dossiers'    => 'dossiers.index',
            'view_agendas'     => 'agendas.index',
            'view_factures'    => 'factures.index',
            'view_tasks'      => 'tasks.index',
            'view_timesheets' => 'time-sheets.index',
            ];

            foreach ($permissionRouteMap as $permission => $routeName) {
            if (auth()->user()->hasPermission($permission)) {
                return redirect()->route($routeName);
            }
            }

            // Fallback if no specific view_* permission: show limited home or deny access
           abort(403, 'Unauthorized action.');
        }
    }

    private function getStats()
    {
        if (auth()->user()->hasRole('admin')) {
            return [
                'total_dossiers' => Dossier::where('archive', false)->count(),
                'dossiers_contentieux' => Dossier::where('contentieux', true)->where('archive', false)->count(),
                'dossiers_non_contentieux' => Dossier::where('contentieux', false)->where('archive', false)->count(),
                'chiffre_affaires' => Facture::sum('montant'),
                'factures_impayees' => Facture::where('statut', 'non_payé')->count(),
                'taches_en_cours' => Task::whereIn('statut', ['a_faire', 'en_cours'])->count(),
                'evenements_semaine' => Agenda::whereBetween('date_debut', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
                'clients_actifs' => Intervenant::where('categorie', 'client')->where('archive', false)->count(),
                'heures_mois' => TimeSheet::whereMonth('date_timesheet', now()->month)
                    ->where('categorie',1)->sum('quantite')
            ];
        } else {
            $userId = auth()->id();

            return [
                'total_dossiers' => Dossier::where('archive', false)
                    ->whereHas('users', function ($q) use ($userId) {
                        $q->where('users.id', $userId);
                    })->count(),

                'dossiers_contentieux' => Dossier::where('contentieux', true)
                    ->where('archive', false)
                    ->whereHas('users', function ($q) use ($userId) {
                        $q->where('users.id', $userId);
                    })->count(),

                'dossiers_non_contentieux' => Dossier::where('contentieux', false)
                    ->where('archive', false)
                    ->whereHas('users', function ($q) use ($userId) {
                        $q->where('users.id', $userId);
                    })->count(),

                'chiffre_affaires' => Facture::whereHas('dossier.users', function ($q) use ($userId) {
                        $q->where('users.id', $userId);
                    })->sum('montant'),

                'factures_impayees' => Facture::where('statut', 'non_payé')
                    ->whereHas('dossier.users', function ($q) use ($userId) {
                        $q->where('users.id', $userId);
                    })->count(),

                'taches_en_cours' => Task::whereIn('statut', ['a_faire', 'en_cours'])
                    ->whereHas('dossier.users', function ($q) use ($userId) {
                        $q->where('users.id', $userId);
                    })->count(),

                'evenements_semaine' => Agenda::whereBetween('date_debut', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ])->whereHas('dossier.users', function ($q) use ($userId) {
                        $q->where('users.id', $userId);
                    })->count(),

                'clients_actifs' => Intervenant::where('categorie', 'client')->where('archive', false)
                    ->whereHas('dossiers.users', function ($q) use ($userId) {
                        $q->where('users.id', $userId);
                    })->count(),

                'heures_mois' => TimeSheet::whereMonth('date_timesheet', now()->month)
                    ->whereHas('dossier.users', function ($q) use ($userId) {
                        $q->where('users.id', $userId);
                    })->sum('quantite')
            ];
        }
    }

    private function getChiffreAffairesMois()
    {
        $months = [];
        $data = [];
        $userId = auth()->id();
        $isAdmin = auth()->user() && auth()->user()->hasRole('admin');

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->locale('fr')->translatedFormat('M Y');
            $months[] = $monthName;

            if ($isAdmin) {
                $total = Facture::whereYear('date_emission', $date->year)
                    ->whereMonth('date_emission', $date->month)
                    ->sum('montant');
            } else {
                // Jointure entre factures -> dossiers -> dossier_user pour filtrer par utilisateur
                $total = \DB::table('factures')
                    ->join('dossiers', 'factures.dossier_id', '=', 'dossiers.id')
                    ->join('dossier_user', 'dossiers.id', '=', 'dossier_user.dossier_id')
                    ->where('dossier_user.user_id', $userId)
                    ->whereYear('factures.date_emission', $date->year)
                    ->whereMonth('factures.date_emission', $date->month)
                    ->sum('factures.montant');
            }

            $data[] = floatval($total);
        }

        return [
            'labels' => $months,
            'data' => $data
        ];
    }

    private function getDossiersParDomaine()
    {
        $userId = auth()->id();
        $isAdmin = auth()->user() && auth()->user()->hasRole('admin');

        $domaines = Domaine::withCount(['dossiers' => function ($query) use ($isAdmin, $userId) {
            $query->where('archive', false);

            if (! $isAdmin) {
                $query->whereHas('users', function ($q) use ($userId) {
                    $q->where('users.id', $userId);
                });
            }
        }])
        ->having('dossiers_count', '>', 0)
        ->get();

        // Assigner des couleurs
        $colors = [
            '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc',
            '#d2d6de', '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF', '#7AC29A',
            '#6d4c41', '#3949ab', '#5e35b1', '#8e24aa', '#d81b60'
        ];

        foreach ($domaines as $index => $domaine) {
            $domaine->color = $colors[$index % count($colors)];
        }

        return $domaines;
    }

    private function getDossiersRecents()
    {
        $userId = auth()->id();
        $isAdmin = auth()->user() && auth()->user()->hasRole('admin');

        $query = Dossier::with('domaine')
            ->where('archive', false);

        if (! $isAdmin) {
            $query->whereHas('users', function ($q) use ($userId) {
                $q->where('users.id', $userId);
            });
        }

        return $query->orderBy('created_at', 'desc')
                     ->limit(5)
                     ->get();
    }

    private function getEvenementsProchains()
    {
        $userId = auth()->id();
        $isAdmin = auth()->user() && auth()->user()->hasRole('admin');

        $query = Agenda::with('dossier')
            ->where('date_debut', '>=', now()->startOfDay());

        if (! $isAdmin) {
            $query->whereHas('dossier.users', function ($q) use ($userId) {
                $q->where('users.id', $userId);
            });
        }

        return $query->orderBy('date_debut')
                     ->limit(5)
                     ->get();
    }
}