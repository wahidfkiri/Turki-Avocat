@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Nouveau Dossier</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dossiers.index') }}">Dossiers</a></li>
                        <li class="breadcrumb-item active">Nouveau Dossier</li>
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
                            <h3 class="card-title">Création d'un nouveau dossier</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('dossiers.store') }}" method="POST" id="dossierForm" enctype="multipart/form-data">
                            @csrf
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
                                                                       value="{{ old('numero_dossier') }}" 
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
                                                                       value="{{ old('date_entree', date('Y-m-d')) }}" required>
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
                                                                       value="{{ old('nom_dossier') }}" 
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
                                                                       id="objet" name="objet" 
                                                                       placeholder="Description détaillée de l'objet du dossier" value="{{ old('objet') }}">
                                                                @error('objet')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Domaines -->
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
                    <option value="{{ $domaine->id }}" {{ old('domaine_id') == $domaine->id ? 'selected' : '' }}>
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
                <option value="">Sélectionnez d'abord un domaine</option>
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
                                                                           {{ old('conseil') ? 'checked' : '' }}>
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
                                                                           {{ old('contentieux') ? 'checked' : '' }}>
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
                                                                           {{ old('archive') ? 'checked' : '' }}>
                                                                    <label for="archive" class="custom-control-label">
                                                                        Marquer comme archivé
                                                                    </label>
                                                                </div>
                                                                <small class="form-text text-muted">
                                                                    Si coché, ce dossier sera marqué comme archivé dès sa création.
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
                                                                       value="{{ old('numero_role') }}" 
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
                                                                        <option value="{{ $chambre->name }}" {{ old('chambre') == $chambre->name ? 'selected' : '' }}>
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
                                                                       value="{{ old('numero_chambre') }}" 
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
                                                                       value="{{ old('numero_parquet') }}" 
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
                                                                       value="{{ old('numero_instruction') }}" 
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
                                                                       value="{{ old('numero_plainte') }}" 
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

                                                 <!-- Onglet Intervenants Liés -->
                                            <div class="tab-pane fade" id="intervenants" role="tabpanel" aria-labelledby="intervenants-tab">
                                                <div class="p-3">
                                                    
                                                    <!-- Client principal -->
                                                    <div class="row d-none">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="client_id">Client principal</label>
                                                                <select class="form-control @error('client_id') is-invalid @enderror" 
                                                                        id="client_id" name="client_id">
                                                                    <option value="">Sélectionnez le client</option>
                                                                    @foreach($intervenants as $intervenant)
                                                                       
                                                                            <option value="{{ $intervenant->id }}" {{ old('client_id') == $intervenant->id ? 'selected' : '' }}>
                                                                                {{ $intervenant->identite_fr }}
                                                                            </option>
                                                                    
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
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <h5 class="text-primary mb-0"><i class="fas fa-users"></i> Intervenants Liés</h5>
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#linkIntervenantModal">
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
                                                                            <th width="30%">Role</th>
                                                                            <th width="10%">Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="linked-intervenants-container">
                                                                        @if(old('linked_intervenants'))
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
                                                                                           value="{{ $linkedIntervenant['role'] ?? '' }}"
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
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <!-- Message quand aucun intervenant n'est lié -->
                                                            <div id="no-linked-intervenants" class="text-center py-4" 
                                                                 style="{{ old('linked_intervenants') ? 'display: none;' : '' }}">
                                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                                <p class="text-muted">Aucun intervenant lié pour le moment</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            
                                            <x-dossier.tab-create :dossiers="$dossiers"/>

                                           <x-dossier.equipe.tab-create :users="$users" />

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
                                                                    <option value="honoraires" {{ old('mode_facturation') == 'honoraires' ? 'selected' : '' }}>Honoraires</option>
                                                                    <option value="forfait" {{ old('mode_facturation') == 'forfait' ? 'selected' : '' }}>Forfait</option>
                                                                    <option value="dossier" {{ old('mode_facturation') == 'dossier' ? 'selected' : '' }}>Au dossier</option>
                                                                    <option value="provision" {{ old('mode_facturation') == 'provision' ? 'selected' : '' }}>Provision</option>
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
                                                                       value="{{ old('budget_estime') }}" 
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
                                                                          rows="3" placeholder="Informations complémentaires sur la facturation">{{ old('notes_facturation') }}</textarea>
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
                                                                  rows="12" placeholder="Notes supplémentaires, observations, informations importantes...">{{ old('note') }}</textarea>
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
                                            <i class="fas fa-save"></i> Créer le dossier
                                        </button>
                                    </div>
                                </div>
                                <div class="text-center mt-2">
                                    <a href="{{ route('dossiers.index') }}" class="btn btn-outline-secondary">
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
                    <select class="form-control search_test1" id="intervenantList" >
                        <option value="">-- Sélectionnez un intervenant --</option>
                        @foreach($intervenants as $intervenant)
                            <option value="{{ $intervenant->id }}" 
                                    data-name="{{ $intervenant->identite_fr }}"
                                    data-email="{{ $intervenant->mail1 ?? 'N/A' }}"
                                    data-phone="{{ $intervenant->portable1 ?? 'N/A' }}"
                                    data-category="{{ $intervenant->categorie ?? 'N/A' }}"
                                    class="intervenant-option">
                                {{ $intervenant->identite_fr }} 
                                @if($intervenant->mail1)
                                    - {{ $intervenant->mail1 }}
                                @endif
                                @if($intervenant->categorie)
                                    ({{ $intervenant->categorie }})
                                @endif
                            </option>
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
                @if($intervenants->isEmpty())
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Aucun intervenant disponible pour le moment.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
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
                                <option value="{{ $domaine->id }}">{{ $domaine->nom }}</option>
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
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{asset('assets/custom/dossier-form.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.2/jquery.sumoselect.min.js"></script>
<script>
    $('.search_test1').SumoSelect({search: true, searchText: 'Sélectionner un intervenant...'});
       
// Gestion des intervenants liés
let linkedIntervenantsCount = {{ old('linked_intervenants') ? count(old('linked_intervenants')) : 0 }};

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
    const intervenantCategory = selectedOption.data('category');

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

    addLinkedIntervenant(intervenantId, intervenantName, intervenantCategory);
    
    // Reset la modal
    $('#intervenantList').val('');
    $('#intervenantFilter').val('');
    $('.intervenant-option').show();
    $('#noResults').hide();
    $('#intervenantPreview').hide();
    $('#linkIntervenantModal').modal('hide');
});

function addLinkedIntervenant(intervenantId, intervenantName, intervenantCategory) {
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
                       value="${intervenantCategory}"
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

// Initialiser les boutons de suppression pour les intervenants existants
$(document).ready(function() {
    $('.remove-linked-intervenant').click(function() {
        $(this).closest('.linked-intervenant-item').remove();
        linkedIntervenantsCount--;
        
        reindexLinkedIntervenants();
        
        if ($('#linked-intervenants-container').children().length === 0) {
            $('#no-linked-intervenants').show();
        }
    });

    // Reset de la modal quand elle se ferme
    $('#linkIntervenantModal').on('hidden.bs.modal', function() {
        $('#intervenantList').val('');
        $('#intervenantFilter').val('');
        $('.intervenant-option').show();
        $('#noResults').hide();
        $('#intervenantPreview').hide();
    });

    // Focus sur le champ de filtre quand la modal s'ouvre
    $('#linkIntervenantModal').on('shown.bs.modal', function() {
        $('#intervenantFilter').focus();
    });
});
    </script>
<script>
$(document).ready(function() {
    // Charger les sous-domaines quand un domaine est sélectionné
    $('#domaine_id').change(function() {
        var domaineId = $(this).val();
        if (domaineId) {
            $.ajax({
                url: "{{ route('sous-domaines.by-domaine') }}",
                type: "GET",
                data: {domaine_id: domaineId},
                success: function(data) {
                    $('#sous_domaine_id').empty();
                    $('#sous_domaine_id').append('<option value="">Sélectionnez un sous-domaine</option>');
                    $.each(data, function(key, value) {
                        $('#sous_domaine_id').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            });
        } else {
            $('#sous_domaine_id').empty();
            $('#sous_domaine_id').append('<option value="">Sélectionnez d\'abord un domaine</option>');
        }
    });

    // Ajouter un domaine via Ajax
    $('#addDomaineForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{ route('domaines.store') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                // Ajouter l'option au select
                $('#domaine_id').append('<option value="'+ response.id +'">'+ response.nom +'</option>');
                $('#domaine_modal_id').append('<option value="'+ response.id +'">'+ response.nom +'</option>');
                
                // Sélectionner le nouveau domaine
                $('#domaine_id').val(response.id);
                
                // Fermer le modal
                $('#addDomaineModal').modal('hide');
                
                // Réinitialiser le formulaire
                $('#addDomaineForm')[0].reset();
                $('#domaine-error').text('');
                
                // Trigger le changement pour charger les sous-domaines
                $('#domaine_id').trigger('change');
                
                toastr.success('Domaine ajouté avec succès!');
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    if (errors.nom) {
                        $('#nom_domaine').addClass('is-invalid');
                        $('#domaine-error').text(errors.nom[0]);
                    }
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
                // Si le domaine sélectionné correspond, ajouter l'option
                if ($('#domaine_id').val() == response.domaine_id) {
                    $('#sous_domaine_id').append('<option value="'+ response.id +'">'+ response.nom +'</option>');
                    $('#sous_domaine_id').val(response.id);
                }
                
                // Fermer le modal
                $('#addSousDomaineModal').modal('hide');
                
                // Réinitialiser le formulaire
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
    });
});
</script>
@endsection


