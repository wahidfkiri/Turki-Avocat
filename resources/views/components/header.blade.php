<style>
  .show-actif {
        opacity: 1 !important;
    visibility: visible;
    display: block !important;
  }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var notificationsDropdown = document.getElementById('notificationsDropdown');
        var dropdownMenu = notificationsDropdown.nextElementSibling;

        notificationsDropdown.addEventListener('click', function (event) {
            event.preventDefault();
            dropdownMenu.classList.toggle('show-actif');
        });

        document.addEventListener('click', function (event) {
            if (!notificationsDropdown.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.remove('show-actif');
            }
        });
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
          <a class="nav-link text-danger" href="{{route('logout')}}"onclick="event.preventDefault();
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
      
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" id="notificationsDropdown" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">
          <i class="far fa-bell"></i>
          <span class="badge badge-danger navbar-badge" style="padding: 0.1em 0.3em !important;">
            @if(auth()->user()->hasRole('admin'))
            {{\App\Models\Notification::where('is_read', false)->count()}}
            @else 
            {{\App\Models\Notification::where('is_read', false)->where('user_id', auth()->id())->count()}}
            @endif
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">
            @if(auth()->user()->hasRole('admin'))
            {{\App\Models\Notification::where('is_read', 0)->count()}} Notifications Non Lues
            @else
            {{\App\Models\Notification::where('is_read', false)->where('user_id', auth()->id())->count()}} Notifications Non Lues
            @endif
            @php 
            $notificationsQuery = \App\Models\Notification::where('is_read', false);
            if (!auth()->user()->hasRole('admin')) {
                $notificationsQuery->where('user_id', auth()->id());
            }
            $notifications = $notificationsQuery->latest()->take(5)->get();
            @endphp
          </span>
          @foreach($notifications as $notification)
          <div class="dropdown-divider"></div>
          <a href="{{ route('tasks.show', ['task' => $notification->task_id]) }}" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> {{ $notification->message }}
            <span class="float-right text-muted text-sm">  {{$notification->created_at->diffForHumans()}}</span>
          </a>
          @endforeach
          <!-- <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">Marquer tous comme lus</a> -->
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>