<div class="tab-pane fade" id="equipe" role="tabpanel" aria-labelledby="equipe-tab">
    <div class="p-3">
        <h5 class="text-primary mb-3"><i class="fas fa-users-cog"></i> Gestion de l'équipe</h5>
        
        <!-- Alert container -->
        <div id="utilisateurAlertContainer" class="mb-3" style="display: none;"></div>

        <!-- Utilisateurs liés -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-users"></i> Utilisateurs liés</h6>
                        <button type="button" class="btn btn-primary btn-sm ml-auto" id="addUtilisateurBtn">
                            <i class="fas fa-plus"></i> Ajouter un utilisateur
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Utilisateurs table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover w-100" id="utilisateursTable">
                                <thead>
                                    <tr>
                                        <th>Utilisateur</th>
                                        <th>Rôle</th>
                                        <th>Email</th>
                                        <th>Fonction</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Les données seront chargées par DataTable via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter un utilisateur -->
<div class="modal fade" id="addUtilisateurModal" tabindex="-1" role="dialog" aria-labelledby="addUtilisateurModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUtilisateurModalLabel">
                    <i class="fas fa-user-plus"></i> Ajouter un utilisateur au dossier
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Utilisateurs list -->
                <div class="form-group">
                    <label for="utilisateurSelect">Sélectionnez un utilisateur</label>
                    <select class="form-control search_users" id="utilisateurSelect">
                        <option value="">-- Sélectionnez un utilisateur --</option>
                        @foreach($users as $user)
                            @php
                                $isAlreadyLinked = $dossier->users->contains($user->id);
                            @endphp
                            
                            @if(!$isAlreadyLinked)
                                <option value="{{ $user->id }}" 
                                        data-name="{{ $user->name }}"
                                        data-email="{{ $user->email ?? '' }}"
                                        data-fonction="{{ $user->fonction ?? '' }}">
                                    {{ $user->name }} 
                                    @if($user->email)
                                        - {{ $user->email }}
                                    @endif
                                    @if($user->fonction)
                                        ({{ $user->fonction }})
                                    @endif
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- Info sur l'utilisateur sélectionné -->
                <div id="utilisateurInfo" class="card mt-3" style="display: none;">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informations</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nom :</strong> <span id="infoUtilisateurName"></span></p>
                                <p><strong>Email :</strong> <span id="infoUtilisateurEmail"></span></p>
                                <p><strong>Fonction :</strong> <span id="infoUtilisateurFonction"></span></p>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="roleInput"><strong>Rôle attribué :</strong></label>
                                    <input type="text" class="form-control" id="roleInput" 
                                           placeholder="Rôle dans ce dossier"
                                           value="" readonly>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Ce champ est basé sur la fonction de l'utilisateur. Vous pouvez le modifier si nécessaire.
                                    </small>
                                </div>
                                <div class="text-right mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="editRoleBtn">
                                        <i class="fas fa-edit"></i> Modifier le rôle
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveUtilisateurBtn">
                    <i class="fas fa-link"></i> Lier cet utilisateur
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Inclure DataTables CSS et JS -->
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<!-- JavaScript pour la gestion des utilisateurs -->
<script> // Initialiser le select avec SumoSelect
  //  $('.search_users').SumoSelect({search: true, searchText: 'Rechercher un utilisateur...'});
    
$(document).ready(function() {
    // Variables
    const dossierId = {{ $dossier->id }};
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    let isRoleEditable = false;
    
   
    // Initialiser DataTable pour les utilisateurs liés
    let utilisateursTable = $('#utilisateursTable').DataTable({
        processing: true,
        serverSide: false,
        searching: true,
        ordering: true,
        paging: true,
        pageLength: 10,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/French.json"
        },
        ajax: {
            url: `/dossiers/${dossierId}/utilisateurs-data`,
            type: 'GET',
            dataSrc: 'data'
        },
        columns: [
            { 
                data: null,
                render: function(data, type, row) {
                    return `
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <i class="fas fa-user fa-2x text-primary"></i>
                            </div>
                            <div>
                                <strong>${row.name}</strong>
                                <br><small class="text-muted">${row.email || ''}</small>
                            </div>
                        </div>
                    `;
                }
            },
            { 
                data: null,
                render: function(data, type, row) {
                    const role = row.pivot_role || row.fonction || 'Collaborateur';
                    return `<span class="badge badge-info">${role}</span>`;
                }
            },
            { 
                data: 'email',
                render: function(data) {
                    return data || '-';
                }
            },
            { 
                data: 'fonction',
                render: function(data) {
                    return data || '-';
                }
            },
            { 
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-danger detach-utilisateur-btn" 
                                    data-utilisateur-id="${row.id}"
                                    data-utilisateur-name="${row.name}">
                                <i class="fas fa-unlink"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        drawCallback: function(settings) {
            // Réattacher les événements après chaque redessin de la table
            attachDetachEvents();
        },
        initComplete: function() {
            attachDetachEvents();
        }
    });

    // ==================== GESTION DES UTILISATEURS ====================

    // Ouvrir le modal d'ajout d'utilisateur
    $('#addUtilisateurBtn').click(function() {
        $('#utilisateurSelect').val('');
        $('#utilisateurInfo').hide();
        $('#roleInput').prop('readonly', true);
        $('#editRoleBtn').html('<i class="fas fa-edit"></i> Modifier le rôle');
        isRoleEditable = false;
        $('#addUtilisateurModal').modal('show');
    });

    // Afficher les informations quand un utilisateur est sélectionné
    $('#utilisateurSelect').change(function() {
        const selectedOption = $(this).find('option:selected');
        
        if ($(this).val()) {
            const userName = selectedOption.data('name');
            const userEmail = selectedOption.data('email') || '-';
            const userFonction = selectedOption.data('fonction') || 'Collaborateur';
            
            $('#infoUtilisateurName').text(userName);
            $('#infoUtilisateurEmail').text(userEmail);
            $('#infoUtilisateurFonction').text(userFonction);
            
            // Définir la valeur du rôle (par défaut = fonction)
            $('#roleInput').val(userFonction);
            $('#roleInput').prop('readonly', true);
            $('#editRoleBtn').html('<i class="fas fa-edit"></i> Modifier le rôle');
            isRoleEditable = false;
            
            $('#utilisateurInfo').show();
        } else {
            $('#utilisateurInfo').hide();
        }
    });

    // Activer/désactiver l'édition du rôle
    $('#editRoleBtn').click(function() {
        isRoleEditable = !isRoleEditable;
        
        if (isRoleEditable) {
            $('#roleInput').prop('readonly', false);
            $('#roleInput').focus();
            $(this).html('<i class="fas fa-lock"></i> Verrouiller');
            $(this).removeClass('btn-outline-secondary').addClass('btn-outline-primary');
        } else {
            $('#roleInput').prop('readonly', true);
            $(this).html('<i class="fas fa-edit"></i> Modifier le rôle');
            $(this).removeClass('btn-outline-primary').addClass('btn-outline-secondary');
            
            // Si le champ est vide, remettre la fonction par défaut
            if (!$('#roleInput').val().trim()) {
                const selectedOption = $('#utilisateurSelect').find('option:selected');
                const defaultFonction = selectedOption.data('fonction') || 'Collaborateur';
                $('#roleInput').val(defaultFonction);
            }
        }
    });

    // Lier un utilisateur
    $('#saveUtilisateurBtn').click(function() {
        const utilisateurId = $('#utilisateurSelect').val();
        const utilisateurName = $('#utilisateurSelect option:selected').data('name');
        const role = $('#roleInput').val().trim();
        
        if (!utilisateurId) {
            showAlert('danger', 'Veuillez sélectionner un utilisateur', '#utilisateurAlertContainer');
            return;
        }
        
        if (!role) {
            showAlert('danger', 'Veuillez spécifier un rôle', '#utilisateurAlertContainer');
            return;
        }

        $.ajax({
            url: `/dossiers/${dossierId}/attach-utilisateur`,
            type: 'POST',
            data: {
                _token: csrfToken,
                utilisateur_id: utilisateurId,
                role: role
            },
            beforeSend: function() {
                $('#saveUtilisateurBtn').prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i> En cours...');
            },
            success: function(response) {
                if (response.success) {
                    // Fermer le modal
                    $('#addUtilisateurModal').modal('hide');
                    
                    // Réinitialiser le modal
                    $('#utilisateurSelect').val('');
                    $('#utilisateurInfo').hide();
                    $('#roleInput').val('');
                    $('#roleInput').prop('readonly', true);
                    $('#editRoleBtn').html('<i class="fas fa-edit"></i> Modifier le rôle').removeClass('btn-outline-primary').addClass('btn-outline-secondary');
                    isRoleEditable = false;
                    
                    // Rafraîchir la DataTable
                    utilisateursTable.ajax.reload(null, false);
                    
                    // Mettre à jour la liste des utilisateurs disponibles
                    updateAvailableUtilisateurs(utilisateurId);
                    
                    showAlert('success', response.message || `Utilisateur "${utilisateurName}" lié avec succès`, '#utilisateurAlertContainer');
                } else {
                    showAlert('danger', response.message || 'Une erreur est survenue', '#utilisateurAlertContainer');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Une erreur est survenue';
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    errorMsg = Object.values(errors).flat().join(', ');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                
                showAlert('danger', errorMsg, '#utilisateurAlertContainer');
            },
            complete: function() {
                $('#saveUtilisateurBtn').prop('disabled', false)
                    .html('<i class="fas fa-link"></i> Lier cet utilisateur');
            }
        });
    });

    // ==================== DÉTACHEMENT D'UTILISATEUR ====================

    // Fonction pour attacher les événements de détachement
    function attachDetachEvents() {
        $('.detach-utilisateur-btn').off('click').on('click', function() {
            const utilisateurId = $(this).data('utilisateur-id');
            const utilisateurName = $(this).data('utilisateur-name');

            if (confirm(`Êtes-vous sûr de vouloir détacher "${utilisateurName}" de ce dossier ?`)) {
                detachUtilisateur(utilisateurId, utilisateurName);
            }
        });
    }

    // Fonction pour détacher un utilisateur
    function detachUtilisateur(utilisateurId, utilisateurName) {
        $.ajax({
            url: `/dossiers/${dossierId}/detach-utilisateur`,
            type: 'POST',
            data: {
                _token: csrfToken,
                utilisateur_id: utilisateurId
            },
            beforeSend: function() {
                $(`.detach-utilisateur-btn[data-utilisateur-id="${utilisateurId}"]`)
                    .prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i>');
            },
            success: function(response) {
                if (response.success) {
                    // Rafraîchir la DataTable
                    utilisateursTable.ajax.reload(null, false);
                    
                    // Ajouter l'utilisateur à la liste des disponibles
                    const utilisateurData = response.data.utilisateur;
                    addUtilisateurToSelect(utilisateurData);
                    
                    showAlert('success', `Utilisateur "${utilisateurName}" détaché avec succès`, '#utilisateurAlertContainer');
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Une erreur est survenue';
                showAlert('danger', errorMsg, '#utilisateurAlertContainer');
                $(`.detach-utilisateur-btn[data-utilisateur-id="${utilisateurId}"]`)
                    .prop('disabled', false)
                    .html('<i class="fas fa-unlink"></i>');
            }
        });
    }

    // ==================== FONCTIONS UTILITAIRES ====================

    // Mettre à jour la liste des utilisateurs disponibles
    function updateAvailableUtilisateurs(removedUtilisateurId) {
        // Retirer l'option du select
        $(`#utilisateurSelect option[value="${removedUtilisateurId}"]`).remove();
        
       
        // Si plus d'options disponibles
        if ($('#utilisateurSelect option').length === 1) {
            $('#utilisateurSelect').append(
                '<option value="" disabled>Aucun utilisateur disponible</option>'
            );
        }
    }

    // Ajouter un utilisateur au select quand il est détaché
    function addUtilisateurToSelect(utilisateurData) {
        // Vérifier si l'option existe déjà
        if ($(`#utilisateurSelect option[value="${utilisateurData.id}"]`).length === 0) {
            // Retirer le message "Aucun utilisateur disponible" s'il existe
            $('#utilisateurSelect option[value=""][disabled]').remove();
            
            // Ajouter la nouvelle option
            const newOption = new Option(
                `${utilisateurData.name} ${utilisateurData.email ? ' - ' + utilisateurData.email : ''} ${utilisateurData.fonction ? ' (' + utilisateurData.fonction + ')' : ''}`,
                utilisateurData.id,
                false,
                false
            );
            
            // Ajouter les data attributes
            $(newOption).attr('data-name', utilisateurData.name)
                        .attr('data-email', utilisateurData.email || '')
                        .attr('data-fonction', utilisateurData.fonction || '');
            
            $('#utilisateurSelect').append(newOption);
            
        }
    }

    // Fonction pour afficher une alerte
    function showAlert(type, message, container) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;
        
        $(container).html(alertHtml).show();
        
        setTimeout(() => {
            $(container + ' .alert').alert('close');
        }, 5000);
    }

    // Réinitialiser le modal quand il se ferme
    $('#addUtilisateurModal').on('hidden.bs.modal', function() {
        $('#utilisateurSelect').val('');
        $('#utilisateurInfo').hide();
        $('#roleInput').val('');
        $('#roleInput').prop('readonly', true);
        $('#editRoleBtn').html('<i class="fas fa-edit"></i> Modifier le rôle').removeClass('btn-outline-primary').addClass('btn-outline-secondary');
        isRoleEditable = false;
    });
});
</script>

<style>
/* Styles pour DataTables */
#utilisateursTable th {
    background-color: #f8f9fa;
    font-weight: 600;
}

#utilisateursTable tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.85em;
    padding: 0.35em 0.65em;
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    margin-bottom: 1rem;
}

.dataTables_wrapper .dataTables_filter input {
    margin-left: 0.5em;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 0.375rem 0.75rem;
}

/* Styles pour SumoSelect dans le modal */
#addUtilisateurModal .SumoSelect {
    width: 100%;
}

#addUtilisateurModal .SumoSelect > .CaptionCont {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 0.375rem 0.75rem;
}

#addUtilisateurModal .SumoSelect.open > .CaptionCont {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

/* Styles pour le champ rôle */
#roleInput:read-only {
    background-color: #f8f9fa;
    border-color: #e9ecef;
}

#roleInput:not(:read-only) {
    background-color: #fff;
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

/* Style pour le bouton d'édition */
#editRoleBtn.btn-outline-primary {
    border-color: #007bff;
    color: #007bff;
}
</style>