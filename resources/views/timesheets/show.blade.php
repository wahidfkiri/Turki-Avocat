@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Détails de la Feuille de Temps</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('time-sheets.index') }}">Feuilles de Temps</a></li>
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
                            <h3 class="card-title">Informations de la feuille de temps</h3>
                            <div class="card-tools">
                                @if(auth()->user()->hasPermission('edit_timesheets'))
                                    <a href="{{ route('time-sheets.edit', $time_sheet->id ?? '') }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                @endif
                                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Retour
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Informations principales -->
                                <div class="col-md-6">
                                    <h5 class="section-title">Informations principales</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%;">Date</th>
                                            <td>{{ $time_sheet->date_timesheet->format('d/m/Y') ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Utilisateur</th>
                                            <td>
                                                <strong>{{ $time_sheet->user->name ?? 'N/A' }}</strong>
                                                @if(($time_sheet->user->fonction ?? null))
                                                    <br><small class="text-muted">{{ $time_sheet->user->fonction }}</small>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Dossier</th>
                                            <td>
                                                @if($time_sheet->dossier ?? null)
                                                    <a href="{{ route('dossiers.show', $time_sheet->dossier->id ?? '') }}" class="text-primary"> <i class="fa fa-eye"></i> 
                                                    {{ $time_sheet->dossier->numero_dossier ?? 'N/A' }}
                                                </a>
                                                    @if($time_sheet->dossier->nom_dossier ?? null)
                                                        <br><small class="text-muted">{{ $time_sheet->dossier->nom_dossier }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Non assigné</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Détails financiers -->
                                <div class="col-md-6">
                                    <h5 class="section-title">Détails financiers</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%;">Quantité</th>
                                            <td>{{ number_format($time_sheet->quantite ?? 0, 2, ',', ' ') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Prix unitaire</th>
                                            <td>{{ number_format($time_sheet->prix ?? 0, 2, ',', ' ') }} DT</td>
                                        </tr>
                                        <tr>
                                            <th>Total</th>
                                            <td class="font-weight-bold text-success" style="font-size: 1.2em;">
                                                {{ number_format($time_sheet->total ?? 0, 2, ',', ' ') }} DT
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <!-- Catégorie et Type -->
                                <div class="col-md-6">
                                    <h5 class="section-title">Classification</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%;">Catégorie</th>
                                            <td>
                                                @if($time_sheet->categorieRelation ?? null)
                                                    {{ $time_sheet->categorieRelation->nom ?? 'N/A' }}
                                                @else
                                                    <span class="text-muted">Non spécifiée</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Type</th>
                                            <td>
                                                @if($time_sheet->typeRelation ?? null)
                                                    {{ $time_sheet->typeRelation->nom ?? 'N/A' }}
                                                @else
                                                    <span class="text-muted">Non spécifié</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Métadonnées -->
                                <div class="col-md-6">
                                    <h5 class="section-title">Métadonnées</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%;">Créé le</th>
                                            <td>{{ ($time_sheet->created_at ?? now())->format('d/m/Y à H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Modifié le</th>
                                            <td>{{ ($time_sheet->updated_at ?? now())->format('d/m/Y à H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Statut</th>
                                            <td>
                                                <span class="badge badge-success">Actif</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="section-title">Description de l'activité</h5>
                                    <div class="card">
                                        <div class="card-body bg-light">
                                            <p class="mb-0" style="white-space: pre-wrap;">{{ $time_sheet->description ?? 'Aucune description fournie' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ url()->previous() }}" class="btn btn-default">
                                        <i class="fas fa-arrow-left"></i> Retour à la liste
                                    </a>
                                </div>
                                <div class="col-md-6 text-right">
                                    @if(auth()->user()->hasPermission('edit_timesheets'))
                                        <a href="{{ route('time-sheets.edit', $time_sheet->id ?? '') }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                    @endif

                                    @if(auth()->user()->hasPermission('delete_timesheets') && ($time_sheet->id ?? null))
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $time_sheet->id }})">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    @endif
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

<!-- Formulaire de suppression -->
@if(auth()->user()->hasPermission('delete_timesheets') && ($time_sheet->id ?? null))
    <form id="delete-form-{{ $time_sheet->id }}" 
          action="{{ route('time-sheets.destroy', $time_sheet->id) }}" 
          method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endif
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
    // Fonction de confirmation de suppression
    function confirmDelete(timesheetId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette feuille de temps ? Cette action est irréversible.')) {
            document.getElementById('delete-form-' + timesheetId).submit();
        }
    }

    $(document).ready(function() {
        // Ajouter un effet de surbrillance sur les cartes au survol
        $('.card').hover(
            function() {
                $(this).addClass('shadow-sm');
            },
            function() {
                $(this).removeClass('shadow-sm');
            }
        );
    });
</script>
<style>
    .section-title {
        color: #495057;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .card {
        border: 1px solid #dee2e6;
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    .badge {
        font-size: 0.85em;
    }
    
    .btn {
        margin: 0 2px;
    }
</style>
@endsection