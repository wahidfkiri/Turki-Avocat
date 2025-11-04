<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class ExplorerController extends Controller
{
    /**
     * Open folder in Windows Explorer
     */
    public function openFolder(Request $request)
    {
        try {
          
            $dossier = \App\Models\Dossier::find($request->dossierId);
            // Ensure the dossier exists before accessing its properties
            if (!$dossier) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dossier not found',
                    'path' => $request->dossierId ?? null
                ], 404);
            }
            // Prefer the path supplied by the request; otherwise fall back to a default UNC path.
            // Build a proper folder name with a separator between numero_dossier and id
            $folderName = $dossier->numero_dossier . '-' . $dossier->id;
            $localPath = '\\\\192.168.1.15\\dossiers\\' . $folderName;

            // if (empty($localPath)) {
            //     // Properly escaped UNC literal: \\server\share\path
            //     $localPath = '\\\\192.168.1.31\\dossiers\\DOS-2025-0015555-36';
            // }

            // If it's already a UNC/network path (starts with \\ or //), use it as-is,
            // otherwise convert the local path to a network path.
            if (substr($localPath, 0, 2) === '\\\\' || substr($localPath, 0, 2) === '//') {
                $networkPath = str_replace('/', '\\', $localPath);
            } else {
                // Convertir le chemin local en chemin réseau si nécessaire
                $networkPath = $this->convertToNetworkPath($localPath);
                // Normaliser les slashs
                $networkPath = str_replace('/', '\\', $networkPath);
            }

            $exitCode = null;
            $output = '';

            // Si le serveur exécute Windows, tenter d'ouvrir directement l'explorateur
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Utiliser cmd /c start pour ouvrir l'explorateur. Escaper l'argument.
                $escaped = escapeshellarg($networkPath);
                // start requires a title argument; fournir une chaîne vide ""
                $cmd = "cmd /c start \"\" $escaped";
                exec($cmd . ' 2>&1', $out, $exitCode);
                $output = isset($out) ? implode("\n", $out) : '';
            } else {
                // Sur autre OS, on ne peut pas ouvrir l'Explorateur Windows.
                // Tenter l'appel à la commande artisan si elle existe (peut échouer si non applicable)
                try {
                    $exitCode = Artisan::call('explorer:open', ['path' => $networkPath]);
                    $output = Artisan::output();
                } catch (\Throwable $t) {
                    // Ignorer, on retournera simplement le chemin réseau pour copie manuelle
                    Log::warning('artisan explorer:open unavailable or failed', ['error' => $t->getMessage()]);
                    $exitCode = 1;
                    $output = $t->getMessage();
                }
            }

            $success = $exitCode === 0 || $exitCode === null;

            return response()->json([
                'success' => $success,
                'message' => $success
                    ? 'L’Explorateur Windows est en cours d’ouverture...'
                    : (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN'
                        ? 'Impossible d’ouvrir l’Explorateur sur ce serveur. Copiez le chemin réseau ci‑dessous.'
                        : 'Échec de l’ouverture de l’Explorateur Windows'),
                'local_path' => $localPath,
                'network_path' => $networkPath,
                'output' => $output,
                'dossierName' => $folderName,
                'exit_code' => $exitCode
            ]);
        } catch (\Exception $e) {
            Log::error('Error in openFolder controller', [
                'path' => $request->path ?? null,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
                'path' => $request->path ?? ''
            ], 500);
        }
    }

public function openFolderNetwork(Request $request)
{
    try {
        $validated = $request->validate([
            'path' => 'required|string|max:500'
        ]);
        
        $path = $validated['path'];
        
        // Convertir le chemin local en chemin réseau
        $networkPath = $this->convertToNetworkPath($path);
        
        return response()->json([
            'success' => true,
            'message' => 'Use the network path below in Windows Explorer',
            'local_path' => $path,
            'network_path' => $networkPath,
            'instructions' => 'Copy the network path and paste in Windows Explorer'
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error in openFolder controller', [
            'path' => $request->path,
            'error' => $e->getMessage()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Convertir le chemin local en chemin réseau
 */
private function convertToNetworkPath(string $localPath): string
{
    $serverIP = '192.168.1.15'; // Votre IP serveur
    $localPath = str_replace('/', '\\', $localPath);
    
    // Mapping des chemins locaux vers les partages réseau
    $networkMappings = [
        '\\var\\www\\sta\\storage\\app\\public\\dossiers' => 'dossiers',
        // Ajoutez d'autres mappings si nécessaire
    ];
    
    foreach ($networkMappings as $local => $shareName) {
        if (str_starts_with($localPath, $local)) {
            $relativePath = str_replace($local, '', $localPath);
            $relativePath = ltrim($relativePath, '\\');
            
            return "\\\\{$serverIP}\\{$shareName}" . ($relativePath ? "\\{$relativePath}" : '');
        }
    }
    
    // Fallback: utiliser le partage par défaut
    return "\\\\{$serverIP}\\dossiers";
}
    
    /**
     * Show the open folder form
     */
    public function showForm()
    {
        return view('open-folder');
    }
}