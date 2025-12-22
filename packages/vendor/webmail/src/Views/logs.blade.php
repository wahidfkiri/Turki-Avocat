<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs Roundcube - NextStep IT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f5f5f5; }
        .log-line { font-family: 'Consolas', monospace; font-size: 12px; padding: 3px; border-bottom: 1px solid #eee; }
        .log-error { background-color: #ffe6e6; color: #cc0000; }
        .log-success { background-color: #e6ffe6; color: #006600; }
        .log-info { background-color: #e6f2ff; color: #0066cc; }
        .log-warning { background-color: #fff9e6; color: #cc8800; }
        .log-debug { background-color: #f2f2f2; color: #666666; }
        .log-file { cursor: pointer; }
        .log-file:hover { background-color: #f0f0f0; }
        .timestamp { color: #888; font-size: 11px; }
        .log-type { font-weight: bold; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-file-alt"></i> Logs Roundcube
            </a>
            <div class="navbar-nav">
                <a class="nav-link" href="{{ route('webmail.test') }}">
                    <i class="fas fa-vial"></i> Tests
                </a>
                <a class="nav-link" href="{{ route('webmail.redirect') }}">
                    <i class="fas fa-envelope"></i> Webmail
                </a>
                <button class="btn btn-sm btn-danger ms-2" onclick="clearLogs()">
                    <i class="fas fa-trash"></i> Effacer anciens logs
                </button>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <!-- Fichiers de log -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-folder-open"></i> Fichiers de log</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Emplacement :</h6>
                        <code>{{ $log_path }}</code>
                        
                        <h6 class="mt-3">Fichier actuel :</h6>
                        <div class="alert alert-info">
                            <i class="fas fa-file"></i> {{ $current_file }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Liste des fichiers :</h6>
                        <div class="list-group" style="max-height: 300px; overflow-y: auto;">
                            @foreach($log_files as $file)
                                <a href="?file={{ $file['name'] }}" 
                                   class="list-group-item list-group-item-action log-file {{ $file['name'] == $current_file ? 'active' : '' }}">
                                    <div class="d-flex justify-content-between">
                                        <span>
                                            <i class="fas fa-file-alt"></i> {{ $file['name'] }}
                                        </span>
                                        <small class="text-muted">
                                            {{ round($file['size'] / 1024, 2) }} KB
                                        </small>
                                    </div>
                                    <small class="timestamp">{{ $file['modified'] }}</small>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Logs -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-stream"></i> Logs ({{ count($logs) }} entrées)</h5>
                    <div>
                        <button class="btn btn-sm btn-light" onclick="refreshLogs()">
                            <i class="fas fa-sync-alt"></i> Actualiser
                        </button>
                        <button class="btn btn-sm btn-light" onclick="copyLogs()">
                            <i class="fas fa-copy"></i> Copier
                        </button>
                        <button class="btn btn-sm btn-light" onclick="downloadLogs()">
                            <i class="fas fa-download"></i> Télécharger
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="logContainer" style="max-height: 600px; overflow-y: auto;">
                    @forelse($logs as $log)
                        <div class="log-line log-{{ strtolower($log['type']) }}">
                            <span class="timestamp">[{{ $log['timestamp'] }}]</span>
                            <span class="log-type">[{{ $log['type'] }}]</span>
                            <span class="log-message">{{ $log['message'] }}</span>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>Aucun log disponible</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Filtres -->
        <div class="card mt-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-filter"></i> Filtres</h5>
            </div>
            <div class="card-body">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" onclick="filterLogs('all')">
                        Tous
                    </button>
                    <button type="button" class="btn btn-outline-success" onclick="filterLogs('success')">
                        Succès
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="filterLogs('error')">
                        Erreurs
                    </button>
                    <button type="button" class="btn btn-outline-info" onclick="filterLogs('info')">
                        Infos
                    </button>
                    <button type="button" class="btn btn-outline-warning" onclick="filterLogs('warning')">
                        Avertissements
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="filterLogs('debug')">
                        Debug
                    </button>
                </div>
                
                <div class="input-group mt-3">
                    <input type="text" class="form-control" id="searchLogs" 
                           placeholder="Rechercher dans les logs...">
                    <button class="btn btn-primary" onclick="searchLogs()">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function filterLogs(type) {
        const lines = document.querySelectorAll('.log-line');
        lines.forEach(line => {
            if (type === 'all') {
                line.style.display = '';
            } else {
                line.style.display = line.classList.contains('log-' + type) ? '' : 'none';
            }
        });
    }
    
    function searchLogs() {
        const searchTerm = document.getElementById('searchLogs').value.toLowerCase();
        const lines = document.querySelectorAll('.log-line');
        
        lines.forEach(line => {
            const text = line.textContent.toLowerCase();
            line.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    }
    
    function refreshLogs() {
        window.location.reload();
    }
    
    function copyLogs() {
        const logs = Array.from(document.querySelectorAll('.log-line'))
            .map(line => line.textContent)
            .join('\n');
        
        navigator.clipboard.writeText(logs).then(() => {
            alert('Logs copiés dans le presse-papier !');
        });
    }
    
    function downloadLogs() {
        const logs = Array.from(document.querySelectorAll('.log-line'))
            .map(line => line.textContent)
            .join('\n');
        
        const blob = new Blob([logs], { type: 'text/plain' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'roundcube_logs_' + new Date().toISOString().slice(0,10) + '.txt';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }
    
    function clearLogs() {
        if (confirm('Effacer les logs de plus de 7 jours ?')) {
            fetch('{{ route("webmail.clear.logs") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    refreshLogs();
                }
            });
        }
    }
    
    // Auto-refresh toutes les 30 secondes
    setInterval(refreshLogs, 30000);
    </script>
</body>
</html>