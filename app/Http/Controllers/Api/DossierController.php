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
        $directory = "dossiers/{$dossier->numero_dossier}-{$dossier->id}";
        $files = [];
        
        // Check if directory exists
        if (Storage::disk('public')->exists($directory)) {
            // Get all files and directories
            $items = Storage::disk('public')->allFiles($directory);
            
            foreach ($items as $item) {
                $files[] = [
                    'name' => basename($item),
                    'path' => $item,
                    'type' => pathinfo($item, PATHINFO_EXTENSION) ? 'file' : 'folder',
                    'extension' => pathinfo($item, PATHINFO_EXTENSION),
                    'size' => Storage::disk('public')->size($item),
                    'last_modified' => Storage::disk('public')->lastModified($item),
                    'url' => Storage::disk('public')->url($item)
                ];
            }
        }
        
        return response()->json($files);
    }

}