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
                                    <a href="{{ route('time-sheets.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Nouvelle feuille de temps
                                    </a>
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
                                    <label for="filter_utilisateur">Utilisateur</label>
                                    <select class="form-control" id="filter_utilisateur">
                                        <option value="">Tous les utilisateurs</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="col-md-2">
                                    <label for="filter_dossier">Dossier</label>
                                    <select class="form-control" id="filter_dossier">
                                        <option value="">Tous les dossiers</option>
                                        @foreach($dossiers as $dossier)
                                            <option value="{{ $dossier->id }}">{{ $dossier->numero_dossier }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="filter_categorie">Catégorie</label>
                                    <select class="form-control" id="filter_categorie">
                                        <option value="">Toutes les catégories</option>
                                        @foreach($categories as $categorie)
                                            <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="filter_type">Type</label>
                                    <select class="form-control" id="filter_type">
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
<script>
$(document).ready(function() {
    let timesheetToDelete = null;
    let timesheetRowToDelete = null;

    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

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
                        actions += '<a href="/time-sheets/' + row.id + '" class="btn btn-info btn-sm" title="Voir"><i class="fas fa-eye"></i></a>';
                    @endif
                    
                    @if(auth()->user()->hasPermission('edit_timesheets'))
                        actions += '<a href="/time-sheets/' + row.id + '/edit" class="btn btn-primary btn-sm" title="Modifier"><i class="fas fa-edit"></i></a>';
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
    .delete-timesheet-btn {
        transition: all 0.3s ease;
    }
    .delete-timesheet-btn:hover {
        transform: scale(1.05);
    }
</style>
@endsection