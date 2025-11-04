@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Détails de l'utilisateur</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Utilisateurs</a></li>
                        <li class="breadcrumb-item active">Détails</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Informations de l'utilisateur</h3>
                            <div class="card-tools">
                                @can('edit_users')
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Informations générales -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="section-title">Informations personnelles</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Nom complet</th>
                                            <td>{{ $user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Adresse email</th>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Fonction</th>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ ucfirst($user->fonction) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Statut</th>
                                            <td>
                                                @if($user->is_active)
                                                    <span class="badge badge-success">Actif</span>
                                                @else
                                                    <span class="badge badge-danger">Inactif</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Date de création</th>
                                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Dernière modification</th>
                                            <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div class="col-md-6">
                                    <h5 class="section-title">Rôle et permissions</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Rôle principal</th>
                                            <td>
                                                @if($user->roles->count() > 0)
                                                    <span class="badge badge-primary">
                                                        {{ $user->roles->first()->name }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">Aucun rôle</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Nombre de permissions</th>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    {{ $user->permissions->count() }} permission(s)
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Permissions détaillées -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="section-title mb-3">Permissions détaillées</h5>
                                    <div class="permissions-container" style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
                                        <div class="row">
                                            @php
                                                // Récupérer toutes les permissions depuis la base de données
                                                $allPermissions = \Spatie\Permission\Models\Permission::all();
                                                
                                                // Grouper les permissions par module (même logique que edit.blade.php)
                                                $permissionsByModule = [];
                                                $moduleLabels = [
                                                    'users' => 'Utilisateurs',
                                                    'dossiers' => 'Dossiers',
                                                    'intervenants' => 'Intervenants',
                                                    'factures' => 'Factures',
                                                    'agendas' => 'Agendas',
                                                    'tasks' => 'Tâches',
                                                    'timesheets' => 'Feuilles de temps',
                                                    'admin' => 'Administration'
                                                ];
                                                
                                                foreach ($allPermissions as $permission) {
                                                    $permissionName = $permission->name;
                                                    
                                                    // Extraire le module du nom de permission
                                                    $parts = explode('_', $permissionName);
                                                    $action = $parts[0];
                                                    $module = $parts[1] ?? '';
                                                    
                                                    // Gérer les cas spéciaux pour les permissions d'administration
                                                    if (in_array($permissionName, ['access_admin_panel', 'manage_settings', 'view_reports', 'export_data'])) {
                                                        $module = 'admin';
                                                    }
                                                    
                                                    if ($module && isset($moduleLabels[$module])) {
                                                        $moduleLabel = $moduleLabels[$module];
                                                        if (!isset($permissionsByModule[$moduleLabel])) {
                                                            $permissionsByModule[$moduleLabel] = [];
                                                        }
                                                        $permissionsByModule[$moduleLabel][] = $permission;
                                                    }
                                                }
                                                
                                                // Labels pour les actions
                                                $actionLabels = [
                                                    'view' => 'Voir',
                                                    'create' => 'Créer',
                                                    'edit' => 'Modifier',
                                                    'delete' => 'Supprimer',
                                                    'access' => 'Accéder',
                                                    'manage' => 'Gérer'
                                                ];
                                                
                                                // Labels spéciaux pour certaines permissions
                                                $specialLabels = [
                                                    'access_admin_panel' => 'Accès panel admin',
                                                    'manage_settings' => 'Gérer paramètres',
                                                    'view_reports' => 'Voir rapports',
                                                    'export_data' => 'Exporter données'
                                                ];
                                                
                                                $userPermissions = $user->permissions->pluck('name')->toArray();
                                            @endphp

                                            @foreach($permissionsByModule as $module => $modulePermissions)
                                                <div class="col-md-6 mb-4">
                                                    <div class="card">
                                                        <div class="card-header bg-light py-2">
                                                            <h6 class="mb-0 font-weight-bold">{{ $module }}</h6>
                                                        </div>
                                                        <div class="card-body p-2">
                                                            @foreach($modulePermissions as $permission)
                                                                @php
                                                                    $permissionName = $permission->name;
                                                                    
                                                                    // Déterminer le label à afficher
                                                                    if (isset($specialLabels[$permissionName])) {
                                                                        $permissionDisplay = $specialLabels[$permissionName];
                                                                    } else {
                                                                        $parts = explode('_', $permissionName);
                                                                        $action = $parts[0];
                                                                        $resource = implode(' ', array_slice($parts, 1));
                                                                        
                                                                        $actionLabel = $actionLabels[$action] ?? ucfirst($action);
                                                                        $permissionDisplay = $actionLabel . ' ' . $resource;
                                                                    }
                                                                    
                                                                    // Vérifier si l'utilisateur a cette permission
                                                                    $hasPermission = in_array($permissionName, $userPermissions);
                                                                @endphp
                                                                <div class="custom-control custom-checkbox mb-2">
                                                                    <input class="custom-control-input" 
                                                                           type="checkbox" 
                                                                           id="permission_{{ $permission->id }}" 
                                                                           disabled
                                                                           {{ $hasPermission ? 'checked' : '' }}>
                                                                    <label class="custom-control-label {{ $hasPermission ? 'text-success font-weight-bold' : 'text-muted' }}" 
                                                                           for="permission_{{ $permission->id }}">
                                                                        {{ ucfirst($permissionDisplay) }}
                                                                        @if($hasPermission)
                                                                            <i class="fas fa-check-circle text-success ml-1"></i>
                                                                        @else
                                                                            <i class="fas fa-times-circle text-muted ml-1"></i>
                                                                        @endif
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <a href="{{ url()->previous() }}" class="btn btn-default btn-lg">
                                <i class="fas fa-arrow-left"></i> Retour à la liste
                            </a>
                            
                            @can('edit_users')
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-lg">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                            @endcan
                            
                            @can('delete_users')
                                <button type="button" class="btn btn-danger btn-lg float-right" 
                                        onclick="confirmDelete({{ $user->id }})">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            @endcan
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- Formulaire de suppression -->
@can('delete_users')
    <form id="delete-form-{{ $user->id }}" 
          action="{{ route('users.destroy', $user) }}" 
          method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endcan

<style>
    .section-title {
        color: #495057;
        border-bottom: 2px solid #007bff;
        padding-bottom: 8px;
        margin-bottom: 15px;
    }
    .table th {
        background-color: #f8f9fa;
    }
    .permissions-container .card-header {
        cursor: default;
        background-color: #f8f9fa !important;
    }
    .permissions-container .card {
        border: 1px solid #dee2e6;
    }
    .permissions-container .custom-control-label {
        font-size: 0.9rem;
    }
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }
    .badge {
        font-size: 0.85em;
    }
</style>

<script>
function confirmDelete(userId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')) {
        document.getElementById('delete-form-' + userId).submit();
    }
}
</script>
@endsection