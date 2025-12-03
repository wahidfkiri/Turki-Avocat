@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Feuilles de Temps</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Feuilles de Temps</li>
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
                            <h3 class="card-title">Liste des feuilles de temps</h3>
                            <div class="card-tools">
                                @if(auth()->user()->hasPermission('create_timesheets'))
                                     <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTimesheetModal">
                                    <i class="fas fa-plus"></i> Nouvelle Feuille de Temps
                                </button>
                                @endif
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- Filtres -->
                            <div class="row mb-3">
                                <!-- <div class="col-md-2">
                                    <label for="filter_date">Date</label>
                                    <input type="date" class="form-control" id="filter_date">
                                </div> -->
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
                                    <select class="form-control" id="filter_year">
                                        <option value="">Toutes les années</option>
                                        @php
                                            $currentYear = date('Y');
                                            $startYear = $currentYear - 5; // Show last 5 years
                                        @endphp
                                        @for($year = $currentYear; $year >= $startYear; $year--)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                                @if(auth()->user()->hasRole('admin'))
                                <div class="col-md-2">
                                    <label for="filter_utilisateur search_utilisateur">Utilisateur</label>
                                    <select class="form-control search_utilisateur" id="filter_utilisateur">
                                        <option value="">Tous les utilisateurs</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
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
                                    <label for="filter_categorie search_categorie">Catégorie</label>
                                    <select class="form-control search_categorie" id="filter_categorie">
                                        <option value="">Toutes les catégories</option>
                                        @foreach($categories as $categorie)
                                            <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="filter_type">Type</label>
                                    <select class="form-control search_type" id="filter_type">
                                        <option value="">Tous les types</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <!-- <div class="col-md-2">
                                    <label for="filter_description">Description</label>
                                    <input type="text" class="form-control" id="filter_description" placeholder="Rechercher par description">
                                </div>
                                <div class="col-md-2">
                                    <label for="filter_min_total">Total min</label>
                                    <input type="number" class="form-control" id="filter_min_total" placeholder="0.00" step="0.01">
                                </div>
                                <div class="col-md-2">
                                    <label for="filter_max_total">Total max</label>
                                    <input type="number" class="form-control" id="filter_max_total" placeholder="0.00" step="0.01">
                                </div> -->
                                <div class="col-md-12" style="margin-top: 25px;text-align:right">
                                    <button type="button" id="btn_reset_filters" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i> Réinitialiser
                                    </button>
                                    <button type="button" id="btn_apply_filters" class="btn btn-primary">
                                        <i class="fas fa-filter"></i> Appliquer
                                    </button>
                                </div>
                            </div>

                            <!-- Table -->
                            <table id="timesheets-table" class="table table-bordered table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
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
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="9" style="text-align:right">Total:</th>
                                        <th id="total-summary"></th>
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
<div class="modal fade" id="deleteTimesheetModal" tabindex="-1" aria-labelledby="deleteTimesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTimesheetModalLabel">Confirmation de suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette feuille de temps ?</p>
                <p><strong>Date:</strong> <span id="timesheet-date"></span></p>
                <p><strong>Utilisateur:</strong> <span id="timesheet-user"></span></p>
                <p><strong>Dossier:</strong> <span id="timesheet-dossier"></span></p>
                <p class="text-danger"><small>Cette action est irréversible.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirm-timesheet-delete">Supprimer</button>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.2/jquery.sumoselect.min.js"></script>
<script>
    $('.search_utilisateur').SumoSelect({search: true, searchText: 'Sélectionner un utilisateur...'});
    $('.search_dossier').SumoSelect({search: true, searchText: 'Sélectionner un dossier...'});
    $('.search_categorie').SumoSelect({search: true, searchText: 'Sélectionner une catégorie...'});
    $('.search_type').SumoSelect({search: true, searchText: 'Sélectionner un type...'});
   
$(document).ready(function() {
    let timesheetToDelete = null;
    let timesheetRowToDelete = null;

    // Initialize Select2
    // $('.select2').select2({
    //     theme: 'bootstrap4'
    // });

    // DataTable initialization
    var table = $('#timesheets-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("timesheets.data") }}',
            data: function (d) {
                d.date = $('#filter_date').val();
                d.month = $('#filter_month').val();
                d.year = $('#filter_year').val();
                d.utilisateur_id = $('#filter_utilisateur').val();
                d.dossier_id = $('#filter_dossier').val();
                d.categorie_id = $('#filter_categorie').val();
                d.type_id = $('#filter_type').val();
                d.description = $('#filter_description').val();
                d.min_total = $('#filter_min_total').val();
                d.max_total = $('#filter_max_total').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' , visible: false,  searchable: false},
            { 
                data: 'date_timesheet', 
                name: 'date_timesheet',
                render: function(data, type, row) {
                    if (type === 'sort' || type === 'type') {
                        return data;
                    }
                    return data || '-';
                }
            },
            { 
                data: 'description', 
                name: 'description',
                render: function(data) {
                    return data ? (data.length > 50 ? data.substr(0, 50) + '...' : data) : '-';
                }
            },
            { 
                data: 'user', 
                name: 'user.name',
                render: function(data) {
                    return data ? data.name : '-';
                }
            },
            { 
                data: 'dossier', 
                name: 'dossier.numero_dossier',
                render: function(data) {
                    return data ? data.numero_dossier + ' - ' + (data.client ? data.client.name : '') : '';
                }
            },
            { 
                data: 'categorie_relation', 
                name: 'categorie_relation.nom',
                render: function(data) {
                    return data ? data.nom : '-';
                }
            },
            { 
                data: 'type_relation', 
                name: 'type_relation.nom',
                render: function(data) {
                    return data ? data.nom : '-';
                }
            },
            { 
                data: 'quantite', 
                name: 'quantite',
                render: function(data) {
                    return data ? parseInt(data) : '0.00';
                }
            },
            { 
                data: 'prix', 
                name: 'prix',
                render: function(data) {
                    return data || '0,00 DT';
                }
            },
            { 
                data: 'total', 
                name: 'total',
                render: function(data) {
                    return data || '0,00 DT';
                }
            },
            {
    data: 'action',
    name: 'action',
    orderable: false,
    searchable: false,
    render: function(data, type, row) {
        var actions = '<div class="btn-group">';
        
        @if(auth()->user()->hasPermission('view_timesheets'))
            actions += '<button type="button" class="btn btn-info btn-sm view-timesheet-btn" data-id="' + row.id + '" title="Voir"><i class="fas fa-eye"></i></button>';
        @endif
        
        @if(auth()->user()->hasPermission('edit_timesheets'))
            actions += '<button type="button" class="btn btn-primary btn-sm edit-timesheet-btn" data-id="' + row.id + '" title="Modifier"><i class="fas fa-edit"></i></button>';
        @endif

        @if(auth()->user()->hasPermission('delete_timesheets'))
            actions += '<button type="button" class="btn btn-danger btn-sm delete-timesheet-btn" data-id="' + row.id + '" data-date="' + (row.date_timesheet || '') + '" data-user="' + (row.user ? row.user.name : '') + '" data-dossier="' + (row.dossier ? row.dossier.numero_dossier : '') + '" title="Supprimer"><i class="fas fa-trash"></i></button>';
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
        
        drawCallback: function(settings) {
            // Calculate total
            var api = this.api();
            var total = api.column(9, {search: 'applied'}).data().reduce(function(a, b) {
                var numericValue = parseFloat(
                    b.replace(/[^\d,]/g, '')
                     .replace(',', '.')
                     .replace(/\s/g, '')
                );
                return a + (numericValue || 0);
            }, 0);
            
            // Formater le total avec le même format
            var formattedTotal = total.toFixed(2)
                .replace('.', ',')
                .replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            
            $('#total-summary').text(formattedTotal + ' DT');
        }
    });

    // Delete button click handler
    $(document).on('click', '.delete-timesheet-btn', function() {
        const timesheetId = $(this).data('id');
        const timesheetDate = $(this).data('date');
        const timesheetUser = $(this).data('user');
        const timesheetDossier = $(this).data('dossier');
        
        timesheetToDelete = timesheetId;
        timesheetRowToDelete = $(this).closest('tr');
        
        // Populate modal with timesheet details
        $('#timesheet-date').text(timesheetDate || 'Non spécifiée');
        $('#timesheet-user').text(timesheetUser || 'Non spécifié');
        $('#timesheet-dossier').text(timesheetDossier || 'Non spécifié');
        
        $('#deleteTimesheetModal').modal('show');
    });
    
    // Confirm delete button handler
    $('#confirm-timesheet-delete').on('click', function() {
        if (!timesheetToDelete) return;
        
        const deleteButton = $(this);
        const originalText = deleteButton.html();
        
        // Show loading state
        deleteButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Suppression...');
        
        $.ajax({
            url: '/time-sheets/' + timesheetToDelete,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteTimesheetModal').modal('hide');
                
                if (response.success) {
                    // Remove the row from the table
                    if (timesheetRowToDelete) {
                        timesheetRowToDelete.fadeOut(300, function() {
                            table.row($(this)).remove().draw(false);
                        });
                    } else {
                        // Fallback: reload the table
                        table.ajax.reload();
                    }
                    
                    // Show success message
                    showAlert('success', response.message || 'Feuille de temps supprimée avec succès!');
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la suppression.');
                }
            },
            error: function(xhr) {
                $('#deleteTimesheetModal').modal('hide');
                
                let errorMessage = 'Une erreur est survenue lors de la suppression de la feuille de temps.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showAlert('danger', errorMessage);
            },
            complete: function() {
                // Reset button state
                deleteButton.prop('disabled', false).html(originalText);
                timesheetToDelete = null;
                timesheetRowToDelete = null;
            }
        });
    });

    // Function to show alert messages
    function showAlert(type, message) {
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
        
        // Remove any existing alerts
        $('.alert-dismissible').remove();
        
        // Prepend the new alert
        $('.card').before(alertHtml);
        
        // Auto-remove alert after 5 seconds
        setTimeout(function() {
            $('.alert-dismissible').fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    }

    // Apply filters
    $('#btn_apply_filters').click(function() {
        table.ajax.reload();
    });

    // Reset filters
    $('#btn_reset_filters').click(function() {
        $('#filter_date').val('');
        $('#filter_month').val('').trigger('change');
        $('#filter_year').val('').trigger('change');
        $('#filter_utilisateur').val('').trigger('change');
        $('#filter_dossier').val('').trigger('change');
        $('#filter_categorie').val('').trigger('change');
        $('#filter_type').val('').trigger('change');
        $('#filter_description').val('');
        $('#filter_min_total').val('');
        $('#filter_max_total').val('');
        table.ajax.reload();
    });

    // Apply filters on Enter key in text inputs
    $('#filter_description, #filter_min_total, #filter_max_total').keypress(function(e) {
        if (e.which == 13) {
            table.ajax.reload();
        }
    });

    // Close modal when clicking the X button
    $('#deleteTimesheetModal .close, #deleteTimesheetModal [data-dismiss="modal"]').on('click', function() {
        timesheetToDelete = null;
        timesheetRowToDelete = null;
    });
    
    // Handle escape key to close modal
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#deleteTimesheetModal').is(':visible')) {
            timesheetToDelete = null;
            timesheetRowToDelete = null;
        }
    });

    // ==============================================
// FONCTIONS POUR LE MODAL "SHOW" (VISUALISATION)
// ==============================================

// Gestionnaire de clic pour le bouton "Voir"
$(document).on('click', '.view-timesheet-btn', function() {
    const timesheetId = $(this).data('id');
    loadTimesheetDetails(timesheetId);
});

// Fonction pour charger les détails
function loadTimesheetDetails(timesheetId) {
    console.log('Chargement des détails pour ID:', timesheetId);
    
    // Afficher le modal
    $('#showTimesheetModal').modal('show');
    
    // Afficher le loader, cacher les détails
    $('#show-loading-section').show();
    $('#show-details-section').hide().empty();
    
    $.ajax({
        url: '/time-sheets/details/' + timesheetId,
        type: 'GET',
        dataType: 'json',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            console.log('Réponse détails:', response);
            
            if (response.success && response.timesheet) {
                // Générer le HTML des détails
                generateShowDetails(response);
            } else {
                showErrorInModal('show', 'Erreur: ' + (response.error || 'Données non disponibles'));
            }
        },
        error: function(xhr, status, error) {
            console.error('Erreur AJAX show:', xhr);
            showErrorInModal('show', 'Erreur ' + xhr.status + ': ' + error);
        }
    });
}

// Fonction pour générer les détails HTML
function generateShowDetails(data) {
    const timesheet = data.timesheet;
    
    const detailsHtml = `
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
                                <tr>
                                    <th>Date</th>
                                    <td>${timesheet.date_timesheet}</td>
                                </tr>
                                <tr>
                                    <th>Utilisateur</th>
                                    <td>
                                        <strong>${timesheet.user.name}</strong>
                                        ${timesheet.user.fonction ? `<br><small class="text-muted">${timesheet.user.fonction}</small>` : ''}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dossier</th>
                                    <td>
                                        ${timesheet.dossier ? 
                                            `<a href="/dossiers/${timesheet.dossier.id}" target="_blank" class="text-primary">
                                                <i class="fas fa-eye"></i> ${timesheet.dossier.numero_dossier}
                                            </a>
                                            ${timesheet.dossier.nom_dossier ? `<br><small class="text-muted">${timesheet.dossier.nom_dossier}</small>` : ''}`
                                            : '<span class="text-muted">Non assigné</span>'}
                                    </td>
                                </tr>
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
                                <tr>
                                    <th>Quantité</th>
                                    <td>${timesheet.quantite}</td>
                                </tr>
                                <tr>
                                    <th>Prix unitaire</th>
                                    <td>${timesheet.prix}</td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td class="font-weight-bold text-success" style="font-size: 1.2em;">
                                        ${timesheet.total}
                                    </td>
                                </tr>
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
                                <tr>
                                    <th>Catégorie</th>
                                    <td>${timesheet.categorie}</td>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <td>${timesheet.type}</td>
                                </tr>
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
                                <tr>
                                    <th>Créé le</th>
                                    <td>${timesheet.created_at}</td>
                                </tr>
                                <tr>
                                    <th>Modifié le</th>
                                    <td>${timesheet.updated_at}</td>
                                </tr>
                                <tr>
                                    <th>Statut</th>
                                    <td>
                                        <span class="badge badge-success">Actif</span>
                                    </td>
                                </tr>
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
                            <p class="mb-0" style="white-space: pre-wrap;">${timesheet.description}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pièce jointe -->
            ${timesheet.file_path ? `
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0 section-title">Pièce jointe</h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-success">
                                <i class="fas fa-file"></i>
                                <a href="/storage/${timesheet.file_path}" target="_blank" class="ml-2">
                                    ${timesheet.file_name}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>` : ''}

            
        </div>
    `;
    
    // Injecter le HTML
    $('#show-details-section').html(detailsHtml);
    
    // Cacher le loader, montrer les détails
    $('#show-loading-section').hide();
    $('#show-details-section').show();
}

// Fonction pour afficher les erreurs dans le modal
function showErrorInModal(modalType, message) {
    const loaderId = modalType === 'show' ? '#show-loading-section' : '#loading-section';
    $(loaderId).html(`
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            ${message}
            <br>
            <button class="btn btn-sm btn-secondary mt-2" onclick="$('#${modalType}TimesheetModal').modal('hide')">
                <i class="fas fa-times"></i> Fermer
            </button>
        </div>
    `);
}

// Fonction pour confirmer la suppression depuis le modal
function confirmDeleteFromModal(timesheetId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette feuille de temps ? Cette action est irréversible.')) {
        deleteTimesheet(timesheetId);
    }
}

// Fonction pour supprimer une feuille de temps
function deleteTimesheet(timesheetId) {
    $.ajax({
        url: '/time-sheets/' + timesheetId,
        type: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                // Fermer tous les modals
                $('#showTimesheetModal').modal('hide');
                $('#editTimesheetModal').modal('hide');
                
                // Afficher le message de succès
                showAlert('success', response.message || 'Feuille de temps supprimée avec succès!');
                
                // Recharger le DataTable
                table.ajax.reload();
            } else {
                showAlert('danger', response.message || 'Erreur lors de la suppression.');
            }
        },
        error: function(xhr) {
            let errorMessage = 'Une erreur est survenue lors de la suppression de la feuille de temps.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showAlert('danger', errorMessage);
        }
    });
}

// Fonction pour ouvrir le modal d'édition depuis le modal show
function openEditFromShow(timesheetId) {
    $('#showTimesheetModal').modal('hide');
    setTimeout(() => {
        loadTimesheetForEdit(timesheetId);
    }, 300);
}

// Initialiser quand le modal show se ferme
$('#showTimesheetModal').on('hidden.bs.modal', function() {
    $('#show-loading-section').show().html(`
        <div class="text-center p-4">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p class="mt-2">Chargement des détails...</p>
        </div>
    `);
    $('#show-details-section').hide().empty();
});

    // ==============================================
    // GESTION DU MODAL D'ÉDITION
    // ==============================================

    // Gestionnaire de clic pour le bouton d'édition
    $(document).on('click', '.edit-timesheet-btn', function() {
        const timesheetId = $(this).data('id');
        loadTimesheetForEdit(timesheetId);
    });

    // Fonction pour charger les données d'édition
// Fonction pour charger les données d'édition
function loadTimesheetForEdit(timesheetId) {
    console.log('Chargement des données pour ID:', timesheetId);
    
    // Afficher le modal avec un loader
    $('#editTimesheetModal').modal('show');
    
    // Réinitialiser le formulaire
    resetEditForm();
    
    // Afficher un message de chargement
    // $('#edit_tracking_info').html(`
    //     <div class="text-center">
    //         <i class="fas fa-spinner fa-spin"></i>
    //         <p class="mt-2">Chargement des données...</p>
    //     </div>
    // `);
    
    // URL AJAX
    const url = '/time-sheets/ajax/' + timesheetId;
    console.log('URL AJAX:', url);
    
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            console.log('Réponse AJAX complète:', response);
            console.log('Données timesheet:', response.timesheet);
            
            if (response.success && response.timesheet) {
                // Vérifier que l'ID n'est pas null
                if (!response.timesheet.id) {
                    console.error('ID est null dans la réponse!');
                    showAlert('danger', 'Erreur: ID non trouvé dans la réponse');
                    return;
                }
                
                // Remplir le formulaire avec les données
                fillEditForm(response);
                showAlert('success', 'Données chargées avec succès!');
            } else {
                console.error('Erreur dans la réponse:', response);
                $('#editTimesheetModal').modal('hide');
                showAlert('danger', 'Erreur: ' + (response.error || 'Données non disponibles'));
            }
        },
        error: function(xhr, status, error) {
            console.error('Erreur AJAX:', {
                status: xhr.status,
                statusText: xhr.statusText,
                responseText: xhr.responseText,
                error: error
            });
            
            showAlert('danger', 'Erreur ' + xhr.status + ': ' + error);
            $('#editTimesheetModal').modal('hide');
        }
    });
}

// Fonction pour réinitialiser le formulaire
function resetEditForm() {
    $('#editTimesheetForm')[0].reset();
    $('#edit_timesheet_id').val('');
    $('.select2-edit').val('').trigger('change');
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');
    $('#edit_total_calcule').val('0,00 DT');
    $('#created_at').text('');
    $('#updated_at').text('');
}

// Fonction pour remplir le formulaire avec TOUTES les données
function fillEditForm(data) {
    const timesheet = data.timesheet;
    
    console.log('Remplissage du formulaire avec:', timesheet);
    
    // Remplir l'ID caché
    $('#edit_timesheet_id').val(timesheet.id);
    
    // Remplir les champs simples
    $('#edit_date_timesheet').val(timesheet.date_timesheet || '');
    $('#edit_quantite').val(timesheet.quantite || 0);
    $('#edit_prix').val(timesheet.prix || 0);
    $('#edit_description').val(timesheet.description || '');
    
    // Mettre à jour le total
    calculateEditTotal();
    
    // Mettre à jour les informations de suivi
    $('#created_at').text(timesheet.created_at || 'Non disponible');
    $('#updated_at').text(timesheet.updated_at || 'Non disponible');
    
    // Remplir les selects (ATTENDRE que Select2 soit initialisé)
    setTimeout(function() {
        // Remplir les options des selects d'abord
        populateSelectOptions(data);
        
        // Puis sélectionner les valeurs
        if (timesheet.utilisateur_id) {
            $('#edit_utilisateur_id').val(timesheet.utilisateur_id).trigger('change');
        }
        if (timesheet.dossier_id) {
            $('#edit_dossier_id').val(timesheet.dossier_id).trigger('change');
        }
        if (timesheet.categorie) {
            $('#edit_categorie').val(timesheet.categorie).trigger('change');
        }
        if (timesheet.type) {
            $('#edit_type').val(timesheet.type).trigger('change');
        }
    }, 100);
}

// Fonction pour peupler les options des selects
function populateSelectOptions(data) {
    console.log('Peuplement des options de select:', data);
    
    // Utilisateurs
    const utilisateurSelect = $('#edit_utilisateur_id');
    utilisateurSelect.empty();
    utilisateurSelect.append('<option value="">Sélectionnez un utilisateur</option>');
    data.users.forEach(user => {
        utilisateurSelect.append(new Option(`${user.name} (${user.fonction})`, user.id));
    });
    
    // Dossiers
    const dossierSelect = $('#edit_dossier_id');
    dossierSelect.empty();
    dossierSelect.append('<option value="">Sélectionnez un dossier</option>');
    data.dossiers.forEach(dossier => {
        dossierSelect.append(new Option(dossier.numero_dossier, dossier.id));
    });
    
    // Catégories
    const categorieSelect = $('#edit_categorie');
    categorieSelect.empty();
    categorieSelect.append('<option value="">Sélectionnez une catégorie</option>');
    data.categories.forEach(categorie => {
        categorieSelect.append(new Option(categorie.nom, categorie.id));
    });
    
    // Types
    const typeSelect = $('#edit_type');
    typeSelect.empty();
    typeSelect.append('<option value="">Sélectionnez un type</option>');
    data.types.forEach(type => {
        typeSelect.append(new Option(type.nom, type.id));
    });
}

// Fonction pour calculer le total
function calculateEditTotal() {
    const quantite = parseFloat($('#edit_quantite').val()) || 0;
    const prix = parseFloat($('#edit_prix').val()) || 0;
    const total = quantite * prix;
    
    const formattedTotal = total.toLocaleString('fr-FR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    $('#edit_total_calcule').val(formattedTotal + ' DT');
}

// Initialiser Select2 dans le modal quand il s'ouvre
$('#editTimesheetModal').on('shown.bs.modal', function() {
    console.log('Modal ouvert, initialisation Select2');
    
    $('.select2-edit').select2({
        theme: 'bootstrap4',
        dropdownParent: $('#editTimesheetModal'),
        width: '100%'
    });
    
    // Écouter les changements sur quantité et prix
    $('#edit_quantite, #edit_prix').off('input').on('input', function() {
        calculateEditTotal();
    });
});

// Nettoyer le modal quand il se ferme
$('#editTimesheetModal').on('hidden.bs.modal', function() {
    console.log('Modal fermé, réinitialisation');
    resetEditForm();
});

// Soumission du formulaire d'édition
$(document).on('submit', '#editTimesheetForm', function(e) {
    e.preventDefault();
    submitEditForm();
});

// Fonction pour soumettre le formulaire
function submitEditForm() {
    const form = $('#editTimesheetForm');
    const timesheetId = $('#edit_timesheet_id').val();
    const submitBtn = form.find('button[type="submit"]');
    const originalText = submitBtn.html();
    
    if (!timesheetId) {
        showAlert('danger', 'Erreur: ID de feuille de temps manquant');
        return;
    }
    
    // Désactiver le bouton et afficher le loader
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enregistrement...');
    
    // Valider le formulaire
    if (!validateEditForm()) {
        submitBtn.prop('disabled', false).html(originalText);
        return;
    }
    
    // Créer FormData
    const formData = new FormData(form[0]);
    
    $.ajax({
        url: '/time-sheets/' + timesheetId,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            console.log('Réponse de mise à jour:', response);
            
            if (response.success) {
                showAlert('success', response.message || 'Feuille de temps modifiée avec succès!');
                $('#editTimesheetModal').modal('hide');
                table.ajax.reload();
            } else {
                showFormErrors(response.errors || {});
                showAlert('danger', response.message || 'Erreur lors de la modification.');
            }
            submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Enregistrer');
        },
        error: function(xhr) {
            console.error('Erreur de mise à jour:', xhr);
            
            let errorMessage = 'Une erreur est survenue lors de la modification.';
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                showFormErrors(errors);
                errorMessage = 'Veuillez corriger les erreurs ci-dessus.';
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            showAlert('danger', errorMessage);
            submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Enregistrer');
        }
    });
}
    // Fonction pour valider le formulaire
    function validateEditForm() {
        let isValid = true;
        
        // Réinitialiser les erreurs
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Valider les champs obligatoires
        const requiredFields = [
            { id: 'edit_date_timesheet', name: 'date_timesheet', label: 'Date' },
            { id: 'edit_utilisateur_id', name: 'utilisateur_id', label: 'Utilisateur' },
            { id: 'edit_quantite', name: 'quantite', label: 'Quantité' },
            { id: 'edit_prix', name: 'prix', label: 'Prix' },
            { id: 'edit_description', name: 'description', label: 'Description' }
        ];
        
        requiredFields.forEach(field => {
            const element = $(`#${field.id}`);
            if (!element.val() || element.val().trim() === '') {
                element.addClass('is-invalid');
                $(`#${field.id}_error`).text(`${field.label} est obligatoire`);
                isValid = false;
            }
        });
        
        // Valider la quantité et le prix (doivent être > 0)
        const quantite = parseFloat($('#edit_quantite').val());
        const prix = parseFloat($('#edit_prix').val());
        
        if (quantite <= 0) {
            $('#edit_quantite').addClass('is-invalid');
            $('#edit_quantite_error').text('La quantité doit être supérieure à 0');
            isValid = false;
        }
        
        if (prix <= 0) {
            $('#edit_prix').addClass('is-invalid');
            $('#edit_prix_error').text('Le prix doit être supérieur à 0');
            isValid = false;
        }
        
        return isValid;
    }

    // Fonction pour afficher les erreurs de formulaire
    function showFormErrors(errors) {
        // Réinitialiser les erreurs
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Afficher les nouvelles erreurs
        Object.keys(errors).forEach(field => {
            const errorMessages = errors[field];
            const fieldId = `edit_${field}`;
            const errorFieldId = `edit_${field}_error`;
            const element = $(`#${fieldId}`);
            
            if (element.length) {
                element.addClass('is-invalid');
                $(`#${errorFieldId}`).text(errorMessages.join(', '));
            }
        });
    }

    // Initialiser Select2 dans le modal quand il s'ouvre
    $('#editTimesheetModal').on('shown.bs.modal', function () {
        $('.select2-edit').select2({
            theme: 'bootstrap4',
            dropdownParent: $('#editTimesheetModal')
        });
    });

    // Nettoyer le modal quand il se ferme
    $('#editTimesheetModal').on('hidden.bs.modal', function () {
        resetEditForm();
    });
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
    #total-summary {
        font-weight: bold;
        color: #28a745;
    }
    .delete-timesheet-btn, .edit-timesheet-btn {
        transition: all 0.3s ease;
    }
    .delete-timesheet-btn:hover, .edit-timesheet-btn:hover {
        transform: scale(1.05);
    }

    /* Styles spécifiques au modal d'édition */
    .select2-edit-container {
        z-index: 9999 !important;
    }

    .modal-xl {
        max-width: 1140px;
    }

    #editTimesheetForm .is-invalid {
        border-color: #dc3545;
    }

    #editTimesheetForm .invalid-feedback {
        display: block;
    }
</style>
@include('timesheets.create')
@include('timesheets.edit')
@include('timesheets.show')
@endsection