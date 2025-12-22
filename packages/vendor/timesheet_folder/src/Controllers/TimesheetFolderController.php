<?php

namespace Vendor\TimesheetFolder\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TimeSheet;
use App\Models\Dossier;
use App\Models\User;
use App\Models\Categorie;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class TimesheetFolderController extends Controller
{
    /**
     * Récupérer les données d'édition d'une timesheet (route globale)
     */
    public function getTimesheetEditData(Dossier $dossier, TimeSheet $timesheet)
    {
        if (!auth()->user()->hasPermission('edit_timesheets')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Vérifier que l'utilisateur peut éditer cette timesheet
        if (!auth()->user()->hasRole('admin') && $timesheet->utilisateur_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $timesheet->load(['user']);
            
            // Récupérer toutes les catégories
            $categories = Categorie::orderBy('nom')->get(['id', 'nom']);
            
            // Récupérer les types de la catégorie actuelle
            $types = $timesheet->categorie ? 
                Type::where('categorie_id', $timesheet->categorie)->orderBy('nom')->get(['id', 'nom']) : 
                collect([]);
            
            // Récupérer tous les utilisateurs actifs
            $users = User::where('is_active', true)->orderBy('name')->get(['id', 'name']);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $timesheet->id,
                    'date_timesheet' => $timesheet->date_timesheet ? $timesheet->date_timesheet->format('Y-m-d') : '',
                    'utilisateur_id' => $timesheet->utilisateur_id,
                    'description' => $timesheet->description ?? '',
                    'categorie_id' => $timesheet->categorie ?? '', // Note: this is 'categorie' in DB
                    'type_id' => $timesheet->type ?? '', // Note: this is 'type' in DB
                    'quantite' => $timesheet->quantite ?? 0,
                    'prix' => $timesheet->prix ?? 0,
                    'total' => $timesheet->total ?? 0,
                    'user_name' => $timesheet->user ? $timesheet->user->name : '',
                    'file_name' => $timesheet->file_name,
                    'file_path' => $timesheet->file_path,
                ],
                'categories' => $categories,
                'types' => $types,
                'users' => $users
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération données édition timesheet: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des données: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les données DataTable pour les timesheets d'un dossier
     */
    public function getDossierTimesheetsData(Request $request, Dossier $dossier)
    {
        if (!auth()->user()->hasPermission('view_timesheets')) {
            abort(403, 'Unauthorized action.');
        }

        $query = TimeSheet::with([
                'user:id,name,email',
                'categorieRelation:id,nom',
                'typeRelation:id,nom'
            ])
            ->where('dossier_id', $dossier->id)
            ->orderBy('date_timesheet', 'DESC');

        // Filtre par utilisateur (seulement pour les admins)
        if (!auth()->user()->hasRole('admin')) {
            $query->where('utilisateur_id', auth()->id());
        }

        return DataTables::eloquent($query)
            ->addColumn('actions', function (TimeSheet $timesheet) {
                $html = '<div class="btn-group btn-group-sm">';
                
                // Bouton Voir
                if (auth()->user()->hasPermission('view_timesheets')) {
                    $html .= '<button class="btn btn-info view-timesheet-btn mr-1" 
                              data-id="' . $timesheet->id . '" 
                              title="Voir les détails">
                              <i class="fas fa-eye"></i>
                          </button>';
                }
                
                // Bouton Modifier
                if (auth()->user()->hasPermission('edit_timesheets') && 
                    (auth()->user()->hasRole('admin') || $timesheet->utilisateur_id == auth()->id())) {
                    $html .= '<button class="btn btn-warning edit-timesheet-btn mr-1" 
                              data-id="' . $timesheet->id . '" 
                              title="Modifier">
                              <i class="fas fa-edit"></i>
                          </button>';
                }
                
                // Bouton Supprimer
                if (auth()->user()->hasPermission('delete_timesheets') && 
                    (auth()->user()->hasRole('admin') || $timesheet->utilisateur_id == auth()->id())) {
                    $html .= '<button class="btn btn-danger delete-timesheet-btn" 
                              data-id="' . $timesheet->id . '" 
                              data-description="' . htmlspecialchars(substr($timesheet->description, 0, 50)) . '" 
                              title="Supprimer">
                              <i class="fas fa-trash"></i>
                          </button>';
                }
                
                $html .= '</div>';
                return $html;
            })
            
            ->addColumn('categorieRelation.nom', function (Timesheet $timesheet) {
                return $timesheet->categorieRelation ? $timesheet->categorieRelation->nom : '-';
            })
            ->addColumn('typeRelation.nom', function (Timesheet $timesheet) {
                return $timesheet->typeRelation ? $timesheet->typeRelation->nom : '-';
            })
            ->editColumn('date_timesheet', function (TimeSheet $timesheet) {
                return $timesheet->date_timesheet ? $timesheet->date_timesheet->format('d/m/Y') : '-';
            })
            ->editColumn('quantite', function (TimeSheet $timesheet) {
                return number_format($timesheet->quantite, 2, ',', ' ');
            })
            ->editColumn('prix', function (TimeSheet $timesheet) {
                return number_format($timesheet->prix, 2, ',', ' ') . ' DT';
            })
            ->editColumn('total', function (TimeSheet $timesheet) {
                return number_format($timesheet->total, 2, ',', ' ') . ' DT';
            })
            ->editColumn('description', function (TimeSheet $timesheet) {
                return strlen($timesheet->description) > 50 ? 
                    substr($timesheet->description, 0, 50) . '...' : 
                    $timesheet->description;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Créer une feuille de temps pour un dossier spécifique
     */
    public function storeForDossier(Request $request, Dossier $dossier)
    {
        if (!auth()->user()->hasPermission('create_timesheets')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        Log::info('Données reçues pour création:', $request->all());

        try {
            $validated = $request->validate([
                'date_timesheet' => 'required|date',
                'utilisateur_id' => 'required|exists:users,id',
                'description' => 'required|string|max:1000',
                'categorie' => 'required|exists:categories,id',
                'type' => 'nullable|exists:types,id',
                'quantite' => 'required|numeric|min:0.01',
                'prix' => 'required|numeric|min:0.01',
                'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            ]);

            Log::info('Données validées:', $validated);

            // Calculate total
            $total = $validated['quantite'] * $validated['prix'];
            
            // Create TimeSheet manually
            $timesheet = new TimeSheet();
            $timesheet->date_timesheet = $validated['date_timesheet'];
            $timesheet->utilisateur_id = $validated['utilisateur_id'];
            $timesheet->dossier_id = $dossier->id; // Make sure this is set!
            $timesheet->description = $validated['description'];
            $timesheet->categorie = $validated['categorie_id']; // Save as 'categorie' (not categorie_id)
            $timesheet->type = $validated['type_id'] ?? null; // Save as 'type' (not type_id)
            $timesheet->quantite = $validated['quantite'];
            $timesheet->prix = $validated['prix'];
            $timesheet->total = $total;
            
            // DEBUG: Log data before saving
            Log::info('Données à sauvegarder:', [
                'dossier_id' => $dossier->id,
                'categorie' => $validated['categorie_id'],
                'type' => $validated['type_id'] ?? null,
                'utilisateur_id' => $validated['utilisateur_id']
            ]);
            
            // Handle file upload
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                try {
                    $file = $request->file('file');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('timesheet_files', $fileName, 'public');
                    
                    $timesheet->file_path = $path;
                    $timesheet->file_name = $file->getClientOriginalName();
                    
                    Log::info('Fichier uploadé avec succès:', [
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName()
                    ]);
                } catch (\Exception $fileException) {
                    Log::error('Erreur lors de l\'upload du fichier:', [
                        'error' => $fileException->getMessage(),
                        'file' => $file->getClientOriginalName()
                    ]);
                    // Continue without file - don't fail the entire creation
                }
            }
            
            // Save to database
            $timesheet->save();
            
            // DEBUG: Log saved timesheet
            Log::info('Timesheet sauvegardée:', [
                'id' => $timesheet->id,
                'dossier_id' => $timesheet->dossier_id,
                'categorie' => $timesheet->categorie,
                'type' => $timesheet->type
            ]);
            
            // Reload with relationships
            $timesheet->load(['user', 'dossier', 'categorieRelation', 'typeRelation']);

            Log::info('Timesheet créée avec succès:', [
                'id' => $timesheet->id,
                'dossier_id' => $timesheet->dossier_id,
                'total' => $timesheet->total
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Feuille de temps créée avec succès.',
                'data' => $timesheet,
                'file_url' => $timesheet->file_path ? asset('storage/' . $timesheet->file_path) : null
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Erreur création timesheet:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la feuille de temps: ' . $e->getMessage(),
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Une erreur est survenue'
            ], 500);
        }
    }

    /**
     * Mettre à jour une feuille de temps dans un dossier
     */
    public function updateForDossier(Request $request, Dossier $dossier, TimeSheet $timesheet)
    {
        if (!auth()->user()->hasPermission('edit_timesheets')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Vérifier que la timesheet appartient au dossier
        if ($timesheet->dossier_id != $dossier->id) {
            return response()->json(['error' => 'Cette feuille de temps n\'appartient pas à ce dossier'], 403);
        }

        Log::info('Mise à jour timesheet:', $request->all());

        try {
            $validated = $request->validate([
                'date_timesheet' => 'required|date',
                'utilisateur_id' => 'required|exists:users,id',
                'description' => 'required|string|max:1000',
                'categorie_id' => 'required|exists:categories,id',
                'type_id' => 'nullable|exists:types,id',
                'quantite' => 'required|numeric|min:0.01',
                'prix' => 'required|numeric|min:0.01',
                'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            ]);

            // Calculate total
            $total = $validated['quantite'] * $validated['prix'];
            
            // Update manually
            $timesheet->date_timesheet = $validated['date_timesheet'];
            $timesheet->utilisateur_id = $validated['utilisateur_id'];
            $timesheet->description = $validated['description'];
            $timesheet->categorie = $validated['categorie_id']; // Map to 'categorie'
            $timesheet->type = $validated['type_id'] ?? null; // Map to 'type'
            $timesheet->quantite = $validated['quantite'];
            $timesheet->prix = $validated['prix'];
            $timesheet->total = $total;

            // Handle file
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                // Delete old file if exists
                if ($timesheet->file_path) {
                    Storage::disk('public')->delete($timesheet->file_path);
                }
                
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('timesheet_files', $fileName, 'public');
                
                $timesheet->file_path = $path;
                $timesheet->file_name = $file->getClientOriginalName();
            }

            $timesheet->save();

            Log::info('Timesheet mise à jour:', ['id' => $timesheet->id]);

            return response()->json([
                'success' => true,
                'message' => 'Feuille de temps mise à jour avec succès.',
                'data' => $timesheet->fresh(['user', 'categorieRelation', 'typeRelation'])
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour timesheet:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer une feuille de temps d'un dossier
     */
    public function destroyForDossier(Request $request, Dossier $dossier, TimeSheet $timesheet)
    {
        if (!auth()->user()->hasPermission('delete_timesheets')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Vérifier que la timesheet appartient au dossier
        if ($timesheet->dossier_id != $dossier->id) {
            return response()->json(['error' => 'Cette feuille de temps n\'appartient pas à ce dossier'], 403);
        }

        try {
            // Delete file if exists
            if ($timesheet->file_path) {
                Storage::disk('public')->delete($timesheet->file_path);
            }

            $timesheet->delete();

            return response()->json([
                'success' => true,
                'message' => 'Feuille de temps supprimée avec succès.'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur suppression timesheet: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les détails d'une feuille de temps
     */
    public function getTimesheetDetails(Dossier $dossier, TimeSheet $timesheet)
    {
        if (!auth()->user()->hasPermission('view_timesheets')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $timesheet->load(['user', 'categorieRelation', 'typeRelation', 'dossier']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $timesheet->id,
                'date' => $timesheet->date_timesheet ? $timesheet->date_timesheet->format('d/m/Y') : 'N/A',
                'description' => $timesheet->description,
                'user' => $timesheet->user ? $timesheet->user->name : 'N/A',
                'dossier' => $timesheet->dossier ? $timesheet->dossier->numero_dossier : 'N/A',
                'categorie' => $timesheet->categorieRelation ? $timesheet->categorieRelation->nom : 'N/A',
                'type' => $timesheet->typeRelation ? $timesheet->typeRelation->nom : 'N/A',
                'quantite' => number_format($timesheet->quantite, 2, ',', ' '),
                'prix' => number_format($timesheet->prix, 2, ',', ' ') . ' DT',
                'total' => number_format($timesheet->total, 2, ',', ' ') . ' DT',
                'created_at' => $timesheet->created_at ? $timesheet->created_at->format('d/m/Y H:i') : 'N/A',
                'updated_at' => $timesheet->updated_at ? $timesheet->updated_at->format('d/m/Y H:i') : 'N/A',
                'file_name' => $timesheet->file_name,
                'file_path' => $timesheet->file_path ? Storage::url($timesheet->file_path) : null,
            ]
        ]);
    }

    /**
     * Récupérer les catégories via AJAX
     */
    public function getCategoriesAjax()
    {
        $categories = Categorie::orderBy('nom')->get(['id', 'nom']);
        return response()->json($categories);
    }

    /**
     * Récupérer les types par catégorie
     */
    public function getTypesByCategory(Categorie $categorie)
    {
        $types = Type::where('categorie_id', $categorie->id)
                    ->orderBy('nom')
                    ->get(['id', 'nom']);
        
        return response()->json($types);
    }

    /**
     * Récupérer les données d'édition d'une timesheet
     */
    public function getTimesheetEditDatacopy(TimeSheet $timesheet)
    {
        if (!auth()->user()->hasPermission('edit_timesheets')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $timesheet->load(['user']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $timesheet->id,
                'date_timesheet' => $timesheet->date_timesheet ? $timesheet->date_timesheet->format('Y-m-d') : '',
                'utilisateur_id' => $timesheet->utilisateur_id,
                'description' => $timesheet->description,
                'categorie_id' => $timesheet->categorie,
                'type_id' => $timesheet->type,
                'quantite' => $timesheet->quantite,
                'prix' => $timesheet->prix,
                'total' => $timesheet->total,
                'user_name' => $timesheet->user ? $timesheet->user->name : '',
            ],
            'categories' => Categorie::orderBy('nom')->get(['id', 'nom']),
            'types' => $timesheet->categorie ? 
                Type::where('categorie_id', $timesheet->categorie)->orderBy('nom')->get(['id', 'nom']) : 
                [],
            'users' => User::where('is_active', true)->orderBy('name')->get(['id', 'name'])
        ]);
    }
}