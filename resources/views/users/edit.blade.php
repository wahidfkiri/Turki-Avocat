@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modifier l'utilisateur</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Utilisateurs</a></li>
                        <li class="breadcrumb-item active">Modifier</li>
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
                     <!-- Alert Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Succès!</h5>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Erreur!</h5>
                {{ session('error') }}
            </div>
            @endif
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Modifier les informations de l'utilisateur</h3>
                        </div>
                        
                        <div class="card-body">
                            <!-- Navigation par onglets -->
                            <ul class="nav nav-tabs" id="userTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ session('active_tab', 'general') == 'general' ? 'active' : '' }}" 
                                       id="general-tab" data-toggle="tab" href="#general" role="tab" 
                                       aria-controls="general" aria-selected="true">
                                        <i class="fas fa-user"></i> Informations générales
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ session('active_tab') == 'security' ? 'active' : '' }}" 
                                       id="security-tab" data-toggle="tab" href="#security" role="tab" 
                                       aria-controls="security" aria-selected="false">
                                        <i class="fas fa-lock"></i> Sécurité
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ session('active_tab') == 'privileges' ? 'active' : '' }}" 
                                       id="privileges-tab" data-toggle="tab" href="#privileges" role="tab" 
                                       aria-controls="privileges" aria-selected="false">
                                        <i class="fas fa-key"></i> Privilèges
                                    </a>
                                </li>
                            </ul>

                            <!-- Contenu des onglets -->
                            <div class="tab-content" id="userTabsContent">
                                <!-- Onglet Informations générales -->
                                <div class="tab-pane fade {{ session('active_tab', 'general') == 'general' ? 'show active' : '' }}" 
                                     id="general" role="tabpanel" aria-labelledby="general-tab">
                                    <div class="p-3">
                                        <form action="{{ route('users.update.general', $user) }}" method="POST" id="generalForm">
                                            @csrf
                                            @method('PUT')
                                            
                                            <!-- Nom -->
                                            <div class="form-group">
                                                <label for="name">Nom complet *</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                       id="name" name="name" value="{{ old('name', $user->name) }}" 
                                                       placeholder="Entrez le nom complet" required>
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <!-- Email -->
                                            <div class="form-group">
                                                <label for="email">Adresse email *</label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                       id="email" name="email" value="{{ old('email', $user->email) }}" 
                                                       placeholder="Entrez l'adresse email" required>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <!-- Fonction -->
                                            <div class="form-group">
                                                <label for="fonction">Fonction *</label>
                                                <select class="form-control @error('fonction') is-invalid @enderror" 
                                                        id="fonction" name="fonction" required>
                                                    <option value="">Sélectionnez une fonction</option>
                                                    <option value="admin" {{ old('fonction', $user->fonction) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                                    <option value="avocat" {{ old('fonction', $user->fonction) == 'avocat' ? 'selected' : '' }}>Avocat</option>
                                                    <option value="secrétaire" {{ old('fonction', $user->fonction) == 'secrétaire' ? 'selected' : '' }}>Secrétaire</option>
                                                    <option value="clerc" {{ old('fonction', $user->fonction) == 'clerc' ? 'selected' : '' }}>Clerc</option>
                                                    <option value="stagiaire" {{ old('fonction', $user->fonction) == 'stagiaire' ? 'selected' : '' }}>Stagiaire</option>
                                                </select>
                                                @error('fonction')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <!-- Statut -->
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" 
                                                           id="is_active" name="is_active" value="1" 
                                                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_active">Utilisateur actif</label>
                                                </div>
                                            </div>

                                            <div class="form-group mt-4">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save"></i> Mettre à jour les informations
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Onglet Sécurité -->
                                <div class="tab-pane fade {{ session('active_tab') == 'security' ? 'show active' : '' }}" 
                                     id="security" role="tabpanel" aria-labelledby="security-tab">
                                    <div class="p-3">
                                        <form action="{{ route('users.update.security', $user) }}" method="POST" id="securityForm">
                                            @csrf
                                            @method('PUT')
                                            
                                            <!-- Mot de passe -->
                                            <div class="form-group">
                                                <label for="password">Nouveau mot de passe</label>
                                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                       id="password" name="password" 
                                                       placeholder="Laissez vide pour ne pas modifier">
                                                <small class="form-text text-muted">
                                                    Laissez ce champ vide si vous ne souhaitez pas modifier le mot de passe.
                                                </small>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <!-- Confirmation mot de passe -->
                                            <div class="form-group">
                                                <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
                                                <input type="password" class="form-control" 
                                                       id="password_confirmation" name="password_confirmation" 
                                                       placeholder="Confirmez le mot de passe">
                                            </div>

                                            <div class="alert alert-info">
                                                <h6><i class="icon fas fa-info"></i> Information</h6>
                                                Le mot de passe doit contenir au moins 8 caractères.
                                            </div>

                                            <div class="form-group mt-4">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-lock"></i> Mettre à jour la sécurité
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Onglet Privilèges -->
                                <div class="tab-pane fade {{ session('active_tab') == 'privileges' ? 'show active' : '' }}" 
                                     id="privileges" role="tabpanel" aria-labelledby="privileges-tab">
                                    <div class="p-3">
                                        <form action="{{ route('users.update.privileges', $user) }}" method="POST" id="privilegesForm">
                                            @csrf
                                            @method('PUT')
                                            
                                            <!-- Rôles -->
                                            <div class="form-group">
                                                <label for="roles">Rôle *</label>
                                                <select class="form-control @error('roles') is-invalid @enderror" 
                                                        id="roles" name="roles"
                                                        data-placeholder="Sélectionnez un rôle" style="width: 100%;" required>
                                                    <option value="">Sélectionnez un rôle</option>
                                                    @foreach($roles as $role)
                                                        @php
                                                            // Récupérer le premier rôle de l'utilisateur (puisqu'il n'en a qu'un)
                                                            $userRole = $user->roles->first();
                                                            $isSelected = old('roles', $userRole ? $userRole->name : '') == $role->name;
                                                        @endphp
                                                        <option value="{{ $role->name }}" {{ $isSelected ? 'selected' : '' }}>
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('roles')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <!-- Permissions/Privilèges -->
                                            <div class="form-group">
                                                <label>Privilèges/Permissions</label>
                                                <div class="alert alert-warning">
                                                    <small>
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        Les permissions sélectionnées ici écraseront les permissions du rôle. 
                                                        Laissez vide pour utiliser uniquement les permissions du rôle.
                                                    </small>
                                                </div>
                                                <div class="permissions-container" style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
                                                    <div class="row">
                                                        @php
                                                            // Récupérer toutes les permissions depuis la base de données
                                                            $allPermissions = \Spatie\Permission\Models\Permission::all();
                                                            
                                                            // Grouper les permissions par module
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
                                                                        
                                                            $permissionsToCheck = old('permissions', $userPermissions);
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
                                                                                
                                                                                // Vérifier si la permission doit être cochée
                                                                                $isChecked = in_array($permissionName, $permissionsToCheck);
                                                                            @endphp
                                                                            <div class="custom-control custom-checkbox mb-2">
                                                                                <input class="custom-control-input permission-checkbox" 
                                                                                       type="checkbox" 
                                                                                       id="permission_{{ $permission->id }}" 
                                                                                       name="permissions[]" 
                                                                                       value="{{ $permission->name }}"
                                                                                       {{ $isChecked ? 'checked' : '' }}>
                                                                                <label class="custom-control-label" for="permission_{{ $permission->id }}">
                                                                                    {{ ucfirst($permissionDisplay) }}
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @error('permissions')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group mt-4">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-key"></i> Mettre à jour les privilèges
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <a href="{{ url()->previous() }}" class="btn btn-default btn-lg">
                                <i class="fas fa-arrow-left"></i> Retour à la liste
                            </a>
                            
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
    .nav-tabs .nav-link {
        color: #6c757d;
        font-weight: 500;
    }
    .nav-tabs .nav-link.active {
        color: #495057;
        font-weight: 600;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
    }
    .tab-content {
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 0.25rem 0.25rem;
        background-color: #fff;
    }
    .permissions-container .card-header {
        cursor: pointer;
        background-color: #f8f9fa !important;
        transition: background-color 0.3s;
    }
    .permissions-container .card-header:hover {
        background-color: #e9ecef !important;
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
</style>
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {

    // Gestion des onglets - sauvegarder l'onglet actif
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href");
        localStorage.setItem('activeUserTab', target);
    });

    // Restaurer l'onglet actif depuis le localStorage ou la session
    var activeTab = localStorage.getItem('activeUserTab') || '{{ session('active_tab', '#general') }}';
    if (activeTab) {
        $('#userTabs a[href="' + activeTab + '"]').tab('show');
    }

    // Gestion de la soumission des formulaires
    $('form').on('submit', function() {
        // Sauvegarder l'onglet actif avant soumission
        var activeTab = $('#userTabs .nav-link.active').attr('href');
        localStorage.setItem('activeUserTab', activeTab);
    });

    // Sélectionner/désélectionner toutes les permissions d'un module
    $('.permissions-container .card-header').on('click', function() {
        var cardBody = $(this).closest('.card').find('.card-body');
        var checkboxes = cardBody.find('.permission-checkbox');
        var allChecked = checkboxes.length === checkboxes.filter(':checked').length;
        
        checkboxes.prop('checked', !allChecked);
    });
});

function confirmDelete(userId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')) {
        document.getElementById('delete-form-' + userId).submit();
    }
}
</script>
@endsection