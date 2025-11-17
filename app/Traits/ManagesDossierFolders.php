<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ManagesDossierFolders
{
    /**
     * Synchronise le dossier Storage pour un dossier donné :
     * - Si un dossier existe avec le même ID → rename si nom/numero changés
     * - Si aucun dossier pour cet ID → create
     * - Si déjà correct → do nothing
     */
    public function syncDossierFolder($dossier)
    {
        $newFolderName = $dossier->numero_dossier . '-' . $dossier->nom_dossier . '-' . $dossier->id;
        $basePath = 'dossiers';
        $newPath = $basePath . '/' . $newFolderName;

        // Liste les dossiers dans /dossiers
        $allFolders = Storage::disk('public')->directories($basePath);

        $existingFolder = null;

        // Cherche un dossier avec suffixe -ID
        foreach ($allFolders as $folder) {
            $parts = explode('/', $folder);
            $folderName = end($parts);

            if (preg_match('/-(\d+)$/', $folderName, $m)) {
                $foundId = (int)$m[1];

                if ($foundId === (int)$dossier->id) {
                    $existingFolder = $folder;
                    break;
                }
            }
        }

        // 1. Le dossier existe déjà avec le bon nom
        if ($existingFolder === $newPath) {
            return "Aucun changement : '$newFolderName' déjà correct.";
        }

        // 2. Le dossier existe mais doit être renommé
        if ($existingFolder) {
            Storage::disk('public')->move($existingFolder, $newPath);
            return "Dossier renommé en '$newFolderName'.";
        }

        // 3. Aucun dossier trouvé → créer
        Storage::disk('public')->makeDirectory($newPath);
        return "Dossier '$newFolderName' créé.";
    }
}
