<!-- Onglet Tâches -->
<div class="tab-pane fade" id="tasks" role="tabpanel" aria-labelledby="tasks-tab">
    <div class="p-3">
        <div style="display: flow-root;">
            <h5 class="text-primary mb-3"><i class="fas fa-tasks"></i> Gestion des tâches</h5>
            <a href="#" data-toggle="modal" data-target="#taskModal" class="btn btn-primary mb-3" style="float: right;">
                <i class="fas fa-plus"></i> Ajouter une tâche 
            </a>
        </div>

        <!-- Filtres de recherche -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="fas fa-filter"></i> Filtres de recherche</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filterStatut">Statut</label>
                            <select class="form-control" id="filterStatut">
                                <option value="">Tous les statuts</option>
                                <option value="a_faire">À faire</option>
                                <option value="en_cours">En cours</option>
                                <option value="terminee">Terminée</option>
                                <option value="en_retard">En retard</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filterPriorite">Priorité</label>
                            <select class="form-control" id="filterPriorite">
                                <option value="">Toutes les priorités</option>
                                <option value="basse">Basse</option>
                                <option value="normale">Normale</option>
                                <option value="haute">Haute</option>
                                <option value="urgente">Urgente</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filterUser">Assigné à</label>
                            <select class="form-control" id="filterUser">
                                <option value="">Tous les utilisateurs</option>
                                @foreach($dossier->tasks->pluck('user')->unique()->filter() as $user)
                                    @if($user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filterDateDebut">Date de début</label>
                            <input type="date" class="form-control" id="filterDateDebut">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="button" id="resetFilters" class="btn btn-secondary btn-sm">
                            <i class="fas fa-redo"></i> Réinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table DataTable -->
        <div class="table-responsive">
            <table id="tasksTable" class="table table-bordered table-hover" style="width:100%">
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
                    <!-- Les données seront chargées via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Inclure le modal de visualisation -->
@include('components.dossier.task.modal-view')
<script>
$(document).ready(function() {
    // Initialiser la DataTable
    var tasksTable = $('#tasksTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("dossier.tasks.data", $dossier->id) }}',
            type: 'GET',
            data: function(d) {
                // Ajouter les filtres personnalisés
                d.statut = $('#filterStatut').val();
                d.priorite = $('#filterPriorite').val();
                d.user_id = $('#filterUser').val();
                d.date_debut = $('#filterDateDebut').val();
            }
        },
        columns: [
    { 
        data: 'titre',
        name: 'titre',
        render: function(data, type, row) {
            if (type === 'display' && data.length > 30) {
                return data.substr(0, 30) + '...';
            }
            return data;
        }
    },
    { 
        data: 'date_debut',
        name: 'date_debut',
        render: function(data) {
            return data || '';
        }
    },
    { 
        data: 'date_fin',
        name: 'date_fin',
        render: function(data) {
            return data || '';
        }
    },
    { 
        data: 'priorite',
        name: 'priorite',
        render: function(data, type, row) {
            if (type === 'display') {
                return data; // Déjà formaté côté serveur
            }
            return data;
        }
    },
    { 
        data: 'user_name',
        name: 'user.name',
        defaultContent: ''
    },
    { 
        data: 'statut',
        name: 'statut',
        render: function(data, type, row) {
            if (type === 'display') {
                return data; // Déjà formaté côté serveur
            }
            return data;
        }
    },
    { 
        data: 'id',
        name: 'actions',
        orderable: false,
        searchable: false,
        render: function(data, type, row) {
            let actions = '';
            
            // Bouton Voir
            actions += '<button type="button" class="btn btn-sm btn-info view-task-btn mr-1" data-task-id="' + data + '" title="Voir">';
            actions += '<i class="fas fa-eye"></i>';
            actions += '</button>';
            
            // Bouton Télécharger
            if (row.file_path) {
                actions += '<a href="/tasks/download/' + data + '" class="btn btn-sm btn-success mr-1" title="Télécharger" download>';
                actions += '<i class="fas fa-download"></i>';
                actions += '</a>';
            }
            
            return '<div class="btn-group btn-group-sm">' + actions + '</div>';
        }
    }
],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tous"]],
        order: [[1, 'desc']], // Tri par date de début décroissante par défaut
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        initComplete: function() {
            // Ajouter une classe à la recherche
            $('.dataTables_filter input').addClass('form-control-sm');
            $('.dataTables_length select').addClass('form-control-sm');
        }
    });

    // Appliquer les filtres
    $('#filterStatut, #filterPriorite, #filterUser, #filterDateDebut').on('change', function() {
        tasksTable.ajax.reload();
    });

    // Réinitialiser les filtres
    $('#resetFilters').click(function() {
        $('#filterStatut').val('');
        $('#filterPriorite').val('');
        $('#filterUser').val('');
        $('#filterDateDebut').val('');
        tasksTable.ajax.reload();
    });

    // Recherche personnalisée
    $(document).on('keyup', '#tasksTable_filter input', function() {
        tasksTable.search(this.value).draw();
    });

    // Gestion du bouton Voir
    $(document).on('click', '.view-task-btn', function() {
        const taskId = $(this).data('task-id');
        // Ouvrir le modal de visualisation
        $('#taskViewModal').modal('show');
        // Charger les données via AJAX
        loadTaskDetails(taskId);
    });

    // Gestion du bouton Modifier
    $(document).on('click', '.edit-task-btn', function() {
        const taskId = $(this).data('task-id');
        // Charger le formulaire d'édition
        loadEditTaskForm(taskId);
    });

    // Gestion du bouton Supprimer
    $(document).on('click', '.delete-task-btn', function() {
        const taskId = $(this).data('task-id');
        const taskTitle = $(this).data('task-title');
        
        if (confirm('Êtes-vous sûr de vouloir supprimer la tâche "' + taskTitle + '" ?')) {
            deleteTask(taskId);
        }
    });

    // Fonction pour charger les détails d'une tâche
    function loadTaskDetails(taskId) {
        $.ajax({
            url: '/tasks/' + taskId,
            type: 'GET',
            success: function(response) {
                // Afficher les détails dans le modal
                $('#taskViewModal .modal-body').html(response);
            },
            error: function() {
                $('#taskViewModal .modal-body').html(
                    '<div class="alert alert-danger">Erreur lors du chargement des détails</div>'
                );
            }
        });
    }

    // Fonction pour charger le formulaire d'édition
    function loadEditTaskForm(taskId) {
        // Ouvrir un modal d'édition
        $('#editTaskModal').modal('show');
        
        $.ajax({
            url: '/tasks/' + taskId + '/edit',
            type: 'GET',
            success: function(response) {
                $('#editTaskModal .modal-body').html(response);
            },
            error: function() {
                $('#editTaskModal .modal-body').html(
                    '<div class="alert alert-danger">Erreur lors du chargement du formulaire</div>'
                );
            }
        });
    }

    // Fonction pour supprimer une tâche
    function deleteTask(taskId) {
        $.ajax({
            url: '/tasks/' + taskId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE'
            },
            success: function(response) {
                if (response.success) {
                    // Recharger la DataTable
                    tasksTable.ajax.reload(null, false);
                    
                    // Afficher un message de succès
                    showAlert('success', response.message || 'Tâche supprimée avec succès');
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la suppression');
                }
            },
            error: function(xhr) {
                showAlert('danger', 'Erreur lors de la suppression de la tâche');
            }
        });
    }

    // Fonction pour afficher les alertes
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert" 
                 style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;
        
        $('body').append(alertHtml);
        
        setTimeout(() => {
            $('.alert').alert('close');
        }, 3000);
    }
});
</script>