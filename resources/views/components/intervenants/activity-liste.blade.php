<div class="tab-pane fade" id="activites" role="tabpanel" aria-labelledby="activites-tab">
    <div class="p-3">
        <!-- Agenda -->
        <h5 class="text-primary mb-3">
            <i class="fas fa-calendar-check"></i> Événements Agenda
        </h5>
        @if(($intervenant->agendas ?? null) && count($intervenant->agendas) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped w-100" id="agenda-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Date Début</th>
                            <th>Date Fin</th>
                            <th>Catégorie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($intervenant->agendas as $agenda)
                        <tr>
                            <td>{{ $agenda->titre ?? 'N/A' }}</td>
                            <td>{{ Str::limit($agenda->description ?? '', 50) }}</td>
                            <td>{{ isset($agenda->date_debut) ? \Carbon\Carbon::parse($agenda->date_debut)->format('d/m/Y') : 'N/A' }}</td>
                            <td>{{ isset($agenda->date_fin) ? \Carbon\Carbon::parse($agenda->date_fin)->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                <span class="badge badge-info">{{ ucfirst($agenda->categorie ?? 'N/A') }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>
                Aucun événement agenda associé à cet intervenant.
            </div>
        @endif

        <!-- Tâches -->
        <h5 class="text-primary mb-3 mt-4">
            <i class="fas fa-tasks"></i> Tâches
        </h5>
        @if(($intervenant->tasks ?? null) && count($intervenant->tasks) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped w-100" id="tasks-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Date Début</th>
                            <th>Date Fin</th>
                            <th>Priorité</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($intervenant->tasks as $task)
                        <tr>
                            <td>{{ $task->titre ?? 'N/A' }}</td>
                            <td>{{ Str::limit($task->description ?? '', 50) }}</td>
                            <td>{{ isset($task->date_debut) ? \Carbon\Carbon::parse($task->date_debut)->format('d/m/Y') : 'N/A' }}</td>
                            <td>{{ isset($task->date_fin) ? \Carbon\Carbon::parse($task->date_fin)->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                @php
                                    $priorityBadge = [
                                        'basse' => 'badge-secondary',
                                        'moyenne' => 'badge-info',
                                        'haute' => 'badge-warning',
                                        'urgente' => 'badge-danger'
                                    ][$task->priorite ?? ''] ?? 'badge-secondary';
                                @endphp
                                <span class="badge {{ $priorityBadge }}">{{ ucfirst($task->priorite ?? 'N/A') }}</span>
                            </td>
                            <td>
                                @php
                                    $statusBadge = [
                                        'en_attente' => 'badge-secondary',
                                        'en_cours' => 'badge-primary',
                                        'termine' => 'badge-success',
                                        'annule' => 'badge-danger'
                                    ][$task->statut ?? ''] ?? 'badge-secondary';
                                @endphp
                                <span class="badge {{ $statusBadge }}">{{ isset($task->statut) ? ucfirst(str_replace('_', ' ', $task->statut)) : 'N/A' }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>
                Aucune tâche associée à cet intervenant.
            </div>
        @endif
    </div>
</div>

<!-- DataTables CSS (if not already included) -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<!-- Script DataTables pour Activités -->
<script>
    $(document).ready(function() {
        // Initialisation de DataTables pour l'agenda
        $('#agenda-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json"
            },
            "responsive": true,
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
            "pageLength": 10,
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Tous"]],
            "order": [[2, 'asc']], // Tri par Date Début par défaut
            "columnDefs": [
                {
                    "targets": [0, 1, 2, 3, 4], // Toutes les colonnes
                    "searchable": true,
                    "orderable": true
                }
            ],
            "drawCallback": function(settings) {
                // Ajouter un effet de survol personnalisé
                $('#agenda-table tr').hover(
                    function() {
                        $(this).addClass('hover-row');
                    },
                    function() {
                        $(this).removeClass('hover-row');
                    }
                );
            }
        });

        // Initialisation de DataTables pour les tâches
        $('#tasks-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json"
            },
            "responsive": true,
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
            "pageLength": 10,
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Tous"]],
            "order": [[2, 'asc']], // Tri par Date Début par défaut
            "columnDefs": [
                {
                    "targets": [0, 1, 2, 3, 4, 5], // Toutes les colonnes
                    "searchable": true,
                    "orderable": true
                }
            ],
            "drawCallback": function(settings) {
                // Ajouter un effet de survol personnalisé
                $('#tasks-table tr').hover(
                    function() {
                        $(this).addClass('hover-row');
                    },
                    function() {
                        $(this).removeClass('hover-row');
                    }
                );
            }
        });
    });
</script>

<!-- Styles supplémentaires pour les activités -->
<style>
    #agenda-table thead th,
    #tasks-table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    
    .hover-row {
        background-color: #f8f9fa !important;
        transition: background-color 0.3s ease;
    }
    
    /* Styles pour DataTables */
    #agenda-table_wrapper .dataTables_filter input,
    #tasks-table_wrapper .dataTables_filter input {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        margin-left: 0.5rem;
    }
    
    #agenda-table_wrapper .dataTables_length select,
    #tasks-table_wrapper .dataTables_length select {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
    }
    
    #agenda-table_wrapper .dataTables_paginate .paginate_button,
    #tasks-table_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.375rem;
        margin: 0 2px;
        border: 1px solid #dee2e6;
    }
    
    #agenda-table_wrapper .dataTables_paginate .paginate_button.current,
    #tasks-table_wrapper .dataTables_paginate .paginate_button.current {
        background: #007bff;
        color: white !important;
        border: 1px solid #007bff;
    }
    
    #agenda-table_wrapper .dataTables_paginate .paginate_button:hover,
    #tasks-table_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e9ecef;
        border: 1px solid #dee2e6;
    }
    
    #agenda-table_wrapper .dataTables_info,
    #tasks-table_wrapper .dataTables_info {
        padding-top: 0.85em;
    }
</style>