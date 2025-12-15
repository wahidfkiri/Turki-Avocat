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
                <table id="facturesTable" class="table table-bordered table-hover w-100">
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

<!-- Modal pour visualiser une facture -->
<div class="modal fade" id="viewFactureModal" tabindex="-1" role="dialog" aria-labelledby="viewFactureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="viewFactureModalLabel">
                    <i class="fas fa-eye"></i> Détails de la facture
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="viewFactureModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-info" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement des détails...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour éditer une facture -->
<div class="modal fade" id="editFactureModal" tabindex="-1" role="dialog" aria-labelledby="editFactureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="editFactureModalLabel">
                    <i class="fas fa-edit"></i> Modifier la facture
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="editFactureModalBody">
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

<!-- Inclure le modal de facture -->
<!-- Onglet Facturation -->
<div class="tab-pane fade" id="facturation" role="tabpanel" aria-labelledby="facturation-tab">
    <div class="p-3">
        <div style="display: flow-root;">
            <h5 class="text-primary mb-3"><i class="fas fa-money-bill-wave"></i> Informations de facturation</h5>
            <a href="#" data-toggle="modal" data-target="#factureModal" class="btn btn-primary mb-3" style="float: right;">
                <i class="fas fa-plus"></i> Ajouter une facture 
            </a>
        </div>

        <!-- Table DataTable -->
        <div class="table-responsive">
            <table id="facturesTable" class="table table-bordered table-hover" style="width:100%">
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
                    <!-- Les données seront chargées via AJAX -->
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
    </div>
</div>

<!-- Modal pour visualiser une facture -->
<div class="modal fade" id="viewFactureModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title"><i class="fas fa-eye"></i> Détails de la facture</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="viewFactureModalBody">
                <!-- Chargé via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour éditer une facture -->
<div class="modal fade" id="editFactureModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-edit"></i> Modifier la facture</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="editFactureModalBody">
                <!-- Chargé via AJAX -->
            </div>
        </div>
    </div>
</div>
<script>
// ============================
// FONCTIONS UTILITAIRES GLOBALES
// ============================

// Fonction pour effacer l'input file
function clearFileInput() {
    $('#piece_jointe').val('');
    $('#piece_jointe_label').text('Choisir un fichier (PDF, images, Word, Excel) - Max 10MB');
    $('#file_preview').hide();
}

// Fonction pour afficher les messages d'erreur
function showErrors(errors) {
    // Supprimer les anciennes erreurs
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').html('');
    
    // Afficher les nouvelles erreurs
    $.each(errors, function(field, messages) {
        var input = $('#' + field);
        var errorDiv = $('#error_' + field);
        
        input.addClass('is-invalid');
        if (errorDiv.length) {
            errorDiv.html(messages[0]);
        }
    });
}

// Fonction pour afficher une alerte
function showAlert(message, type = 'danger') {
    var alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
    
    $('#ajaxAlert').html(alertHtml).show();
}

// Fonction pour calculer le montant TTC
function calculateMontantTTC() {
    var montantHT = parseFloat($('#montant_ht').val()) || 0;
    var montantTVA = parseFloat($('#montant_tva').val()) || 0;
    var montantTTC = montantHT + montantTVA;
    
    $('#montant').val(montantTTC.toFixed(2));
    
    // Vérifier la cohérence
    var tolerance = 0.01;
    var difference = Math.abs(montantTTC - parseFloat($('#montant').val()));
    
    if (difference > tolerance) {
        $('#montantAlert').show();
        $('#montantAlertText').text('Attention : Le montant TTC calculé (' + montantTTC.toFixed(2) + ' DT) est différent du montant saisi. Vérifiez les montants HT et TVA.');
    } else {
        $('#montantAlert').hide();
    }
}

// Fonction pour afficher une alerte SweetAlert
function showToastAlert(type, message) {
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
    }
}

// Fonction pour échapper le HTML
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

// Fonction pour formater les devises
function formatCurrency(amount) {
    return new Intl.NumberFormat('fr-FR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(amount);
}

// Fonction pour formater les dates
function formatDateTime(dateTimeString) {
    if (!dateTimeString) return 'Non définie';
    const date = new Date(dateTimeString);
    return date.toLocaleDateString('fr-FR') + ' à ' + date.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'});
}

// Fonction pour obtenir le libellé du type de pièce
function getTypePieceLabel(type) {
    const labels = {
        'facture': 'Facture',
        'note_frais': 'Note de frais',
        'note_provision': 'Note de provision',
        'avoir': 'Avoir'
    };
    return labels[type] || type || 'Non défini';
}

// Fonction pour obtenir le libellé du statut
function getStatusLabel(status) {
    const labels = {
        'payé': 'Payée',
        'non_payé': 'Non Payée'
    };
    return labels[status] || status || 'Non défini';
}

// Fonction pour obtenir la classe du badge de statut
function getStatusBadgeClass(status) {
    const classes = {
        'payé': 'badge-success',
        'non_payé': 'badge-warning'
    };
    return classes[status] || 'badge-secondary';
}

// Fonction pour afficher les erreurs de formulaire
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

// ============================
// VARIABLES GLOBALES
// ============================

var facturesTable;

// ============================
// INITIALISATION PRINCIPALE
// ============================

$(document).ready(function() {
    // Initialize Select2
    if ($.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "Sélectionnez un client",
            allowClear: true
        });
    }

    // ============================
    // GESTION DE LA MODALE DE CRÉATION
    // ============================
    
    // Auto-sélection du client basé sur le dossier
    $('#dossier_id').change(function() {
        var selectedOption = $(this).find('option:selected');
        var clientId = selectedOption.data('client-id');
        
        if (clientId) {
            $('#client_id').val(clientId).trigger('change');
        }
    });

    // Gestion de l'input file
    $('#piece_jointe').on('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            var fileName = file.name;
            var fileSize = (file.size / 1024 / 1024).toFixed(2);
            
            // Mettre à jour le label
            $('#piece_jointe_label').text(fileName);
            
            // Afficher l'aperçu
            $('#file_name').text(fileName + ' (' + fileSize + ' MB)');
            $('#file_preview').show();
            
            // Vérifier la taille du fichier
            if (file.size > 10 * 1024 * 1024) {
                showAlert('Le fichier est trop volumineux. Taille maximum: 10MB', 'warning');
                clearFileInput();
            }
        }
    });

    // Calcul automatique du montant TTC
    $('#montant_ht, #montant_tva').on('input', function() {
        calculateMontantTTC();
    });

    // Calcul initial
    calculateMontantTTC();

    // Formater les montants à la sortie des champs
    $('#montant_ht, #montant_tva').on('blur', function() {
        var value = parseFloat($(this).val()) || 0;
        $(this).val(value.toFixed(2));
        calculateMontantTTC();
    });

    // Soumission du formulaire avec AJAX
    $('#factureForm').submit(function(e) {
        e.preventDefault();
        
        // Vérification finale de la cohérence des montants
        var montantHT = parseFloat($('#montant_ht').val()) || 0;
        var montantTVA = parseFloat($('#montant_tva').val()) || 0;
        var montantTTC = parseFloat($('#montant').val()) || 0;
        var calculatedTTC = montantHT + montantTVA;
        
        if (Math.abs(calculatedTTC - montantTTC) > 0.01) {
            showAlert('Erreur : Le montant TTC doit être égal à HT + TVA.<br>HT: ' + montantHT.toFixed(2) + ' DT<br>TVA: ' + montantTVA.toFixed(2) + ' DT<br>TTC calculé: ' + calculatedTTC.toFixed(2) + ' DT<br>TTC saisi: ' + montantTTC.toFixed(2) + ' DT', 'danger');
            return false;
        }
        
        // Créer un FormData pour gérer le fichier
        var formData = new FormData(this);
        
        // Afficher un loader pendant la soumission
        var submitBtnSave = $('#submitBtnSave');
        var originalText = submitBtnSave.html();
        submitBtnSave.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Création en cours...');
        
        // Cacher les anciennes alertes et erreurs
        $('#ajaxAlert').hide().empty();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').html('');
        
        // Envoyer la requête AJAX
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Afficher le message de succès
                showAlert('success','Facture créée avec succès!', 'success');
                console.log('Facture créée avec succès:', response);
                
                // Réinitialiser le formulaire
                $('#factureForm')[0].reset();
                $('#montant').val('0.00');
                $('#montantAlert').hide();
                clearFileInput();
                
                // Réinitialiser le numéro
                
                // Réinitialiser Select2
                if ($.fn.select2) {
                    $('.select2').val(null).trigger('change');
                }
                
                // Recalculer le TTC
                calculateMontantTTC();
                
                // Rafraîchir la table si elle existe
                if (facturesTable) {
                    facturesTable.ajax.reload(null, false);
                }
                
                // Fermer le modal après un délai (optionnel)
                setTimeout(function() {
                    $('#factureModal').modal('hide');
                }, 2000);
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Erreurs de validation
                    var errors = xhr.responseJSON.errors;
                    showErrors(errors);
                    
                    // Afficher une alerte générale
                    showAlert('Veuillez corriger les erreurs dans le formulaire.', 'danger');
                } else if (xhr.status === 500) {
                    // Erreur serveur
                    showAlert('Une erreur serveur est survenue. Veuillez réessayer.', 'danger');
                } else {
                    // Autres erreurs
                    showAlert('Une erreur est survenue. Veuillez réessayer.', 'danger');
                }
                
                // Récupérer le message d'erreur général
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    showAlert(xhr.responseJSON.message, 'danger');
                }
            },
            complete: function() {
                // Réactiver le bouton
                submitBtnSave.prop('disabled', false).html(originalText);
            }
        });
    });

    // Reset form when modal is closed
    $('#factureModal').on('hidden.bs.modal', function () {
        // Réinitialiser le formulaire
        $('#factureForm')[0].reset();
        $('#montant').val('0.00');
        $('#montantAlert').hide();
        clearFileInput();
        $('#ajaxAlert').hide().empty();
        
        // Réinitialiser le numéro
       
        
        // Réinitialiser Select2
        if ($.fn.select2) {
            $('.select2').val(null).trigger('change');
        }
        
        // Supprimer les erreurs
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').html('');
        
        // Réactiver le bouton
        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Créer la facture');
        
        // Recalculer le TTC
        calculateMontantTTC();
    });

    // Réinitialiser le formulaire quand le modal s'ouvre
    $('#factureModal').on('show.bs.modal', function () {
        $('#ajaxAlert').hide().empty();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').html('');
    });

    // ============================
    // INITIALISATION DATATABLE AVEC AJAX
    // ============================
    
    if ($('#facturesTable').length) {
        facturesTable = $('#facturesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("dossier.factures.data", $dossier->id) }}',
                type: 'GET',
                error: function(xhr, error, thrown) {
                    console.error('Erreur DataTable:', error, thrown);
                    if (xhr.status === 403) {
                        Swal.fire({
                            title: 'Accès refusé',
                            text: 'Vous n\'avez pas la permission de voir les factures',
                            icon: 'error'
                        });
                    }
                }
            },
            columns: [
                { 
                    data: 'numero',
                    name: 'numero',
                    render: function(data, type, row) {
                        return data || 'N/A';
                    }
                },
                { 
                    data: 'date_emission',
                    name: 'date_emission',
                    render: function(data) {
                        return data || '';
                    }
                },
                { 
                    data: 'montant_ht',
                    name: 'montant_ht',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        if (type === 'display') {
                            return data;
                        }
                        // Pour le tri, retourner la valeur numérique
                        return parseFloat(data.match(/data-value="([^"]+)"/)?.[1] || 0);
                    }
                },
                { 
                    data: 'montant_tva',
                    name: 'montant_tva',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        if (type === 'display') {
                            return data;
                        }
                        return parseFloat(data.match(/data-value="([^"]+)"/)?.[1] || 0);
                    }
                },
                { 
                    data: 'montant',
                    name: 'montant',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        if (type === 'display') {
                            return data;
                        }
                        return parseFloat(data.match(/data-value="([^"]+)"/)?.[1] || 0);
                }
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
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/French.json'
            },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tous"]],
            order: [[1, 'desc']], // Tri par date d'émission décroissante
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            footerCallback: function (row, data, start, end, display) {
                setTimeout(calculateTotals, 100);
            },
            drawCallback: function() {
                calculateTotals();
                // Réattacher les événements après chaque redraw
                setTimeout(attachFactureEvents, 100);
            },
            initComplete: function() {
                calculateTotals();
                // Attacher les événements après l'initialisation
                setTimeout(attachFactureEvents, 100);
            }
        });

        // ============================
        // FONCTION POUR ATTACHER LES ÉVÉNEMENTS
        // ============================
        
        function attachFactureEvents() {
            // Bouton Voir
            $(document).off('click', '.view-facture-btn').on('click', '.view-facture-btn', function(e) {
                e.preventDefault();
                const factureId = $(this).data('facture-id');
                loadFactureDetails(factureId);
            });

            // Bouton Modifier
            $(document).off('click', '.edit-facture-btn').on('click', '.edit-facture-btn', function(e) {
                e.preventDefault();
                const factureId = $(this).data('facture-id');
                loadEditFactureForm(factureId);
            });

            // Bouton Supprimer
            $(document).off('click', '.delete-facture-btn').on('click', '.delete-facture-btn', function(e) {
                e.preventDefault();
                const factureId = $(this).data('facture-id');
                const factureNumero = $(this).data('facture-numero');
                deleteFacture(factureId, factureNumero);
            });
        }

        // ============================
        // CALCUL DES TOTAUX
        // ============================
        
        function calculateTotals() {
            var totalHT = 0;
            var totalTVA = 0;
            var totalMontant = 0;

            $('#facturesTable tbody tr').each(function() {
                // Extraire les valeurs numériques des spans avec data-value
                var htSpan = $(this).find('td:eq(2) span[data-value]');
                var tvaSpan = $(this).find('td:eq(3) span[data-value]');
                var montantSpan = $(this).find('td:eq(4) span[data-value]');
                
                var ht = parseFloat(htSpan.data('value')) || 0;
                var tva = parseFloat(tvaSpan.data('value')) || 0;
                var montant = parseFloat(montantSpan.data('value')) || 0;

                totalHT += ht;
                totalTVA += tva;
                totalMontant += montant;
            });

            $('#totalHT').text(totalHT.toFixed(2) + ' DT');
            $('#totalTVA').text(totalTVA.toFixed(2) + ' DT');
            $('#totalMontant').text(totalMontant.toFixed(2) + ' DT');
        }

        // Recalculer les totaux lors de la recherche
        facturesTable.on('search.dt', function() {
            calculateTotals();
        });
    }

    // ============================
    // FONCTIONS AJAX POUR LES ACTIONS
    // ============================
    
    // Fonction pour charger les détails d'une facture
    function loadFactureDetails(factureId) {
        $('#viewFactureModal').modal('show');
        
        $('#viewFactureModalBody').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-info" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
                <p class="mt-2 text-muted">Chargement des détails...</p>
            </div>
        `);

        $.ajax({
            url: '/dossiers/factures/' + factureId + '/datas',
            type: 'GET',
            success: function(response) {
                if (response.success && response.data) {
                    displayFactureView(response.data);
                } else {
                    showFactureViewError('Erreur lors du chargement des données.');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Une erreur est survenue lors du chargement.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                } else if (xhr.status === 403) {
                    errorMessage = 'Vous n\'avez pas la permission de voir cette facture';
                } else if (xhr.status === 404) {
                    errorMessage = 'Facture non trouvée';
                }
                showFactureViewError(errorMessage);
            }
        });
    }

    // Fonction pour afficher la vue détaillée
    function displayFactureView(facture) {
        const html = `
            <div class="facture-details">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="border-bottom pb-2">Informations générales</h5>
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Numéro:</th>
                                <td>${escapeHtml(facture.numero || 'N/A')}</td>
                            </tr>
                            <tr>
                                <th>Date d'émission:</th>
                                <td>${facture.date_emission ? new Date(facture.date_emission).toLocaleDateString('fr-FR') : 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>Type de pièce:</th>
                                <td>${escapeHtml(getTypePieceLabel(facture.type_piece))}</td>
                            </tr>
                            <tr>
                                <th>Statut:</th>
                                <td>
                                    <span class="badge ${getStatusBadgeClass(facture.statut)}">
                                        ${getStatusLabel(facture.statut)}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5 class="border-bottom pb-2">Montants</h5>
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Montant HT:</th>
                                <td class="text-success font-weight-bold">${formatCurrency(facture.montant_ht || 0)} DT</td>
                            </tr>
                            <tr>
                                <th>Montant TVA:</th>
                                <td class="text-success font-weight-bold">${formatCurrency(facture.montant_tva || 0)} DT</td>
                            </tr>
                            <tr>
                                <th>Montant Total:</th>
                                <td class="text-success font-weight-bold">${formatCurrency(facture.montant || 0)} DT</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="border-bottom pb-2">Document</h5>
                        ${facture.piece_jointe ? `
                            <div class="alert alert-light">
                                <i class="fas fa-paperclip mr-2"></i>
                                <strong>Fichier joint:</strong> ${escapeHtml(facture.piece_jointe.split('/').pop())}
                                <a href="/storage/${facture.piece_jointe}" class="btn btn-sm btn-success float-right" download>
                                    <i class="fas fa-download"></i> Télécharger
                                </a>
                            </div>
                        ` : `
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Aucun fichier joint
                            </div>
                        `}
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info text-dark">
                            <h6 class="alert-heading"><i class="fas fa-info-circle"></i> Informations de suivi</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <small>
                                        <strong>Créé le:</strong> ${formatDateTime(facture.created_at)}<br>
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small>
                                        <strong>Modifié le:</strong> ${formatDateTime(facture.updated_at)}<br>
                                        <strong>ID Facture:</strong> ${facture.id}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('#viewFactureModalBody').html(html);
    }

    // Fonction pour charger le formulaire d'édition
    function loadEditFactureForm(factureId) {
        $('#editFactureModal').modal('show');
        
        $('#editFactureModalBody').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-warning" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
                <p class="mt-2">Chargement du formulaire...</p>
            </div>
        `);

        $.ajax({
            url: '/dossiers/factures/' + factureId ,
            type: 'GET',
            success: function(response) {
                if (response.success && response.data) {
                    const formHtml = generateEditFactureFormHtml(response.data, response.dossiers);
                    $('#editFactureModalBody').html(formHtml);
                    initEditFactureForm(factureId);
                } else {
                    showEditFactureError('Erreur lors du chargement du formulaire');
                }
            },
            error: function(xhr) {
                console.error('Erreur:', xhr);
                let errorMessage = 'Erreur lors du chargement';
                
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                } else if (xhr.status === 403) {
                    errorMessage = 'Vous n\'avez pas la permission de modifier cette facture';
                } else if (xhr.status === 404) {
                    errorMessage = 'Facture non trouvée';
                }
                
                showEditFactureError(errorMessage);
            }
        });
    }

    // Fonction pour générer le formulaire HTML
    function generateEditFactureFormHtml(factureData, dossiers = []) {
        const escapeHtml = (text) => {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        };

        const formatDate = (dateString) => {
            if (!dateString) return '';
            return dateString.split('T')[0];
        };

        // Options pour le select des dossiers
        let dossierOptions = '';
        if (dossiers && dossiers.length > 0) {
            dossiers.forEach(function(dossier) {
                const selected = (factureData.dossier_id && dossier.id == factureData.dossier_id) ? 'selected' : '';
                dossierOptions += `<option value="${dossier.id}" ${selected}>${escapeHtml(dossier.numero_dossier)} - ${escapeHtml(dossier.nom_dossier)}</option>`;
            });
        }

        return `
            <form id="editFactureForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Type de pièce -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="type_piece">Type de pièce *</label>
                            <select class="form-control" id="type_piece" name="type_piece" required>
                                <option value="">Sélectionnez un type</option>
                                <option value="facture" ${factureData.type_piece === 'facture' ? 'selected' : ''}>Facture</option>
                                <option value="note_frais" ${factureData.type_piece === 'note_frais' ? 'selected' : ''}>Note de frais</option>
                                <option value="note_provision" ${factureData.type_piece === 'note_provision' ? 'selected' : ''}>Note de provision</option>
                                <option value="avoir" ${factureData.type_piece === 'avoir' ? 'selected' : ''}>Avoir</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Numéro -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="numero">Numéro *</label>
                            <input type="text" class="form-control" 
                                   id="numero" name="numero" 
                                   value="${escapeHtml(factureData.numero || '')}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Date émission -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_emission">Date d'émission *</label>
                            <input type="date" class="form-control" 
                                   id="date_emission" name="date_emission" 
                                   value="${formatDate(factureData.date_emission)}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Dossier -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="dossier_id">Dossier *</label>
                            <select class="form-control" id="dossier_id" name="dossier_id" required>
                                <option value="">Sélectionnez un dossier</option>
                                ${dossierOptions}
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Montant HT -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="montant_ht">Montant HT *</label>
                            <input type="number" step="0.01" class="form-control" 
                                   id="montant_ht" name="montant_ht" 
                                   value="${factureData.montant_ht || 0}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Montant TVA -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="montant_tva">Montant TVA *</label>
                            <input type="number" step="0.01" class="form-control" 
                                   id="montant_tva" name="montant_tva" 
                                   value="${factureData.montant_tva || 0}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Montant Total (calculé automatiquement) -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="montant">Montant Total</label>
                            <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                   id="montant" value="${(parseFloat(factureData.montant_ht) + parseFloat(factureData.montant_tva)).toFixed(2)}" readonly>
                            <input type="hidden" name="montant" id="hidden_montant" value="${factureData.montant || 0}">
                        </div>
                    </div>

                    <!-- Statut -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="statut">Statut</label>
                            <select class="form-control" id="statut" name="statut">
                                <option value="">Sélectionnez un statut</option>
                                <option value="payé" ${factureData.statut === 'payé' ? 'selected' : ''}>Payée</option>
                                <option value="non_payé" ${factureData.statut === 'non_payé' ? 'selected' : ''}>Non Payée</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Fichier joint -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="piece_jointe">Fichier joint</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" 
                                       id="piece_jointe" name="piece_jointe"
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                                <label class="custom-file-label" for="piece_jointe">
                                    ${factureData.piece_jointe ? escapeHtml(factureData.piece_jointe.split('/').pop()) : 'Choisir un fichier...'}
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Formats acceptés: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG (Max: 10MB)
                            </small>
                            
                            ${factureData.piece_jointe ? `
                            <div class="mt-2">
                                <div class="alert alert-info text-dark p-2">
                                    <i class="fas fa-paperclip mr-2"></i>
                                    Fichier actuel: 
                                    <a href="/storage/${factureData.piece_jointe}" target="_blank" class="ml-1 text-dark">
                                        ${escapeHtml(factureData.piece_jointe.split('/').pop())}
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger ml-2" id="removeFileBtn">
                                        <i class="fas fa-times"></i> Supprimer
                                    </button>
                                    <input type="hidden" name="remove_existing_file" id="removeExistingFile" value="0">
                                </div>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelEditBtn" data-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        `;
    }

    // Fonction pour initialiser le formulaire
    function initEditFactureForm(factureId) {
        // Calcul automatique du montant total
        function calculateMontantTotal() {
            const montantHT = parseFloat($('#montant_ht').val()) || 0;
            const montantTVA = parseFloat($('#montant_tva').val()) || 0;
            const montantTotal = montantHT + montantTVA;
            $('#montant').val(montantTotal.toFixed(2));
            $('#hidden_montant').val(montantTotal);
        }
        
        $('#montant_ht, #montant_tva').on('input', calculateMontantTotal);

        // Gestion du nom du fichier
        $('#piece_jointe').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });

        // Gestion du bouton de suppression de fichier
        $('#removeFileBtn').on('click', function() {
            $('#removeExistingFile').val('1');
            $(this).closest('.alert').fadeOut();
            showAlert('info', 'Le fichier sera supprimé lors de l\'enregistrement.');
        });

        // Soumission du formulaire
        $('#editFactureForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            updateFacture(factureId);
        });

        // Bouton d'annulation
        $('#cancelEditBtn').off('click').on('click', function() {
            $('#editFactureModal').modal('hide');
        });
    }

    // Fonction pour mettre à jour une facture
    function updateFacture(factureId) {
        const form = $('#editFactureForm');
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
            url: '/dossiers/factures/' + factureId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(response) {
                submitBtn.prop('disabled', false);
                submitBtn.html(originalText);
                
                if (response.success) {
                    $('#editFactureModal').modal('hide');
                    
                    // Recharger la DataTable
                    if (facturesTable) {
                        facturesTable.ajax.reload(null, false);
                    }
                    
                    showToastAlert('success', response.message || 'Facture modifiée avec succès');
                } else {
                    showFormErrors(form, response.errors || {});
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false);
                submitBtn.html(originalText);
                
                let errorMessage = 'Erreur lors de la modification de la facture';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    showFormErrors(form, xhr.responseJSON.errors);
                    return;
                }
                
                showToastAlert('danger', errorMessage);
            }
        });
    }

    // Fonction pour supprimer une facture
    function deleteFacture(factureId, factureNumero) {
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            html: `<div class="text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <p>Vous êtes sur le point de supprimer la facture :</p>
                <p class="font-weight-bold">"${escapeHtml(factureNumero)}"</p>
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
                    url: '/factures/' + factureId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        dossier_id: '{{ $dossier->id }}'
                    },
                    success: function(response) {
                        Swal.close();
                        
                        if (response.success) {
                            // Recharger la DataTable
                            if (facturesTable) {
                                facturesTable.ajax.reload(null, false);
                            }
                            
                            showToastAlert('success', response.message || 'Facture supprimée avec succès');
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
                        
                        let errorMessage = 'Erreur lors de la suppression de la facture';
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
    // FONCTIONS UTILITAIRES LOCALES
    // ============================
    
    function showFactureViewError(message) {
        $('#viewFactureModalBody').html(`
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

    function showEditFactureError(message) {
        $('#editFactureModalBody').html(`
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

    // Réinitialiser les modals quand ils sont fermés
    $('#viewFactureModal, #editFactureModal').on('hidden.bs.modal', function() {
        if ($(this).attr('id') === 'viewFactureModal') {
            $('#viewFactureModalBody').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-info" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement des détails...</p>
                </div>
            `);
        } else if ($(this).attr('id') === 'editFactureModal') {
            $('#editFactureModalBody').html(`
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