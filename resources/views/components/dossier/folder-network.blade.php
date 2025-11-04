@php
    $folderPath = storage_path('app/public/dossiers/' . $dossier->numero_dossier . '-' . $dossier->id);
    $normalizedPath = str_replace('/', '\\', $folderPath);
@endphp

<form id="openFolderForm">
    @csrf
    <input type="hidden" 
           id="path" 
           name="path" 
           value="{{ $normalizedPath }}">
    <button type="submit" class="btn btn-secondary" id="openBtn">
        📁 Obtenir le chemin réseau
    </button>
</form>


<script>
document.getElementById('openFolderForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const openBtn = document.getElementById('openBtn');
    const originalText = openBtn.innerHTML;
    const path = document.getElementById('path').value;
    
    openBtn.innerHTML = '⏳ Génération...';
    openBtn.disabled = true;
    
    const formData = new FormData();
    formData.append('path', path);
    formData.append('_token', '{{ csrf_token() }}');
    
    fetch('{{ route("folder.open.network") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const resultDiv = document.getElementById('result');
        
        if (data.success) {
            resultDiv.innerHTML = `
                <div class="alert alert-success">
                    <div class="mt-2">
                        <label class="form-label"><strong>Chemin réseau :</strong></label>
                        <div class="input-group">
                            <input type="text" 
                                   id="networkPath" 
                                   value="${data.network_path}" 
                                   class="form-control" 
                                   readonly
                                   style="font-family: monospace;">
                            <button class="btn btn-primary" onclick="copyNetworkPath()">
                                📋 Copier
                            </button>
                        </div>
                    </div>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="alert alert-danger">
                    ❌ ${data.message}
                </div>
            `;
        }
    })
    .catch(error => {
        document.getElementById('result').innerHTML = `
            <div class="alert alert-danger">
                ❌ Erreur réseau: ${error.message}
            </div>
        `;
    })
    .finally(() => {
        openBtn.innerHTML = originalText;
        openBtn.disabled = false;
    });
});

function copyNetworkPath() {
    const input = document.getElementById('networkPath');
    input.select();
    navigator.clipboard.writeText(input.value).then(() => {
        // Feedback visuel
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '✅ Copié!';
        setTimeout(() => {
            btn.innerHTML = originalText;
        }, 2000);
    });
}
</script>