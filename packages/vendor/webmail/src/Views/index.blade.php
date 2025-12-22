<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Roundcube - NextStep IT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; padding: 20px; }
        .container { max-width: 1200px; }
        .card { margin-bottom: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .test-result { padding: 10px; border-radius: 5px; margin: 5px 0; }
        .success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }
        .warning { background: #fff3cd; color: #856404; border-left: 4px solid #ffc107; }
        .info { background: #d1ecf1; color: #0c5460; border-left: 4px solid #17a2b8; }
        .log-entry { font-family: monospace; font-size: 12px; padding: 5px; margin: 2px 0; }
        .log-error { color: #dc3545; }
        .log-success { color: #28a745; }
        .log-info { color: #17a2b8; }
        .log-debug { color: #6c757d; }
        .btn-test { margin: 5px; }
        .accordion-button:not(.collapsed) { background-color: #e7f1ff; }
        .table-sm th, .table-sm td { padding: 0.5rem; }
        .badge-test { font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-primary">
                <i class="fas fa-mail-bulk"></i> Test Roundcube
            </h1>
            <div>
                <a href="{{ route('webmail.redirect') }}" class="btn btn-success">
                    <i class="fas fa-envelope"></i> Accéder au Webmail
                </a>
                <a href="{{ route('webmail.logs') }}" class="btn btn-info">
                    <i class="fas fa-clipboard-list"></i> Voir les logs
                </a>
            </div>
        </div>
        
        <!-- Alertes -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <!-- Tests automatiques -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-vial"></i> Tests automatiques</h4>
            </div>
            <div class="card-body">
                @foreach($tests as $testName => $testResult)
                    <div class="test-result {{ $testResult['success'] ? 'success' : 'error' }}">
                        <strong>{{ ucfirst(str_replace('_', ' ', $testName)) }}:</strong>
                        @if($testResult['success'])
                            <span class="badge bg-success">SUCCÈS</span>
                        @else
                            <span class="badge bg-danger">ÉCHEC</span>
                        @endif
                        
                        @if(isset($testResult['error']))
                            <div class="mt-2">
                                <strong>Erreur:</strong> {{ $testResult['error'] }}
                            </div>
                        @endif
                        
                        @if(isset($testResult['details']))
                            <div class="mt-2">
                                <button class="btn btn-sm btn-outline-primary" type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#details-{{ $testName }}">
                                    Détails
                                </button>
                                <div class="collapse mt-2" id="details-{{ $testName }}">
                                    <pre class="bg-light p-3">{{ json_encode($testResult['details'], JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Tests manuels -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="fas fa-hand-paper"></i> Tests manuels</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Test IMAP</h5>
                            </div>
                            <div class="card-body">
                                <form id="testImapForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" 
                                               value="{{ Auth::user()->email ?? '' }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Mot de passe</label>
                                        <input type="password" class="form-control" name="password" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-plug"></i> Tester IMAP
                                    </button>
                                </form>
                                <div id="imapResult" class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Générer URL de test</h5>
                            </div>
                            <div class="card-body">
                                <form id="generateUrlForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" 
                                               value="{{ Auth::user()->email ?? '' }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Mot de passe email</label>
                                        <input type="password" class="form-control" name="password" required>
                                    </div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-link"></i> Générer URL
                                    </button>
                                </form>
                                <div id="urlResult" class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Logs récents -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0"><i class="fas fa-clipboard-list"></i> Logs récents</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Heure</th>
                                <th>Type</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr class="log-entry log-{{ strtolower($log['type']) }}">
                                    <td>{{ $log['timestamp'] }}</td>
                                    <td>
                                        <span class="badge bg-{{ $log['type'] == 'ERROR' ? 'danger' : ($log['type'] == 'SUCCESS' ? 'success' : 'info') }}">
                                            {{ $log['type'] }}
                                        </span>
                                    </td>
                                    <td>{{ $log['message'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Aucun log disponible</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Informations système -->
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h4 class="mb-0"><i class="fas fa-info-circle"></i> Informations système</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th>PHP Version</th>
                                <td>{{ phpversion() }}</td>
                            </tr>
                            <tr>
                                <th>Laravel Version</th>
                                <td>{{ app()->version() }}</td>
                            </tr>
                            <tr>
                                <th>Extensions IMAP</th>
                                <td>{{ extension_loaded('imap') ? '✅ Activée' : '❌ Désactivée' }}</td>
                            </tr>
                            <tr>
                                <th>Session Path</th>
                                <td>{{ session_save_path() }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th>Serveur HTTP</th>
                                <td>{{ $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>IP Client</th>
                                <td>{{ request()->ip() }}</td>
                            </tr>
                            <tr>
                                <th>User Agent</th>
                                <td>{{ request()->userAgent() }}</td>
                            </tr>
                            <tr>
                                <th>URL Roundcube</th>
                                <td>{{ config('services.roundcube.url') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Test IMAP
        $('#testImapForm').submit(function(e) {
            e.preventDefault();
            $('#imapResult').html('<div class="alert alert-info">Test en cours...</div>');
            
            $.ajax({
                url: '{{ route("webmail.test.imap") }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    let html = '<div class="alert ' + (response.success ? 'alert-success' : 'alert-danger') + '">';
                    html += '<h5>' + (response.success ? '✅ Succès' : '❌ Échec') + '</h5>';
                    
                    if (response.success) {
                        html += '<p>Connexion IMAP réussie en ' + response.response_time_ms + 'ms</p>';
                        if (response.mailbox_info) {
                            html += '<p>Messages: ' + response.mailbox_info.messages + '</p>';
                        }
                    } else {
                        html += '<p>Erreur: ' + response.error + '</p>';
                        if (response.action) {
                            html += '<p>Action: ' + response.action + '</p>';
                        }
                    }
                    
                    html += '</div>';
                    $('#imapResult').html(html);
                },
                error: function(xhr) {
                    $('#imapResult').html(
                        '<div class="alert alert-danger">Erreur AJAX: ' + xhr.status + ' ' + xhr.statusText + '</div>'
                    );
                }
            });
        });
        
        // Générer URL
        $('#generateUrlForm').submit(function(e) {
            e.preventDefault();
            $('#urlResult').html('<div class="alert alert-info">Génération en cours...</div>');
            
            $.ajax({
                url: '{{ route("webmail.generate.url") }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        let html = '<div class="alert alert-success">';
                        html += '<h5>✅ URL générée</h5>';
                        html += '<p><strong>Session ID:</strong> ' + response.session_id + '</p>';
                        html += '<p><strong>URL:</strong> <a href="' + response.login_url + '" target="_blank">' + 
                                response.login_url.substring(0, 80) + '...</a></p>';
                        html += '<button class="btn btn-sm btn-primary mt-2" onclick="navigator.clipboard.writeText(\'' + 
                                response.login_url + '\')">📋 Copier l\'URL</button>';
                        html += '</div>';
                        $('#urlResult').html(html);
                    } else {
                        $('#urlResult').html(
                            '<div class="alert alert-danger">Erreur: ' + response.error + '</div>'
                        );
                    }
                },
                error: function(xhr) {
                    $('#urlResult').html(
                        '<div class="alert alert-danger">Erreur AJAX: ' + xhr.status + ' ' + xhr.statusText + '</div>'
                    );
                }
            });
        });
    });
    </script>
</body>
</html>