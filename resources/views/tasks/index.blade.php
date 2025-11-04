@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gestion des Tâches</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Tâches</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    
            
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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Liste des tâches</h3>
                            <div class="card-tools">
                                @if(auth()->user()->hasPermission('create_tasks'))
                                    <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Nouvelle tâche
                                    </a>
                                @endif
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- Filtres -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="filter_titre">Titre</label>
                                    <input type="text" class="form-control" id="filter_titre" placeholder="Rechercher par titre">
                                </div>
                                <div class="col-md-3">
                                    <label for="filter_priorite">Priorité</label>
                                    <select class="form-control" id="filter_priorite">
                                        <option value="">Toutes les priorités</option>
                                        <option value="basse">Basse</option>
                                        <option value="normale">Normale</option>
                                        <option value="haute">Haute</option>
                                        <option value="urgente">Urgente</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="filter_statut">Statut</label>
                                    <select class="form-control" id="filter_statut">
                                        <option value="">Tous les statuts</option>
                                        <option value="a_faire">À faire</option>
                                        <option value="en_cours">En cours</option>
                                        <option value="terminee">Terminée</option>
                                        <option value="en_retard">En retard</option>
                                    </select>
                                </div>
                                @if(auth()->user()->hasRole('admin'))
                                <div class="col-md-3">
                                    <label for="filter_utilisateur">Utilisateur</label>
                                    <select class="form-control" id="filter_utilisateur">
                                        <option value="">Tous les utilisateurs</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="filter_dossier">Dossier</label>
                                    <select class="form-control" id="filter_dossier">
                                        <option value="">Tous les dossiers</option>
                                        @foreach($dossiers as $dossier)
                                            <option value="{{ $dossier->id }}">{{ $dossier->numero_dossier }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- <div class="col-md-3">
                                    <label for="filter_intervenant">Intervenant</label>
                                    <select class="form-control" id="filter_intervenant">
                                        <option value="">Tous les intervenants</option>
                                        @foreach($intervenants as $intervenant)
                                            <option value="{{ $intervenant->id }}">{{ $intervenant->identite_fr }}</option>
                                        @endforeach
                                    </select>
                                </div> -->
                                <div class="col-md-3">
                                    <label for="filter_date_debut">Date début</label>
                                    <input type="date" class="form-control" id="filter_date_debut">
                                </div>
                                <div class="col-md-3">
                                    <label for="filter_date_fin">Date fin</label>
                                    <input type="date" class="form-control" id="filter_date_fin">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12 text-right">
                                    <button type="button" id="btn_reset_filters" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i> Réinitialiser
                                    </button>
                                    <button type="button" id="btn_apply_filters" class="btn btn-primary">
                                        <i class="fas fa-filter"></i> Appliquer
                                    </button>
                                </div>
                            </div>

                            <!-- Table -->
                            <table id="tasks-table" class="table table-bordered table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Titre</th>
                                        <th>Date Début</th>
                                        <th>Date Fin</th>
                                        <th>Priorité</th>
                                        <th>Statut</th>
                                        <th>Numéro Dossier</th>
                                        <th>Nom Dossier</th>
                                        @if(auth()->user()->hasRole('admin'))
                                        <th>Assigné à</th>
                                        @endif
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="deleteTaskModal" tabindex="-1" aria-labelledby="deleteTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTaskModalLabel">Confirmation de suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer la tâche <strong id="task-title"></strong> ?</p>
                <p class="text-danger"><small>Cette action est irréversible.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirm-task-delete">Supprimer</button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {
    let taskToDelete = null;
    let taskRowToDelete = null;

    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // DataTable initialization
    var table = $('#tasks-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("tasks.data") }}',
            data: function (d) {
                d.titre = $('#filter_titre').val();
                d.priorite = $('#filter_priorite').val();
                d.statut = $('#filter_statut').val();
                d.utilisateur_id = $('#filter_utilisateur').val();
                d.dossier_id = $('#filter_dossier').val();
                d.intervenant_id = $('#filter_intervenant').val();
                d.date_debut = $('#filter_date_debut').val();
                d.date_fin = $('#filter_date_fin').val();
            }
        },
        columns: [
            {
                data: null,
                orderable: false,
                searchable: false,
                visible: false,
                render: function(data, type, row, meta) {
                    return meta.settings._iDisplayStart + meta.row + 1;
                }
            },
            { 
                data: 'titre', 
                name: 'titre',
                render: function(data) {
                    return data || '-';
                }
            },
            { 
                data: 'date_debut', 
                name: 'date_debut',
                render: function(data, type, row) {
                    if (type === 'sort' || type === 'type') {
                        return data;
                    }
                    return data || '-';
                }
            },
            { 
                data: 'date_fin', 
                name: 'date_fin',
                render: function(data, type, row) {
                    if (type === 'sort' || type === 'type') {
                        return data;
                    }
                    return data || '-';
                }
            },
            { 
                data: 'priorite', 
                name: 'priorite',
                render: function(data) {
                    var badges = {
                        'basse': 'secondary',
                        'normale': 'info',
                        'haute': 'warning',
                        'urgente': 'danger'
                    };
                    var labels = {
                        'basse': 'Basse',
                        'normale': 'Normale',
                        'haute': 'Haute',
                        'urgente': 'Urgente'
                    };
                    return data ? '<span class="badge badge-' + badges[data] + '">' + labels[data] + '</span>' : '-';
                }
            },
            { 
                data: 'statut', 
                name: 'statut',
                render: function(data) {
                    var badges = {
                        'a_faire': 'secondary',
                        'en_cours': 'primary',
                        'terminee': 'success',
                        'en_retard': 'danger'
                    };
                    var labels = {
                        'a_faire': 'À faire',
                        'en_cours': 'En cours',
                        'terminee': 'Terminée',
                        'en_retard': 'En retard'
                    };
                    return data ? '<span class="badge badge-' + badges[data] + '">' + labels[data] + '</span>' : '-';
                }
            },
            { 
                data: 'dossier', 
                name: 'dossier.numero_dossier',
                render: function(data) {
                    return data ? data.numero_dossier : '-';
                }
            },
            { 
                data: 'dossier', 
                name: 'dossier.nom_dossier',
                render: function(data) {
                    return data ? data.nom_dossier : '-';
                }
            },
            @if(auth()->user()->hasRole('admin'))
            { 
                data: 'user', 
                name: 'user.name',
                render: function(data) {
                    return data ? data.name : '-';
                }
            },
            @endif
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    var actions = '<div class="btn-group">';
                    
                    @if(auth()->user()->hasPermission('view_tasks'))
                        actions += '<a href="/tasks/' + row.id + '" class="btn btn-info btn-sm" title="Voir"><i class="fas fa-eye"></i></a>';
                    @endif
                    
                    @if(auth()->user()->hasPermission('edit_tasks'))
                        actions += '<a href="/tasks/' + row.id + '/edit" class="btn btn-primary btn-sm" title="Modifier"><i class="fas fa-edit"></i></a>';
                    @endif
                    
                    @if(auth()->user()->hasPermission('delete_tasks'))
                        actions += '<button type="button" class="btn btn-danger btn-sm delete-task-btn" data-id="' + row.id + '" data-title="' + (row.titre || '') + '" title="Supprimer"><i class="fas fa-trash"></i></button>';
                    @endif
                    @if(auth()->user()->hasPermission('view_tasks'))
                        if (row.file_path != null) {
                            actions += '<a href="/tasks/' + row.id + '/download" class="btn btn-secondary btn-sm" title="Télécharger"><i class="fas fa-download"></i></a>';
                        }
                    @endif
                    
                    actions += '</div>';
                    return actions;
                }
            }
        ],
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        },
        createdRow: function(row, data, dataIndex) {
            // Add ID to row for easy removal
            $(row).attr('id', 'task-row-' + data.id);
        }
    });

    // Delete button click handler
    $(document).on('click', '.delete-task-btn', function() {
        const taskId = $(this).data('id');
        const taskTitle = $(this).data('title') || 'cette tâche';
        
        taskToDelete = taskId;
        taskRowToDelete = $(this).closest('tr');
        $('#task-title').text(taskTitle);
        $('#deleteTaskModal').modal('show');
    });
    
    // Confirm delete button handler
    $('#confirm-task-delete').on('click', function() {
        if (!taskToDelete) return;
        
        const deleteButton = $(this);
        const originalText = deleteButton.html();
        
        // Show loading state
        deleteButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Suppression...');
        
        $.ajax({
            url: '/tasks/' + taskToDelete,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteTaskModal').modal('hide');
                
                if (response.success) {
                    // Remove the row from the table
                    if (taskRowToDelete) {
                        taskRowToDelete.fadeOut(300, function() {
                            table.row($(this)).remove().draw(false);
                        });
                    } else {
                        // Fallback: reload the table
                        table.ajax.reload();
                    }
                    
                    // Show success message
                    showAlert('success', response.message || 'Tâche supprimée avec succès!');
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la suppression.');
                }
            },
            error: function(xhr) {
                $('#deleteTaskModal').modal('hide');
                
                let errorMessage = 'Une erreur est survenue lors de la suppression de la tâche.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showAlert('danger', errorMessage);
            },
            complete: function() {
                // Reset button state
                deleteButton.prop('disabled', false).html(originalText);
                taskToDelete = null;
                taskRowToDelete = null;
            }
        });
    });

    // Function to show alert messages
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fa-check' : 'fa-ban';
        const title = type === 'success' ? 'Succès!' : 'Erreur!';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas ${iconClass}"></i> ${title}</h5>
                ${message}
            </div>
        `;
        
        // Remove any existing alerts
        $('.alert-dismissible').remove();
        
        // Prepend the new alert
        $('.card').before(alertHtml);
        
        // Auto-remove alert after 5 seconds
        setTimeout(function() {
            $('.alert-dismissible').fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    }

    // Apply filters
    $('#btn_apply_filters').click(function() {
        table.ajax.reload();
    });

    // Reset filters
    $('#btn_reset_filters').click(function() {
        $('#filter_titre').val('');
        $('#filter_priorite').val('').trigger('change');
        $('#filter_statut').val('').trigger('change');
        $('#filter_utilisateur').val('').trigger('change');
        $('#filter_dossier').val('').trigger('change');
        $('#filter_intervenant').val('').trigger('change');
        $('#filter_date_debut').val('');
        $('#filter_date_fin').val('');
        table.ajax.reload();
    });

    // Apply filters on Enter key in text inputs
    $('#filter_titre').keypress(function(e) {
        if (e.which == 13) {
            table.ajax.reload();
        }
    });

    // Close modal when clicking the X button
    $('#deleteTaskModal .close, #deleteTaskModal [data-dismiss="modal"]').on('click', function() {
        taskToDelete = null;
        taskRowToDelete = null;
    });
    
    // Handle escape key to close modal
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#deleteTaskModal').is(':visible')) {
            taskToDelete = null;
            taskRowToDelete = null;
        }
    });
});
</script>
<style>
    .dataTables_wrapper .dataTables_filter {
        float: right;
    }
    .dataTables_wrapper .dataTables_length {
        float: left;
    }
    .table th {
        background-color: #f8f9fa;
    }
    .btn-group .btn {
        margin-right: 2px;
    }
    .badge {
        font-size: 0.8em;
    }
    .delete-task-btn {
        transition: all 0.3s ease;
    }
    .delete-task-btn:hover {
        transform: scale(1.05);
    }
</style>
@endsection