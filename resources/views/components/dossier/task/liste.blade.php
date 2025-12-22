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

<!-- Modal pour l'édition des tâches -->
<div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="editTaskModalLabel">
                    <i class="fas fa-edit"></i> Modifier la tâche
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Le formulaire sera chargé ici via AJAX -->
                <div class="text-center py-4">
                    <div class="spinner-border text-warning" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement du formulaire...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AJOUTEZ CES CDN DANS VOTRE LAYOUT OU ICI -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.fr.min.js"></script>

<style>
/* Styles additionnels */
.datepicker {
    z-index: 9999 !important;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}
.modal-xl {
    max-width: 800px;
}
</style>
<script>
$(document).ready(function() {
    // Stocker la liste des utilisateurs globalement
    window.usersList = [];
    
    // ============================
    // INITIALISATION DATATABLE
    // ============================
    var tasksTable = $('#tasksTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("dossier.tasks.data", $dossier->id) }}',
            type: 'GET',
            data: function(d) {
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
                        return data;
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
                        return data;
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
                    
                    actions += '<button type="button" class="btn btn-sm btn-info view-task-btn mr-1" data-task-id="' + data + '" title="Voir">';
                    actions += '<i class="fas fa-eye"></i>';
                    actions += '</button>';
                    
                    actions += '<button type="button" class="btn btn-sm btn-warning edit-task-btn mr-1" data-task-id="' + data + '" data-task-title="' + row.titre + '" title="Modifier">';
                    actions += '<i class="fas fa-edit"></i>';
                    actions += '</button>';
                    
                    actions += '<button type="button" class="btn btn-sm btn-danger delete-task-btn mr-1" data-task-id="' + data + '" data-task-title="' + row.titre + '" title="Supprimer">';
                    actions += '<i class="fas fa-trash"></i>';
                    actions += '</button>';
                    
                    if (row.file_path) {
                        actions += '<a href="/tasks/download/' + data + '" class="btn btn-sm btn-success" title="Télécharger" download>';
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
        order: [[1, 'desc']],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        initComplete: function() {
            $('.dataTables_filter input').addClass('form-control-sm');
            $('.dataTables_length select').addClass('form-control-sm');
            
            // Charger la liste des utilisateurs au démarrage
            loadUsersList();
        }
    });

    // ============================
    // FONCTIONS UTILITAIRES
    // ============================
    
    function loadUsersList() {
        $.ajax({
            url: '{{ route("users.list") }}',
            type: 'GET',
            dataType: 'json',
            success: function(users) {
                window.usersList = users;
                console.log('Liste des utilisateurs chargée:', users);
            },
            error: function(xhr) {
                console.error('Erreur lors du chargement des utilisateurs:', xhr);
            }
        });
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    function showAlert(type, message) {
        if (typeof Swal !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
            
            Toast.fire({
                icon: type,
                title: escapeHtml(message)
            });
        } else {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert" 
                     style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                    <i class="fas fa-${getAlertIcon(type)} mr-2"></i>
                    ${escapeHtml(message)}
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
    }

    function getAlertIcon(type) {
        switch(type) {
            case 'success': return 'check-circle';
            case 'danger': return 'exclamation-circle';
            case 'warning': return 'exclamation-triangle';
            case 'info': return 'info-circle';
            default: return 'bell';
        }
    }

    function showFormErrors(form, errors) {
        $.each(errors, function(field, messages) {
            const input = form.find('[name="' + field + '"]');
            const formGroup = input.closest('.form-group');
            
            if (formGroup.length) {
                formGroup.find('.form-control, .custom-file-input').addClass('is-invalid');
                const errorDiv = formGroup.find('.invalid-feedback');
                
                if (errorDiv.length === 0) {
                    formGroup.append('<div class="invalid-feedback">' + messages.join('<br>') + '</div>');
                } else {
                    errorDiv.html(messages.join('<br>'));
                }
            }
        });
    }

    function showModalError(message) {
        $('#editTaskModal .modal-body').html(`
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> ${message}
                <div class="mt-3">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Fermer
                    </button>
                </div>
            </div>
        `);
    }

    // ============================
    // GESTION DES FILTRES
    // ============================

    $('#filterStatut, #filterPriorite, #filterUser, #filterDateDebut').on('change', function() {
        tasksTable.ajax.reload();
    });

    $('#resetFilters').click(function() {
        $('#filterStatut').val('');
        $('#filterPriorite').val('');
        $('#filterUser').val('');
        $('#filterDateDebut').val('');
        tasksTable.ajax.reload();
    });

    // ============================
    // GESTION DES BOUTONS ACTIONS
    // ============================

    $(document).on('click', '.view-task-btn', function(e) {
        e.preventDefault();
        const taskId = $(this).data('task-id');
        loadTaskDetails(taskId);
    });

    $(document).on('click', '.edit-task-btn', function(e) {
        e.preventDefault();
        const taskId = $(this).data('task-id');
        const taskTitle = $(this).data('task-title');
        console.log('Modifier tâche ID:', taskId, 'Titre:', taskTitle);
        loadEditTaskForm(taskId, taskTitle);
    });

    $(document).on('click', '.delete-task-btn', function(e) {
        e.preventDefault();
        const taskId = $(this).data('task-id');
        const taskTitle = $(this).data('task-title');
        deleteTask(taskId, taskTitle);
    });

    // ============================
    // FONCTIONS AJAX POUR ACTIONS
    // ============================

    function loadTaskDetails(taskId) {
        $('#taskViewModal').modal('show');
        
        $('#taskViewModal .modal-body').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
                <p class="mt-2">Chargement des détails...</p>
            </div>
        `);
        
        $.ajax({
            url: '/tasks/' + taskId,
            type: 'GET',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                $('#taskViewModal .modal-body').html(response);
            },
            error: function(xhr) {
                let errorMessage = 'Erreur lors du chargement des détails';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                $('#taskViewModal .modal-body').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> ${errorMessage}
                    </div>
                `);
            }
        });
    }

    function loadEditTaskForm(taskId, taskTitle) {
        console.log('Chargement formulaire pour tâche:', taskId);
        
        $('#editTaskModal').modal('show');
        $('#editTaskModalLabel').html(`<i class="fas fa-edit"></i> Modifier : ${escapeHtml(taskTitle)}`);
        
        $('#editTaskModal .modal-body').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-warning" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
                <p class="mt-2">Chargement du formulaire...</p>
            </div>
        `);
        
        $.ajax({
            url: '/tasks/' + taskId + '/edit',
            type: 'GET',
            data: { _token: '{{ csrf_token() }}' },
            success: function(taskData) {
                console.log('Données de la tâche reçues:', taskData);
                
                const formHtml = generateEditFormHtml(taskData);
                $('#editTaskModal .modal-body').html(formHtml);
                
                initEditForm(taskId);
            },
            error: function(xhr) {
                console.error('Erreur lors du chargement:', xhr);
                let errorMessage = 'Erreur lors du chargement des données';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 404) {
                    errorMessage = 'Tâche non trouvée';
                }
                
                showModalError(errorMessage);
            }
        });
    }

    function generateEditFormHtml(taskData) {
        const formatDate = (dateString) => {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toISOString().split('T')[0];
        };

        const titreLength = taskData.titre ? taskData.titre.length : 0;
        const titreWarningClass = titreLength > 30 ? 'text-warning' : 'text-muted';

        let userOptions = '<option value="">Non assigné</option>';
        
        if (window.usersList && window.usersList.length > 0) {
            window.usersList.forEach(function(user) {
                const isSelected = (taskData.utilisateur_id && user.id == taskData.utilisateur_id) ? 'selected' : '';
                userOptions += `<option value="${user.id}" ${isSelected}>${escapeHtml(user.name)}</option>`;
            });
        } else if (taskData.user) {
            userOptions += `<option value="${taskData.user.id}" selected>${escapeHtml(taskData.user.name)}</option>`;
        }

        return `
        <form id="editTaskForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="edit_titre">Titre <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control" 
                               id="edit_titre" 
                               name="titre" 
                               value="${escapeHtml(taskData.titre || '')}"
                               required
                               maxlength="255">
                        <div class="invalid-feedback"></div>
                        <small class="form-text text-muted">Caractères: 
                            <span id="edit_titre_count" class="${titreWarningClass}">${titreLength}</span>/255
                        </small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="edit_date_debut">Date de début <span class="text-danger">*</span></label>
                        <input type="date" 
                               class="form-control datepicker-edit" 
                               id="edit_date_debut" 
                               name="date_debut" 
                               value="${formatDate(taskData.date_debut)}"
                               required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="edit_date_fin">Date de fin</label>
                        <input type="date" 
                               class="form-control datepicker-edit" 
                               id="edit_date_fin" 
                               name="date_fin" 
                               value="${formatDate(taskData.date_fin)}">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="edit_priorite">Priorité <span class="text-danger">*</span></label>
                        <select class="form-control" 
                                id="edit_priorite" 
                                name="priorite" 
                                required>
                            <option value="basse" ${taskData.priorite === 'basse' ? 'selected' : ''}>Basse</option>
                            <option value="normale" ${taskData.priorite === 'normale' ? 'selected' : ''}>Normale</option>
                            <option value="haute" ${taskData.priorite === 'haute' ? 'selected' : ''}>Haute</option>
                            <option value="urgente" ${taskData.priorite === 'urgente' ? 'selected' : ''}>Urgente</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="edit_statut">Statut <span class="text-danger">*</span></label>
                        <select class="form-control" 
                                id="edit_statut" 
                                name="statut" 
                                required>
                            <option value="a_faire" ${taskData.statut === 'a_faire' ? 'selected' : ''}>À faire</option>
                            <option value="en_cours" ${taskData.statut === 'en_cours' ? 'selected' : ''}>En cours</option>
                            <option value="terminee" ${taskData.statut === 'terminee' ? 'selected' : ''}>Terminée</option>
                            <option value="en_retard" ${taskData.statut === 'en_retard' ? 'selected' : ''}>En retard</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="edit_utilisateur_id">Assigné à</label>
                        <select class="form-control select2" 
                                id="edit_utilisateur_id" 
                                name="utilisateur_id">
                            ${userOptions}
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="edit_dossier_id">Dossier <span class="text-danger">*</span></label>
                        <select class="form-control" 
                                id="edit_dossier_id" 
                                name="dossier_id" 
                                required>
                            ${taskData.dossier ? 
                                `<option value="${taskData.dossier.id}" selected>
                                    ${escapeHtml(taskData.dossier.nom_dossier)} (${taskData.dossier.numero_dossier})
                                </option>` : 
                                ''}
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="edit_file">Fichier joint</label>
                        <div class="custom-file">
                            <input type="file" 
                                   class="custom-file-input" 
                                   id="edit_file" 
                                   name="file"
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                            <label class="custom-file-label" for="edit_file">Choisir un fichier...</label>
                            <div class="invalid-feedback"></div>
                        </div>
                        <small class="form-text text-muted">
                            Formats acceptés: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG (Max: 10MB)
                        </small>
                        
                        ${taskData.file_path && taskData.file_name ? `
                        <div id="edit_current_file" class="mt-2">
                            <div class="alert alert-info p-2">
                                <i class="fas fa-paperclip mr-2"></i>
                                Fichier actuel: 
                                <a href="/storage/${taskData.file_path}" target="_blank" class="ml-1">
                                    ${escapeHtml(taskData.file_name)}
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger ml-2" id="removeFileBtn">
                                    <i class="fas fa-times"></i> Supprimer
                                </button>
                                <input type="hidden" name="remove_existing_file" id="removeExistingFile" value="0">
                            </div>
                        </div>
                        ` : '<div id="edit_current_file" class="mt-2"></div>'}
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea class="form-control" 
                                  id="edit_description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Ajouter une description détaillée...">${escapeHtml(taskData.description || '')}</textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelEditBtn" data-dismiss="modal">
                    <i class="fas fa-times"></i> Annuler
                </button>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
        `;
    }

    function initEditForm(taskId) {
        console.log('Initialisation du formulaire pour ID:', taskId);
        
        // Initialiser Select2 pour le champ utilisateur
        $('#edit_utilisateur_id').select2({
            theme: 'bootstrap4',
            dropdownParent: $('#editTaskModal')
        });
        
        // Gestion du nom du fichier
        $('#edit_file').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });
        
        // Gestion du bouton de suppression de fichier
        $('#removeFileBtn').on('click', function() {
            $('#removeExistingFile').val('1');
            $(this).closest('.alert').fadeOut();
            showAlert('info', 'Le fichier sera supprimé lors de l\'enregistrement.');
        });
        
        // Compteur de caractères pour le titre
        $('#edit_titre').on('input', function() {
            const length = $(this).val().length;
            $('#edit_titre_count').text(length);
            if (length > 30) {
                $('#edit_titre_count').removeClass('text-muted').addClass('text-warning');
            } else {
                $('#edit_titre_count').removeClass('text-warning').addClass('text-muted');
            }
        });
        
        // Soumission du formulaire d'édition
        $('#editTaskForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            console.log('Soumission formulaire pour ID:', taskId);
            updateTaskAjax(taskId);
        });
        
        // Bouton d'annulation
        $('#cancelEditBtn').off('click').on('click', function() {
            $('#editTaskModal').modal('hide');
        });
        
        console.log('Formulaire initialisé avec succès');
    }

    function updateTaskAjax(taskId) {
        const form = $('#editTaskForm');
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true);
        submitBtn.html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Enregistrement...
        `);
        
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        const formData = new FormData(form[0]);
        
        $.ajax({
            url: '/tasks/' + taskId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(response) {
                submitBtn.prop('disabled', false);
                submitBtn.html(originalText);
                
                if (response.success) {
                    $('#editTaskModal').modal('hide');
                    tasksTable.ajax.reload(null, false);
                    showAlert('success', response.message || 'Tâche modifiée avec succès');
                } else {
                    showFormErrors(form, response.errors || {});
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false);
                submitBtn.html(originalText);
                
                let errorMessage = 'Erreur lors de la modification de la tâche';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    showFormErrors(form, xhr.responseJSON.errors);
                    return;
                }
                
                showAlert('danger', errorMessage);
            }
        });
    }

    function deleteTask(taskId, taskTitle) {
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            html: `<div class="text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <p>Vous êtes sur le point de supprimer la tâche :</p>
                <p class="font-weight-bold">"${escapeHtml(taskTitle)}"</p>
                <p class="text-danger">Cette action est irréversible !</p>
            </div>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="fas fa-trash"></i> Oui, supprimer',
            cancelButtonText: '<i class="fas fa-times"></i> Annuler',
            reverseButtons: true,
            backdrop: true,
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Suppression en cours...',
                    html: '<div class="text-center"><div class="spinner-border text-danger" role="status"></div><p class="mt-2">Veuillez patienter</p></div>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    backdrop: true
                });
                
                $.ajax({
                    url: '/tasks/' + taskId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        dossier_id: '{{ $dossier->id }}'
                    },
                    success: function(response) {
                        Swal.close();
                        
                        if (response.success) {
                            tasksTable.ajax.reload(null, false);
                            Swal.fire({
                                title: 'Supprimé !',
                                html: `<div class="text-center">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <p>${escapeHtml(response.message || 'La tâche a été supprimée avec succès.')}</p>
                                </div>`,
                                icon: 'success',
                                confirmButtonColor: '#28a745',
                                confirmButtonText: '<i class="fas fa-check"></i> OK'
                            });
                        } else {
                            Swal.fire({
                                title: 'Erreur !',
                                text: escapeHtml(response.message || 'Erreur lors de la suppression'),
                                icon: 'error',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        
                        let errorMessage = 'Erreur lors de la suppression de la tâche';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            title: 'Erreur !',
                            html: `<div class="text-center">
                                <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                                <p>${escapeHtml(errorMessage)}</p>
                            </div>`,
                            icon: 'error',
                            confirmButtonColor: '#dc3545',
                            confirmButtonText: '<i class="fas fa-times"></i> Fermer'
                        });
                    }
                });
            }
        });
    }

    // ============================
    // FORMULAIRE DE CRÉATION (2ème script)
    // ============================

    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // Fonction pour afficher les messages
    function showMessage(type, message) {
        $('#ajax-messages').html(`
            <div class="alert alert-${type} alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                ${type === 'success' ? '<h5><i class="icon fas fa-check"></i> Succès!</h5>' : '<h5><i class="icon fas fa-ban"></i> Erreur!</h5>'}
                ${message}
            </div>
        `).show();
        
        setTimeout(function() {
            $('#ajax-messages').fadeOut();
        }, 2000);
    }

    // Fonction pour réinitialiser les erreurs
    function resetErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').html('');
        $('#ajax-messages').hide();
    }

    // Fonction pour afficher les erreurs de validation
    function showValidationErrors(errors) {
        resetErrors();
        $.each(errors, function(field, messages) {
            var input = $('[name="' + field + '"]');
            var errorElement = $('#' + field + '-error');
            
            input.addClass('is-invalid');
            errorElement.html('<strong>' + messages[0] + '</strong>');
        });
    }

    // Set today's date as default for date fields
    function setDefaultDates() {
        var today = new Date().toISOString().split('T')[0];
        $('#date_debut').val(today);
    }

    // Gérer l'affichage du nom du fichier
    $('#file').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $('#file-label').text(fileName || 'Choisir un fichier...');
    });

    // Soumission du formulaire avec AJAX
    $('#taskForm').submit(function(e) {
        e.preventDefault();
        
        resetErrors();
        
        var submitBtn = $('#submitBtnTask');
        var originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Création en cours...');
        
        var formData = new FormData(this);
        formData.append('_token', '{{ csrf_token() }}');
        
        $.ajax({
            url: '{{ route("dossiers.tasks.store", ["dossier" => $dossier->id]) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showMessage('success', response.message);
                    
                    $('#taskForm')[0].reset();
                    $('#file-label').text('Choisir un fichier...');
                    
                    $('#taskModal').modal('hide');
                    
                    // Rafraîchir la DataTable
                    tasksTable.ajax.reload();
                } else {
                    showMessage('error', response.message || 'Une erreur est survenue.');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    showValidationErrors(errors);
                } else {
                    showMessage('error', 'Une erreur est survenue lors de la création de la tâche.');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Auto-sélection de l'utilisateur connecté si c'est un avocat/secrétaire
    @if(auth()->user()->fonction === 'avocat' || auth()->user()->fonction === 'secrétaire')
        $('#utilisateur_id').val('{{ auth()->id() }}').trigger('change');
    @endif

    // Reset form when modal is opened
    $('#taskModal').on('show.bs.modal', function () {
        $('#taskForm')[0].reset();
        $('#file-label').text('Choisir un fichier...');
        
        resetErrors();
        
        $('.select2').val(null).trigger('change');
        
        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Créer la tâche');
        
        setDefaultDates();
        
        @if(auth()->user()->fonction === 'avocat' || auth()->user()->fonction === 'secrétaire')
            $('#utilisateur_id').val('{{ auth()->id() }}').trigger('change');
        @endif
        
        $('#dossier_id').val('{{ $dossier->id }}').trigger('change');
    });

    // Gérer la fermeture de la modal
    $('#taskModal').on('hidden.bs.modal', function () {
        $('#taskForm')[0].reset();
        resetErrors();
    });

    // Validation des dates
    $('#date_fin').on('change', function() {
        var startDate = $('#date_debut').val();
        var endDate = $(this).val();
        
        if (startDate && endDate) {
            var start = new Date(startDate);
            var end = new Date(endDate);
            
            if (end < start) {
                $(this).addClass('is-invalid');
                $('#date_fin-error').html('<strong>La date de fin doit être postérieure ou égale à la date de début</strong>');
            } else {
                $(this).removeClass('is-invalid');
                $('#date_fin-error').html('');
            }
        }
    });

    // ============================
    // GESTION DES MODALS
    // ============================

    $(document).on('hidden.bs.modal', '.modal', function() {
        if ($(this).attr('id') === 'editTaskModal') {
            $(this).find('.modal-body').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-warning" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement du formulaire...</p>
                </div>
            `);
        }
    });
});
</script>