<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTimeSheetRequest;
use App\Http\Requests\UpdateTimeSheetRequest;
use App\Http\Resources\TimeSheetResource;
use App\Models\TimeSheet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\User;
use App\Models\Dossier;
use App\Models\Categorie;
use App\Models\Type;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TimeSheetController extends Controller
{
    public function index()
{
    if (!auth()->user()->hasPermission('view_timesheets')) {
            abort(403, 'Unauthorized action.');
        }
    
    $users = User::where('is_active', true)->get();
    $dossiers = Dossier::with('intervenants')->get();
    $categories = Categorie::all(); // Assurez-vous d'avoir le modèle Categorie
    $types = Type::all(); // Assurez-vous d'avoir le modèle Type
    
    return view('timesheets.index', compact('users', 'dossiers', 'categories', 'types'));
}


public function create()
{
    $this->authorize('create_timesheets', Timesheet::class);
    
    $users = User::where('is_active', true)->get();
    $dossiers = Dossier::with('intervenants')->get();
    $categories = Categorie::all();
    $types = Type::all();
    
    return view('timesheets.create', compact('users', 'dossiers', 'categories', 'types'));
}

 public function getCategories()
    {
        $categories = Categorie::select('id', 'nom')->get();
        return response()->json($categories);
    }
    public function getTypes(Request $request)
    {
        $categorieId = $request->query('categorie_id');
        
        if (!$categorieId) {
            return response()->json([]);
        }
        
        $types = Type::where('categorie_id', $categorieId)
                    ->select('id', 'nom')
                    ->get();
                    
        return response()->json($types);
    }
 public function store(Request $request)
{
    if (!auth()->user()->hasPermission('create_timesheets')) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $validated = $request->validate([
        'date_timesheet' => 'required|date',
        'utilisateur_id' => 'required|exists:users,id',
        'dossier_id' => 'nullable|exists:dossiers,id',
        'description' => 'required|string|max:1000',
        'categorie' => 'nullable|exists:categories,id',
        'type' => 'nullable|exists:types,id',
        'quantite' => 'required|numeric|min:0',
        'prix' => 'required|numeric|min:0',
        'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048', // max 2MB
    ]);

    try {
        // Calculer le total
        $validated['total'] = $validated['quantite'] * $validated['prix'];

        if($request->hasFile('file')){
            $file = $request->file('file');
            $path = $file->store('timesheet_files', 'public');
            $validated['file_path'] = $path;
            $validated['file_name'] = $file->getClientOriginalName();
        }

        Timesheet::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Feuille de temps créée avec succès.',
            'redirect_url' => route('time-sheets.index')
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la création de la feuille de temps: ' . $e->getMessage()
        ], 500);
    }
}

    public function show(TimeSheet $time_sheet)
    {
        if (!auth()->user()->hasPermission('view_timesheets')) {
            abort(403, 'Unauthorized action.');
        }
        return view('timesheets.show', compact('time_sheet'));
    }
public function edit(Timesheet $time_sheet)
{
         if (!auth()->user()->hasPermission('edit_timesheets')) {
            abort(403, 'Unauthorized action.');
        }
    $users = User::where('is_active', true)->get();
    $dossiers = Dossier::with('intervenants')->get();
    $categories = Categorie::all();
    $types = Type::all();
    $timesheet = $time_sheet;

    
    return view('timesheets.edit', compact('timesheet', 'users', 'dossiers', 'categories', 'types'));
}

public function getTimesheetAjax($id)  // Accepte l'ID directement
{
    \Log::info('AJAX Request for timesheet ID:', ['id' => $id]);
    
    if (!auth()->user()->hasPermission('edit_timesheets')) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    try {
        // Trouver la feuille de temps par ID
        $timesheet = Timesheet::find($id);
        
        if (!$timesheet) {
            return response()->json([
                'success' => false,
                'error' => 'Feuille de temps non trouvée'
            ], 404);
        }
        
        // Préparer les données
        $timesheetData = [
            'id' => $timesheet->id,
            'date_timesheet' => $timesheet->date_timesheet ? $timesheet->date_timesheet->format('Y-m-d') : null,
            'utilisateur_id' => $timesheet->utilisateur_id,
            'dossier_id' => $timesheet->dossier_id,
            'categorie' => $timesheet->categorie,
            'type' => $timesheet->type,
            'quantite' => (float) $timesheet->quantite,
            'prix' => (float) $timesheet->prix,
            'total' => (float) $timesheet->total,
            'description' => $timesheet->description,
            'created_at' => $timesheet->created_at ? $timesheet->created_at->format('d/m/Y H:i') : null,
            'updated_at' => $timesheet->updated_at ? $timesheet->updated_at->format('d/m/Y H:i') : null,
        ];
        
        \Log::info('AJAX Response Data:', $timesheetData);
        
        return response()->json([
            'success' => true,
            'timesheet' => $timesheetData,
            'dossiers' => Dossier::all()->map(function($dossier) {
                return ['id' => $dossier->id, 'numero_dossier' => $dossier->numero_dossier];
            }),
            'categories' => Categorie::all()->map(function($categorie) {
                return ['id' => $categorie->id, 'nom' => $categorie->nom];
            }),
            'types' => Type::all()->map(function($type) {
                return ['id' => $type->id, 'nom' => $type->nom, 'categorie_id' => $type->categorie_id];
            }),
            'users' => User::where('is_active', true)->get()->map(function($user) {
                return ['id' => $user->id, 'name' => $user->name, 'fonction' => $user->fonction];
            })
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Erreur dans getTimesheetAjax', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'error' => 'Erreur interne: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Récupérer les détails d'une feuille de temps via AJAX
 */
public function getTimesheetDetailsAjax($id)
{
    \Log::info('AJAX Show Request for timesheet ID:', ['id' => $id]);
    
    if (!auth()->user()->hasPermission('view_timesheets')) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    try {
        // Trouver la feuille de temps avec ses relations
        $timesheet = Timesheet::with(['user', 'dossier', 'categorieRelation', 'typeRelation'])->find($id);
        
        if (!$timesheet) {
            return response()->json([
                'success' => false,
                'error' => 'Feuille de temps non trouvée'
            ], 404);
        }
        
        // Préparer les données pour la vue
        $timesheetData = [
            'id' => $timesheet->id,
            'date_timesheet' => $timesheet->date_timesheet ? $timesheet->date_timesheet->format('d/m/Y') : 'N/A',
            'user' => [
                'name' => $timesheet->user->name ?? 'N/A',
                'fonction' => $timesheet->user->fonction ?? null,
            ],
            'dossier' => $timesheet->dossier ? [
                'id' => $timesheet->dossier->id,
                'numero_dossier' => $timesheet->dossier->numero_dossier ?? 'N/A',
                'nom_dossier' => $timesheet->dossier->nom_dossier ?? null,
            ] : null,
            'quantite' => number_format($timesheet->quantite ?? 0, 2, ',', ' '),
            'prix' => number_format($timesheet->prix ?? 0, 2, ',', ' ') . ' DT',
            'total' => number_format($timesheet->total ?? 0, 2, ',', ' ') . ' DT',
            'categorie' => $timesheet->categorieRelation->nom ?? 'Non spécifiée',
            'type' => $timesheet->typeRelation->nom ?? 'Non spécifié',
            'description' => $timesheet->description ?? 'Aucune description fournie',
            'created_at' => $timesheet->created_at ? $timesheet->created_at->format('d/m/Y à H:i') : 'N/A',
            'updated_at' => $timesheet->updated_at ? $timesheet->updated_at->format('d/m/Y à H:i') : 'N/A',
            'file_path' => $timesheet->file_path,
            'file_name' => $timesheet->file_name,
        ];
        
        \Log::info('AJAX Show Response Data:', $timesheetData);
        
        return response()->json([
            'success' => true,
            'timesheet' => $timesheetData,
            'edit_url' => route('time-sheets.edit', $timesheet->id),
            'delete_url' => route('time-sheets.destroy', $timesheet->id),
            'has_edit_permission' => auth()->user()->hasPermission('edit_timesheets'),
            'has_delete_permission' => auth()->user()->hasPermission('delete_timesheets'),
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Erreur dans getTimesheetDetailsAjax', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'error' => 'Erreur interne: ' . $e->getMessage()
        ], 500);
    }
}
  public function update(Request $request, Timesheet $time_sheet)
{
    if (!auth()->user()->hasPermission('edit_timesheets')) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    $validated = $request->validate([
        'date_timesheet' => 'required|date',
        'utilisateur_id' => 'required|exists:users,id',
        'dossier_id' => 'nullable|exists:dossiers,id',
        'description' => 'required|string|max:1000',
        'categorie' => 'nullable|exists:categories,id',
        'type' => 'nullable|exists:types,id',
        'quantite' => 'required|numeric|min:0',
        'prix' => 'required|numeric|min:0',
        'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048', // max 2MB
    ]);

    try {
        // Calculer le total
        $validated['total'] = $validated['quantite'] * $validated['prix'];

        if($request->hasFile('file')){
            // Supprimer l'ancien fichier s'il existe
            if ($time_sheet->file_path) {
                Storage::disk('public')->delete($time_sheet->file_path);
            }
            
            $file = $request->file('file');
            $path = $file->store('timesheet_files', 'public');
            $validated['file_path'] = $path;
            $validated['file_name'] = $file->getClientOriginalName();
        }

        $time_sheet->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Feuille de temps mise à jour avec succès.',
            'redirect_url' => route('time-sheets.index')
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour de la feuille de temps: ' . $e->getMessage()
        ], 500);
    }
}

public function destroy(Timesheet $time_sheet)
{
    if (!auth()->user()->hasPermission('delete_timesheets')) {
            abort(403, 'Unauthorized action.');
        }
    
    $time_sheet->delete();

    

        // Delete File
        if ($time_sheet->file_path) {
            Storage::disk('public')->delete($time_sheet->file_path);
        }

    if (request()->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Feuille de temps supprimée avec succès.'
        ]);
    }

    return redirect()->route('time-sheets.index')
        ->with('success', 'Feuille de temps supprimée avec succès.');
}

    public function byDossier(Request $request, $dossierId): AnonymousResourceCollection
    {
        $timeSheets = TimeSheet::where('dossier_id', $dossierId)
            ->with(['user', 'categorieRelation', 'typeRelation'])
            ->paginate(10);
            
        return TimeSheetResource::collection($timeSheets);
    }

    public function byUser(Request $request, $userId): AnonymousResourceCollection
    {
        $timeSheets = TimeSheet::where('utilisateur_id', $userId)
            ->with(['dossier', 'categorieRelation', 'typeRelation'])
            ->paginate(10);
            
        return TimeSheetResource::collection($timeSheets);
    }

    public function report(Request $request): AnonymousResourceCollection
    {
        $query = TimeSheet::query();
        
        if ($request->has('date_debut')) {
            $query->where('date_timesheet', '>=', $request->date_debut);
        }
        
        if ($request->has('date_fin')) {
            $query->where('date_timesheet', '<=', $request->date_fin);
        }
        
        if ($request->has('utilisateur_id')) {
            $query->where('utilisateur_id', $request->utilisateur_id);
        }
        
        if ($request->has('dossier_id')) {
            $query->where('dossier_id', $request->dossier_id);
        }
        
        $timeSheets = $query->with(['user', 'dossier', 'categorieRelation', 'typeRelation'])->get();
        
        return TimeSheetResource::collection($timeSheets);
    }

    public function getTimeSheetData(TimeSheet $time_sheet)
{
    if(!auth()->user()->hasPermission('view_timesheets')){
        return response()->json([
            'success' => false,
            'error' => 'Unauthorized action.'
        ], 403);
    }

    // Charger les relations nécessaires
    $time_sheet->load(['dossier', 'user', 'categorieRelation', 'typeRelation']);

    // Retourner les données formatées
    return response()->json([
        'success' => true,
        'data' => [
            'id' => $time_sheet->id,
            'date_timesheet' => $time_sheet->date_timesheet,
            'description' => $time_sheet->description,
            'quantite' => $time_sheet->quantite,
            'prix' => $time_sheet->prix,
            'total' => $time_sheet->total,
            'dossier' => $time_sheet->dossier ? [
                'id' => $time_sheet->dossier->id,
                'numero_dossier' => $time_sheet->dossier->numero_dossier,
                'nom_dossier' => $time_sheet->dossier->nom_dossier,
            ] : null,
            'user' => $time_sheet->user ? [
                'id' => $time_sheet->user->id,
                'name' => $time_sheet->user->name,
                'email' => $time_sheet->user->email,
                'fonction' => $time_sheet->user->fonction,
            ] : null,
            'categorierelation' => $time_sheet->categorieRelation ? [
                'id' => $time_sheet->categorieRelation->id,
                'name' => $time_sheet->categorieRelation->nom,
            ] : null,
            'typeRelation' => $time_sheet->typeRelation ? [
                'id' => $time_sheet->typeRelation->id,
                'name' => $time_sheet->typeRelation->nom,
            ] : null,
            'created_at' => $time_sheet->created_at,
            'updated_at' => $time_sheet->updated_at,
        ]
    ]);
}
   public function getTimesheetsData(Request $request)
{
    $this->authorize('view_timesheets', Timesheet::class);
    
    if(auth()->user()->hasRole('admin')){
        $query = Timesheet::with([
            'user:id,name',
            'dossier:id,numero_dossier',
            'dossier.intervenants:id,identite_fr',
            'categorieRelation:id,nom',
            'typeRelation:id,nom'
        ])->select('time_sheets.*');
    }else{
    $query = Timesheet::with([
        'user:id,name',
        'dossier:id,numero_dossier',
        'dossier.intervenants:id,identite_fr',
        'categorieRelation:id,nom',
        'typeRelation:id,nom'
    ])->where('utilisateur_id', auth()->id())
    ->select('time_sheets.*');
    }

    // Filtre par date
    if ($request->has('date') && !empty($request->date)) {
        $query->whereDate('date_timesheet', $request->date);
    }

    // Filtre par mois
    if ($request->has('month') && !empty($request->month)) {
        $query->whereMonth('date_timesheet', $request->month);
    }

    // Filtre par année
    if ($request->has('year') && !empty($request->year)) {
        $query->whereYear('date_timesheet', $request->year);
    }

    // Filtre par mois et année combinés
    if ($request->has('month') && !empty($request->month) && $request->has('year') && !empty($request->year)) {
        $query->whereYear('date_timesheet', $request->year)
              ->whereMonth('date_timesheet', $request->month);
    }

    // Filtre par utilisateur
    if ($request->has('utilisateur_id') && !empty($request->utilisateur_id)) {
        $query->where('utilisateur_id', $request->utilisateur_id);
    }

    // Filtre par dossier
    if ($request->has('dossier_id') && !empty($request->dossier_id)) {
        $query->where('dossier_id', $request->dossier_id);
    }

    // Filtre par catégorie
    if ($request->has('categorie_id') && !empty($request->categorie_id)) {
        $query->where('categorie', $request->categorie_id);
    }

    // Filtre par type
    if ($request->has('type_id') && !empty($request->type_id)) {
        $query->where('type', $request->type_id);
    }

    // Filtre par description
    if ($request->has('description') && !empty($request->description)) {
        $query->where('description', 'LIKE', '%' . $request->description . '%');
    }

    // Filtre par total minimum
    if ($request->has('min_total') && !empty($request->min_total)) {
        $query->where('total', '>=', $request->min_total);
    }

    // Filtre par total maximum
    if ($request->has('max_total') && !empty($request->max_total)) {
        $query->where('total', '<=', $request->max_total);
    }

    // Recherche globale DataTables
    if ($request->has('search') && !empty($request->search['value'])) {
        $search = $request->search['value'];
        $query->where(function ($q) use ($search) {
            $q->where('description', 'LIKE', "%{$search}%")
              ->orWhereHas('user', function ($q) use ($search) {
                  $q->where('name', 'LIKE', "%{$search}%");
              })
              ->orWhereHas('dossier', function ($q) use ($search) {
                  $q->where('numero_dossier', 'LIKE', "%{$search}%")
                    ->orWhereHas('intervenants', function ($q) use ($search) {
                        $q->where('identite_fr', 'LIKE', "%{$search}%");
                    });
              });
        });
    }

    return DataTables::eloquent($query)
        ->addColumn('action', function (Timesheet $timesheet) {
            $actions = '<div class="btn-group">';
            
            // Bouton Voir
            if (auth()->user()->hasPermission('view_timesheets')) {
                $actions .= '<a href="' . route('time-sheets.show', $timesheet) . '" class="btn btn-info btn-sm" title="Voir">
                    <i class="fas fa-eye"></i>
                </a>';
            }
            
            // Bouton Modifier
            if (auth()->user()->hasPermission('edit_timesheets')) {
                $actions .= '<a href="' . route('time-sheets.edit', $timesheet) . '" class="btn btn-primary btn-sm" title="Modifier">
                    <i class="fas fa-edit"></i>
                </a>';
            }
            
            // Bouton Supprimer
            if (auth()->user()->hasPermission('delete_timesheets')) {
                $actions .= '<button type="button" class="btn btn-danger btn-sm delete-btn" data-id="' . $timesheet->id . '" title="Supprimer">
                    <i class="fas fa-trash"></i>
                </button>';
            }
            
            $actions .= '</div>';
            return $actions;
        })
        ->editColumn('date_timesheet', function (Timesheet $timesheet) {
            if (!$timesheet->date_timesheet) {
                return '-';
            }
            
            // Assurer que c'est un objet Carbon
            $date = $timesheet->date_timesheet;
            if (is_string($date)) {
                $date = \Carbon\Carbon::parse($date);
            }
            
            return $date->format('d/m/Y');
        })
        ->editColumn('quantite', function (Timesheet $timesheet) {
            return number_format($timesheet->quantite, 2, ',', ' ');
        })
        ->editColumn('prix', function (Timesheet $timesheet) {
            // Formater avec espace comme séparateur de milliers
            return number_format($timesheet->prix, 2, ',', ' ') . ' DT';
        })
        ->editColumn('total', function (Timesheet $timesheet) {
            // Formater avec espace comme séparateur de milliers
            return number_format($timesheet->total, 2, ',', ' ') . ' DT';
        })
        ->editColumn('description', function (Timesheet $timesheet) {
            return $timesheet->description ? 
                (strlen($timesheet->description) > 50 ? 
                 substr($timesheet->description, 0, 50) . '...' : 
                 $timesheet->description) : '-';
        })
        ->rawColumns(['action', 'description'])
        ->toJson();
}

}