@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Sauvegardes de la Base de Données</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Sauvegardes DB</li>
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
                            <h3 class="card-title">Gestion des Sauvegardes</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-sm" id="createBackupBtn">
                                    <i class="fas fa-database"></i> Nouvelle Sauvegarde DB
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- Database Info -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fas fa-database"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Taille de la Base de Données</span>
                                            <span class="info-box-number">{{ $databaseSize }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success"><i class="fas fa-save"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Nombre de Sauvegardes</span>
                                            <span class="info-box-number">{{ count($backupFiles) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(count($backupFiles) > 0)
                            <table id="backupsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom du fichier</th>
                                        <th>Taille</th>
                                        <th>Date de création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($backupFiles as $index => $backup)
                                    <tr id="backup-row-{{ $index }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <i class="fas fa-database text-primary mr-2"></i>
                                            {{ basename($backup['filename']) }}
                                        </td>
                                    
                                        <td>{{ $backup['date'] }}</td>
                                        <td><span class="badge badge-secondary">{{ $backup['age'] }}</span></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('backups.download', $backup['filename']) }}" 
                                                   class="btn btn-success" title="Télécharger">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger delete-backup-btn" 
                                                        title="Supprimer" 
                                                        data-filename="{{ $backup['filename'] }}"
                                                        data-basename="{{ basename($backup['filename']) }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div class="alert alert-warning text-center">
                                <h5><i class="icon fas fa-exclamation-triangle"></i> Aucune sauvegarde trouvée</h5>
                                <p>Cliquez sur "Nouvelle Sauvegarde DB" pour créer votre première sauvegarde de base de données.</p>
                            </div>
                            @endif
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
<div class="modal fade" id="deleteBackupModal" tabindex="-1" aria-labelledby="deleteBackupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteBackupModalLabel">Confirmation de suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer la sauvegarde <strong id="backup-filename"></strong> ?</p>
                <p class="text-danger"><small>Cette action est irréversible.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirm-backup-delete">Supprimer</button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
                <h5 id="loading-message">Sauvegarde de la base de données en cours...</h5>
                <p class="text-muted">Veuillez patienter, cette opération peut prendre quelques minutes.</p>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {
    let backupToDelete = null;
    let backupRowToDelete = null;

    // Create backup button handler
    $('#createBackupBtn').on('click', function() {
        const createButton = $(this);
        const originalText = createButton.html();
        
        // Show loading modal
        $('#loading-message').text('Sauvegarde de la base de données en cours...');
        $('#loadingModal').modal({
            backdrop: 'static',
            keyboard: false
        });
        
        // Disable button
        createButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sauvegarde...');
        
        $.ajax({
            url: '{{ route("backups.create") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#loadingModal').modal('hide');
                
                if (response.success) {
                    showAlert('success', response.message);
                    // Reload the page after a short delay to show the new backup
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    showAlert('danger', response.message);
                }
            },
            error: function(xhr) {
                $('#loadingModal').modal('hide');
                
                let errorMessage = 'Une erreur est survenue lors de la sauvegarde de la base de données.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showAlert('danger', errorMessage);
            },
            complete: function() {
                // Re-enable button
                createButton.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Delete backup button handler
    $(document).on('click', '.delete-backup-btn', function() {
        const filename = $(this).data('filename');
        const basename = $(this).data('basename');
        
        backupToDelete = filename;
        backupRowToDelete = $(this).closest('tr');
        $('#backup-filename').text(basename);
        $('#deleteBackupModal').modal('show');
    });
    
    // Confirm delete button handler
    $('#confirm-backup-delete').on('click', function() {
        if (!backupToDelete) return;
        
        const deleteButton = $(this);
        const originalText = deleteButton.html();
        
        // Show loading state
        deleteButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Suppression...');
        
        $.ajax({
            url: '{{ route("backups.delete", "") }}/' + encodeURIComponent(backupToDelete),
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteBackupModal').modal('hide');
                
                if (response.success) {
                    // Remove the row from the table
                    if (backupRowToDelete) {
                        backupRowToDelete.fadeOut(300, function() {
                            $(this).remove();
                            updateRowNumbers();
                            
                            // Show message if no more backups
                            if ($('#backupsTable tbody tr').length === 0) {
                                location.reload();
                            }
                        });
                    }
                    
                    showAlert('success', response.message);
                } else {
                    showAlert('danger', response.message);
                }
            },
            error: function(xhr) {
                $('#deleteBackupModal').modal('hide');
                
                let errorMessage = 'Une erreur est survenue lors de la suppression.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showAlert('danger', errorMessage);
            },
            complete: function() {
                // Reset button state
                deleteButton.prop('disabled', false).html(originalText);
                backupToDelete = null;
                backupRowToDelete = null;
            }
        });
    });
    
    // Function to update row numbers after deletion
    function updateRowNumbers() {
        $('#backupsTable tbody tr').each(function(index) {
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
    $('#deleteBackupModal .close, #deleteBackupModal [data-dismiss="modal"]').on('click', function() {
        backupToDelete = null;
        backupRowToDelete = null;
    });
});
</script>

<style>
.delete-backup-btn {
    transition: all 0.3s ease;
}
.delete-backup-btn:hover {
    transform: scale(1.05);
}
#createBackupBtn {
    transition: all 0.3s ease;
}
#createBackupBtn:hover {
    transform: translateY(-2px);
}
.table td {
    vertical-align: middle;
}
.info-box {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    border-radius: 0.25rem;
}
</style>
@endsection