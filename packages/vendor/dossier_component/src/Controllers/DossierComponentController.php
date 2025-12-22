<?php 

namespace Vendor\DossierComponent\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DossierComponentController extends Controller 
{
/**
 * Récupérer les utilisateurs liés au dossier (pour DataTable)
 */
public function getUtilisateursData($id)
{
    $dossier = Dossier::findOrFail($id);
    
    $utilisateurs = $dossier->users()->withPivot('role')->get();
    
    $data = $utilisateurs->map(function ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'fonction' => $user->fonction,
            'pivot_role' => $user->pivot->role ?? 'Collaborateur'
        ];
    });
    
    return response()->json([
        'success' => true,
        'data' => $data,
        'count' => $data->count()
    ]);
}

/**
 * Lier un utilisateur au dossier
 */
public function attachUtilisateur(Request $request, $id)
{
    $request->validate([
        'utilisateur_id' => 'required|exists:users,id',
        'role' => 'required|string|max:50'
    ]);
    
    $dossier = Dossier::findOrFail($id);
    $utilisateur = User::findOrFail($request->utilisateur_id);
    
    // Vérifier si l'utilisateur n'est pas déjà lié
    if ($dossier->users()->where('user_id', $request->utilisateur_id)->exists()) {
        return response()->json([
            'success' => false,
            'message' => 'Cet utilisateur est déjà lié à ce dossier.'
        ], 422);
    }
    
    // Lier l'utilisateur avec le rôle spécifié
    $dossier->users()->attach($request->utilisateur_id, [
        'role' => $request->role,
        'ordre' => $dossier->users()->count() + 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Utilisateur lié avec succès.',
        'data' => [
            'utilisateur' => [
                'id' => $utilisateur->id,
                'name' => $utilisateur->name,
                'email' => $utilisateur->email,
                'fonction' => $utilisateur->fonction
            ]
        ]
    ]);
}

/**
 * Détacher un utilisateur du dossier
 */
public function detachUtilisateur(Request $request, $id)
{
    $request->validate([
        'utilisateur_id' => 'required|exists:users,id'
    ]);
    
    $dossier = Dossier::findOrFail($id);
    $utilisateur = User::findOrFail($request->utilisateur_id);
    
    // Détacher l'utilisateur
    $dossier->users()->detach($request->utilisateur_id);
    
    return response()->json([
        'success' => true,
        'message' => 'Utilisateur détaché avec succès.',
        'data' => [
            'utilisateur' => [
                'id' => $utilisateur->id,
                'name' => $utilisateur->name,
                'email' => $utilisateur->email,
                'fonction' => $utilisateur->fonction
            ]
        ]
    ]);
}

/**
 * Mettre à jour les notes du dossier
 */
public function updateNotes(Request $request, $id)
{
    $request->validate([
        'notes' => 'nullable|string|max:5000'
    ]);
    
    $dossier = Dossier::findOrFail($id);
    
    // Mettre à jour les notes
    $dossier->note = $request->notes;
    $dossier->save();
    
    return response()->json([
        'success' => true,
        'message' => 'Notes mises à jour avec succès.',
        'data' => [
            'updated_at' => $dossier->updated_at->format('Y-m-d H:i:s'),
            'note' => $dossier->note
        ]
    ]);
}

/**
 * Récupérer les notes du dossier (pour pré-remplir l'édition)
 */
public function getNotes($id)
{
    $dossier = Dossier::findOrFail($id);
    
    return response()->json([
        'success' => true,
        'data' => [
            'note' => $dossier->note,
            'updated_at' => $dossier->updated_at->format('Y-m-d H:i:s')
        ]
    ]);
}
}