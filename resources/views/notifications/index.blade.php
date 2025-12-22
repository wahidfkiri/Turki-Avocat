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
                                    <h3 id="totalCount">{{ $totalNotifications }}</h3>
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
                                    <h3 id="unreadCount">{{ $unreadNotifications }}</h3>
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
                                    <h3 id="readCount">{{ $readNotifications }}</h3>
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
                                    <h3 id="todayCount">{{ $todayNotifications }}</h3>
                                    <p>Aujourd'hui</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Notifications List avec DataTables -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Liste des notifications</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table id="notificationsTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th style="display:none;" width="30">
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
                                                <td style="display:none;" >
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
                                                        <a href="{{ route('notification.show', ['notificationId' => $notification->id]) }}">
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
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <i class="far fa-clock mr-1"></i>
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </small>
                                                    <br>
                                                    <small data-order="{{ $notification->created_at->format('Y-m-d H:i:s') }}">
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

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- CDN DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

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

/* Toast style */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 250px;
}
</style>

<script>
$(document).ready(function() {
    let currentNotificationId = null;
    let dataTable;

    // Initialiser DataTables
    function initializeDataTable() {
        dataTable = $('#notificationsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            },
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tous"]],
            order: [[3, 'desc']], // Trier par date décroissante
            columnDefs: [
                {
                    orderable: false,
                    targets: [0, 4] // Colonnes checkbox et actions non triables
                },
                {
                    type: 'date',
                    targets: 3 // Colonne date pour tri correct
                }
            ],
            initComplete: function() {
                // Réattacher les événements après l'initialisation
                reattachEvents();
            },
            drawCallback: function() {
                // Réattacher les événements après chaque redessin
                reattachEvents();
                updateSelectAllCheckbox();
                updateCountersDisplay();
            }
        });
    }

    // Réattacher les événements aux boutons
    function reattachEvents() {
        // Mark as read
        $('.mark-read-btn').off('click').on('click', function(e) {
            e.stopPropagation();
            const notificationId = $(this).data('id');
            const row = $(this).closest('tr');
            markAsRead(notificationId, row);
        });

        // Mark as unread
        $('.mark-unread-btn').off('click').on('click', function(e) {
            e.stopPropagation();
            const notificationId = $(this).data('id');
            const row = $(this).closest('tr');
            markAsUnread(notificationId, row);
        });

        // Delete single notification
        $('.delete-btn').off('click').on('click', function(e) {
            e.stopPropagation();
            currentNotificationId = $(this).data('id');
            $('#deleteModal').modal('show');
        });

        // Gestion des checkbox
        $('.notification-checkbox').off('change').on('change', function() {
            updateSelectAllCheckbox();
        });
    }

    // Initialiser DataTables
    initializeDataTable();

    // Recherche globale
    $('#globalSearch').on('keyup', function() {
        dataTable.search(this.value).draw();
    });

    // Effacer la recherche
    $('#clearSearch').on('click', function() {
        $('#globalSearch').val('');
        dataTable.search('').draw();
    });

    // Filtre par statut
    $('.filter-status').on('click', function(e) {
        e.preventDefault();
        const status = $(this).data('status');
        
        // Filtrer la colonne Statut (colonne 1)
        dataTable.column(1).search(status === '' ? '' : (status === '1' ? 'Lu' : 'Non lu')).draw();
        
        // Mettre à jour le texte du bouton
        const buttonText = $(this).text();
        $(this).closest('.btn-group').find('.dropdown-toggle').html(buttonText);
    });

    // Sélection multiple
    $('#selectAllHeader').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.notification-checkbox').prop('checked', isChecked).trigger('change');
    });

    $('#selectAllCheckbox').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('#selectAllHeader').prop('checked', isChecked);
        $('.notification-checkbox').prop('checked', isChecked).trigger('change');
    });

    function updateSelectAllCheckbox() {
        const totalCheckboxes = $('.notification-checkbox').length;
        const checkedCheckboxes = $('.notification-checkbox:checked').length;
        
        const allChecked = totalCheckboxes > 0 && checkedCheckboxes === totalCheckboxes;
        $('#selectAllHeader').prop('checked', allChecked);
        $('#selectAllCheckbox').prop('checked', allChecked);
    }

    // Mettre à jour l'affichage des compteurs
    function updateCountersDisplay() {
        const visibleRows = dataTable.rows({ filter: 'applied' }).count();
        const unreadCount = dataTable.rows({ filter: 'applied' }).nodes().to$().filter('.bg-light').length;
        const readCount = visibleRows - unreadCount;
        
        // Mettre à jour les compteurs affichés (optionnel)
        $('#visibleCount').text(visibleRows);
    }

    // ========== FONCTIONS AJAX EXISTANTES ==========

    // Mark as read
    function markAsRead(notificationId, row) {
        $.ajax({
            url: '/notifications/' + notificationId + '/read',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    // Mettre à jour l'interface
                    row.removeClass('bg-light');
                    row.find('.badge').removeClass('badge-warning').addClass('badge-success')
                        .html('<i class="fas fa-envelope-open"></i>');
                    
                    // Changer le bouton
                    row.find('.mark-read-btn')
                        .removeClass('btn-outline-success mark-read-btn')
                        .addClass('btn-outline-warning mark-unread-btn')
                        .html('<i class="fas fa-envelope"></i>')
                        .attr('title', 'Marquer comme non lu');
                    
                    // Mettre à jour les compteurs côté client
                    updateLocalCounters(-1); // -1 non lue, +1 lue
                    showToast('Notification marquée comme lue', 'success');
                    
                    // Réattacher les événements
                    reattachEvents();
                }
            },
            error: function() {
                showToast('Erreur lors du marquage comme lu', 'error');
            }
        });
    }

    // Mark as unread
    function markAsUnread(notificationId, row) {
        $.ajax({
            url: '/notifications/' + notificationId + '/unread',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    // Mettre à jour l'interface
                    row.addClass('bg-light');
                    row.find('.badge').removeClass('badge-success').addClass('badge-warning')
                        .html('<i class="fas fa-envelope"></i>');
                    
                    // Changer le bouton
                    row.find('.mark-unread-btn')
                        .removeClass('btn-outline-warning mark-unread-btn')
                        .addClass('btn-outline-success mark-read-btn')
                        .html('<i class="fas fa-check"></i>')
                        .attr('title', 'Marquer comme lu');
                    
                    // Mettre à jour les compteurs côté client
                    updateLocalCounters(1); // +1 non lue, -1 lue
                    showToast('Notification marquée comme non lue', 'success');
                    
                    // Réattacher les événements
                    reattachEvents();
                }
            },
            error: function() {
                showToast('Erreur lors du marquage comme non lu', 'error');
            }
        });
    }

    // Delete single notification
    function deleteNotification(notificationId) {
        $.ajax({
            url: '/notifications/' + notificationId,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    // Supprimer la ligne du DataTable
                    const row = dataTable.row($('tr[data-notification-id="' + notificationId + '"]'));
                    const wasUnread = row.node().classList.contains('bg-light');
                    
                    row.remove().draw();
                    
                    // Mettre à jour les compteurs
                    if(wasUnread) {
                        updateLocalCounters(0, -1); // -1 non lue
                    } else {
                        updateLocalCounters(0, 0, -1); // -1 lue
                    }
                    
                    showToast('Notification supprimée avec succès', 'success');
                }
            },
            error: function() {
                showToast('Erreur lors de la suppression', 'error');
            }
        });
    }

    // Bulk actions
    $('#markAllRead').click(function(e) {
        e.preventDefault();
        if(confirm('Marquer toutes les notifications comme lues ?')) {
            markAllAsRead();
        }
    });

    $('#markSelectedRead').click(function(e) {
        e.preventDefault();
        const selectedIds = getSelectedNotificationIds();
        if(selectedIds.length > 0) {
            markMultipleAsRead(selectedIds);
        } else {
            alert('Veuillez sélectionner au moins une notification.');
        }
    });

    $('#deleteSelected').click(function(e) {
        e.preventDefault();
        const selectedIds = getSelectedNotificationIds();
        if(selectedIds.length > 0) {
            if(confirm(`Supprimer ${selectedIds.length} notification(s) sélectionnée(s) ?`)) {
                deleteMultipleNotifications(selectedIds);
            }
        } else {
            alert('Veuillez sélectionner au moins une notification.');
        }
    });

    $('#deleteAllRead').click(function(e) {
        e.preventDefault();
        if(confirm('Supprimer toutes les notifications lues ? Cette action est irréversible.')) {
            deleteAllReadNotifications();
        }
    });

    // Helper functions
    function getSelectedNotificationIds() {
        return $('.notification-checkbox:checked').map(function() {
            return $(this).val();
        }).get();
    }

    function markAllAsRead() {
        $.ajax({
            url: '/notifications/mark-all-read',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
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
            url: '/notifications/mark-multiple-read',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ids: ids
            },
            success: function(response) {
                if(response.success) {
                    // Mettre à jour chaque notification sélectionnée
                    ids.forEach(id => {
                        const row = $('tr[data-notification-id="' + id + '"]');
                        if(row.length) {
                            markAsRead(id, row);
                        }
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
            url: '/notifications/delete-multiple',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ids: ids,
                _method: 'DELETE'
            },
            success: function(response) {
                if(response.success) {
                    // Supprimer chaque ligne
                    ids.forEach(id => {
                        const row = dataTable.row($('tr[data-notification-id="' + id + '"]'));
                        if(row.any()) {
                            const wasUnread = row.node().classList.contains('bg-light');
                            row.remove();
                            
                            // Mettre à jour les compteurs
                            if(wasUnread) {
                                updateLocalCounters(0, -1);
                            } else {
                                updateLocalCounters(0, 0, -1);
                            }
                        }
                    });
                    dataTable.draw();
                    
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
            url: '/notifications/delete-all-read',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE'
            },
            success: function(response) {
                if(response.success) {
                    location.reload();
                }
            },
            error: function() {
                showToast('Erreur lors de la suppression des notifications lues', 'error');
            }
        });
    }

    // Mettre à jour les compteurs localement
    function updateLocalCounters(unreadChange = 0, totalChange = 0, readChange = 0) {
        const total = parseInt($('#totalCount').text()) + totalChange;
        const unread = parseInt($('#unreadCount').text()) + unreadChange;
        const read = parseInt($('#readCount').text()) + readChange;
        
        $('#totalCount').text(total);
        $('#unreadCount').text(unread);
        $('#readCount').text(read);
    }

    // Confirm delete
    $('#confirmDelete').click(function() {
        if(currentNotificationId) {
            deleteNotification(currentNotificationId);
            $('#deleteModal').modal('hide');
            currentNotificationId = null;
        }
    });

    // Toast notification
    function showToast(message, type = 'info') {
        const toastClass = type === 'error' ? 'danger' : type;
        const icon = type === 'success' ? 'check-circle' : 
                    type === 'error' ? 'exclamation-triangle' : 'info-circle';
        
        const toastId = 'toast-' + Date.now();
        const toast = $(`
            <div id="${toastId}" class="toast bg-${toastClass} text-white" role="alert">
                <div class="toast-body d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-${icon} mr-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="close text-white ml-3" data-dismiss="toast">
                        <span>&times;</span>
                    </button>
                </div>
            </div>
        `);
        
        // Créer le conteneur si nécessaire
        if($('#toastContainer').length === 0) {
            $('body').append('<div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>');
        }
        
        $('#toastContainer').append(toast);
        
        // Afficher et auto-supprimer
        toast.fadeIn();
        setTimeout(() => {
            toast.fadeOut(() => toast.remove());
        }, 3000);
        
        // Bouton de fermeture manuel
        toast.find('.close').on('click', function() {
            toast.fadeOut(() => toast.remove());
        });
    }
});
</script>
@endsection