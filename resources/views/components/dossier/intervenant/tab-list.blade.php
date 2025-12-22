<!-- Onglet Intervenants -->
<div class="tab-pane fade" id="intervenants" role="tabpanel" aria-labelledby="intervenants-tab">
    <div class="p-3">
        <h5 class="text-primary mb-3"><i class="fas fa-handshake"></i> Gestion des intervenants</h5>
        
        <!-- Alert container -->
        <div id="intervenantAlertContainer" class="mb-3" style="display: none;"></div>

        <!-- Autres intervenants -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-users"></i> Intervenants liés</h6>
                        <button type="button" class="btn btn-primary btn-sm" id="addIntervenantBtn">
                            <i class="fas fa-plus"></i> Ajouter un intervenant
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Intervenants table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover w-100" id="intervenantsTable">
                                <thead>
                                    <tr>
                                        <th>Intervenant</th>
                                        <th>Rôle (Catégorie)</th>
                                        <th>Type</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
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

<!-- Modal pour ajouter un intervenant -->
<div class="modal fade" id="addIntervenantModal" tabindex="-1" role="dialog" aria-labelledby="addIntervenantModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addIntervenantModalLabel">
                    <i class="fas fa-user-plus"></i> Ajouter un intervenant au dossier
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Intervenants list -->
                <div class="form-group">
                    <label for="intervenantSelect">Sélectionnez un intervenant</label>
                    <select class="form-control" id="intervenantSelect">
                        <option value="">-- Sélectionnez un intervenant --</option>
                        @foreach($intervenants as $intervenant)
                            @php
                                $isAlreadyLinked = $dossier->intervenants->contains($intervenant->id);
                            @endphp
                            
                            @if(!$isAlreadyLinked)
                                <option value="{{ $intervenant->id }}" 
                                        data-name="{{ $intervenant->identite_fr }}"
                                        data-email="{{ $intervenant->mail1 ?? '' }}"
                                        data-phone="{{ $intervenant->portable1 ?? '' }}"
                                        data-category="{{ $intervenant->categorie ?? '' }}"
                                        data-type="{{ $intervenant->type ?? '' }}">
                                    {{ $intervenant->identite_fr }} 
                                    @if($intervenant->mail1)
                                        - {{ $intervenant->mail1 }}
                                    @endif
                                    @if($intervenant->categorie)
                                        ({{ $intervenant->categorie }})
                                    @endif
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- Info sur l'intervenant sélectionné -->
                <div id="intervenantInfo" class="card mt-3" style="display: none;">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informations</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nom :</strong> <span id="infoName"></span></p>
                                <p><strong>Email :</strong> <span id="infoEmail"></span></p>
                                <p><strong>Téléphone :</strong> <span id="infoPhone"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Catégorie :</strong> <span id="infoCategory"></span></p>
                                <p><strong>Type :</strong> <span id="infoType"></span></p>
                                <p><strong>Rôle attribué :</strong> <span id="infoRole" class="badge badge-info"></span></p>
                            </div>
                        </div>
                        <div class="alert alert-info mt-2">
                            <small>
                                <i class="fas fa-info-circle"></i> 
                                Le rôle sera automatiquement défini sur la catégorie de l'intervenant.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveIntervenantBtn">
                    <i class="fas fa-link"></i> Lier cet intervenant
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

<!-- JavaScript pour la gestion des intervenants -->
<script>
$(document).ready(function() {
    // Variables
    const dossierId = {{ $dossier->id }};
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    // Initialiser DataTable
    let intervenantsTable = $('#intervenantsTable').DataTable({
        processing: true,
        serverSide: false, // On utilise le mode client-side car on gère nous-mêmes les données
        searching: true,
        ordering: true,
        paging: true,
        pageLength: 10,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/French.json"
        },
        ajax: {
            url: `/dossiers/${dossierId}/intervenants-data`,
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
                                <i class="fas fa-user-circle fa-2x text-primary"></i>
                            </div>
                            <div>
                                <strong>${row.identite_fr}</strong>
                                ${row.identite_ar ? '<br><small class="text-muted">' + row.identite_ar + '</small>' : ''}
                            </div>
                        </div>
                    `;
                }
            },
            { 
                data: null,
                render: function(data, type, row) {
                    const role = row.pivot_role || row.categorie || 'Non défini';
                    return `<span class="badge badge-info">${role}</span>`;
                }
            },
            { 
                data: 'type',
                render: function(data, type, row) {
                    return data ? `<span class="badge badge-secondary">${data}</span>` : 'Non défini';
                }
            },
            { 
                data: 'mail1',
                render: function(data) {
                    return data || '-';
                }
            },
            { 
                data: 'portable1',
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
                            <a href="/intervenants/${row.id}" 
                               class="btn btn-info" title="Voir" target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-danger detach-intervenant-btn" 
                                    data-intervenant-id="${row.id}"
                                    data-intervenant-name="${row.identite_fr}">
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

    // ==================== GESTION DES INTERVENANTS ====================

    // Ouvrir le modal d'ajout d'intervenant
    $('#addIntervenantBtn').click(function() {
        $('#intervenantSelect').val('');
        $('#intervenantInfo').hide();
        $('#addIntervenantModal').modal('show');
    });

    // Afficher les informations quand un intervenant est sélectionné
    $('#intervenantSelect').change(function() {
        const selectedOption = $(this).find('option:selected');
        
        if ($(this).val()) {
            $('#infoName').text(selectedOption.data('name'));
            $('#infoEmail').text(selectedOption.data('email') || '-');
            $('#infoPhone').text(selectedOption.data('phone') || '-');
            $('#infoCategory').text(selectedOption.data('category') || '-');
            $('#infoType').text(selectedOption.data('type') || '-');
            $('#infoRole').text(selectedOption.data('category') || '-');
            $('#intervenantInfo').show();
        } else {
            $('#intervenantInfo').hide();
        }
    });

    // Lier un intervenant
    // Lier un intervenant - VERSION CORRIGÉE
$('#saveIntervenantBtn').click(function() {
    const intervenantId = $('#intervenantSelect').val();
    const intervenantName = $('#intervenantSelect option:selected').data('name');
    const intervenantCategory = $('#intervenantSelect option:selected').data('category') || 'Non défini';

    if (!intervenantId) {
        showAlert('danger', 'Veuillez sélectionner un intervenant', '#intervenantAlertContainer');
        return;
    }

    $.ajax({
        url: `/dossiers/${dossierId}/attach-intervenant`,
        type: 'POST',
        data: {
            _token: csrfToken,
            intervenant_id: intervenantId,
            role: intervenantCategory
        },
        beforeSend: function() {
            $('#saveIntervenantBtn').prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin"></i> En cours...');
        },
        success: function(response) {
            // Vérifier si la réponse contient 'success' true
            if (response.success) {
                // Fermer le modal
                $('#addIntervenantModal').modal('hide');
                
                // Réinitialiser le modal
                $('#intervenantSelect').val('');
                $('#intervenantInfo').hide();
                
                // Rafraîchir la DataTable
                intervenantsTable.ajax.reload(null, false); // false pour garder la pagination actuelle
                
                // Mettre à jour la liste des intervenants disponibles dans le select
                updateAvailableIntervenants(intervenantId);
                
                showAlert('success', response.message || `Intervenant "${intervenantName}" lié avec succès`, '#intervenantAlertContainer');
            } else {
                // Si success est false, afficher le message d'erreur
                showAlert('danger', response.message || 'Une erreur est survenue', '#intervenantAlertContainer');
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
            
            showAlert('danger', errorMsg, '#intervenantAlertContainer');
        },
        complete: function() {
            $('#saveIntervenantBtn').prop('disabled', false)
                .html('<i class="fas fa-link"></i> Lier cet intervenant');
        }
    });
});

    // ==================== DÉTACHEMENT D'INTERVENANT ====================

    // Fonction pour attacher les événements de détachement
    function attachDetachEvents() {
        $('.detach-intervenant-btn').off('click').on('click', function() {
            const intervenantId = $(this).data('intervenant-id');
            const intervenantName = $(this).data('intervenant-name');

            if (confirm(`Êtes-vous sûr de vouloir détacher "${intervenantName}" de ce dossier ?`)) {
                detachIntervenant(intervenantId, intervenantName);
            }
        });
    }

    // Fonction pour détacher un intervenant
    function detachIntervenant(intervenantId, intervenantName) {
        $.ajax({
            url: `/dossiers/${dossierId}/detach-intervenant`,
            type: 'POST',
            data: {
                _token: csrfToken,
                intervenant_id: intervenantId
            },
            beforeSend: function() {
                $(`.detach-intervenant-btn[data-intervenant-id="${intervenantId}"]`)
                    .prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i>');
            },
            success: function(response) {
                if (response.success) {
                    // Rafraîchir la DataTable
                    intervenantsTable.ajax.reload(null, false);
                    
                    // Ajouter l'intervenant à la liste des disponibles
                    const intervenantData = response.data.intervenant;
                    addIntervenantToSelect(intervenantData);
                    
                    showAlert('success', `Intervenant "${intervenantName}" détaché avec succès`, '#intervenantAlertContainer');
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Une erreur est survenue';
                showAlert('danger', errorMsg, '#intervenantAlertContainer');
                $(`.detach-intervenant-btn[data-intervenant-id="${intervenantId}"]`)
                    .prop('disabled', false)
                    .html('<i class="fas fa-unlink"></i>');
            }
        });
    }

    // ==================== FONCTIONS UTILITAIRES ====================

    // Mettre à jour la liste des intervenants disponibles
    function updateAvailableIntervenants(removedIntervenantId) {
        // Retirer l'option du select
        $(`#intervenantSelect option[value="${removedIntervenantId}"]`).remove();
        
        // Si plus d'options disponibles
        if ($('#intervenantSelect option').length === 1) { // Seulement l'option par défaut
            $('#intervenantSelect').append(
                '<option value="" disabled>Aucun intervenant disponible</option>'
            );
        }
    }

    // Ajouter un intervenant au select quand il est détaché
    function addIntervenantToSelect(intervenantData) {
        // Vérifier si l'option existe déjà
        if ($(`#intervenantSelect option[value="${intervenantData.id}"]`).length === 0) {
            const newOption = `
                <option value="${intervenantData.id}" 
                        data-name="${intervenantData.identite_fr}"
                        data-email="${intervenantData.mail1 || ''}"
                        data-phone="${intervenantData.portable1 || ''}"
                        data-category="${intervenantData.categorie || ''}"
                        data-type="${intervenantData.type || ''}">
                    ${intervenantData.identite_fr} 
                    ${intervenantData.mail1 ? ' - ' + intervenantData.mail1 : ''}
                    ${intervenantData.categorie ? ' (' + intervenantData.categorie + ')' : ''}
                </option>
            `;
            
            // Retirer le message "Aucun intervenant disponible" s'il existe
            $('#intervenantSelect option[value=""][disabled]').remove();
            
            // Ajouter la nouvelle option
            $('#intervenantSelect').append(newOption);
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
    $('#addIntervenantModal').on('hidden.bs.modal', function() {
        $('#intervenantSelect').val('');
        $('#intervenantInfo').hide();
    });
});
</script>

<style>
/* Styles supplémentaires */
.intervenant-role {
    transition: all 0.3s ease;
}

#intervenantsTable th {
    background-color: #f8f9fa;
    font-weight: 600;
}

#intervenantsTable tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.85em;
    padding: 0.35em 0.65em;
}

#intervenantInfo .card-body p {
    margin-bottom: 0.5rem;
}

#intervenantInfo .alert {
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
</style>