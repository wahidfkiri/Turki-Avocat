@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gestion des Factures</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Factures</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                     <!-- Alert Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Succès!</h5>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Erreur!</h5>
                {{ session('error') }}
            </div>
            @endif
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Liste des factures</h3>
                            <div class="card-tools">
                                @if(auth()->user()->hasPermission('create_factures'))
                                     <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createFactureModal">
                                    <i class="fas fa-plus"></i> Nouvelle Facture
                                </button>
                                @endif
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- Filtres -->
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="filter_numero">Numéro</label>
                                    <input type="text" class="form-control" id="filter_numero" placeholder="Rechercher par numéro">
                                </div>
                                <div class="col-md-2">
                                    <label for="filter_type_piece">Type de pièce</label>
                                    <select class="form-control" id="filter_type_piece">
                                        <option value="">Tous les types</option>
                                        <option value="facture">Facture</option>
                                        <option value="note_frais">Note de frais</option>
                                        <option value="note_provision">Note de provision</option>
                                        <option value="avoir">Avoir</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="filter_statut">Statut</label>
                                    <select class="form-control" id="filter_statut">
                                        <option value="">Tous les statuts</option>
                                        <option value="payé">Payé</option>
                                        <option value="non_payé" selected>Non payé</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="filter_dossier">Dossier</label>
                                    <select class="form-control search_dossier" id="filter_dossier">
                                        <option value="">Tous les dossiers</option>
                                        @foreach($dossiers as $dossier)
                                            <option value="{{ $dossier->id }}">{{ $dossier->numero_dossier }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="filter_client">Client</label>
                                    <select class="form-control search_client" id="filter_client">
                                        <option value="">Tous les clients</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->identite_fr ?? $client->identite_ar }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="filter_date_debut">Date émission</label>
                                    <input type="date" class="form-control" id="filter_date_debut">
                                </div>
                            </div>
                            <!-- New Row for Month and Year Filters -->
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="filter_month">Mois</label>
                                    <select class="form-control" id="filter_month">
                                        <option value="">Tous les mois</option>
                                        <option value="01">Janvier</option>
                                        <option value="02">Février</option>
                                        <option value="03">Mars</option>
                                        <option value="04">Avril</option>
                                        <option value="05">Mai</option>
                                        <option value="06">Juin</option>
                                        <option value="07">Juillet</option>
                                        <option value="08">Août</option>
                                        <option value="09">Septembre</option>
                                        <option value="10">Octobre</option>
                                        <option value="11">Novembre</option>
                                        <option value="12">Décembre</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="filter_year">Année</label>
                                    <input type="text" class="form-control" id="filter_year" placeholder="Toutes les années">
                                    <!-- <select class="form-control" id="filter_year">
                                        <option value="">Toutes les années</option>
                                        @php
                                            $currentYear = date('Y');
                                            $startYear = $currentYear - 5; // Show last 5 years
                                        @endphp
                                        @for($year = $currentYear; $year >= $startYear; $year--)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select> -->
                                </div>
                                <div class="col-md-2">
                                    <label for="filter_date_fin">Date fin</label>
                                    <input type="date" class="form-control" id="filter_date_fin">
                                </div>
                                <div class="col-md-6" style="margin-top: 25px;">
                                    <button type="button" id="btn_reset_filters" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i> Réinitialiser
                                    </button>
                                    <button type="button" id="btn_apply_filters" class="btn btn-primary">
                                        <i class="fas fa-filter"></i> Appliquer
                                    </button>
                                </div>
                            </div>

                            <!-- Table -->
                            <table id="factures-table" class="table table-bordered table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Numéro</th>
                                        <th>Type</th>
                                        <th>Date Émission</th>
                                        <th>Dossier</th>
                                        <th>Client</th>
                                        <th>Montant HT</th>
                                        <th>TVA</th>
                                        <th>Montant TTC</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" style="text-align:right">Totaux:</th>
                                        <th id="total-ht"></th>
                                        <th id="total-tva"></th>
                                        <th id="total-ttc"></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="deleteFactureModal" tabindex="-1" aria-labelledby="deleteFactureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteFactureModalLabel">Confirmation de suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer la facture <strong id="facture-numero"></strong> ?</p>
                <p class="text-danger"><small>Cette action est irréversible. Toutes les données associées à cette facture seront perdues.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirm-facture-delete">Supprimer</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal pour afficher les détails -->
<div class="modal fade" id="factureDetailsModal" tabindex="-1" role="dialog" aria-labelledby="factureDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="factureDetailsModalLabel">
                    <i class="fas fa-file-invoice"></i> Détails de la facture
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="factureDetailsModalBody">
                <!-- Le contenu sera chargé dynamiquement ici -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement des détails...</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal pour éditer la facture -->
<div class="modal fade" id="editFactureModal" tabindex="-1" role="dialog" aria-labelledby="editFactureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="editFactureModalLabel">
                    <i class="fas fa-edit"></i> Modifier la facture
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="editFactureModalBody">
                <!-- Le contenu sera chargé dynamiquement ici -->
                <div class="text-center py-5">
                    <div class="spinner-border text-warning" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement du formulaire de modification...</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.2/jquery.sumoselect.min.js"></script>
<script>
$(document).ready(function() {
    // ============================
    // VARIABLES GLOBALES
    // ============================
    let factureToDelete = null;
    let factureRowToDelete = null;
    let table; // Déclaration de la variable table

    // ============================
    // INITIALISATION GENERALE
    // ============================
    
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // ============================
    // DATATABLE INITIALISATION
    // ============================
    table = $('#factures-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("factures.data") }}',
            data: function (d) {
                d.numero = $('#filter_numero').val();
                d.type_piece = $('#filter_type_piece').val();
                d.statut = $('#filter_statut').val();
                d.dossier_id = $('#filter_dossier').val();
                d.client_id = $('#filter_client').val();
                d.date_debut = $('#filter_date_debut').val();
                d.date_fin = $('#filter_date_fin').val();
                d.month = $('#filter_month').val();
                d.year = $('#filter_year').val();
                d.min_montant = $('#filter_min_montant').val();
                d.max_montant = $('#filter_max_montant').val();
            }
        },
        columns: [
            {
                data: null,
                orderable: false,
                searchable: false,
                visible: false,
                render: function(data, type, row, meta) {
                    return meta.settings._iDisplayStart + meta.row + 1;
                }
            },
            { 
                data: 'numero', 
                name: 'numero',
                render: function(data) {
                    return data || '-';
                }
            },
            { 
                data: 'type_piece', 
                name: 'type_piece',
                render: function(data) {
                    var badges = {
                        'facture': 'primary',
                        'note_frais': 'info',
                        'note_provision': 'warning',
                        'avoir': 'success'
                    };
                    var labels = {
                        'facture': 'Facture',
                        'note_frais': 'Note de frais',
                        'note_provision': 'Note de provision',
                        'avoir': 'Avoir'
                    };
                    return data ? '<span class="badge badge-' + badges[data] + '">' + labels[data] + '</span>' : '-';
                }
            },
            
            { 
                data: 'date_emission', 
                name: 'date_emission',
                render: function(data, type, row) {
                    if (type === 'sort' || type === 'type') {
                        return data;
                    }
                    return data || '-';
                }
            },
            { 
                data: 'dossier', 
                name: 'dossier.numero_dossier',
                render: function(data) {
                    return data ? data.numero_dossier : '-';
                }
            },
            { 
                data: 'client', 
                name: 'client.identite_fr',
                render: function(data) {
                    return data ? (data.identite_fr || data.identite_ar) : '-';
                }
            },
            { 
                data: 'montant_ht', 
                name: 'montant_ht',
                render: function(data) {
                    return data || '0,00 DT';
                }
            },
            { 
                data: 'montant_tva', 
                name: 'montant_tva',
                render: function(data) {
                    return data || '0,00 DT';
                }
            },
            { 
                data: 'montant', 
                name: 'montant',
                render: function(data) {
                    return data || '0,00 DT';
                }
            },
            { 
                data: 'statut', 
                name: 'statut',
                render: function(data) {
                    var badges = {
                        'payé': 'success',
                        'non_payé': 'danger'
                    };
                    var labels = {
                        'payé': 'Payé',
                        'non_payé': 'Non payé'
                    };
                    return data ? '<span class="badge badge-' + badges[data] + '">' + labels[data] + '</span>' : '-';
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    var actions = '<div class="btn-group">';
                    
                    @if(auth()->user()->hasPermission('view_factures'))
    actions += '<button type="button" class="btn btn-info btn-sm view-facture-btn" data-id="' + row.id + '" title="Voir"><i class="fas fa-eye"></i></button>';
@endif

                   @if(auth()->user()->hasPermission('edit_factures'))
    actions += '<button type="button" class="btn btn-warning btn-sm edit-facture-btn" data-id="' + row.id + '" title="Modifier"><i class="fas fa-edit"></i></button>';
@endif
                    
                    @if(auth()->user()->hasPermission('delete_factures'))
                        actions += '<button type="button" class="btn btn-danger btn-sm delete-facture-btn" data-id="' + row.id + '" data-numero="' + (row.numero || '') + '" title="Supprimer"><i class="fas fa-trash"></i></button>';
                    @endif

                    @if(auth()->user()->hasPermission('export_data'))
                        actions += '<a href="/factures/display/' + row.id + '" class="btn btn-secondary btn-sm" title="PDF" target="_blank"><i class="fas fa-file-pdf"></i></a>';
                    @endif
                    
                    actions += '</div>';
                    return actions;
                }
            }
        ],
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        },
        createdRow: function(row, data, dataIndex) {
            // Add ID to row for easy removal
            $(row).attr('id', 'facture-row-' + data.id);
        },
        drawCallback: function(settings) {
            // Calculate totals
            var api = this.api();
            
            var totalHt = api.column(6, {search: 'applied'}).data().reduce(function(a, b) {
                var numericValue = parseFloat(
                    b.replace(/[^\d,]/g, '')
                     .replace(',', '.')
                     .replace(/\s/g, '')
                );
                return a + (numericValue || 0);
            }, 0);
            
            var totalTva = api.column(7, {search: 'applied'}).data().reduce(function(a, b) {
                var numericValue = parseFloat(
                    b.replace(/[^\d,]/g, '')
                     .replace(',', '.')
                     .replace(/\s/g, '')
                );
                return a + (numericValue || 0);
            }, 0);
            
            var totalTtc = api.column(8, {search: 'applied'}).data().reduce(function(a, b) {
                var numericValue = parseFloat(
                    b.replace(/[^\d,]/g, '')
                     .replace(',', '.')
                     .replace(/\s/g, '')
                );
                return a + (numericValue || 0);
            }, 0);
            
            // Formater les totaux avec le même format
            var formattedHt = totalHt.toFixed(2)
                .replace('.', ',')
                .replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            
            var formattedTva = totalTva.toFixed(2)
                .replace('.', ',')
                .replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            
            var formattedTtc = totalTtc.toFixed(2)
                .replace('.', ',')
                .replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            
            $('#total-ht').text(formattedHt + ' DT');
            $('#total-tva').text(formattedTva + ' DT');
            $('#total-ttc').text(formattedTtc + ' DT');
        }
    });

    // ============================
    // FONCTIONS UTILITAIRES
    // ============================
    
    // Fonction pour effacer l'input file
    function clearFileInput() {
        $('#piece_jointe').val('');
        $('#piece_jointe_label').text('Choisir un fichier (PDF, images, Word, Excel) - Max 10MB');
        $('#file_preview').hide();
    }

    // Fonction pour afficher les messages
    function showMessage(message, type = 'success') {
        const messageDiv = $('#ajaxMessage');
        messageDiv.removeClass('alert-success alert-danger alert-warning')
                  .addClass(`alert-${type === 'error' ? 'danger' : type}`)
                  .html(message)
                  .show()
                  .delay(5000)
                  .fadeOut();
    }

    // Fonction pour afficher les alertes
    function showAlert(type, message) {
        // Supprimer les alertes existantes
        $('.alert.toast-alert').remove();
        
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
        const title = type === 'success' ? 'Succès' : 'Erreur';
        
        const alertHtml = `
            <div class="alert alert-${type} toast-alert alert-dismissible fade show" role="alert" 
                 style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 400px;">
                <div class="d-flex align-items-center">
                    <i class="fas ${icon} mr-2" style="font-size: 1.5rem;"></i>
                    <div style="flex: 1;">
                        <strong>${title}</strong>
                        <div class="small">${message}</div>
                    </div>
                    <button type="button" class="close ml-2" data-dismiss="alert" style="position: static;">
                        <span>&times;</span>
                    </button>
                </div>
            </div>
        `;
        
        // Ajouter la nouvelle alerte
        $('body').append(alertHtml);
        
        // Supprimer automatiquement après 5 secondes
        setTimeout(() => {
            $('.alert.toast-alert').alert('close');
        }, 5000);
    }

    // Fonction pour réinitialiser le formulaire
    function resetForm() {
        if ($('#factureForm').length) {
            $('#factureForm')[0].reset();
            
            // Réinitialiser les valeurs spécifiques
            $('#montant_ht').val('0');
            $('#montant_tva').val('0');
            $('#montant').val('0');
            $('#montantAlert').hide();
            
            // Réinitialiser le numéro automatique
            @if(isset($nextNumber))
            $('#numero').val('{{ $nextNumber }}');
            @endif
            
            clearFileInput();
            
            // Réinitialiser la validation
            if ($('#factureForm').validate()) {
                $('#factureForm').validate().resetForm();
            }
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        }
    }

    // ============================
    // GESTION DES FILTRES
    // ============================

    // Apply filters
    $('#btn_apply_filters').click(function() {
        table.ajax.reload();
    });

    // Reset filters
    $('#btn_reset_filters').click(function() {
        $('#filter_numero').val('');
        $('#filter_type_piece').val('').trigger('change');
        $('#filter_statut').val('').trigger('change');
        $('#filter_dossier').val('').trigger('change');
        $('#filter_client').val('').trigger('change');
        $('#filter_date_debut').val('');
        $('#filter_date_fin').val('');
        $('#filter_month').val('').trigger('change');
        $('#filter_year').val('').trigger('change');
        $('#filter_min_montant').val('');
        $('#filter_max_montant').val('');
        table.ajax.reload();
    });

    // Apply filters on Enter key in text inputs
    $('#filter_numero, #filter_min_montant, #filter_max_montant').keypress(function(e) {
        if (e.which == 13) {
            table.ajax.reload();
        }
    });

    // ============================
    // GESTION DES BOUTONS ACTIONS
    // ============================

    // Delete button click handler
    $(document).on('click', '.delete-facture-btn', function() {
        const factureId = $(this).data('id');
        const factureNumero = $(this).data('numero') || 'cette facture';
        
        factureToDelete = factureId;
        factureRowToDelete = $(this).closest('tr');
        $('#facture-numero').text(factureNumero);
        $('#deleteFactureModal').modal('show');
    });

    // Confirm delete button handler
    $('#confirm-facture-delete').on('click', function() {
        if (!factureToDelete) return;
        
        const deleteButton = $(this);
        const originalText = deleteButton.html();
        
        // Show loading state
        deleteButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Suppression...');
        
        $.ajax({
            url: '/factures/' + factureToDelete,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteFactureModal').modal('hide');
                
                if (response.success) {
                    // Remove the row from the table
                    if (factureRowToDelete) {
                        factureRowToDelete.fadeOut(300, function() {
                            table.row($(this)).remove().draw(false);
                        });
                    } else {
                        // Fallback: reload the table
                        table.ajax.reload();
                    }
                    
                    // Show success message
                    showAlert('success', response.message || 'Facture supprimée avec succès!');
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la suppression.');
                }
            },
            error: function(xhr) {
                $('#deleteFactureModal').modal('hide');
                
                let errorMessage = 'Une erreur est survenue lors de la suppression de la facture.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showAlert('danger', errorMessage);
            },
            complete: function() {
                // Reset button state
                deleteButton.prop('disabled', false).html(originalText);
                factureToDelete = null;
                factureRowToDelete = null;
            }
        });
    });

    // Gestion du clic sur le bouton "Voir"
    $(document).on('click', '.view-facture-btn', function() {
        const factureId = $(this).data('id');
        loadFactureDetails(factureId);
    });

    // Gestion du clic sur le bouton "Modifier"
    $(document).on('click', '.edit-facture-btn', function() {
        const factureId = $(this).data('id');
        loadEditForm(factureId);
    });

    // ============================
    // FONCTIONS AJAX POUR ACTIONS
    // ============================

    // Fonction pour charger les détails de la facture
    function loadFactureDetails(factureId) {
        // Afficher le spinner de chargement
        $('#factureDetailsModalBody').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
                <p class="mt-2">Chargement des détails...</p>
            </div>
        `);
        
        // Ouvrir le modal
        $('#factureDetailsModal').modal('show');
        
        // Charger les détails via AJAX
        $.ajax({
            url: '/factures/' + factureId,
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                // Parser la réponse HTML
                const parser = new DOMParser();
                const doc = parser.parseFromString(response, 'text/html');
                
                // Extraire le contenu principal - méthode simplifiée
                let modalContent = '';
                
                // Trouver toutes les sections
                const sections = doc.querySelectorAll('.row > .col-md-6, .row > .col-12');
                
                // Parcourir les sections et les organiser
                sections.forEach((section, index) => {
                    const sectionHTML = section.innerHTML;
                    
                    // Vérifier quel type de section c'est en fonction du contenu
                    if (sectionHTML.includes('Informations principales')) {
                        modalContent += `
                            <div class="row">
                                <div class="col-md-6">
                                    ${sectionHTML}
                                </div>
                        `;
                    } 
                    else if (sectionHTML.includes('Détails financiers')) {
                        modalContent += `
                                <div class="col-md-6">
                                    ${sectionHTML}
                                </div>
                            </div>
                        `;
                    }
                    else if (sectionHTML.includes('Relations')) {
                        modalContent += `
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    ${sectionHTML}
                                </div>
                        `;
                    }
                    else if (sectionHTML.includes('Métadonnées')) {
                        modalContent += `
                                <div class="col-md-6">
                                    ${sectionHTML}
                                </div>
                            </div>
                        `;
                    }
                    else if (sectionHTML.includes('Commentaires')) {
                        modalContent += `
                            <div class="row mt-4">
                                ${sectionHTML}
                            </div>
                        `;
                    }
                });
                
                // Mettre à jour le contenu du modal
                $('#factureDetailsModalBody').html(modalContent);
                
                // Ajouter les boutons d'action dans le footer
                const modalFooter = `
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Fermer
                        </button>
                       
                    </div>
                `;
                
                // Ajouter le footer au modal
                $('#factureDetailsModal .modal-content').append(modalFooter);
                
                // Trouver le numéro de facture
                let factureNumero = '#' + factureId;
                const numeroElement = doc.querySelector('td.font-weight-bold');
                if (numeroElement) {
                    factureNumero = numeroElement.textContent.trim();
                }
                
                // Mettre à jour le titre du modal
                $('#factureDetailsModalLabel').html(`<i class="fas fa-file-invoice"></i> Détails de la facture ${factureNumero}`);
            },
            error: function(xhr, status, error) {
                $('#factureDetailsModalBody').html(`
                    <div class="text-center py-5">
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>Erreur lors du chargement des détails.</p>
                            <p><small>${xhr.status}: ${xhr.statusText}</small></p>
                            <a href="/factures/${factureId}" class="btn btn-primary mt-2" target="_blank">
                                Ouvrir dans une nouvelle fenêtre
                            </a>
                        </div>
                    </div>
                `);
            }
        });
    }

    // Fonction pour charger le formulaire d'édition
    function loadEditForm(factureId) {
        // Afficher le spinner de chargement
        $('#editFactureModalBody').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-warning" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
                <p class="mt-2">Chargement du formulaire de modification...</p>
            </div>
        `);
        
        // Ouvrir le modal
        $('#editFactureModal').modal('show');
        
        // Charger le formulaire via AJAX
        $.ajax({
            url: '/factures/' + factureId + '/edit',
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                // Parser la réponse HTML
                const parser = new DOMParser();
                const doc = parser.parseFromString(response, 'text/html');
                
                // Extraire le formulaire et son contenu
                const form = doc.querySelector('form#factureForm');
                if (form) {
                    // Extraire le contenu de la carte principale
                    const cardContent = doc.querySelector('.card-body');
                    if (cardContent) {
                        // Créer le contenu du modal
                        const modalContent = `
                            <form id="editFactureForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="facture_id" value="${factureId}">
                                <div class="card-body">
                                    ${cardContent.innerHTML}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        <i class="fas fa-times"></i> Annuler
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="updateFactureBtn">
                                        <i class="fas fa-save"></i> Mettre à jour
                                    </button>
                                </div>
                            </form>
                        `;
                        
                        $('#editFactureModalBody').html(modalContent);
                        
                        // Initialiser les composants JavaScript
                        initEditFormComponents();
                        
                        // Mettre à jour le titre du modal avec le numéro de facture
                        const factureNumero = doc.querySelector('#numero')?.value || '#' + factureId;
                        $('#editFactureModalLabel').html(`<i class="fas fa-edit"></i> Modifier la facture ${factureNumero}`);
                    }
                } else {
                    // Fallback: afficher un message d'erreur
                    $('#editFactureModalBody').html(`
                        <div class="text-center py-5">
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                <p>Impossible de charger le formulaire de modification.</p>
                                <a href="/factures/${factureId}/edit" class="btn btn-warning mt-2" target="_blank">
                                    Ouvrir dans une nouvelle fenêtre
                                </a>
                            </div>
                        </div>
                    `);
                }
            },
            error: function(xhr, status, error) {
                $('#editFactureModalBody').html(`
                    <div class="text-center py-5">
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>Erreur lors du chargement du formulaire.</p>
                            <p><small>${xhr.status}: ${xhr.statusText}</small></p>
                            <a href="/factures/${factureId}/edit" class="btn btn-warning mt-2" target="_blank">
                                Ouvrir dans une nouvelle fenêtre
                            </a>
                        </div>
                    </div>
                `);
            }
        });
    }

    // ============================
    // FORMULAIRE D'ÉDITION
    // ============================

    // Fonction pour initialiser les composants du formulaire d'édition
    function initEditFormComponents() {
        // Initialiser les événements du formulaire
        $(document).off('submit', '#editFactureForm').on('submit', '#editFactureForm', function(e) {
            e.preventDefault();
            updateFacture();
        });

        // Gestion de l'input file
        $(document).off('change', '#editFactureModal input[type="file"]').on('change', '#editFactureModal input[type="file"]', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                const label = $(this).siblings('.custom-file-label');
                label.text(fileName);
                
                // Afficher l'aperçu
                const preview = $(this).closest('.form-group').find('#file_preview');
                preview.find('#file_name').text(fileName + ' (' + fileSize + ' MB)');
                preview.show();
                
                // Validation de la taille
                if (file.size > 10 * 1024 * 1024) {
                    showAlert('danger', 'Le fichier est trop volumineux. Taille maximum: 10MB');
                    $(this).val('');
                    label.text('Choisir un fichier (PDF, images, Word, Excel) - Max 10MB');
                    preview.hide();
                }
            }
        });

        // Gestion du bouton de suppression de fichier
        $(document).off('click', '#editFactureModal .btn-danger[onclick*="confirmDeleteFile"]').on('click', '#editFactureModal .btn-danger[onclick*="confirmDeleteFile"]', function() {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette pièce jointe ?')) {
                // Ajouter un champ caché pour indiquer la suppression
                $('#editFactureForm').append('<input type="hidden" name="delete_piece_jointe" value="1">');
                $(this).closest('.alert').remove();
                showAlert('warning', 'La pièce jointe sera supprimée lors de la sauvegarde.');
            }
        });

        // Calcul automatique du montant TTC
        $(document).off('input', '#editFactureModal #montant_ht, #editFactureModal #montant_tva').on('input', '#editFactureModal #montant_ht, #editFactureModal #montant_tva', function() {
            calculateMontantTTC();
        });

        // Auto-sélection du client basé sur le dossier
        $(document).off('change', '#editFactureModal #dossier_id').on('change', '#editFactureModal #dossier_id', function() {
            const selectedOption = $(this).find('option:selected');
            const clientId = selectedOption.data('client-id');
            if (clientId) {
                $('#editFactureModal #client_id').val(clientId).trigger('change');
            }
        });

        // Calcul initial
        calculateMontantTTC();
    }

    // Fonction pour calculer le montant TTC
    function calculateMontantTTC() {
        // Vérifier si on est dans le modal d'édition ou de création
        const isEditModal = $('#editFactureModal').is(':visible');
        const prefix = isEditModal ? '#editFactureModal ' : '';
        
        const montantHT = parseFloat($(prefix + '#montant_ht').val()) || 0;
        const montantTVA = parseFloat($(prefix + '#montant_tva').val()) || 0;
        const montantTTC = montantHT + montantTVA;
        
        $(prefix + '#montant').val(montantTTC.toFixed(2));
        
        // Vérifier la cohérence
        const tolerance = 0.01;
        const difference = Math.abs(montantTTC - parseFloat($(prefix + '#montant').val()));
        
        if (difference > tolerance) {
            $(prefix + '#montantAlert').show();
            if (isEditModal) {
                $('#editFactureModal #montantAlertText').text('Attention : Le montant TTC calculé (' + montantTTC.toFixed(2) + ' DT) est différent du montant saisi. Vérifiez les montants HT et TVA.');
            } else {
                $('#montantAlertText').text('Attention : Le montant TTC calculé (' + montantTTC.toFixed(2) + ' DT) est différent du montant saisi. Vérifiez les montants HT et TVA.');
            }
        } else {
            $(prefix + '#montantAlert').hide();
        }
    }

    // Fonction pour mettre à jour la facture via AJAX
    function updateFacture() {
        const form = $('#editFactureForm')[0];
        const formData = new FormData(form);
        const factureId = formData.get('facture_id');
        
        // Désactiver le bouton et afficher le loader
        const updateBtn = $('#updateFactureBtn');
        const originalHtml = updateBtn.html();
        updateBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mise à jour...');

        // Vérification de la cohérence des montants
        const montantHT = parseFloat($('#editFactureModal #montant_ht').val()) || 0;
        const montantTVA = parseFloat($('#editFactureModal #montant_tva').val()) || 0;
        const montantTTC = parseFloat($('#editFactureModal #montant').val()) || 0;
        const calculatedTTC = montantHT + montantTVA;
        
        if (Math.abs(calculatedTTC - montantTTC) > 0.01) {
            showAlert('danger', `Erreur : Le montant TTC doit être égal à HT + TVA.<br>HT: ${montantHT.toFixed(2)} DT<br>TVA: ${montantTVA.toFixed(2)} DT<br>TTC calculé: ${calculatedTTC.toFixed(2)} DT<br>TTC saisi: ${montantTTC.toFixed(2)} DT`);
            updateBtn.prop('disabled', false).html(originalHtml);
            return;
        }

        // Soumission AJAX
        $.ajax({
            url: '/factures/' + factureId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    // Fermer le modal
                    $('#editFactureModal').modal('hide');
                    
                    // Recharger la DataTable (REMPLACÉ - pas de reload de page)
                    table.ajax.reload();
                    
                    // Afficher un message de succès
                    showAlert('success', response.message || 'Facture mise à jour avec succès');
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la mise à jour');
                    updateBtn.prop('disabled', false).html(originalHtml);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Une erreur est survenue lors de la mise à jour.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 422) {
                    // Gestion des erreurs de validation
                    const errors = xhr.responseJSON.errors;
                    errorMessage = 'Veuillez corriger les erreurs suivantes:<br>';
                    for (const field in errors) {
                        errorMessage += `• ${errors[field][0]}<br>`;
                    }
                } else if (xhr.status === 403) {
                    errorMessage = 'Vous n\'avez pas la permission de modifier des factures.';
                } else if (xhr.status === 404) {
                    errorMessage = 'La facture que vous essayez de modifier n\'existe pas.';
                }
                
                showAlert('danger', errorMessage);
                updateBtn.prop('disabled', false).html(originalHtml);
            }
        });
    }

    // ============================
    // FORMULAIRE DE CRÉATION
    // ============================

    // Auto-sélection du client basé sur le dossier (création)
    $('#dossier_id').change(function() {
        var selectedOption = $(this).find('option:selected');
        var clientId = selectedOption.data('client-id');
        
        if (clientId) {
            $('#client_id').val(clientId).trigger('change');
        }
    });

    // Gestion de l'input file (création)
    $('#piece_jointe').on('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            var fileName = file.name;
            var fileSize = (file.size / 1024 / 1024).toFixed(2);
            
            $('#piece_jointe_label').text(fileName);
            $('#file_name').text(fileName + ' (' + fileSize + ' MB)');
            $('#file_preview').show();
            
            if (file.size > 10 * 1024 * 1024) {
                showMessage('Le fichier est trop volumineux. Taille maximum: 10MB', 'error');
                clearFileInput();
            }
        }
    });

    // Validation pour le fichier
    $.validator.addMethod('fileSize', function(value, element, param) {
        return this.optional(element) || (element.files[0].size <= param);
    }, 'La taille du fichier doit être inférieure à {0}');

    $.validator.addMethod('fileType', function(value, element, param) {
        return this.optional(element) || (element.files[0].type.match(param) || element.files[0].name.match(param));
    }, 'Type de fichier non supporté');

    // Écouter les changements sur HT et TVA (création)
    $('#montant_ht, #montant_tva').on('input', function() {
        calculateMontantTTC();
    });

    // Calcul initial (création)
    calculateMontantTTC();

    // Reset form when modal is shown (création)
    $('#createFactureModal').on('show.bs.modal', function() {
        // S'assurer que les calculs sont à jour
        calculateMontantTTC();
    });

    // Reset form when modal is hidden (création)
    $('#createFactureModal').on('hidden.bs.modal', function() {
        resetForm();
    });

    // Validation côté client (création)
    if ($('#factureForm').length) {
        $('#factureForm').validate({
            rules: {
                type_piece: {
                    required: true
                },
                numero: {
                    required: true,
                    minlength: 3
                },
                date_emission: {
                    required: true
                },
                montant_ht: {
                    required: true,
                    min: 0
                },
                montant_tva: {
                    required: true,
                    min: 0
                },
                montant: {
                    required: true,
                    min: 0
                },
                statut: {
                    required: true
                },
                piece_jointe: {
                    fileSize: 10 * 1024 * 1024,
                    fileType: /\.(pdf|jpg|jpeg|png|doc|docx|xls|xlsx)$/i
                }
            },
            messages: {
                type_piece: {
                    required: "Le type de pièce est obligatoire"
                },
                numero: {
                    required: "Le numéro est obligatoire",
                    minlength: "Le numéro doit contenir au moins 3 caractères"
                },
                date_emission: {
                    required: "La date d'émission est obligatoire"
                },
                montant_ht: {
                    required: "Le montant HT est obligatoire",
                    min: "Le montant HT doit être positif"
                },
                montant_tva: {
                    required: "Le montant TVA est obligatoire",
                    min: "Le montant TVA doit être positif"
                },
                montant: {
                    required: "Le montant TTC est obligatoire",
                    min: "Le montant TTC doit être positif"
                },
                statut: {
                    required: "Le statut est obligatoire"
                },
                piece_jointe: {
                    fileSize: "Le fichier ne doit pas dépasser 10MB",
                    fileType: "Formats acceptés: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX"
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) {
                // Vérification finale de la cohérence des montants
                var montantHT = parseFloat($('#montant_ht').val()) || 0;
                var montantTVA = parseFloat($('#montant_tva').val()) || 0;
                var montantTTC = parseFloat($('#montant').val()) || 0;
                var calculatedTTC = montantHT + montantTVA;
                
                if (Math.abs(calculatedTTC - montantTTC) > 0.01) {
                    showMessage('Erreur : Le montant TTC doit être égal à HT + TVA.\nHT: ' + montantHT.toFixed(2) + ' DT\nTVA: ' + montantTVA.toFixed(2) + ' DT\nTTC calculé: ' + calculatedTTC.toFixed(2) + ' DT\nTTC saisi: ' + montantTTC.toFixed(2) + ' DT', 'error');
                    return false;
                }
                
                // Soumission AJAX
                submitFormAjax(form);
                return false; // Empêcher la soumission normale
            }
        });
    }

    // Fonction de soumission AJAX (création)
    function submitFormAjax(form) {
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        
        // Désactiver le bouton et afficher le loader
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Création...');
        
        // Créer FormData pour gérer les fichiers
        const formData = new FormData(form);
        
        $.ajax({
            url: $(form).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Fermer le modal
                    $('#createFactureModal').modal('hide');
                    
                    // Recharger la DataTable (REMPLACÉ - pas de reload de page)
                    table.ajax.reload();
                    
                    // Afficher un message de succès
                    showAlert('success', response.message);
                    
                    // Réinitialiser le formulaire
                    resetForm();
                    
                } else {
                    showMessage(response.message, 'error');
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Une erreur est survenue lors de la création.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 422) {
                    // Gestion des erreurs de validation
                    const errors = xhr.responseJSON.errors;
                    errorMessage = 'Veuillez corriger les erreurs suivantes:<br>';
                    for (const field in errors) {
                        errorMessage += `- ${errors[field][0]}<br>`;
                    }
                } else if (xhr.status === 403) {
                    errorMessage = 'Vous n\'avez pas la permission de créer des factures.';
                }
                
                showMessage(errorMessage, 'error');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    }

    // Formater les montants à la sortie des champs
    $('#montant_ht, #montant_tva').on('blur', function() {
        var value = parseFloat($(this).val()) || 0;
        $(this).val(value.toFixed(2));
    });

    // ============================
    // GESTION DES MODALS
    // ============================

    // Close modal when clicking the X button (suppression)
    $('#deleteFactureModal .close, #deleteFactureModal [data-dismiss="modal"]').on('click', function() {
        factureToDelete = null;
        factureRowToDelete = null;
    });

    // Nettoyer le modal quand il est fermé (visualisation)
    $('#factureDetailsModal').on('hidden.bs.modal', function() {
        // Réinitialiser le contenu du modal
        $(this).find('.modal-content').html(`
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="factureDetailsModalLabel">
                    <i class="fas fa-file-invoice"></i> Détails de la facture
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="factureDetailsModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement des détails...</p>
                </div>
            </div>
        `);
    });

    // Nettoyer le modal quand il est fermé (édition)
    $('#editFactureModal').on('hidden.bs.modal', function() {
        // Réinitialiser le contenu du modal
        $(this).find('.modal-content').html(`
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="editFactureModalLabel">
                    <i class="fas fa-edit"></i> Modifier la facture
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="editFactureModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-warning" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement du formulaire de modification...</p>
                </div>
            </div>
        `);
    });

    // Fermer les modals avec la touche ESC
    $(document).keydown(function(e) {
        if (e.keyCode === 27) {
            if ($('#factureDetailsModal').hasClass('show')) {
                $('#factureDetailsModal').modal('hide');
            }
            if ($('#editFactureModal').hasClass('show')) {
                $('#editFactureModal').modal('hide');
            }
            if ($('#deleteFactureModal').hasClass('show')) {
                factureToDelete = null;
                factureRowToDelete = null;
                $('#deleteFactureModal').modal('hide');
            }
        }
    });

    // ============================
    // FONCTIONS DE CONFIRMATION
    // ============================

    // Fonction de confirmation de suppression depuis le modal
    window.confirmDeleteFromModal = function(factureId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette facture ? Cette action est irréversible.')) {
            // Afficher un indicateur de chargement
            const deleteButton = $('.btn-danger[onclick*="confirmDeleteFromModal"]');
            const originalHtml = deleteButton.html();
            deleteButton.html('<i class="fas fa-spinner fa-spin"></i> Suppression...');
            deleteButton.prop('disabled', true);

            // Soumettre la suppression via AJAX
            $.ajax({
                url: '/factures/' + factureId,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function(response) {
                    // Fermer le modal
                    $('#factureDetailsModal').modal('hide');
                    
                    // Recharger la DataTable
                    table.ajax.reload();
                    
                    // Afficher un message de succès
                    showAlert('success', 'Facture supprimée avec succès');
                },
                error: function(xhr) {
                    showAlert('danger', 'Erreur lors de la suppression');
                    
                    // Réactiver le bouton
                    deleteButton.html(originalHtml);
                    deleteButton.prop('disabled', false);
                }
            });
        }
    };
});
</script>
<style>
    .dataTables_wrapper .dataTables_filter {
        float: right;
    }
    .dataTables_wrapper .dataTables_length {
        float: left;
    }
    .table th {
        background-color: #f8f9fa;
    }
    .btn-group .btn {
        margin-right: 2px;
    }
    .badge {
        font-size: 0.8em;
    }
    tfoot th {
        background-color: #e9ecef;
        font-weight: bold;
    }
    #total-ht, #total-tva, #total-ttc {
        color: #28a745;
        font-weight: bold;
    }
    .delete-facture-btn {
        transition: all 0.3s ease;
    }
    .delete-facture-btn:hover {
        transform: scale(1.05);
    }
</style>
@include('factures.create')
@endsection