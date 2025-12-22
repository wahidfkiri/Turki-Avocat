@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modifier le Dossier</h1>
                             <br><p>{{$dossier->numero_dossier}}</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dossiers.index') }}">Dossiers</a></li>
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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Modification du dossier</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('dossiers.update', $dossier) }}" method="POST" id="dossierForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <!-- Alert Messages -->
                                @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <!-- Navigation par onglets -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <ul class="nav nav-tabs" id="dossierTabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="generale-tab" data-toggle="tab" href="#generale" role="tab" aria-controls="generale" aria-selected="true">
                                                    <i class="fas fa-info-circle"></i> Générale
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="juridique-tab" data-toggle="tab" href="#juridique" role="tab" aria-controls="juridique" aria-selected="false">
                                                    <i class="fas fa-gavel"></i> Information Juridique
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="intervenants-tab" data-toggle="tab" href="#intervenants" role="tab" aria-controls="intervenants" aria-selected="false">
                                                    <i class="fas fa-users"></i> Intervenants
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="dossiers-tab" data-toggle="tab" href="#dossiers" role="tab" aria-controls="dossiers" aria-selected="false">
                                                    <i class="fas fa-folder"></i> Dossiers Liés
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="equipe-tab" data-toggle="tab" href="#equipe" role="tab" aria-controls="equipe" aria-selected="false">
                                                    <i class="fas fa-user-shield"></i> Équipe
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="notes-tab" data-toggle="tab" href="#notes" role="tab" aria-controls="notes" aria-selected="false">
                                                    <i class="fas fa-sticky-note"></i> Notes 
                                                </a>
                                            </li>
                                        </ul>
                                        
                                        <div class="tab-content" id="dossierTabsContent">
                                            <!-- Onglet Générale -->
                                            <div class="tab-pane fade show active" id="generale" role="tabpanel" aria-labelledby="generale-tab">
                                                <div class="p-3">
                                                    <!-- Informations de base -->
                                                    <h5 class="text-primary mb-3"><i class="fas fa-folder"></i> Informations de base</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="numero_dossier">Numéro du dossier *</label>
                                                                <input type="text" class="form-control @error('numero_dossier') is-invalid @enderror" 
                                                                       id="numero_dossier" name="numero_dossier" 
                                                                       value="{{ old('numero_dossier', $dossier->numero_dossier) }}" 
                                                                       placeholder="Ex: DOS-2024-001" required>
                                                                @error('numero_dossier')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="date_entree">Date d'entrée *</label>
                                                                <input type="date" class="form-control @error('date_entree') is-invalid @enderror" 
                                                                       id="date_entree" name="date_entree" 
                                                                       value="{{ old('date_entree', $dossier->date_entree->format('Y-m-d')) }}" required>
                                                                @error('date_entree')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="nom_dossier">Nom du dossier *</label>
                                                                <input type="text" class="form-control @error('nom_dossier') is-invalid @enderror" 
                                                                       id="nom_dossier" name="nom_dossier" 
                                                                       value="{{ old('nom_dossier', $dossier->nom_dossier) }}" 
                                                                       placeholder="Intitulé complet du dossier" required>
                                                                @error('nom_dossier')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="objet">Objet du dossier</label>
                                                                <input type="text" class="form-control @error('objet') is-invalid @enderror" 
                                                                          id="objet" name="objet" placeholder="Description détaillée de l'objet du dossier" value="{{ old('objet', $dossier->objet) }}">
                                                                @error('objet')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Domaines -->
<h5 class="text-primary mb-3 mt-4"><i class="fas fa-tags"></i> Classification</h5>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="domaine_id">Domaine</label>
                <a href="#" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#addDomaineModal">
                    <i class="fas fa-plus"></i> Ajouter
                </a>
            </div>
            <select class="form-control @error('domaine_id') is-invalid @enderror" 
                    id="domaine_id" name="domaine_id">
                <option value="">Sélectionnez un domaine</option>
                @foreach($domaines as $domaine)
                    <option value="{{ $domaine->id }}" {{ $dossier->domaine_id == $domaine->id ? 'selected' : '' }}>
                        {{ $domaine->nom }}
                    </option>
                @endforeach
            </select>
            @error('domaine_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="sous_domaine_id">Sous-domaine</label>
                <a href="#" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#addSousDomaineModal">
                    <i class="fas fa-plus"></i> Ajouter
                </a>
            </div>
            <select class="form-control @error('sous_domaine_id') is-invalid @enderror" 
                    id="sous_domaine_id" name="sous_domaine_id">
                @if($dossier->domaine_id)
                    <option value="">Sélectionnez un sous-domaine</option>
                    @foreach($dossier->domaine->sousDomaines as $sousDomaine)
                        <option value="{{ $sousDomaine->id }}" {{ $dossier->sous_domaine_id == $sousDomaine->id ? 'selected' : '' }}>
                            {{ $sousDomaine->nom }}
                        </option>
                    @endforeach
                @else
                    <option value="">Sélectionnez d'abord un domaine</option>
                @endif
            </select>
            @error('sous_domaine_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>

                                                    <!-- Type de dossier -->
                                                    <h5 class="text-primary mb-3 mt-4"><i class="fas fa-balance-scale"></i> Type de dossier</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input class="custom-control-input" type="checkbox" 
                                                                           id="conseil" name="conseil" value="1" 
                                                                           {{ old('conseil', $dossier->conseil) ? 'checked' : '' }}>
                                                                    <label for="conseil" class="custom-control-label">
                                                                        Dossier de conseil
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input class="custom-control-input" type="checkbox" 
                                                                           id="contentieux" name="contentieux" value="1" 
                                                                           {{ old('contentieux', $dossier->contentieux) ? 'checked' : '' }}>
                                                                    <label for="contentieux" class="custom-control-label">
                                                                        Dossier contentieux
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    
                                                    <!-- Archivage -->
                                                    <h5 class="text-primary mb-3 mt-4"><i class="fas fa-archive"></i> Archivage</h5>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input class="custom-control-input" type="checkbox" 
                                                                           id="archive" name="archive" value="1" 
                                                                           {{ old('archive', $dossier->archive) ? 'checked' : '' }}>
                                                                    <label for="archive" class="custom-control-label">
                                                                        Marquer comme archivé
                                                                    </label>
                                                                </div>
                                                                <small class="form-text text-muted">
                                                                    Si coché, ce dossier sera marqué comme archivé.
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="alert alert-info">
                                                        <h5><i class="icon fas fa-info"></i> Informations</h5>
                                                        <p class="mb-0">
                                                            Utilisez cet espace pour noter toutes informations supplémentaires concernant ce dossier.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Onglet Information Juridique -->
                                            <div class="tab-pane fade" id="juridique" role="tabpanel" aria-labelledby="juridique-tab">
                                                <div class="p-3">
                                                    <!-- Informations juridiques -->
                                                    <h5 class="text-primary mb-3"><i class="fas fa-scale-balanced"></i> Informations sur la procédure</h5>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="numero_role">Numéro de rôle</label>
                                                                <input type="text" class="form-control @error('numero_role') is-invalid @enderror" 
                                                                       id="numero_role" name="numero_role" 
                                                                       value="{{ old('numero_role', $dossier->numero_role) }}" 
                                                                       placeholder="Numéro attribué par la juridiction">
                                                                @error('numero_role')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="chambre">Chambre</label>
                                                                <select class="form-control @error('chambre') is-invalid @enderror" 
                                                                        id="chambre" name="chambre">
                                                                    <option value="">Sélectionnez une chambre</option>
                                                                    @foreach(\App\Models\Chambre::all() as $chambre)
                                                                        <option value="{{ $chambre->name }}" @if($chambre->name == $dossier->chambre) selected @endif>
                                                                            {{ $chambre->name }}
                                                                        </option>
                                                                        @endforeach
                                                                    
                                                                </select>
                                                                @error('chambre')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="numero_chambre">Numéro de chambre</label>
                                                                <input type="text" class="form-control @error('numero_chambre') is-invalid @enderror" 
                                                                       id="numero_chambre" name="numero_chambre" 
                                                                       value="{{ old('numero_chambre', $dossier->numero_chambre) }}" 
                                                                       placeholder="N° de chambre">
                                                                @error('numero_chambre')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="numero_parquet">Numéro de parquet</label>
                                                                <input type="text" class="form-control @error('numero_parquet') is-invalid @enderror" 
                                                                       id="numero_parquet" name="numero_parquet" 
                                                                       value="{{ old('numero_parquet', $dossier->numero_parquet) }}" 
                                                                       placeholder="N° de parquet">
                                                                @error('numero_parquet')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="numero_instruction">Numéro d'instruction</label>
                                                                <input type="text" class="form-control @error('numero_instruction') is-invalid @enderror" 
                                                                       id="numero_instruction" name="numero_instruction" 
                                                                       value="{{ old('numero_instruction', $dossier->numero_instruction) }}" 
                                                                       placeholder="N° d'instruction">
                                                                @error('numero_instruction')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="numero_plainte">Numéro de plainte</label>
                                                                <input type="text" class="form-control @error('numero_plainte') is-invalid @enderror" 
                                                                       id="numero_plainte" name="numero_plainte" 
                                                                       value="{{ old('numero_plainte', $dossier->numero_plainte) }}" 
                                                                       placeholder="N° de plainte">
                                                                @error('numero_plainte')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        <!-- Onglet Dossiers -->
                                        <x-dossier.tab-list :dossier="$dossier" :dossiers="$dossiers"/>

                                         <!-- Onglet Intervenants -->
<div class="tab-pane fade" id="intervenants" role="tabpanel" aria-labelledby="intervenants-tab">
    <div class="p-3">
        <h5 class="text-primary mb-3"><i class="fas fa-handshake"></i> Gestion des intervenants</h5>
        
        <!-- Alert container -->
        <div id="intervenantAlertContainer" class="mb-3" style="display: none;"></div>

        <!-- Autres intervenants -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-users"></i> Intervenants liés</h6>
                        <button type="button" class="btn btn-primary btn-sm" id="addIntervenantBtn">
                            <i class="fas fa-plus"></i> Ajouter un intervenant
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Intervenants table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover w-100" id="intervenantsTable">
                                <thead>
                                    <tr>
                                        <th>Intervenant</th>
                                        <th>Rôle (Catégorie)</th>
                                        <th>Type</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Les données seront chargées par DataTable via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter un intervenant -->
<div class="modal fade" id="addIntervenantModal" tabindex="-1" role="dialog" aria-labelledby="addIntervenantModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addIntervenantModalLabel">
                    <i class="fas fa-user-plus"></i> Ajouter un intervenant au dossier
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Intervenants list -->
                <div class="form-group">
                    <label for="intervenantSelect">Sélectionnez un intervenant</label>
                    <select class="form-control" id="intervenantSelect">
                        <option value="">-- Sélectionnez un intervenant --</option>
                        @foreach($intervenants as $intervenant)
                            @php
                                $isAlreadyLinked = $dossier->intervenants->contains($intervenant->id);
                            @endphp
                            
                            @if(!$isAlreadyLinked)
                                <option value="{{ $intervenant->id }}" 
                                        data-name="{{ $intervenant->identite_fr }}"
                                        data-email="{{ $intervenant->mail1 ?? '' }}"
                                        data-phone="{{ $intervenant->portable1 ?? '' }}"
                                        data-category="{{ $intervenant->categorie ?? '' }}"
                                        data-type="{{ $intervenant->type ?? '' }}">
                                    {{ $intervenant->identite_fr }} 
                                    @if($intervenant->mail1)
                                        - {{ $intervenant->mail1 }}
                                    @endif
                                    @if($intervenant->categorie)
                                        ({{ $intervenant->categorie }})
                                    @endif
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- Info sur l'intervenant sélectionné -->
                <div id="intervenantInfo" class="card mt-3" style="display: none;">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informations</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nom :</strong> <span id="infoName"></span></p>
                                <p><strong>Email :</strong> <span id="infoEmail"></span></p>
                                <p><strong>Téléphone :</strong> <span id="infoPhone"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Catégorie :</strong> <span id="infoCategory"></span></p>
                                <p><strong>Type :</strong> <span id="infoType"></span></p>
                                <p><strong>Rôle attribué :</strong> <span id="infoRole" class="badge badge-info"></span></p>
                            </div>
                        </div>
                        <div class="alert alert-info mt-2">
                            <small>
                                <i class="fas fa-info-circle"></i> 
                                Le rôle sera automatiquement défini sur la catégorie de l'intervenant.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveIntervenantBtn">
                    <i class="fas fa-link"></i> Lier cet intervenant
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add this JavaScript code -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const clientPrincipalSelect = document.getElementById('client_id');
    const autresIntervenantsSelect = document.getElementById('autres_intervenants');
    
    // Function to hide selected client principal from autres intervenants
    function updateAutresIntervenants() {
        const selectedClientId = clientPrincipalSelect.value;
        
        // Reset all options to be visible
        for (let option of autresIntervenantsSelect.options) {
            option.classList.remove('hidden-item');
        }
        
        // Hide the selected client principal
        if (selectedClientId) {
            for (let option of autresIntervenantsSelect.options) {
                if (option.value === selectedClientId) {
                    option.classList.add('hidden-item');
                    
                    // Also deselect it if it was selected
                    option.selected = false;
                }
            }
        }
    }
    
    // Add event listener to client principal select
    if (clientPrincipalSelect) {
        clientPrincipalSelect.addEventListener('change', updateAutresIntervenants);
        
        // Initialize on page load
        updateAutresIntervenants();
    }
});
</script>

                                            <!-- Onglet Équipe -->
                                             @include('dossier_component::users')

                                            <!-- Onglet Facturation -->
                                            <div class="tab-pane fade d-none" id="facturation" role="tabpanel" aria-labelledby="facturation-tab">
                                                <div class="p-3">
                                                    <h5 class="text-primary mb-3"><i class="fas fa-money-bill-wave"></i> Informations de facturation</h5>
                                                 
                                                    
                                                    <!-- Mode de facturation -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mode_facturation">Mode de facturation</label>
                                                                <select class="form-control @error('mode_facturation') is-invalid @enderror" 
                                                                        id="mode_facturation" name="mode_facturation">
                                                                    <option value="">Sélectionnez un mode</option>
                                                                    <option value="honoraires" {{ old('mode_facturation', $dossier->mode_facturation) == 'honoraires' ? 'selected' : '' }}>Honoraires</option>
                                                                    <option value="forfait" {{ old('mode_facturation', $dossier->mode_facturation) == 'forfait' ? 'selected' : '' }}>Forfait</option>
                                                                    <option value="dossier" {{ old('mode_facturation', $dossier->mode_facturation) == 'dossier' ? 'selected' : '' }}>Au dossier</option>
                                                                    <option value="provision" {{ old('mode_facturation', $dossier->mode_facturation) == 'provision' ? 'selected' : '' }}>Provision</option>
                                                                </select>
                                                                @error('mode_facturation')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="budget_estime">Budget estimé (DH)</label>
                                                                <input type="number" class="form-control @error('budget_estime') is-invalid @enderror" 
                                                                       id="budget_estime" name="budget_estime" 
                                                                       value="{{ old('budget_estime', $dossier->budget_estime) }}" 
                                                                       placeholder="Montant estimé" step="0.01">
                                                                @error('budget_estime')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Informations complémentaires -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="notes_facturation">Notes de facturation</label>
                                                                <textarea class="form-control @error('notes_facturation') is-invalid @enderror" 
                                                                          id="notes_facturation" name="notes_facturation" 
                                                                          rows="3" placeholder="Informations complémentaires sur la facturation">{{ old('notes_facturation', $dossier->notes_facturation) }}</textarea>
                                                                @error('notes_facturation')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Onglet Notes -->
                                            @include('dossier_component::notes')
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="button" class="btn btn-default btn-previous" style="display: none;">
                                            <i class="fas fa-arrow-left"></i> Précédent
                                        </button>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-secondary btn-next">
                                            Suivant <i class="fas fa-arrow-right"></i>
                                        </button>
                                        <button type="submit" id="submitDossier" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Mettre à jour le dossier
                                        </button>
                                    </div>
                                </div>
                                <div class="text-center mt-2">
                                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left"></i> Retour à la liste
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>


<!-- Modal pour lier un intervenant -->
<div class="modal fade" id="linkIntervenantModal" tabindex="-1" role="dialog" aria-labelledby="linkIntervenantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="linkIntervenantModalLabel">
                    <i class="fas fa-users"></i> Sélectionner un intervenant à lier
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Filtre de recherche -->
                <div class="form-group d-none">
                    <label for="intervenantFilter">Filtrer les intervenants</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="intervenantFilter" 
                               placeholder="Tapez pour filtrer par nom, email ou catégorie...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="clearFilter">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <small class="form-text text-muted">
                        Tapez pour filtrer la liste des intervenants. {{ $intervenants->count() }} intervenant(s) disponible(s).
                    </small>
                </div>

                <!-- Liste des intervenants disponibles -->
                <div class="form-group w-100" style="display:grid;">
                    <label for="intervenantList">Choisir un intervenant</label>
                    <select class="form-control search_test2" id="intervenantList">
                        <option value="">-- Sélectionnez un intervenant --</option>
                        @foreach($intervenants as $intervenantItem)
                            @if($intervenantItem->id != $intervenant->id) {{-- Exclure l'intervenant actuel --}}
                            <option value="{{ $intervenantItem->id }}" 
                                    data-name="{{ $intervenantItem->identite_fr }}"
                                    data-email="{{ $intervenantItem->mail1 ?? 'N/A' }}"
                                    data-phone="{{ $intervenantItem->portable1 ?? 'N/A' }}"
                                    data-category="{{ $intervenantItem->categorie ?? 'N/A' }}"
                                    class="intervenant-option">
                                {{ $intervenantItem->identite_fr }} 
                                @if($intervenantItem->mail1)
                                    - {{ $intervenantItem->mail1 }}
                                @endif
                                @if($intervenantItem->categorie)
                                    ({{ $intervenantItem->categorie }})
                                @endif
                            </option>
                            @endif
                        @endforeach
                    </select>
                    <div id="noResults" class="alert alert-warning mt-2" style="display: none;">
                        <i class="fas fa-search"></i> Aucun intervenant ne correspond à votre recherche.
                    </div>
                </div>

                <!-- Aperçu de l'intervenant sélectionné -->
                <div id="intervenantPreview" class="card mt-3" style="display: none;">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-eye"></i> Aperçu de l'intervenant</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="30%"><strong>Nom :</strong></td>
                                        <td id="previewName"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email :</strong></td>
                                        <td id="previewEmail"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Téléphone :</strong></td>
                                        <td id="previewPhone"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Catégorie :</strong></td>
                                        <td id="previewCategory"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4 text-right">
                                <button type="button" class="btn btn-success" id="confirmLinkIntervenant">
                                    <i class="fas fa-link"></i> Lier cet intervenant
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message si aucun intervenant disponible -->
                @if($intervenants->count() <= 1)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Aucun autre intervenant disponible pour le moment.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal de confirmation de suppression de fichier -->
<div class="modal fade" id="deleteFileModal" tabindex="-1" role="dialog" aria-labelledby="deleteFileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteFileModalLabel">Confirmation de suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer ce fichier ? Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteFile">Supprimer</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Ajouter Domaine -->
<div class="modal fade" id="addDomaineModal" tabindex="-1" role="dialog" aria-labelledby="addDomaineModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDomaineModalLabel">Ajouter un nouveau domaine</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addDomaineForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nom_domaine">Nom du domaine</label>
                        <input type="text" class="form-control" id="nom_domaine" name="nom" required>
                        <div class="invalid-feedback" id="domaine-error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ajouter Sous-Domaine -->
<div class="modal fade" id="addSousDomaineModal" tabindex="-1" role="dialog" aria-labelledby="addSousDomaineModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSousDomaineModalLabel">Ajouter un nouveau sous-domaine</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addSousDomaineForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="domaine_modal_id">Domaine</label>
                        <select class="form-control" id="domaine_modal_id" name="domaine_id" required>
                            <option value="">Sélectionnez un domaine</option>
                            @foreach($domaines as $domaine)
                                <option value="{{ $domaine->id }}" {{ $dossier->domaine_id == $domaine->id ? 'selected' : '' }}>
                                    {{ $domaine->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nom_sous_domaine">Nom du sous-domaine</label>
                        <input type="text" class="form-control" id="nom_sous_domaine" name="nom" required>
                        <div class="invalid-feedback" id="sous-domaine-error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Champ caché pour les fichiers à supprimer -->
<input type="hidden" name="fichiers_supprimes" id="fichiers_supprimes" value="">
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.2/jquery.sumoselect.min.js"></script>

<!-- Inclure DataTables CSS et JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    // Initialiser Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // Gestion de la suppression des fichiers
    let fileToDelete = null;

    $('.delete-file-btn').on('click', function() {
        fileToDelete = $(this).data('file-id');
        $('#deleteFileModal').modal('show');
    });

    $('#confirmDeleteFile').on('click', function() {
        if (fileToDelete) {
            // Ajouter l'ID du fichier à supprimer dans le champ caché
            const currentFiles = $('#fichiers_supprimes').val();
            const filesArray = currentFiles ? currentFiles.split(',') : [];
            filesArray.push(fileToDelete);
            $('#fichiers_supprimes').val(filesArray.join(','));

            // Masquer la ligne du fichier
            $(`.delete-file-btn[data-file-id="${fileToDelete}"]`).closest('tr').fadeOut();

            $('#deleteFileModal').modal('hide');
            fileToDelete = null;
        }
    });

    // Gestion des sous-domaines en fonction du domaine sélectionné
    $('#domaine_id').on('change', function() {
        const domaineId = $(this).val();
        const sousDomaineSelect = $('#sous_domaine_id');
        
        if (domaineId) {
            $.ajax({
                url: '{{ route("get.sous-domaines") }}',
                type: 'GET',
                data: {
                    domaine_id: domaineId
                },
                success: function(data) {
                    sousDomaineSelect.empty();
                    sousDomaineSelect.append('<option value="">Sélectionnez un sous-domaine</option>');
                    
                    $.each(data, function(key, value) {
                        sousDomaineSelect.append('<option value="' + key + '">' + value + '</option>');
                    });
                },
                error: function() {
                    console.log('Erreur lors du chargement des sous-domaines');
                }
            });
        } else {
            sousDomaineSelect.empty();
            sousDomaineSelect.append('<option value="">Sélectionnez d\'abord un domaine</option>');
        }
    });

    // Navigation par onglets
    $('.btn-next').on('click', function() {
        const currentTab = $('.nav-tabs .active');
        const nextTab = currentTab.parent().next().find('a');
        
        if (nextTab.length) {
            currentTab.removeClass('active');
            nextTab.addClass('active');
            
            $('.tab-pane.active').removeClass('active show');
            $(nextTab.attr('href')).addClass('active show');
            
            $('.btn-previous').show();
            
            // Cacher le bouton suivant si on est sur le dernier onglet
            if (!nextTab.parent().next().length) {
                $('.btn-next').hide();
            }
        }
    });

    $('.btn-previous').on('click', function() {
        const currentTab = $('.nav-tabs .active');
        const prevTab = currentTab.parent().prev().find('a');
        
        if (prevTab.length) {
            currentTab.removeClass('active');
            prevTab.addClass('active');
            
            $('.tab-pane.active').removeClass('active show');
            $(prevTab.attr('href')).addClass('active show');
            
            $('.btn-next').show();
            
            // Cacher le bouton précédent si on est sur le premier onglet
            if (!prevTab.parent().prev().length) {
                $('.btn-previous').hide();
            }
        }
    });

    // Gestion de l'affichage du nom du fichier
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // ==================== GESTION DES INTERVENANTS ====================
    
    // Variables pour les intervenants
    const dossierId = {{ $dossier->id }};
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    // Initialiser DataTable pour les intervenants
    let intervenantsTable = $('#intervenantsTable').DataTable({
        processing: true,
        serverSide: false,
        searching: true,
        ordering: true,
        paging: true,
        pageLength: 10,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/French.json"
        },
        ajax: {
            url: `/dossiers/${dossierId}/intervenants-data`,
            type: 'GET',
            dataSrc: 'data'
        },
        columns: [
            { 
                data: null,
                render: function(data, type, row) {
                    return `
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <i class="fas fa-user-circle fa-2x text-primary"></i>
                            </div>
                            <div>
                                <strong>${row.identite_fr}</strong>
                                ${row.identite_ar ? '<br><small class="text-muted">' + row.identite_ar + '</small>' : ''}
                            </div>
                        </div>
                    `;
                }
            },
            { 
                data: null,
                render: function(data, type, row) {
                    const role = row.pivot_role || row.categorie || 'Non défini';
                    return `<span class="badge badge-info">${role}</span>`;
                }
            },
            { 
                data: 'type',
                render: function(data, type, row) {
                    return data ? `<span class="badge badge-secondary">${data}</span>` : 'Non défini';
                }
            },
            { 
                data: 'mail1',
                render: function(data) {
                    return data || '-';
                }
            },
            { 
                data: 'portable1',
                render: function(data) {
                    return data || '-';
                }
            },
            { 
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <div class="btn-group btn-group-sm">
                            <a href="/intervenants/${row.id}" 
                               class="btn btn-info" title="Voir" target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-danger detach-intervenant-btn" 
                                    data-intervenant-id="${row.id}"
                                    data-intervenant-name="${row.identite_fr}">
                                <i class="fas fa-unlink"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        drawCallback: function(settings) {
            // Réattacher les événements après chaque redessin de la table
            attachDetachEvents();
        },
        initComplete: function() {
            // Attacher les événements initialement
            attachDetachEvents();
        }
    });

    // Ouvrir le modal d'ajout d'intervenant
    $('#addIntervenantBtn').click(function() {
        $('#intervenantSelect').val('');
        $('#intervenantInfo').hide();
        $('#addIntervenantModal').modal('show');
    });

    // Afficher les informations quand un intervenant est sélectionné
    $('#intervenantSelect').change(function() {
        const selectedOption = $(this).find('option:selected');
        
        if ($(this).val()) {
            $('#infoName').text(selectedOption.data('name'));
            $('#infoEmail').text(selectedOption.data('email') || '-');
            $('#infoPhone').text(selectedOption.data('phone') || '-');
            $('#infoCategory').text(selectedOption.data('category') || '-');
            $('#infoType').text(selectedOption.data('type') || '-');
            $('#infoRole').text(selectedOption.data('category') || '-');
            $('#intervenantInfo').show();
        } else {
            $('#intervenantInfo').hide();
        }
    });

    // Lier un intervenant
    $('#saveIntervenantBtn').click(function() {
        const intervenantId = $('#intervenantSelect').val();
        const intervenantName = $('#intervenantSelect option:selected').data('name');
        const intervenantCategory = $('#intervenantSelect option:selected').data('category') || 'Non défini';

        if (!intervenantId) {
            showAlert('danger', 'Veuillez sélectionner un intervenant', '#intervenantAlertContainer');
            return;
        }

        $.ajax({
            url: `/dossiers/${dossierId}/attach-intervenant`,
            type: 'POST',
            data: {
                _token: csrfToken,
                intervenant_id: intervenantId,
                role: intervenantCategory
            },
            beforeSend: function() {
                $('#saveIntervenantBtn').prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i> En cours...');
            },
            success: function(response) {
                if (response.success) {
                    // Fermer le modal
                    $('#addIntervenantModal').modal('hide');
                    
                    // Réinitialiser le modal
                    $('#intervenantSelect').val('');
                    $('#intervenantInfo').hide();
                    
                    // Rafraîchir la DataTable
                    intervenantsTable.ajax.reload(null, false);
                    
                    // Mettre à jour la liste des intervenants disponibles dans le select
                    updateAvailableIntervenants(intervenantId);
                    
                    showAlert('success', response.message || `Intervenant "${intervenantName}" lié avec succès`, '#intervenantAlertContainer');
                } else {
                    showAlert('danger', response.message || 'Une erreur est survenue', '#intervenantAlertContainer');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Une erreur est survenue';
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    errorMsg = Object.values(errors).flat().join(', ');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                
                showAlert('danger', errorMsg, '#intervenantAlertContainer');
            },
            complete: function() {
                $('#saveIntervenantBtn').prop('disabled', false)
                    .html('<i class="fas fa-link"></i> Lier cet intervenant');
            }
        });
    });

    // ==================== FONCTIONS UTILITAIRES INTERVENANTS ====================

    // Fonction pour attacher les événements de détachement
    function attachDetachEvents() {
        $('.detach-intervenant-btn').off('click').on('click', function() {
            const intervenantId = $(this).data('intervenant-id');
            const intervenantName = $(this).data('intervenant-name');

            if (confirm(`Êtes-vous sûr de vouloir détacher "${intervenantName}" de ce dossier ?`)) {
                detachIntervenant(intervenantId, intervenantName);
            }
        });
    }

    // Fonction pour détacher un intervenant
    function detachIntervenant(intervenantId, intervenantName) {
        $.ajax({
            url: `/dossiers/${dossierId}/detach-intervenant`,
            type: 'POST',
            data: {
                _token: csrfToken,
                intervenant_id: intervenantId
            },
            beforeSend: function() {
                $(`.detach-intervenant-btn[data-intervenant-id="${intervenantId}"]`)
                    .prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i>');
            },
            success: function(response) {
                if (response.success) {
                    // Rafraîchir la DataTable
                    intervenantsTable.ajax.reload(null, false);
                    
                    // Ajouter l'intervenant à la liste des disponibles
                    if (response.data && response.data.intervenant) {
                        addIntervenantToSelect(response.data.intervenant);
                    }
                    
                    showAlert('success', response.message || `Intervenant "${intervenantName}" détaché avec succès`, '#intervenantAlertContainer');
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Une erreur est survenue';
                showAlert('danger', errorMsg, '#intervenantAlertContainer');
                $(`.detach-intervenant-btn[data-intervenant-id="${intervenantId}"]`)
                    .prop('disabled', false)
                    .html('<i class="fas fa-unlink"></i>');
            }
        });
    }

    // Mettre à jour la liste des intervenants disponibles
    function updateAvailableIntervenants(removedIntervenantId) {
        $(`#intervenantSelect option[value="${removedIntervenantId}"]`).remove();
        
        if ($('#intervenantSelect option').length === 1) {
            $('#intervenantSelect').append(
                '<option value="" disabled>Aucun intervenant disponible</option>'
            );
        }
    }

    // Ajouter un intervenant au select quand il est détaché
    function addIntervenantToSelect(intervenantData) {
        if ($(`#intervenantSelect option[value="${intervenantData.id}"]`).length === 0) {
            const newOption = `
                <option value="${intervenantData.id}" 
                        data-name="${intervenantData.identite_fr}"
                        data-email="${intervenantData.mail1 || ''}"
                        data-phone="${intervenantData.portable1 || ''}"
                        data-category="${intervenantData.categorie || ''}"
                        data-type="${intervenantData.type || ''}">
                    ${intervenantData.identite_fr} 
                    ${intervenantData.mail1 ? ' - ' + intervenantData.mail1 : ''}
                    ${intervenantData.categorie ? ' (' + intervenantData.categorie + ')' : ''}
                </option>
            `;
            
            $('#intervenantSelect option[value=""][disabled]').remove();
            $('#intervenantSelect').append(newOption);
        }
    }

    // Fonction pour afficher une alerte
    function showAlert(type, message, container) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;
        
        $(container).html(alertHtml).show();
        
        setTimeout(() => {
            $(container + ' .alert').alert('close');
        }, 5000);
    }

    // Réinitialiser le modal quand il se ferme
    $('#addIntervenantModal').on('hidden.bs.modal', function() {
        $('#intervenantSelect').val('');
        $('#intervenantInfo').hide();
    });

    // ==================== GESTION DES DOMAINES/SOUS-DOMAINES ====================

    // Fonction pour charger les sous-domaines
    function loadSousDomaines(domaineId, selectedId = null) {
        if (domaineId) {
            $.ajax({
                url: "{{ route('sous-domaines.by-domaine') }}",
                type: "GET",
                data: {domaine_id: domaineId},
                success: function(data) {
                    $('#sous_domaine_id').empty();
                    $('#sous_domaine_id').append('<option value="">Sélectionnez un sous-domaine</option>');
                    $.each(data, function(key, value) {
                        var selected = (selectedId && key == selectedId) ? 'selected' : '';
                        $('#sous_domaine_id').append('<option value="'+ key +'" '+ selected +'>'+ value +'</option>');
                    });
                }
            });
        } else {
            $('#sous_domaine_id').empty();
            $('#sous_domaine_id').append('<option value="">Sélectionnez d\'abord un domaine</option>');
        }
    }

    // Charger les sous-domaines au chargement si un domaine est déjà sélectionné
    @if($dossier->domaine_id)
        loadSousDomaines({{ $dossier->domaine_id }}, {{ $dossier->sous_domaine_id ?? 'null' }});
    @endif

    // Charger les sous-domaines quand un domaine est sélectionné
    $('#domaine_id').change(function() {
        var domaineId = $(this).val();
        loadSousDomaines(domaineId);
    });

    // Mettre à jour le domaine dans le modal de sous-domaine
    $('#domaine_id').change(function() {
        $('#domaine_modal_id').val($(this).val());
    });

    // Ajouter un domaine via Ajax
    $('#addDomaineForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{ route('domaines.store') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                var newOption = new Option(response.nom, response.id, false, true);
                $('#domaine_id').append(newOption).trigger('change');
                $('#domaine_modal_id').append(new Option(response.nom, response.id));
                
                $('#addDomaineModal').modal('hide');
                $('#addDomaineForm')[0].reset();
                $('#domaine-error').text('');
                
                loadSousDomaines(response.id);
                
                toastr.success('Domaine ajouté avec succès!');
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    if (errors.nom) {
                        $('#nom_domaine').addClass('is-invalid');
                        $('#domaine-error').text(errors.nom[0]);
                    }
                } else {
                    toastr.error('Une erreur est survenue!');
                }
            }
        });
    });

    // Ajouter un sous-domaine via Ajax
    $('#addSousDomaineForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{ route('sous-domaines.store') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                if ($('#domaine_id').val() == response.domaine_id) {
                    var newOption = new Option(response.nom, response.id, false, true);
                    $('#sous_domaine_id').append(newOption).trigger('change');
                }
                
                $('#addSousDomaineModal').modal('hide');
                $('#addSousDomaineForm')[0].reset();
                $('#sous-domaine-error').text('');
                
                toastr.success('Sous-domaine ajouté avec succès!');
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    if (errors.nom) {
                        $('#nom_sous_domaine').addClass('is-invalid');
                        $('#sous-domaine-error').text(errors.nom[0]);
                    }
                } else {
                    toastr.error('Une erreur est survenue!');
                }
            }
        });
    });

    // Réinitialiser les erreurs quand le modal s'ouvre
    $('#addDomaineModal').on('show.bs.modal', function() {
        $('#nom_domaine').removeClass('is-invalid');
        $('#domaine-error').text('');
    });

    $('#addSousDomaineModal').on('show.bs.modal', function() {
        $('#nom_sous_domaine').removeClass('is-invalid');
        $('#sous-domaine-error').text('');
        
        var currentDomaineId = $('#domaine_id').val();
        if (currentDomaineId) {
            $('#domaine_modal_id').val(currentDomaineId);
        }
    });
});
</script>

<style>
/* Styles supplémentaires */
.intervenant-role {
    transition: all 0.3s ease;
}

#intervenantsTable th {
    background-color: #f8f9fa;
    font-weight: 600;
}

#intervenantsTable tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.85em;
    padding: 0.35em 0.65em;
}

#intervenantInfo .card-body p {
    margin-bottom: 0.5rem;
}

#intervenantInfo .alert {
    margin-bottom: 0;
}

/* Style pour DataTables */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    margin-bottom: 1rem;
}

.dataTables_wrapper .dataTables_filter input {
    margin-left: 0.5em;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 0.375rem 0.75rem;
}
</style>
@endsection