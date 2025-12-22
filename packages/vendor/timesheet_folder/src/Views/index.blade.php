<!-- Modal de création -->
<div class="modal fade" id="createTimesheetModal" tabindex="-1" role="dialog" aria-labelledby="createTimesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createTimesheetModalLabel">
                    <i class="fas fa-plus"></i> Nouvelle feuille de temps
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createTimesheetForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div id="timesheetAlertContainer"></div>
                    <div class="row">
                        <div class="col-md-4">
                    <!-- Date -->
                    <div class="form-group">
                        <label for="date_timesheet">Date *</label>
                        <input type="date" class="form-control" id="date_timesheet" name="date_timesheet" 
                               value="{{ date('Y-m-d') }}" required>
                        <div class="invalid-feedback" id="error_date_timesheet"></div>
                    </div>
                        </div>
                        <div class="col-md-4">
                    <!-- Dossier -->
                    <div class="form-group">
                        <label for="date_timesheet">Dossier *</label>
                        <input type="text" class="form-control"
                               value="{{ $dossier->numero_dossier }}" disabled>
                    </div>
                        </div>
                        <div class="col-md-4">
                    <!-- Utilisateur -->
                    <div class="form-group">
                        <label for="utilisateur_id">Utilisateur *</label>
                        <select class="form-control" id="utilisateur_id" name="utilisateur_id" required>
                            <option value="">Sélectionnez un utilisateur</option>
                            @foreach(\App\Models\User::where('is_active', true)->orderBy('name')->get() as $user)
                                <option value="{{ $user->id }}" {{ auth()->id() == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="error_utilisateur_id"></div>
                    </div>
                    </div>
                   </div>
                   <div class="row">
                    <div class="col-md-6">
                    <!-- Catégorie -->
                    <div class="form-group">
                        <label for="categorie_id">Catégorie *</label>
                        <select class="form-control" id="categorie_id" name="categorie" required>
                            <option value="">Sélectionnez une catégorie</option>
                            <!-- Chargé via AJAX -->
                        </select>
                        <div class="invalid-feedback" id="error_categorie_id"></div>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <!-- Type -->
                    <div class="form-group">
                        <label for="type_id">Type</label>
                        <select class="form-control" id="type_id" name="type">
                            <option value="">Sélectionnez d'abord une catégorie</option>
                        </select>
                        <div class="invalid-feedback" id="error_type_id"></div>
                    </div>
                    </div>
                   </div>
                    <input type="hidden" name="dossier_id" value="{{$dossier->id}}">  
                    <!-- Quantité et Prix -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantite">Quantité *</label>
                                <input type="number" step="0.01" min="0.01" class="form-control" 
                                       id="quantite" name="quantite" value="1" required>
                                <div class="invalid-feedback" id="error_quantite"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="prix">Prix unitaire (DT) *</label>
                                <input type="number" step="0.01" min="0.01" class="form-control" 
                                       id="prix" name="prix" value="0" required>
                                <div class="invalid-feedback" id="error_prix"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                    <!-- Total calculé -->
                    <div class="form-group">
                        <label>Total calculé</label>
                        <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                               id="total_calcule" value="0,00 DT" readonly>
                    </div>
                        </div>
                    </div>

                    
                    <!-- Fichier (Optionnel) -->
                    <div class="form-group">
                        <label for="file">Fichier joint (optionnel)</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="file" name="file">
                            <label class="custom-file-label" for="file">Choisir un fichier...</label>
                        </div>
                        <small class="form-text text-muted">
                            Formats acceptés: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG (Max: 10MB)
                        </small>
                        <div class="invalid-feedback" id="error_file"></div>
                    </div>

                    
                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea class="form-control" id="description" name="description" rows="3" 
                                  placeholder="Description de la tâche..." required></textarea>
                        <div class="invalid-feedback" id="error_description"></div>
                    </div>
                    
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitCreateBtn">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de visualisation -->
<div class="modal fade" id="viewTimesheetModal" tabindex="-1" role="dialog" aria-labelledby="viewTimesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header bg-primary">
            <h5 class="modal-title text-white" id="showTimesheetModalLabel">
                <i class="fas fa-eye"></i> Détails de la Feuille de Temps
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div id="show-modal-content">
                <!-- Section de chargement - visible par défaut -->
                <div class="text-center p-4" id="show-loading-section">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">Chargement des détails...</p>
                </div>
                
                <!-- Section des détails - cachée par défaut -->
                <div id="show-details-section" style="display: none;">
                    <!-- Le contenu sera injecté ici via AJAX -->
                </div>
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

<!-- Modal d'édition -->
<div class="modal fade" id="editTimesheetModal" tabindex="-1" role="dialog" aria-labelledby="editTimesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="editTimesheetModalLabel">
                    <i class="fas fa-edit"></i> Modifier la feuille de temps
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editTimesheetForm" enctype="multipart/form-data">
                <div class="modal-body" id="editTimesheetContent">
                    <!-- Chargé via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-warning" id="submitEditBtn">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteTimesheetModal" tabindex="-1" role="dialog" aria-labelledby="deleteTimesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteTimesheetModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Confirmation
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette feuille de temps ?</p>
                <p class="font-weight-bold" id="deleteTimesheetDescription"></p>
                <p class="text-danger"><small>Cette action est irréversible !</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Annuler
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Onglet Feuille de temps -->
<div class="tab-pane fade" id="timesheet" role="tabpanel" aria-labelledby="timesheet-tab">
    <div class="p-3">
        <!-- Alertes générales -->
        <div id="mainAlert" style="display: none;"></div>
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="text-primary mb-0">
                <i class="fas fa-money-bill-wave"></i> Feuilles de temps
            </h5>
            @if(auth()->user()->hasPermission('create_timesheets'))
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTimesheetModal">
                    <i class="fas fa-plus"></i> Ajouter
                </button>
            @endif
        </div>
        
        <!-- Table DataTable -->
        <div class="table-responsive">
            <table id="timesheetsTable" class="table table-bordered table-hover w-100">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Utilisateur</th>
                        <th>Catégorie</th>
                        <th>Type</th>
                        <th>Quantité</th>
                        <th>Prix</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Chargé via AJAX -->
                </tbody>
                <tfoot>
                    <tr class="bg-light font-weight-bold">
                        <td colspan="5" class="text-right">Totaux :</td>
                        <td id="totalQuantity">0</td>
                        <td id="totalPrice">0,00 DT</td>
                        <td id="totalAmount">0,00 DT</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    // Variables globales
    let timesheetTable;
    let currentTimesheetId = null;
    let currentDossierId = {{ $dossier->id }};
    
    // URLs des routes - CORRIGÉES
    // Utilise les routes Laravel générées dans Blade
    const urls = {
        dataTable: '{{ route("dossiers.timesheets.data", $dossier) }}',
        store: '{{ route("dossiers.timesheets.store", $dossier) }}',
        update: (id) => '{{ route("dossiers.timesheets.update", [$dossier, "TIMESHEET_ID"]) }}'.replace('TIMESHEET_ID', id),
        destroy: (id) => '{{ route("dossiers.timesheets.destroy", [$dossier, "TIMESHEET_ID"]) }}'.replace('TIMESHEET_ID', id),
        editData: (id) => '{{ route("dossiers.timesheets.edit", [$dossier, "TIMESHEET_ID"]) }}'.replace('TIMESHEET_ID', id),
        details: (id) => '{{ route("dossiers.timesheets.details", [$dossier, "TIMESHEET_ID"]) }}'.replace('TIMESHEET_ID', id),
        categories: '{{ route("categories.ajax") }}',
        types: (id) => '{{ route("categories.types", ["CATEGORIE_ID"]) }}'.replace('CATEGORIE_ID', id)
    };
    
    // ==================== FONCTIONS UTILITAIRES ====================
    
    function formatNumber(number, decimals = 2) {
        if (number === null || number === undefined) return '0,00';
        return number.toFixed(decimals).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    }
    
    function formatCurrency(number) {
        return formatNumber(number) + ' DT';
    }
    
    function parseNumber(str) {
        if (!str) return 0;
        return parseFloat(str.replace(' DT', '').replace(/\s/g, '').replace(',', '.')) || 0;
    }
    
    function showAlert(type, message, container = '#mainAlert') {
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
    
    function showToast(type, message) {
        if (typeof Swal !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            
            Toast.fire({
                icon: type,
                title: message
            });
        } else {
            showAlert(type, message);
        }
    }
    
    function clearFormErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    }
    
    function showFormErrors(errors) {
        clearFormErrors();
        $.each(errors, function(field, messages) {
            const $input = $(`[name="${field}"]`);
            const $error = $(`#error_${field}`);
            if ($input.length) {
                $input.addClass('is-invalid');
                if ($error.length) {
                    $error.text(messages[0]);
                }
            }
        });
    }
    
    // ==================== GESTION DES CATÉGORIES/TYPES ====================
    
    function loadCategories() {
        $.ajax({
            url: urls.categories,
            type: 'GET',
            success: function(categories) {
                const $select = $('#categorie_id');
                $select.empty().append('<option value="">Sélectionnez une catégorie</option>');
                categories.forEach(function(categorie) {
                    $select.append(`<option value="${categorie.id}">${categorie.nom}</option>`);
                });
            },
            error: function() {
                console.error('Erreur lors du chargement des catégories');
            }
        });
    }
    
    function loadTypes(categorieId) {
        if (!categorieId) {
            $('#type_id').empty().append('<option value="">Sélectionnez d\'abord une catégorie</option>');
            return;
        }
        
        $.ajax({
            url: urls.types(categorieId),
            type: 'GET',
            success: function(types) {
                const $select = $('#type_id');
                $select.empty().append('<option value="">Sélectionnez un type (optionnel)</option>');
                types.forEach(function(type) {
                    $select.append(`<option value="${type.id}">${type.nom}</option>`);
                });
            },
            error: function() {
                console.error('Erreur lors du chargement des types');
            }
        });
    }
    
    // ==================== GESTION DU FORMULAIRE DE CRÉATION ====================
    
    function calculateTotal() {
        const quantite = parseFloat($('#quantite').val()) || 0;
        const prix = parseFloat($('#prix').val()) || 0;
        const total = quantite * prix;
        $('#total_calcule').val(formatCurrency(total));
    }
    
    $('#categorie_id').change(function() {
        loadTypes($(this).val());
    });
    
    $('#quantite, #prix').on('input', calculateTotal);
    
    // Gestion du nom du fichier dans l'input file
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
    
    // Soumission du formulaire de création - URL CORRIGÉE
    $('#createTimesheetForm').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $submitBtn = $('#submitCreateBtn');
        const originalText = $submitBtn.html();
        
        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Création...');
        clearFormErrors();
        
        const formData = new FormData(this);
        // Ajouter le token CSRF
        formData.append('_token', '{{ csrf_token() }}');
        
        // Utiliser l'URL correcte
        $.ajax({
            url: urls.store, // Cette URL est maintenant correcte
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $submitBtn.prop('disabled', false).html(originalText);
                
                if (response.success) {
                    // Réinitialiser le formulaire
                    $form[0].reset();
                    $('.custom-file-label').html('Choisir un fichier...');
                    $('#total_calcule').val('0,00 DT');
                    $('#type_id').empty().append('<option value="">Sélectionnez d\'abord une catégorie</option>');
                    
                    // Fermer le modal
                    $('#createTimesheetModal').modal('hide');
                    
                    // Rafraîchir la table
                    timesheetTable.ajax.reload(null, false);
                    
                    // Afficher message
                    showToast('success', response.message);
                }
            },
            error: function(xhr) {
                $submitBtn.prop('disabled', false).html(originalText);
                
                if (xhr.status === 422) {
                    showFormErrors(xhr.responseJSON.errors);
                    showAlert('danger', 'Veuillez corriger les erreurs dans le formulaire', '#timesheetAlertContainer');
                } else {
                    const errorMsg = xhr.responseJSON?.message || xhr.responseJSON?.error || 'Erreur lors de la création';
                    showAlert('danger', errorMsg, '#timesheetAlertContainer');
                }
            }
        });
    });
    
    // ==================== DATATABLE ====================
    
    function calculateTotals() {
        let totalQuantite = 0;
        let totalPrix = 0;
        let totalMontant = 0;
        
        $('#timesheetsTable tbody tr').each(function() {
            const quantite = parseNumber($(this).find('td:eq(5)').text());
            const prix = parseNumber($(this).find('td:eq(6)').text());
            const montant = parseNumber($(this).find('td:eq(7)').text());
            
            totalQuantite += quantite;
            totalPrix += prix;
            totalMontant += montant;
        });
        
        $('#totalQuantity').text(formatNumber(totalQuantite, 0));
        $('#totalPrice').text(formatCurrency(totalPrix));
        $('#totalAmount').text(formatCurrency(totalMontant));
    }
    
    function initDataTable() {
        timesheetTable = $('#timesheetsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: urls.dataTable,
                type: 'GET'
            },
            columns: [
                { data: 'date_timesheet', name: 'date_timesheet' },
                { data: 'description', name: 'description' },
                { data: 'user.name', name: 'user.name' },
                { 
                    data: 'categorieRelation.nom', 
                    name: 'categorieRelation.nom',
                    render: function(data) {
                        return data ? data : 'N/A';
                    }
                },
                { 
                    data: 'typeRelation.nom', 
                    name: 'typeRelation.nom',
                    render: function(data) {
                        return data ? data : 'N/A';
                    }
                },
                { 
                    data: 'quantite', 
                    name: 'quantite',
                    render: function(data) {
                        return formatNumber(parseFloat(data));
                    }
                },
                { 
                    data: 'prix', 
                    name: 'prix',
                    render: function(data) {
                        return formatCurrency(parseFloat(data));
                    }
                },
                { 
                    data: 'total', 
                    name: 'total',
                    render: function(data) {
                        return formatCurrency(parseFloat(data));
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
                calculateTotals();
                attachTableEvents();
            },
            initComplete: function() {
                calculateTotals();
                attachTableEvents();
            }
        });
    }
    
    // ==================== ÉVÉNEMENTS DE LA TABLE ====================
    
    function attachTableEvents() {
        // Voir les détails
        $(document).off('click', '.view-timesheet-btn').on('click', '.view-timesheet-btn', function() {
            const timesheetId = $(this).data('id');
            viewTimesheet(timesheetId);
        });
        
        // Modifier
        $(document).off('click', '.edit-timesheet-btn').on('click', '.edit-timesheet-btn', function() {
            const timesheetId = $(this).data('id');
            editTimesheet(timesheetId);
        });
        
        // Supprimer
        $(document).off('click', '.delete-timesheet-btn').on('click', '.delete-timesheet-btn', function() {
            const timesheetId = $(this).data('id');
            const description = $(this).data('description');
            confirmDeleteTimesheet(timesheetId, description);
        });
    }
    
    // ==================== FONCTIONS DES ACTIONS ====================
    
    function viewTimesheet(timesheetId) {
        $('#viewTimesheetContent').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-info" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
                <p class="mt-2">Chargement des détails...</p>
            </div>
        `);
        
        $('#viewTimesheetModal').modal('show');
        
        // Utiliser l'URL correcte
        $.ajax({
    url: urls.details(timesheetId),
    type: 'GET',
    beforeSend: function() {
        // Cacher la section des détails, montrer le chargement
        $('#show-details-section').hide();
        $('#show-loading-section').show();
    },
    success: function(response) {
        if (response.success) {
            const data = response.data;
            
            // Construction du HTML pour la section des détails
            let fileHtml = '';
            if (data.file_path) {
                fileHtml = `
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0 section-title">Pièce jointe</h6>
                                </div>
                                <div class="card-body">
                                    <a href="${data.file_path}" target="_blank" class="btn btn-outline-secondary">
                                        <i class="fas fa-download"></i> ${data.file_name}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // Déterminer la classe du badge selon le statut
            let statusBadgeClass = 'badge-secondary';
            if (data.statut === 'Actif') {
                statusBadgeClass = 'badge-success';
            } else if (data.statut === 'Inactif') {
                statusBadgeClass = 'badge-danger';
            }
            
            const html = `
                <div class="container-fluid">
                    <div class="row">
                        <!-- Informations principales -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0 section-title">Informations principales</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered mb-0">
                                        <tbody>
                                            <tr>
                                                <th>Date</th>
                                                <td>${data.date}</td>
                                            </tr>
                                            <tr>
                                                <th>Utilisateur</th>
                                                <td>
                                                    <strong>${data.user_name || data.user}</strong>
                                                    <br><small class="text-muted">${data.user_role || ''}</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Dossier</th>
                                                <td>
                                                    ${data.dossier_link ? `
                                                        <a href="${data.dossier_link}" target="_blank" class="text-primary">
                                                            <i class="fas fa-eye"></i> ${data.dossier_number || 'Voir dossier'}
                                                        </a>
                                                        <br><small class="text-muted">${data.dossier_name || ''}</small>
                                                    ` : data.dossier || 'Non spécifié'}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Détails financiers -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0 section-title">Détails financiers</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered mb-0">
                                        <tbody>
                                            <tr>
                                                <th>Quantité</th>
                                                <td>${data.quantite}</td>
                                            </tr>
                                            <tr>
                                                <th>Prix unitaire</th>
                                                <td>${data.prix}</td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <td class="font-weight-bold text-success" style="font-size: 1.2em;">
                                                    ${data.total}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <!-- Catégorie et Type -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0 section-title">Classification</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered mb-0">
                                        <tbody>
                                            <tr>
                                                <th>Catégorie</th>
                                                <td>${data.categorie}</td>
                                            </tr>
                                            <tr>
                                                <th>Type</th>
                                                <td>${data.type}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Métadonnées -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0 section-title">Métadonnées</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered mb-0">
                                        <tbody>
                                            <tr>
                                                <th>Créé le</th>
                                                <td>${data.created_at}</td>
                                            </tr>
                                            <tr>
                                                <th>Modifié le</th>
                                                <td>${data.updated_at}</td>
                                            </tr>
                                            <tr>
                                                <th>Statut</th>
                                                <td>
                                                    <span class="badge ${statusBadgeClass}">${data.statut || 'Actif'}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0 section-title">Description de l'activité</h6>
                                </div>
                                <div class="card-body bg-light">
                                    <p class="mb-0" style="white-space: pre-wrap;">${data.description || 'Aucune description'}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pièce jointe -->
                    ${fileHtml}
                </div>
            `;
            
            // Injection du HTML et changement d'affichage
            $('#show-details-section').html(html);
            $('#show-loading-section').hide();
            $('#show-details-section').show();
        } else {
            // Gérer le cas où success est false
            $('#show-details-section').html(`
                <div class="container-fluid">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> ${response.message || 'Données non disponibles'}
                    </div>
                </div>
            `);
            $('#show-loading-section').hide();
            $('#show-details-section').show();
        }
    },
    error: function(xhr) {
        const errorMsg = xhr.responseJSON?.message || 'Erreur lors du chargement des détails';
        $('#show-details-section').html(`
            <div class="container-fluid">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> ${errorMsg}
                </div>
            </div>
        `);
        $('#show-loading-section').hide();
        $('#show-details-section').show();
    }
});
    }
    
    function editTimesheet(timesheetId) {
        currentTimesheetId = timesheetId;
        
        $('#editTimesheetContent').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-warning" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
                <p class="mt-2">Chargement du formulaire...</p>
            </div>
        `);
        
        $('#editTimesheetModal').modal('show');
        
        // Utiliser l'URL correcte - je vois que vous avez une route 'getTimesheetEditData'
        $.ajax({
            url: urls.editData(timesheetId),
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    
                    let currentTypesHtml = '';
                    if (response.types && response.types.length > 0) {
                        currentTypesHtml = response.types.map(type => 
                            `<option value="${type.id}" ${type.id == data.type_id ? 'selected' : ''}>${type.nom}</option>`
                        ).join('');
                    }
                    
                    const html = `
                        <div id="editAlertContainer"></div>
                        
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="timesheet_id" value="${data.id}">
                        
                        <div class="row">
                            <!-- Date -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_date_timesheet">Date *</label>
                                    <input type="date" class="form-control" id="edit_date_timesheet" 
                                           name="date_timesheet" value="${data.date_timesheet}" required>
                                    <div class="invalid-feedback" id="error_edit_date_timesheet"></div>
                                </div>
                            </div>
                            
                            <!-- Utilisateur -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_utilisateur_id">Utilisateur *</label>
                                    <select class="form-control" id="edit_utilisateur_id" name="utilisateur_id" required>
                                        <option value="">Sélectionnez un utilisateur</option>
                                        ${response.users.map(user => 
                                            `<option value="${user.id}" ${user.id == data.utilisateur_id ? 'selected' : ''}>
                                                ${user.name}
                                            </option>`
                                        ).join('')}
                                    </select>
                                    <div class="invalid-feedback" id="error_edit_utilisateur_id"></div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="row">
                            <!-- Catégorie -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_categorie_id">Catégorie *</label>
                                    <select class="form-control" id="edit_categorie_id" name="categorie_id" required>
                                        <option value="">Sélectionnez une catégorie</option>
                                        ${response.categories.map(categorie => 
                                            `<option value="${categorie.id}" ${categorie.id == data.categorie_id ? 'selected' : ''}>
                                                ${categorie.nom}
                                            </option>`
                                        ).join('')}
                                    </select>
                                    <div class="invalid-feedback" id="error_edit_categorie_id"></div>
                                </div>
                            </div>
                            
                            <!-- Type -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_type_id">Type</label>
                                    <select class="form-control" id="edit_type_id" name="type_id">
                                        <option value="">Sélectionnez un type (optionnel)</option>
                                        ${currentTypesHtml}
                                    </select>
                                    <div class="invalid-feedback" id="error_edit_type_id"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Quantité -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_quantite">Quantité *</label>
                                    <input type="number" step="0.01" min="0.01" class="form-control" 
                                           id="edit_quantite" name="quantite" value="${data.quantite}" required>
                                    <div class="invalid-feedback" id="error_edit_quantite"></div>
                                </div>
                            </div>
                            
                            <!-- Prix -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_prix">Prix unitaire (DT) *</label>
                                    <input type="number" step="0.01" min="0.01" class="form-control" 
                                           id="edit_prix" name="prix" value="${data.prix}" required>
                                    <div class="invalid-feedback" id="error_edit_prix"></div>
                                </div>
                            </div>
                            
                            <!-- Total -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Total calculé</label>
                                    <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                           id="edit_total_calcule" value="${formatCurrency(data.quantite * data.prix)}" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Fichier -->
                        <div class="form-group">
                            <label for="edit_file">Changer le fichier (optionnel)</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="edit_file" name="file">
                                <label class="custom-file-label" for="edit_file">Choisir un nouveau fichier...</label>
                            </div>
                            <small class="form-text text-muted">
                                Laisser vide pour conserver le fichier actuel
                            </small>
                        </div>

                        
                        <!-- Description -->
                        <div class="form-group">
                            <label for="edit_description">Description *</label>
                            <textarea class="form-control" id="edit_description" name="description" 
                                      rows="3" required>${data.description || ''}</textarea>
                            <div class="invalid-feedback" id="error_edit_description"></div>
                        </div>
                    `;
                    
                    $('#editTimesheetContent').html(html);
                    
                    // Initialiser les événements pour l'édition
                    initEditFormEvents();
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Erreur lors du chargement du formulaire';
                $('#editTimesheetContent').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> ${errorMsg}
                    </div>
                `);
            }
        });
    }
    
    function initEditFormEvents() {
        // Calcul du total
        $('#edit_quantite, #edit_prix').on('input', function() {
            const quantite = parseFloat($('#edit_quantite').val()) || 0;
            const prix = parseFloat($('#edit_prix').val()) || 0;
            $('#edit_total_calcule').val(formatCurrency(quantite * prix));
        });
        
        // Charger les types quand la catégorie change
        $('#edit_categorie_id').change(function() {
            const categorieId = $(this).val();
            if (categorieId) {
                $.ajax({
                    url: urls.types(categorieId),
                    type: 'GET',
                    success: function(types) {
                        const $select = $('#edit_type_id');
                        $select.empty().append('<option value="">Sélectionnez un type (optionnel)</option>');
                        types.forEach(function(type) {
                            $select.append(`<option value="${type.id}">${type.nom}</option>`);
                        });
                    }
                });
            } else {
                $('#edit_type_id').empty().append('<option value="">Sélectionnez un type (optionnel)</option>');
            }
        });
        
        // Gestion du nom du fichier
        $('#edit_file').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
        
        // Soumission du formulaire d'édition
        $('#editTimesheetForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            
            const $submitBtn = $('#submitEditBtn');
            const originalText = $submitBtn.html();
            
            $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enregistrement...');
            clearFormErrors();
            
            const formData = new FormData(this);
            formData.append('_token', '{{ csrf_token() }}');
            
            // Utiliser l'URL correcte
            $.ajax({
                url: urls.update(currentTimesheetId),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $submitBtn.prop('disabled', false).html(originalText);
                    
                    if (response.success) {
                        // Fermer le modal
                        $('#editTimesheetModal').modal('hide');
                        
                        // Rafraîchir la table
                        timesheetTable.ajax.reload(null, false);
                        
                        // Afficher message
                        showToast('success', response.message);
                    }
                },
                error: function(xhr) {
                    $submitBtn.prop('disabled', false).html(originalText);
                    
                    if (xhr.status === 422) {
                        // Afficher les erreurs de validation
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            const $input = $(`[name="${field}"]`);
                            const $error = $(`#error_edit_${field}`);
                            if ($input.length) {
                                $input.addClass('is-invalid');
                                if ($error.length) {
                                    $error.text(messages[0]);
                                }
                            }
                        });
                        showAlert('danger', 'Veuillez corriger les erreurs', '#editAlertContainer');
                    } else {
                        const errorMsg = xhr.responseJSON?.message || xhr.responseJSON?.error || 'Erreur lors de la mise à jour';
                        showAlert('danger', errorMsg, '#editAlertContainer');
                    }
                }
            });
        });
    }
    
    function confirmDeleteTimesheet(timesheetId, description) {
        currentTimesheetId = timesheetId;
        $('#deleteTimesheetDescription').text(description || 'Cette feuille de temps');
        $('#deleteTimesheetModal').modal('show');
    }
    
    $('#confirmDeleteBtn').on('click', function() {
        const $btn = $(this);
        const originalText = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Suppression...');
        
        // Utiliser l'URL correcte
        $.ajax({
            url: urls.destroy(currentTimesheetId),
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $btn.prop('disabled', false).html(originalText);
                
                if (response.success) {
                    // Fermer le modal
                    $('#deleteTimesheetModal').modal('hide');
                    
                    // Rafraîchir la table
                    timesheetTable.ajax.reload(null, false);
                    
                    // Afficher message
                    showToast('success', response.message);
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(originalText);
                const errorMsg = xhr.responseJSON?.message || xhr.responseJSON?.error || 'Erreur lors de la suppression';
                showToast('danger', errorMsg);
            }
        });
    });
    
    // ==================== INITIALISATION ====================
    
    function init() {
        // Charger les catégories
        loadCategories();
        
        // Initialiser le calcul du total
        calculateTotal();
        
        // Initialiser DataTable
        initDataTable();
        
        // Réinitialiser le formulaire de création quand le modal se ferme
        $('#createTimesheetModal').on('hidden.bs.modal', function() {
            $('#createTimesheetForm')[0].reset();
            $('.custom-file-label').html('Choisir un fichier...');
            clearFormErrors();
            $('#timesheetAlertContainer').empty();
            $('#type_id').empty().append('<option value="">Sélectionnez d\'abord une catégorie</option>');
            calculateTotal();
        });
        
        // Réinitialiser le modal d'édition
        $('#editTimesheetModal').on('hidden.bs.modal', function() {
            currentTimesheetId = null;
            $('#editTimesheetContent').html('');
        });
        
        // Réinitialiser le modal de suppression
        $('#deleteTimesheetModal').on('hidden.bs.modal', function() {
            currentTimesheetId = null;
            $('#deleteTimesheetDescription').text('');
        });
    }
    
    // Lancer l'initialisation
    init();
});
</script>