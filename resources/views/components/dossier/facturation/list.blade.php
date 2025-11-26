<!-- Onglet Facturation -->
<div class="tab-pane fade" id="facturation" role="tabpanel" aria-labelledby="facturation-tab">
    <div class="p-3">
        <div style="display: flow-root;">
            <h5 class="text-primary mb-3"><i class="fas fa-money-bill-wave"></i> Informations de facturation</h5>
            <a href="#" data-toggle="modal" data-target="#factureModal" class="btn btn-primary mb-3" style="float: right;">
                <i class="fas fa-plus"></i> Ajouter une facture 
            </a>
        </div>

        @if($dossier->factures && $dossier->factures->count() > 0)
            <div class="table-responsive">
                <table id="facturesTable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Numéro</th>
                            <th>Date émission</th>
                            <th>Montant HT</th>
                            <th>Montant TVA</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dossier->factures as $facture)
                        <tr>
                            <td>{{ $facture->numero }}</td>
                            <td>{{ $facture->date_emission->format('d/m/Y') }}</td>
                            <td data-value="{{ $facture->montant_ht }}">{{ number_format($facture->montant_ht, 2) }} DT</td>
                            <td data-value="{{ $facture->montant_tva }}">{{ number_format($facture->montant_tva, 2) }} DT</td>
                            <td data-value="{{ $facture->montant }}">{{ number_format($facture->montant, 2) }} DT</td>
                            <td>
                                <span class="badge 
                                    @if($facture->statut == 'payé') badge-success
                                    @elseif($facture->statut == 'non_payé') badge-warning
                                    @else badge-secondary
                                    @endif">
                                    @if($facture->statut == 'payé')
                                    Payée
                                    @else 
                                    Non Payée
                                    @endif
                                </span>
                            </td>
                            <td>
                                <!-- Bouton unique pour visualisation et édition -->
                                <button type="button" class="btn btn-sm btn-primary view-facture-btn" 
                                        data-facture-id="{{ $facture->id }}" title="Voir/Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                @if($facture->piece_jointe)
                                <a href="{{ url('factures/download')}}/{{ $facture->id }}" 
                                   download class="btn btn-sm btn-success" title="Télécharger">
                                    <i class="fas fa-download"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-light font-weight-bold">
                            <td colspan="2" class="text-right">TOTAUX :</td>
                            <td id="totalHT" class="text-success">0.00 DT</td>
                            <td id="totalTVA" class="text-success">0.00 DT</td>
                            <td id="totalMontant" class="text-success">0.00 DT</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="alert alert-info" style="color:black;">
                <h6><i class="icon fas fa-info"></i> Information</h6>
                <p class="mb-0">
                    Aucune facture n'a été ajoutée à ce dossier.
                </p>
            </div>
        @endif
    </div>
</div>

<!-- Inclure le modal de facture -->
@include('components.dossier.facturation.modal-view')
<script>
$(document).ready(function() {
    let currentFactureId = null;
    let originalFactureData = null;
    let isEditModeFacture = false;

    // Gestionnaire pour le bouton unique des factures
    $(document).on('click', '.view-facture-btn', function() {
        const factureId = $(this).data('facture-id');
        currentFactureId = factureId;
        loadFactureDetails(factureId, 'view');
    });

    // Bouton Modifier
    $('#editFactureBtn').on('click', function() {
        switchToEditModeFacture();
    });

    // Bouton Enregistrer
    $('#saveFactureBtn').on('click', function() {
        saveFacture();
    });

    // Bouton Annuler
    $('#cancelEditFactureBtn').on('click', function() {
        switchToViewModeFacture();
        displayFactureForm(originalFactureData, 'view');
    });

    function loadFactureDetails(factureId, mode = 'view') {
        // Afficher le loader
        $('#viewFactureModalBody').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
                <p class="mt-2 text-muted">Chargement des détails...</p>
            </div>
        `);

        // Ouvrir le modal
        $('#viewFactureModal').modal('show');

        // Charger les données
        $.ajax({
            url: '/factures/' + factureId + '/data',
            type: 'GET',
            success: function(response) {
                if (response.success && response.data) {
                    originalFactureData = response.data;
                    displayFactureForm(response.data, mode);
                    updateFactureModalButtons(mode);
                } else {
                    showFactureError('Erreur lors du chargement des données.');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Une erreur est survenue lors du chargement.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                showFactureError(errorMessage);
            }
        });
    }

    function displayFactureForm(facture, mode = 'view') {
        const isViewMode = mode === 'view';
        const readOnlyAttr = isViewMode ? 'readonly' : '';
        const cssClass = isViewMode ? 'form-control-plaintext bg-light p-2 rounded' : 'form-control';
        
        const formHtml = `
            <form id="factureForm">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <div class="row">
                    <!-- Numéro -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="numero" class="font-weight-bold">Numéro *</label>
                            <input type="text" class="${cssClass}" 
                                   id="numero" name="numero" 
                                   value="${escapeHtml(facture.numero || '')}" 
                                   ${readOnlyAttr} required>
                        </div>
                    </div>

                    <!-- Date émission -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_emission" class="font-weight-bold">Date d'émission *</label>
                            <input type="date" class="${cssClass}" 
                                   id="date_emission" name="date_emission" 
                                   value="${facture.date_emission ? facture.date_emission.split('T')[0] : ''}" 
                                   ${readOnlyAttr} required>
                        </div>
                    </div>

                    <!-- Montant HT -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="montant_ht" class="font-weight-bold">Montant HT *</label>
                            <input type="number" step="0.01" class="${cssClass}" 
                                   id="montant_ht" name="montant_ht" 
                                   value="${facture.montant_ht || 0}" 
                                   ${readOnlyAttr} required>
                        </div>
                    </div>

                    <!-- Montant TVA -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="montant_tva" class="font-weight-bold">Montant TVA *</label>
                            <input type="number" step="0.01" class="${cssClass}" 
                                   id="montant_tva" name="montant_tva" 
                                   value="${facture.montant_tva || 0}" 
                                   ${readOnlyAttr} required>
                        </div>
                    </div>

                    <!-- Montant Total (calculé automatiquement) -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="montant" class="font-weight-bold">Montant Total</label>
                            <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                   id="montant" value="${formatCurrency((facture.montant_ht || 0) + (facture.montant_tva || 0))}" readonly>
                        </div>
                    </div>

                    <!-- Statut -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="statut" class="font-weight-bold">Statut</label>
                            ${isViewMode ? `
                                <input type="text" class="${cssClass}" 
                                       value="${getStatutLabel(facture.statut)}" readonly>
                            ` : `
                                <select class="form-control" id="statut" name="statut" ${readOnlyAttr}>
                                    <option value="">Sélectionnez un statut</option>
                                    <option value="payé" ${facture.statut == 'payé' ? 'selected' : ''}>Payée</option>
                                    <option value="non_payé" ${facture.statut == 'non_payé' ? 'selected' : ''}>Non Payée</option>
                                </select>
                            `}
                        </div>
                    </div>

                    <!-- Dossier (lecture seule) -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="dossier" class="font-weight-bold">Dossier</label>
                            <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                   value="${escapeHtml(facture.dossier?.numero_dossier || 'Non lié')}" readonly>
                        </div>
                    </div>
                </div>

                <!-- Fichier joint -->
                <div class="form-group">
                    <label class="font-weight-bold">Fichier joint</label>
                    ${facture.piece_jointe ? `
                        <div class="input-group">
                            <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                   value="${escapeHtml(facture.piece_jointe)}" readonly>
                            <div class="input-group-append">
                                <a href="/factures/${facture.id}/download" 
                                   class="btn btn-outline-success" download>
                                    <i class="fas fa-download"></i> Télécharger
                                </a>
                            </div>
                        </div>
                    ` : `
                        <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                               value="Aucun fichier joint" readonly>
                    `}
                </div>

                <!-- Informations de suivi -->
                ${isViewMode ? `
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="fas fa-info-circle"></i> Informations de suivi</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <small>
                                        <strong>Créé le:</strong> ${formatDateTime(facture.created_at)}<br>
                                        <strong>Par:</strong> ${escapeHtml(facture.user?.name || 'Inconnu')}
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small>
                                        <strong>Modifié le:</strong> ${formatDateTime(facture.updated_at)}<br>
                                        <strong>ID:</strong> ${facture.id}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ` : ''}
            </form>
        `;
        
        $('#viewFactureModalBody').html(formHtml);

        // Calcul automatique du montant total en mode édition
        if (!isViewMode) {
            $('#montant_ht, #montant_tva').on('input', calculateMontantTotal);
        }
    }

    function calculateMontantTotal() {
        const montantHT = parseFloat($('#montant_ht').val()) || 0;
        const montantTVA = parseFloat($('#montant_tva').val()) || 0;
        const montantTotal = montantHT + montantTVA;
        $('#montant').val(formatCurrency(montantTotal));
    }

    function getStatutLabel(statut) {
        const labels = {
            'payé': 'Payée',
            'non_payé': 'Non Payée'
        };
        return labels[statut] || statut || 'Non défini';
    }

    function switchToEditModeFacture() {
        isEditModeFacture = true;
        displayFactureForm(originalFactureData, 'edit');
        updateFactureModalButtons('edit');
    }

    function switchToViewModeFacture() {
        isEditModeFacture = false;
        updateFactureModalButtons('view');
    }

    function updateFactureModalButtons(mode) {
        const isViewMode = mode === 'view';
        
        // Masquer tous les boutons d'abord
        $('#editFactureBtn, #saveFactureBtn, #cancelEditFactureBtn').hide();
        
        if (isViewMode) {
            // Mode visualisation - afficher seulement le bouton Modifier si l'utilisateur a la permission
            @if(auth()->user()->hasPermission('edit_factures'))
                $('#editFactureBtn').show();
            @endif
        } else {
            // Mode édition - afficher Enregistrer et Annuler
            $('#saveFactureBtn').show();
            $('#cancelEditFactureBtn').show();
        }
    }

    function saveFacture() {
        if (!currentFactureId) return;

        const formData = new FormData($('#factureForm')[0]);
        
        // Afficher le loader
        $('#saveFactureBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enregistrement...');

        $.ajax({
            url: '/factures/' + currentFactureId,
            type: 'POST', // Laravel utilise POST avec _method=PUT
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Fermer le modal
                $('#viewFactureModal').modal('hide');
                
                // Recharger la table
                if (typeof table !== 'undefined') {
                    table.ajax.reload();
                }
                
                // Afficher le message de succès
                showAlert('success', 'Facture mise à jour avec succès!');
            },
            error: function(xhr) {
                $('#saveFactureBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Enregistrer');
                
                if (xhr.status === 422) {
                    // Gestion des erreurs de validation
                    const errors = xhr.responseJSON.errors;
                    let errorHtml = '<div class="alert alert-danger"><ul class="mb-0">';
                    $.each(errors, function(field, messages) {
                        errorHtml += '<li>' + messages[0] + '</li>';
                    });
                    errorHtml += '</ul></div>';
                    
                    $('#viewFactureModalBody').prepend(errorHtml);
                } else {
                    showFactureError('Erreur lors de la mise à jour.');
                }
            }
        });
    }

    function showFactureError(message) {
        $('#viewFactureModalBody').html(`
            <div class="alert alert-danger text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                <h5>Erreur</h5>
                <p>${message}</p>
                <button class="btn btn-primary mt-2" onclick="loadFactureDetails(${currentFactureId})">
                    <i class="fas fa-redo"></i> Réessayer
                </button>
            </div>
        `);
    }

    // Fonctions utilitaires (déjà définies dans votre script)
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('fr-FR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount) + ' DT';
    }

    function formatDateTime(dateTimeString) {
        if (!dateTimeString) return 'Non définie';
        const date = new Date(dateTimeString);
        return date.toLocaleDateString('fr-FR') + ' à ' + date.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'});
    }

    // Réinitialiser le modal quand il est fermé
    $('#viewFactureModal').on('hidden.bs.modal', function () {
        isEditModeFacture = false;
        currentFactureId = null;
        originalFactureData = null;
        $('#viewFactureModalBody').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
                <p class="mt-2 text-muted">Chargement des détails...</p>
            </div>
        `);
        updateFactureModalButtons('view');
    });

    // Votre code DataTable existant pour les calculs de totaux...
    // Function to calculate totals
    function calculateTotals() {
        var totalHT = 0;
        var totalTVA = 0;
        var totalMontant = 0;

        $('#facturesTable tbody tr').each(function() {
            var ht = parseFloat($(this).find('td:eq(2)').data('value')) || 0;
            var tva = parseFloat($(this).find('td:eq(3)').data('value')) || 0;
            var montant = parseFloat($(this).find('td:eq(4)').data('value')) || 0;

            totalHT += ht;
            totalTVA += tva;
            totalMontant += montant;
        });

        $('#totalHT').text(totalHT.toFixed(2) + ' DT');
        $('#totalTVA').text(totalTVA.toFixed(2) + ' DT');
        $('#totalMontant').text(totalMontant.toFixed(2) + ' DT');
    }

    // Initialize DataTable
    var table = $('#facturesTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/French.json"
        },
        "responsive": true,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "pageLength": 10,
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        "footerCallback": function (row, data, start, end, display) {
            setTimeout(calculateTotals, 100);
        },
        "drawCallback": function() {
            calculateTotals();
            // Réattacher les événements après chaque redraw
            $('.view-facture-btn').off('click').on('click', function() {
                const factureId = $(this).data('facture-id');
                loadFactureDetails(factureId, 'view');
            });
        },
        "initComplete": function() {
            calculateTotals();
            // Attacher les événements après l'initialisation
            $('.view-facture-btn').off('click').on('click', function() {
                const factureId = $(this).data('facture-id');
                loadFactureDetails(factureId, 'view');
            });
        }
    });

    // Recalculate totals when search/filter changes
    table.on('search.dt', function() {
        calculateTotals();
    });
});
</script>

<style>
.form-control-plaintext.bg-light {
    border: 1px solid #dee2e6;
    min-height: 38px;
    display: flex;
    align-items: center;
}
.form-control-plaintext.bg-light:focus {
    background-color: #e9ecef !important;
    border-color: #ced4da;
    outline: 0;
    box-shadow: none;
}
</style>