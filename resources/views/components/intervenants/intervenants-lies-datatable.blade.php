{{-- resources/views/components/intervenants/intervenants-lies-datatable.blade.php --}}
<div class="tab-pane fade" id="intervenants-lies" role="tabpanel" aria-labelledby="intervenants-lies-tab">
    <div class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="section-title mb-0">Intervenants Liés</h5>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#attachIntervenantModal">
                <i class="fas fa-plus"></i> Attacher un intervenant
            </button>
        </div>
        
        <div class="table-responsive">
            <table id="intervenantsLiesTable" class="table table-bordered table-hover w-100">
                <thead>
                    <tr>
                        <th width="30">#</th>
                        <th width="150">Type de lien</th>
                        <th>Identité</th>
                        <th width="120">Catégorie</th>
                        <th width="120">Type</th>
                        <th width="150">Téléphone</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Les données seront chargées via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal pour attacher un intervenant -->
<div class="modal fade" id="attachIntervenantModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">
                    <i class="fas fa-link"></i> Attacher un intervenant
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="searchIntervenant">Rechercher un intervenant</label>
                            <input type="text" class="form-control" id="searchIntervenant" 
                                   placeholder="Nom, email ou téléphone...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="linkType">Type de lien</label>
                            <select class="form-control" id="linkType">
                                <option value="collaborateur">Collaborateur</option>
                                <option value="associe">Associé</option>
                                <option value="contact">Contact</option>
                                <option value="correspondant">Correspondant</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive mt-3" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th width="30"></th>
                                <th>Identité</th>
                                <th>Catégorie</th>
                                <th>Téléphone</th>
                            </tr>
                        </thead>
                        <tbody id="searchResults">
                            <!-- Résultats de recherche -->
                        </tbody>
                    </table>
                </div>
                
                <div id="selectedIntervenant" class="mt-3" style="display: none;">
                    <div class="alert alert-info">
                        <strong>Intervenant sélectionné :</strong>
                        <span id="selectedIntervenantName"></span>
                        <button type="button" class="close" onclick="clearSelection()">
                            <span>&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="confirmAttach" disabled>Attacher</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de détachement -->
<div class="modal fade" id="detachIntervenantModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir détacher cet intervenant ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDetach">Détacher</button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- CDN DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<style>
    #intervenantsLiesTable_wrapper {
        margin-top: 15px;
    }
    .dataTables_length select {
        width: 75px;
    }
    .section-title {
        color: #495057;
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 0.5rem;
    }
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
</style>

<script>
$(document).ready(function() {
    let currentIntervenantId = null;
    let selectedIntervenantId = null;
    let selectedIntervenantName = null;
    let dataTable;
    
    // Initialiser DataTables
    function initializeDataTable() {
        dataTable = $('#intervenantsLiesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("intervenants.lies.datatable", $intervenant->id) }}',
                type: 'GET',
                data: function(d) {
                    // Ajouter des paramètres supplémentaires si nécessaire
                    d.search_type = $('#filterType').val();
                }
            },
            columns: [
                { 
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                { 
                    data: 'type_lien',
                    name: 'type_lien',
                    render: function(data) {
                        const badges = {
                            'collaborateur': 'primary',
                            'associe': 'success',
                            'contact': 'info',
                            'correspondant': 'warning',
                            'autre': 'secondary'
                        };
                        const badgeClass = badges[data] || 'secondary';
                        return `<span class="badge badge-${badgeClass}">${data}</span>`;
                    }
                },
                { 
                    data: 'identite_fr',
                    name: 'identite_fr',
                    render: function(data, type, row) {
                        return `
                            <div>
                                <strong>${data}</strong>
                                ${row.identite_ar ? '<br><small class="text-muted">' + row.identite_ar + '</small>' : ''}
                            </div>
                        `;
                    }
                },
                { 
                    data: 'categorie',
                    name: 'categorie',
                    render: function(data) {
                        if (!data || data === 'null') return '<span class="text-muted">N/A</span>';
                        return `<span class="badge badge-info">${data}</span>`;
                    }
                },
                { 
                    data: 'type',
                    name: 'type',
                    render: function(data) {
                        if (!data) return '<span class="text-muted">N/A</span>';
                        return `<span class="badge badge-secondary">${data}</span>`;
                    }
                },
                { 
                    data: 'portable1',
                    name: 'portable1',
                    render: function(data) {
                        return data ? `<a href="tel:${data}" class="text-decoration-none"><i class="fas fa-phone mr-1"></i>${data}</a>` : 'N/A';
                    }
                },
                { 
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `
                            <div class="btn-group">
                                <a href="/intervenants/${row.id}" 
                                   class="btn btn-sm btn-outline-info" 
                                   title="Voir détails"
                                   target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger detach-btn" 
                                        data-id="${row.pivot_id}"
                                        title="Détacher">
                                    <i class="fas fa-unlink"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            },
            order: [[0, 'asc']],
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Tous"]],
            drawCallback: function(settings) {
                // Mettre à jour le compteur dans l'onglet
                const total = this.api().page.info().recordsTotal;
                $('#intervenantsLiesCount').text(total);
                
                // Réattacher les événements
                reattachEvents();
            }
        });
    }
    
    // Réattacher les événements
    function reattachEvents() {
        // Bouton détacher
        $('.detach-btn').off('click').on('click', function(e) {
            e.stopPropagation();
            currentIntervenantId = $(this).data('id');
            $('#detachIntervenantModal').modal('show');
        });
    }
    
    // Initialiser DataTables
    initializeDataTable();
    
    // Recherche d'intervenants
    let searchTimeout;
    $('#searchIntervenant').on('keyup', function() {
        clearTimeout(searchTimeout);
        const searchTerm = $(this).val();
        
        if(searchTerm.length < 2) {
            $('#searchResults').html('<tr><td colspan="4" class="text-center text-muted">Entrez au moins 2 caractères</td></tr>');
            return;
        }
        
        searchTimeout = setTimeout(() => {
            searchIntervenants(searchTerm);
        }, 500);
    });
    
    // Fonction de recherche d'intervenants
    function searchIntervenants(searchTerm) {
        $('#searchResults').html('<tr><td colspan="4" class="text-center"><i class="fas fa-spinner fa-spin"></i> Recherche en cours...</td></tr>');
        
        $.ajax({
            url: '{{ route("intervenants.search.ajax") }}',
            method: 'GET',
            data: {
                search: searchTerm,
                exclude: {{ $intervenant->id }}
            },
            success: function(response) {
                let html = '';
                if(response.success && response.data && response.data.length > 0) {
                    response.data.forEach(intervenant => {
                        html += `
                            <tr class="intervenant-row" data-id="${intervenant.id}" data-name="${intervenant.identite_fr}">
                                <td class="text-center">
                                    <input type="radio" name="selectedIntervenant" value="${intervenant.id}">
                                </td>
                                <td>
                                    <strong>${intervenant.identite_fr}</strong>
                                    ${intervenant.categorie ? '<br><small class="text-muted">' + intervenant.categorie + '</small>' : ''}
                                </td>
                                <td>${intervenant.categorie || 'N/A'}</td>
                                <td>${intervenant.portable1 || 'N/A'}</td>
                            </tr>
                        `;
                    });
                } else {
                    html = '<tr><td colspan="4" class="text-center text-muted">Aucun intervenant trouvé</td></tr>';
                }
                $('#searchResults').html(html);
                
                // Gérer la sélection
                $('.intervenant-row input[type="radio"]').off('change').on('change', function() {
                    const row = $(this).closest('tr');
                    selectedIntervenantId = row.data('id');
                    selectedIntervenantName = row.data('name');
                    
                    $('#selectedIntervenantName').text(selectedIntervenantName);
                    $('#selectedIntervenant').show();
                    $('#confirmAttach').prop('disabled', false);
                });
            },
            error: function() {
                $('#searchResults').html('<tr><td colspan="4" class="text-center text-danger">Erreur lors de la recherche</td></tr>');
            }
        });
    }
    
    // Effacer la sélection
    window.clearSelection = function() {
        selectedIntervenantId = null;
        selectedIntervenantName = null;
        $('#selectedIntervenant').hide();
        $('#selectedIntervenantName').text('');
        $('#confirmAttach').prop('disabled', true);
        $('.intervenant-row input[type="radio"]').prop('checked', false);
    }
    
    // Attacher un intervenant
    $('#confirmAttach').click(function() {
        if(!selectedIntervenantId) return;
        
        const linkType = $('#linkType').val();
        const button = $(this);
        
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Attachement...');
        
        $.ajax({
            url: '{{ route("intervenants.lies.attach", $intervenant->id) }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                intervenant_id: selectedIntervenantId,
                type_lien: linkType
            },
            success: function(response) {
                if(response.success) {
                    // Recharger DataTables
                    dataTable.ajax.reload();
                    
                    // Fermer le modal et réinitialiser
                    $('#attachIntervenantModal').modal('hide');
                    clearSelection();
                    $('#searchIntervenant').val('');
                    $('#searchResults').html('');
                    
                    // Afficher notification
                    showToast(response.message || 'Intervenant attaché avec succès', 'success');
                } else {
                    showToast(response.message || 'Erreur lors de l\'attachement', 'error');
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Erreur lors de l\'attachement';
                showToast(errorMsg, 'error');
            },
            complete: function() {
                button.prop('disabled', false).html('Attacher');
            }
        });
    });
    
    // Détacher un intervenant
    $('#confirmDetach').click(function() {
        if(!currentIntervenantId) return;
        
        const button = $(this);
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Détachement...');
        
        $.ajax({
            url: '{{ route("intervenants.lies.detach", $intervenant->id) }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                pivot_id: currentIntervenantId,
                _method: 'DELETE'
            },
            success: function(response) {
                if(response.success) {
                    // Recharger DataTables
                    dataTable.ajax.reload();
                    
                    // Fermer le modal
                    $('#detachIntervenantModal').modal('hide');
                    currentIntervenantId = null;
                    
                    // Afficher notification
                    showToast(response.message || 'Intervenant détaché avec succès', 'success');
                } else {
                    showToast(response.message || 'Erreur lors du détachement', 'error');
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Erreur lors du détachement';
                showToast(errorMsg, 'error');
            },
            complete: function() {
                button.prop('disabled', false).html('Détacher');
            }
        });
    });
    
    // Fonction de notification
    function showToast(message, type = 'info') {
        const toastId = 'toast-' + Date.now();
        const toastClass = type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info';
        const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle';
        
        const toast = $(`
            <div id="${toastId}" class="toast bg-${toastClass} text-white" 
                 style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 250px; border-radius: 4px; padding: 10px 15px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-${icon} mr-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="close text-white ml-3" onclick="$('#${toastId}').remove()" style="opacity: 0.8;">
                        <span>&times;</span>
                    </button>
                </div>
            </div>
        `);
        
        $('body').append(toast);
        
        // Auto-suppression après 3 secondes
        setTimeout(() => {
            $(`#${toastId}`).fadeOut(() => $(`#${toastId}`).remove());
        }, 3000);
    }
    
    // Nettoyage lors de la fermeture du modal
    $('#attachIntervenantModal').on('hidden.bs.modal', function() {
        clearSelection();
        $('#searchIntervenant').val('');
        $('#searchResults').html('');
    });
});
</script>