<style>
  /* Custom Notification Styles */
  .notification-panel-active {
    opacity: 1 !important;
    visibility: visible !important;
    display: block !important;
    transform: translateY(0) !important;
  }

  /* Notification container */
  .notification-wrapper {
    position: relative;
    display: inline-block;
  }

  /* Notification trigger button */
  .notification-trigger {
    position: relative;
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    color: #6c757d;
    font-size: 1.2rem;
  }

  .notification-trigger:hover {
    color: #495057;
  }

  /* Main notification panel */
  .notification-panel {
    position: absolute;
    top: 100%;
    right: 0;
    width: 380px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    transform: translateY(-10px);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    display: none;
  }

  /* Panel header */
  .notification-header {
    padding: 12px 16px;
    background-color: #f8f9fa;
    font-weight: 600;
    color: #495057;
    border-bottom: 1px solid #e9ecef;
    font-size: 14px;
    border-radius: 8px 8px 0 0;
  }

  /* Progress section */
  .notification-progress-section {
    padding: 8px 16px;
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
  }

  .notification-progress-track {
    height: 4px;
    background-color: #e9ecef;
    border-radius: 2px;
    overflow: hidden;
  }

  .notification-progress-value {
    height: 100%;
    background: linear-gradient(90deg, #007bff, #0056b3);
    border-radius: 2px;
    transition: width 0.3s ease;
  }

  .notification-progress-label {
    font-size: 12px;
    color: #6c757d;
    margin-top: 4px;
    text-align: center;
  }

  /* Notifications list container */
  .notification-list-container {
    max-height: 300px;
    overflow-y: auto;
    background: white;
  }

  /* Individual notification item */
  .notification-item {
    display: flex;
    align-items: flex-start;
    padding: 12px 16px;
    border-bottom: 1px solid #f1f3f4;
    text-decoration: none;
    color: #495057;
    transition: background-color 0.2s;
    position: relative;
  }

  .notification-item:hover {
    background-color: #f8f9fa;
  }

  /* Unread notification */
  .notification-item-unread {
    background-color: rgba(0, 123, 255, 0.05);
    border-left: 3px solid #007bff;
  }

  /* Notification icon */
  .notification-icon {
    margin-top: 2px;
    color: #6c757d;
    font-size: 14px;
    margin-right: 8px;
    flex-shrink: 0;
  }

  /* Notification content */
  .notification-content {
    flex: 1;
    margin-right: 12px;
    font-size: 14px;
    line-height: 1.4;
  }

  /* Notification time */
  .notification-time {
    font-size: 12px;
    color: #6c757d;
    white-space: nowrap;
    flex-shrink: 0;
  }

  /* Delete button */
  .notification-delete {
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    padding: 4px;
    margin-left: 8px;
    opacity: 0.6;
    transition: all 0.2s;
    flex-shrink: 0;
    border-radius: 3px;
  }

  .notification-delete:hover {
    opacity: 1;
    color: #dc3545;
    background-color: rgba(220, 53, 69, 0.1);
  }

  .notification-delete.loading {
    opacity: 0.5;
    cursor: not-allowed;
  }

  /* Divider */
  .notification-divider {
    height: 1px;
    background-color: #e9ecef;
    margin: 0;
  }

  /* Badge */
  .notification-badge {
    position: absolute;
    top: 3px;
    right: 3px;
    background-color: #dc3545;
    color: white;
    border-radius: 10px;
    padding: 2px 6px;
    font-size: 0.7rem;
    font-weight: bold;
    border: 2px solid white;
    box-shadow: 0 0 0 1px rgba(220, 53, 69, 0.2);
    min-width: 18px;
    text-align: center;
  }

  /* Empty state */
  .notification-empty {
    padding: 30px 20px;
    text-align: center;
    color: #6c757d;
    font-style: italic;
    background: white;
  }

  /* Footer */
  .notification-footer {
    padding: 10px 16px;
    text-align: center;
    background-color: #f8f9fa;
    border-top: 1px solid #e9ecef;
    border-radius: 0 0 8px 8px;
  }

  .notification-view-all {
    color: #007bff;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
  }

  .notification-view-all:hover {
    text-decoration: underline;
  }

  /* Toast notification */
  .notification-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 12px 20px;
    background: #dc3545;
    color: white;
    border-radius: 4px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 10000;
    transform: translateX(100%);
    transition: transform 0.3s ease;
  }

  .notification-toast.show {
    transform: translateX(0);
  }

  .notification-toast.success {
    background: #28a745;
  }

  /* Animation for new notifications */
  @keyframes notificationPulse {
    0% { background-color: rgba(0, 123, 255, 0.1); }
    50% { background-color: rgba(0, 123, 255, 0.2); }
    100% { background-color: rgba(0, 123, 255, 0.1); }
  }

  .notification-item-new {
    animation: notificationPulse 2s ease-in-out;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Notification elements
    const notificationTrigger = document.getElementById('notificationTrigger');
    const notificationPanel = document.getElementById('notificationPanel');
    const notificationCount = document.getElementById('notificationCount');

    // Toggle notification panel
    notificationTrigger.addEventListener('click', function (event) {
      event.preventDefault();
      event.stopPropagation();
      
      const isActive = notificationPanel.classList.contains('notification-panel-active');
      
      if (isActive) {
        notificationPanel.classList.remove('notification-panel-active');
      } else {
        notificationPanel.classList.add('notification-panel-active');
        updateNotificationProgress();
      }
    });

    // Close panel when clicking outside
    document.addEventListener('click', function (event) {
      if (!notificationTrigger.contains(event.target) && !notificationPanel.contains(event.target)) {
        notificationPanel.classList.remove('notification-panel-active');
      }
    });

    // Update progress bar
    function updateNotificationProgress() {
      const progressValue = document.querySelector('.notification-progress-value');
      const progressLabel = document.querySelector('.notification-progress-label');
      const allItems = document.querySelectorAll('.notification-item');
      const unreadItems = document.querySelectorAll('.notification-item-unread');
      
      if (progressValue && progressLabel && allItems.length > 0) {
        const percentage = (unreadItems.length / allItems.length) * 100;
        progressValue.style.width = `${percentage}%`;
        progressLabel.textContent = `${unreadItems.length} non lues sur ${allItems.length} notifications`;
      } else if (progressLabel && allItems.length === 0) {
        progressLabel.textContent = 'Aucune notification';
        if (progressValue) progressValue.style.width = '0%';
      }
    }

    // Show toast message
    function showToast(message, type = 'error') {
      const toast = document.createElement('div');
      toast.className = `notification-toast ${type}`;
      toast.textContent = message;
      document.body.appendChild(toast);
      
      setTimeout(() => toast.classList.add('show'), 100);
      
      setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
      }, 3000);
    }

    // AJAX Delete notification
    function deleteNotification(button, notificationId) {
    //  if (confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')) {
        const notificationItem = button.closest('.notification-item');
        const deleteButton = button;
        
        // Add loading state
        deleteButton.classList.add('loading');
        deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        // Add fade out effect
        notificationItem.style.opacity = '0.5';
        notificationItem.style.transform = 'translateX(20px)';
        notificationItem.style.transition = 'all 0.3s ease';
        
        // AJAX request to delete notification
        fetch(`/notifications/${notificationId}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          },
          credentials: 'same-origin'
        })
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          // Complete the fade out and remove
          setTimeout(() => {
            notificationItem.remove();
            updateNotificationProgress();
            updateNotificationBadge();
            checkEmptyState();
            showToast('Notification supprimée avec succès', 'success');
          }, 300);
        })
        .catch(error => {
          console.error('Error deleting notification:', error);
          
          // Restore the notification item
          notificationItem.style.opacity = '1';
          notificationItem.style.transform = 'translateX(0)';
          
          // Restore delete button
          deleteButton.classList.remove('loading');
          deleteButton.innerHTML = '<i class="fas fa-trash-alt"></i>';
          
          showToast('Erreur lors de la suppression de la notification');
        });
    //  }
    }

    // Setup delete handlers
    function setupDeleteHandlers() {
      document.querySelectorAll('.notification-delete').forEach(button => {
        button.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          
          const notificationId = this.getAttribute('data-notification-id');
          deleteNotification(this, notificationId);
        });
      });
    }

    // Update badge count
    function updateNotificationBadge() {
      const unreadCount = document.querySelectorAll('.notification-item-unread').length;
      if (notificationCount) {
        notificationCount.textContent = unreadCount;
        
        if (unreadCount === 0) {
          notificationCount.style.display = 'none';
        } else {
          notificationCount.style.display = 'block';
        }
      }
    }

    // Check if panel is empty
    function checkEmptyState() {
      const listContainer = document.querySelector('.notification-list-container');
      const existingItems = listContainer.querySelectorAll('.notification-item');
      const existingEmpty = listContainer.querySelector('.notification-empty');
      
      if (existingItems.length === 0 && !existingEmpty) {
        // Add empty state
        const emptyState = document.createElement('div');
        emptyState.className = 'notification-empty';
        emptyState.textContent = 'Aucune notification';
        listContainer.appendChild(emptyState);
        
        // Update progress label
        const progressLabel = document.querySelector('.notification-progress-label');
        if (progressLabel) {
          progressLabel.textContent = 'Aucune notification';
        }
      } else if (existingItems.length > 0 && existingEmpty) {
        // Remove empty state
        existingEmpty.remove();
      }
    }

    // Mark as read with AJAX
    function markAsRead(notificationId) {
      fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        },
        credentials: 'same-origin'
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        console.log('Notification marked as read:', notificationId);
      })
      .catch(error => {
        console.error('Error marking notification as read:', error);
      });
    }

    // Setup read handlers
    function setupReadHandlers() {
      document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function(e) {
          // Don't trigger if clicking delete button
          if (e.target.closest('.notification-delete')) {
            return;
          }
          
          const notificationId = this.getAttribute('data-notification-id');
          
          // Remove unread styling
          if (this.classList.contains('notification-item-unread')) {
            this.classList.remove('notification-item-unread');
            this.classList.remove('notification-item-new');
            
            // Update badge and progress
            updateNotificationBadge();
            updateNotificationProgress();
            
            // Mark as read on server
            markAsRead(notificationId);
          }
        });
      });
    }

    // Initialize all handlers
    setupDeleteHandlers();
    setupReadHandlers();
    updateNotificationBadge();
    updateNotificationProgress();
  });
</script>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" id="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">
        @if(request()->routeIs('time-sheets.*'))
          Feuilles de Temps
        @elseif(request()->routeIs('users.*'))
          Utilisateurs
        @elseif(request()->routeIs('dossiers.*'))
          Dossiers
        @elseif(request()->routeIs('intervenants.*'))
          Intervenants
        @elseif(request()->routeIs('agendas.*'))
          Agenda
        @elseif(request()->routeIs('tasks.*'))
          Tâches
        @elseif(request()->routeIs('factures.*'))
          Facturation
        @else
          Tableau de board
        @endif
      </a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link text-danger" href="{{route('logout')}}" onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> @csrf </form>
        <i class="fas fa-sign-out-alt"></i> Déconnexion
      </a>
    </li>
    <li class="nav-item">
      <div class="user-dropdown">
        <a href="{{route('profile.edit')}}" class="dropdown-trigger text-muted">
          <span class="user-avatar" style="background-color: {{ auth()->user()->getAvatarColor() }};">
            {{ auth()->user()->getInitials() }}
          </span>
        </a>
      </div>
    </li>
    
    <!-- Custom Notifications Component -->
    <li class="nav-item">
      <div class="notification-wrapper">
        <button class="nav-link notification-trigger" id="notificationTrigger">
          <i class="far fa-bell"></i>
          <span class="notification-badge" id="notificationCount">
            @if(auth()->user()->hasRole('admin'))
              {{\App\Models\Notification::where('is_read', false)->count()}}
            @else 
              {{\App\Models\Notification::where('is_read', false)->where('user_id', auth()->id())->count()}}
            @endif
          </span>
        </button>
        
        <div class="notification-panel" id="notificationPanel">
          <div class="notification-header">
            Mes Notifications
          </div>
          
          <div class="notification-progress-section">
            <div class="notification-progress-track">
              <div class="notification-progress-value"></div>
            </div>
            <div class="notification-progress-label">Chargement...</div>
          </div>
          
          <div class="notification-list-container">
            @php 
              // Get all notifications
              $notificationsQuery = \App\Models\Notification::query();
              if (!auth()->user()->hasRole('admin')) {
                $notificationsQuery->where('user_id', auth()->id());
              }
              $notifications = $notificationsQuery->where('is_read', false)->latest()->get();
            @endphp
            
            @if($notifications->count() > 0)
              @foreach($notifications as $notification)
                <div class="notification-divider"></div>
                <a href="{{ route('notification.show', ['notificationId' => $notification->id]) }}" 
                   class="notification-item @if(!$notification->is_read) notification-item-unread notification-item-new @endif"
                   data-notification-id="{{ $notification->id }}">
                  <i class="fas fa-envelope notification-icon"></i>
                  <div class="notification-content">
                    {{ $notification->message }}
                  </div>
                  <div class="notification-time">
                    {{ $notification->created_at->diffForHumans() }}
                  </div>
                  <button class="notification-delete" data-notification-id="{{ $notification->id }}">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </a>
              @endforeach
            @else
              <div class="notification-empty">
                Aucune notification
              </div>
            @endif
          </div>
          @if($notifications->count() > 0)
          <div class="notification-footer">
            <a href="{{ route('notifications.index') }}" class="notification-view-all">
              Voir toutes les notifications
            </a>
          </div>
          @endif
        </div>
      </div>
    </li>
    
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
  </ul>
</nav>