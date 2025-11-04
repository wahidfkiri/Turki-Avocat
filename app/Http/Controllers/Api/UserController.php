<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateUserGeneralRequest;
use App\Http\Requests\UpdateUserSecurityRequest;
use App\Http\Requests\UpdateUserPrivilegesRequest;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
      if(!auth()->user()->hasPermission('view_users')){
        abort(403, 'Unauthorized action.');
        }
        $users = User::with('roles')->where('id', '!=', auth()->id())->get();
        $roles = Role::all();
        return view('users.index', compact('users','roles'));
    }

    public function create()
    {
       if(!auth()->user()->hasPermission('create_users')){
           abort(403, 'Unauthorized action.');
       }

        $roles = Role::all();

        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
{
   if(!auth()->user()->hasPermission('create_users')){
         abort(403, 'Unauthorized action.');
    }
    
    try {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        
        // Utiliser une transaction pour s'assurer de l'intégrité des données
        DB::transaction(function () use ($validated, $request) {
            $user = User::create($validated);

            if($request->has('is_active')){
                $user->is_active = true;
            } else {
                $user->is_active = false;
            }
            $user->save();

            // Create Email user settings
            $user->emailSetting()->create([
                'imap_host' => env('IMAP_HOST'),
                'imap_port' => env('IMAP_PORT'),
                'imap_encryption' => env('IMAP_ENCRYPTION'),
                'imap_username' => $user->email,
                'imap_password' => $request->password,
                'smtp_host' => env('MAIL_HOST'),
                'smtp_port' => env('MAIL_PORT'),
                'smtp_encryption' => env('MAIL_ENCRYPTION'),
                'smtp_username' => $user->email,
                'smtp_password' => $request->password,
            ]);

            // Synchroniser les rôles
            if ($request->has('roles')) {
                $user->syncRoles($request->roles);
            }

            // Synchroniser les permissions directes
            if ($request->has('permissions')) {
                $user->syncPermissions($request->permissions);
            }
        });

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès.');

    } catch (\Exception $e) {
        Log::error('Erreur lors de la création de l\'utilisateur: ' . $e->getMessage());
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Une erreur est survenue lors de la création de l\'utilisateur.');
    }
}

    public function show(User $user)
    {
        if (!auth()->user()->hasPermission('view_users')) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
{
    if(!auth()->user()->hasPermission('edit_users')){
        abort(403, 'Unauthorized action.');
    }
    
    $roles = Role::all();
    return view('users.edit', compact('user', 'roles'));
}

    public function update(UpdateUserRequest $request, User $user)
{
    if(!auth()->user()->hasPermission('edit_users')){
        abort(403, 'Unauthorized action.');
    }   
    
    $validated = $request->validated();
    
    // Séparer les données par onglets
    $generalData = [
        'name' => $validated['name'],
        'email' => $validated['email'],
        'fonction' => $validated['fonction'],
        'is_active' => $request->has('is_active') ? true : false,
    ];
    
    // Ne mettre à jour le mot de passe que s'il est fourni (onglet Sécurité)
    if ($request->filled('password')) {
        $generalData['password'] = Hash::make($validated['password']);
    }
    
    $user->update($generalData);

    if($request->has('is_active')){
        $user->is_active = true;
    } else {
        $user->is_active = false;
    }
    $user->save();
    
    // Synchroniser les rôles (onglet Privilèges)
    if ($request->has('roles')) {
        $user->syncRoles($request->roles);
    }

    // Synchroniser les permissions directes (onglet Privilèges)
    if ($request->has('permissions')) {
        $user->syncPermissions($request->permissions);
    } else {
        // Si aucune permission n'est sélectionnée, supprimer toutes les permissions directes
        $user->syncPermissions([]);
    }

    return redirect()->route('users.index')
        ->with('success', 'Utilisateur mis à jour avec succès.');
}

    public function destroy(User $user): JsonResponse
    {
        if(!auth()->user()->hasPermission('delete_users')){
            abort(403, 'Unauthorized action.');
        }

        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'Vous ne pouvez pas supprimer votre propre compte.'
            ], 403);
        }
        
        $user->delete();
        
        return response()->json([
            'message' => 'Utilisateur supprimé avec succès.'
        ], 200);
    }

    public function search(Request $request): AnonymousResourceCollection
    {
        $query = User::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('fonction', 'like', "%{$search}%");
        }
        
        if ($request->has('fonction')) {
            $query->where('fonction', $request->fonction);
        }
        
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }
        
        $users = $query->with('roles')->paginate(10);
        
        return UserResource::collection($users);
    }

    public function updateGeneralInfo(UpdateUserGeneralRequest $request, User $user)
{
    if(!auth()->user()->hasPermission('edit_users')){
        abort(403, 'Unauthorized action.');
    }
    
    $validated = $request->validated();
    
    $generalData = [
        'name' => $validated['name'],
        'email' => $validated['email'],
        'fonction' => $validated['fonction'],
        'is_active' => $request->has('is_active') ? true : false,
    ];
    
    $user->update($generalData);

    return redirect()->route('users.edit', $user)
        ->with('success', 'Informations générales mises à jour avec succès.')
        ->with('active_tab', 'general');
}

public function updateSecurity(UpdateUserSecurityRequest $request, User $user)
{
    if(!auth()->user()->hasPermission('edit_users')){
        abort(403, 'Unauthorized action.');
    }
    
    $validated = $request->validated();
    
    $securityData = [];
    
    // Ne mettre à jour le mot de passe que s'il est fourni
    if ($request->filled('password')) {
        $securityData['password'] = Hash::make($validated['password']);
    }
    
    if (!empty($securityData)) {
        $user->update($securityData);
    }

    // Update Email Settings password if changed
    if ($request->filled('password')) {
        $emailSetting = $user->emailSetting;
        if ($emailSetting) {
            $emailSetting->imap_password = $validated['password'];
            $emailSetting->smtp_password = $validated['password'];
            $emailSetting->save();
        }else{
             $user->emailSetting()->create([
                'imap_host' => 'mailbox.nextstep-it.com',
                'imap_port' => '993',
                'imap_encryption' => 'ssl',
                'imap_username' => $user->email,
                'imap_password' => $validated['password'],
                'smtp_host' => 'smtp.nextstep-it.com',
                'smtp_port' => '465',
                'smtp_encryption' => 'ssl',
                'smtp_username' => $user->email,
                'smtp_password' => $validated['password'],
            ]);
        }
    }

    return redirect()->route('users.edit', $user)
        ->with('success', 'Paramètres de sécurité mis à jour avec succès.')
        ->with('active_tab', 'security');
}

public function updatePrivileges(UpdateUserPrivilegesRequest $request, User $user)
{
    if(!auth()->user()->hasPermission('edit_users')){
        abort(403, 'Unauthorized action.');
    }   
    
    $validated = $request->validated();
    
    // Synchroniser les rôles
    if ($request->has('roles')) {
        $user->syncRoles($request->roles);
    }

    // Synchroniser les permissions directes
    if ($request->has('permissions')) {
        $user->syncPermissions($request->permissions);
    } else {
        // Si aucune permission n'est sélectionnée, supprimer toutes les permissions directes
        $user->syncPermissions([]);
    }

    return redirect()->route('users.edit', $user)
        ->with('success', 'Privilèges mis à jour avec succès.')
        ->with('active_tab', 'privileges');
}
}