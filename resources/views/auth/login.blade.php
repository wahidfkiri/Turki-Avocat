<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Connexion | {{config('app.name')}}</title>

  
    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('logo1.png') }}">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css?v=3.2.0">
  <style>
    .login-page {
      background-color: #f8f9fa;
    }
    .login-logo {
      margin-bottom: 1.5rem;
    }
    .login-logo a {
      color: #6c757d;
      font-weight: 600;
      font-size: 1.8rem;
    }
    .login-card-body {
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .btn-primary {
      background-color: #3490dc;
      border-color: #3490dc;
    }
    .btn-primary:hover {
      background-color: #2779bd;
      border-color: #2779bd;
    }
    .login-box-msg {
      color: #6c757d;
      font-size: 1.1rem;
      margin-bottom: 1.5rem;
    }
  </style>
</head>
<body class="hold-transition login-page" style="background-image:url({{asset('assets/img-auth-bg-5.jpg')}});background-size:cover;">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
  <div class="login-logo">
    <a href="/"><img src="{{asset('logo1.png')}}" style="width:100px;"></a>
  </div>
      <p class="login-box-msg">Connectez-vous pour démarrer votre session</p>
      @if(Session::has('error'))
      <div class="alert alert-danger" role="alert">
        {{ Session::get('error') }}
      </div>
      @endif
      <form action="{{ route('login') }}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Adresse e-mail" required autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          @error('email')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember">
                Se souvenir de moi
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Connexion</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.min.js?v=3.2.0"></script>
</body>
</html>