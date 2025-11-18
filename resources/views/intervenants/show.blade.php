{{-- resources/views/intervenants/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Détails de l'Intervenant</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('intervenants.index') }}">Intervenants</a></li>
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
                            <h3 class="card-title">
                                <i class="fas fa-user mr-2"></i>
                                {{ $intervenant->identite_fr ?? 'N/A' }}
                                @if($intervenant->archive ?? false)
                                    <span class="badge badge-warning ml-2">Archivé</span>
                                @endif
                            </h3>
                            <div class="card-tools">
                                @if(auth()->user()->hasPermission('edit_intervenants'))
                                <a href="{{ route('intervenants.edit', $intervenant->id ?? '') }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                @endif
                                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm ml-1">
                                    <i class="fas fa-arrow-left"></i> Retour
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
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
                                        <li class="nav-item d-none">
                                            <a class="nav-link" id="fichiers-tab" data-toggle="tab" href="#fichiers" role="tab" aria-controls="fichiers" aria-selected="false">
                                                <i class="fas fa-file"></i> Fichiers
                                                @if(($intervenant->files ?? null) && count($intervenant->files) > 0)
                                                    <span class="badge badge-primary ml-1">{{ count($intervenant->files) }}</span>
                                                @endif
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="intervenants-lies-tab" data-toggle="tab" href="#intervenants-lies" role="tab" aria-controls="intervenants-lies" aria-selected="false">
                                                <i class="fas fa-users"></i> Intervenants Liés
                                                @if(($intervenant->intervenantsLies ?? null) && count($intervenant->intervenantsLies) > 0)
                                                    <span class="badge badge-primary ml-1">{{ count($intervenant->intervenantsLies) }}</span>
                                                @endif
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="dossiers-lies-tab" data-toggle="tab" href="#dossiers-lies" role="tab" aria-controls="dossiers-lies" aria-selected="false">
                                                <i class="fas fa-folder-open"></i> Dossiers Liés
                                                @if(($intervenant->dossiers ?? null) && count($intervenant->dossiers) > 0)
                                                    <span class="badge badge-primary ml-1">{{ count($intervenant->dossiers) }}</span>
                                                @endif
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="activites-tab" data-toggle="tab" href="#activites" role="tab" aria-controls="activites" aria-selected="false">
                                                <i class="fas fa-calendar-alt"></i> Activités
                                                @if((($intervenant->agendas ?? null) && count($intervenant->agendas) > 0) || (($intervenant->tasks ?? null) && count($intervenant->tasks) > 0))
                                                    <span class="badge badge-info ml-1">
                                                        {{ (($intervenant->agendas ? count($intervenant->agendas) : 0)) + (($intervenant->tasks ? count($intervenant->tasks) : 0)) }}
                                                    </span>
                                                @endif
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="notes-tab" data-toggle="tab" href="#notes" role="tab" aria-controls="notes" aria-selected="false">
                                                <i class="fas fa-sticky-note"></i> Notes
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">
                                                <i class="fas fa-sticky-note"></i> Répertoire des fichiers
                                            </a>
                                        </li>
                                    </ul>
                                    
                                    <div class="tab-content" id="intervenantTabsContent">
                                        <!-- Onglet Général -->
                                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                            <div class="p-3">
                                                <div class="row">
                                                    <!-- Informations principales -->
                                                    <div class="col-md-6">
                                                        <h5 class="section-title">Identité</h5>
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <th style="width: 40%;">Identité (Français)</th>
                                                                <td>{{ $intervenant->identite_fr ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Identité (Arabe)</th>
                                                                <td>{{ $intervenant->identite_ar ?? 'Non renseigné' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Type</th>
                                                                <td>
                                                                    <span class="badge badge-info">
                                                                        {{ ucfirst($intervenant->type ?? 'Non spécifié') }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Catégorie</th>
                                                                <td>
                                                                    <span class="badge badge-primary">
                                                                        {{ isset($intervenant->categorie) ? ucfirst(str_replace('_', ' ', $intervenant->categorie)) : 'Non spécifiée' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>

                                                    <!-- Informations professionnelles -->
                                                    <div class="col-md-6">
                                                        <h5 class="section-title">Informations Professionnelles</h5>
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <th style="width: 40%;">Fonction</th>
                                                                <td>{{ $intervenant->fonction ?? 'Non renseigné' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Forme Sociale</th>
                                                                <td>{{ $intervenant->forme_sociale->nom ?? 'Non renseigné' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Numéro CNI</th>
                                                                <td>{{ $intervenant->numero_cni ?? 'Non renseigné' }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <!-- Numéros d'identification -->
                                                    <div class="col-md-6">
                                                        <h5 class="section-title">Numéros d'Identification</h5>
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <th style="width: 40%;">RNE</th>
                                                                <td>{{ $intervenant->rne ?? 'Non renseigné' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Numéro CNSS</th>
                                                                <td>{{ $intervenant->numero_cnss ?? 'Non renseigné' }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>

                                                    <!-- Statut -->
                                                    <div class="col-md-6">
                                                        <h5 class="section-title">Statut</h5>
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <th style="width: 40%;">ID Intervenant</th>
                                                                <td>{{ $intervenant->id ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Statut</th>
                                                                <td>
                                                                    @if($intervenant->archive ?? false)
                                                                        <span class="badge badge-warning">Archivé</span>
                                                                    @else
                                                                        <span class="badge badge-success">Actif</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Onglet Coordonnées -->
                                        <div class="tab-pane fade" id="coordonnees" role="tabpanel" aria-labelledby="coordonnees-tab">
                                            <div class="p-3">
                                                <div class="row">
                                                    <!-- Coordonnées téléphoniques -->
                                                    <div class="col-md-6">
                                                        <h5 class="section-title">Coordonnées Téléphoniques</h5>
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <th style="width: 40%;">Portable 1</th>
                                                                <td>
                                                                    @if($intervenant->portable1 ?? null)
                                                                        <a href="tel:{{ $intervenant->portable1 }}" class="text-decoration-none">
                                                                            <i class="fas fa-phone-alt mr-2 text-success"></i>{{ $intervenant->portable1 }}
                                                                        </a>
                                                                    @else
                                                                        Non renseigné
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Portable 2</th>
                                                                <td>
                                                                    @if($intervenant->portable2 ?? null)
                                                                        <a href="tel:{{ $intervenant->portable2 }}" class="text-decoration-none">
                                                                            <i class="fas fa-phone-alt mr-2 text-success"></i>{{ $intervenant->portable2 }}
                                                                        </a>
                                                                    @else
                                                                        Non renseigné
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Téléphone Fixe 1</th>
                                                                <td>
                                                                    @if($intervenant->fixe1 ?? null)
                                                                        <a href="tel:{{ $intervenant->fixe1 }}" class="text-decoration-none">
                                                                            <i class="fas fa-phone mr-2 text-primary"></i>{{ $intervenant->fixe1 }}
                                                                        </a>
                                                                    @else
                                                                        Non renseigné
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Téléphone Fixe 2</th>
                                                                <td>
                                                                    @if($intervenant->fixe2 ?? null)
                                                                        <a href="tel:{{ $intervenant->fixe2 }}" class="text-decoration-none">
                                                                            <i class="fas fa-phone mr-2 text-primary"></i>{{ $intervenant->fixe2 }}
                                                                        </a>
                                                                    @else
                                                                        Non renseigné
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>

                                                    <!-- Emails et Adresses -->
                                                    <div class="col-md-6">
                                                        <h5 class="section-title">Emails et Adresses</h5>
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <th style="width: 40%;">Email 1</th>
                                                                <td>
                                                                    @if($intervenant->mail1 ?? null)
                                                                        <a href="mailto:{{ $intervenant->mail1 }}" class="text-decoration-none">
                                                                            <i class="fas fa-envelope mr-2 text-info"></i>{{ $intervenant->mail1 }}
                                                                        </a>
                                                                    @else
                                                                        Non renseigné
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Email 2</th>
                                                                <td>
                                                                    @if($intervenant->mail2 ?? null)
                                                                        <a href="mailto:{{ $intervenant->mail2 }}" class="text-decoration-none">
                                                                            <i class="fas fa-envelope mr-2 text-info"></i>{{ $intervenant->mail2 }}
                                                                        </a>
                                                                    @else
                                                                        Non renseigné
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Adresse 1</th>
                                                                <td>
                                                                    @if($intervenant->adresse1 ?? null)
                                                                        <i class="fas fa-map-marker-alt mr-2 text-danger"></i>{{ $intervenant->adresse1 }}
                                                                    @else
                                                                        Non renseigné
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Adresse 2</th>
                                                                <td>
                                                                    @if($intervenant->adresse2 ?? null)
                                                                        <i class="fas fa-map-marker-alt mr-2 text-danger"></i>{{ $intervenant->adresse2 }}
                                                                    @else
                                                                        Non renseigné
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <!-- Autres coordonnées -->
                                                    <div class="col-md-6">
                                                        <h5 class="section-title">Autres Coordonnées</h5>
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <th style="width: 40%;">Site Internet</th>
                                                                <td>
                                                                    @if($intervenant->site_internet ?? null)
                                                                        <a href="{{ $intervenant->site_internet }}" target="_blank" class="text-decoration-none">
                                                                            <i class="fas fa-external-link-alt mr-2 text-warning"></i>{{ $intervenant->site_internet }}
                                                                        </a>
                                                                    @else
                                                                        Non renseigné
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Fax</th>
                                                                <td>{{ $intervenant->fax ?? 'Non renseigné' }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Onglet Fichiers (unchanged) -->
                                        <x-intervenant.file-liste :intervenant="$intervenant" />

                                        <!-- Onglet Intervenants Liés (unchanged) -->
                                        <x-intervenants.intervenant-liste :intervenant="$intervenant" />

                                        <!-- Onglet Notes (unchanged) -->
                                        <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                                            <div class="p-3">
                                                <!-- Notes -->
                                                <div class="info-group">
                                                    <label class="font-weight-bold">Notes et Observations</label>
                                                    <div class="form-control-plaintext bg-light p-3 rounded" style="min-height: 200px;">
                                                        @if($intervenant->notes ?? null)
                                                            {!! nl2br(e($intervenant->notes)) !!}
                                                        @else
                                                            <p class="text-muted font-italic">Aucune note n'a été enregistrée pour cet intervenant.</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Onglet Activités (unchanged) -->
                                        <x-intervenants.activity-liste :intervenant="$intervenant" />

                                        <!-- Onglet Dossiers (unchanged) -->
                                        <x-intervenants.dossier-liste :intervenant="$intervenant" />
                                        <x-intervenant.folder.liste :intervenant="$intervenant" />
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-plus"></i> Créé le: {{ ($intervenant->created_at ?? now())->format('d/m/Y à H:i') }}
                                    </small>
                                    @if(($intervenant->created_at ?? null) != ($intervenant->updated_at ?? null))
                                        <small class="text-muted ml-3">
                                            <i class="fas fa-edit"></i> Modifié le: {{ ($intervenant->updated_at ?? now())->format('d/m/Y à H:i') }}
                                        </small>
                                    @endif
                                </div>
                                <div>
                                    @if(auth()->user()->hasPermission('edit_intervenants'))
                                    <a href="{{ route('intervenants.edit', $intervenant->id ?? '') }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    @endif
                                    <a href="{{ url()->previous() }}" class="btn btn-secondary ml-1">
                                        <i class="fas fa-arrow-left"></i> Retour à la liste
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<style>
.section-title {
    color: #495057;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.table-bordered {
    border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
    padding: 0.75rem;
    vertical-align: top;
}

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

.badge {
    font-size: 0.85em;
    padding: 0.4em 0.8em;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.alert-info {
    background-color: #e8f4fd;
    border-color: #b6e0fe;
    color: black;
}
</style>
<script>
$(document).ready(function() {
    // Initialisation des onglets
    $('#intervenantTabs a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // Affichage des tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endsection