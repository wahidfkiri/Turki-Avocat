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
            abort(403, 'Unauthorized action.');
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

    // Calculer le total
    $validated['total'] = $validated['quantite'] * $validated['prix'];

        if($request->hasFile('file')){
            $file = $request->file('file');
            $path = $file->store('timesheet_files', 'public');
            $validated['file_path'] = $path;
            $validated['file_name'] = $file->getClientOriginalName();
        }

    Timesheet::create($validated);

    return redirect()->route('time-sheets.index')
        ->with('success', 'Feuille de temps créée avec succès.');
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
   public function update(Request $request, Timesheet $time_sheet)
{
    if (!auth()->user()->hasPermission('edit_timesheets')) {
            abort(403, 'Unauthorized action.');
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

    
        if($request->hasFile('file')){
            $file = $request->file('file');
            $path = $file->store('agenda_files', 'public');
            $validated['file_path'] = $path;
            $validated['file_name'] = $file->getClientOriginalName();
        }

    // Calculer le total
    $validated['total'] = $validated['quantite'] * $validated['prix'];

    $time_sheet->update($validated);

    return redirect()->route('time-sheets.index')
        ->with('success', 'Feuille de temps mise à jour avec succès.');
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

   public function getTimesheetsData(Request $request)
{
    $this->authorize('view_timesheets', Timesheet::class);

    $query = Timesheet::with([
        'user:id,name',
        'dossier:id,numero_dossier',
        'dossier.intervenants:id,identite_fr',
        'categorieRelation:id,nom',
        'typeRelation:id,nom'
    ])->select('time_sheets.*');

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