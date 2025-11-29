<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Models\Dossier;
use App\Models\Intervenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\EmailTrait;
use App\Notifications\TaskAssignedNotification;

class TaskController extends Controller
{
    use EmailTrait;
    /**
     * Get tasks data for DataTable
     */
    public function getTasksData(Request $request)
    {
        $this->authorize('view_tasks', Task::class);
    if(auth()->user()->hasRole('admin')){
        $query = Task::with([
            'dossier:id,numero_dossier,nom_dossier',
            'intervenant:id,identite_fr',
            'user:id,name'
        ])->select('tasks.*');
    }else{
        $query = Task::with([
            'dossier:id,numero_dossier,nom_dossier',
            'intervenant:id,identite_fr',
            'user:id,name'
        ])->where('utilisateur_id', auth()->id())->select('tasks.*');
    }

        // Filtre par titre
        if ($request->has('titre') && !empty($request->titre)) {
            $query->where('titre', 'LIKE', '%' . $request->titre . '%');
        }

        // Filtre par priorité
        if ($request->has('priorite') && !empty($request->priorite)) {
            $query->where('priorite', $request->priorite);
        }

        // Filtre par statut
        if ($request->has('statut') && !empty($request->statut)) {
            if($request->statut == 'all'){
                $query->where('statut', '!=', 'terminee');
            } else {
                $query->where('statut', $request->statut);
            }
        }

        // Filtre par utilisateur
        if ($request->has('utilisateur_id') && !empty($request->utilisateur_id)) {
            $query->where('utilisateur_id', $request->utilisateur_id);
        }

        // Filtre par dossier
        if ($request->has('dossier_id') && !empty($request->dossier_id)) {
            $query->where('dossier_id', $request->dossier_id);
        }

        // Filtre par intervenant
        if ($request->has('intervenant_id') && !empty($request->intervenant_id)) {
            $query->where('intervenant_id', $request->intervenant_id);
        }

        // Filtre par date début
        if ($request->has('date_debut') && !empty($request->date_debut)) {
            $query->whereDate('date_debut', '>=', $request->date_debut);
        }

        // Filtre par date fin
        if ($request->has('date_fin') && !empty($request->date_fin)) {
            $query->whereDate('date_fin', '<=', $request->date_fin);
        }

        // Recherche globale DataTables
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('note', 'LIKE', "%{$search}%")
                  ->orWhereHas('dossier', function ($q) use ($search) {
                      $q->where('numero_dossier', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('intervenant', function ($q) use ($search) {
                      $q->where('identite_fr', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        return DataTables::eloquent($query)
            ->addColumn('action', function (Task $task) {
                $actions = '<div class="btn-group">';
                
                // Bouton Voir
                if (auth()->user()->hasPermission('view_tasks')) {
                    $actions .= '<a href="' . route('tasks.show', $task) . '" class="btn btn-info btn-sm" title="Voir">
                        <i class="fas fa-eye"></i>
                    </a>';
                }
                
                // Bouton Modifier
                if (auth()->user()->hasPermission('edit_tasks')) {
                    $actions .= '<a href="' . route('tasks.edit', $task) . '" class="btn btn-primary btn-sm" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>';
                }
                
                // Bouton Supprimer
                if (auth()->user()->hasPermission('delete_tasks')) {
                    $actions .= '<button type="button" class="btn btn-danger btn-sm delete-btn" data-id="' . $task->id . '" title="Supprimer">
                        <i class="fas fa-trash"></i>
                    </button>';
                }

                // Button Download
                if (auth()->user()->hasPermission('view_tasks') && $task->file_path) {
                    if (file_exists(public_path($task->file_path))) {
                        $actions .= '<a href="' . route('tasks.download', $task) . '" class="btn btn-danger btn-sm" title="Télécharger">
                            <i class="fas fa-download"></i>
                        </a>';
                    }
                }

                $actions .= '</div>';
                return $actions;
            })
            ->editColumn('date_debut', function (Task $task) {
                return $task->date_debut ? \Carbon\Carbon::parse($task->date_debut)->format('d/m/Y') : '-';
            })
            ->editColumn('date_fin', function (Task $task) {
                return $task->date_fin ? \Carbon\Carbon::parse($task->date_fin)->format('d/m/Y') : '-';
            })
            ->editColumn('description', function (Task $task) {
                return $task->description ? 
                    (strlen($task->description) > 50 ? 
                     substr($task->description, 0, 50) . '...' : 
                     $task->description) : '-';
            })
            ->rawColumns(['action', 'priorite', 'statut'])
            ->toJson();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(!auth()->user()->hasPermission('view_tasks')){
         abort(403, 'Unauthorized action.');
        }
        
        $users = User::where('is_active', true)->get();
        $dossiers = Dossier::all();
        $intervenants = Intervenant::all();
        
        return view('tasks.index', compact('users', 'dossiers', 'intervenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(!auth()->user()->hasPermission('create_tasks')){
            return abort(403, 'Unauthorized action.');
        }

        if(auth()->user()->hasRole('admin')){
        $dossiers = Dossier::all();
        }else{
        $dossiers = Dossier::whereHas('users', function($q){
            $q->where('user_id', auth()->id());
        })->get();
        }
        $intervenants = Intervenant::all();
        $users = User::where('is_active', 1)->get();
        
        return view('tasks.create', compact('users', 'dossiers', 'intervenants'));
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    if(!auth()->user()->hasPermission('create_tasks')){
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }
        return abort(403, 'Unauthorized action.');
    }
    
    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'description' => 'nullable|string',
        'date_debut' => 'nullable|date',
        'date_fin' => 'nullable|date|after_or_equal:date_debut',
        'priorite' => 'required|in:basse,normale,haute,urgente',
        'statut' => 'required|in:a_faire,en_cours,terminee,en_retard',
        'dossier_id' => 'nullable|exists:dossiers,id',
        'intervenant_id' => 'nullable|exists:intervenants,id',
        'utilisateur_id' => 'required|exists:users,id',
        'note' => 'nullable|string',
        'file' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png,xlsx,xls|max:10240', // 10MB max
    ]);

    // Create the task with basic data
    $taskData = $validated;
    
    // Handle file upload before creating the task
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filePath = $file->store('tasks/files', 'public');
        
        // Add file data to task data
        $taskData['file_path'] = $filePath;
        $taskData['file_name'] = $file->getClientOriginalName();
    }

    // Create the task with all data including file info
    $task = Task::create($taskData);

    // Create notification for this task
    if($task->utilisateur_id){
        \App\Models\Notification::create([
            'task_id' => $task->id,
            'user_id' => $task->utilisateur_id,
            'title' => 'Nouvelle Tache',
            'message' => 'Nouvelle tache a été crée',
            'is_read' => 0
        ]);
    }

    if(auth()->user()->hasRole('admin')){
       $user = User::find($task->utilisateur_id);
       $user->notify(new TaskAssignedNotification($task));
    }

    // Réponse pour requête AJAX
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Tâche créée avec succès.',
            'task_id' => $task->id
        ]);
    }

    // Réponse normale pour les requêtes non-AJAX
    return redirect()->route('tasks.index')
        ->with('success', 'Tâche créée avec succès.');
}

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        if(!auth()->user()->hasPermission('view_tasks')){
         abort(403, 'Unauthorized action.');
        }
        
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
public function edit(Task $task)
{
    if(!auth()->user()->hasPermission('edit_tasks')){
        if (request()->ajax()) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }
        return abort(403, 'Unauthorized action.');
    }
    
    // Charger les relations nécessaires pour le modal
    $task->load(['dossier', 'intervenant', 'user']);
    
    // Si c'est une requête AJAX (pour le modal)
    if (request()->ajax() || request()->wantsJson()) {
        return response()->json([
            'id' => $task->id,
            'titre' => $task->titre,
            'description' => $task->description,
            'date_debut' => $task->date_debut,
            'date_fin' => $task->date_fin,
            'priorite' => $task->priorite,
            'statut' => $task->statut,
            'dossier_id' => $task->dossier_id,
            'intervenant_id' => $task->intervenant_id,
            'utilisateur_id' => $task->utilisateur_id,
            'note' => $task->note,
            'file_path' => $task->file_path,
            'file_name' => $task->file_name,
            'created_at' => $task->created_at,
            'updated_at' => $task->updated_at,
            'dossier' => $task->dossier ? [
                'id' => $task->dossier->id,
                'numero_dossier' => $task->dossier->numero_dossier,
                'nom_dossier' => $task->dossier->nom_dossier,
            ] : null,
            'intervenant' => $task->intervenant ? [
                'id' => $task->intervenant->id,
                'identite_fr' => $task->intervenant->identite_fr,
            ] : null,
            'user' => $task->user ? [
                'id' => $task->user->id,
                'name' => $task->user->name,
            ] : null,
        ]);
    }
    
    // Pour les requêtes normales (page d'édition complète)
    $users = User::where('is_active', true)->get();
    $dossiers = Dossier::all();
    $intervenants = Intervenant::all();
    
    return view('tasks.edit', compact('task', 'users', 'dossiers', 'intervenants'));
}


public function getTaskData(Task $task)
{
    if(!auth()->user()->hasPermission('view_tasks')){
        return response()->json([
            'success' => false,
            'error' => 'Unauthorized action.'
        ], 403);
    }

    // Charger les relations nécessaires
    $task->load(['dossier', 'intervenant', 'user']);

    // Retourner les données formatées
    return response()->json([
        'success' => true,
        'data' => [
            'id' => $task->id,
            'titre' => $task->titre,
            'description' => $task->description,
            'date_debut' => $task->date_debut,
            'date_fin' => $task->date_fin,
            'priorite' => $task->priorite,
            'statut' => $task->statut,
            'dossier_id' => $task->dossier_id,
            'intervenant_id' => $task->intervenant_id,
            'utilisateur_id' => $task->utilisateur_id,
            'note' => $task->note,
            'file_path' => $task->file_path,
            'file_name' => $task->file_name,
            'created_at' => $task->created_at,
            'updated_at' => $task->updated_at,
            'dossier' => $task->dossier ? [
                'id' => $task->dossier->id,
                'numero_dossier' => $task->dossier->numero_dossier,
                'nom_dossier' => $task->dossier->nom_dossier,
            ] : null,
            'intervenant' => $task->intervenant ? [
                'id' => $task->intervenant->id,
                'identite_fr' => $task->intervenant->identite_fr,
            ] : null,
            'user' => $task->user ? [
                'id' => $task->user->id,
                'name' => $task->user->name,
                'email' => $task->user->email,
                'fonction' => $task->user->fonction,
            ] : null,
        ]
    ]);
}
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
{
    if(!auth()->user()->hasPermission('edit_tasks')){
        return abort(403, 'Unauthorized action.');
    }
    
    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'description' => 'nullable|string',
        'date_debut' => 'nullable|date',
        'date_fin' => 'nullable|date|after_or_equal:date_debut',
        'priorite' => 'required|in:basse,normale,haute,urgente',
        'statut' => 'required|in:a_faire,en_cours,terminee,en_retard',
        'dossier_id' => 'nullable|exists:dossiers,id',
        'intervenant_id' => 'nullable|exists:intervenants,id',
        'utilisateur_id' => 'required|exists:users,id',
        'note' => 'nullable|string',
        'file' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png,xlsx,xls|max:10240', // 10MB max
        'remove_file' => 'nullable|boolean',
    ]);

    $updateData = $validated;

    // Handle file removal if requested
    if ($request->has('remove_file') && $request->boolean('remove_file')) {
        // Delete the physical file from storage
        if ($task->file_path && Storage::disk('public')->exists($task->file_path)) {
            Storage::disk('public')->delete($task->file_path);
        }
        
        // Remove file data from database
        $updateData['file_path'] = null;
        $updateData['file_name'] = null;
    }

    // Handle new file upload
    if ($request->hasFile('file')) {
        // Delete old file if exists
        if ($task->file_path && Storage::disk('public')->exists($task->file_path)) {
            Storage::disk('public')->delete($task->file_path);
        }

        // Store new file
        $file = $request->file('file');
        $filePath = $file->store('tasks/files', 'public');
        
        // Add file data to update data
        $updateData['file_path'] = $filePath;
        $updateData['file_name'] = $file->getClientOriginalName();
    }

    // Remove the file and remove_file keys from update data as they're not in the database
    unset($updateData['file']);
    unset($updateData['remove_file']);

    $task->update($updateData);

    // Réponse pour requête AJAX
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Tâche modifiée avec succès.',
            'task_id' => $task->id
        ]);
    }

    return redirect()->route('tasks.index')
        ->with('success', 'Tâche mise à jour avec succès.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete_tasks', $task);
        
        $task->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tâche supprimée avec succès.'
            ]);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Tâche supprimée avec succès.');
    }

    // public function downloadFile($taskId)
    // {
    //     $task = Task::findOrFail($taskId);
    //    if(!auth()->user()->hasPermission('view_tasks')){
    //         return abort(403, 'Unauthorized action.');
    //     }

    //     if ($task->file_path && Storage::disk('public')->exists($task->file_path)) {
    //         return Storage::disk('public')->download($task->file_path, $task->file_name);
    //     }

    //     return redirect()->back()->with('error', 'Fichier non trouvé.');
    // }

    public function downloadFile($id)
    {
        $task = Task::findOrFail($id);

        if (!auth()->user()->hasPermission('view_tasks')) {
            abort(403, 'Unauthorized action.');
        }

        if ($task->file_path && Storage::disk('public')->exists($task->file_path)) {
            $path = Storage::disk('public')->path($task->file_path);
            $mime = Storage::disk('public')->mimeType($task->file_path) ?? 'application/octet-stream';
            $headers = [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . ($task->file_name ?? basename($path)) . '"'
            ];

            return response()->file($path, $headers);
        }

        return redirect()->back()->with('error', 'Fichier non trouvé.');
    }

    public function displayFile($id)
    {
        $task = Task::findOrFail($id);

        if (!auth()->user()->hasPermission('view_tasks')) {
            abort(403, 'Unauthorized action.');
        }

        if ($task->file_path && Storage::disk('public')->exists($task->file_path)) {
            $path = Storage::disk('public')->path($task->file_path);
            $mime = Storage::disk('public')->mimeType($task->file_path) ?? 'application/octet-stream';
            $filename = $task->file_name ?? basename($path);

            $headers = [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ];

            return response()->file($path, $headers);
        }

        return redirect()->back()->with('error', 'Fichier non trouvé.');
    }
}