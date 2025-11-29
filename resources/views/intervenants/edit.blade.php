@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modifier l'Intervenant</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('intervenants.index') }}">Intervenants</a></li>
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
                            <h3 class="card-title">Modification de l'intervenant</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('intervenants.update', $intervenant->id) }}" method="POST" id="intervenantForm" enctype="multipart/form-data">
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
                                        <ul class="nav nav-tabs" id="intervenantTabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">
                                                    <i class="fas fa-info-circle"></i> Général
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="coordonnees-tab" data-toggle="tab" href="#coordonnees" role="tab" aria-controls="coordonnees" aria-selected="false">
                                                    <i class="fas fa-address-book"></i> Coordonnées
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="fichiers-tab" data-toggle="tab" href="#fichiers" role="tab" aria-controls="fichiers" aria-selected="false">
                                                    <i class="fas fa-file"></i> Fichiers
                                                    @if($intervenant->files && count($intervenant->files) > 0)
                                                        <span class="badge badge-primary ml-1">{{ count($intervenant->files) }}</span>
                                                    @endif
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="intervenants-lies-tab" data-toggle="tab" href="#intervenants-lies" role="tab" aria-controls="intervenants-lies" aria-selected="false">
                                                    <i class="fas fa-users"></i> Intervenants Liés
                                                    @if($intervenant->intervenantsLies && count($intervenant->intervenantsLies) > 0)
                                                        <span class="badge badge-primary ml-1">{{ count($intervenant->intervenantsLies) }}</span>
                                                    @endif
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="notes-tab" data-toggle="tab" href="#notes" role="tab" aria-controls="notes" aria-selected="false">
                                                    <i class="fas fa-sticky-note"></i> Notes
                                                </a>
                                            </li>
                                        </ul>
                                        
                                        <div class="tab-content" id="intervenantTabsContent">
                                            <!-- Onglet Général -->
                                            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                                <div class="p-3">
                                                    <!-- Identité -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="identite_fr">Identité (Français) *</label>
                                                                <input type="text" class="form-control @error('identite_fr') is-invalid @enderror" 
                                                                       id="identite_fr" name="identite_fr" 
                                                                       value="{{ old('identite_fr', $intervenant->identite_fr) }}" 
                                                                       placeholder="Nom et prénom ou raison sociale" required>
                                                                @error('identite_fr')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="identite_ar">Identité (Arabe)</label>
                                                                <input type="text" class="form-control @error('identite_ar') is-invalid @enderror" 
                                                                       id="identite_ar" name="identite_ar" 
                                                                       value="{{ old('identite_ar', $intervenant->identite_ar) }}" 
                                                                       placeholder="الاسم الكامل أو التسمية الاجتماعية">
                                                                @error('identite_ar')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Type et Catégorie -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="type">Type *</label>
                                                                <select class="form-control @error('type') is-invalid @enderror" 
                                                                        id="type" name="type" required>
                                                                    <option value="">Sélectionnez un type</option>
                                                                    <option value="personne physique" {{ old('type', $intervenant->type) == 'personne physique' ? 'selected' : '' }}>Personne Physique</option>
                                                                    <option value="personne morale" {{ old('type', $intervenant->type) == 'personne morale' ? 'selected' : '' }}>Personne Morale</option>
                                                                    <option value="entreprise individuelle" {{ old('type', $intervenant->type) == 'entreprise individuelle' ? 'selected' : '' }}>Entreprise Individuelle</option>
                                                                </select>
                                                                @error('type')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="categorie">Catégorie *</label>
                                                                <select class="form-control @error('categorie') is-invalid @enderror" 
                                                                        id="categorie" name="categorie" required>
                                                                    <option value="">Sélectionnez une catégorie</option>
                                                                    <option value="contact" {{ old('categorie', $intervenant->categorie) == 'contact' ? 'selected' : '' }}>Contact</option>
                                                                    <option value="client" {{ old('categorie', $intervenant->categorie) == 'client' ? 'selected' : '' }}>Client</option>
                                                                    <option value="avocat" {{ old('categorie', $intervenant->categorie) == 'avocat' ? 'selected' : '' }}>Avocat</option>
                                                                    <option value="adversaire" {{ old('categorie', $intervenant->categorie) == 'adversaire' ? 'selected' : '' }}>Adversaire</option>
                                                                    <option value="notaire" {{ old('categorie', $intervenant->categorie) == 'notaire' ? 'selected' : '' }}>Notaire</option>
                                                                    <option value="huissier" {{ old('categorie', $intervenant->categorie) == 'huissier' ? 'selected' : '' }}>Huissier</option>
                                                                    <option value="juridiction" {{ old('categorie', $intervenant->categorie) == 'juridiction' ? 'selected' : '' }}>Juridiction</option>
                                                                    <option value="administrateur_judiciaire" {{ old('categorie', $intervenant->categorie) == 'administrateur_judiciaire' ? 'selected' : '' }}>Administrateur Judiciaire</option>
                                                                    <option value="mandataire_judiciaire" {{ old('categorie', $intervenant->categorie) == 'mandataire_judiciaire' ? 'selected' : '' }}>Mandataire Judiciaire</option>
                                                                    <option value="expert_judiciaire" {{ old('categorie', $intervenant->categorie) == 'expert_judiciaire' ? 'selected' : '' }}>Expert Judiciaire</option>
                                                                    <option value="traducteur" {{ old('categorie', $intervenant->categorie) == 'traducteur' ? 'selected' : '' }}>Traducteur</option>
                                                                </select>
                                                                @error('categorie')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Informations professionnelles -->
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="fonction">Fonction</label>
                                                                <input type="text" class="form-control @error('fonction') is-invalid @enderror" 
                                                                       id="fonction" name="fonction" 
                                                                       value="{{ old('fonction', $intervenant->fonction) }}" 
                                                                       placeholder="Fonction ou profession">
                                                                @error('fonction')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="forme_sociale_id">Forme Sociale</label>
                                                                <select class="form-control @error('forme_sociale_id') is-invalid @enderror" 
                                                                        id="forme_sociale_id" name="forme_sociale_id">
                                                                    <option value="">Sélectionnez une forme sociale</option>
                                                                    @foreach($formeSociales as $formeSociale)
                                                                        <option value="{{ $formeSociale->id }}" {{ old('forme_sociale_id', $intervenant->forme_sociale_id) == $formeSociale->id ? 'selected' : '' }}>
                                                                            {{ $formeSociale->nom }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('forme_sociale_id')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="numero_cni">Numéro CNI</label>
                                                                <input type="text" class="form-control @error('numero_cni') is-invalid @enderror" 
                                                                       id="numero_cni" name="numero_cni" 
                                                                       value="{{ old('numero_cni', $intervenant->numero_cni) }}" 
                                                                       placeholder="Numéro de carte d'identité">
                                                                @error('numero_cni')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Numéros d'identification -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="rne">RNE (Registre National des Entreprises)</label>
                                                                <input type="text" class="form-control @error('rne') is-invalid @enderror" 
                                                                       id="rne" name="rne" 
                                                                       value="{{ old('rne', $intervenant->rne) }}" 
                                                                       placeholder="Numéro RNE">
                                                                @error('rne')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="numero_cnss">Numéro CNSS</label>
                                                                <input type="text" class="form-control @error('numero_cnss') is-invalid @enderror" 
                                                                       id="numero_cnss" name="numero_cnss" 
                                                                       value="{{ old('numero_cnss', $intervenant->numero_cnss) }}" 
                                                                       placeholder="Numéro de sécurité sociale">
                                                                @error('numero_cnss')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Statut Archive -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input class="custom-control-input" type="checkbox" 
                                                                           id="archive" name="archive" value="1" 
                                                                           {{ old('archive', $intervenant->archive) ? 'checked' : '' }}>
                                                                    <label for="archive" class="custom-control-label">
                                                                        Marquer comme archivé
                                                                    </label>
                                                                </div>
                                                                <small class="form-text text-muted">
                                                                    Si coché, cet intervenant sera marqué comme archivé et n'apparaîtra pas dans les listes par défaut.
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Onglet Coordonnées -->
                                            <div class="tab-pane fade" id="coordonnees" role="tabpanel" aria-labelledby="coordonnees-tab">
                                                <div class="p-3">
                                                    <!-- Coordonnées téléphoniques -->
                                                    <h5 class="text-primary mb-3"><i class="fas fa-phone"></i> Coordonnées Téléphoniques</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="portable1">Portable 1</label>
                                                                <input type="text" class="form-control @error('portable1') is-invalid @enderror" 
                                                                       id="portable1" name="portable1" 
                                                                       value="{{ old('portable1', $intervenant->portable1) }}" 
                                                                       placeholder="Numéro de portable principal">
                                                                @error('portable1')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="portable2">Portable 2</label>
                                                                <input type="text" class="form-control @error('portable2') is-invalid @enderror" 
                                                                       id="portable2" name="portable2" 
                                                                       value="{{ old('portable2', $intervenant->portable2) }}" 
                                                                       placeholder="Numéro de portable secondaire">
                                                                @error('portable2')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Téléphones fixes -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="fixe1">Téléphone Fixe 1</label>
                                                                <input type="text" class="form-control @error('fixe1') is-invalid @enderror" 
                                                                       id="fixe1" name="fixe1" 
                                                                       value="{{ old('fixe1', $intervenant->fixe1) }}" 
                                                                       placeholder="Numéro de fixe principal">
                                                                @error('fixe1')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="fixe2">Téléphone Fixe 2</label>
                                                                <input type="text" class="form-control @error('fixe2') is-invalid @enderror" 
                                                                       id="fixe2" name="fixe2" 
                                                                       value="{{ old('fixe2', $intervenant->fixe2) }}" 
                                                                       placeholder="Numéro de fixe secondaire">
                                                                @error('fixe2')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Emails -->
                                                    <h5 class="text-primary mb-3 mt-4"><i class="fas fa-envelope"></i> Adresses Email</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mail1">Email 1</label>
                                                                <input type="email" class="form-control @error('mail1') is-invalid @enderror" 
                                                                       id="mail1" name="mail1" 
                                                                       value="{{ old('mail1', $intervenant->mail1) }}" 
                                                                       placeholder="Adresse email principale">
                                                                @error('mail1')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mail2">Email 2</label>
                                                                <input type="email" class="form-control @error('mail2') is-invalid @enderror" 
                                                                       id="mail2" name="mail2" 
                                                                       value="{{ old('mail2', $intervenant->mail2) }}" 
                                                                       placeholder="Adresse email secondaire">
                                                                @error('mail2')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Adresses -->
                                                    <h5 class="text-primary mb-3 mt-4"><i class="fas fa-map-marker-alt"></i> Adresses</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="adresse1">Adresse 1</label>
                                                                <textarea class="form-control @error('adresse1') is-invalid @enderror" 
                                                                          id="adresse1" name="adresse1" 
                                                                          rows="3" placeholder="Adresse principale">{{ old('adresse1', $intervenant->adresse1) }}</textarea>
                                                                @error('adresse1')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="adresse2">Adresse 2</label>
                                                                <textarea class="form-control @error('adresse2') is-invalid @enderror" 
                                                                          id="adresse2" name="adresse2" 
                                                                          rows="3" placeholder="Adresse secondaire (complément)">{{ old('adresse2', $intervenant->adresse2) }}</textarea>
                                                                @error('adresse2')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Site web et Fax -->
                                                    <h5 class="text-primary mb-3 mt-4"><i class="fas fa-globe"></i> Autres Coordonnées</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="site_internet">Site Internet</label>
                                                                <input type="url" class="form-control @error('site_internet') is-invalid @enderror" 
                                                                       id="site_internet" name="site_internet" 
                                                                       value="{{ old('site_internet', $intervenant->site_internet) }}" 
                                                                       placeholder="https://example.com">
                                                                @error('site_internet')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="fax">Fax</label>
                                                                <input type="text" class="form-control @error('fax') is-invalid @enderror" 
                                                                       id="fax" name="fax" 
                                                                       value="{{ old('fax', $intervenant->fax) }}" 
                                                                       placeholder="Numéro de fax">
                                                                @error('fax')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Onglet Fichiers -->
                                            <div class="tab-pane fade" id="fichiers" role="tabpanel" aria-labelledby="fichiers-tab">
                                                <div class="p-3">
                                                    <h5 class="text-primary mb-3"><i class="fas fa-file-upload"></i> Gestion des fichiers</h5>
                                                    
                                                    <!-- Upload de nouveaux fichiers -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="piece_jointe">Nouvelles pièces jointes</label>
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input @error('piece_jointe') is-invalid @enderror" 
                                                                           id="piece_jointe" name="piece_jointe[]" 
                                                                           multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.txt,.zip,.rar">
                                                                    <label class="custom-file-label" for="piece_jointe" id="piece_jointe_label">
                                                                        Choisir des fichiers (PDF, images, Word, Excel) - Max 10MB par fichier
                                                                    </label>
                                                                    @error('piece_jointe')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                    @error('piece_jointe.*')
                                                                        <span class="invalid-feedback d-block" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                                <small class="form-text text-muted">
                                                                    Formats acceptés: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX, TXT, ZIP, RAR - Taille max: 10MB par fichier
                                                                </small>
                                                                
                                                                <!-- Aperçu des nouveaux fichiers -->
                                                                <div id="files_preview" class="mt-3" style="display: none;">
                                                                    <h6 class="text-info">Nouveaux fichiers sélectionnés :</h6>
                                                                    <div id="files_list" class="list-group"></div>
                                                                    <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="clearFileInput()">
                                                                        <i class="fas fa-times"></i> Effacer tous les nouveaux fichiers
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Liste des fichiers existants -->
                                                    <div class="row mt-4">
                                                        <div class="col-md-12">
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <h5 class="card-title mb-0">
                                                                        <i class="fas fa-files-o mr-1"></i>
                                                                        Fichiers existants
                                                                        <small class="text-muted ml-1">({{ $intervenant->files->count() }} fichiers)</small>
                                                                    </h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    @if($intervenant->files->count() > 0)
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered table-hover">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Nom du fichier</th>
                                                                                        <th>Date d'upload</th>
                                                                                        <th>Actions</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($intervenant->files as $file)
                                                                                        <tr>
                                                                                            <td>
                                                                                                <span class="badge badge-info">{{ pathinfo($file->file_path, PATHINFO_EXTENSION) }}</span> {{ $file->file_name ?? $file->file_path }}
                                                                                            </td>
                                                                                            <td>{{ $file->created_at->format('d/m/Y H:i') }}</td>
                                                                                            <td>
                                                                                               <div class="btn-group btn-group-sm">
                                                                                <a href="{{url('intervenant/display')}}/{{ $file->id ?? '#' }}" target="_blank" class="btn btn-info" title="Voir">
                                                                                    <i class="fas fa-eye"></i>
                                                                                </a>
                                                                                <a href="{{url('intervenant/download')}}/{{ $file->id ?? '#' }}" download class="btn btn-success" title="Télécharger">
                                                                                    <i class="fas fa-download"></i>
                                                                                </a>
                                                                            </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    @else
                                                                        <div class="text-center py-4">
                                                                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                                                            <p class="text-muted">Aucun fichier attaché à cet intervenant</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Onglet Intervenants Liés -->
                                            <div class="tab-pane fade" id="intervenants-lies" role="tabpanel" aria-labelledby="intervenants-lies-tab">
                                                <div class="p-3">
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
                                                                            <th width="30%">Relation (de cet intervenant)</th>
                                                                            <th width="30%">Relation (de l'intervenant lié)</th>
                                                                            <th width="10%">Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="linked-intervenants-container">
                                                                        @php
    $existingLinkedIntervenants = [];
    if ($intervenant->intervenantsLies->count() > 0) {
        foreach ($intervenant->intervenantsLies as $index => $linkedIntervenant) {
            $pivot = $linkedIntervenant->pivot;
            
            // Récupérer la relation inverse
            $relationTo = '';
            $inverseRelation = $linkedIntervenant->intervenantsLies->where('id', $intervenant->id)->first();
            if ($inverseRelation) {
                $relationTo = $inverseRelation->pivot->relation ?? '';
            }
            
            $existingLinkedIntervenants[] = [
                'intervenant_id' => $linkedIntervenant->id,
                'intervenant_name' => $linkedIntervenant->identite_fr,
                'relation_from' => $pivot->relation,
                'relation_to' => $relationTo
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
                                                                                           name="linked_intervenants[{{ $index }}][relation_from]" 
                                                                                           value="{{ $linkedIntervenant['relation_from'] ?? '' }}"
                                                                                           placeholder="Ex: Client, Partenaire, Associé..."
                                                                                           required>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" class="form-control" 
                                                                                           name="linked_intervenants[{{ $index }}][relation_to]" 
                                                                                           value="{{ $linkedIntervenant['relation_to'] ?? '' }}"
                                                                                           placeholder="Ex: Avocat, Fournisseur, Collaborateur..."
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
                                                                                           name="linked_intervenants[{{ $index }}][relation_from]" 
                                                                                           value="{{ $linkedIntervenant['relation_from'] }}"
                                                                                           placeholder="Ex: Client, Partenaire, Associé..."
                                                                                           required>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" class="form-control" 
                                                                                           name="linked_intervenants[{{ $index }}][relation_to]" 
                                                                                           value="{{ $linkedIntervenant['relation_to'] }}"
                                                                                           placeholder="Ex: Avocat, Fournisseur, Collaborateur..."
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
                                                                 style="{{ (old('linked_intervenants') || count($existingLinkedIntervenants) > 0) ? 'display: none;' : '' }}">
                                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                                <p class="text-muted">Aucun intervenant lié pour le moment</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="alert alert-info mt-3">
                                                        <h6><i class="icon fas fa-info"></i> Information</h6>
                                                        <p class="mb-0">
                                                            <strong>Relation (de cet intervenant)</strong> : Comment cet intervenant voit l'intervenant lié.<br>
                                                            <strong>Relation (de l'intervenant lié)</strong> : Comment l'intervenant lié voit cet intervenant.<br>
                                                            Exemple : "Client" / "Avocat" ou "Employeur" / "Employé"
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Onglet Notes -->
                                            <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                                                <div class="p-3">
                                                    <!-- Notes -->
                                                    <div class="form-group">
                                                        <label for="notes">Notes et Observations</label>
                                                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                                                  id="notes" name="notes" 
                                                                  rows="12" placeholder="Notes supplémentaires, observations, informations importantes...">{{ old('notes', $intervenant->notes) }}</textarea>
                                                        @error('notes')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="alert alert-info">
                                                        <h5><i class="icon fas fa-info"></i> Informations</h5>
                                                        <p class="mb-0">
                                                            Utilisez cet espace pour noter toutes informations supplémentaires concernant cet intervenant 
                                                            qui pourraient être utiles pour le suivi des dossiers.
                                                        </p>
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
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Mettre à jour
                                        </button>
                                    </div>
                                </div>
                                <div class="text-center mt-2">
                                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left"></i> Retour aux détails
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
                    <select class="form-control search_test1" id="intervenantList">
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

<!-- Modal de confirmation de suppression -->
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
                <p>Êtes-vous sûr de vouloir supprimer le fichier <strong id="fileNameToDelete"></strong> ?</p>
                <p class="text-danger"><small>Cette action est irréversible.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <form id="deleteFileForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<!-- <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> -->
<!-- jquery-validation -->
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.2/jquery.sumoselect.min.js"></script>
<script>
    $('.search_test1').SumoSelect({search: true, searchText: 'Sélectionner un intervenant...'});
// Fonction pour effacer l'input file
function clearFileInput() {
    $('#piece_jointe').val('');
    $('#piece_jointe_label').text('Choisir des fichiers (PDF, images, Word, Excel) - Max 10MB par fichier');
    $('#files_preview').hide();
    $('#files_list').empty();
}

// Fonction pour formater la taille des fichiers
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Fonction pour obtenir l'icône du fichier selon son type
function getFileIcon(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    const iconMap = {
        'pdf': 'fa-file-pdf text-danger',
        'jpg': 'fa-file-image text-success',
        'jpeg': 'fa-file-image text-success',
        'png': 'fa-file-image text-success',
        'doc': 'fa-file-word text-primary',
        'docx': 'fa-file-word text-primary',
        'xls': 'fa-file-excel text-success',
        'xlsx': 'fa-file-excel text-success',
        'txt': 'fa-file-alt text-secondary',
        'zip': 'fa-file-archive text-warning',
        'rar': 'fa-file-archive text-warning'
    };
    return iconMap[ext] || 'fa-file text-secondary';
}

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

    addLinkedIntervenant(intervenantId, intervenantName);
    
    // Reset la modal
    $('#intervenantList').val('');
    $('#intervenantFilter').val('');
    $('.intervenant-option').show();
    $('#noResults').hide();
    $('#intervenantPreview').hide();
    $('#linkIntervenantModal').modal('hide');
});

function addLinkedIntervenant(intervenantId, intervenantName) {
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
                       name="linked_intervenants[${newIndex}][relation_from]" 
                       placeholder="Ex: Client, Partenaire, Associé..."
                       required>
            </td>
            <td>
                <input type="text" class="form-control" 
                       name="linked_intervenants[${newIndex}][relation_to]" 
                       placeholder="Ex: Avocat, Fournisseur, Collaborateur..."
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

    // Gestion de la navigation par onglets
    let currentTab = 0;
    const tabs = $('#intervenantTabs .nav-link');
    const tabPanes = $('.tab-pane');

    // Afficher/masquer les boutons de navigation
    function updateNavigationButtons() {
        if (currentTab === 0) {
            $('.btn-previous').hide();
            $('.btn-next').show();
        } else if (currentTab === tabs.length - 1) {
            $('.btn-previous').show();
            $('.btn-next').hide();
        } else {
            $('.btn-previous').show();
            $('.btn-next').show();
        }
    }

    // Aller à l'onglet suivant
    $('.btn-next').click(function() {
        if (currentTab < tabs.length - 1) {
            currentTab++;
            $(tabs[currentTab]).tab('show');
            updateNavigationButtons();
        }
    });

    // Aller à l'onglet précédent
    $('.btn-previous').click(function() {
        if (currentTab > 0) {
            currentTab--;
            $(tabs[currentTab]).tab('show');
            updateNavigationButtons();
        }
    });

    // Mettre à jour la navigation quand on clique directement sur un onglet
    tabs.on('shown.bs.tab', function(e) {
        currentTab = tabs.index(e.target);
        updateNavigationButtons();
    });

    // Gestion de l'input file multiple
    $('#piece_jointe').on('change', function(e) {
        const files = e.target.files;
        
        if (files.length > 0) {
            let fileNames = [];
            let totalSize = 0;
            let hasOversizedFile = false;
            
            // Vider la liste précédente
            $('#files_list').empty();
            
            // Parcourir tous les fichiers
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                fileNames.push(file.name);
                totalSize += file.size;
                
                // Vérifier la taille du fichier
                if (file.size > 10 * 1024 * 1024) { // 10MB en bytes
                    hasOversizedFile = true;
                }
                
                // Ajouter à l'aperçu
                const fileItem = `
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas ${getFileIcon(file.name)} mr-2"></i>
                            <span class="file-name">${file.name}</span>
                        </div>
                        <div>
                            <span class="badge badge-info badge-pill">${formatFileSize(file.size)}</span>
                            ${file.size > 10 * 1024 * 1024 ? '<span class="badge badge-danger badge-pill ml-1">Trop volumineux</span>' : ''}
                        </div>
                    </div>
                `;
                $('#files_list').append(fileItem);
            }
            
            // Mettre à jour le label
            if (files.length === 1) {
                $('#piece_jointe_label').text(fileNames[0]);
            } else {
                $('#piece_jointe_label').text(files.length + ' fichiers sélectionnés');
            }
            
            // Afficher l'aperçu
            $('#files_preview').show();
            
            // Afficher un avertissement si des fichiers sont trop volumineux
            if (hasOversizedFile) {
                alert('Certains fichiers dépassent la taille maximale de 10MB. Ils ne pourront pas être uploadés.');
            }
            
            // Afficher la taille totale
            const totalSizeItem = `
                <div class="list-group-item list-group-item-secondary d-flex justify-content-between align-items-center">
                    <strong>Total</strong>
                    <strong>${formatFileSize(totalSize)}</strong>
                </div>
            `;
            $('#files_list').append(totalSizeItem);
            
        } else {
            clearFileInput();
        }
    });

    // Gestion de la suppression des fichiers
    $('.delete-file-btn').click(function() {
        const fileId = $(this).data('file-id');
        const fileName = $(this).data('file-name');
        
        $('#fileNameToDelete').text(fileName);
        $('#deleteFileForm').attr('action', '{{ url("intervenant-files") }}/' + fileId);
        $('#deleteFileModal').modal('show');
    });

    // Validation du formulaire
    $('#intervenantForm').validate({
        rules: {
            identite_fr: {
                required: true,
                minlength: 2
            },
            type: {
                required: true
            },
            categorie: {
                required: true
            },
            // portable1: {
            //     required: true
            // },
            // mail1: {
            //     required: true,
            //     email: true
            // },
            // adresse1: {
            //     required: true
            // },
            mail2: {
                email: true
            },
            site_internet: {
                url: true
            },
            'piece_jointe[]': {
                accept: "application/pdf,image/jpeg,image/jpg,image/png,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain,application/zip,application/x-rar-compressed",
                filesize: 10485760 // 10MB in bytes
            },
            'linked_intervenants[*][relation_from]': {
                required: true
            },
            'linked_intervenants[*][relation_to]': {
                required: true
            }
        },
        messages: {
            identite_fr: {
                required: "L'identité en français est obligatoire",
                minlength: "L'identité doit contenir au moins 2 caractères"
            },
            type: {
                required: "Le type d'intervenant est obligatoire"
            },
            categorie: {
                required: "La catégorie est obligatoire"
            },
            portable1: {
                required: "Le numéro de portable est obligatoire"
            },
            mail1: {
                required: "L'adresse email est obligatoire",
                email: "Veuillez entrer une adresse email valide"
            },
            adresse1: {
                required: "L'adresse est obligatoire"
            },
            mail2: {
                email: "Veuillez entrer une adresse email valide"
            },
            site_internet: {
                url: "Veuillez entrer une URL valide"
            },
            'piece_jointe[]': {
                accept: "Veuillez choisir des fichiers de type PDF, image, Word, Excel, texte ou archive",
                filesize: "La taille d'un fichier ne doit pas dépasser 10MB"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        invalidHandler: function(event, validator) {
            // Aller à l'onglet contenant la première erreur
            const firstError = validator.errorList[0];
            if (firstError) {
                const errorElement = $(firstError.element);
                const tabPane = errorElement.closest('.tab-pane');
                const tabId = tabPane.attr('id');
                const tabLink = $(`[href="#${tabId}"]`);
                
                tabLink.tab('show');
                currentTab = tabs.index(tabLink[0]);
                updateNavigationButtons();
                
                // Scroll vers l'erreur
                $('html, body').animate({
                    scrollTop: errorElement.offset().top - 100
                }, 500);
            }
        }
    });

    updateNavigationButtons();
});
</script>
<script>
$(document).ready(function() {
    // AJAX form submission
    $('#intervenantForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        // Show loading state
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mise à jour...');
        
        // Create FormData to handle file uploads
        const formData = new FormData(this);
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Show success message
                showAlert('success', response.message || 'Intervenant mis à jour avec succès!');
                
                // Reset loading state
                submitBtn.prop('disabled', false).html(originalText);
                
                // Optionally redirect after success
                // setTimeout(() => {
                //     window.location.href = response.redirect_url || '{{ route("intervenants.show", $intervenant->id) }}';
                // }, 1500);
            },
            error: function(xhr) {
                // Reset loading state
                submitBtn.prop('disabled', false).html(originalText);
                
                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    displayValidationErrors(errors);
                } else {
                    // Other errors
                    showAlert('danger', xhr.responseJSON.message || 'Une erreur est survenue lors de la mise à jour.');
                }
            }
        });
    });
    
    // Function to display validation errors
    function displayValidationErrors(errors) {
        // Remove existing error displays
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Display new errors
        $.each(errors, function(field, messages) {
            const input = $(`[name="${field}"]`);
            const tabPane = input.closest('.tab-pane');
            
            // Add error styling
            input.addClass('is-invalid');
            
            // Create error message
            const errorHtml = `<span class="invalid-feedback" role="alert"><strong>${messages[0]}</strong></span>`;
            
            // Append error message
            if (input.closest('.form-group').length) {
                input.closest('.form-group').append(errorHtml);
            } else {
                input.after(errorHtml);
            }
            
            // Switch to tab containing the error
            if (tabPane.length) {
                const tabId = tabPane.attr('id');
                $(`[href="#${tabId}"]`).tab('show');
            }
        });
        
        // Scroll to first error
        const firstError = $('.is-invalid').first();
        if (firstError.length) {
            $('html, body').animate({
                scrollTop: firstError.offset().top - 100
            }, 500);
        }
    }
    
    // Function to show alert messages
    function showAlert(type, message) {
        // Remove existing alerts
        $('.ajax-alert').remove();
        
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible ajax-alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                ${message}
            </div>
        `;
        
        // Insert alert at the top of the form
        $('#intervenantForm .card-body').prepend(alertHtml);
        
        // Auto-remove success alerts after 5 seconds
        if (type === 'success') {
            setTimeout(() => {
                $('.ajax-alert').alert('close');
            }, 5000);
        }
    }
    
    // AJAX file deletion
    $(document).on('click', '.delete-file-btn1', function(e) {
        e.preventDefault();
        
        const fileId = $(this).data('file-id');
        const fileName = $(this).data('file-name');
        const button = $(this);
        
        // if (confirm(`Êtes-vous sûr de vouloir supprimer le fichier "${fileName}" ?`)) {
            $.ajax({
                url: `{{ url('intervenant-files') }}/${fileId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Remove file row from table
                    button.closest('tr').fadeOut(300, function() {
                        $(this).remove();
                        
                        // Update file count in tab
                        updateFileCount();
                        
                      //  showAlert('success', response.message || 'Fichier supprimé avec succès!');
                    });
                },
                error: function(xhr) {
                    showAlert('danger', xhr.responseJSON.message || 'Erreur lors de la suppression du fichier.');
                }
            });
        // }
    });
    
    // Function to update file count in tab
    function updateFileCount() {
        const fileCount = $('#fichiers .table tbody tr').length;
        const fileBadge = $('#fichiers-tab .badge');
        
        if (fileCount > 0) {
            fileBadge.text(fileCount);
        } else {
            fileBadge.remove();
        }
    }
    
    // Real-time validation for some fields
    $('#mail1, #mail2').on('blur', function() {
        const email = $(this).val();
        if (email && !isValidEmail(email)) {
            $(this).addClass('is-invalid');
            $(this).after('<span class="invalid-feedback"><strong>Veuillez entrer une adresse email valide</strong></span>');
        }
    });
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // Clear validation on input
    $('input, select, textarea').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });
});
</script>
<style>
.nav-tabs .nav-link {
    font-weight: 500;
    padding: 0.75rem 1.5rem;
}

.nav-tabs .nav-link.active {
    border-bottom: 3px solid #007bff;
    font-weight: 600;
}

.tab-content {
    border: 1px solid #dee2e6;
    border-top: none;
    border-radius: 0 0 0.25rem 0.25rem;
}

.tab-pane {
    min-height: 400px;
}

.btn-previous, .btn-next {
    min-width: 120px;
}

/* Amélioration de l'apparence des sections */
h5.text-primary {
    border-bottom: 2px solid #007bff;
    padding-bottom: 0.5rem;
    margin-bottom: 1.5rem !important;
}

/* Styles pour l'aperçu des fichiers */
#files_preview .list-group-item {
    border-left: 4px solid #007bff;
}

.file-name {
    word-break: break-all;
}

.fa-file-pdf { color: #dc3545; }
.fa-file-image { color: #28a745; }
.fa-file-word { color: #007bff; }
.fa-file-excel { color: #28a745; }
.fa-file-alt { color: #6c757d; }
.fa-file-archive { color: #ffc107; }

/* Styles pour le tableau des intervenants liés */
#linkedIntervenantsTable {
    font-size: 0.9rem;
}

#linkedIntervenantsTable th {
    background-color: #343a40;
    color: white;
    font-weight: 600;
}

.linked-intervenant-item td {
    vertical-align: middle;
    padding: 0.75rem;
}

.linked-intervenant-item:hover {
    background-color: #f8f9fa;
}

/* Styles pour les champs de relation */
.linked-intervenant-item input[type="text"] {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.linked-intervenant-item input[type="text"]:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
.SelectClass,.SumoSelect.open .search-txt,.SumoUnder {
  position:absolute;
  -webkit-box-sizing:border-box;
  -moz-box-sizing:border-box;
  top:0;
  left:0
}
.SumoSelect p {
  margin:0
}
.SumoSelect {
  width:100%;
}
.SelectBox {
  padding:5px 8px
}
.sumoStopScroll {
  overflow:hidden
}
.SumoSelect .hidden {
  display:none
}
.SumoSelect .search-txt {
  display:none;
  outline:0
}
.SumoSelect .no-match {
  display:none;
  padding:6px
}
.SumoSelect.open .search-txt {
  display:inline-block;
  width:100%;
  margin:0;
  padding:5px 8px;
  border:none;
  box-sizing:border-box;
  border-radius:5px
}
.SumoSelect.open>.search>label,.SumoSelect.open>.search>span {
visibility:hidden
}
.SelectClass,.SumoUnder {
  right:0;
  height:100%;
  width:100%;
  border:none;
  box-sizing:border-box;
  -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
  filter:alpha(opacity=0);
  -moz-opacity:0;
  -khtml-opacity:0;
  opacity:0
}
.SelectClass {
  z-index:1
}
.SumoSelect .select-all>label,.SumoSelect>.CaptionCont,.SumoSelect>.optWrapper>.options li.opt label {
  user-select:none;
  -o-user-select:none;
  -moz-user-select:none;
  -khtml-user-select:none;
  -webkit-user-select:none
}
.SumoSelect {
  display:inline-block;
  position:relative;
  outline:0
}
.SumoSelect.open>.CaptionCont,.SumoSelect:focus>.CaptionCont,.SumoSelect:hover>.CaptionCont {
  box-shadow:0 0 2px #7799D0;
  border-color:#7799D0
} 
.SumoSelect>.CaptionCont {
  position:relative;
  border:1px solid #A4A4A4;
  min-height:14px;
  background-color:#fff;
  border-radius:2px;
  margin:0
}
.SumoSelect>.CaptionCont>span {
  display:block;
  padding-right:30px;
  text-overflow:ellipsis;
  white-space:nowrap;
  overflow:hidden;
  cursor:default
}
.SumoSelect>.CaptionCont>span.placeholder {
  color:#ccc;
  font-style:italic
}
.SumoSelect>.CaptionCont>label {
  position:absolute;
  top:0;
  right:0;
  bottom:0;
  width:30px
}
.SumoSelect>.CaptionCont>label>i {
  background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA0AAAANCAYAAABy6+R8AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3wMdBhAJ/fwnjwAAAGFJREFUKM9jYBh+gBFKuzEwMKQwMDB8xaOWlYGB4T4DA0MrsuapDAwM//HgNwwMDDbYTJuGQ8MHBgYGJ1xOYGNgYJiBpuEpAwODHSF/siDZ+ISBgcGClEDqZ2Bg8B6CkQsAPRga0cpRtDEAAAAASUVORK5CYII=);
  background-position:center center;
  width:16px;
  height:16px;
  display:block;
  position:absolute;
  top:0;
  left:0;
  right:0;
  bottom:0;
  margin:auto;
  background-repeat:no-repeat;
  opacity:.8
}
.SumoSelect>.optWrapper {
  display:none;
  z-index:1000;
  top:30px;
  width:100%;
  position:absolute;
  left:0;
  -webkit-box-sizing:border-box;
  -moz-box-sizing:border-box;
  box-sizing:border-box;
  background:#fff;
  border:1px solid #ddd;
  box-shadow:2px 3px 3px rgba(0,0,0,.11);
  border-radius:3px;
  overflow:hidden
}
.SumoSelect.open>.optWrapper {
  top:35px;
  display:block
}
.SumoSelect.open>.optWrapper.up {
  top:auto;
  bottom:100%;
  margin-bottom:5px
}
.SumoSelect>.optWrapper ul {
  list-style:none;
  display:block;
  padding:0;
  margin:0;
  overflow:auto
}
.SumoSelect>.optWrapper>.options {
  border-radius:2px;
  position:relative;
  max-height:250px
}
.SumoSelect>.optWrapper>.options li.group.disabled>label {
  opacity:.5
}
.SumoSelect>.optWrapper>.options li ul li.opt {
  padding-left:22px
}
.SumoSelect>.optWrapper.multiple>.options li ul li.opt {
  padding-left:50px
}
.SumoSelect>.optWrapper.isFloating>.options {
  max-height:100%;
  box-shadow:0 0 100px #595959
}
.SumoSelect>.optWrapper>.options li.opt {
  padding:6px;
  position:relative;
  border-bottom:1px solid #f5f5f5
}
.SumoSelect>.optWrapper>.options>li.opt:first-child {
  border-radius:2px 2px 0 0
}
.SumoSelect>.optWrapper>.options>li.opt:last-child {
  border-radius:0 0 2px 2px;
  border-bottom:none
}
.SumoSelect>.optWrapper>.options li.opt:hover {
  background-color:#E4E4E4
}
.SumoSelect>.optWrapper>.options li.opt.sel {
  background-color:#a1c0e4;
  border-bottom:1px solid #a1c0e4
}
.SumoSelect>.optWrapper>.options li label {
  text-overflow:ellipsis;
  white-space:nowrap;
  overflow:hidden;
  display:block;
  cursor:pointer
}
.SumoSelect>.optWrapper>.options li span {
  display:none
}
.SumoSelect>.optWrapper>.options li.group>label {
  cursor:default;
  padding:8px 6px;
  font-weight:700
}
.SumoSelect>.optWrapper.isFloating {
  position:fixed;
  top:0;
  left:0;
  right:0;
  width:90%;
  bottom:0;
  margin:auto;
  max-height:90%
}
.SumoSelect>.optWrapper>.options li.opt.disabled {
  background-color:inherit;
  pointer-events:none
}
.SumoSelect>.optWrapper>.options li.opt.disabled * {
  -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";
  filter:alpha(opacity=50);
  -moz-opacity:.5;
  -khtml-opacity:.5;
  opacity:.5
}
.SumoSelect>.optWrapper.multiple>.options li.opt {
  padding-left:35px;
  cursor:pointer
}
.SumoSelect .select-all>span,.SumoSelect>.optWrapper.multiple>.options li.opt span {
  position:absolute;
  display:block;
  width:30px;
  top:0;
  bottom:0;
  margin-left:-35px
}
.SumoSelect .select-all>span i,.SumoSelect>.optWrapper.multiple>.options li.opt span i {
  position:absolute;
  margin:auto;
  left:0;
  right:0;
  top:0;
  bottom:0;
  width:14px;
  height:14px;
  border:1px solid #AEAEAE;
  border-radius:2px;
  box-shadow:inset 0 1px 3px rgba(0,0,0,.15);
  background-color:#fff
}
.SumoSelect>.optWrapper>.MultiControls {
  display:none;
  border-top:1px solid #ddd;
  background-color:#fff;
  box-shadow:0 0 2px rgba(0,0,0,.13);
  border-radius:0 0 3px 3px
}
.SumoSelect>.optWrapper.multiple.isFloating>.MultiControls {
  display:block;
  margin-top:5px;
  position:absolute;
  bottom:0;
  width:100%
}
.SumoSelect>.optWrapper.multiple.okCancelInMulti>.MultiControls {
  display:block
}
.SumoSelect>.optWrapper.multiple.okCancelInMulti>.MultiControls>p {
  padding:6px
}
.SumoSelect>.optWrapper.multiple>.MultiControls>p {
  display:inline-block;
  cursor:pointer;
  padding:12px;
  width:50%;
  box-sizing:border-box;
  text-align:center
}
.SumoSelect>.optWrapper.multiple>.MultiControls>p:hover {
  background-color:#f1f1f1
}
.SumoSelect>.optWrapper.multiple>.MultiControls>p.btnOk {
  border-right:1px solid #DBDBDB;
  border-radius:0 0 0 3px 
}
.SumoSelect>.optWrapper.multiple>.MultiControls>p.btnCancel {
  border-radius:0 0 3px
}
.SumoSelect>.optWrapper.isFloating>.options li.opt {
  padding:12px 6px 
}
.SumoSelect>.optWrapper.multiple.isFloating>.options li.opt {
  padding-left:35px
}
.SumoSelect>.optWrapper.multiple.isFloating {
  padding-bottom:43px
}
.SumoSelect .select-all.partial>span i,.SumoSelect .select-all.selected>span i,.SumoSelect>.optWrapper.multiple>.options li.opt.selected span i {
  background-color:#11a911;
  box-shadow:none;
  border-color:transparent;
  background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAGCAYAAAD+Bd/7AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNXG14zYAAABMSURBVAiZfc0xDkAAFIPhd2Kr1WRjcAExuIgzGUTIZ/AkImjSofnbNBAfHvzAHjOKNzhiQ42IDFXCDivaaxAJd0xYshT3QqBxqnxeHvhunpu23xnmAAAAAElFTkSuQmCC);
  background-repeat:no-repeat;
  background-position:center center
}
.SumoSelect.disabled {
  opacity:.7;
  cursor:not-allowed
}
.SumoSelect.disabled>.CaptionCont {
  border-color:#ccc;
  box-shadow:none
}
.SumoSelect .select-all {
  border-radius:3px 3px 0 0;
  position:relative;
  border-bottom:1px solid #ddd;
  background-color:#fff;
  padding:8px 0 3px 35px;
  height:20px;
  cursor:pointer
}
.SumoSelect .select-all>label,.SumoSelect .select-all>span i {
  cursor:pointer
}
.SumoSelect .select-all.partial>span i {
  background-color:#ccc
}
.SumoSelect>.optWrapper>.options li.optGroup {
  padding-left:5px;
  text-decoration:underline
}
/*# sourceMappingURL=sumoselect.min.css.map */
</style>
@endsection
