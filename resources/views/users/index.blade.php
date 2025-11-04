@extends('layouts.app')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Gestion des utilisateurs</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
              <li class="breadcrumb-item active">Utilisateurs</li>
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
                <h3 class="card-title">Liste des utilisateurs</h3>
                <div class="card-tools">
                  @if(auth()->user()->hasPermission('create_users'))
                  <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nouvel Utilisateur
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
                      <div class="col-md-12">
                        <select class="form-control" id="categorieFilter">
                          <option value="">Toutes les roles</option>
                          @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name  }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <table id="intervenantsTable" class="table table-bordered table-striped w-100">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nom Complet</th>
                    <th>Email</th>
                    <th>Fonction</th>
                    <th>Role</th>
                    <th>Statut</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    @php $counter = 1; @endphp
                    @foreach($users as $user)
                    <tr id="user-row-{{ $user->id }}">
                      <td>{{ $counter++}}</td>
                      <td>{{ $user->name }}</td>
                      <td>{{ $user->email }}</td>
                      <td>{{ $user->fonction }}</td>
                      <td>{{ $user->getRoleNames()->first() }}</td>
                      <td>
                        @if($user->is_active == true)
                        <span class="badge badge-success">Active</span>
                        @else 
                        <span class="badge badge-danger">Inactive</span>
                        @endif
                      </td>
                      <td>
                        <div class="btn-group btn-group-sm">
                          @if(auth()->user()->hasPermission('view_users'))
                          <a href="{{ route('users.show', $user->id) }}" 
                             class="btn btn-info" title="Voir">
                            <i class="fas fa-eye"></i>
                          </a>
                          @endif
                          @if(auth()->user()->hasPermission('edit_users'))
                          <a href="{{ route('users.edit', $user->id) }}" 
                             class="btn btn-warning" title="Modifier">
                            <i class="fas fa-edit"></i>
                          </a>
                          @endif
                          @if(auth()->user()->hasPermission('delete_users'))
                          <button type="button" class="btn btn-danger delete-user-btn" 
                                  title="Supprimer" 
                                  data-id="{{ $user->id }}"
                                  data-name="{{ $user->name }}"
                                  data-email="{{ $user->email }}">
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
  <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteUserModalLabel">Confirmation de suppression</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Êtes-vous sûr de vouloir supprimer l'utilisateur <strong id="user-name"></strong> ?</p>
          <p><strong>Email:</strong> <span id="user-email"></span></p>
          <p class="text-danger"><small>Cette action est irréversible. Toutes les données associées à cet utilisateur seront perdues.</small></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          <button type="button" class="btn btn-danger" id="confirm-user-delete">Supprimer</button>
        </div>
      </div>
    </div>
  </div>
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {
    let userToDelete = null;
    
    // Delete button click handler
    $('.delete-user-btn').on('click', function() {
        const userId = $(this).data('id');
        const userName = $(this).data('name');
        const userEmail = $(this).data('email');
        
        userToDelete = userId;
        $('#user-name').text(userName);
        $('#user-email').text(userEmail);
        $('#deleteUserModal').modal('show');
    });
    
    // Confirm delete button handler
    $('#confirm-user-delete').on('click', function() {
        if (!userToDelete) return;
        
        const deleteButton = $(this);
        const originalText = deleteButton.html();
        
        // Show loading state
        deleteButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Suppression...');
        
        $.ajax({
            url: '{{ route("users.destroy", "") }}/' + userToDelete,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteUserModal').modal('hide');
                
                // Remove the row from the table
                $('#user-row-' + userToDelete).fadeOut(300, function() {
                    $(this).remove();
                    // Update counter numbers
                    updateRowNumbers();
                });
                
                // Show success message
                showAlert('success', 'Utilisateur supprimé avec succès!');
            },
            error: function(xhr) {
                $('#deleteUserModal').modal('hide');
                
                let errorMessage = 'Une erreur est survenue lors de la suppression.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showAlert('danger', errorMessage);
            },
            complete: function() {
                // Reset button state
                deleteButton.prop('disabled', false).html(originalText);
                userToDelete = null;
            }
        });
    });
    
    // Function to update row numbers after deletion
    function updateRowNumbers() {
        $('#intervenantsTable tbody tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }
    
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
    $('#deleteUserModal .close, #deleteUserModal [data-dismiss="modal"]').on('click', function() {
        userToDelete = null;
    });
    
    // Handle escape key to close modal
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#deleteUserModal').is(':visible')) {
            userToDelete = null;
        }
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('#intervenantsTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Reset search
    $('#resetSearch').on('click', function() {
        $('#searchInput').val('');
        $('#intervenantsTable tbody tr').show();
    });

    // Role filter functionality
    $('#categorieFilter').on('change', function() {
        const value = $(this).val().toLowerCase();
        if (value === '') {
            $('#intervenantsTable tbody tr').show();
        } else {
            $('#intervenantsTable tbody tr').filter(function() {
                const role = $(this).find('td:eq(4)').text().toLowerCase();
                $(this).toggle(role.indexOf(value) > -1);
            });
        }
    });
});
</script>

<style>
.delete-user-btn {
    transition: all 0.3s ease;
}
.delete-user-btn:hover {
    transform: scale(1.05);
}
.badge {
    font-size: 0.8em;
    padding: 0.4em 0.6em;
}
</style>
@endsection