<?php
// app/Services/EmailDossierService.php

namespace App\Services;

use App\Models\Dossier;
use App\Models\EmailDossier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EmailDossierService
{
    /**
     * Attacher des emails à un dossier
     */
    public function attachEmailsToDossier($dossierId, array $emailUids, $folderName, $emailData = [])
    {
        try {
            DB::beginTransaction();

            $dossier = Dossier::findOrFail($dossierId);
            $userId = Auth::id(); // Récupérer l'ID de l'utilisateur connecté
            $attachedCount = 0;

            foreach ($emailUids as $emailUid) {
                // Vérifier si l'email n'est pas déjà attaché à ce dossier par cet utilisateur
                $existing = EmailDossier::where('user_id', $userId)
                    ->where('dossier_id', $dossierId)
                    ->where('email_uid', $emailUid)
                    ->where('folder_name', $folderName)
                    ->exists();

                if (!$existing) {
                    // Récupérer les données de l'email si disponibles
                    $emailInfo = $emailData[$emailUid] ?? [];

                    EmailDossier::create([
                        'user_id' => $userId,
                        'dossier_id' => $dossierId,
                        'email_uid' => $emailUid,
                        'folder_name' => $folderName,
                        'subject' => $emailInfo['subject'] ?? null,
                        'from' => $emailInfo['from'] ?? null,
                        'email_date' => $emailInfo['date'] ?? null,
                    ]);

                    $attachedCount++;
                }
            }

            DB::commit();

            return [
                'success' => true,
                'attached_count' => $attachedCount,
                'message' => "{$attachedCount} email(s) attaché(s) au dossier '{$dossier->nom}'"
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'attachement des emails au dossier', [
                'user_id' => Auth::id(),
                'dossier_id' => $dossierId,
                'email_uids' => $emailUids,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Erreur lors de l\'attachement des emails: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Détacher des emails d'un dossier
     */
    public function detachEmailsFromDossier($dossierId, array $emailUids)
    {
        try {
            $userId = Auth::id();

            $deletedCount = EmailDossier::where('user_id', $userId)
                ->where('dossier_id', $dossierId)
                ->whereIn('email_uid', $emailUids)
                ->delete();

            return [
                'success' => true,
                'detached_count' => $deletedCount,
                'message' => "{$deletedCount} email(s) détaché(s) du dossier"
            ];

        } catch (\Exception $e) {
            Log::error('Erreur lors du détachement des emails du dossier', [
                'user_id' => Auth::id(),
                'dossier_id' => $dossierId,
                'email_uids' => $emailUids,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Erreur lors du détachement des emails: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer les emails attachés à un dossier pour l'utilisateur connecté
     */
    public function getAttachedEmails($dossierId, $perPage = 15)
    {
        $userId = Auth::id();

        return EmailDossier::with('dossier')
            ->where('user_id', $userId)
            ->where('dossier_id', $dossierId)
            ->orderBy('email_date', 'desc')
            ->paginate($perPage);
    }

    /**
     * Vérifier si un email est attaché à un dossier par l'utilisateur connecté
     */
    public function isEmailAttachedToDossier($emailUid, $folderName, $dossierId = null)
    {
        $userId = Auth::id();
        
        $query = EmailDossier::where('user_id', $userId)
            ->where('email_uid', $emailUid)
            ->where('folder_name', $folderName);

        if ($dossierId) {
            $query->where('dossier_id', $dossierId);
        }

        return $query->exists();
    }

    /**
     * Récupérer les dossiers d'un email pour l'utilisateur connecté
     */
    public function getEmailDossiers($emailUid, $folderName)
    {
        $userId = Auth::id();

        return EmailDossier::with('dossier')
            ->where('user_id', $userId)
            ->where('email_uid', $emailUid)
            ->where('folder_name', $folderName)
            ->get()
            ->pluck('dossier');
    }

    /**
     * Migrer les données existantes vers le nouveau système avec user_id
     */
    public function migrateExistingData($userId)
    {
        try {
            $migratedCount = EmailDossier::whereNull('user_id')
                ->update(['user_id' => $userId]);

            return $migratedCount;

        } catch (\Exception $e) {
            Log::error('Erreur lors de la migration des données email_dossier', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);

            return 0;
        }
    }
}