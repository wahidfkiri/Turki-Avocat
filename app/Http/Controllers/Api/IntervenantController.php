<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIntervenantRequest;
use App\Http\Requests\UpdateIntervenantRequest;
use App\Http\Resources\IntervenantResource;
use App\Models\Intervenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\FormeSociale;
use App\Models\IntervenantFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class IntervenantController extends Controller
{
    public function index()
    {
        $formeSociales = FormeSociale::all();
        $intervenants = Intervenant::with(['formeSociale', 'dossiers'])->orderBy('created_at', 'desc')->get();
        return view('intervenants.index', compact('intervenants', 'formeSociales'));
    }

    public function create()
{
     $formeSociales = FormeSociale::all();
    $intervenants = Intervenant::where('id', '!=', request('id'))->get();
    return view('intervenants.create', compact('formeSociales','intervenants'));
}

 public function edit(Intervenant $intervenant)
{
    $intervenantsLies = $intervenant->intervenantsLies()->pluck('intervenant_lie_id')->toArray();
    $formeSociales = FormeSociale::all();
    $intervenants = Intervenant::where('id', '!=', $intervenant->id)->get();
    return view('intervenants.edit', compact('intervenant','formeSociales','intervenants','intervenantsLies'));
}

   public function store(StoreIntervenantRequest $request)
{
    try {
        DB::beginTransaction();

        // Récupérer les données validées (sans piece_jointe)
        $validatedData = $request->validated();
        unset($validatedData['piece_jointe']);
        
        // Créer l'intervenant avec les données validées
        $intervenant = Intervenant::create($validatedData);
        
        // Gestion des fichiers
        if ($request->hasFile('piece_jointe')) {
            $files = $request->file('piece_jointe');
            
            // Créer le dossier pour cet intervenant
            $intervenantFolder = 'intervenants/' . $intervenant->id;
            $storagePath = storage_path('app/public/' . $intervenantFolder);
            
            // S'assurer que le dossier existe
            if (!File::exists($storagePath)) {
                File::makeDirectory($storagePath, 0755, true);
            }
            
            foreach ($files as $file) {
                if ($file->isValid()) {
                    // Générer un nom de fichier unique
                    $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                    
                    // Déplacer le fichier vers le dossier de l'intervenant
                    $filePath = $file->storeAs($intervenantFolder, $fileName, 'public');
                    
                    // Enregistrer dans la table IntervenantFile
                    IntervenantFile::create([
                        'intervenant_id' => $intervenant->id,
                        'file_path' => $filePath,
                        'file_name' => $file->getClientOriginalName(),
                    ]);
                }
            }
        }
        
        // Gestion des intervenants liés avec les deux relations
        if ($request->has('linked_intervenants')) {
            $intervenantsLiesFrom = []; // Relations de cet intervenant vers les autres
            $intervenantsLiesTo = [];   // Relations des autres intervenants vers celui-ci
            
            foreach ($request->linked_intervenants as $linkedIntervenant) {
                if (!empty($linkedIntervenant['intervenant_id']) && 
                    !empty($linkedIntervenant['relation_from']) && 
                    !empty($linkedIntervenant['relation_to'])) {
                    
                    // Relation de cet intervenant vers l'intervenant lié
                    $intervenantsLiesFrom[$linkedIntervenant['intervenant_id']] = [
                        'relation' => $linkedIntervenant['relation_from'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    
                    // Relation inverse (de l'intervenant lié vers cet intervenant)
                    $intervenantsLiesTo[$linkedIntervenant['intervenant_id']] = [
                        'relation' => $linkedIntervenant['relation_to'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
            
            if (!empty($intervenantsLiesFrom)) {
                // Attacher les relations de cet intervenant vers les autres
                $intervenant->intervenantsLies()->attach($intervenantsLiesFrom);
                
                // Attacher les relations inverses
                foreach ($intervenantsLiesTo as $linkedId => $relationData) {
                    $linkedIntervenant = Intervenant::find($linkedId);
                    if ($linkedIntervenant) {
                        $linkedIntervenant->intervenantsLies()->attach($intervenant->id, $relationData);
                    }
                }
            }
        }

        DB::commit();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Intervenant créé avec succès.' . ($request->has('linked_intervenants') ? ' ' . count($request->linked_intervenants) . ' intervenant(s) lié(s).' : ''),
                'redirect_url' => route('intervenants.index')
            ]);
        }
        
        return redirect()->route('intervenants.index')->with('success', $message);

    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Erreur lors de la création de l\'intervenant', [
            'erreur' => $e->getMessage(),
            'fichier' => $e->getFile(),
            'ligne' => $e->getLine()
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la création de l\'intervenant.'
            ], 500);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Une erreur est survenue lors de la création de l\'intervenant. Veuillez réessayer.');
    }
}

    public function show(Intervenant $intervenant)
    {
        //return $intervenant->dossiers;
        return view('intervenants.show', compact('intervenant'));
    }

  public function update(UpdateIntervenantRequest $request, Intervenant $intervenant)
{
    try {
        DB::beginTransaction();

        // Récupérer les données validées (sans piece_jointe)
        $validatedData = $request->validated();
        unset($validatedData['piece_jointe']);
        
        // Mettre à jour l'intervenant avec les données validées
        $intervenant->update($validatedData);
        
        // Gestion des nouveaux fichiers
        if ($request->hasFile('piece_jointe')) {
            $files = $request->file('piece_jointe');
            
            // Créer le dossier pour cet intervenant s'il n'existe pas
            $intervenantFolder = 'intervenants/' . $intervenant->id;
            $storagePath = storage_path('app/public/' . $intervenantFolder);
            
            // S'assurer que le dossier existe
            if (!File::exists($storagePath)) {
                File::makeDirectory($storagePath, 0755, true);
            }
            
            foreach ($files as $file) {
                if ($file->isValid()) {
                    // Générer un nom de fichier unique
                    $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                    
                    // Déplacer le fichier vers le dossier de l'intervenant
                    $filePath = $file->storeAs($intervenantFolder, $fileName, 'public');
                    
                    // Enregistrer dans la table IntervenantFile
                    IntervenantFile::create([
                        'intervenant_id' => $intervenant->id,
                        'file_path' => $filePath,
                        'file_name' => $file->getClientOriginalName(),
                    ]);
                }
            }
        }
        
        // Gestion des intervenants liés avec les deux relations
        $intervenantsLiesFrom = []; // Relations de cet intervenant vers les autres
        $intervenantsLiesTo = [];   // Relations des autres intervenants vers celui-ci
        
        if ($request->has('linked_intervenants')) {
            foreach ($request->linked_intervenants as $linkedIntervenant) {
                if (!empty($linkedIntervenant['intervenant_id']) && 
                    !empty($linkedIntervenant['relation_from']) && 
                    !empty($linkedIntervenant['relation_to'])) {
                    
                    // Relation de cet intervenant vers l'intervenant lié
                    $intervenantsLiesFrom[$linkedIntervenant['intervenant_id']] = [
                        'relation' => $linkedIntervenant['relation_from'],
                        'updated_at' => now()
                    ];
                    
                    // Relation inverse (de l'intervenant lié vers cet intervenant)
                    $intervenantsLiesTo[$linkedIntervenant['intervenant_id']] = [
                        'relation' => $linkedIntervenant['relation_to'],
                        'updated_at' => now()
                    ];
                }
            }
        }
        
        // Synchroniser les relations de cet intervenant vers les autres
        $intervenant->intervenantsLies()->sync($intervenantsLiesFrom);
        
        // Synchroniser les relations inverses
        foreach ($intervenantsLiesTo as $linkedId => $relationData) {
            $linkedIntervenant = Intervenant::find($linkedId);
            if ($linkedIntervenant) {
                $linkedIntervenant->intervenantsLies()->syncWithoutDetaching([
                    $intervenant->id => $relationData
                ]);
            }
        }
        
        // Supprimer les relations qui ne sont plus présentes
        $currentLinkedIds = array_keys($intervenantsLiesFrom);
        $allLinkedIntervenants = $intervenant->intervenantsLies()->pluck('intervenant_id')->toArray();
        
        $intervenantsToDetach = array_diff($allLinkedIntervenants, $currentLinkedIds);
        
        foreach ($intervenantsToDetach as $intervenantIdToDetach) {
            // Détacher de cet intervenant
            $intervenant->intervenantsLies()->detach($intervenantIdToDetach);
            
            // Détacher la relation inverse
            $intervenantToDetach = Intervenant::find($intervenantIdToDetach);
            if ($intervenantToDetach) {
                $intervenantToDetach->intervenantsLies()->detach($intervenant->id);
            }
        }

        // Ancien format de gestion (pour compatibilité) - à supprimer éventuellement
        if ($request->has('intervenants_lies')) {
            $intervenantsLiesOld = [];
            foreach ($request->intervenants_lies as $intervenantLieId) {
                $intervenantsLiesOld[$intervenantLieId] = [
                    'relation' => 'représente',
                    'updated_at' => now()
                ];
            }
            $intervenant->intervenantsLies()->sync($intervenantsLiesOld);
        } elseif (!$request->has('linked_intervenants')) {
            // Si aucun format n'est utilisé, détacher toutes les relations
            $intervenant->intervenantsLies()->detach();
        }

        DB::commit();
        
        $message = 'Intervenant modifié avec succès.';
        if ($request->has('linked_intervenants')) {
            $message .= ' ' . count($request->linked_intervenants) . ' intervenant(s) lié(s).';
        }
        
        // Recharger les relations pour la réponse
        $intervenant->load(['formeSociale', 'dossiers', 'files', 'intervenantsLies']);
        
        if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Intervenant mis à jour avec succès!',
                    'redirect_url' => route('intervenants.show', $intervenant->id)
                ]);
            }

            return redirect()->back()->with('success', $message);
       
    } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de l\'intervenant. Veuillez réessayer.');
            throw $e;
        }
}

    public function destroy(Intervenant $intervenant, Request $request)
{
    try {
        DB::beginTransaction();
        // Pour les dossiers où cet intervenant est référencé
        if ($intervenant->dossiers()->count() > 0) {
            // Ou supprimer les dossiers associés (si c'est le comportement souhaité)
            $dossiers = $intervenant->dossiers()->delete();

        }

        // Pour les factures où cet intervenant est référencé
        if ($intervenant->factures()->count() > 0) {
            // Mettre à jour les factures ou les supprimer selon votre logique métier
            // $intervenant->factures()->update(['intervenant_id' => null]);
            
            // Ou supprimer les factures associées
            $intervenant->factures()->delete();
        }

        // 3. Supprimer l'intervenant
        $intervenantName = $intervenant->name; // ou le champ approprié
        $intervenant->delete();

        DB::commit();

        // Log de la suppression
        // Log::info('Intervenant supprimé avec suppression en cascade', [
        //     'intervenant_id' => $intervenant->id,
        //     'intervenant_name' => $intervenantName,
        //     'dossiers_supprimés' => $dossiersAssocies,
        //     'factures_supprimées' => $facturesAssociees,
        //     'supprimé_par' => auth()->id(),
        //     'supprimé_le' => now()->toDateTimeString()
        // ]);
         if ($request->ajax()) {
            return response()->json([
                'message' => 'Intervenant et toutes ses associations supprimés avec succès.',
            ], 200);
        }
        return redirect()->back()->with('success', 'Intervenant et toutes ses associations supprimés avec succès.');

    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Erreur lors de la suppression en cascade de l\'intervenant', [
            'intervenant_id' => $intervenant->id,
            'erreur' => $e->getMessage(),
            'fichier' => $e->getFile(),
            'ligne' => $e->getLine()
        ]);
        if ($request->ajax()) {
           return response()->json([
            'message' => 'Une erreur est survenue lors de la suppression de l\'intervenant.',
            'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne du serveur'
        ], 500);
        }
        return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression de l\'intervenant. Veuillez réessayer.');
        
    }
}

    public function search(Request $request): AnonymousResourceCollection
    {
        $query = Intervenant::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('identite_fr', 'like', "%{$search}%")
                  ->orWhere('identite_ar', 'like', "%{$search}%")
                  ->orWhere('mail1', 'like', "%{$search}%");
        }
        
        if ($request->has('categorie')) {
            $query->where('categorie', $request->categorie);
        }
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('archive')) {
            $query->where('archive', $request->boolean('archive'));
        }
        
        $intervenants = $query->with(['formeSociale', 'dossiers'])->paginate(10);
        
        return IntervenantResource::collection($intervenants);
    }

    public function attachDossier(Request $request, Intervenant $intervenant): JsonResponse
    {
        $request->validate([
            'dossier_id' => 'required|exists:dossiers,id',
            'role' => 'required|in:client,avocat,avocat_secondaire,adversaire,huissier,notaire,expert,juridiction,administrateur_judiciaire,mandataire_judiciaire,autre'
        ]);
        
        $intervenant->dossiers()->attach($request->dossier_id, [
            'role' => $request->role
        ]);
        
        return response()->json([
            'message' => 'Intervenant attaché au dossier avec succès.'
        ], 200);
    }

    public function destroyFile(Request $request, IntervenantFile $file)
{
    try {
        // Supprimer le fichier physique
        if (File::exists(storage_path('app/public/' . $file->file_path))) {
            File::delete(storage_path('app/public/' . $file->file_path));
        }
        
        // Supprimer l'enregistrement de la base de données
        $file->delete();
if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Fichier supprimé avec succès.'
            ], 200);
        }
        return redirect()->back()->with('success', 'Fichier supprimé avec succès.');

    } catch (\Exception $e) {
        if($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du fichier.'
            ], 500);
        }
        return redirect()->back()->with('error', 'Erreur lors de la suppression du fichier.');
    }
}

    public function downloadFile($file)
    {
        try {
            $intervenantFile = IntervenantFile::find($file);

            if (!$intervenantFile) {
                if (request()->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Fichier introuvable.'], 404);
                }
                return redirect()->back()->with('error', 'Fichier introuvable.');
            }

            $filePath = storage_path('app/public/' . $intervenantFile->file_path);

            if (!File::exists($filePath)) {
                if (request()->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Fichier physique introuvable sur le serveur.'], 404);
                }
                return redirect()->back()->with('error', 'Fichier introuvable sur le serveur.');
            }

            $downloadName = $intervenantFile->file_name ?? basename($filePath);

            return response()->download($filePath, $downloadName);
        } catch (\Exception $e) {
            Log::error('Erreur lors du téléchargement du fichier intervenant', [
                'file_id' => $file,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'user_id' => auth()->id() ?? null,
            ]);

            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Une erreur est survenue lors du téléchargement du fichier.'], 500);
            }

            return redirect()->back()->with('error', 'Une erreur est survenue lors du téléchargement du fichier. Veuillez réessayer.');
        }
    }

    public function displayFile($file)
    {
        try {
            $intervenantFile = IntervenantFile::find($file);

            if (!$intervenantFile) {
                if (request()->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Fichier introuvable.'], 404);
                }
                return redirect()->back()->with('error', 'Fichier introuvable.');
            }

            $filePath = storage_path('app/public/' . $intervenantFile->file_path);

            if (!File::exists($filePath)) {
                if (request()->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Fichier physique introuvable sur le serveur.'], 404);
                }
                return redirect()->back()->with('error', 'Fichier introuvable sur le serveur.');
            }

            $mime = File::mimeType($filePath) ?: 'application/octet-stream';
            $headers = [
                'Content-Type' => $mime,
                // force inline display
                'Content-Disposition' => 'inline; filename="' . ($intervenantFile->file_name ?? basename($filePath)) . '"'
            ];

            return response()->file($filePath, $headers);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage du fichier intervenant', [
                'file_id' => $file,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'user_id' => auth()->id() ?? null,
            ]);

            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Une erreur est survenue lors de l\'affichage du fichier.'], 500);
            }

            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'affichage du fichier. Veuillez réessayer.');
        }
    }



    public function getFiles(Intervenant $intervenant)
    {
         $dbFiles = IntervenantFile::where('intervenant_id', $intervenant->id)->get();

        $files = $dbFiles->map(function (IntervenantFile $f) {
            $exists = Storage::disk('public')->exists($f->file_path);
            $size = $exists ? Storage::disk('public')->size($f->file_path) : null;
            $lastModified = $exists ? Storage::disk('public')->lastModified($f->file_path) : null;

            return [
                'id' => $f->id,
                'name' => $f->file_name,
                'stored_name' => basename($f->file_path),
                'path' => $f->file_path,
                'extension' => pathinfo($f->file_path, PATHINFO_EXTENSION),
                'size' => $size,
                'type' => 'file',
                'last_modified' => $lastModified ? date('c', $lastModified) : null,
                'url' => $exists ? Storage::disk('public')->url($f->file_path) : null,
                'exists' => $exists,
                'created_at' => $f->created_at,
                'updated_at' => $f->updated_at,
            ];
        });

        return response()->json($files);
    }
}