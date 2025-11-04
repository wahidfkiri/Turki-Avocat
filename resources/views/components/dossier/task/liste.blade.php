

                                        <!-- Onglet Tâches -->
                                        <div class="tab-pane fade" id="tasks" role="tabpanel" aria-labelledby="tasks-tab">
                                            <div class="p-3">
                                                <div style="display: flow-root;">
                                                <h5 class="text-primary mb-3"><i class="fas fa-money-bill-wave"></i> Informations des taches</h5>
                                                <a href="{{ route('dossiers.tasks.create', ['dossier' => $dossier->id]) }}" class="btn btn-primary mb-3" style="float: right;">
                                                    <i class="fas fa-plus"></i> Ajouter une tâche </a>
                                                </div>

                                                 @if($dossier->tasks && $dossier->tasks->count() > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Titre</th>
                                                                    <!-- <th>Description</th> -->
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
                                                                    <td>
                                                                        {{ $task->titre }}
                                                                    </td>
                                                                    <!-- <td>{{ $task->description }}</td> -->
                                                                    <td>{{ $task->date_debut?->format('d/m/Y') ?? ''}}</td>
                                                                    <td>{{ $task->date_fin?->format('d/m/Y') ?? '' }}</td>
                                                                    <td>{{ $task->priorite ?? ''}}</td>
                                                                    <td>{{ $task->user->name ?? ''}}</td>
                                                                    <td>
                                                                        @if($task->statut == 'en_cours')
                                                                            <span class="badge badge-warning">En cours</span>
                                                                        @elseif($task->statut == 'termine')
                                                                            <span class="badge badge-success">Terminé</span>
                                                                        @elseif($task->statut == 'a_faire')
                                                                            <span class="badge badge-secondary">A faire</span>
                                                                        @else
                                                                            <span class="badge badge-info">En retard</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <a href="{{route('tasks.show', $task)}}" class="btn btn-sm btn-info" title="Voir">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                        @if($task->file_path)
                                                                        <a href="{{url('tasks/download')}}/{{$task->id}}" 
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