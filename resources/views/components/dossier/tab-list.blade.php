<!-- Onglet dossiers Liés -->
<div class="tab-pane fade" id="dossiers" role="tabpanel" aria-labelledby="dossiers-tab">
    <div class="p-3">
        <!-- Alert container -->
        <div id="dossierAlertContainer" class="mb-3" style="display: none;"></div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="text-primary mb-0"><i class="fas fa-link"></i> Dossiers Liés</h5>
            <button type="button" class="btn btn-primary" id="addDossierBtn">
                <i class="fas fa-plus"></i> Lier un dossier
            </button>
        </div>

        <!-- Tableau des dossiers liés -->
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-table"></i> Liste des dossiers liés</h6>
                <!-- <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-secondary" id="refreshDossiersBtn" title="Rafraîchir">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div> -->
            </div>
            <div class="card-body">
                <!-- Dossiers table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover w-100" id="dossiersTable">
                        <thead>
                            <tr>
                                <th>Dossier Lié</th>
                                <!-- <th>Relation</th> -->
                                <th>Objet</th>
                                <th>Date d'entrée</th>
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

<!-- Modal pour lier un dossier -->
<div class="modal fade" id="addDossierModal" tabindex="-1" role="dialog" aria-labelledby="addDossierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDossierModalLabel">
                    <i class="fas fa-link"></i> Lier un dossier au dossier courant
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Dossiers list -->
                <div class="form-group">
                    <label for="dossierSelect">Sélectionnez un dossier2222</label>
                    <select class="form-control search_dossier" id="dossierSelect">
                        <option value="">-- Sélectionnez un dossier --</option>
                        @foreach($dossiers as $dossierItem)
                            @if($dossierItem->id != $dossier->id)
                                <option value="{{ $dossierItem->id }}" 
                                        data-numero="{{ $dossierItem->numero_dossier }}"
                                        data-objet="{{ $dossierItem->objet ?? 'Non défini' }}"
                                        data-date="{{ $dossierItem->date_entree ?? 'Non définie' }}"
                                        data-nom="{{ $dossierItem->nom_dossier ?? 'Non défini' }}">
                                    {{ $dossierItem->numero_dossier }} - {{ $dossierItem->objet ?? 'Sans objet' }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveDossierBtn">
                    <i class="fas fa-link"></i> Lier ce dossier
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Inclure DataTables CSS et JS -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.2/jquery.sumoselect.min.js"></script>

<!-- JavaScript pour la gestion des dossiers liés -->
<script>
    // Initialiser SumoSelect
    $('.search_dossier').SumoSelect({
        search: true, 
        searchText: 'Rechercher un dossier...',
        placeholder: 'Sélectionnez un dossier'
    });
$(document).ready(function() {
    // Variables
    const dossierId = {{ $dossier->id }};
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    
    // Initialiser DataTable
    let dossiersTable = $('#dossiersTable').DataTable({
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
            url: `/dossiers/${dossierId}/linked-dossiers-data`,
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
                                <i class="fas fa-folder fa-2x text-warning"></i>
                            </div>
                            <div>
                                <strong><a href="/dossiers/${row.id}" target="_blank">${row.numero_dossier}</a></strong>
                                ${row.nom_dossier ? '<br><small class="text-muted">' + row.nom_dossier + '</small>' : ''}
                            </div>
                        </div>
                    `;
                }
            },
            // { 
            //     data: null,
            //     render: function(data, type, row) {
            //         const relation = row.pivot_relation || 'Non définie';
            //         return `<span class="badge badge-info">${relation}</span>`;
            //     }
            // },
            { 
                data: 'objet',
                render: function(data) {
                    return`<span class="badge badge-info">${data}</span>`;
                }
            },
            { 
                data: 'date_entree',
                render: function(data) {
                    if (!data) return '-';
                    const date = new Date(data);
                    return date.toLocaleDateString('fr-FR');
                }
            },
            { 
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <div class="btn-group btn-group-sm">
                            <a href="/dossiers/${row.id}" 
                               class="btn btn-info" title="Voir" target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-danger detach-dossier-btn" 
                                    data-dossier-id="${row.id}"
                                    data-dossier-numero="${row.numero_dossier}"
                                    data-relation="${row.pivot_relation || ''}">
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
            // Attacher les événements initialement
            attachDetachEvents();
        }
    });

    // ==================== GESTION DES DOSSIERS LIÉS ====================

    // Ouvrir le modal d'ajout de dossier
    $('#addDossierBtn').click(function() {
        $('#dossierSelect').val('');
        $('#dossierInfo').hide();
        $('#addDossierModal').modal('show');
    });

    // Rafraîchir la liste des dossiers
    $('#refreshDossiersBtn').click(function() {
        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        dossiersTable.ajax.reload(null, false);
        
        setTimeout(() => {
            $btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i>');
            showAlert('success', 'Liste des dossiers rafraîchie', '#dossierAlertContainer');
        }, 1000);
    });

    // Afficher les informations quand un dossier est sélectionné
    $('#dossierSelect').change(function() {
        const selectedOption = $(this).find('option:selected');
        
        if ($(this).val()) {
            $('#infoNumero').text(selectedOption.data('numero'));
            $('#infoNom').text(selectedOption.data('nom'));
            $('#infoObjet').text(selectedOption.data('objet'));
            $('#infoDate').text(selectedOption.data('date'));
            $('#dossierInfo').show();
        } else {
            $('#dossierInfo').hide();
        }
    });

    // Lier un dossier
    $('#saveDossierBtn').click(function() {
        const dossierLieId = $('#dossierSelect').val();
        const dossierNumero = $('#dossierSelect option:selected').data('numero');
        const relation = $('#relationInput').val() || 'Dossier connexe';

        if (!dossierLieId) {
            showAlert('danger', 'Veuillez sélectionner un dossier', '#dossierAlertContainer');
            return;
        }

        $.ajax({
            url: `/dossiers/${dossierId}/attach-dossier`,
            type: 'POST',
            data: {
                _token: csrfToken,
                dossier_lie_id: dossierLieId,
                relation: relation
            },
            beforeSend: function() {
                $('#saveDossierBtn').prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i> En cours...');
            },
            success: function(response) {
                if (response.success) {
                    // Fermer le modal
                    $('#addDossierModal').modal('hide');
                    
                    // Réinitialiser le modal
                    $('#dossierSelect').val('');
                    $('#relationInput').val('');
                    $('#dossierInfo').hide();
                    
                    // Rafraîchir la DataTable
                    dossiersTable.ajax.reload(null, false);
                    
                    // Mettre à jour la liste des dossiers disponibles dans le select
                    updateAvailableDossiers(dossierLieId);
                    
                    // Réinitialiser SumoSelect
                    $('.search_dossier')[0].sumo.unSelectAll();
                    
                    showAlert('success', response.message || `Dossier "${dossierNumero}" lié avec succès`, '#dossierAlertContainer');
                } else {
                    showAlert('danger', response.message || 'Une erreur est survenue', '#dossierAlertContainer');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Une erreur est survenue';
                
                if (xhr.status === 422) {
                    // Erreur de validation
                    const errors = xhr.responseJSON.errors;
                    errorMsg = Object.values(errors).flat().join(', ');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                
                showAlert('danger', errorMsg, '#dossierAlertContainer');
            },
            complete: function() {
                $('#saveDossierBtn').prop('disabled', false)
                    .html('<i class="fas fa-link"></i> Lier ce dossier');
            }
        });
    });

    // ==================== DÉTACHEMENT DE DOSSIER ====================

    // Fonction pour attacher les événements de détachement
    function attachDetachEvents() {
        $('.detach-dossier-btn').off('click').on('click', function() {
            const dossierLieId = $(this).data('dossier-id');
            const dossierNumero = $(this).data('dossier-numero');
            const relation = $(this).data('relation');

            if (confirm(`Êtes-vous sûr de vouloir détacher le dossier "${dossierNumero}" (${relation}) de ce dossier ?`)) {
                detachDossier(dossierLieId, dossierNumero);
            }
        });
    }

    // Fonction pour détacher un dossier
    function detachDossier(dossierLieId, dossierNumero) {
        const $btn = $(`.detach-dossier-btn[data-dossier-id="${dossierLieId}"]`);
        
        $.ajax({
            url: `/dossiers/${dossierId}/detach-dossier`,
            type: 'POST',
            data: {
                _token: csrfToken,
                dossier_lie_id: dossierLieId
            },
            beforeSend: function() {
                $btn.prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i>');
            },
            success: function(response) {
                if (response.success) {
                    // Rafraîchir la DataTable
                    dossiersTable.ajax.reload(null, false);
                    
                    // Ajouter le dossier à la liste des disponibles
                    if (response.data && response.data.dossier) {
                        addDossierToSelect(response.data.dossier);
                    }
                    
                    showAlert('success', `Dossier "${dossierNumero}" détaché avec succès`, '#dossierAlertContainer');
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Une erreur est survenue';
                showAlert('danger', errorMsg, '#dossierAlertContainer');
                $btn.prop('disabled', false)
                    .html('<i class="fas fa-unlink"></i>');
            }
        });
    }

    // ==================== FONCTIONS UTILITAIRES ====================

    // Mettre à jour la liste des dossiers disponibles
    function updateAvailableDossiers(removedDossierId) {
        // Retirer l'option du select
        $(`#dossierSelect option[value="${removedDossierId}"]`).remove();
        
        // Si plus d'options disponibles
        if ($('#dossierSelect option').length === 1) { // Seulement l'option par défaut
            $('#dossierSelect').append(
                '<option value="" disabled>Aucun dossier disponible</option>'
            );
        }
        
        // Mettre à jour SumoSelect
        $('.search_dossier')[0].sumo.reload();
    }

    // Ajouter un dossier au select quand il est détaché
    function addDossierToSelect(dossierData) {
        // Vérifier si l'option existe déjà
        if ($(`#dossierSelect option[value="${dossierData.id}"]`).length === 0) {
            const newOption = `
                <option value="${dossierData.id}" 
                        data-numero="${dossierData.numero_dossier}"
                        data-objet="${dossierData.objet || 'Non défini'}"
                        data-date="${dossierData.date_entree || 'Non définie'}"
                        data-nom="${dossierData.nom_dossier || 'Non défini'}">
                    ${dossierData.numero_dossier} - ${dossierData.objet || 'Sans objet'}
                </option>
            `;
            
            // Retirer le message "Aucun dossier disponible" s'il existe
            $('#dossierSelect option[value=""][disabled]').remove();
            
            // Ajouter la nouvelle option
            $('#dossierSelect').append(newOption);
            
            // Mettre à jour SumoSelect
            $('.search_dossier')[0].sumo.reload();
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
    $('#addDossierModal').on('hidden.bs.modal', function() {
        $('#dossierSelect').val('');
        $('#relationInput').val('');
        $('#dossierInfo').hide();
        $('.search_dossier')[0].sumo.unSelectAll();
    });
    
    // Focus sur la recherche quand la modal s'ouvre
    $('#addDossierModal').on('shown.bs.modal', function() {
        // Forcer l'ouverture de la liste déroulante SumoSelect
        setTimeout(() => {
            $('.SumoSelect').find('.CaptionCont').click();
        }, 300);
    });
});
</script>

<style>
/* Styles supplémentaires pour les dossiers */
#dossiersTable th {
    background-color: #f8f9fa;
    font-weight: 600;
}

#dossiersTable tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.85em;
    padding: 0.35em 0.65em;
}

#dossierInfo .card-body p {
    margin-bottom: 0.5rem;
}

#dossierInfo .alert {
    margin-bottom: 0;
}

/* Style pour DataTables */
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

/* Style pour SumoSelect dans le modal */
#addDossierModal .SumoSelect {
    width: 100% !important;
}

#addDossierModal .search_test1 {
    display: none;
}
</style>