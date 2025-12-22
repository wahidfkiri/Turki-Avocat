<!-- Modal pour créer une feuille de temps -->
<div class="modal fade" id="timesheetModal" tabindex="-1" role="dialog" aria-labelledby="timesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="timesheetModalLabel">
                    <i class="fas fa-plus"></i> Ajouter une feuille de temps
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="timesheetForm">
                <div class="modal-body">
                    <div id="ajaxAlertContainer"></div>
                    
                    <!-- Date -->
                    <div class="form-group">
                        <label for="date_timesheet">Date *</label>
                        <input type="date" class="form-control" id="date_timesheet" name="date_timesheet" value="{{ date('Y-m-d') }}" required>
                        <div class="invalid-feedback" id="error_date_timesheet"></div>
                    </div>
                    
                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description de la tâche..." required></textarea>
                        <div class="invalid-feedback" id="error_description"></div>
                    </div>
                    
                    <!-- Utilisateur -->
                    <div class="form-group">
                        <label for="utilisateur_id">Utilisateur *</label>
                        <select class="form-control" id="utilisateur_id" name="utilisateur_id" required>
                            <option value="">Sélectionnez un utilisateur</option>
                            @foreach($users ?? [] as $user)
                                <option value="{{ $user->id }}" {{ auth()->id() == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="error_utilisateur_id"></div>
                    </div>
                    
                    <!-- Catégorie -->
                    <div class="form-group">
                        <label for="categorieList">Catégorie *</label>
                        <select class="form-control" id="categorieList" name="categorie_id" required>
                            <option value="">Sélectionnez une catégorie</option>
                            <!-- Les options seront chargées via AJAX -->
                        </select>
                        <div class="invalid-feedback" id="error_categorie_id"></div>
                    </div>
                    
                    <!-- Type -->
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control" id="type" name="type_id">
                            <option value="">Sélectionnez d'abord une catégorie</option>
                        </select>
                        <div class="invalid-feedback" id="error_type_id"></div>
                    </div>
                    
                    <!-- Quantité et Prix -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantite">Quantité *</label>
                                <input type="number" step="0.01" min="0.01" class="form-control" id="quantite" name="quantite" value="1" required>
                                <div class="invalid-feedback" id="error_quantite"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="prix">Prix unitaire (DT) *</label>
                                <input type="number" step="0.01" min="0.01" class="form-control" id="prix" name="prix" value="0" required>
                                <div class="invalid-feedback" id="error_prix"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total calculé -->
                    <div class="form-group">
                        <label>Total calculé</label>
                        <input type="text" class="form-control-plaintext bg-light p-2 rounded" id="total_calcule" value="0,00 DT" readonly>
                    </div>
                    
                    <!-- Fichier -->
                    <div class="form-group">
                        <label for="file">Fichier (optionnel)</label>
                        <input type="file" class="form-control-file" id="file" name="file">
                        <small class="form-text text-muted">Formats acceptés: PDF, Word, Excel, Images (max: 10MB)</small>
                        <div class="invalid-feedback" id="error_file"></div>
                    </div>
                    
                    <!-- Champ caché pour le dossier_id -->
                    <input type="hidden" name="dossier_id" value="{{ $dossier->id }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Créer la feuille de temps
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
    
    // Variables pour les routes avec le dossier actuel
    window.dossierId = {{ $dossier->id }};
    window.createTimesheetUrl = '{{ route("time-sheets.store") }}'; // Route globale
    window.timesheetsDataUrl = '{{ route("dossier.timesheets.data", $dossier) }}'; // Data spécifique au dossier
    
    // ============================
    // FONCTIONS UTILITAIRES
    // ============================
    
    function parseFrenchNumber(numberStr) {
        if (!numberStr) return 0;
        
        var cleanStr = numberStr.toString()
            .replace(' DT', '')
            .replace(/\s/g, '')  
            .replace(',', '.')   
            .trim();
            
        return parseFloat(cleanStr) || 0;
    }

    function formatFrenchNumber(number, decimals = 2) {
        if (number === null || number === undefined) return '0,00 DT';
        
        return number.toFixed(decimals)
            .replace('.', ',')
            .replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' DT';
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

    // ============================
    // FONCTIONS DE MESSAGES
    // ============================
    
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
        
        $(container).html(alertHtml).show();
        
        setTimeout(() => {
            $(container + ' .alert').alert('close');
        }, 5000);
    }

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
            showAlert(message, type, '#timesheetAjaxAlert');
        }
    }

    function clearValidationErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    }

    function showValidationErrors(errors) {
        clearValidationErrors();
        
        $.each(errors, function(field, messages) {
            const input = $(`[name="${field}"]`);
            const errorDiv = $(`#error_${field}`);
            
            if (input.length) {
                input.addClass('is-invalid');
                if (errorDiv.length) {
                    errorDiv.text(messages[0]);
                }
            }
        });
    }

    // ============================
    // GESTION DES CATÉGORIES ET TYPES
    // ============================
    
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
    
    function calculateTotal() {
        const quantite = parseFloat($('#quantite').val()) || 0;
        const prix = parseFloat($('#prix').val()) || 0;
        const total = quantite * prix;
        
        $('#total_calcule').val(formatFrenchNumber(total));
    }

    // Gérer la soumission du formulaire de création via AJAX
    $('#timesheetForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Création en cours...');
        
        // Récupérer les données du formulaire
        const formData = new FormData(this);
        
        // Ajouter le token CSRF
        formData.append('_token', '{{ csrf_token() }}');
        
        console.log('Création de feuille de temps pour le dossier:', window.dossierId);
        console.log('URL:', window.createTimesheetUrl);
        
        // Envoyer la requête AJAX
        $.ajax({
            url: window.createTimesheetUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                submitBtn.prop('disabled', false).html(originalText);
                
                console.log('Réponse:', response);
                
                if (response.success) {
                    // Afficher message de succès
                    showAlert('success', response.message, '#ajaxAlertContainer');
                    
                    // Réinitialiser le formulaire
                    $('#timesheetForm')[0].reset();
                    $('#date_timesheet').val('{{ date("Y-m-d") }}');
                    $('#total_calcule').val('0,00 DT');
                    $('#type').empty().append('<option value="">Sélectionnez d\'abord une catégorie</option>');
                    currentCategorieId = null;
                    
                    // Rafraîchir SEULEMENT le DataTable
                    if (timesheetTable) {
                        timesheetTable.ajax.reload(null, false);
                        showTimesheetToast('success', 'Feuille de temps créée avec succès');
                    }
                    
                    // Fermer le modal après 1.5 secondes
                    setTimeout(function() {
                        $('#timesheetModal').modal('hide');
                        $('#ajaxAlertContainer').empty();
                    }, 1500);
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalText);
                
                console.error('Erreur:', xhr);
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    showValidationErrors(errors);
                    showAlert('danger', 'Veuillez corriger les erreurs', '#ajaxAlertContainer');
                } else if (xhr.status === 403) {
                    showAlert('danger', 'Permission refusée', '#ajaxAlertContainer');
                } else {
                    const message = xhr.responseJSON?.message || 'Erreur lors de la création';
                    showAlert('danger', message, '#ajaxAlertContainer');
                }
            }
        });
    });

    // ============================
    // DATATABLE DES FEUILLES DE TEMPS
    // ============================
    
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

    function initTimesheetDataTable() {
        if ($('#timesheetsTable').length) {
            timesheetTable = $('#timesheetsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: window.timesheetsDataUrl,
                    type: 'GET',
                    dataSrc: function (json) {
                        return json.data;
                    },
                    error: function(xhr, error, thrown) {
                        console.error('Erreur DataTable:', error, thrown);
                        if (xhr.status === 403) {
                            showTimesheetToast('danger', 'Vous n\'avez pas la permission de voir les feuilles de temps');
                        }
                    }
                },
                columns: [
                    { 
                        data: 'date_timesheet',
                        name: 'date_timesheet',
                        render: function(data) {
                            if (!data) return '';
                            try {
                                const date = new Date(data);
                                return isNaN(date.getTime()) ? data : date.toLocaleDateString('fr-FR');
                            } catch (e) {
                                return data;
                            }
                        }
                    },
                    { 
                        data: 'description',
                        name: 'description',
                        render: escapeHtml
                    },
                    { 
                        data: 'user.name',
                        name: 'user.name',
                        render: escapeHtml
                    },
                    { 
                        data: 'dossier.numero_dossier',
                        name: 'dossier.numero_dossier',
                        render: escapeHtml
                    },
                    { 
                        data: 'categorieRelation.nom',
                        name: 'categorieRelation.nom',
                        render: escapeHtml
                    },
                    { 
                        data: 'typeRelation.nom',
                        name: 'typeRelation.nom',
                        render: escapeHtml
                    },
                    { 
                        data: 'quantite',
                        name: 'quantite',
                        render: function(data) {
                            var quantite = parseFloat(data) || 0;
                            return `<span data-value="${quantite}">${quantite}</span>`;
                        }
                    },
                    { 
                        data: 'prix',
                        name: 'prix',
                        render: function(data) {
                            var prix = parseFloat(data) || 0;
                            return `<span data-value="${prix}">${formatFrenchNumber(prix)}</span>`;
                        }
                    },
                    { 
                        data: 'total',
                        name: 'total',
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
                            
                            var hasViewPermission = {{ auth()->user()->hasPermission('view_timesheets') ? 'true' : 'false' }};
                            var hasEditPermission = {{ auth()->user()->hasPermission('edit_timesheets') ? 'true' : 'false' }};
                            var hasDeletePermission = {{ auth()->user()->hasPermission('delete_timesheets') ? 'true' : 'false' }};
                            var isAdmin = {{ auth()->user()->hasRole('admin') ? 'true' : 'false' }};
                            var isOwner = row.utilisateur_id == {{ auth()->id() }};
                            
                            if (hasViewPermission) {
                                html += `<button type="button" class="btn btn-sm btn-info view-timesheet-btn mr-1" style="padding: 0.3rem 0.3rem;"
                                        data-timesheet-id="${row.id}" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </button>`;
                            }
                            
                            if (hasEditPermission && (isAdmin || isOwner)) {
                                html += `<button type="button" class="btn btn-sm btn-warning edit-timesheet-btn mr-1" style="padding: 0.3rem 0.3rem;"
                                        data-timesheet-id="${row.id}" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>`;
                            }
                            
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
    
    function attachTimesheetEvents() {
        $(document).off('click', '.view-timesheet-btn').on('click', '.view-timesheet-btn', function(e) {
            e.preventDefault();
            loadTimesheetDetails($(this).data('timesheet-id'));
        });

        $(document).off('click', '.edit-timesheet-btn').on('click', '.edit-timesheet-btn', function(e) {
            e.preventDefault();
            loadEditTimesheetForm($(this).data('timesheet-id'));
        });

        $(document).off('click', '.delete-timesheet-btn').on('click', '.delete-timesheet-btn', function(e) {
            e.preventDefault();
            const $btn = $(this);
            showDeleteTimesheetModal($btn.data('timesheet-id'), $btn.data('timesheet-description'));
        });
    }

    // ============================
    // FONCTIONS AJAX POUR LES ACTIONS
    // ============================
    
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
            url: '{{ route("time-sheets.details.ajax", ":id") }}'.replace(':id', timesheetId),
            type: 'GET',
            success: function(response) {
                if (response.success && response.timesheet) {
                    displayTimesheetView(response.timesheet);
                } else {
                    showTimesheetViewError('Erreur lors du chargement des données.');
                }
            },
            error: function(xhr) {
                showTimesheetViewError(xhr.responseJSON?.message || 'Erreur de chargement');
            }
        });
    }

    function displayTimesheetView(timesheet) {
        const html = `
            <div class="timesheet-details">
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="border-bottom pb-2 text-primary">
                            <i class="fas fa-info-circle"></i> Informations générales
                        </h5>
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Date:</th>
                                <td><span class="badge badge-info">${timesheet.date_timesheet || 'N/A'}</span></td>
                            </tr>
                            <tr>
                                <th width="40%;">Description:</th>
                                <td><div class="bg-light p-2 rounded">${escapeHtml(timesheet.description || 'N/A')}</div></td>
                            </tr>
                            <tr>
                                <th>Utilisateur:</th>
                                <td>${escapeHtml(timesheet.user?.name || 'N/A')}</td>
                            </tr>
                            <tr>
                                <th>Dossier:</th>
                                <td>${escapeHtml(timesheet.dossier?.numero_dossier || 'N/A')}</td>
                            </tr>
                            <tr>
                                <th>Catégorie:</th>
                                <td><span class="badge badge-warning">${escapeHtml(timesheet.categorie || 'N/A')}</span></td>
                            </tr>
                            <tr>
                                <th>Type:</th>
                                <td><span class="badge badge-success">${escapeHtml(timesheet.type || 'N/A')}</span></td>
                            </tr>
                            <tr>
                                <th>Quantité:</th>
                                <td><span class="font-weight-bold">${timesheet.quantite || 0}</span></td>
                            </tr>
                            <tr>
                                <th>Prix unitaire:</th>
                                <td class="text-success font-weight-bold">${timesheet.prix || '0,00 DT'}</td>
                            </tr>
                            <tr>
                                <th>Total:</th>
                                <td class="text-success font-weight-bold" style="font-size: 1.1rem;">${timesheet.total || '0,00 DT'}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        `;
        
        $('#viewTimesheetModalBody').html(html);
    }

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
            url: '{{ route("time-sheets.edit", ":id") }}'.replace(':id', timesheetId),
            type: 'GET',
            data: { ajax: true },
            success: function(response) {
                if (response.success && response.data) {
                    loadCategoriesForEdit(response.data, response.categories, response.types);
                } else {
                    showEditTimesheetError('Erreur lors du chargement du formulaire');
                }
            },
            error: function(xhr) {
                showEditTimesheetError(xhr.responseJSON?.message || 'Erreur de chargement');
            }
        });
    }

    function loadCategoriesForEdit(timesheetData, categories = [], types = []) {
        if (!categories || categories.length === 0) {
            $.ajax({
                url: '{{ route("categories.ajax") }}',
                type: 'GET',
                success: function(catResponse) {
                    loadTypesForEdit(timesheetData, catResponse, types);
                },
                error: function() {
                    $('#editTimesheetModalBody').html(generateEditTimesheetFormHtml(timesheetData, [], types));
                    initEditTimesheetForm();
                }
            });
        } else {
            loadTypesForEdit(timesheetData, categories, types);
        }
    }

    function loadTypesForEdit(timesheetData, categories, types) {
        const categorieId = timesheetData.categorie_id || timesheetData.categorie;
        if (!types || types.length === 0) {
            if (categorieId) {
                $.ajax({
                    url: `{{ url("categories") }}/${categorieId}/types`,
                    type: 'GET',
                    success: function(typeResponse) {
                        $('#editTimesheetModalBody').html(generateEditTimesheetFormHtml(timesheetData, categories, typeResponse));
                        initEditTimesheetForm();
                    },
                    error: function() {
                        $('#editTimesheetModalBody').html(generateEditTimesheetFormHtml(timesheetData, categories, []));
                        initEditTimesheetForm();
                    }
                });
            } else {
                $('#editTimesheetModalBody').html(generateEditTimesheetFormHtml(timesheetData, categories, []));
                initEditTimesheetForm();
            }
        } else {
            $('#editTimesheetModalBody').html(generateEditTimesheetFormHtml(timesheetData, categories, types));
            initEditTimesheetForm();
        }
    }

    function generateEditTimesheetFormHtml(timesheetData, categories = [], types = []) {
        // Format date for input
        let dateValue = '';
        if (timesheetData.date_timesheet) {
            const dateObj = new Date(timesheetData.date_timesheet);
            if (!isNaN(dateObj.getTime())) {
                dateValue = dateObj.toISOString().split('T')[0];
            }
        }
        
        const utilisateurId = timesheetData.utilisateur_id || timesheetData.user_id || '{{ auth()->id() }}';
        const userName = timesheetData.user_name || timesheetData.user?.name || 'Utilisateur inconnu';
        const descriptionValue = timesheetData.description || '';
        const categorieId = timesheetData.categorie_id || timesheetData.categorie || '';
        const typeId = timesheetData.type_id || timesheetData.type || '';
        const quantiteValue = timesheetData.quantite || 0;
        const prixValue = timesheetData.prix || 0;

        // Catégories options
        let categorieOptions = '<option value="">Sélectionnez une catégorie</option>';
        if (categories && categories.length > 0) {
            categories.forEach(function(categorie) {
                const selected = categorie.id == categorieId ? 'selected' : '';
                categorieOptions += `<option value="${categorie.id}" ${selected}>${escapeHtml(categorie.nom)}</option>`;
            });
        }

        // Types options
        let typeOptions = '<option value="">Sélectionnez un type (optionnel)</option>';
        if (types && types.length > 0) {
            types.forEach(function(type) {
                const selected = type.id == typeId ? 'selected' : '';
                typeOptions += `<option value="${type.id}" ${selected}>${escapeHtml(type.nom)}</option>`;
            });
        }

        return `
            <form id="editTimesheetForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="dossier_id" value="${window.dossierId}">
                
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
                            <label>Utilisateur *</label>
                            <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                   value="${escapeHtml(userName)}" readonly>
                            <input type="hidden" name="utilisateur_id" id="utilisateur_id" value="${utilisateurId}">
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
                            <input type="number" step="0.01" min="0.01" class="form-control" 
                                   id="quantite" name="quantite" 
                                   value="${quantiteValue}" required>
                            <div class="invalid-feedback" id="error_quantite"></div>
                        </div>
                    </div>

                    <!-- Prix -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="prix">Prix unitaire (DT) *</label>
                            <input type="number" step="0.01" min="0.01" class="form-control" 
                                   id="prix" name="prix" 
                                   value="${prixValue}" required>
                            <div class="invalid-feedback" id="error_prix"></div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Total calculé</label>
                            <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                   id="total" value="${formatFrenchNumber(quantiteValue * prixValue)}" readonly>
                        </div>
                    </div>
                </div>
            </form>
        `;
    }

    function initEditTimesheetForm() {
        // Calcul automatique du total
        function calculateEditTotal() {
            const quantite = parseFloat($('#quantite').val()) || 0;
            const prix = parseFloat($('#prix').val()) || 0;
            $('#total').val(formatFrenchNumber(quantite * prix));
        }
        
        $('#quantite, #prix').on('input', calculateEditTotal);
        
        // Gérer le changement de catégorie pour charger les types
        $('#categorie_id').change(function() {
            const categorieId = $(this).val();
            if (categorieId) {
                $.ajax({
                    url: `{{ url("categories") }}/${categorieId}/types`,
                    type: 'GET',
                    success: function(response) {
                        const typeSelect = $('#type_id');
                        typeSelect.empty().append('<option value="">Sélectionnez un type (optionnel)</option>');
                        
                        if (response.length > 0) {
                            response.forEach(function(type) {
                                typeSelect.append(
                                    `<option value="${type.id}">${escapeHtml(type.nom)}</option>`
                                );
                            });
                        }
                    }
                });
            }
        });
    }

    // FONCTION POUR LA MISE À JOUR
    function updateTimesheet() {
        // Récupérer les valeurs du formulaire
        const formData = {
            date_timesheet: $('#date_timesheet').val(),
            description: $('#description').val(),
            utilisateur_id: $('#utilisateur_id').val(),
            categorie_id: $('#categorie_id').val(),
            type_id: $('#type_id').val() || null,
            quantite: $('#quantite').val(),
            prix: $('#prix').val(),
            dossier_id: window.dossierId,
            _token: '{{ csrf_token() }}',
            _method: 'PUT'
        };

        console.log('Données envoyées pour mise à jour:', formData);

        // Validation basique côté client
        const requiredFields = ['date_timesheet', 'description', 'utilisateur_id', 'categorie_id', 'quantite', 'prix'];
        let isValid = true;
        
        requiredFields.forEach(field => {
            const value = formData[field];
            const $input = $(`#${field}`);
            const $error = $(`#error_${field}`);
            
            if (!value || value === '') {
                $input.addClass('is-invalid');
                $error.text('Ce champ est requis');
                isValid = false;
            } else if ((field === 'quantite' || field === 'prix') && parseFloat(value) <= 0) {
                $input.addClass('is-invalid');
                $error.text('La valeur doit être supérieure à 0');
                isValid = false;
            } else {
                $input.removeClass('is-invalid');
                $error.text('');
            }
        });

        if (!isValid) {
            showTimesheetToast('danger', 'Veuillez corriger les erreurs dans le formulaire');
            return;
        }

        const $submitBtn = $('#submitEditTimesheet');
        const originalText = $submitBtn.html();
        
        $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Enregistrement...');

        // URL de mise à jour - Utiliser la route globale avec le dossier_id dans les données
        const updateUrl = '{{ route("time-sheets.update", ":id") }}'.replace(':id', currentTimesheetId);
        
        $.ajax({
            url: updateUrl,
            type: 'POST', // Laravel accepte POST avec _method: 'PUT'
            data: formData,
            success: function(response) {
                $submitBtn.prop('disabled', false).html(originalText);
                
                console.log('Réponse mise à jour:', response);
                
                if (response.success) {
                    $('#editTimesheetModal').modal('hide');
                    // Rafraîchir SEULEMENT le DataTable
                    if (timesheetTable) {
                        timesheetTable.ajax.reload(null, false);
                        showTimesheetToast('success', response.message || 'Feuille de temps modifiée avec succès');
                    }
                } else {
                    if (response.errors) {
                        // Afficher les erreurs de validation
                        $.each(response.errors, function(field, messages) {
                            const $input = $(`#${field}`);
                            const $error = $(`#error_${field}`);
                            if ($input.length) {
                                $input.addClass('is-invalid');
                                if ($error.length) {
                                    $error.text(messages[0]);
                                }
                            }
                        });
                        showTimesheetToast('danger', 'Veuillez corriger les erreurs');
                    } else {
                        showTimesheetToast('danger', response.message || 'Erreur lors de la modification');
                    }
                }
            },
            error: function(xhr) {
                $submitBtn.prop('disabled', false).html(originalText);
                
                console.error('Erreur mise à jour:', xhr);
                
                if (xhr.status === 422) {
                    // Erreurs de validation Laravel
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        const $input = $(`#${field}`);
                        const $error = $(`#error_${field}`);
                        if ($input.length) {
                            $input.addClass('is-invalid');
                            if ($error.length) {
                                $error.text(messages[0]);
                            }
                        }
                    });
                    showTimesheetToast('danger', 'Veuillez corriger les erreurs de validation');
                } else {
                    const message = xhr.responseJSON?.message || 'Erreur lors de la modification';
                    showTimesheetToast('danger', message);
                }
            }
        });
    }

    function showDeleteTimesheetModal(timesheetId, timesheetDescription) {
        currentTimesheetId = timesheetId;
        $('#deleteTimesheetDescription').text(timesheetDescription || 'N/A');
        $('#deleteTimesheetModal').modal('show');
    }

    function deleteTimesheet() {
        const $deleteBtn = $('#confirmDeleteTimesheet');
        const originalText = $deleteBtn.html();
        
        $deleteBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Suppression...');
        
        // URL de suppression - Utiliser la route globale
        const deleteUrl = '{{ route("time-sheets.destroy", ":id") }}'.replace(':id', currentTimesheetId);
        
        $.ajax({
            url: deleteUrl,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}',
                dossier_id: window.dossierId
            },
            success: function(response) {
                $deleteBtn.prop('disabled', false).html(originalText);
                
                if (response.success) {
                    $('#deleteTimesheetModal').modal('hide');
                    // Rafraîchir SEULEMENT le DataTable
                    if (timesheetTable) {
                        timesheetTable.ajax.reload(null, false);
                        showTimesheetToast('success', response.message || 'Feuille de temps supprimée avec succès');
                    }
                } else {
                    showTimesheetToast('danger', response.message || 'Erreur lors de la suppression');
                }
            },
            error: function(xhr) {
                $deleteBtn.prop('disabled', false).html(originalText);
                
                const message = xhr.responseJSON?.message || 'Erreur lors de la suppression';
                showTimesheetToast('danger', message);
            }
        });
    }

    // ============================
    // FONCTIONS D'ERREUR
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
        $('#quantite, #prix').on('input', calculateTotal);

        // Confirmer la suppression
        $('#confirmDeleteTimesheet').on('click', deleteTimesheet);

        // Soumission du formulaire d'édition
        $('#submitEditTimesheet').on('click', updateTimesheet);

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
        });

        $('#deleteTimesheetModal').on('hidden.bs.modal', function() {
            currentTimesheetId = null;
        });

        // Reset form when modal is closed (création)
        $('#timesheetModal').on('hidden.bs.modal', function () {
            clearValidationErrors();
            $('#ajaxAlertContainer').empty();
            $('#timesheetForm')[0].reset();
            $('#date_timesheet').val('{{ date("Y-m-d") }}');
            $('#total_calcule').val('0,00 DT');
            $('#type').empty().append('<option value="">Sélectionnez d\'abord une catégorie</option>');
            currentCategorieId = null;
            
            $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Créer la feuille de temps');
        });
    }

    // ============================
    // INITIALISATION
    // ============================
    
    // Initialisation
    loadCategories();
    calculateTotal();
    initTimesheetDataTable();
    initEvents();
    
    // Initialiser les événements des boutons
    setTimeout(attachTimesheetEvents, 500);
});
</script>