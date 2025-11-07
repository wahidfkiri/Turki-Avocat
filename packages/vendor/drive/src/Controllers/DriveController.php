<?php 

namespace Vendor\Drive\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

class DriveController extends Controller
{
    public function index()
    { 
        return 'Hello from DriveController';
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
            'files.*' => 'required|file|max:10240', // 10MB max per file
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
        if (Storage::disk('public')->size($fullPath) === 0) {
            \Log::error("Chemin point vers un dossier: " . $fullPath);
            return response()->json([
                'error' => 'Impossible de télécharger un dossier'
            ], 400);
        }

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
}
