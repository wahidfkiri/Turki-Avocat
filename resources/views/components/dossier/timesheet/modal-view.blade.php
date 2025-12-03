<!-- Modal combiné pour visualisation et édition de feuille de temps -->
<div class="modal fade" id="viewTimeSheetModal" tabindex="-1" role="dialog" aria-labelledby="viewTimeSheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewTimeSheetModalLabel">
                    <i class="fas fa-eye"></i> Détails de la Feuille de Temps
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="viewTimeSheetModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2 text-muted">Chargement des détails...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Fermer
                </button>
                <button type="button" class="btn btn-warning" id="editTimeSheetBtn" style="display: none;">
                    <i class="fas fa-edit"></i> Modifier
                </button>
                <button type="button" class="btn btn-primary" id="saveTimeSheetBtn" style="display: none;">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <button type="button" class="btn btn-secondary" id="cancelEditBtn" style="display: none;">
                    <i class="fas fa-undo"></i> Annuler
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let currentTimeSheetId = null;
    let originalData = null;
    let isEditMode = false;

    // Gestionnaire pour le bouton unique
    $(document).on('click', '.view-timesheet-btn', function() {
        const timesheetId = $(this).data('timesheet-id');
        currentTimeSheetId = timesheetId;
        loadTimeSheetDetails(timesheetId, 'view');
    });

    // Bouton Modifier
    $('#editTimeSheetBtn').on('click', function() {
        switchToEditMode();
    });

    // Bouton Enregistrer
    $('#saveTimeSheetBtn').on('click', function() {
        saveTimeSheet();
    });

    // Bouton Annuler
    $('#cancelEditBtn').on('click', function() {
        switchToViewMode();
        displayTimeSheetForm(originalData, 'view');
    });

    function loadTimeSheetDetails(timesheetId, mode = 'view') {
        // Afficher le loader
        $('#viewTimeSheetModalBody').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
                <p class="mt-2 text-muted">Chargement des détails...</p>
            </div>
        `);

        // Ouvrir le modal
        $('#viewTimeSheetModal').modal('show');

        // Charger les données
        $.ajax({
            url: '/time-sheets/' + timesheetId + '/data',
            type: 'GET',
            success: function(response) {
                if (response.success && response.data) {
                    originalData = response.data;
                    displayTimeSheetForm(response.data, mode);
                    updateModalButtons(mode);
                } else {
                    showError('Erreur lors du chargement des données.');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Une erreur est survenue lors du chargement.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                showError(errorMessage);
            }
        });
    }

    function displayTimeSheetForm(timesheet, mode = 'view') {
        const isViewMode = mode === 'view';
        const readOnlyAttr = isViewMode ? 'readonly' : '';
        const cssClass = isViewMode ? 'form-control-plaintext bg-light p-2 rounded' : 'form-control';
        
        const formHtml = `
            <form id="timeSheetForm">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <div class="row">
                    <!-- Date -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date_timesheet" class="font-weight-bold">Date *</label>
                            <input type="date" class="${cssClass}" 
                                   id="date_timesheet" name="date_timesheet" 
                                   value="${timesheet.date_timesheet ? timesheet.date_timesheet.split('T')[0] : ''}" 
                                   ${readOnlyAttr} required>
                        </div>
                    </div>

                    <!-- Dossier (lecture seule) -->
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="dossier" class="font-weight-bold">Dossier</label>
                            <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                   value="${escapeHtml(timesheet.dossier?.numero_dossier + ' ' + timesheet.dossier?.nom_dossier || 'Non lié')}" readonly>
                        </div>
                    </div>

                    <!-- Utilisateur -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="user_id" class="font-weight-bold">Utilisateur</label>
                            ${isViewMode ? `
                                <input type="text" class="${cssClass}" 
                                       value="${escapeHtml(timesheet.user?.name || 'Non assigné')}" readonly>
                            ` : `
                                <select class="form-control" id="user_id" name="utilisateur_id" ${readOnlyAttr}>
                                    <option value="">Sélectionnez un utilisateur</option>
                                    @foreach(\App\Models\User::all() as $user)
                                        <option value="{{ $user->id }}" ${timesheet.user?.id == {{ $user->id }} ? 'selected' : ''}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            `}
                        </div>
                    </div>

                    <!-- Catégorie -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="categorie_id" class="font-weight-bold">Catégorie</label>
                            ${isViewMode ? `
                                <input type="text" class="${cssClass}" 
                                       value="${escapeHtml(timesheet.categorie?.nom || 'Non définie')}" readonly>
                            ` : `
                                <select class="form-control" id="categorie_id" name="categorie" ${readOnlyAttr}>
                                    <option value="">Sélectionnez une catégorie</option>
                                    @foreach(\App\Models\Categorie::all() as $categorie)
                                        <option value="{{ $categorie->id }}" ${timesheet.categorie?.id == {{ $categorie->id }} ? 'selected' : ''}>
                                            {{ $categorie->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            `}
                        </div>
                    </div>

                    <!-- Type -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="type_id" class="font-weight-bold">Type</label>
                            ${isViewMode ? `
                                <input type="text" class="${cssClass}" 
                                       value="${escapeHtml(timesheet.type?.nom || 'Non défini')}" readonly>
                            ` : `
                                <select class="form-control" id="type_id" name="type" ${readOnlyAttr}>
                                    <option value="">Sélectionnez un type</option>
                                    @foreach(\App\Models\Type::all() as $type)
                                        <option value="{{ $type->id }}" ${timesheet.type?.id == {{ $type->id }} ? 'selected' : ''}>
                                            {{ $type->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            `}
                        </div>
                    </div>

                    <!-- Quantité -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quantite" class="font-weight-bold">Quantité *</label>
                            <input type="number" step="0.01" class="${cssClass}" 
                                   id="quantite" name="quantite" 
                                   value="${timesheet.quantite || 0}" 
                                   ${readOnlyAttr} required>
                        </div>
                    </div>

                    <!-- Prix -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="prix" class="font-weight-bold">Prix Unitaire *</label>
                            <input type="number" step="0.01" class="${cssClass}" 
                                   id="prix" name="prix" 
                                   value="${timesheet.prix || 0}" 
                                   ${readOnlyAttr} required>
                        </div>
                    </div>

                    <!-- Total (calculé automatiquement) -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="total" class="font-weight-bold">Total</label>
                            <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                   id="total" value="${formatCurrency(timesheet.total || 0)}" readonly>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description" class="font-weight-bold">Description</label>
                    <textarea class="${cssClass}" id="description" name="description" 
                              rows="2" ${readOnlyAttr}>${escapeHtml(timesheet.description || '')}</textarea>
                </div>

                <!-- Informations de suivi -->
                ${isViewMode ? `
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="alert alert-info text-black">
                            <h6 class="alert-heading"><i class="fas fa-info-circle"></i> Informations de suivi</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <small>
                                        <strong>Créé le:</strong> ${formatDateTime(timesheet.created_at)}<br>
                                        <strong>Par:</strong> ${escapeHtml(timesheet.user?.name || 'Inconnu')}
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small>
                                        <strong>Modifié le:</strong> ${formatDateTime(timesheet.updated_at)}<br>
                                        <strong>ID:</strong> ${timesheet.id}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ` : ''}
            </form>
        `;
        
        $('#viewTimeSheetModalBody').html(formHtml);

        // Calcul automatique du total en mode édition
        if (!isViewMode) {
            $('#quantite, #prix').on('input', calculateTotal);
        }
    }

    function calculateTotal() {
        const quantite = parseFloat($('#quantite').val()) || 0;
        const prix = parseFloat($('#prix').val()) || 0;
        const total = quantite * prix;
        $('#total').val(formatCurrency(total));
    }

    function switchToEditMode() {
        isEditMode = true;
        displayTimeSheetForm(originalData, 'edit');
        updateModalButtons('edit');
    }

    function switchToViewMode() {
        isEditMode = false;
        updateModalButtons('view');
    }

    function updateModalButtons(mode) {
        const isViewMode = mode === 'view';
        
        // Masquer tous les boutons d'abord
        $('#editTimeSheetBtn, #saveTimeSheetBtn, #cancelEditBtn').hide();
        
        if (isViewMode) {
            // Mode visualisation - afficher seulement le bouton Modifier si l'utilisateur a la permission
            @if(auth()->user()->hasPermission('edit_timesheets'))
                $('#editTimeSheetBtn').show();
            @endif
        } else {
            // Mode édition - afficher Enregistrer et Annuler
            $('#saveTimeSheetBtn').show();
            $('#cancelEditBtn').show();
        }
    }

    function saveTimeSheet() {
        if (!currentTimeSheetId) return;

        const formData = new FormData($('#timeSheetForm')[0]);
        
        // Afficher le loader
        $('#saveTimeSheetBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enregistrement...');

        $.ajax({
            url: '/time-sheets/' + currentTimeSheetId,
            type: 'POST', // Laravel utilise POST avec _method=PUT
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Fermer le modal
                $('#viewTimeSheetModal').modal('hide');
                
                // Recharger la table
                if (typeof timesheetTable !== 'undefined') {
                    timesheetTable.ajax.reload();
                }
                
                // Afficher le message de succès
                showAlert('success', 'Feuille de temps mise à jour avec succès!');
            },
            error: function(xhr) {
                $('#saveTimeSheetBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Enregistrer');
                
                if (xhr.status === 422) {
                    // Gestion des erreurs de validation
                    const errors = xhr.responseJSON.errors;
                    let errorHtml = '<div class="alert alert-danger"><ul class="mb-0">';
                    $.each(errors, function(field, messages) {
                        errorHtml += '<li>' + messages[0] + '</li>';
                    });
                    errorHtml += '</ul></div>';
                    
                    $('#viewTimeSheetModalBody').prepend(errorHtml);
                } else {
                    showError('Erreur lors de la mise à jour.');
                }
            }
        });
    }

    function showError(message) {
        $('#viewTimeSheetModalBody').html(`
            <div class="alert alert-danger text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                <h5>Erreur</h5>
                <p>${message}</p>
                <button class="btn btn-primary mt-2" onclick="loadTimeSheetDetails(${currentTimeSheetId})">
                    <i class="fas fa-redo"></i> Réessayer
                </button>
            </div>
        `);
    }

    function showAlert(type, message) {
        // Votre fonction showAlert existante
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
        
        $('.alert-dismissible').remove();
        $('.card').before(alertHtml);
        
        setTimeout(function() {
            $('.alert-dismissible').fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    }

    // Fonctions utilitaires
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
    $('#viewTimeSheetModal').on('hidden.bs.modal', function () {
        isEditMode = false;
        currentTimeSheetId = null;
        originalData = null;
        $('#viewTimeSheetModalBody').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
                <p class="mt-2 text-muted">Chargement des détails...</p>
            </div>
        `);
        updateModalButtons('view');
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