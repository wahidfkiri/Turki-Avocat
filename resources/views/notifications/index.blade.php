@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Notifications</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Notifications</li>
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
                    <!-- Notifications Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalNotifications }}</h3>
                                    <p>Total des notifications</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-bell"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $unreadNotifications }}</h3>
                                    <p>Non lues</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $readNotifications }}</h3>
                                    <p>Lues</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-envelope-open"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-secondary">
                                <div class="inner">
                                    <h3>{{ $todayNotifications }}</h3>
                                    <p>Aujourd'hui</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications Filters -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Filtres et Actions</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="btn-group mb-3">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            Actions groupées
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#" id="markAllRead">
                                                <i class="fas fa-envelope-open mr-2"></i>Marquer toutes comme lues
                                            </a>
                                            <a class="dropdown-item" href="#" id="markSelectedRead">
                                                <i class="fas fa-check-circle mr-2"></i>Marquer la sélection comme lue
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="#" id="deleteSelected">
                                                <i class="fas fa-trash mr-2"></i>Supprimer la sélection
                                            </a>
                                            <a class="dropdown-item text-danger" href="#" id="deleteAllRead">
                                                <i class="fas fa-trash-alt mr-2"></i>Supprimer toutes les lues
                                            </a>
                                        </div>
                                    </div>

                                    <div class="btn-group mb-3 ml-2">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            Filtrer par statut
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}">
                                                Toutes ({{ $totalNotifications }})
                                            </a>
                                            <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'unread']) }}">
                                                Non lues ({{ $unreadNotifications }})
                                            </a>
                                            <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'read']) }}">
                                                Lues ({{ $readNotifications }})
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <form method="GET" action="{{ route('notifications.index') }}">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-default">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications List -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Liste des notifications</h3>
                            <div class="card-tools">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="selectAllCheckbox">
                                    <label class="form-check-label" for="selectAllCheckbox">Tout sélectionner</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($notifications->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th width="30">
                                                    <input type="checkbox" id="selectAllHeader">
                                                </th>
                                                <th width="40">Statut</th>
                                                <th>Message</th>
                                                <th width="150">Date</th>
                                                <th width="100">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($notifications as $notification)
                                                <tr class="{{ $notification->is_read ? '' : 'bg-light' }}" data-notification-id="{{ $notification->id }}">
                                                    <td>
                                                        <input type="checkbox" class="notification-checkbox" value="{{ $notification->id }}">
                                                    </td>
                                                    <td>
                                                        @if($notification->is_read)
                                                            <span class="badge badge-success" title="Lu">
                                                                <i class="fas fa-envelope-open"></i>
                                                            </span>
                                                        @else
                                                            <span class="badge badge-warning" title="Non lu">
                                                                <i class="fas fa-envelope"></i>
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="notification-message">
                                                            <strong>{{ $notification->message }}</strong>
                                                            @if($notification->data)
                                                                <br>
                                                                <small class="text-muted">
                                                                    @php
                                                                        $data = json_decode($notification->data, true);
                                                                        if(isset($data['description'])) {
                                                                            echo \Illuminate\Support\Str::limit($data['description'], 100);
                                                                        }
                                                                    @endphp
                                                                </small>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">
                                                            <i class="far fa-clock mr-1"></i>
                                                            {{ $notification->created_at->diffForHumans() }}
                                                        </small>
                                                        <br>
                                                        <small>
                                                            {{ $notification->created_at->format('d/m/Y H:i') }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            @if(!$notification->is_read)
                                                                <button class="btn btn-sm btn-outline-success mark-read-btn" 
                                                                        data-id="{{ $notification->id }}"
                                                                        title="Marquer comme lu">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            @else
                                                                <button class="btn btn-sm btn-outline-warning mark-unread-btn" 
                                                                        data-id="{{ $notification->id }}"
                                                                        title="Marquer comme non lu">
                                                                    <i class="fas fa-envelope"></i>
                                                                </button>
                                                            @endif
                                                            <button class="btn btn-sm btn-outline-danger delete-btn" 
                                                                    data-id="{{ $notification->id }}"
                                                                    title="Supprimer">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">Aucune notification</h4>
                                    <p class="text-muted">Vous n'avez aucune notification pour le moment.</p>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer clearfix">
                            <div class="float-left">
                                <div class="dataTables_info">
                                    Affichage de {{ $notifications->firstItem() }} à {{ $notifications->lastItem() }} sur {{ $notifications->total() }} notifications
                                </div>
                            </div>
                            <div class="float-right">
                                {{ $notifications->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation de suppression</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette notification ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Supprimer</button>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {
    let currentNotificationId = null;

    // Select all functionality
    $('#selectAllHeader').change(function() {
        $('.notification-checkbox').prop('checked', this.checked);
    });

    $('.notification-checkbox').change(function() {
        if (!this.checked) {
            $('#selectAllHeader').prop('checked', false);
        } else {
            const allChecked = $('.notification-checkbox:checked').length === $('.notification-checkbox').length;
            $('#selectAllHeader').prop('checked', allChecked);
        }
    });

    // Mark as read
    $('.mark-read-btn').click(function() {
        const notificationId = $(this).data('id');
        markAsRead(notificationId, $(this).closest('tr'));
    });

    // Mark as unread
    $('.mark-unread-btn').click(function() {
        const notificationId = $(this).data('id');
        markAsUnread(notificationId, $(this).closest('tr'));
    });

    // Delete single notification
    $('.delete-btn').click(function() {
        currentNotificationId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    // Confirm delete
    $('#confirmDelete').click(function() {
        if (currentNotificationId) {
            deleteNotification(currentNotificationId);
        }
    });

    // Bulk actions
    $('#markAllRead').click(function(e) {
        e.preventDefault();
        if (confirm('Marquer toutes les notifications comme lues ?')) {
            markAllAsRead();
        }
    });

    $('#markSelectedRead').click(function(e) {
        e.preventDefault();
        const selectedIds = getSelectedNotificationIds();
        if (selectedIds.length > 0) {
            markMultipleAsRead(selectedIds);
        } else {
            alert('Veuillez sélectionner au moins une notification.');
        }
    });

    $('#deleteSelected').click(function(e) {
        e.preventDefault();
        const selectedIds = getSelectedNotificationIds();
        if (selectedIds.length > 0) {
            if (confirm(`Supprimer ${selectedIds.length} notification(s) sélectionnée(s) ?`)) {
                deleteMultipleNotifications(selectedIds);
            }
        } else {
            alert('Veuillez sélectionner au moins une notification.');
        }
    });

    $('#deleteAllRead').click(function(e) {
        e.preventDefault();
        if (confirm('Supprimer toutes les notifications lues ? Cette action est irréversible.')) {
            deleteAllReadNotifications();
        }
    });

    // Helper functions
    function getSelectedNotificationIds() {
        return $('.notification-checkbox:checked').map(function() {
            return $(this).val();
        }).get();
    }

    function markAsRead(notificationId, row) {
        $.ajax({
            url: `/notifications/${notificationId}/read`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    row.removeClass('bg-light');
                    row.find('.badge').removeClass('badge-warning').addClass('badge-success')
                        .html('<i class="fas fa-envelope-open"></i>');
                    row.find('.mark-read-btn').removeClass('btn-outline-success mark-read-btn')
                        .addClass('btn-outline-warning mark-unread-btn')
                        .html('<i class="fas fa-envelope"></i>')
                        .off('click').click(function() {
                            markAsUnread(notificationId, row);
                        });
                    updateCounters();
                    showToast('Notification marquée comme lue', 'success');
                }
            },
            error: function() {
                showToast('Erreur lors du marquage comme lu', 'error');
            }
        });
    }

    function markAsUnread(notificationId, row) {
        $.ajax({
            url: `/notifications/${notificationId}/unread`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    row.addClass('bg-light');
                    row.find('.badge').removeClass('badge-success').addClass('badge-warning')
                        .html('<i class="fas fa-envelope"></i>');
                    row.find('.mark-unread-btn').removeClass('btn-outline-warning mark-unread-btn')
                        .addClass('btn-outline-success mark-read-btn')
                        .html('<i class="fas fa-check"></i>')
                        .off('click').click(function() {
                            markAsRead(notificationId, row);
                        });
                    updateCounters();
                    showToast('Notification marquée comme non lue', 'success');
                }
            },
            error: function() {
                showToast('Erreur lors du marquage comme non lu', 'error');
            }
        });
    }

    function deleteNotification(notificationId) {
        $.ajax({
            url: `/notifications/${notificationId}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $(`[data-notification-id="${notificationId}"]`).fadeOut(300, function() {
                        $(this).remove();
                        updateCounters();
                        checkEmptyState();
                    });
                    $('#deleteModal').modal('hide');
                    showToast('Notification supprimée avec succès', 'success');
                }
            },
            error: function() {
                showToast('Erreur lors de la suppression', 'error');
                $('#deleteModal').modal('hide');
            }
        });
    }

    function markAllAsRead() {
        $.ajax({
            url: '{{ route("notifications.markAllRead") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            },
            error: function() {
                showToast('Erreur lors du marquage de toutes les notifications', 'error');
            }
        });
    }

    function markMultipleAsRead(ids) {
        $.ajax({
            url: '{{ route("notifications.markMultipleRead") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ids: ids
            },
            success: function(response) {
                if (response.success) {
                    ids.forEach(id => {
                        const row = $(`[data-notification-id="${id}"]`);
                        markAsRead(id, row);
                    });
                }
            },
            error: function() {
                showToast('Erreur lors du marquage des notifications', 'error');
            }
        });
    }

    function deleteMultipleNotifications(ids) {
        $.ajax({
            url: '{{ route("notifications.deleteMultiple") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ids: ids,
                _method: 'DELETE'
            },
            success: function(response) {
                if (response.success) {
                    ids.forEach(id => {
                        $(`[data-notification-id="${id}"]`).fadeOut(300, function() {
                            $(this).remove();
                        });
                    });
                    setTimeout(() => {
                        updateCounters();
                        checkEmptyState();
                    }, 300);
                    showToast('Notifications supprimées avec succès', 'success');
                }
            },
            error: function() {
                showToast('Erreur lors de la suppression des notifications', 'error');
            }
        });
    }

    function deleteAllReadNotifications() {
        $.ajax({
            url: '{{ route("notifications.deleteAllRead") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            },
            error: function() {
                showToast('Erreur lors de la suppression des notifications lues', 'error');
            }
        });
    }

    function updateCounters() {
        // You can implement AJAX counter updates here if needed
        // For now, we'll reload the page to get updated counters
        // location.reload();
    }

    function checkEmptyState() {
        if ($('.notification-checkbox').length === 0) {
            $('.card-body').html(`
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Aucune notification</h4>
                    <p class="text-muted">Vous n'avez aucune notification pour le moment.</p>
                </div>
            `);
        }
    }

    function showToast(message, type = 'info') {
        // Simple toast implementation
        const toast = $(`<div class="alert alert-${type} alert-dismissible fade show" style="position: fixed; top: 20px; right: 20px; z-index: 10000;">
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>`);
        $('body').append(toast);
        setTimeout(() => toast.alert('close'), 3000);
    }
});
</script>
<style>
.notification-message {
    max-width: 400px;
    word-wrap: break-word;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.075);
}

.btn-group .btn {
    margin-right: 2px;
}

.small-box .icon {
    font-size: 70px;
}
</style>
@endsection