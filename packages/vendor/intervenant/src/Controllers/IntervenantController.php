<?php

namespace Vendor\Intervenant\Controllers;

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
use Exception;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;


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
           'intervenant_id' => 'required|exists:intervenants,id',
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

    // public function downloadFile($file)
    // {
    //     try {
    //         $intervenantFile = IntervenantFile::find($file);

    //         if (!$intervenantFile) {
    //             if (request()->ajax()) {
    //                 return response()->json(['success' => false, 'message' => 'Fichier introuvable.'], 404);
    //             }
    //             return redirect()->back()->with('error', 'Fichier introuvable.');
    //         }

    //         $filePath = storage_path('app/public/' . $intervenantFile->file_path);

    //         if (!File::exists($filePath)) {
    //             if (request()->ajax()) {
    //                 return response()->json(['success' => false, 'message' => 'Fichier physique introuvable sur le serveur.'], 404);
    //             }
    //             return redirect()->back()->with('error', 'Fichier introuvable sur le serveur.');
    //         }

    //         $downloadName = $intervenantFile->file_name ?? basename($filePath);

    //         return response()->download($filePath, $downloadName);
    //     } catch (\Exception $e) {
    //         Log::error('Erreur lors du téléchargement du fichier intervenant', [
    //             'file_id' => $file,
    //             'message' => $e->getMessage(),
    //             'line' => $e->getLine(),
    //             'user_id' => auth()->id() ?? null,
    //         ]);

    //         if (request()->ajax()) {
    //             return response()->json(['success' => false, 'message' => 'Une erreur est survenue lors du téléchargement du fichier.'], 500);
    //         }

    //         return redirect()->back()->with('error', 'Une erreur est survenue lors du téléchargement du fichier. Veuillez réessayer.');
    //     }
    // }

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
    $baseDirectory = "intervenants/{$intervenant->id}";
    $baseDirectory1 = "{$intervenant->id}";
    $requestedPath = request('path', '');
    
    // Build the full directory path
    $directory = $requestedPath ? $baseDirectory . '/' . $requestedPath : $baseDirectory;
     $directory1 = $requestedPath ? $baseDirectory1 . '/' . $requestedPath : $baseDirectory1;
    
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
                    'original_path' => $directory1,
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
                    'original_path' => $directory1,
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


   public function uploadFiles(Request $request, Intervenant $intervenant)
{
    try {
        $request->validate([
            'files.*' => 'required|file', // 10MB max per file
        ]);

        $uploadedFiles = [];
        $baseDirectory = "intervenants/{$intervenant->id}";
        
        // Get the subdirectory path from the request
        $subDirectory = $request->input('path', '');
        
        // Build the full directory path
        $fullDirectory = $subDirectory ? $baseDirectory . '/' . $subDirectory : $baseDirectory;

        \Log::info("Upload attempt:");
        \Log::info("Intervenant: " . $intervenant->id);
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

public function viewFilePost(Request $request)
{
    $validated = $request->validate([
       'intervenant_id' => 'required|exists:intervenants,id',
        'file_path' => 'required|string',
    ]);

    $intervenant = Intervenant::findOrFail($validated['intervenant_id']);
    $filePath = base64_encode($validated['file_path']); // POST

    return redirect()->route('dossier.view', [
        'intervenant' => $intervenant->id,
        'file' => $filePath
    ]);
}

public function viewFileChrome(Request $request)
{
    $validated = $request->validate([
       'intervenant_id' => 'required|exists:intervenants,id',
        'file_path' => 'required|string',
    ]);

    $intervenant = Intervenant::findOrFail($validated['intervenant_id']);
    $filePath = $validated['file_path']; // POST

    $basePath = "intervenants/{$intervenant->id}";
    $fullPath = $filePath ? $basePath . '/' . $filePath : $basePath;

    // Check if the file exists
    if (!Storage::disk('public')->exists($fullPath)) {
        abort(404, 'File not found');
    }

    // Non-supported formats: return file directly
    $mimeType = Storage::disk('public')->mimeType($fullPath);
    $fileContent = Storage::disk('public')->get($fullPath);

    return response($fileContent, 200)->header('Content-Type', $mimeType);
}

public function viewFile($intervenantId, $file)
{
    try {
        $intervenant = Intervenant::findOrFail($intervenantId);
        $filePath = base64_decode($file);

        $basePath = "intervenants/{$intervenant->id}";
        $fullPath = $filePath ? $basePath . '/' . $filePath : $basePath;

        if (!Storage::disk('public')->exists($fullPath)) {
            abort(404, 'Fichier non trouvé');
        }

        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $onlyofficeFormats = ['docx', 'xlsx', 'pptx', 'pdf'];

        if (in_array($extension, $onlyofficeFormats)) {
            $fileUrl = asset("storage/$fullPath");

            // Generate safe hash key
            $key = md5($fullPath . '-' . (auth()->id() ?? '1'));
              // Clear any old cache for this key
            Cache::forget("onlyoffice_file_$key");
            Cache::put("onlyoffice_file_$key", $fullPath, 3600);

            $config = [
                "document" => [
                    "fileType" => $extension,
                    "key" => $key,
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

        // Non-supported formats: return file directly
        $mimeType = Storage::disk('public')->mimeType($fullPath);
        $fileContent = Storage::disk('public')->get($fullPath);

        return response($fileContent, 200)->header('Content-Type', $mimeType);

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
            'intervenant_id' => 'required|exists:intervenants,id',
            'file_path' => 'required|string'
        ]);

        $intervenant = Intervenant::findOrFail($validated['intervenant_id']);
        $filePath = $validated['file_path'];
        
        // Construire le chemin complet
        $basePath = "intervenants/{$intervenant->id}";
        $fullPath = $filePath ? $basePath . '/' . $filePath : $basePath;

        \Log::info("Tentative de téléchargement:");
        \Log::info("Intervenant ID: " . $intervenant->id);
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

public function createFile(Request $request)
{
    $validated = $request->validate([
       'intervenant_id' => 'required|exists:intervenants,id',
        'file_name' => 'required|string', // ex: "Nouveau.docx"
    ]);

    $intervenant = Intervenant::findOrFail($validated['intervenant_id']);
    $fileName = $validated['file_name'];
    

    // Chemin complet dans storage
    $basePath = "intervenants/{$intervenant->id}";
    $fullPath = $basePath . '/' . $fileName;

    // Crée le dossier s'il n'existe pas
    if (!Storage::disk('public')->exists($basePath)) {
        Storage::disk('public')->makeDirectory($basePath);
    }

    // Crée le fichier vide si n'existe pas
    if (!Storage::disk('public')->exists($fullPath)) {
        // Fichier vide pour docx, xlsx, pptx peut être un vrai template minimal
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($extension === 'docx') {
            // Crée un document Word minimal vide
            $template = Storage::disk('local')->get('empty.docx'); // mettez un docx vide dans storage/app/empty.docx
            Storage::disk('public')->put($fullPath, $template);
        } elseif ($extension === 'xlsx') {
            $template = Storage::disk('local')->get('empty.xlsx');
            Storage::disk('public')->put($fullPath, $template);
        } elseif ($extension === 'pptx') {
            $template = Storage::disk('local')->get('empty.pptx');
            Storage::disk('public')->put($fullPath, $template);
        } else {
            // Pour PDF ou autre: fichier vide simple
            Storage::disk('public')->put($fullPath, '');
        }
    }

    // Encode le chemin pour URL
    $filePathEncoded = base64_encode($fileName);

    // Redirige vers la vue OnlyOffice pour édition
    return redirect()->route('intervenant.view', [
        'intervenant' => $intervenant->id,
        'file' => $filePathEncoded
    ]);
}


public function createFileBackend(Request $request)
{
    $validated = $request->validate([
       'intervenant_id' => 'required|exists:intervenants,id',
        'file_name' => 'required|string', // ex: "Report.docx"
    ]);

    $intervenant = Intervenant::findOrFail($validated['intervenant_id']);
    $fileName = $validated['file_name'];
    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $path = $request->input('path', ''); // Récupère le chemin depuis la requête, par défaut vide
    $basePath = "intervenants/{$intervenant->id}";
    $fullPath = $basePath . '/' . ($path ? $path . '/' : '') . $fileName;

    // Make directory if it doesn't exist
    if (!Storage::disk('public')->exists($basePath)) {
        Storage::disk('public')->makeDirectory($basePath);
    }

    // Minimal templates for each type
    $templates = [
        'docx' => 'templates/empty.docx',
        'xlsx' => 'templates/empty.xlsx',
        'pptx' => 'templates/empty.pptx',
    ];

    if (!array_key_exists($extension, $templates)) {
        return response()->json(['error' => 'Extension non supportée'], 422);
    }

    // Copy the template file to create new file
    if (!Storage::disk('public')->exists($fullPath)) {
        Storage::disk('public')->copy($templates[$extension], $fullPath);
    }

    return response()->json([
        'success' => true,
        'file_path' => $fullPath,
        'file_url' => asset("storage/$fullPath"),
        'message' => "Le fichier $fileName a été créé avec succès."
    ]);
}


public function deleteFile(Request $request, Intervenant $intervenant)
{
    try {
        $request->validate([
            'file_path' => 'required|string',
            'file_type' => 'required|in:file,folder'
        ]);

        $basePath = "intervenants/{$intervenant->id}";
        $filePath = $request->file_path;
        $fullPath = $basePath . '/' . $filePath;

        \Log::info("Tentative de suppression:");
        \Log::info("Intervenant: " . $intervenant->id);
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

public function renameFile(Request $request, Intervenant $intervenant)
{
    try {
        $request->validate([
            'file_path' => 'required|string',
            'file_type' => 'required|in:file,folder',
            'new_name' => 'required|string|max:255'
        ]);

        $basePath = "intervenants/{$intervenant->id}";
        $oldFilePath = $request->file_path;
        $newName = $request->new_name;
        $fileType = $request->file_type;

        $oldFullPath = $basePath . '/' . $oldFilePath;
        
        // Construire le nouveau chemin
        $directory = dirname($oldFilePath);
        $newFilePath = $directory === '.' ? $newName : $directory . '/' . $newName;
        $newFullPath = $basePath . '/' . $newFilePath;

        \Log::info("Tentative de renommage:");
        \Log::info("Intervenant: " . $intervenant->id);
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

public function moveFile(Request $request, Intervenant $intervenant)
{
    try {
        $request->validate([
            'file_path' => 'required|string',
            'file_type' => 'required|in:file,folder',
            'target_path' => 'sometimes|string|nullable'
        ]);

        $basePath = "intervenants/{$intervenant->id}";
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

public function getFoldersTree(Intervenant $intervenant, Request $request)
{
    try {
        $baseDirectory = "intervenants/{$intervenant->id}";
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

public function createFolder(Request $request, Intervenant $intervenant)
{
    try {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'path' => 'sometimes|string|nullable'
        ]);

        $basePath = "intervenants/{$intervenant->id}";
        $folderName = $request->folder_name;
        $parentPath = $request->path ?? '';

        // Construire le chemin complet
        $fullPath = $parentPath ? $basePath . '/' . $parentPath . '/' . $folderName : $basePath . '/' . $folderName;

        \Log::info("Tentative de création de dossier:");
        \Log::info("Intervenant: " . $intervenant->id);
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
public function getFileUrl(Request $request, Intervenant $intervenant)
{
    try {
        $request->validate([
            'file_path' => 'required|string'
        ]);

        $basePath = "intervenants/{$intervenant->id}";
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
public function uploadFolder(Request $request, Intervenant $intervenant)
{
    try {
        $request->validate([
            'folder' => 'required', // Le dossier avec sa structure
            'path' => 'sometimes|string|nullable' // Chemin de destination
        ]);

        $baseDirectory = "intervenants/{$intervenant->id}";
        $subDirectory = $request->input('path', '');
        $fullDirectory = $subDirectory ? $baseDirectory . '/' . $subDirectory : $baseDirectory;

        \Log::info("Upload de dossier:");
        \Log::info("Intervenant: " . $intervenant->id);
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
