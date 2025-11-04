@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Détails de la Tâche</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Tâches</a></li>
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
                            <h3 class="card-title">Informations de la tâche</h3>
                            <div class="card-tools">
                                @if(auth()->user()->hasPermission('edit_tasks'))
                                    <a href="{{ route('tasks.edit', $task->id ?? '') }}" class="btn btn-warning btn-sm">
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
                                            <th style="width: 40%;">Titre</th>
                                            <td class="font-weight-bold">{{ $task->titre ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Priorité</th>
                                            <td>
                                                @php
                                                    $priorityColors = [
                                                        'basse' => 'success',
                                                        'normale' => 'info',
                                                        'haute' => 'warning',
                                                        'urgente' => 'danger'
                                                    ];
                                                    $priorityClass = $priorityColors[$task->priorite ?? ''] ?? 'secondary';
                                                @endphp
                                                <span class="badge badge-{{ $priorityClass }} text-uppercase">
                                                    {{ $task->priorite ?? 'Non spécifiée' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Statut</th>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'a_faire' => 'secondary',
                                                        'en_cours' => 'primary',
                                                        'terminee' => 'success',
                                                        'en_retard' => 'danger'
                                                    ];
                                                    $statusClass = $statusColors[$task->statut ?? ''] ?? 'secondary';
                                                @endphp
                                                <span class="badge badge-{{ $statusClass }} text-uppercase">
                                                    {{ isset($task->statut) ? str_replace('_', ' ', $task->statut) : 'Non spécifié' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Pièce Jointe</td>
                                            <td>
                                                @if($task->file_path != null)
                                                <div class="btn-group btn-group-sm d-block">
                                                                                <a href="{{url('tasks/display')}}/{{ $task->id ?? '#' }}" target="_blank" title="Voir">
                                                                                    <i class="fas fa-eye text-success"></i> Afficher
                                                                                </a><br>
                                                                                <a href="{{url('tasks/download')}}/{{ $task->id ?? '#' }}" download title="Télécharger">
                                                                                    <i class="fas fa-download text-info"></i>  Télécharger
                                                                                </a>
                                                                            </div>
                                                                            @else
                                                                            Non définit
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Détails temporels -->
                                <div class="col-md-6">
                                    <h5 class="section-title">Détails temporels</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%;">Date de début</th>
                                            <td>{{ $task->date_debut ? $task->date_debut->format('d/m/Y') : 'Non définie' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date de fin</th>
                                            <td>{{ $task->date_fin ? $task->date_fin->format('d/m/Y') : 'Non définie' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Assigné à</th>
                                            <td>
                                                {{ $task->user->name ?? 'Non assigné' }}
                                                @if(($task->user->fonction ?? null))
                                                    <br><small class="text-muted">{{ $task->user->fonction }}</small>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <!-- Relations -->
                                <div class="col-md-6">
                                    <h5 class="section-title">Relations</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%;">Dossier</th>
                                            <td>
                                                @if($task->dossier ?? null)
                                                    <a href="{{ route('dossiers.show', $task->dossier->id ?? '') }}" class="text-primary">
                                                        <i class="fa fa-eye"></i> {{ $task->dossier->numero_dossier ?? 'N/A' }}
                                                    </a>
                                                    @if($task->dossier->nom_dossier ?? null)
                                                        <br><small class="text-muted">{{ $task->dossier->nom_dossier }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Non assigné</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Intervenant</th>
                                            <td>
                                                @if($task->intervenant ?? null)
                                                    {{ $task->intervenant->identite_fr ?? $task->intervenant->identite_ar ?? 'N/A' }}
                                                @if($task->intervenant->email ?? null)
                                                    <br><small class="text-muted">{{ $task->intervenant->email }}</small>
                                                @endif
                                                @else
                                                    <span class="text-muted">Non assigné</span>
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
                                            <td>{{ ($task->created_at ?? now())->format('d/m/Y à H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Modifié le</th>
                                            <td>{{ ($task->updated_at ?? now())->format('d/m/Y à H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Statut système</th>
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
                                    <h5 class="section-title">Description</h5>
                                    <div class="card">
                                        <div class="card-body bg-light">
                                            <p class="mb-0" style="white-space: pre-wrap;">{{ $task->description ?? 'Aucune description fournie' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes supplémentaires -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="section-title">Notes supplémentaires</h5>
                                    <div class="card">
                                        <div class="card-body bg-light">
                                            <p class="mb-0" style="white-space: pre-wrap;">{{ $task->note ?? 'Aucune note supplémentaire' }}</p>
                                            <small class="text-muted mt-2 d-block">
                                                <i class="fas fa-info-circle"></i> Ces notes sont internes et ne sont pas visibles par le client.
                                            </small>
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
                                    @if(auth()->user()->hasPermission('edit_tasks'))
                                        <a href="{{ route('tasks.edit', $task->id ?? '') }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                    @endif

                                    @if(auth()->user()->hasPermission('delete_tasks') && ($task->id ?? null))
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $task->id }})">
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
@if(auth()->user()->hasPermission('delete_tasks') && ($task->id ?? null))
    <form id="delete-form-{{ $task->id }}" 
          action="{{ route('tasks.destroy', $task->id) }}" 
          method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endif

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
    // Fonction de confirmation de suppression
    function confirmDelete(taskId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette tâche ? Cette action est irréversible.')) {
            document.getElementById('delete-form-' + taskId).submit();
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
        border-bottom: 2px solid #dee2e6;
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
    
    .table-bordered {
        border: 1px solid #dee2e6;
    }
    
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
        padding: 0.75rem;
        vertical-align: top;
    }
</style>
@endsection