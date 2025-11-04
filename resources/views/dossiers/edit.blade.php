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
                                                                <label for="domaine_id">Domaine</label>
                                                                <select class="form-control @error('domaine_id') is-invalid @enderror" 
                                                                        id="domaine_id" name="domaine_id">
                                                                    <option value="">Sélectionnez un domaine</option>
                                                                    @foreach($domaines as $domaine)
                                                                        <option value="{{ $domaine->id }}" {{  $dossier->domaine_id == $domaine->id ? 'selected' : '' }}>
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
                                                                <label for="sous_domaine_id">Sous-domaine</label>
                                                                <select class="form-control @error('sous_domaine_id') is-invalid @enderror" 
                                                                        id="sous_domaine_id" name="sous_domaine_id">
                                                                    <option value="">Sélectionnez d'abord un domaine</option>
                                                                    @if($dossier->sous_domaine_id)
                                                                        @foreach($sousDomaines as $sousDomaine)
                                                                            <option value="{{ $sousDomaine->id }}" {{ old('sous_domaine_id', $dossier->sous_domaine_id) == $sousDomaine->id ? 'selected' : '' }}>
                                                                                {{ $sousDomaine->nom }}
                                                                            </option>
                                                                        @endforeach
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
                                                                    <option value="civil" {{ old('chambre', $dossier->chambre) == 'civil' ? 'selected' : '' }}>Civil</option>
                                                                    <option value="commercial" {{ old('chambre', $dossier->chambre) == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                                                    <option value="social" {{ old('chambre', $dossier->chambre) == 'social' ? 'selected' : '' }}>Social</option>
                                                                    <option value="pénal" {{ old('chambre', $dossier->chambre) == 'pénal' ? 'selected' : '' }}>Pénal</option>
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

                                         <x-dossier.tab-edit :dossier="$dossier" :dossiers="$dossiers"/>

<!-- Onglet Intervenants -->
<div class="tab-pane fade" id="intervenants" role="tabpanel" aria-labelledby="intervenants-tab">
    <div class="p-3">
        <h5 class="text-primary mb-3"><i class="fas fa-handshake"></i> Gestion des intervenants</h5>
        
        <!-- Client principal -->
        <div class="row d-none">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="client_id">Client principal</label>
                    <select class="form-control @error('client_id') is-invalid @enderror" 
                            id="client_id" name="client_id">
                        <option value="">Sélectionnez le client</option>
                        @foreach($intervenants as $intervenant)
                            @if($intervenant->categorie == 'client')
                                <option value="{{ $intervenant->id }}" {{ old('client_id', $dossier->intervenants()->wherePivot('role', 'client')->first()?->id) == $intervenant->id ? 'selected' : '' }}>
                                    {{ $intervenant->identite_fr }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('client_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Autres intervenants -->
        <div class="row">
            <div class="col-md-12">
                
                                                    <div class="d-flex justify-content-end align-items-center mb-3">
                                                        <h5 class="text-primary mb-0 d-none"><i class="fas fa-users"></i> Intervenants Liés</h5>
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#linkIntervenantModal" style="float:right;">
                                                            <i class="fas fa-link"></i> Lier un intervenant
                                                        </button>
                                                    </div>
                                                     <!-- Tableau des intervenants liés -->
                                                    <div class="card">
                                                        <div class="card-header bg-light">
                                                            <h6 class="mb-0"><i class="fas fa-table"></i> Liste des intervenants liés</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped" id="linkedIntervenantsTable">
                                                                    <thead class="thead-dark">
                                                                        <tr>
                                                                            <th width="30%">Intervenant Lié</th>
                                                                            <th width="30%">Role (de cet intervenant)</th>
                                                                            <th width="10%">Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="linked-intervenants-container">
                                                                        @php
    $existingLinkedIntervenants = [];
    if ($dossier->intervenants->count() > 0) {
        foreach ($dossier->intervenants as $index => $linkedIntervenant) {
            $pivot = $linkedIntervenant->pivot;
            $existingLinkedIntervenants[] = [
                'intervenant_id' => $linkedIntervenant->id,
                'intervenant_name' => $linkedIntervenant->identite_fr,
                'role' => $pivot->role,
            ];
        }
    }
@endphp

                                                                        @if(old('linked_intervenants'))
                                                                            <!-- Afficher les intervenants depuis la validation -->
                                                                            @foreach(old('linked_intervenants') as $index => $linkedIntervenant)
                                                                            <tr class="linked-intervenant-item">
                                                                                <td>
                                                                                    <strong>{{ $linkedIntervenant['intervenant_name'] ?? 'Intervenant' }}</strong>
                                                                                    <input type="hidden" name="linked_intervenants[{{ $index }}][intervenant_id]" 
                                                                                           value="{{ $linkedIntervenant['intervenant_id'] }}">
                                                                                    <input type="hidden" name="linked_intervenants[{{ $index }}][intervenant_name]" 
                                                                                           value="{{ $linkedIntervenant['intervenant_name'] }}">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" class="form-control" 
                                                                                           name="linked_intervenants[{{ $index }}][role]" 
                                                                                           value="{{ $linkedIntervenant['intervenant_id'] ?? '' }}"
                                                                                           placeholder="Ex: Client, Partenaire, Associé..."
                                                                                           required>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <button type="button" class="btn btn-danger btn-sm remove-linked-intervenant">
                                                                                        <i class="fas fa-trash"></i>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach
                                                                        @elseif(count($existingLinkedIntervenants) > 0)
                                                                            <!-- Afficher les intervenants existants depuis la base de données -->
                                                                            @foreach($existingLinkedIntervenants as $index => $linkedIntervenant)
                                                                            <tr class="linked-intervenant-item">
                                                                                <td>
                                                                                    <strong>{{ $linkedIntervenant['intervenant_name'] }}</strong>
                                                                                    <input type="hidden" name="linked_intervenants[{{ $index }}][intervenant_id]" 
                                                                                           value="{{ $linkedIntervenant['intervenant_id'] }}">
                                                                                    <input type="hidden" name="linked_intervenants[{{ $index }}][intervenant_name]" 
                                                                                           value="{{ $linkedIntervenant['intervenant_name'] }}">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" class="form-control" 
                                                                                           name="linked_intervenants[{{ $index }}][role]" 
                                                                                           value="{{ $linkedIntervenant['role'] }}"
                                                                                           placeholder="Ex: Client, Partenaire, Associé..."
                                                                                           required>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <button type="button" class="btn btn-danger btn-sm remove-linked-intervenant" data-dossier-id="{{ $dossier->id }}">
                                                                                        <i class="fas fa-trash"></i>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <!-- Message quand aucun intervenant n'est lié -->
                                                            <div id="no-linked-intervenants" class="text-center py-4" 
                                                                 style="{{ (old('linked_intervenants') || count($existingLinkedIntervenants) > 0) ? 'display: none;' : '' }}">
                                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                                <p class="text-muted">Aucun intervenant lié pour le moment</p>
                                                            </div>
                                                        </div>
                                                    </div>

                <div class="form-group d-none">
                    <label>Autres intervenants</label>
                    <select class="form-control" id="autres_intervenants" name="autres_intervenants[]" multiple>
                        @foreach($intervenants as $intervenant)
                          
                                @php
                                    $isSelected = $dossier->intervenants()
                                        ->where('intervenants.id', $intervenant->id)
                                        ->exists();
                                @endphp
                                <option value="{{ $intervenant->id }}" {{ $isSelected ? 'selected' : '' }}>
                                    {{ $intervenant->identite_fr }} ({{ $intervenant->categorie }})
                                </option>
                         
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Rôles des intervenants -->
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <h6><i class="icon fas fa-info"></i> Information</h6>
                    <p class="mb-0">
                        Sélectionnez le client principal et éventuellement d'autres intervenants (avocats adverses, experts, etc.).
                    </p>
                </div>
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
                                            <x-dossier.equipe.tab-edit :users="$users" :dossier="$dossier"/>

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
                                            <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                                                <div class="p-3">
                                                    <!-- Notes générales -->
                                                    <div class="form-group">
                                                        <label for="notes">Notes et observations</label>
                                                        <textarea class="form-control @error('note') is-invalid @enderror" 
                                                                  id="notes" name="note" 
                                                                  rows="12" placeholder="Notes supplémentaires, observations, informations importantes...">{{ old('note', $dossier->note) }}</textarea>{{ old('note', $dossier->note) }}
                                                        @error('note')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
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

<!-- Champ caché pour les fichiers à supprimer -->
<input type="hidden" name="fichiers_supprimes" id="fichiers_supprimes" value="">

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.2/jquery.sumoselect.min.js"></script>

<script>
        $('.search_test2').SumoSelect({search: true, searchText: 'Sélectionner un intervenant...'});

$(document).ready(function() {
    // Initialisation de
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
});


// Gestion des intervenants liés
let linkedIntervenantsCount = {{ max(
    old('linked_intervenants') ? count(old('linked_intervenants')) : 0,
    count($existingLinkedIntervenants)
) }};

// Filtrage des intervenants
$('#intervenantFilter').on('input', function() {
    const filterText = $(this).val().toLowerCase();
    const options = $('.intervenant-option');
    let visibleCount = 0;
    
    options.each(function() {
        const optionText = $(this).text().toLowerCase();
        if (optionText.includes(filterText)) {
            $(this).show();
            visibleCount++;
        } else {
            $(this).hide();
        }
    });
    
    // Afficher/masquer le message "aucun résultat"
    if (visibleCount === 0 && filterText !== '') {
        $('#noResults').show();
    } else {
        $('#noResults').hide();
    }
    
    // Réinitialiser la sélection si l'option sélectionnée est masquée
    const selectedOption = $('#intervenantList option:selected');
    if (selectedOption.length > 0 && selectedOption.is(':hidden')) {
        $('#intervenantList').val('');
        $('#intervenantPreview').hide();
    }
});

// Effacer le filtre
$('#clearFilter').click(function() {
    $('#intervenantFilter').val('');
    $('.intervenant-option').show();
    $('#noResults').hide();
});

// Sélection d'un intervenant dans la liste
$('#intervenantList').change(function() {
    const selectedOption = $(this).find('option:selected');
    const intervenantId = selectedOption.val();
    
    if (!intervenantId) {
        $('#intervenantPreview').hide();
        return;
    }

    // Afficher l'aperçu
    $('#previewName').text(selectedOption.data('name'));
    $('#previewEmail').text(selectedOption.data('email'));
    $('#previewPhone').text(selectedOption.data('phone'));
    $('#previewCategory').text(selectedOption.data('category'));
    
    $('#intervenantPreview').show();
});

// Confirmation du lien
$('#confirmLinkIntervenant').click(function() {
    const selectedOption = $('#intervenantList option:selected');
    const intervenantId = selectedOption.val();
    const intervenantName = selectedOption.data('name');
    const intervenatRole = selectedOption.data('category');

    if (!intervenantId) {
        alert('Veuillez sélectionner un intervenant.');
        return;
    }

    // Vérifier si l'intervenant n'est pas déjà lié
    const existingLink = $(`input[value="${intervenantId}"]`).closest('.linked-intervenant-item');
    if (existingLink.length > 0) {
        alert('Cet intervenant est déjà lié.');
        return;
    }

    addLinkedIntervenant(intervenantId, intervenantName, intervenatRole);
    
    // Reset la modal
    $('#intervenantList').val('');
    $('#intervenantFilter').val('');
    $('.intervenant-option').show();
    $('#noResults').hide();
    $('#intervenantPreview').hide();
    $('#linkIntervenantModal').modal('hide');
});

function addLinkedIntervenant(intervenantId, intervenantName, intervenatRole) {
    const newIndex = linkedIntervenantsCount++;
    
    const linkedItem = `
        <tr class="linked-intervenant-item">
            <td>
                <strong>${intervenantName}</strong>
                <input type="hidden" name="linked_intervenants[${newIndex}][intervenant_id]" value="${intervenantId}">
                <input type="hidden" name="linked_intervenants[${newIndex}][intervenant_name]" value="${intervenantName}">
            </td>
            <td>
                <input type="text" class="form-control" 
                       name="linked_intervenants[${newIndex}][role]" 
                       value="${intervenatRole}"
                       required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-linked-intervenant">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;

    $('#linked-intervenants-container').append(linkedItem);
    $('#no-linked-intervenants').hide();

    // Ajouter l'événement de suppression
    $('.remove-linked-intervenant').off('click').on('click', function() {
        $(this).closest('.linked-intervenant-item').remove();
        linkedIntervenantsCount--;
        
        // Réindexer les éléments restants
        reindexLinkedIntervenants();
        
        // Afficher le message si plus d'intervenants liés
        if ($('#linked-intervenants-container').children().length === 0) {
            $('#no-linked-intervenants').show();
        }
    });
}

function reindexLinkedIntervenants() {
    $('#linked-intervenants-container .linked-intervenant-item').each(function(index) {
        $(this).find('input').each(function() {
            const name = $(this).attr('name').replace(/\[\d+\]/, `[${index}]`);
            $(this).attr('name', name);
        });
    });
}

</script>
<script>
// Remove linked intervenant
$(document).on('click', '.remove-linked-intervenant', function() {
    const row = $(this).closest('.linked-intervenant-item');
    const intervenantId = row.find('input[name*="[intervenant_id]"]').val();
    const intervenantName = row.find('input[name*="[intervenant_name]"]').val();
    const dossierId = '{{ $dossier->id }}'; // Make sure $dossier is available in your view
    
    // Show confirmation dialog
    if (confirm(`Êtes-vous sûr de vouloir détacher "${intervenantName}" ?`)) {
        // Get CSRF token
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        // Send AJAX request to detach intervenant
        $.ajax({
            url: `/dossiers/${dossierId}/detach-intervenant`,
            type: 'POST',
            data: {
                _token: csrfToken,
                intervenant_id: intervenantId
            },
            beforeSend: function() {
                // Show loading indicator
                row.css('opacity', '0.5');
                $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
            },
            success: function(response) {
                if (response.success) {
                    // Remove row from table
                    row.fadeOut(300, function() {
                        $(this).remove();
                        updateIntervenantCounter();
                        showSuccessMessage(response.message);
                        
                        // Check if no intervenants left
                        if ($('.linked-intervenant-item').length === 0) {
                            $('#linked-intervenants-container').html(
                                '<tr><td colspan="3" class="text-center text-muted">Aucun intervenant lié</td></tr>'
                            );
                        }
                    });
                } else {
                    showErrorMessage(response.message);
                    row.css('opacity', '1');
                    $(this).prop('disabled', false).html('<i class="fas fa-trash"></i>');
                }
            },
            error: function(xhr) {
                console.error(xhr);
                let errorMessage = 'Une erreur est survenue lors du détachement.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showErrorMessage(errorMessage);
                row.css('opacity', '1');
                $(this).prop('disabled', false).html('<i class="fas fa-trash"></i>');
            }
        });
    }
});

// Update counter after removal
function updateIntervenantCounter() {
    const count = $('.linked-intervenant-item').length;
    $('#intervenant-count').text(count);
}

// Show success message
function showSuccessMessage(message) {
    // Using Toastr
    if (typeof toastr !== 'undefined') {
        toastr.success(message);
    } else {
        // Fallback to Bootstrap alert
        $('#alert-container').html(`
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
    }
}

function showErrorMessage(message) {
    if (typeof toastr !== 'undefined') {
        toastr.error(message);
    } else {
        $('#alert-container').html(`
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
    }
}
</script>
@endsection