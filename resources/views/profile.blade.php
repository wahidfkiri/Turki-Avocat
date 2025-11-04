@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Mon Profil</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Mon Profil</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <!-- Dans layouts/app.blade.php -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
    </div>
@endif
                </div>
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <div class="user-avatar-profile" style="background-color: {{ auth()->user()->getAvatarColor() }};">
                                    {{ auth()->user()->getInitials() }}
                                </div>
                            </div>

                            <h3 class="profile-username text-center">{{ $user->name }}</h3>

                            <p class="text-muted text-center">
                                @switch($user->fonction)
                                    @case('admin')
                                        <span class="badge badge-danger">Administrateur</span>
                                        @break
                                    @case('avocat')
                                        <span class="badge badge-primary">Avocat</span>
                                        @break
                                    @case('secrétaire')
                                        <span class="badge badge-info">Secrétaire</span>
                                        @break
                                    @case('clerc')
                                        <span class="badge badge-warning">Clerc</span>
                                        @break
                                    @case('stagiaire')
                                        <span class="badge badge-secondary">Stagiaire</span>
                                        @break
                                @endswitch
                            </p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Membre depuis</b> <a class="float-right">{{ $user->created_at->format('d/m/Y') }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Statut</b> 
                                    <a class="float-right">
                                        @if($user->is_active)
                                            <span class="badge badge-success">Actif</span>
                                        @else
                                            <span class="badge badge-danger">Inactif</span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->

                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link {{ session('tab', 'profile') == 'profile' ? 'active' : '' }}" href="#profile" data-toggle="tab">
                                        <i class="fas fa-user-edit"></i> Informations du profil
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ session('tab') == 'password' ? 'active' : '' }}" href="#password" data-toggle="tab">
                                        <i class="fas fa-lock"></i> Modifier le mot de passe
                                    </a>
                                </li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- Profile Tab -->
                                <div class="tab-pane {{ session('tab', 'profile') == 'profile' ? 'active' : '' }}" id="profile">
                                    <form action="{{ route('profile.update') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="form-group">
                                            <label for="name">Nom complet</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Adresse email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="fonction">Fonction</label>
                                            <select class="form-control @error('fonction') is-invalid @enderror" 
                                                    id="fonction" name="fonction" required>
                                                <option value="admin" {{ old('fonction', $user->fonction) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                                <option value="avocat" {{ old('fonction', $user->fonction) == 'avocat' ? 'selected' : '' }}>Avocat</option>
                                                <option value="secrétaire" {{ old('fonction', $user->fonction) == 'secrétaire' ? 'selected' : '' }}>Secrétaire</option>
                                                <option value="clerc" {{ old('fonction', $user->fonction) == 'clerc' ? 'selected' : '' }}>Clerc</option>
                                                <option value="stagiaire" {{ old('fonction', $user->fonction) == 'stagiaire' ? 'selected' : '' }}>Stagiaire</option>
                                            </select>
                                            @error('fonction')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Mettre à jour le profil
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->

                                <!-- Password Tab -->
                                <div class="tab-pane {{ session('tab') == 'password' ? 'active' : '' }}" id="password">
                                    <form action="{{ route('profile.password.update') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="form-group">
                                            <label for="current_password">Mot de passe actuel</label>
                                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                                   id="current_password" name="current_password" required>
                                            @error('current_password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="password">Nouveau mot de passe</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" name="password" required>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Le mot de passe doit contenir au moins 8 caractères.
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
                                            <input type="password" class="form-control" 
                                                   id="password_confirmation" name="password_confirmation" required>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-key"></i> Changer le mot de passe
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<style>
.user-avatar-profile {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 24px;
    margin-bottom: 15px;
    border: 4px solid #dee2e6;
}

.profile-username {
    font-size: 21px;
    margin-top: 0;
}

.list-group-item {
    border-left: 0;
    border-right: 0;
}

.list-group-item:first-child {
    border-top: 0;
}

.list-group-item:last-child {
    border-bottom: 0;
}
</style>
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Afficher/masquer le mot de passe
    $('.toggle-password').click(function() {
        var input = $(this).closest('.input-group').find('input');
        var icon = $(this).find('i');
        
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Gestion des onglets avec validation
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        // Réinitialiser les erreurs de validation quand on change d'onglet
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
    });

    // Auto-focus sur le premier champ en erreur
    @if($errors->any())
        var firstError = $('.is-invalid').first();
        if (firstError.length) {
            firstError.focus();
        }
    @endif
});
</script>
@endsection