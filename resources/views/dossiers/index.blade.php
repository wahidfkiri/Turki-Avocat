@extends('layouts.app')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Gestion des Dossiers</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
              <li class="breadcrumb-item active">Dossiers</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

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
                <h3 class="card-title">Liste des Dossiers</h3>
                @if(auth()->user()->hasPermission('create_dossiers'))
                <div class="card-tools">
                  <a href="{{ route('dossiers.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nouveau Dossier
                  </a>
                </div>
                @endif
              </div>
              
              <div class="card-body">
                <!-- Search and Filter Form -->
                <div class="row mb-3">
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Rechercher par numéro ou nom..." id="globalSearch">
                      <div class="input-group-append">
                        <button type="button" class="btn btn-secondary" id="resetSearch">
                          <i class="fas fa-times"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-md-6">
                        <select class="form-control" id="domaineFilter">
                          <option value="">Tous les domaines</option>
                          @foreach($domaines as $domaine)
                            <option value="{{ $domaine->nom }}">{{ $domaine->nom }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-6">
                        <select class="form-control" id="statutFilter">
                          <option value="">Tous les statuts</option>
                          <option value="conseil">Conseil</option>
                          <option value="contentieux">Contentieux</option>
                          <option value="archive">Archivé</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <table id="dossiersTable" class="table table-bordered table-striped w-100">
                  <thead>
                  <tr>
                    <th>Numéro</th>
                    <th>Nom du Dossier</th>
                    <th>Objet du Dossier</th>
                    <th>Date Entrée</th>
                    <th>Type</th>
                    <th>Archivé</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    <!-- Data will be loaded via AJAX -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Confirmation Modal -->
  <div class="modal fade" id="deleteDossierModal" tabindex="-1" aria-labelledby="deleteDossierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteDossierModalLabel">Confirmation de suppression</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Êtes-vous sûr de vouloir supprimer le dossier <strong id="dossier-numero"></strong> - <strong id="dossier-nom"></strong> ?</p>
          <p class="text-danger"><small>Cette action est irréversible. Toutes les données associées à ce dossier seront perdues.</small></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          <button type="button" class="btn btn-danger" id="confirm-dossier-delete">Supprimer</button>
        </div>
      </div>
    </div>
  </div>

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    let table;
    
    function initializeDataTable() {
        // Destroy existing instance if it exists
        if ($.fn.DataTable.isDataTable('#dossiersTable')) {
            $('#dossiersTable').DataTable().destroy();
        }
        
        table = $('#dossiersTable').DataTable({
            processing: true,
            serverSide: true,
            searching: false, // ← DISABLE DataTables default search
            ajax: {
                url: '{{ route("dossiers.index") }}',
                data: function (d) {
                    // Pass custom search parameters
                    d.domaineFilter = $('#domaineFilter').val();
                    d.statutFilter = $('#statutFilter').val();
                    d.globalSearch = $('#globalSearch').val();
                }
            },
            columns: [
                { data: 'numero_dossier', name: 'numero_dossier' },
                { data: 'nom_dossier', name: 'nom_dossier' },
                { data: 'objet', name: 'objet' },
                { data: 'date_entree', name: 'date_entree' },
                { data: 'type_badge', name: 'type_badge', orderable: false, searchable: false },
                { data: 'archive_text', name: 'archive' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json',
                // Remove search text from language since we're not using it
                search: "",
                searchPlaceholder: ""
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"p>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>', // Remove search box from dom
            pageLength: 25,
            responsive: true
        });
    }
    
    // Initialize DataTable
    initializeDataTable();
    
    let dossierToDelete = null;
    
    // Global search handler with debounce
    let searchTimeout;
    $('#globalSearch').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            table.draw();
        }, 500); // Wait 500ms after user stops typing
    });

    // Filter handlers
    $('#domaineFilter, #statutFilter').on('change', function() {
        table.draw();
    });

    // Reset search handler
    $('#resetSearch').on('click', function() {
        $('#globalSearch').val('');
        table.draw();
    });

    // Delete button handler (using event delegation)
    $(document).on('click', '.delete-dossier-btn', function() {
        const dossierId = $(this).data('id');
        const dossierNumero = $(this).data('numero');
        const dossierNom = $(this).data('nom');
        
        dossierToDelete = dossierId;
        $('#dossier-numero').text(dossierNumero);
        $('#dossier-nom').text(dossierNom);
        $('#deleteDossierModal').modal('show');
    });
    
    // Confirm delete button handler
    $('#confirm-dossier-delete').on('click', function() {
        if (!dossierToDelete) return;
        
        const deleteButton = $(this);
        const originalText = deleteButton.html();
        
        // Show loading state
        deleteButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Suppression...');
        
        $.ajax({
            url: '{{ route("dossiers.destroy", "") }}/' + dossierToDelete,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteDossierModal').modal('hide');
                
                // Refresh the DataTable
                table.draw();
                
                // Show success message
                showAlert('success', 'Dossier supprimé avec succès!');
            },
            error: function(xhr) {
                $('#deleteDossierModal').modal('hide');
                
                let errorMessage = 'Une erreur est survenue lors de la suppression du dossier.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showAlert('danger', errorMessage);
            },
            complete: function() {
                // Reset button state
                deleteButton.prop('disabled', false).html(originalText);
                dossierToDelete = null;
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
});
</script>
@endsection