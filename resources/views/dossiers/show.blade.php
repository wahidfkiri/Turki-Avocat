@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Détails du Dossier</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dossiers.index') }}">Dossiers</a></li>
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
                                <i class="fas fa-folder mr-2"></i>
                               {{$dossier->numero_dossier}} {{$dossier->nom_dossier}}
                            </h3>
                        </div>
                        <div class="card-body">
                            <!-- Navigation par onglets -->
                            <div class="row">
                                <div class="col-md-12">
                        <div id="result" class="mt-3"></div>
                                    <ul class="nav nav-tabs" id="dossierTabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="generale-tab" data-toggle="tab" href="#generale" role="tab" aria-controls="generale" aria-selected="true">
                                                <i class="fas fa-info-circle"></i> Générale
                                            </a>
                                        </li>
                                        <!-- <li class="nav-item">
                                            <a class="nav-link" id="juridique-tab" data-toggle="tab" href="#juridique" role="tab" aria-controls="juridique" aria-selected="false">
                                                <i class="fas fa-gavel"></i> Information Juridique
                                            </a>
                                        </li> -->
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
                                        <li class="nav-item">
                                            <a class="nav-link" id="agenda-tab" data-toggle="tab" href="#agenda" role="tab" aria-controls="agenda" aria-selected="false">
                                                <i class="fas fa-calendar-alt"></i> Agenda
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="tasks-tab" data-toggle="tab" href="#tasks" role="tab" aria-controls="tasks" aria-selected="false">
                                                <i class="fas fa-tasks"></i> Tâches
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="facturation-tab" data-toggle="tab" href="#facturation" role="tab" aria-controls="facturation" aria-selected="false">
                                                <i class="fas fa-file-invoice-dollar"></i> Factures
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="timesheet-tab" data-toggle="tab" href="#timesheet" role="tab" aria-controls="timesheet" aria-selected="false">
                                                <i class="fas fa-clock"></i> Timesheet
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="email-tab" data-toggle="tab" href="#email" role="tab" aria-controls="email" aria-selected="false">
                                                <i class="fas fa-envelope"></i> Emails Attachés
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">
                                                <i class="fas fa-folder"></i> Répertoire du dossier
                                            </a>
                                        </li>
                                        <li class="nav-item d-none">
                                           <a class="nav-link" href="openfolder://C:/Users/Wahid Fkiri/Desktop">
                                             <i class="fas fa-clock"></i> Ouvrir le chemin du dossier
                                            </a>
                                        </li>
                                    </ul>
                                    
                                    <div class="tab-content" id="dossierTabsContent">
                                        <!-- Onglet Générale -->
                                       <div class="tab-pane fade show active" id="generale" role="tabpanel" aria-labelledby="generale-tab">
    <div class="p-3">
        <!-- Informations de base -->
        <div class="row">
            <div class="col-md-6">
                <h5 class="section-title">Informations de base</h5>
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 29%;">Numéro du dossier</th>
                        <td>{{ $dossier->numero_dossier }}</td>
                    </tr>
                    <tr>
                        <th>Date d'entrée</th>
                        <td>{{ $dossier->date_entree->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>
            
            <div class="col-md-6">
                <h5 class="section-title">Nom du dossier</h5>
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 29%;">Nom complet</th>
                        <td>{{ $dossier->nom_dossier }}</td>
                    </tr>
                    <tr>
                        <th style="width: 29%;">Objet du dossier</th>
                        <td>{{ $dossier->objet ?? 'Non défini' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Domaines -->
        <div class="row mt-4">
            <div class="col-md-6">
                <h5 class="section-title">Classification</h5>
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 29%;">Domaine</th>
                        <td>{{ $dossier->domaine->nom ?? 'Non défini' }}</td>
                    </tr>
                    <tr>
                        <th style="width: 29%;">Sous-domaine</th>
                        <td>{{ $dossier->sousDomaine->nom ?? 'Non défini' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Type de dossier -->
            <div class="col-md-6">
                <h5 class="section-title">Type de dossier</h5>
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 29%;">Dossier de conseil</th>
                        <td>
                            @if($dossier->conseil)
                                <span class="badge badge-success">Oui</span>
                            @else
                                <span class="badge badge-secondary">Non</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 29%;">Dossier contentieux</th>
                        <td>
                            @if($dossier->contentieux)
                                <span class="badge badge-success">Oui</span>
                            @else
                                <span class="badge badge-secondary">Non</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Statut -->
        <div class="row mt-4">
            <div class="col-md-6">
                <h5 class="section-title">Statut</h5>
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 29%;">Archivé</th>
                        <td>
                            @if($dossier->archive)
                                <span class="badge badge-warning">Archivé</span>
                            @else
                                <span class="badge badge-info">Actif</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5 class="section-title">ID Dossier</h5>
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 29%;">ID </th>
                        <td>
                            {{$dossier->id}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    @if($dossier->contentieux)
     <div class="p-3">
        <!-- Informations sur la procédure -->
        <div class="row">
            <div class="col-md-6">
                <h5 class="section-title">Informations sur la procédure</h5>
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 40%;">Numéro de rôle</th>
                        <td>{{ $dossier->numero_role ?? 'Non défini' }}</td>
                    </tr>
                    <tr>
                        <th>Chambre</th>
                        <td>{{ $dossier->chambre ? ucfirst($dossier->chambre) : 'Non définie' }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <h5 class="section-title">Détails de la procédure</h5>
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 40%;">Numéro de chambre</th>
                        <td>{{ $dossier->numero_chambre ?? 'Non défini' }}</td>
                    </tr>
                    <tr>
                        <th>Numéro de parquet</th>
                        <td>{{ $dossier->numero_parquet ?? 'Non défini' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <h5 class="section-title">Informations complémentaires</h5>
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 40%;">Numéro d'instruction</th>
                        <td>{{ $dossier->numero_instruction ?? 'Non défini' }}</td>
                    </tr>
                    <tr>
                        <th>Numéro de plainte</th>
                        <td>{{ $dossier->numero_plainte ?? 'Non défini' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>


                                        <x-dossier.intervenant.tab-list :dossier="$dossier" :intervenants="$intervenants"/>

                                        <!-- Onglet Équipe -->
                                        @include('dossier_component::users')
                                        <!-- Onglet Dossiers -->
                                        <x-dossier.tab-list :dossier="$dossier" :dossiers="$dossiers"/>
                                        <!-- Onglet Notes -->
                                        @include('dossier_component::notes')
                                        <x-dossier.agenda.list :dossier="$dossier" :users="$users" :intervenants="$intervenants" :categories="$categories" :types="$types" />
                                        <x-dossier.task.liste :dossier="$dossier" />
                                        <x-dossier.facturation.list :dossier="$dossier" />
                                      
                                         @include('timesheet_folder::index')
                                        <x-dossier.email.liste :dossier="$dossier" />
                                        <x-dossier.folder.liste :dossier="$dossier" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <a href="{{ url()->previous() }}" class="btn btn-default btn-lg">
                                <i class="fas fa-arrow-left"></i> Retour à la liste
                            </a>

                            @if(auth()->user()->hasPermission('edit_dossiers'))
                                <a href="{{ route('dossiers.edit', $dossier) }}" class="btn btn-warning btn-lg">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                            @endif

                            @if(auth()->user()->hasPermission('delete_dossiers'))
                                <button type="button" class="btn btn-danger btn-lg float-right" 
                                        onclick="confirmDelete({{ $dossier->id }}, '{{ addslashes($dossier->nom_dossier) }}')">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            @endif
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
@can('delete_dossiers')
    <form id="delete-form-{{ $dossier->id }}" 
          action="{{ route('dossiers.destroy', $dossier) }}" 
          method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endcan

@include('dossiers.timesheets.create')
@include('dossiers.factures.create')
@include('dossiers.tasks.create')
<script>
// Fonction de confirmation de suppression
function confirmDelete(dossierId, dossierTitle = 'ce dossier') {
    if (confirm('Êtes-vous sûr de vouloir supprimer le dossier "' + dossierTitle + '" ? Cette action est irréversible.')) {
        // Afficher un indicateur de chargement
        const deleteButton = document.querySelector('.btn-danger');
        const originalText = deleteButton.innerHTML;
        deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression...';
        deleteButton.disabled = true;

        // Soumettre le formulaire de suppression
        document.getElementById('delete-form-' + dossierId).submit();
    }
}

$(document).ready(function() {
    // Ajouter un style pour les badges
    $('.badge').css({
        'font-size': '0.9em',
        'padding': '0.4em 0.8em'
    });
});
</script>
@endsection