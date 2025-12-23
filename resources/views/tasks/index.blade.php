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
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createTaskModal">
                                        <i class="fas fa-plus"></i> Nouvelle tâche
                                    </button>
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
                                        <option value="all">Tous les statuts</option>
                                        <option value="a_faire">À faire</option>
                                        <option value="en_cours">En cours</option>
                                        <option value="terminee">Terminée</option>
                                        <option value="en_retard">En retard</option>
                                    </select>
                                </div>
                                @if(auth()->user()->hasRole('admin'))
                                <div class="col-md-3">
                                    <label for="filter_utilisateur">Utilisateur</label>
                                    <select class="form-control search_utilisateur" id="filter_utilisateur">
                                        <option value="">Tous les utilisateurs</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}"  @if(auth()->user()->id == $user->id) selected @endif>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="filter_dossier">Dossier</label>
                                    <select class="form-control search_dossier" id="filter_dossier">
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
            <div class="modal-header bg-danger">
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
<!-- Modal pour afficher les détails de la tâche -->
<div class="modal fade" id="taskDetailsModal" tabindex="-1" role="dialog" aria-labelledby="taskDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="taskDetailsModalLabel">
                    <i class="fas fa-tasks"></i> Détails de la tâche
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="taskDetailsModalBody">
                <!-- Le contenu sera chargé dynamiquement ici -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement des détails...</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
@include('tasks.create')
@include('tasks.edit')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.2/jquery.sumoselect.min.js"></script>
<script>
$(document).ready(function() {
    // ============================
    // VARIABLES GLOBALES
    // ============================
    let taskToDelete = null;
    let taskRowToDelete = null;
    let table; // Déclaration de la variable DataTable

    // ============================
    // INITIALISATION GENERALE
    // ============================
    
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // ============================
    // DATATABLE INITIALISATION
    // ============================
    table = $('#tasks-table').DataTable({
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
                        actions += '<button type="button" class="btn btn-info btn-sm view-task-btn" data-id="' + row.id + '" title="Voir"><i class="fas fa-eye"></i></button>';
                    @endif
                    
                    @if(auth()->user()->hasPermission('edit_tasks'))
                        // Bouton pour ouvrir le modal d'édition
                        actions += '<button type="button" class="btn btn-warning btn-sm edit-task-btn" data-id="' + row.id + '" title="Modifier"><i class="fas fa-edit"></i></button>';
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

    // ============================
    // FONCTIONS UTILITAIRES
    // ============================
    
    // Fonction pour afficher les alertes
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

    // Fonction pour afficher les alertes toast
    function showTaskAlert(type, message) {
        // Supprimer les alertes existantes
        $('.alert.task-toast-alert').remove();
        
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
        const title = type === 'success' ? 'Succès' : 'Erreur';
        
        const alertHtml = `
            <div class="alert alert-${type} task-toast-alert alert-dismissible fade show" role="alert" 
                 style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 400px;">
                <div class="d-flex align-items-center">
                    <i class="fas ${icon} mr-2" style="font-size: 1.5rem;"></i>
                    <div style="flex: 1;">
                        <strong>${title}</strong>
                        <div class="small">${message}</div>
                    </div>
                    <button type="button" class="close ml-2" data-dismiss="alert" style="position: static;">
                        <span>&times;</span>
                    </button>
                </div>
            </div>
        `;
        
        // Ajouter la nouvelle alerte
        $('body').append(alertHtml);
        
        // Supprimer automatiquement après 5 secondes
        setTimeout(() => {
            $('.alert.task-toast-alert').alert('close');
        }, 5000);
    }

    function showFieldError(field, message) {
        var input = $('#' + field);
        var errorDiv = $('#' + field + '-error');
        
        input.addClass('is-invalid');
        errorDiv.text(message);
    }

    function clearFieldError(field) {
        var input = $('#' + field);
        var errorDiv = $('#' + field + '-error');
        
        input.removeClass('is-invalid');
        errorDiv.text('');
        
        if (input.hasClass('select2-hidden-accessible')) {
            input.next('.select2-container').find('.select2-selection')
                .removeClass('is-invalid');
        }
    }

    // ============================
    // GESTION DES FILTRES
    // ============================

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

    // ============================
    // GESTION DES BOUTONS ACTIONS
    // ============================

    // Gestionnaire pour le bouton d'édition
    $(document).on('click', '.edit-task-btn', function() {
        const taskId = $(this).data('id');
        loadTaskData(taskId);
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

    // Gestion du clic sur le bouton "Voir"
    $(document).on('click', '.view-task-btn', function() {
        const taskId = $(this).data('id');
        loadTaskDetails(taskId);
    });

    // ============================
    // FONCTIONS AJAX POUR ACTIONS
    // ============================

    // Fonction pour charger les données de la tâche (édition)
    window.loadTaskData = function(taskId) {
        $.ajax({
            url: '/tasks/' + taskId + '/edit',
            type: 'GET',
            success: function(response) {
                // Remplir le formulaire avec les données
                $('#edit_task_id').val(response.id);
                $('#edit_titre').val(response.titre);
                $('#edit_priorite').val(response.priorite).trigger('change');
                $('#edit_statut').val(response.statut).trigger('change');
                $('#edit_utilisateur_id').val(response.utilisateur_id).trigger('change');
                $('#edit_dossier_id').val(response.dossier_id).trigger('change');
                $('#edit_intervenant_id').val(response.intervenant_id).trigger('change');
                $('#edit_description').val(response.description);
                $('#edit_note').val(response.note);
                
                // Dates
                if (response.date_debut) {
                    $('#edit_date_debut').val(response.date_debut.split('T')[0]);
                }
                if (response.date_fin) {
                    $('#edit_date_fin').val(response.date_fin.split('T')[0]);
                }
                
                // Fichier existant
                if (response.file_path) {
                    $('#current-file-name').text(response.file_name);
                    $('#current-file-info').show();
                } else {
                    $('#current-file-info').hide();
                }
                
                // Informations de suivi
                let infoHtml = `<small>
                    <strong>Créé le:</strong> ${new Date(response.created_at).toLocaleDateString('fr-FR')}<br>
                    <strong>Modifié le:</strong> ${new Date(response.updated_at).toLocaleDateString('fr-FR')}`;
                
                if (response.dossier) {
                    infoHtml += `<br><strong>Dossier:</strong> ${response.dossier.numero_dossier}`;
                }
                if (response.intervenant) {
                    infoHtml += `<br><strong>Intervenant:</strong> ${response.intervenant.identite_fr}`;
                }
                infoHtml += `</small>`;
                
                $('#task-info').html(infoHtml);
                
                // Afficher le bouton de suppression si l'utilisateur a la permission
                @if(auth()->user()->hasPermission('delete_tasks'))
                    $('#deleteTaskBtn').show();
                @endif
                
                // Ouvrir le modal
                $('#editTaskModal').modal('show');
            },
            error: function(xhr) {
                showAlert('error', 'Erreur lors du chargement des données de la tâche.');
            }
        });
    };

    // Fonction pour charger les détails de la tâche (visualisation)
    function loadTaskDetails(taskId) {
        // Afficher le spinner de chargement
        $('#taskDetailsModalBody').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
                <p class="mt-2">Chargement des détails...</p>
            </div>
        `);
        
        // Ouvrir le modal
        $('#taskDetailsModal').modal('show');
        
        // Charger les détails via AJAX
        $.ajax({
            url: '/tasks/' + taskId,
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                // Parser la réponse HTML
                const parser = new DOMParser();
                const doc = parser.parseFromString(response, 'text/html');
                
                // Extraire toutes les sections pertinentes
                const sections = doc.querySelectorAll('.row > .col-md-6, .row > .col-12');
                
                // Construire le contenu du modal
                let modalContent = '';
                
                // Parcourir les sections et les organiser
                sections.forEach((section) => {
                    const sectionHTML = section.innerHTML;
                    
                    // Identifier le type de section par son contenu
                    if (sectionHTML.includes('Informations principales')) {
                        modalContent += `
                            <div class="row">
                                <div class="col-md-6">
                                    ${sectionHTML}
                                </div>
                        `;
                    } 
                    else if (sectionHTML.includes('Détails temporels')) {
                        modalContent += `
                                <div class="col-md-6">
                                    ${sectionHTML}
                                </div>
                            </div>
                        `;
                    }
                    else if (sectionHTML.includes('Relations')) {
                        modalContent += `
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    ${sectionHTML}
                                </div>
                        `;
                    }
                    else if (sectionHTML.includes('Métadonnées')) {
                        modalContent += `
                                <div class="col-md-6">
                                    ${sectionHTML}
                                </div>
                            </div>
                        `;
                    }
                    else if (sectionHTML.includes('Description')) {
                        modalContent += `
                            <div class="row mt-4 d-block">
                                ${sectionHTML}
                            </div>
                        `;
                    }
                    else if (sectionHTML.includes('Notes supplémentaires')) {
                        modalContent += `
                            <div class="row mt-4 d-none">
                                ${sectionHTML}
                            </div>
                        `;
                    }
                });
                
                // Mettre à jour le contenu du modal
                $('#taskDetailsModalBody').html(modalContent);
                
                // Ajouter les boutons d'action dans le footer
                const modalFooter = `
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Fermer
                        </button>
                        
                    </div>
                `;
                
                // Ajouter le footer au modal
                $('#taskDetailsModal .modal-content').append(modalFooter);
                
                // Trouver le titre de la tâche pour l'afficher dans le titre du modal
                let taskTitle = 'Tâche #' + taskId;
                const titleElement = doc.querySelector('td.font-weight-bold');
                if (titleElement && titleElement.textContent.trim() !== 'N/A') {
                    taskTitle = titleElement.textContent.trim();
                }
                
                // Mettre à jour le titre du modal
                $('#taskDetailsModalLabel').html(`<i class="fas fa-tasks"></i> Détails de la tâche: ${taskTitle}`);
            },
            error: function(xhr, status, error) {
                $('#taskDetailsModalBody').html(`
                    <div class="text-center py-5">
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>Erreur lors du chargement des détails de la tâche.</p>
                            <p><small>${xhr.status}: ${xhr.statusText}</small></p>
                            <a href="/tasks/${taskId}" class="btn btn-primary mt-2" target="_blank">
                                Ouvrir dans une nouvelle fenêtre
                            </a>
                        </div>
                    </div>
                `);
            }
        });
    }

    // ============================
    // FORMULAIRE DE CRÉATION
    // ============================
    
    // Gestion de l'affichage du nom du fichier (création)
    $('#file').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $('#file-label').text(fileName || 'Choisir un fichier...');
    });

    // Soumission du formulaire de création
    $('#submitCreateTask').on('click', function() {
        createTask();
    });

    // Soumission avec Enter (création)
    $('#taskCreateForm').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            createTask();
        }
    });

    function createTask() {
        // Réinitialiser les erreurs
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Désactiver le bouton et afficher le loader
        var submitBtn = $('#submitCreateTask');
        var originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Création...');

        // Créer FormData pour gérer les fichiers
        var formData = new FormData($('#taskCreateForm')[0]);

        $.ajax({
            url: '{{ route("tasks.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Fermer le modal
                $('#createTaskModal').modal('hide');
                
                // Recharger la table
                table.ajax.reload();
                
                // Afficher le message de succès
                showAlert('success', 'Tâche créée avec succès!');
                
                // Réactiver le bouton
                submitBtn.prop('disabled', false).html(originalText);
                
                // Réinitialiser le formulaire
                $('#taskCreateForm')[0].reset();
                $('#file-label').text('Choisir un fichier...');
                $('#priorite, #statut, #utilisateur_id, #dossier_id, #intervenant_id')
                    .val('').trigger('change');
            },
            error: function(xhr) {
                // Réactiver le bouton
                submitBtn.prop('disabled', false).html(originalText);
                
                if (xhr.status === 422) {
                    // Gestion des erreurs de validation
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        var input = $('#' + field);
                        var errorDiv = $('#' + field + '-error');
                        
                        input.addClass('is-invalid');
                        errorDiv.text(messages[0]);
                        
                        // Pour les selects de Select2
                        if (input.hasClass('select2-hidden-accessible')) {
                            input.next('.select2-container').find('.select2-selection')
                                .addClass('is-invalid');
                        }
                    });
                    
                    // Scroll vers la première erreur
                    $('.is-invalid').first().focus();
                } else if (xhr.status === 403) {
                    // Erreur de permission
                    showAlert('error', 'Permission refusée', 'Vous n\'avez pas la permission de créer des tâches.');
                    $('#createTaskModal').modal('hide');
                } else {
                    // Erreur générale
                    showAlert('error', 'Erreur', 'Une erreur est survenue lors de la création de la tâche.');
                }
            }
        });
    }

    // Validation en temps réel (création)
    $('#titre').on('blur', function() {
        if ($(this).val().length > 0 && $(this).val().length < 3) {
            showFieldError('titre', 'Le titre doit contenir au moins 3 caractères');
        } else {
            clearFieldError('titre');
        }
    });

    $('#date_fin').on('change', function() {
        var dateDebut = $('#date_debut').val();
        var dateFin = $(this).val();
        
        if (dateDebut && dateFin) {
            var start = new Date(dateDebut);
            var end = new Date(dateFin);
            
            if (end < start) {
                showFieldError('date_fin', 'La date de fin doit être postérieure ou égale à la date de début');
            } else {
                clearFieldError('date_fin');
            }
        }
    });

    // ============================
    // FORMULAIRE D'ÉDITION
    // ============================
    
    // Gestion de l'affichage du nom du fichier (édition)
    $('#edit_file').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $('#edit_file-label').text(fileName || 'Choisir un fichier...');
    });

    // Gestion de la suppression de fichier (édition)
    $('#remove-file-btn').on('click', function() {
        $('#remove_file').val('1');
        $('#current-file-info').hide();
        $('#edit_file').val('');
        $('#edit_file-label').text('Choisir un fichier...');
    });

    // Soumission du formulaire d'édition
    $('#submitEditTask').on('click', function() {
        updateTask();
    });

    // Soumission avec Enter (édition)
    $('#taskEditForm').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            updateTask();
        }
    });

    // Bouton de suppression (édition)
    $('#deleteTaskBtn').on('click', function() {
        const taskId = $('#edit_task_id').val();
        if (taskId && confirm('Êtes-vous sûr de vouloir supprimer cette tâche ? Cette action est irréversible.')) {
            deleteTask(taskId);
        }
    });

    function updateTask() {
        const taskId = $('#edit_task_id').val();
        if (!taskId) return;

        // Réinitialiser les erreurs
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Désactiver le bouton et afficher le loader
        var submitBtn = $('#submitEditTask');
        var originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mise à jour...');

        // Créer FormData pour gérer les fichiers
        var formData = new FormData($('#taskEditForm')[0]);

        $.ajax({
            url: '/tasks/' + taskId,
            type: 'POST', // Laravel utilise POST avec _method=PUT
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Fermer le modal
                $('#editTaskModal').modal('hide');
                
                // Recharger la table
                table.ajax.reload();
                
                // Afficher le message de succès
                showAlert('success', 'Tâche mise à jour avec succès!');
                
                // Réactiver le bouton
                submitBtn.prop('disabled', false).html(originalText);
            },
            error: function(xhr) {
                // Réactiver le bouton
                submitBtn.prop('disabled', false).html(originalText);
                
                if (xhr.status === 422) {
                    // Gestion des erreurs de validation
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        var input = $('#edit_' + field);
                        var errorDiv = $('#edit_' + field + '-error');
                        
                        input.addClass('is-invalid');
                        errorDiv.text(messages[0]);
                        
                        // Pour les selects de Select2
                        if (input.hasClass('select2-hidden-accessible')) {
                            input.next('.select2-container').find('.select2-selection')
                                .addClass('is-invalid');
                        }
                    });
                    
                    // Scroll vers la première erreur
                    $('.is-invalid').first().focus();
                } else if (xhr.status === 403) {
                    // Erreur de permission
                    showAlert('error', 'Permission refusée', 'Vous n\'avez pas la permission de modifier des tâches.');
                    $('#editTaskModal').modal('hide');
                } else {
                    // Erreur générale
                    showAlert('error', 'Erreur', 'Une erreur est survenue lors de la mise à jour de la tâche.');
                }
            }
        });
    }

    function deleteTask(taskId) {
        $.ajax({
            url: '/tasks/' + taskId,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Fermer le modal
                $('#editTaskModal').modal('hide');
                
                // Recharger la table
                table.ajax.reload();
                
                // Afficher le message de succès
                showAlert('success', 'Tâche supprimée avec succès!');
            },
            error: function(xhr) {
                let errorMessage = 'Une erreur est survenue lors de la suppression.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showAlert('error', errorMessage);
            }
        });
    }

    // Validation en temps réel (édition)
    $('#edit_titre').on('blur', function() {
        if ($(this).val().length > 0 && $(this).val().length < 3) {
            showFieldError('edit_titre', 'Le titre doit contenir au moins 3 caractères');
        } else {
            clearFieldError('edit_titre');
        }
    });

    $('#edit_date_fin').on('change', function() {
        var dateDebut = $('#edit_date_debut').val();
        var dateFin = $(this).val();
        
        if (dateDebut && dateFin) {
            var start = new Date(dateDebut);
            var end = new Date(dateFin);
            
            if (end < start) {
                showFieldError('edit_date_fin', 'La date de fin doit être postérieure ou égale à la date de début');
            } else {
                clearFieldError('edit_date_fin');
            }
        }
    });

    // ============================
    // GESTION DES MODALS
    // ============================

    // Réinitialiser le formulaire quand le modal de création est fermé
    $('#createTaskModal').on('hidden.bs.modal', function () {
        $('#taskCreateForm')[0].reset();
        $('#file-label').text('Choisir un fichier...');
        $('.invalid-feedback').text('');
        $('.is-invalid').removeClass('is-invalid');
        
        // Réinitialiser Select2
        $('#priorite, #statut, #utilisateur_id, #dossier_id, #intervenant_id')
            .val('').trigger('change');
            
        // Définir la date d'aujourd'hui par défaut
        var today = new Date().toISOString().split('T')[0];
        $('#date_debut').val(today);
        
        // Auto-sélection de l'utilisateur connecté si c'est un avocat/secrétaire
        @if(auth()->user()->fonction === 'avocat' || auth()->user()->fonction === 'secrétaire')
            $('#utilisateur_id').val('{{ auth()->id() }}').trigger('change');
        @endif
    });

    // Réinitialiser le formulaire quand le modal d'édition est fermé
    $('#editTaskModal').on('hidden.bs.modal', function () {
        $('#taskEditForm')[0].reset();
        $('#edit_file-label').text('Choisir un fichier...');
        $('.invalid-feedback').text('');
        $('.is-invalid').removeClass('is-invalid');
        $('#current-file-info').hide();
        $('#remove_file').val('0');
        $('#deleteTaskBtn').hide();
        
        // Réinitialiser Select2
        $('#edit_priorite, #edit_statut, #edit_utilisateur_id, #edit_dossier_id, #edit_intervenant_id')
            .val('').trigger('change');
    });

    // Nettoyer le modal quand il est fermé (visualisation)
    $('#taskDetailsModal').on('hidden.bs.modal', function() {
        // Réinitialiser le contenu du modal
        $(this).find('.modal-content').html(`
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="taskDetailsModalLabel">
                    <i class="fas fa-tasks"></i> Détails de la tâche
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="taskDetailsModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement des détails...</p>
                </div>
            </div>
        `);
    });

    // Close modal when clicking the X button (suppression)
    $('#deleteTaskModal .close, #deleteTaskModal [data-dismiss="modal"]').on('click', function() {
        taskToDelete = null;
        taskRowToDelete = null;
    });

    // Fermer les modals avec la touche ESC
    $(document).keydown(function(e) {
        if (e.keyCode === 27) {
            if ($('#taskDetailsModal').hasClass('show')) {
                $('#taskDetailsModal').modal('hide');
            }
            if ($('#editTaskModal').hasClass('show')) {
                $('#editTaskModal').modal('hide');
            }
            if ($('#deleteTaskModal').hasClass('show')) {
                taskToDelete = null;
                taskRowToDelete = null;
                $('#deleteTaskModal').modal('hide');
            }
        }
    });

    // ============================
    // FONCTIONS DE CONFIRMATION
    // ============================

    // Fonction de confirmation de suppression de tâche depuis le modal de visualisation
    window.confirmDeleteTaskFromModal = function(taskId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette tâche ? Cette action est irréversible.')) {
            // Afficher un indicateur de chargement
            const deleteButton = $('.btn-danger[onclick*="confirmDeleteTaskFromModal"]');
            const originalHtml = deleteButton.html();
            deleteButton.html('<i class="fas fa-spinner fa-spin"></i> Suppression...');
            deleteButton.prop('disabled', true);

            // Soumettre la suppression via AJAX
            $.ajax({
                url: '/tasks/' + taskId,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function(response) {
                    // Fermer le modal
                    $('#taskDetailsModal').modal('hide');
                    
                    // Recharger la DataTable
                    table.ajax.reload();
                    
                    // Afficher un message de succès
                    showTaskAlert('success', 'Tâche supprimée avec succès');
                },
                error: function(xhr) {
                    showTaskAlert('danger', 'Erreur lors de la suppression de la tâche');
                    
                    // Réactiver le bouton
                    deleteButton.html(originalHtml);
                    deleteButton.prop('disabled', false);
                }
            });
        }
    };

    // Définir la date d'aujourd'hui par défaut (création)
    var today = new Date().toISOString().split('T')[0];
    $('#date_debut').val(today);
    
    // Auto-sélection de l'utilisateur connecté si c'est un avocat/secrétaire (création)
    @if(auth()->user()->fonction === 'avocat' || auth()->user()->fonction === 'secrétaire')
        $('#utilisateur_id').val('{{ auth()->id() }}').trigger('change');
    @endif
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