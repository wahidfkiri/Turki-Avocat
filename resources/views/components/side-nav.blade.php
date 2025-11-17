<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/home') }}" class="brand-link">
        <img src="{{ asset('logo1.png') }}" alt="{{config('app.name')}}" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
               <span class="user-avatar" style="background-color: {{ auth()->user()->getAvatarColor() }};">
    {{ auth()->user()->getInitials() }}
</span>
            </div>
            <div class="info">
                <a href="{{route('profile.edit')}}" class="d-block">{{ auth()->user()->name ?? 'Utilisateur' }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome -->
                  @if(auth()->user()->hasPermission('access_admin_panel'))
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Tableau de board</p>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPermission('view_intervenants'))
                <li class="nav-item">
                    <a href="{{ route('intervenants.index') }}" class="nav-link {{ request()->routeIs('intervenants.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Intervenants</p>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPermission('view_dossiers'))
                <li class="nav-item">
                    <a href="{{ route('dossiers.index') }}" class="nav-link {{ request()->routeIs('dossiers.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-folder"></i>
                        <p>Dossiers</p>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPermission('view_agendas'))
                <li class="nav-item">
                    <a href="{{ route('agendas.index') }}" class="nav-link {{ request()->routeIs('agendas.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Agenda</p>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPermission('view_tasks'))
                <li class="nav-item">
                    <a href="{{ route('tasks.index') }}" class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Tâches</p>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPermission('view_factures'))
                <li class="nav-item">
                    <a href="{{ route('factures.index') }}" class="nav-link {{ request()->routeIs('factures.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>Facturation</p>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPermission('view_timesheets'))
                <li class="nav-item">
                    <a href="{{ route('time-sheets.index') }}" class="nav-link {{ request()->routeIs('time-sheets.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clock"></i>
                        <p>Timesheet</p>
                    </a>
                </li>
                @endif
                <li class="nav-item d-none">
                    <a href="{{ route('email.index') }}" class="nav-link {{ request()->routeIs('email.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>Emails</p>
                    </a>
                </li>
                @if(auth()->user()->hasPermission('view_users'))
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>Utilisateurs</p>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPermission('export_data'))
<li class="nav-item d-none">
    <a href="{{ route('backups.index') }}" class="nav-link {{ request()->routeIs('backups.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-database"></i>
        <p>Sauvegardes</p>
    </a>
</li>
@endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>