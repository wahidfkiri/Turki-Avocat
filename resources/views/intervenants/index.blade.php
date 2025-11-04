@extends('layouts.app')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Gestion des Intervenants</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
              <li class="breadcrumb-item active">Intervenants</li>
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
                <h3 class="card-title">Liste des Intervenants</h3>
                <div class="card-tools">
                  @if(auth()->user()->hasPermission('create_intervenants'))
                  <a href="{{ route('intervenants.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nouvel Intervenant
                  </a>
                  @endif
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <!-- Search and Filter Form -->
                <div class="row mb-3">
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Rechercher..." id="searchInput">
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
                        <select class="form-control" id="categorieFilter">
                          <option value="">Toutes les catégories</option>
                          @foreach(['contact','client','avocat','notaire','huissier','juridiction','administrateur judiciaire','mandataire judiciaire','adversaire','expert judiciaire','traducteur'] as $categorie)
                            <option value="{{ $categorie }}">{{ ucfirst($categorie) }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-6">
                        <select class="form-control" id="typeFilter">
                          <option value="">Tous les types</option>
                          @foreach(['personne physique','personne morale','entreprise individuelle'] as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <table id="intervenantsTable" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <!-- <th>ID</th> -->
                    <th>Identité FR</th>
                    <th>Identité AR</th>
                    <!-- <th>Type</th> -->
                    <th>Catégorie</th>
                    <!-- <th>Fonction</th> -->
                    <th>Téléphone</th>
                    <th>Email</th>
                    <!-- <th>Archivé</th> -->
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($intervenants as $intervenant)
                    <tr id="intervenant-row-{{ $intervenant->id }}">
                      <!-- <td>{{ $intervenant->id }}</td> -->
                      <td>{{ $intervenant->identite_fr }}</td>
                      <td>{{ $intervenant->identite_ar ?? 'N/A' }}</td>
                      <!-- <td>{{ $intervenant->type }}</td> -->
                      <td>{{ $intervenant->categorie }}</td>
                      <!-- <td>{{ $intervenant->fonction ?? 'N/A' }}</td> -->
                      <td>{{ $intervenant->portable1 ?? $intervenant->fixe1 ?? 'N/A' }}</td>
                      <td>{{ $intervenant->mail1 ?? 'N/A' }}</td>
                      <!-- <td>{{ $intervenant->archive ? 'Oui' : 'Non' }}</td> -->
                      <td>
                        <div class="btn-group btn-group-sm">
                        @if(auth()->user()->hasPermission('view_intervenants'))
                        <a href="{{ route('intervenants.show', $intervenant->id) }}" 
                             class="btn btn-info" title="Voir">
                            <i class="fas fa-eye"></i>
                          </a>
                          @endif
                          @if(auth()->user()->hasPermission('edit_intervenants'))
                          <a href="{{ route('intervenants.edit', $intervenant->id) }}" 
                             class="btn btn-warning" title="Modifier">
                            <i class="fas fa-edit"></i>
                          </a>
                          @endif
                          @if(auth()->user()->hasPermission('delete_intervenants'))
                          <button type="button" class="btn btn-danger delete-btn" 
                                  title="Supprimer" 
                                  data-id="{{ $intervenant->id }}"
                                  data-name="{{ $intervenant->identite_fr }}">
                            <i class="fas fa-trash"></i>
                          </button>
                          @endif
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
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
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Êtes-vous sûr de vouloir supprimer l'intervenant <strong id="intervenant-name"></strong> ?</p>
          <p class="text-danger"><small>Cette action est irréversible.</small></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          <button type="button" class="btn btn-danger" id="confirm-delete">Supprimer</button>
        </div>
      </div>
    </div>
  </div>
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/custom/intervenant-filter.js') }}"></script>
<script>
$(document).ready(function() {
    let intervenantToDelete = null;
    $('#intervenantsTable_filter').css('display', 'none'); // Hide default search box
    // Delete button click handler
    $('.delete-btn').on('click', function() {
        const intervenantId = $(this).data('id');
        const intervenantName = $(this).data('name');
        
        intervenantToDelete = intervenantId;
        $('#intervenant-name').text(intervenantName);
        $('#deleteModal').modal('show');
    });
    
    // Confirm delete button handler
    $('#confirm-delete').on('click', function() {
        if (!intervenantToDelete) return;
        
        const deleteButton = $(this);
        const originalText = deleteButton.html();
        
        // Show loading state
        deleteButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Suppression...');
        
        $.ajax({
            url: '{{ route("intervenants.destroy", "") }}/' + intervenantToDelete,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteModal').modal('hide');
                
                // Remove the row from the table
                $('#intervenant-row-' + intervenantToDelete).fadeOut(300, function() {
                    $(this).remove();
                });
                
                // Show success message
                showAlert('success', 'Intervenant supprimé avec succès!');
            },
            error: function(xhr) {
                $('#deleteModal').modal('hide');
                
                let errorMessage = 'Une erreur est survenue lors de la suppression.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showAlert('danger', errorMessage);
            },
            complete: function() {
                // Reset button state
                deleteButton.prop('disabled', false).html(originalText);
                intervenantToDelete = null;
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
    
    // Close modal when clicking the X button
    $('.modal .close, .modal [data-dismiss="modal"]').on('click', function() {
        intervenantToDelete = null;
    });
});
</script>
@endsection