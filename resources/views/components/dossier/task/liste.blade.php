<!-- Onglet Tâches -->
<div class="tab-pane fade" id="tasks" role="tabpanel" aria-labelledby="tasks-tab">
    <div class="p-3">
        <div style="display: flow-root;">
            <h5 class="text-primary mb-3"><i class="fas fa-money-bill-wave"></i> Informations des taches</h5>
            <a href="#" data-toggle="modal" data-target="#taskModal" class="btn btn-primary mb-3" style="float: right;">
                <i class="fas fa-plus"></i> Ajouter une tâche 
            </a>
        </div>

        @if($dossier->tasks && $dossier->tasks->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Date de début</th>
                            <th>Date de fin</th>
                            <th>Priorité</th>
                            <th>Assigné à</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dossier->tasks as $task)
                        <tr>
                            <td>{{ $task->titre }}</td>
                            <td>{{ $task->date_debut?->format('d/m/Y') ?? ''}}</td>
                            <td>{{ $task->date_fin?->format('d/m/Y') ?? '' }}</td>
                            <td>
                                @if($task->priorite == 'basse')
                                    <span class="badge badge-secondary">Basse</span>
                                @elseif($task->priorite == 'normale')
                                    <span class="badge badge-info">Normale</span>
                                @elseif($task->priorite == 'haute')
                                    <span class="badge badge-warning">Haute</span>
                                @elseif($task->priorite == 'urgente')
                                    <span class="badge badge-danger">Urgente</span>
                                @else
                                    <span class="badge badge-light">Non définie</span>
                                @endif
                            </td>
                            <td>{{ $task->user->name ?? ''}}</td>
                            <td>
                                @if($task->statut == 'en_cours')
                                    <span class="badge badge-warning">En cours</span>
                                @elseif($task->statut == 'terminee')
                                    <span class="badge badge-success">Terminée</span>
                                @elseif($task->statut == 'a_faire')
                                    <span class="badge badge-secondary">À faire</span>
                                @elseif($task->statut == 'en_retard')
                                    <span class="badge badge-danger">En retard</span>
                                @else
                                    <span class="badge badge-info">{{ $task->statut }}</span>
                                @endif
                            </td>
                            <td>
                                <!-- Bouton pour ouvrir le modal de visualisation -->
                                <button type="button" class="btn btn-sm btn-info view-task-btn" 
                                        data-task-id="{{ $task->id }}" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                @if($task->file_path)
                                <a href="{{ url('tasks/download') }}/{{ $task->id }}" 
                                   download class="btn btn-sm btn-success" title="Télécharger">
                                    <i class="fas fa-download"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info" style="color:black;">
                <h6><i class="icon fas fa-info"></i> Information</h6>
                <p class="mb-0">
                    Aucune tâche n'a été ajoutée à ce dossier.
                </p>
            </div>
        @endif
    </div>
</div>

<!-- Inclure le modal de visualisation -->
@include('components.dossier.task.modal-view')