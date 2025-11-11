<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDossierRequest;
use App\Http\Requests\UpdateDossierRequest;
use App\Http\Resources\DossierResource;
use App\Models\Dossier;
use App\Models\Domaine;
use App\Models\SousDomaine;
use App\Models\Intervenant;
use App\Models\User;
use App\Models\Fichier;
use App\Models\Categorie;
use App\Models\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;


class DossierController extends Controller
{
    public function index(Request $request)
{
        if (!auth()->user()->hasPermission('view_dossiers')) {
            abort(403, 'Unauthorized action.');
        }
    if(auth()->user()->hasRole('admin')){
        $dossiers = Dossier::with(['domaine', 'sousDomaine', 'users', 'intervenants'])->where('archive', false)->paginate(10);
    }else{
     $dossiers = Dossier::with(['domaine', 'sousDomaine', 'users', 'intervenants'])
    ->whereHas('users', function($query) {
        $query->where('users.id', auth()->id());
    })
    ->where('archive', false)
    ->get();
}
    $domaines = Domaine::all(); // Ajouter cette ligne
     if ($request->ajax()) {
        // DataTables AJAX response
        return $this->getDossiersData($request);
    }
    return view('dossiers.index', compact('domaines'));
}

    public function create()
{
    $this->authorize('create_dossiers', Dossier::class);
   $domaines = Domaine::with(['sousDomaines' => function($query) {
            $query->orderBy('nom');
        }])->get();
     $intervenants = Intervenant::where('archive', false)
            ->orderBy('identite_fr')
            ->get();
            
        $users = User::where('is_active', true)
            ->orderBy('name')
            ->get();
        $dossiers = Dossier::orderBy('numero_dossier')->get();

    return view('dossiers.create', compact('domaines', 'intervenants', 'users', 'dossiers'));
}

 public function edit(Dossier $dossier)
{
   
        if (!auth()->user()->hasPermission('edit_dossiers')) {
            abort(403, 'Unauthorized action.');
        }
   $domaines = Domaine::with(['sousDomaines' => function($query) {
            $query->orderBy('nom');
        }])->get();
     $intervenants = Intervenant::where('archive', false)
            ->orderBy('identite_fr')
            ->get();
            $sousDomaines = SousDomaine::where('domaine_id', $dossier->domaine_id)
            ->orderBy('nom')
            ->get();
        $users = User::where('is_active', true)
            ->orderBy('name')
            ->get();
        $dossiers = Dossier::where('id', '!=', $dossier->id)
            ->orderBy('numero_dossier')
            ->get();

    return view('dossiers.edit', compact('domaines', 'intervenants', 'users', 'dossier', 'sousDomaines', 'dossiers'));
}



    public function store(StoreDossierRequest $request)
{
    if(!auth()->user()->hasPermission('create_dossiers')) {
        abort(403, 'Unauthorized action.');
    }
    
    // Démarrer une transaction de base de données
    DB::beginTransaction();

    try {
        // Valider les données de base du dossier
        $validatedData = $request->validated();
        
        // Créer le dossier
        $dossier = Dossier::create($validatedData);
        if($request->has('archive')){
            $dossier->archive = true;
            $dossier->date_archive = now();
            $dossier->save();
        }

        // Créer un dossier dans Storage
        $storagePath = 'dossiers/' . $dossier->numero_dossier . '-' . $dossier->id;
        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory($storagePath);
        
        // Attacher le client principal comme intervenant
        if ($request->has('client_id')) {
            $dossier->intervenants()->attach($request->client_id, [
                'role' => 'client'
            ]);
        }
        

          // Gestion des intervenants liés avec les deux relations
        if ($request->has('linked_intervenants')) {
            $intervenantsLiesFrom = []; // Relations de cet intervenant vers les autres
            $intervenantsLiesTo = [];   // Relations des autres intervenants vers celui-ci
            
            foreach ($request->linked_intervenants as $linkedIntervenant) {
                if (!empty($linkedIntervenant['intervenant_id']) && 
                    !empty($linkedIntervenant['role'])) {
                    
                    // Relation de cet intervenant vers l'intervenant lié
                    $intervenantsLiesFrom[$linkedIntervenant['intervenant_id']] = [
                        'intervenant_id' => $linkedIntervenant['intervenant_id'],
                        'dossier_id' => $dossier->id,
                        'role' => $linkedIntervenant['role'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    
                }
            }
            
            if (!empty($intervenantsLiesFrom)) {
                // Attacher les relations de cet intervenant vers les autres
                $dossier->intervenants()->attach($intervenantsLiesFrom);
                
                
            }
        }

        
        // Gestion des dossiers liés 
        if ($request->has('linked_dossiers')) {
            $DossiersLiesFrom = []; // Relations de cet dossier 
            foreach ($request->linked_dossiers as $linkedDossier) {
                if (!empty($linkedDossier['dossier_id']) && 
                    !empty($linkedDossier['relation'])) {
                    
                    // Relation de cet intervenant vers l'intervenant lié
                    $DossiersLiesFrom[$linkedDossier['dossier_id']] = [
                        'dossier_id' => $dossier->id,
                        'dossier_lie_id' => $linkedDossier['dossier_id'],
                        'relation' => $linkedDossier['relation'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    
                }
            }
            
            if (!empty($DossiersLiesFrom)) {
                // Attacher les relations de cet intervenant vers les autres
                $dossier->dossiersLies()->attach($DossiersLiesFrom);
            }
        }
        
        // Attacher l'avocat responsable comme utilisateur
        if ($request->has('avocat_id')) {
            $dossier->users()->attach($request->avocat_id, [
                'role' => 'avocat',
                'ordre' => $request->ordre ?? 1
            ]);
        }
        
        // Attacher l'équipe supplémentaire
        // if ($request->has('equipe_supplementaire')) {
        //     foreach ($request->equipe_supplementaire as $userId) {
        //         $user = User::find($userId);
        //         $role = $user->fonction; // ou une logique plus spécifique
                
        //         $dossier->users()->attach($userId, [
        //             'role' => $role,
        //             'ordre' => $request->ordre ?? 2
        //         ]);
        //     }
        // }

        if ($request->has('linked_utilisateurs')) {
            $UtilisateursLiesFrom = []; // Relations de cet dossier 
            foreach ($request->linked_utilisateurs as $linkedUser) {
                if (!empty($linkedUser['user_id']) && 
                    !empty($linkedUser['role'])) {
                    
                    // Relation de cet intervenant vers l'intervenant lié
                    $UtilisateursLiesFrom[$linkedUser['user_id']] = [
                        'dossier_id' => $dossier->id,
                        'user_id' => $linkedUser['user_id'],
                        'role' => $linkedUser['role'],
                        'ordre' => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    
                }
            }
            
            if (!empty($UtilisateursLiesFrom)) {
                // Attacher les relations de cet intervenant vers les autres
                $dossier->users()->attach($UtilisateursLiesFrom);
            }
        }
        
        // Gérer l'upload des fichiers
        $uploadedFiles = [];
        if ($request->hasFile('fichiers')) {
            foreach ($request->file('fichiers') as $file) {
                if ($file->isValid()) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('dossiers/' . $dossier->numero_dossier, $fileName, 'public');
                    
                    $fichier = Fichier::create([
                        'type_module' => 'facture',
                        'module_id' => $dossier->id,
                        'nom_fichier' => $file->getClientOriginalName(),
                        'chemin_fichier' => $filePath,
                        'type_mime' => $file->getMimeType(),
                        'taille' => $file->getSize(),
                        'description' => 'Fichier joint au dossier'
                    ]);
                    
                    $uploadedFiles[] = $filePath;
                }
            }
        }
        
        // Valider que tout s'est bien passé avant de committer
        DB::commit();
        
        // Redirection avec message de succès
        if($request->ajax()){
            return response()->json([
                'message' => 'Dossier créé avec succès.'
            ], 201);
        }
        return redirect()->route('dossiers.index')
            ->with('success', 'Dossier créé avec succès.');
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        if (isset($uploadedFiles) && !empty($uploadedFiles)) {
            foreach ($uploadedFiles as $filePath) {
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
        }
        
        \Log::error('Erreur création dossier: ' . $e->getMessage(), [
            'exception' => $e,
            'request_data' => $request->except(['fichiers']) // Exclure les fichiers pour la sécurité
        ]);
        if($request->ajax()){
            return response()->json([
                'error' => 'Erreur lors de la création du dossier. Veuillez réessayer.'
            ], 500);
        }
        return redirect()->back()
            ->withInput()
            ->withErrors(['error' => 'Erreur lors de la création du dossier. Veuillez réessayer.']);
    }
}

    public function show(Dossier $dossier)
    {
        if (!auth()->user()->hasPermission('view_dossiers')) {
            abort(403, 'Unauthorized action.');
        }
        $users = User::where('is_active', true)
            ->orderBy('name')
            ->get();
        $intervenants = Intervenant::orderBy('identite_fr')->get();
        $categories = \App\Models\Categorie::all();
        $types = \App\Models\Type::all();
        return view('dossiers.show', compact('dossier', 'users', 'intervenants', 'categories', 'types'));
    }
public function update(UpdateDossierRequest $request, Dossier $dossier)
{
    if(!auth()->user()->hasPermission('edit_dossiers')) {
        abort(403, 'Unauthorized action.');
    }
    
    $validatedData = $request->validated();
    
    // Mettre à jour le dossier
    $dossier->update($validatedData);
    
    // Valider les données de base du dossier
    if($request->has('conseil')){
        $dossier->conseil = true;
    }else{
        $dossier->conseil = false;
    }
    if($request->has('contentieux')){
        $dossier->contentieux = true;
    }else{
        $dossier->contentieux = false;
    }
    $dossier->save();
    // Synchroniser le client principal comme intervenant
    if ($request->has('client_id')) {
        // Supprimer l'ancien client et ajouter le nouveau
        $dossier->intervenants()->wherePivot('role', 'client')->detach();
        $dossier->intervenants()->attach($request->client_id, [
            'role' => 'client'
        ]);
    } else {
        // Si aucun client n'est sélectionné, supprimer l'ancien client
        $dossier->intervenants()->wherePivot('role', 'client')->detach();
    }
    
    if ($request->has('linked_intervenants')) {
            foreach ($request->linked_intervenants as $linkedIntervenant) {
                if (!empty($linkedIntervenant['intervenant_id']) && 
                    !empty($linkedIntervenant['role'])) {
                    
                    // Relation de cet intervenant vers l'intervenant lié
                    $intervenantsLiesFrom[$linkedIntervenant['intervenant_id']] = [
                        'intervenant_id' => $linkedIntervenant['intervenant_id'],
                        'dossier_id' => $dossier->id,
                        'role' => $linkedIntervenant['role'],
                        'updated_at' => now()
                    ];
                }
            }
        }

       // dd($intervenantsLiesFrom);die;
        // Synchroniser les relations de cet intervenant vers les autres
        foreach(\DB::table('dossier_intervenant')->where('dossier_id', $dossier->id)->get() as $dossier_inter){
            //dd($dossier_inter);die;
            \DB::table('dossier_intervenant')->where('id', $dossier_inter->id)->delete();
        }
       if (!empty($intervenantsLiesFrom)) {
                // Attacher les relations de cet intervenant vers les autres
                $dossier->intervenants()->attach($intervenantsLiesFrom);
            }


    
        // Gestion des dossiers liés avec les deux relations
        $dossiersLiesFrom = []; // Relations de cet intervenant vers les autres
        
        if ($request->has('linked_dossiers')) {
            foreach ($request->linked_dossiers as $linkedDossier) {
                if (!empty($linkedDossier['dossier_id']) && 
                    !empty($linkedDossier['relation'])) {
                    
                    // Relation de cet intervenant vers l'intervenant lié
                    $dossiersLiesFrom[$linkedDossier['dossier_id']] = [
                        'dossier_id' => $dossier->id,
                        'dossier_lie_id' => $linkedDossier['dossier_id'],
                        'relation' => $linkedDossier['relation'],
                        'updated_at' => now()
                    ];
                }
            }
        }
        
        // Synchroniser les relations de cet dossier vers les autres
        $dossier->dossiersLies()->sync($dossiersLiesFrom);
        
        
        // Supprimer les relations qui ne sont plus présentes
        $currentLinkedIds = array_keys($dossiersLiesFrom);
        $allLinkedDossiers = $dossier->DossiersLies()->pluck('dossier_id')->toArray();
        
        $dossiersToDetach = array_diff($allLinkedDossiers, $currentLinkedIds);
        
        foreach ($dossiersToDetach as $dossierIdToDetach) {
            // Détacher de cet dossier
            $dossier->DossiersLies()->detach($dossierIdToDetach);
            
            // Détacher la relation inverse
            $dossierToDetach = Dossier::find($dossierIdToDetach);
            if ($dossierToDetach) {
                $dossierToDetach->DossiersLies()->detach($dossier->id);
            }
        }

        // Ancien format de gestion (pour compatibilité) - à supprimer éventuellement
        if ($request->has('dossiers_lies')) {
            $intervenantsLiesOld = [];
            foreach ($request->dossiers_lies as $intervenantLieId) {
                $intervenantsLiesOld[$intervenantLieId] = [
                    'relation' => 'représente',
                    'updated_at' => now()
                ];
            }
            $dossier->DossiersLies()->sync($intervenantsLiesOld);
        } elseif (!$request->has('linked_dossiers')) {
            // Si aucun format n'est utilisé, détacher toutes les relations
            $dossier->dossiersLies()->detach();
        }



        // Utilisateurs liés 
        // Gestion des utilisateurs liés 
        $utilisateursLiesFrom = []; // Relations de cet intervenant vers les autres
        
        if ($request->has('linked_utilisateurs')) {
            foreach ($request->linked_utilisateurs as $linkedUtilisateur) {
                if (!empty($linkedUtilisateur['user_id']) && 
                    !empty($linkedUtilisateur['role'])) {
                    
                    // Relation de cet utilisateur 
                    $utilisateursLiesFrom[$linkedUtilisateur['user_id']] = [
                        'dossier_id' => $dossier->id,
                        'user_id' => $linkedUtilisateur['user_id'],
                        'role' => $linkedUtilisateur['role'],
                        'ordre' => 1,
                        'updated_at' => now()
                    ];
                }
            }
        }
        
        // Synchroniser les relations de cet dossier vers les autres
        $dossier->users()->sync($utilisateursLiesFrom);
        
        
        // Supprimer les relations qui ne sont plus présentes
        $currentLinkedUsersIds = array_keys($utilisateursLiesFrom);
        $allLinkedUsers = $dossier->users()->pluck('dossier_id')->toArray();
        
        $usersToDetach = array_diff($allLinkedUsers, $currentLinkedUsersIds);
        
        foreach ($usersToDetach as $userIdToDetach) {
            // Détacher de cet dossier
            $dossier->users()->detach($userIdToDetach);
            
            // Détacher la relation inverse
            $userToDetach = Dossier::find($userIdToDetach);
            if ($userToDetach) {
                $userToDetach->users()->detach($dossier->id);
            }
        }

        // Ancien format de gestion (pour compatibilité) - à supprimer éventuellement
        if ($request->has('users_lies')) {
            $usersLiesOld = [];
            foreach ($request->users_lies as $userLieId) {
                $usersLiesOld[$userLieId] = [
                    'relation' => 'représente',
                    'updated_at' => now()
                ];
            }
            $dossier->users()->sync($usersLiesOld);
        } elseif (!$request->has('linked_utilisateurs')) {
            // Si aucun format n'est utilisé, détacher toutes les relations
            $dossier->users()->detach();
        }
    

    
    // // Synchroniser l'équipe supplémentaire
    // if ($request->has('equipe_supplementaire')) {
    //     // Récupérer les IDs des utilisateurs actuels (sauf l'avocat responsable)
    //     $currentTeam = $dossier->users()
    //         ->wherePivot('role', '!=', 'avocat')
    //         ->pluck('users.id')
    //         ->toArray();
        
    //     // Détacher les utilisateurs supprimés
    //     $usersToDetach = array_diff($currentTeam, $request->equipe_supplementaire);
    //     $dossier->users()->detach($usersToDetach);
        
    //     // Attacher les nouveaux utilisateurs
    //     foreach ($request->equipe_supplementaire as $userId) {
    //         if (!in_array($userId, $currentTeam)) {
    //             $user = User::find($userId);
    //             $role = $user->fonction; // ou une logique plus spécifique
                
    //             $dossier->users()->attach($userId, [
    //                 'role' => $role,
    //                 'ordre' => $request->ordre ?? 2
    //             ]);
    //         }
    //     }
    // } else {
    //     // Si aucune équipe supplémentaire n'est sélectionnée, supprimer toute l'équipe sauf l'avocat
    //     $dossier->users()->wherePivot('role', '!=', 'avocat')->detach();
    // }
    
    // Gérer l'upload des nouveaux fichiers
    if ($request->hasFile('fichiers')) {
        foreach ($request->file('fichiers') as $file) {
            if ($file->isValid()) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('dossiers/' . $dossier->numero_dossier, $fileName, 'public');
                
                Fichier::create([
                    'type_module' => 'facture',
                    'module_id' => $dossier->id,
                    'nom_fichier' => $file->getClientOriginalName(),
                    'chemin_fichier' => $filePath,
                    'type_mime' => $file->getMimeType(),
                    'taille' => $file->getSize(),
                    'description' => 'Fichier joint au dossier'
                ]);
            }
        }
    }
    
    // Gérer la suppression des fichiers existants
    if ($request->has('fichiers_supprimes')) {
        $fichiersASupprimer = $request->fichiers_supprimes;
        $fichiers = Fichier::whereIn('id', $fichiersASupprimer)
            ->where('module_id', $dossier->id)
            ->where('type_module', 'facture')
            ->get();
        
        foreach ($fichiers as $fichier) {
            // Supprimer le fichier physique
            Storage::disk('public')->delete($fichier->chemin_fichier);
            // Supprimer l'enregistrement en base
            $fichier->delete();
        }
    }
    
    // Redirection avec message de succès
    return redirect()->route('dossiers.index')
        ->with('success', 'Dossier mis à jour avec succès.');
}

    public function destroy(Dossier $dossier): JsonResponse
    {
        $this->authorize('delete_dossiers', Dossier::class);
        $dossier->delete();
        
        return response()->json([
            'message' => 'Dossier supprimé avec succès.'
        ], 200);
    }

    public function search(Request $request): AnonymousResourceCollection
    {
        $query = Dossier::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('numero_dossier', 'like', "%{$search}%")
                  ->orWhere('nom_dossier', 'like', "%{$search}%");
        }
        
        if ($request->has('domaine_id')) {
            $query->where('domaine_id', $request->domaine_id);
        }
        
        if ($request->has('conseil')) {
            $query->where('conseil', $request->boolean('conseil'));
        }
        
        if ($request->has('contentieux')) {
            $query->where('contentieux', $request->boolean('contentieux'));
        }
        
        if ($request->has('archive')) {
            $query->where('archive', $request->boolean('archive'));
        }
        
        $dossiers = $query->with(['domaine', 'sousDomaine', 'users', 'intervenants'])->paginate(10);
        
        return DossierResource::collection($dossiers);
    }

    public function attachUser(Request $request, Dossier $dossier): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:avocat,clerc,secrétaire,stagiaire',
            'ordre' => 'required|integer'
        ]);
        
        $dossier->users()->attach($request->user_id, [
            'role' => $request->role,
            'ordre' => $request->ordre
        ]);
        
        return response()->json([
            'message' => 'Utilisateur attaché au dossier avec succès.'
        ], 200);
    }

    public function attachIntervenant(Request $request, Dossier $dossier): JsonResponse
    {
        $request->validate([
            'intervenant_id' => 'required|exists:intervenants,id',
            'role' => 'required|in:client,avocat,avocat_secondaire,adversaire,huissier,notaire,expert,juridiction,administrateur_judiciaire,mandataire_judiciaire,autre'
        ]);
        
        $dossier->intervenants()->attach($request->intervenant_id, [
            'role' => $request->role
        ]);
        
        return response()->json([
            'message' => 'Intervenant attaché au dossier avec succès.'
        ], 200);
    }


    public function detachIntervenant(Request $request, Dossier $dossier)
    {
        try {
            $request->validate([
                'intervenant_id' => 'required|exists:intervenants,id'
            ]);

            // Check if the intervenant is actually linked to this dossier
            $isLinked = $dossier->intervenants()
                ->where('intervenant_id', $request->intervenant_id)
                ->exists();

            if (!$isLinked) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet intervenant n\'est pas lié à ce dossier.'
                ], 404);
            }

            // Detach the intervenant from the dossier
            $dossier->intervenants()->detach($request->intervenant_id);

            // Log the action if you have activity logging
            // activity()
            //     ->causedBy(auth()->user())
            //     ->performedOn($dossier)
            //     ->log("Intervenant détaché du dossier");

            return response()->json([
                'success' => true,
                'message' => 'Intervenant détaché du dossier avec succès.',
                'remaining_count' => $dossier->intervenants()->count()
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides.',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Error detaching intervenant: ' . $e->getMessage(), [
                'dossier_id' => $dossier->id,
                'intervenant_id' => $request->intervenant_id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du détachement de l\'intervenant.'
            ], 500);
        }
    }

    public function linkDossier(Request $request, Dossier $dossier): JsonResponse
    {
        $request->validate([
            'dossier_lie_id' => 'required|exists:dossiers,id',
            'relation' => 'required|in:appel,cassation,opposition,renvoi_premiere_instance,autre'
        ]);
        
        $dossier->dossiersLies()->attach($request->dossier_lie_id, [
            'relation' => $request->relation
        ]);
        
        return response()->json([
            'message' => 'Dossier lié avec succès.'
        ], 200);
    }

    public function getSousDomainesByDomaine(Request $request)
    {
        $request->validate([
            'domaine_id' => 'required|exists:domaines,id'
        ]);

        $sousDomaines = SousDomaine::where('domaine_id', $request->domaine_id)
            ->orderBy('nom')
            ->get(['id', 'nom']);
    
        return response()->json($sousDomaines);
    }

    public function getSousDomaines(Request $request)
{
    $domaineId = $request->get('domaine_id');
    
    $sousDomaines = SousDomaine::where('domaine_id', $domaineId)
        ->pluck('nom', 'id');
    
    return response()->json($sousDomaines);
}

public function getDossiersData(Request $request)
{
    $this->authorize('view_dossiers', Dossier::class);


    // Use globalSearch instead of DataTables search
    if ($request->has('globalSearch') && !empty($request->globalSearch)) {
        $query = Dossier::with(['domaine'])->select('dossiers.*');
        $search = $request->globalSearch;
        $query->where(function ($q) use ($search) {
            $q->where('numero_dossier', 'LIKE', "%{$search}%")
              ->orWhere('nom_dossier', 'LIKE', "%{$search}%")
              ->orWhere('objet', 'LIKE', "%{$search}%")
              ->orWhereHas('domaine', function ($q) use ($search) {
                  $q->where('nom', 'LIKE', "%{$search}%");
              });
        });
    }else{
        $query = Dossier::with(['domaine'])->where('archive', false)->select('dossiers.*');
    }

    // Filtre par domaine
    if ($request->has('domaineFilter') && !empty($request->domaineFilter)) {
        $query->whereHas('domaine', function ($q) use ($request) {
            $q->where('nom', $request->domaineFilter);
        });
    }

    // Filtre par statut
    if ($request->has('statutFilter') && !empty($request->statutFilter)) {
        switch ($request->statutFilter) {
            case 'conseil':
                $query->where('conseil', true);
                break;
            case 'contentieux':
                $query->where('contentieux', true);
                break;
            case 'archive':
                $query->where('archive', true);
                break;
        }
    }

    // Tri personnalisé si nécessaire
    if ($request->has('order') && !empty($request->order)) {
        $order = $request->order[0];
        $column_index = $order['column'];
        $column_name = $request->columns[$column_index]['data'];
        $direction = $order['dir'];

        // Mapping des colonnes triables
        $sortable_columns = [
            'numero_dossier' => 'numero_dossier',
            'nom_dossier' => 'nom_dossier',
            'objet' => 'objet',
            'date_entree' => 'date_entree',
            'archive' => 'archive'
        ];

        if (array_key_exists($column_name, $sortable_columns)) {
            $query->orderBy($sortable_columns[$column_name], $direction);
        }
    } else {
        // Tri par défaut
        $query->orderBy('dossiers.created_at', 'desc');
    }

    return DataTables::eloquent($query)
        ->addColumn('type_badge', function (Dossier $dossier) {
            if ($dossier->conseil && $dossier->contentieux) {
                return '<span class="badge badge-warning">Mixte</span>';
            } elseif ($dossier->conseil) {
                return '<span class="badge badge-info">Conseil</span>';
            } elseif ($dossier->contentieux) {
                return '<span class="badge badge-primary">Contentieux</span>';
            } else {
                return '<span class="badge badge-secondary">Non défini</span>';
            }
        })
        ->addColumn('statut_badge', function (Dossier $dossier) {
            if ($dossier->numero_role) {
                return '<span class="badge badge-success">En cours</span>';
            } else {
                return '<span class="badge badge-secondary">En préparation</span>';
            }
        })
        ->addColumn('archive_text', function (Dossier $dossier) {
            return $dossier->archive ? 'Oui' : 'Non';
        })
        ->addColumn('action', function (Dossier $dossier) {
            $actions = '<div class="btn-group btn-group-sm">';
            
            if (auth()->user()->hasPermission('view_dossiers')) {
                $actions .= '<a href="' . route('dossiers.show', $dossier->id) . '" class="btn btn-info" title="Voir">
                    <i class="fas fa-eye"></i>
                </a>';
            }
            
            if (auth()->user()->hasPermission('edit_dossiers')) {
                $actions .= '<a href="' . route('dossiers.edit', $dossier->id) . '" class="btn btn-warning" title="Modifier">
                    <i class="fas fa-edit"></i>
                </a>';
            }
            
            if (auth()->user()->hasPermission('delete_dossiers')) {
                $actions .= '<button type="button" class="btn btn-danger delete-dossier-btn" 
                    title="Supprimer" 
                    data-id="' . $dossier->id . '"
                    data-numero="' . $dossier->numero_dossier . '"
                    data-nom="' . $dossier->nom_dossier . '">
                    <i class="fas fa-trash"></i>
                </button>';
            }
            
            $actions .= '</div>';
            return $actions;
        })
        ->editColumn('date_entree', function (Dossier $dossier) {
            return $dossier->date_entree ? $dossier->date_entree->format('d/m/Y') : '-';
        })
        ->filterColumn('numero_dossier', function ($query, $keyword) {
            $query->where('numero_dossier', 'LIKE', "%{$keyword}%");
        })
        ->filterColumn('nom_dossier', function ($query, $keyword) {
            $query->where('nom_dossier', 'LIKE', "%{$keyword}%");
        })
        ->filterColumn('objet', function ($query, $keyword) {
            $query->where('objet', 'LIKE', "%{$keyword}%");
        })
        ->filterColumn('date_entree', function ($query, $keyword) {
            // Convertir la date du format français au format database
            if (preg_match('/\d{2}\/\d{2}\/\d{4}/', $keyword)) {
                $date = \Carbon\Carbon::createFromFormat('d/m/Y', $keyword)->format('Y-m-d');
                $query->whereDate('date_entree', $date);
            }
        })
        ->rawColumns(['action', 'type_badge', 'statut_badge'])
        ->make(true);
}

   public function createForDossier(Dossier $dossier)
   {
    if(!auth()->user()->hasPermission('create_tasks')){
        abort(403, 'Unauthorized action.');
    }
        $users = \App\Models\User::where('is_active', true)->get();
        return view('dossiers.tasks.create', compact('users','dossier'));
    }


   public function storeForDossier(Request $request, Dossier $dossier)
   {
    if(!auth()->user()->hasPermission('create_tasks')){
        abort(403, 'Unauthorized action.');
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
        ]);

        \App\Models\Task::create($validated);

        return redirect()->back()->with('success', 'Tâche créée avec succès.');
    }

    public function createFactureForDossier(Dossier $dossier)
    {
        if(!auth()->user()->hasPermission('create_factures')){
            abort(403, 'Unauthorized action.');
        }

        $clients = $dossier->intervenants()
            ->wherePivot('role', 'client')
            ->get();
        // Générer le prochain numéro de facture
        $lastFacture = \App\Models\Facture::orderBy('id', 'desc')->first();
        $nextNumber = 'FACT-' . date('Y') . '-' . str_pad(($lastFacture ? $lastFacture->id + 1 : 1), 4, '0', STR_PAD_LEFT);
        return view('dossiers.factures.create', compact('dossier', 'nextNumber', 'clients'));
    }

    public function storeFactureForDossier(Request $request, Dossier $dossier)
    {
        if(!auth()->user()->hasPermission('create_factures')){
            abort(403, 'Unauthorized action.');
        }
        $validated = $request->validate([
            'dossier_id' => 'nullable|exists:dossiers,id',
            'client_id' => 'nullable|exists:intervenants,id',
            'type_piece' => 'required|in:facture,note_frais,note_provision,avoir',
            'numero' => 'required|string|max:100|unique:factures,numero',
            'date_emission' => 'required|date',
            'montant_ht' => 'required|numeric|min:0',
            'montant_tva' => 'required|numeric|min:0',
            'montant' => 'required|numeric|min:0',
            'statut' => 'required|in:payé,non_payé',
            'commentaires' => 'nullable|string',
            'piece_jointe' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240', 
        ]);

        // Vérifier la cohérence des montants
        $calculatedMontant = $validated['montant_ht'] + $validated['montant_tva'];
        if (abs($calculatedMontant - $validated['montant']) > 0.01) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Le montant TTC doit être égal à HT + TVA.');
        }

        // Gestion de la pièce jointe
    if ($request->hasFile('piece_jointe')) {
        $file = $request->file('piece_jointe');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('factures', $fileName, 'public');
        $validated['piece_jointe'] = $fileName;
    }

        $facture = \App\Models\Facture::create($validated);
         if($request->hasFile('piece_jointe')) {
        $facture->file_name = $file->getClientOriginalName();
        $facture->save();
        }

        return redirect()->back()->with('success', 'Facture créée avec succès.');
    }   

    public function createTimeSheetForDossier(Dossier $dossier)
    {
        if(!auth()->user()->hasPermission('create_timesheets')){
            abort(403, 'Unauthorized action.');
        }
        
    $users = User::where('is_active', true)->get();
    $categories = Categorie::all();
    $types = Type::all();
        return view('dossiers.timesheets.create', compact('dossier', 'users', 'categories', 'types'));
    }

    public function storeTimeSheetForDossier(Request $request, Dossier $dossier)
    {
        if(!auth()->user()->hasPermission('create_timesheets')){
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
    ]);

    // Calculer le total
    $validated['total'] = $validated['quantite'] * $validated['prix'];

    \App\Models\TimeSheet::create($validated);

        return redirect()->back()->with('success', 'Feuille de temps créée avec succès.');
    }

    
    public function getFiles(Dossier $dossier)
{
    $baseDirectory = "dossiers/{$dossier->numero_dossier}-{$dossier->id}";
    $requestedPath = request('path', '');
    
    // Build the full directory path
    $directory = $requestedPath ? $baseDirectory . '/' . $requestedPath : $baseDirectory;
    
    $items = [];
    
    if (Storage::disk('public')->exists($directory)) {
        // Get all contents (non-recursive)
        $contents = Storage::disk('public')->listContents($directory, false);
        
        foreach ($contents as $item) {
            // Handle both Flysystem v1 (array) and v2 (FileAttributes/DirectoryAttributes)
            if (is_object($item)) {
                // Flysystem v2+
                $isDirectory = method_exists($item, 'isDir') ? $item->isDir() : false;
                $path = $item->path();
                $lastModified = $item->lastModified();
                $size = $isDirectory ? 0 : $item->fileSize();
            } else {
                // Flysystem v1
                $isDirectory = $item['type'] === 'dir';
                $path = $item['path'];
                $lastModified = $item['timestamp'] ?? time();
                $size = $isDirectory ? 0 : ($item['size'] ?? 0);
            }
            
            if ($isDirectory) {
                $items[] = [
                    'name' => basename($path),
                    'path' => str_replace($baseDirectory . '/', '', $path), // Relative path
                    'type' => 'folder',
                    'extension' => '',
                    'size' => 0,
                    'last_modified' => $lastModified,
                    'url' => null
                ];
            } else {
                $items[] = [
                    'name' => basename($path),
                    'path' => str_replace($baseDirectory . '/', '', $path), // Relative path
                    'type' => 'file',
                    'extension' => pathinfo($path, PATHINFO_EXTENSION),
                    'size' => $size,
                    'last_modified' => $lastModified,
                    'url' => Storage::disk('public')->url($path)
                ];
            }
        }
        
        // Sort: folders first, then files, both alphabetically
        usort($items, function($a, $b) {
            if ($a['type'] === $b['type']) {
                return strcasecmp($a['name'], $b['name']);
            }
            return $a['type'] === 'folder' ? -1 : 1;
        });
    }
    
    return response()->json($items);
}


   public function uploadFiles(Request $request, Dossier $dossier)
{
    try {
        $request->validate([
            'files.*' => 'required|file', // 10MB max per file
        ]);

        $uploadedFiles = [];
        $baseDirectory = "dossiers/{$dossier->numero_dossier}-{$dossier->id}";
        
        // Get the subdirectory path from the request
        $subDirectory = $request->input('path', '');
        
        // Build the full directory path
        $fullDirectory = $subDirectory ? $baseDirectory . '/' . $subDirectory : $baseDirectory;

        \Log::info("Upload attempt:");
        \Log::info("Dossier: " . $dossier->id);
        \Log::info("Base directory: " . $baseDirectory);
        \Log::info("Sub directory: " . $subDirectory);
        \Log::info("Full directory: " . $fullDirectory);

        // Ensure directory exists
        if (!Storage::disk('public')->exists($fullDirectory)) {
            Storage::disk('public')->makeDirectory($fullDirectory, 0755, true); // recursive creation
            \Log::info("Directory created: " . $fullDirectory);
        }

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = pathinfo($originalName, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                
                // Generate unique name if file exists
                $fullFileName = $originalName;
                $counter = 1;
                
                // Check if file already exists and generate unique name
                while (Storage::disk('public')->exists("$fullDirectory/$fullFileName")) {
                    $fullFileName = $fileName . '_' . $counter . '.' . $extension;
                    $counter++;
                }

                // Store file
                $path = $file->storeAs($fullDirectory, $fullFileName, 'public');

                // Get relative path for the response (without base directory)
                $relativePath = str_replace($baseDirectory . '/', '', $path);

                $uploadedFiles[] = [
                    'name' => $fullFileName,
                    'path' => $relativePath,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'full_path' => $path, // For debugging
                ];

                \Log::info("File uploaded: " . $fullFileName . " to " . $path);
            }
        }

        \Log::info("Upload completed. Files count: " . count($uploadedFiles));

        return response()->json([
            'success' => true,
            'message' => 'Fichiers uploadés avec succès',
            'files' => $uploadedFiles,
            'upload_directory' => $fullDirectory
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Upload validation error: ' . json_encode($e->errors()));
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur de validation des fichiers',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        \Log::error('Upload error: ' . $e->getMessage());
        \Log::error('Upload error trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'upload des fichiers: ' . $e->getMessage()
        ], 500);
    }
}

public function viewFile(Request $request)
{
    try {
        // Validate input
        $validated = $request->validate([
            'dossier_id' => 'required|exists:dossiers,id',
            'file_path' => 'required|string'
        ]);

        $dossier = Dossier::findOrFail($validated['dossier_id']);
        $filePath = urldecode($validated['file_path']); // decode URL if needed

        $basePath = "dossiers/{$dossier->numero_dossier}-{$dossier->id}";
        $fullPath = $filePath ? $basePath . '/' . $filePath : $basePath;

        if (!Storage::disk('public')->exists($fullPath)) {
            return response()->json(['error' => 'Fichier non trouvé'], 404);
        }

        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $onlyofficeFormats = ['docx', 'xlsx', 'pptx', 'pdf'];

        if (in_array($extension, $onlyofficeFormats)) {
            $fileUrl = asset("storage/$fullPath");

            // Generate a safe hash key
            $key = md5($fullPath . '-' . (auth()->id() ?? '1'));

            // Store mapping key → original path in cache for 1 hour
            Cache::put("onlyoffice_file_$key", $fullPath, 3600);

            $config = [
                "document" => [
                    "fileType" => $extension,
                    "key" => $key, // safe hash key
                    "title" => basename($fullPath),
                    "url" => $fileUrl,
                ],
                "editorConfig" => [
                    "mode" => "edit",
                    "callbackUrl" => url('/onlyoffice/save'),
                    "user" => [
                        "id" => (string)(auth()->id() ?? '1'),
                        "name" => auth()->user()->name ?? "Utilisateur",
                    ],
                ],
            ];

            return view('onlyoffice.editor', compact('config'));
        }

        // Non-supported formats: return raw file
        $mimeType = Storage::disk('public')->mimeType($fullPath);
        $fileContent = Storage::disk('public')->get($fullPath);

        return response($fileContent, 200)
            ->header('Content-Type', $mimeType);

    } catch (\Exception $e) {
        \Log::error("Erreur lors de la visualisation: " . $e->getMessage());
        return response()->json(['error' => 'Erreur serveur lors de la visualisation'], 500);
    }
}

public function downloadFile(Request $request)
{
    try {
        // Valider les données de la requête
        $validated = $request->validate([
            'dossier_id' => 'required|exists:dossiers,id',
            'file_path' => 'required|string'
        ]);

        $dossier = Dossier::findOrFail($validated['dossier_id']);
        $filePath = $validated['file_path'];
        
        // Construire le chemin complet
        $basePath = "dossiers/{$dossier->numero_dossier}-{$dossier->id}";
        $fullPath = $filePath ? $basePath . '/' . $filePath : $basePath;

        \Log::info("Tentative de téléchargement:");
        \Log::info("Dossier ID: " . $dossier->id);
        \Log::info("Numéro dossier: " . $dossier->numero_dossier);
        \Log::info("File path: " . $filePath);
        \Log::info("Full storage path: " . $fullPath);

        // Vérifier si le fichier existe
        if (!Storage::disk('public')->exists($fullPath)) {
            \Log::error("Fichier non trouvé: " . $fullPath);
            
            // Lister tous les fichiers pour debug
            $allFiles = Storage::disk('public')->allFiles($basePath);
            \Log::info("Fichiers disponibles:", $allFiles);
            
            return response()->json([
                'error' => 'Fichier non trouvé',
                'requested_path' => $fullPath
            ], 404);
        }

        // Vérifier que c'est bien un fichier et non un dossier
        // if (Storage::disk('public')->size($fullPath) === 0) {
        //     \Log::error("Chemin point vers un dossier: " . $fullPath);
        //     return response()->json([
        //         'error' => 'Impossible de télécharger un dossier'
        //     ], 400);
        // }

        // Télécharger le fichier
        return Storage::disk('public')->download($fullPath);

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error("Validation error: " . json_encode($e->errors()));
        return response()->json([
            'error' => 'Données invalides',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        \Log::error("Dossier non trouvé: " . $request->dossier_id);
        return response()->json([
            'error' => 'Dossier non trouvé'
        ], 404);
        
    } catch (\Exception $e) {
        \Log::error("Erreur lors du téléchargement: " . $e->getMessage());
        return response()->json([
            'error' => 'Erreur serveur lors du téléchargement'
        ], 500);
    }
}

public function deleteFile(Request $request, Dossier $dossier)
{
    try {
        $request->validate([
            'file_path' => 'required|string',
            'file_type' => 'required|in:file,folder'
        ]);

        $basePath = "dossiers/{$dossier->numero_dossier}-{$dossier->id}";
        $filePath = $request->file_path;
        $fullPath = $basePath . '/' . $filePath;

        \Log::info("Tentative de suppression:");
        \Log::info("Dossier: " . $dossier->id);
        \Log::info("File path: " . $filePath);
        \Log::info("Full path: " . $fullPath);
        \Log::info("Type: " . $request->file_type);

        if (!Storage::disk('public')->exists($fullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Fichier/dossier non trouvé'
            ], 404);
        }

        if ($request->file_type === 'folder') {
            // Supprimer un dossier récursivement
            Storage::disk('public')->deleteDirectory($fullPath);
        } else {
            // Supprimer un fichier
            Storage::disk('public')->delete($fullPath);
        }

        \Log::info("Suppression réussie");

        return response()->json([
            'success' => true,
            'message' => $request->file_type === 'folder' ? 'Dossier supprimé avec succès' : 'Fichier supprimé avec succès'
        ]);

    } catch (\Exception $e) {
        \Log::error('Erreur suppression: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
        ], 500);
    }
}

public function renameFile(Request $request, Dossier $dossier)
{
    try {
        $request->validate([
            'file_path' => 'required|string',
            'file_type' => 'required|in:file,folder',
            'new_name' => 'required|string|max:255'
        ]);

        $basePath = "dossiers/{$dossier->numero_dossier}-{$dossier->id}";
        $oldFilePath = $request->file_path;
        $newName = $request->new_name;
        $fileType = $request->file_type;

        $oldFullPath = $basePath . '/' . $oldFilePath;
        
        // Construire le nouveau chemin
        $directory = dirname($oldFilePath);
        $newFilePath = $directory === '.' ? $newName : $directory . '/' . $newName;
        $newFullPath = $basePath . '/' . $newFilePath;

        \Log::info("Tentative de renommage:");
        \Log::info("Dossier: " . $dossier->id);
        \Log::info("Ancien chemin: " . $oldFilePath);
        \Log::info("Nouveau nom: " . $newName);
        \Log::info("Nouveau chemin: " . $newFilePath);
        \Log::info("Type: " . $fileType);

        // Vérifier si l'ancien fichier/dossier existe
        if (!Storage::disk('public')->exists($oldFullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Fichier/dossier non trouvé'
            ], 404);
        }

        // Vérifier si le nouveau nom est différent
        if ($oldFilePath === $newFilePath) {
            return response()->json([
                'success' => false,
                'message' => 'Le nouveau nom est identique à l\'ancien'
            ], 422);
        }

        // Vérifier si le nouveau nom existe déjà
        if (Storage::disk('public')->exists($newFullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Un élément avec ce nom existe déjà'
            ], 422);
        }

        // Valider le nouveau nom (pas de caractères interdits)
        if (preg_match('/[<>:"\/\\|?*]/', $newName)) {
            return response()->json([
                'success' => false,
                'message' => 'Le nom contient des caractères non autorisés'
            ], 422);
        }

        // Effectuer le renommage
        $renamed = Storage::disk('public')->move($oldFullPath, $newFullPath);

        if ($renamed) {
            \Log::info("Renommage réussi");
            return response()->json([
                'success' => true,
                'message' => $fileType === 'folder' ? 'Dossier renommé avec succès' : 'Fichier renommé avec succès',
                'new_path' => $newFilePath
            ]);
        } else {
            throw new \Exception('Échec du renommage');
        }

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Erreur validation renommage: ' . json_encode($e->errors()));
        return response()->json([
            'success' => false,
            'message' => 'Données invalides',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        \Log::error('Erreur renommage: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors du renommage: ' . $e->getMessage()
        ], 500);
    }
}

public function moveFile(Request $request, Dossier $dossier)
{
    try {
        $request->validate([
            'file_path' => 'required|string',
            'file_type' => 'required|in:file,folder',
            'target_path' => 'sometimes|string|nullable'
        ]);

        $basePath = "dossiers/{$dossier->numero_dossier}-{$dossier->id}";
        $oldFilePath = $request->file_path;
        $fileType = $request->file_type;
        $targetPath = $request->target_path ?? ''; // Chaîne vide pour la racine

        $oldFullPath = $basePath . '/' . $oldFilePath;
        
        // Construire le nouveau chemin - CORRECTION ICI
        $fileName = basename($oldFilePath);
        
        // Si targetPath est vide (racine), newFilePath = fileName
        // Si targetPath n'est pas vide, newFilePath = targetPath + '/' + fileName
        $newFilePath = $targetPath === '' ? $fileName : $targetPath . '/' . $fileName;
        $newFullPath = $basePath . '/' . $newFilePath;

        \Log::info("Tentative de déplacement:");
        \Log::info("Ancien chemin: " . $oldFilePath);
        \Log::info("Target path: '" . $targetPath . "'");
        \Log::info("Nouveau chemin: " . $newFilePath);
        \Log::info("Type: " . $fileType);

        // Vérifier si l'ancien fichier/dossier existe
        if (!Storage::disk('public')->exists($oldFullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Fichier/dossier non trouvé'
            ], 404);
        }

        // Vérifier si le déplacement est dans le même dossier
        if ($oldFilePath === $newFilePath) {
            return response()->json([
                'success' => false,
                'message' => 'L\'élément est déjà dans ce dossier'
            ], 422);
        }

        // Vérifier si le nouveau chemin existe déjà
        if (Storage::disk('public')->exists($newFullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Un élément avec ce nom existe déjà dans le dossier de destination'
            ], 422);
        }

        // Vérifier qu'on ne déplace pas un dossier dans lui-même ou ses sous-dossiers
        if ($fileType === 'folder' && $targetPath && strpos($targetPath, $oldFilePath) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de déplacer un dossier dans lui-même ou dans un de ses sous-dossiers'
            ], 422);
        }

        // Effectuer le déplacement
        $moved = Storage::disk('public')->move($oldFullPath, $newFullPath);

        if ($moved) {
            \Log::info("Déplacement réussi vers: " . ($targetPath === '' ? 'RACINE' : $targetPath));
            return response()->json([
                'success' => true,
                'message' => $fileType === 'folder' ? 'Dossier déplacé avec succès' : 'Fichier déplacé avec succès',
                'new_path' => $newFilePath
            ]);
        } else {
            throw new \Exception('Échec du déplacement');
        }

    } catch (\Exception $e) {
        \Log::error('Erreur déplacement: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors du déplacement: ' . $e->getMessage()
        ], 500);
    }
}

public function getFoldersTree(Dossier $dossier, Request $request)
{
    try {
        $baseDirectory = "dossiers/{$dossier->numero_dossier}-{$dossier->id}";
        $excludePath = $request->get('exclude_path', ''); // Chemin à exclure
        
        \Log::info("Chargement de l'arborescence des dossiers - Exclure: " . $excludePath);

        $folders = [];
        
        if (Storage::disk('public')->exists($baseDirectory)) {
            // Obtenir tous les dossiers de manière récursive
            $allDirectories = Storage::disk('public')->allDirectories($baseDirectory);
            
            foreach ($allDirectories as $dir) {
                // Obtenir le chemin relatif
                $relativePath = str_replace($baseDirectory . '/', '', $dir);
                
                // Ignorer les dossiers qui ne sont pas dans le chemin de base
                if (strpos($dir, $baseDirectory) !== 0) {
                    continue;
                }
                
                // Exclure le dossier source et ses sous-dossiers
                if ($excludePath && (strpos($relativePath, $excludePath) === 0)) {
                    continue;
                }
                
                $folderName = basename($dir);
                $level = substr_count($relativePath, '/');
                
                $folders[] = [
                    'name' => $folderName,
                    'path' => $relativePath,
                    'level' => $level
                ];
            }
            
            // Trier par chemin pour une meilleure organisation
            usort($folders, function($a, $b) {
                return strcmp($a['path'], $b['path']);
            });
            
            \Log::info("Dossiers disponibles pour déplacement: " . count($folders));
        }

        return response()->json($folders);
        
    } catch (\Exception $e) {
        \Log::error('Erreur dans getFoldersTree: ' . $e->getMessage());
        return response()->json([], 500);
    }
}

public function createFolder(Request $request, Dossier $dossier)
{
    try {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'path' => 'sometimes|string|nullable'
        ]);

        $basePath = "dossiers/{$dossier->numero_dossier}-{$dossier->id}";
        $folderName = $request->folder_name;
        $parentPath = $request->path ?? '';

        // Construire le chemin complet
        $fullPath = $parentPath ? $basePath . '/' . $parentPath . '/' . $folderName : $basePath . '/' . $folderName;

        \Log::info("Tentative de création de dossier:");
        \Log::info("Dossier: " . $dossier->id);
        \Log::info("Nom: " . $folderName);
        \Log::info("Chemin parent: " . ($parentPath ?: 'RACINE'));
        \Log::info("Chemin complet: " . $fullPath);

        // Vérifier les caractères interdits
        if (preg_match('/[<>:"\/\\|?*]/', $folderName)) {
            return response()->json([
                'success' => false,
                'message' => 'Le nom contient des caractères non autorisés: < > : " / \\ | ? *'
            ], 422);
        }

        // Vérifier si le dossier existe déjà
        if (Storage::disk('public')->exists($fullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Un dossier avec ce nom existe déjà dans cet emplacement'
            ], 422);
        }

        // Créer le dossier
        $created = Storage::disk('public')->makeDirectory($fullPath);

        if ($created) {
            \Log::info("Dossier créé avec succès: " . $fullPath);
            return response()->json([
                'success' => true,
                'message' => 'Dossier créé avec succès',
                'folder_path' => $parentPath ? $parentPath . '/' . $folderName : $folderName
            ]);
        } else {
            throw new \Exception('Échec de la création du dossier');
        }

    } catch (\Exception $e) {
        \Log::error('Erreur création dossier: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la création du dossier: ' . $e->getMessage()
        ], 500);
    }
}
public function getFileUrl(Request $request, Dossier $dossier)
{
    try {
        $request->validate([
            'file_path' => 'required|string'
        ]);

        $basePath = "dossiers/{$dossier->numero_dossier}-{$dossier->id}";
        $filePath = $request->file_path;
        $fullPath = $basePath . '/' . $filePath;

        // Vérifier si le fichier existe
        if (!Storage::disk('public')->exists($fullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Fichier non trouvé'
            ], 404);
        }

        // Obtenir l'URL publique du fichier
        $fileUrl = Storage::disk('public')->url($fullPath);

        return response()->json([
            'success' => true,
            'file_url' => $fileUrl,
            'file_path' => $filePath
        ]);

    } catch (\Exception $e) {
        \Log::error('Erreur getFileUrl: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la récupération du fichier'
        ], 500);
    }
}
public function uploadFolder(Request $request, Dossier $dossier)
{
    try {
        $request->validate([
            'folder' => 'required', // Le dossier avec sa structure
            'path' => 'sometimes|string|nullable' // Chemin de destination
        ]);

        $baseDirectory = "dossiers/{$dossier->numero_dossier}-{$dossier->id}";
        $subDirectory = $request->input('path', '');
        $fullDirectory = $subDirectory ? $baseDirectory . '/' . $subDirectory : $baseDirectory;

        \Log::info("Upload de dossier:");
        \Log::info("Dossier: " . $dossier->id);
        \Log::info("Destination: " . $fullDirectory);

        // Vérifier si le répertoire de destination existe
        if (!Storage::disk('public')->exists($fullDirectory)) {
            Storage::disk('public')->makeDirectory($fullDirectory, 0755, true);
        }

        $uploadedItems = [];
        $folderData = $request->folder;

        // Traiter récursivement la structure du dossier
        function processFolder($folder, $currentPath, $basePath, &$uploadedItems) {
            $folderName = $folder['name'];
            $newPath = $currentPath ? $currentPath . '/' . $folderName : $folderName;
            
            // Créer le dossier
            $fullFolderPath = $basePath . '/' . $newPath;
            Storage::disk('public')->makeDirectory($fullFolderPath, 0755, true);
            
            $uploadedItems[] = [
                'name' => $folderName,
                'path' => $newPath,
                'type' => 'folder',
                'size' => 0,
                'last_modified' => time()
            ];

            \Log::info("Dossier créé: " . $newPath);

            // Traiter les fichiers dans ce dossier
            if (isset($folder['files']) && is_array($folder['files'])) {
                foreach ($folder['files'] as $file) {
                    // Vérifier si c'est un fichier base64
                    if (isset($file['content']) && strpos($file['content'], 'base64,') !== false) {
                        $fileName = $file['name'];
                        $base64Content = $file['content'];
                        
                        // Extraire le contenu base64
                        $base64Data = explode('base64,', $base64Content)[1];
                        $fileContent = base64_decode($base64Data);
                        
                        // Chemin complet du fichier
                        $filePath = $newPath ? $newPath . '/' . $fileName : $fileName;
                        $fullFilePath = $basePath . '/' . $filePath;
                        
                        // Stocker le fichier
                        Storage::disk('public')->put($fullFilePath, $fileContent);
                        
                        $uploadedItems[] = [
                            'name' => $fileName,
                            'path' => $filePath,
                            'type' => 'file',
                            'size' => strlen($fileContent),
                            'mime_type' => $file['type'] ?? 'application/octet-stream',
                            'last_modified' => time()
                        ];

                        \Log::info("Fichier uploadé: " . $filePath);
                    }
                }
            }

            // Traiter les sous-dossiers récursivement
            if (isset($folder['folders']) && is_array($folder['folders'])) {
                foreach ($folder['folders'] as $subFolder) {
                    processFolder($subFolder, $newPath, $basePath, $uploadedItems);
                }
            }
        }

        // Démarrer le traitement
        processFolder($folderData, $subDirectory, $baseDirectory, $uploadedItems);

        \Log::info("Upload de dossier terminé. Éléments: " . count($uploadedItems));

        return response()->json([
            'success' => true,
            'message' => 'Dossier uploadé avec succès',
            'items' => $uploadedItems,
            'count' => count($uploadedItems),
            'folder_name' => $folderData['name']
        ]);

    } catch (\Exception $e) {
        \Log::error('Erreur upload dossier: ' . $e->getMessage());
        \Log::error('Trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'upload du dossier: ' . $e->getMessage()
        ], 500);
    }
}
}
