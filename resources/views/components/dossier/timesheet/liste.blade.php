<!-- Onglet Feuille de temps -->
<div class="tab-pane fade" id="timesheet" role="tabpanel" aria-labelledby="timesheet-tab">
    <div class="p-3">
        <!-- Alertes AJAX -->
        <div id="timesheetAjaxAlert" style="display: none;"></div>
        
        <div style="display: flow-root;">
            <h5 class="text-primary mb-3"><i class="fas fa-money-bill-wave"></i> Informations des feuilles de temps</h5>
            @if(auth()->user()->hasPermission('create_timesheets'))
            <a href="#" data-toggle="modal" data-target="#timesheetModal" class="btn btn-primary mb-3" style="float: right;">
                <i class="fas fa-plus"></i> Ajouter une feuille de temps 
            </a>
            @endif
        </div>

        <!-- Table avec DataTable -->
        <div class="table-responsive">
            <table id="timesheetsTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Utilisateur</th>
                        <th>Dossier</th>
                        <th>Catégorie</th>
                        <th>Type</th>
                        <th>Quantité</th>
                        <th>Prix</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Les données seront chargées via AJAX -->
                </tbody>
                <tfoot>
                    <tr class="bg-light font-weight-bold">
                        <td colspan="6" class="text-right">TOTAUX :</td>
                        <td id="totalQuantite">0</td>
                        <td id="totalPrix">0,00 DT</td>
                        <td id="totalMontant">0,00 DT</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Modal pour visualiser une feuille de temps -->
<div class="modal fade" id="viewTimesheetModal" tabindex="-1" role="dialog" aria-labelledby="viewTimesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="viewTimesheetModalLabel">Détails de la feuille de temps</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="viewTimesheetModalBody">
                <!-- Le contenu sera chargé via AJAX -->
                <div class="text-center py-5">
                    <div class="spinner-border text-info" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2 text-muted">Chargement des détails...</p>
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

<!-- Modal pour modifier une feuille de temps -->
<div class="modal fade" id="editTimesheetModal" tabindex="-1" role="dialog" aria-labelledby="editTimesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="editTimesheetModalLabel">Modifier la feuille de temps</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="editTimesheetModalBody">
                <!-- Le formulaire sera chargé via AJAX -->
                <div class="text-center py-5">
                    <div class="spinner-border text-warning" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2 text-muted">Chargement du formulaire...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Annuler
                </button>
                <button type="button" class="btn btn-warning" id="submitEditTimesheet">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation pour la suppression -->
<div class="modal fade" id="deleteTimesheetModal" tabindex="-1" role="dialog" aria-labelledby="deleteTimesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTimesheetModalLabel">Confirmation de suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <p>Êtes-vous sûr de vouloir supprimer cette feuille de temps ?</p>
                    <p class="font-weight-bold" id="deleteTimesheetDescription"></p>
                    <p class="text-danger"><strong>Cette action est irréversible !</strong></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Annuler
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteTimesheet">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    // ============================
    // VARIABLES GLOBALES
    // ============================
    var timesheetTable;
    var currentTimesheetId = null;
    let currentCategorieId = null;
    
    // ============================
    // FONCTIONS UTILITAIRES COMMUNES
    // ============================
    
    // Function to parse French number format with spaces
    function parseFrenchNumber(numberStr) {
        if (!numberStr) return 0;
        
        var cleanStr = numberStr.toString()
            .replace(' DT', '')
            .replace(/\s/g, '')  
            .replace(',', '.')   
            .trim();
            
        return parseFloat(cleanStr) || 0;
    }

    // Fonction pour formater les nombres en français
    function formatFrenchNumber(number, decimals = 2) {
        if (number === null || number === undefined) return '0,00 DT';
        
        return number.toFixed(decimals)
            .replace('.', ',')
            .replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' DT';
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

    // Fonction pour formater les dates
    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR');
    }

    // Fonction pour formater les dates pour input type="date"
    function formatDateForInput(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toISOString().split('T')[0];
    }

    // ============================
    // FONCTIONS DE MESSAGES ET ERREURS
    // ============================
    
    // Fonction pour afficher les alertes
    function showAlert(type, message, container = '#ajaxAlertContainer') {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;
        
        $(container).html(alertHtml);
        
        // Supprimer automatiquement après 5 secondes
        setTimeout(() => {
            $(container + ' .alert').alert('close');
        }, 5000);
    }

    // Fonction pour afficher une alerte
    function showTimesheetAlert(message, type = 'danger') {
        showAlert(type, message, '#timesheetAjaxAlert');
    }

    // Fonction pour afficher une alerte SweetAlert
    function showTimesheetToast(type, message) {
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
                title: message
            });
        } else {
            showTimesheetAlert(message, type);
        }
    }

    // Fonction pour effacer les erreurs de validation
    function clearValidationErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    }

    // Fonction pour afficher les erreurs de validation
    function showValidationErrors(errors) {
        clearValidationErrors();
        
        $.each(errors, function(field, messages) {
            const input = $(`[name="${field}"]`);
            const errorDiv = $(`#${field}_error`);
            
            if (input.length) {
                input.addClass('is-invalid');
                if (errorDiv.length) {
                    errorDiv.text(messages[0]);
                }
            }
        });
    }

    // Fonction pour afficher les erreurs de formulaire timesheet
    function showTimesheetFormErrors(errors) {
        $.each(errors, function(field, messages) {
            const $input = $('#' + field);
            const $errorDiv = $('#error_' + field);
            
            $input.addClass('is-invalid');
            if ($errorDiv.length) {
                $errorDiv.html(messages[0]);
            }
        });
    }

    // ============================
    // GESTION DES CATÉGORIES ET TYPES
    // ============================
    
    // Fonction pour charger les catégories
    function loadCategories() {
        $.ajax({
            url: '{{ route("categories.ajax") }}',
            type: 'GET',
            success: function(response) {
                const categorieSelect = $('#categorieList, #categorie_id');
                categorieSelect.each(function() {
                    const $select = $(this);
                    if ($select.attr('id') === 'categorieList' || !$select.val()) {
                        $select.empty().append('<option value="">Sélectionnez une catégorie</option>');
                        
                        response.forEach(function(categorie) {
                            $select.append(
                                `<option value="${categorie.id}">${escapeHtml(categorie.nom)}</option>`
                            );
                        });
                        
                        // Si une catégorie était sélectionnée avant le rechargement, la reselectionner
                        if (currentCategorieId && $select.attr('id') === 'categorieList') {
                            $select.val(currentCategorieId).trigger('change');
                        }
                    }
                });
            },
            error: function() {
                showAlert('danger', 'Erreur lors du chargement des catégories');
            }
        });
    }

    // Fonction pour charger les types d'une catégorie
    function loadTypes(categorieId, targetSelect = '#type') {
        if (!categorieId) {
            $(targetSelect).empty().append('<option value="">Sélectionnez d\'abord une catégorie</option>');
            return;
        }
        
        currentCategorieId = categorieId;
        
        $.ajax({
            url: `{{ url("categories") }}/${categorieId}/types`,
            type: 'GET',
            success: function(response) {
                const typeSelect = $(targetSelect);
                typeSelect.empty().append('<option value="">Sélectionnez un type (optionnel)</option>');
                
                if (response.length > 0) {
                    response.forEach(function(type) {
                        typeSelect.append(
                            `<option value="${type.id}">${escapeHtml(type.nom)}</option>`
                        );
                    });
                }
            },
            error: function() {
                showAlert('danger', 'Erreur lors du chargement des types');
            }
        });
    }

    // ============================
    // GESTION DU FORMULAIRE DE CRÉATION
    // ============================
    
    // Calcul automatique du total
    function calculateTotal() {
        const quantite = parseFloat($('#quantite').val()) || 0;
        const prix = parseFloat($('#prix').val()) || 0;
        const total = quantite * prix;
        
        $('#total_calcule').val(total.toFixed(2) + ' DT');
    }

    // Gérer la soumission du formulaire de création via AJAX
    $('#timesheetForm').on('submit', function(e) {
        e.preventDefault();
        
        // Désactiver le bouton de soumission
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Création en cours...');
        
        // Récupérer les données du formulaire
        const formData = new FormData(this);
        
        // Récupérer l'URL depuis l'attribut action du formulaire original
        const url = '{{ route("dossiers.timesheets.store", ["dossier" => $dossier->id]) }}';
        
        // Envoyer la requête AJAX
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Réactiver le bouton
                submitBtn.prop('disabled', false).html(originalText);
                
                if (response.success) {
                    // Afficher le message de succès
                    showAlert('success', response.message);
                    
                    // Réinitialiser le formulaire
                    $('#timesheetForm')[0].reset();
                    $('#total_calcule').val('0.00 DT');
                    loadTypes('');
                    
                    // Rafraîchir le tableau des feuilles de temps
                    if (timesheetTable) {
                        timesheetTable.ajax.reload(null, false);
                    }
                    
                    // Fermer le modal après 2 secondes
                    setTimeout(function() {
                        $('#timesheetModal').modal('hide');
                    }, 2000);
                }
            },
            error: function(xhr) {
                // Réactiver le bouton
                submitBtn.prop('disabled', false).html(originalText);
                
                if (xhr.status === 422) {
                    // Erreurs de validation
                    const errors = xhr.responseJSON.errors;
                    showValidationErrors(errors);
                    showAlert('danger', 'Veuillez corriger les erreurs dans le formulaire');
                } else {
                    // Autres erreurs
                    const message = xhr.responseJSON?.message || 'Une erreur est survenue lors de la création';
                    showAlert('danger', message);
                }
            }
        });
    });

    // ============================
    // DATATABLE DES FEUILLES DE TEMPS
    // ============================
    
    // Fonction pour calculer les totaux
    function calculateTimesheetTotals() {
        var totalQuantite = 0;
        var totalPrix = 0;
        var totalMontant = 0;

        $('#timesheetsTable tbody tr').each(function() {
            var quantiteData = $(this).find('td:eq(6)').data('value');
            var prixData = $(this).find('td:eq(7)').data('value');
            var montantData = $(this).find('td:eq(8)').data('value');
            
            var quantite = quantiteData !== undefined ? parseFloat(quantiteData) || 0 : parseFrenchNumber($(this).find('td:eq(6)').text());
            var prix = prixData !== undefined ? parseFloat(prixData) || 0 : parseFrenchNumber($(this).find('td:eq(7)').text());
            var montant = montantData !== undefined ? parseFloat(montantData) || 0 : parseFrenchNumber($(this).find('td:eq(8)').text());

            totalQuantite += quantite;
            totalPrix += prix;
            totalMontant += montant;
        });

        $('#totalQuantite').text(totalQuantite.toFixed(0));
        $('#totalPrix').text(formatFrenchNumber(totalPrix));
        $('#totalMontant').text(formatFrenchNumber(totalMontant));
    }

    // Initialiser la DataTable
    function initTimesheetDataTable() {
        if ($('#timesheetsTable').length) {
            timesheetTable = $('#timesheetsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("dossier.timesheets.data", $dossier->id) }}',
                    type: 'GET',
                    dataSrc: function (json) {
                        console.log('Données reçues du serveur:', json);
                        return json.data;
                    },
                    error: function(xhr, error, thrown) {
                        console.error('Erreur DataTable:', error, thrown);
                        if (xhr.status === 403) {
                            showTimesheetAlert('Vous n\'avez pas la permission de voir les feuilles de temps', 'danger');
                        }
                    }
                },
                columns: [
                    { 
                        data: 'date_timesheet',
                        name: 'date_timesheet',
                        render: function(data, type, row) {
                            if (!data) return '';
                            
                            try {
                                // If data is already a formatted date, return it
                                if (typeof data === 'string' && data.match(/\d{2}\/\d{2}\/\d{4}/)) {
                                    return data;
                                }
                                
                                // Parse the date
                                let dateObj;
                                
                                // Try parsing as ISO string first
                                if (data.includes('T')) {
                                    dateObj = new Date(data);
                                } else {
                                    // Try parsing as date string
                                    dateObj = new Date(data);
                                    
                                    // If that fails, check if it's a timestamp
                                    if (isNaN(dateObj.getTime()) && !isNaN(data)) {
                                        dateObj = new Date(parseInt(data));
                                    }
                                }
                                
                                // Check if date is valid (not 1970)
                                if (isNaN(dateObj.getTime()) || dateObj.getFullYear() === 1970) {
                                    console.warn('Invalid or 1970 date:', data, 'for row:', row.id);
                                    return data; // Return original data
                                }
                                
                                // Format as French date
                                return dateObj.toLocaleDateString('fr-FR', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric'
                                });
                            } catch (e) {
                                console.error('Error parsing date:', data, e);
                                return data;
                            }
                        }
                    },
                    { 
                        data: 'description',
                        name: 'description',
                        render: function(data) {
                            return data ? escapeHtml(data) : '';
                        }
                    },
                    { 
                        data: 'user.name',
                        name: 'user.name',
                        render: function(data) {
                            return data ? escapeHtml(data) : '';
                        }
                    },
                    { 
                        data: 'dossier.numero_dossier',
                        name: 'dossier.numero_dossier',
                        render: function(data) {
                            return data ? escapeHtml(data) : '';
                        }
                    },
                    { 
                        data: 'categorieRelation.nom',
                        name: 'categorieRelation.nom',
                        render: function(data) {
                            return data ? escapeHtml(data) : '';
                        }
                    },
                    { 
                        data: 'typeRelation.nom',
                        name: 'typeRelation.nom',
                        render: function(data) {
                            return data ? escapeHtml(data) : '';
                        }
                    },
                    { 
                        data: 'quantite',
                        name: 'quantite',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            var quantite = parseFloat(data) || 0;
                            return `<span data-value="${quantite}">${quantite}</span>`;
                        }
                    },
                    { 
                        data: 'prix',
                        name: 'prix',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            var prix = parseFloat(data) || 0;
                            return `<span data-value="${prix}">${formatFrenchNumber(prix)}</span>`;
                        }
                    },
                    { 
                        data: 'total',
                        name: 'total',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            var total = parseFloat(data) || 0;
                            return `<span data-value="${total}">${formatFrenchNumber(total)}</span>`;
                        }
                    },
                    { 
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            var html = '';
                            
                            // Vérifier les permissions
                            var hasViewPermission = {{ auth()->user()->hasPermission('view_timesheets') ? 'true' : 'false' }};
                            var hasEditPermission = {{ auth()->user()->hasPermission('edit_timesheets') ? 'true' : 'false' }};
                            var hasDeletePermission = {{ auth()->user()->hasPermission('delete_timesheets') ? 'true' : 'false' }};
                            var isAdmin = {{ auth()->user()->hasRole('admin') ? 'true' : 'false' }};
                            var isOwner = row.utilisateur_id == {{ auth()->id() }};
                            
                            // Bouton Voir
                            if (hasViewPermission) {
                                html += `<button type="button" class="btn btn-sm btn-info view-timesheet-btn mr-1"  style="padding: 0.3rem 0.3rem;"
                                        data-timesheet-id="${row.id}" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </button>`;
                            }
                            
                            // Bouton Modifier
                            if (hasEditPermission && (isAdmin || isOwner)) {
                                html += `<button type="button" class="btn btn-sm btn-warning edit-timesheet-btn mr-1" style="padding: 0.3rem 0.3rem;"
                                        data-timesheet-id="${row.id}" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>`;
                            }
                            
                            // Bouton Supprimer
                            if (hasDeletePermission && (isAdmin || isOwner)) {
                                var description = row.description ? escapeHtml(row.description) : 'N/A';
                                html += `<button type="button" class="btn btn-sm btn-danger delete-timesheet-btn" 
                                        data-timesheet-id="${row.id}" 
                                        data-timesheet-description="${description}" 
                                        title="Supprimer" style="padding: 0.3rem 0.3rem;">
                                    <i class="fas fa-trash"></i>
                                </button>`;
                            }
                            
                            return html || '<span class="text-muted">-</span>';
                        }
                    }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/French.json"
                },
                responsive: true,
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                pageLength: 10,
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                footerCallback: function () {
                    setTimeout(calculateTimesheetTotals, 100);
                },
                drawCallback: function() {
                    calculateTimesheetTotals();
                    attachTimesheetEvents();
                },
                initComplete: function() {
                    calculateTimesheetTotals();
                    attachTimesheetEvents();
                }
            });

            timesheetTable.on('search.dt draw', function() {
                setTimeout(calculateTimesheetTotals, 100);
            });
        }
    }

    // ============================
    // ÉVÉNEMENTS DES FEUILLES DE TEMPS
    // ============================
    
    // Fonction pour attacher les événements
    function attachTimesheetEvents() {
        // Bouton Voir
        $(document).off('click', '.view-timesheet-btn').on('click', '.view-timesheet-btn', function(e) {
            e.preventDefault();
            loadTimesheetDetails($(this).data('timesheet-id'));
        });

        // Bouton Modifier
        $(document).off('click', '.edit-timesheet-btn').on('click', '.edit-timesheet-btn', function(e) {
            e.preventDefault();
            loadEditTimesheetForm($(this).data('timesheet-id'));
        });

        // Bouton Supprimer
        $(document).off('click', '.delete-timesheet-btn').on('click', '.delete-timesheet-btn', function(e) {
            e.preventDefault();
            const $btn = $(this);
            showDeleteTimesheetModal($btn.data('timesheet-id'), $btn.data('timesheet-description'));
        });
    }

    // ============================
    // FONCTIONS AJAX POUR LES ACTIONS
    // ============================
    
    // Fonction pour charger les détails
    function loadTimesheetDetails(timesheetId) {
        $('#viewTimesheetModal').modal('show');
        $('#viewTimesheetModalBody').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-info" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
                <p class="mt-2 text-muted">Chargement des détails...</p>
            </div>
        `);

        $.ajax({
            url: '/time-sheets/' + timesheetId,
            type: 'GET',
            success: function(response) {
                if (response.success && response.data) {
                    displayTimesheetView(response.data);
                } else {
                    showTimesheetViewError('Erreur lors du chargement des données.');
                }
            },
            error: function(xhr) {
                handleAjaxError(xhr, 'voir', showTimesheetViewError);
            }
        });
    }

    // Fonction pour afficher la vue détaillée
    function displayTimesheetView(timesheet) {
        console.log('DEBUG - Detail view timesheet data:', timesheet);
        
        // Helper function to safely get values
        const getValue = (value, fallback = 'N/A') => {
            return value !== null && value !== undefined && value !== '' ? value : fallback;
        };
        
        // Format dates
        const formatDate = (dateString) => {
            if (!dateString) return 'N/A';
            try {
                const date = new Date(dateString);
                return isNaN(date.getTime()) ? dateString : date.toLocaleDateString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch (e) {
                return dateString;
            }
        };
        
        // Get all values
        const date = formatDate(timesheet.date_timesheet);
        const description = escapeHtml(getValue(timesheet.description));
        const userName = escapeHtml(getValue(timesheet.user?.name, timesheet.user_name));
        const userEmail = timesheet.user?.email ? `(${timesheet.user.email})` : '';
        const dossierNum = escapeHtml(getValue(timesheet.dossier?.numero_dossier, timesheet.numero_dossier));
        const dossierNom = escapeHtml(getValue(timesheet.dossier?.nom_dossier, timesheet.nom_dossier));
        const categorie = escapeHtml(getValue(timesheet.categorieRelation?.nom, timesheet.categorie_nom, timesheet.categorie));
        const type = escapeHtml(getValue(timesheet.typeRelation?.nom, timesheet.type_nom, timesheet.type));
        const quantite = getValue(timesheet.quantite, 0);
        const prix = getValue(timesheet.prix, 0);
        const total = getValue(timesheet.total, quantite * prix);
        const created_at = formatDate(timesheet.created_at);
        const updated_at = formatDate(timesheet.updated_at);
        
        const html = `
            <div class="timesheet-details">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="border-bottom pb-2 text-primary">
                            <i class="fas fa-info-circle"></i> Informations générales
                        </h5>
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">ID:</th>
                                <td><span class="badge badge-secondary">${timesheet.id}</span></td>
                            </tr>
                            <tr>
                                <th>Date:</th>
                                <td><span class="badge badge-info">${date}</span></td>
                            </tr>
                            <tr>
                                <th width="40%;">Description:</th>
                                <td>
                                    <div class="bg-light p-2 rounded">
                                        ${description}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Utilisateur:</th>
                                <td>
                                    ${userName}
                                    ${userEmail ? `<br><small class="text-muted">${userEmail}</small>` : ''}
                                    ${timesheet.user_id ? `<br><small class="text-muted">ID: ${timesheet.user_id}</small>` : ''}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <h5 class="border-bottom pb-2 text-primary">
                            <i class="fas fa-tasks"></i> Détails techniques
                        </h5>
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Dossier:</th>
                                <td>
                                    ${dossierNum}
                                    ${dossierNom !== 'N/A' ? `<br><small class="text-muted">${dossierNom}</small>` : ''}
                                    ${timesheet.dossier_id ? `<br><small class="text-muted">ID: ${timesheet.dossier_id}</small>` : ''}
                                </td>
                            </tr>
                            <tr>
                                <th>Catégorie:</th>
                                <td><span class="badge badge-warning">${categorie}</span></td>
                            </tr>
                            <tr>
                                <th>Type:</th>
                                <td><span class="badge badge-success">${type}</span></td>
                            </tr>
                            <tr>
                                <th>Quantité:</th>
                                <td><span class="font-weight-bold">${quantite}</span></td>
                            </tr>
                            <tr>
                                <th>Prix unitaire:</th>
                                <td class="text-success font-weight-bold">${formatFrenchNumber(prix)}</td>
                            </tr>
                            <tr>
                                <th>Total:</th>
                                <td class="text-success font-weight-bold" style="font-size: 1.1rem;">${formatFrenchNumber(total)}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h6 class="alert-heading text-dark">
                                <i class="fas fa-history"></i> Informations de suivi
                            </h6>
                            <div class="row">
                                <div class="col-md-6 text-dark">
                                    <small>
                                        <strong><i class="fas fa-plus-circle"></i> Créé le:</strong><br>
                                        ${created_at}
                                    </small>
                                </div>
                                <div class="col-md-6 text-dark">
                                    <small>
                                        <strong><i class="fas fa-edit"></i> Dernière modification:</strong><br>
                                        ${updated_at}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Fichier joint -->
                ${timesheet.file_path ? `
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-secondary">
                                <h6 class="alert-heading text-dark">
                                    <i class="fas fa-paperclip"></i> Fichier joint
                                </h6>
                                <p>
                                    <i class="fas fa-file"></i> 
                                    <a href="/storage/${timesheet.file_path}" target="_blank" class="ml-2 text-dark">
                                        ${timesheet.file_name || 'Télécharger le fichier'}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                ` : ''}
            </div>
        `;
        
        $('#viewTimesheetModalBody').html(html);
    }

    // Fonction pour charger le formulaire d'édition
    function loadEditTimesheetForm(timesheetId) {
        currentTimesheetId = timesheetId;
        $('#editTimesheetModal').modal('show');
        $('#editTimesheetModalBody').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-warning" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
                <p class="mt-2 text-muted">Chargement du formulaire...</p>
            </div>
        `);

        $.ajax({
            url: '/time-sheets/' + timesheetId + '/edit',
            type: 'GET',
            success: function(response) {
                console.log('Edit form response:', response);
                if (response.success && response.data) {
                    // Charger les catégories et types
                    loadCategoriesForEdit(response.data, response.categories, response.types);
                } else {
                    showEditTimesheetError('Erreur lors du chargement du formulaire');
                }
            },
            error: function(xhr) {
                console.error('Error loading edit form:', xhr);
                handleAjaxError(xhr, 'modifier', showEditTimesheetError);
            }
        });
    }

    // Fonction pour charger les catégories pour l'édition
    function loadCategoriesForEdit(timesheetData, categories = [], types = []) {
        // Si les catégories ne sont pas fournies, les charger via AJAX
        if (!categories || categories.length === 0) {
            $.ajax({
                url: '{{ route("categories.ajax") }}',
                type: 'GET',
                success: function(catResponse) {
                    // Charger les types si nécessaire
                    loadTypesForEdit(timesheetData, catResponse, types);
                },
                error: function() {
                    // Générer le formulaire avec les catégories vides
                    $('#editTimesheetModalBody').html(generateEditTimesheetFormHtml(
                        timesheetData, 
                        [], 
                        types
                    ));
                    initEditTimesheetForm();
                }
            });
        } else {
            loadTypesForEdit(timesheetData, categories, types);
        }
    }

    // Fonction pour charger les types pour l'édition
    function loadTypesForEdit(timesheetData, categories, types) {
        // Si les types ne sont pas fournis, les charger via AJAX
        if (!types || types.length === 0) {
            const categorieId = timesheetData.categorie_id || timesheetData.categorie;
            if (categorieId) {
                $.ajax({
                    url: `{{ url("categories") }}/${categorieId}/types`,
                    type: 'GET',
                    success: function(typeResponse) {
                        $('#editTimesheetModalBody').html(generateEditTimesheetFormHtml(
                            timesheetData, 
                            categories, 
                            typeResponse
                        ));
                        initEditTimesheetForm();
                    },
                    error: function() {
                        // Générer le formulaire avec les types vides
                        $('#editTimesheetModalBody').html(generateEditTimesheetFormHtml(
                            timesheetData, 
                            categories, 
                            []
                        ));
                        initEditTimesheetForm();
                    }
                });
            } else {
                $('#editTimesheetModalBody').html(generateEditTimesheetFormHtml(
                    timesheetData, 
                    categories, 
                    []
                ));
                initEditTimesheetForm();
            }
        } else {
            $('#editTimesheetModalBody').html(generateEditTimesheetFormHtml(
                timesheetData, 
                categories, 
                types
            ));
            initEditTimesheetForm();
        }
    }

    // Fonction pour générer le formulaire HTML
    function generateEditTimesheetFormHtml(timesheetData, categories = [], types = []) {
        console.log('Timesheet data for form:', timesheetData);
        
        // Get the date
        let dateValue = '';
        if (timesheetData.date_timesheet) {
            // If date is already in YYYY-MM-DD format
            if (timesheetData.date_timesheet.match(/^\d{4}-\d{2}-\d{2}$/)) {
                dateValue = timesheetData.date_timesheet;
            } else {
                // Parse and format
                const dateObj = new Date(timesheetData.date_timesheet);
                if (!isNaN(dateObj.getTime())) {
                    dateValue = dateObj.toISOString().split('T')[0];
                }
            }
        }
        
        // Get utilisateur_id
        const utilisateurId = timesheetData.utilisateur_id || timesheetData.user_id || '{{ auth()->id() }}';
        const userName = timesheetData.user_name || timesheetData.user?.name || 'Utilisateur inconnu';
        
        // Get description
        const descriptionValue = timesheetData.description || '';
        
        // Get categorie and type IDs
        const categorieId = timesheetData.categorie_id || timesheetData.categorie || timesheetData.categorieRelation?.id || '';
        const typeId = timesheetData.type_id || timesheetData.type || timesheetData.typeRelation?.id || '';

        // Options pour les catégories
        let categorieOptions = '<option value="">Sélectionnez une catégorie</option>';
        if (categories && categories.length > 0) {
            categories.forEach(function(categorie) {
                const selected = categorie.id == categorieId ? 'selected' : '';
                categorieOptions += `<option value="${categorie.id}" ${selected}>${escapeHtml(categorie.nom)}</option>`;
            });
        } else {
            // Si pas de catégories chargées, montrer au moins la catégorie actuelle
            if (categorieId) {
                categorieOptions += `<option value="${categorieId}" selected>Catégorie ${categorieId}</option>`;
            }
        }

        // Options pour les types
        let typeOptions = '<option value="">Sélectionnez un type (optionnel)</option>';
        if (types && types.length > 0) {
            types.forEach(function(type) {
                const selected = type.id == typeId ? 'selected' : '';
                typeOptions += `<option value="${type.id}" ${selected}>${escapeHtml(type.nom)}</option>`;
            });
        } else {
            // Si pas de types chargés, montrer au moins le type actuel
            if (typeId) {
                typeOptions += `<option value="${typeId}" selected>Type ${typeId}</option>`;
            }
        }

        return `
            <form id="editTimesheetForm">
                <div class="row">
                    <!-- Date -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_timesheet">Date *</label>
                            <input type="date" class="form-control" id="date_timesheet" name="date_timesheet" 
                                   value="${dateValue}" required>
                            <div class="invalid-feedback" id="error_date_timesheet"></div>
                        </div>
                    </div>

                    <!-- Utilisateur -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user">Utilisateur *</label>
                            <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                   value="${escapeHtml(userName)}" readonly>
                            <input type="hidden" name="utilisateur_id" id="utilisateur_id" value="${utilisateurId}">
                            <div class="invalid-feedback" id="error_utilisateur_id"></div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required>${escapeHtml(descriptionValue)}</textarea>
                            <div class="invalid-feedback" id="error_description"></div>
                        </div>
                    </div>

                    <!-- Catégorie -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="categorie_id">Catégorie *</label>
                            <select class="form-control" id="categorie_id" name="categorie_id" required>
                                ${categorieOptions}
                            </select>
                            <div class="invalid-feedback" id="error_categorie_id"></div>
                        </div>
                    </div>

                    <!-- Type -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="type_id">Type</label>
                            <select class="form-control" id="type_id" name="type_id">
                                ${typeOptions}
                            </select>
                            <div class="invalid-feedback" id="error_type_id"></div>
                        </div>
                    </div>

                    <!-- Quantité -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quantite">Quantité *</label>
                            <input type="number" step="0.01" class="form-control" 
                                   id="quantite" name="quantite" 
                                   value="${timesheetData.quantite || 0}" required>
                            <div class="invalid-feedback" id="error_quantite"></div>
                        </div>
                    </div>

                    <!-- Prix -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="prix">Prix unitaire *</label>
                            <input type="number" step="0.01" class="form-control" 
                                   id="prix" name="prix" 
                                   value="${timesheetData.prix || 0}" required>
                            <div class="invalid-feedback" id="error_prix"></div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="total">Total</label>
                            <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                   id="total" value="${formatFrenchNumber((parseFloat(timesheetData.quantite) || 0) * (parseFloat(timesheetData.prix) || 0))}" readonly>
                        </div>
                    </div>
                </div>
            </form>
        `;
    }

    // Fonction pour initialiser le formulaire d'édition
    function initEditTimesheetForm() {
        // Calcul automatique du total
        function calculateTotal() {
            const quantite = parseFloat($('#quantite').val()) || 0;
            const prix = parseFloat($('#prix').val()) || 0;
            $('#total').val(formatFrenchNumber(quantite * prix));
        }
        
        $('#quantite, #prix').on('input', calculateTotal);
        
        // Gérer le changement de catégorie pour charger les types
        $('#categorie_id').change(function() {
            const categorieId = $(this).val();
            loadTypes(categorieId, '#type_id');
        });
    }

    // Fonction pour mettre à jour une feuille de temps
    function updateTimesheet() {
        // Get form values
        const dateToSend = $('#date_timesheet').val();
        
        // Get utilisateur_id
        let utilisateurId = $('#utilisateur_id').val();
        console.log('DEBUG - utilisateur_id from form input:', utilisateurId);
        
        // If it's empty, use a fallback
        if (!utilisateurId || utilisateurId === '') {
            console.warn('DEBUG - utilisateur_id is empty! Using fallback.');
            utilisateurId = '{{ auth()->id() }}';
            $('#utilisateur_id').val(utilisateurId);
        }
        
        // Get description
        let description = $('#description').val();
        if (!description || description.trim() === '') {
            description = 'Modification effectuée';
            $('#description').val(description);
        }
        
        const formData = {
            date_timesheet: dateToSend,
            description: description,
            categorie_id: $('#categorie_id').val(),
            type_id: $('#type_id').val(),
            utilisateur_id: utilisateurId,
            quantite: parseFloat($('#quantite').val()) || 0,
            prix: parseFloat($('#prix').val()) || 0,
            dossier_id: {{ $dossier->id }},
            _token: '{{ csrf_token() }}',
            _method: 'PUT'
        };
        
        console.log('DEBUG - Final form data to send:', formData);
        
        // Validate required fields
        const requiredFields = {
            'utilisateur_id': 'Utilisateur',
            'description': 'Description',
            'categorie_id': 'Catégorie',
            'quantite': 'Quantité',
            'prix': 'Prix'
        };
        
        const missingFields = [];
        for (const [field, label] of Object.entries(requiredFields)) {
            if (!formData[field] || formData[field] === '') {
                missingFields.push(label);
            }
        }
        
        if (missingFields.length > 0) {
            showTimesheetToast('danger', 'Champs obligatoires manquants: ' + missingFields.join(', '));
            
            // Highlight missing fields
            missingFields.forEach(fieldLabel => {
                const fieldName = Object.keys(requiredFields).find(key => requiredFields[key] === fieldLabel);
                if (fieldName) {
                    $(`#${fieldName}`).addClass('is-invalid');
                    $(`#error_${fieldName}`).text(`Le champ ${fieldLabel} est requis`);
                }
            });
            
            return;
        }
        
        const $submitBtn = $('#submitEditTimesheet');
        const originalText = $submitBtn.html();
        
        $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Enregistrement...');
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').empty();
        
        $.ajax({
            url: '/dossiers/time-sheets/' + currentTimesheetId,
            type: 'POST',
            data: formData,
            success: function(response) {
                $submitBtn.prop('disabled', false).html(originalText);
                
                if (response.success) {
                    $('#editTimesheetModal').modal('hide');
                    if (timesheetTable) timesheetTable.ajax.reload(null, false);
                    showTimesheetToast('success', response.message || 'Feuille de temps modifiée avec succès');
                } else {
                    if (response.errors) {
                        showTimesheetFormErrors(response.errors);
                    } else {
                        showTimesheetToast('danger', response.message || 'Erreur inconnue');
                    }
                }
            },
            error: function(xhr) {
                $submitBtn.prop('disabled', false).html(originalText);
                
                console.error('Update error response:', xhr.responseJSON);
                console.error('Update error status:', xhr.status);
                
                if (xhr.status === 422) {
                    // Laravel validation errors
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        showTimesheetFormErrors(xhr.responseJSON.errors);
                        return;
                    }
                }
                
                let errorMessage = 'Erreur lors de la modification';
                if (xhr.responseJSON?.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showTimesheetToast('danger', errorMessage);
            }
        });
    }

    // Fonction pour gérer les erreurs AJAX
    function handleAjaxError(xhr, action, errorCallback) {
        let errorMessage = `Erreur lors du chargement pour ${action}`;
        
        if (xhr.responseJSON?.error) {
            errorMessage = xhr.responseJSON.error;
        } else if (xhr.status === 403) {
            errorMessage = `Vous n'avez pas la permission de ${action} cette feuille de temps`;
        } else if (xhr.status === 404) {
            errorMessage = 'Feuille de temps non trouvée';
        }
        
        if (errorCallback) errorCallback(errorMessage);
    }

    // Fonction pour afficher la modal de suppression
    function showDeleteTimesheetModal(timesheetId, timesheetDescription) {
        currentTimesheetId = timesheetId;
        $('#deleteTimesheetDescription').text(timesheetDescription || 'N/A');
        $('#deleteTimesheetModal').modal('show');
    }

    // Fonction pour supprimer une feuille de temps
    function deleteTimesheet() {
        const $deleteBtn = $('#confirmDeleteTimesheet');
        const originalText = $deleteBtn.html();
        
        $deleteBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Suppression...');
        
        $.ajax({
            url: '/time-sheets/' + currentTimesheetId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}',
                dossier_id: '{{ $dossier->id }}'
            },
            success: function(response) {
                $deleteBtn.prop('disabled', false).html(originalText);
                
                if (response.success) {
                    $('#deleteTimesheetModal').modal('hide');
                    if (timesheetTable) timesheetTable.ajax.reload(null, false);
                    showTimesheetToast('success', response.message || 'Feuille de temps supprimée avec succès');
                } else {
                    showTimesheetToast('danger', response.message || 'Erreur lors de la suppression');
                }
            },
            error: function(xhr) {
                $deleteBtn.prop('disabled', false).html(originalText);
                
                let errorMessage = 'Erreur lors de la suppression';
                if (xhr.responseJSON?.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showTimesheetToast('danger', errorMessage);
            }
        });
    }

    // ============================
    // FONCTIONS D'ERREUR LOCALES
    // ============================
    
    function showTimesheetViewError(message) {
        $('#viewTimesheetModalBody').html(`
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

    function showEditTimesheetError(message) {
        $('#editTimesheetModalBody').html(`
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
    // INITIALISATION DES ÉVÉNEMENTS
    // ============================
    
    function initEvents() {
        // Gestion du changement de catégorie dans le formulaire de création
        $('#categorieList').change(function() {
            const categorieId = $(this).val();
            loadTypes(categorieId);
        });

        // Écouter les changements sur quantité et prix
        $('#quantite, #prix').on('input', function() {
            calculateTotal();
        });

        // Confirmer la suppression
        $('#confirmDeleteTimesheet').on('click', deleteTimesheet);

        // Soumission du formulaire d'édition
        $('#submitEditTimesheet').on('click', function() {
            if (currentTimesheetId) updateTimesheet();
        });

        // Réinitialiser les modals quand ils sont fermés
        $('#viewTimesheetModal').on('hidden.bs.modal', function() {
            $('#viewTimesheetModalBody').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-info" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement des détails...</p>
                </div>
            `);
        });

        $('#editTimesheetModal').on('hidden.bs.modal', function() {
            currentTimesheetId = null;
            $('#editTimesheetModalBody').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-warning" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement du formulaire...</p>
                </div>
            `);
            $('#submitEditTimesheet').prop('disabled', false).html('<i class="fas fa-save"></i> Enregistrer');
        });

        $('#deleteTimesheetModal').on('hidden.bs.modal', function() {
            currentTimesheetId = null;
            $('#confirmDeleteTimesheet').prop('disabled', false).html('<i class="fas fa-trash"></i> Supprimer');
        });

        // Reset form when modal is closed (création)
        $('#timesheetModal').on('hidden.bs.modal', function () {
            clearValidationErrors();
            $('#ajaxAlertContainer').empty();
            $('#timesheetForm')[0].reset();
            $('#total_calcule').val('0.00 DT');
            $('#type').empty().append('<option value="">Sélectionnez d\'abord une catégorie</option>');
            currentCategorieId = null;
            
            // Réinitialiser le bouton
            $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Créer la feuille de temps');
        });
    }

    // ============================
    // INITIALISATION
    // ============================
    
    // Auto-sélection de l'utilisateur connecté si c'est un avocat/secrétaire
    @if(auth()->user()->fonction === 'avocat' || auth()->user()->fonction === 'secrétaire')
        $('#utilisateur_id').val('{{ auth()->id() }}').trigger('change');
    @endif

    // Initialisation des composants
    loadCategories();
    calculateTotal();
    initTimesheetDataTable();
    initEvents();
    
    // Initialiser les événements des boutons
    setTimeout(attachTimesheetEvents, 500);
});
</script>