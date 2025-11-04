<form id="openFolderForm" method="POST" action="{{ route('folder.open') }}">
    @csrf
    <input type="hidden" 
           id="dossierId" 
           name="dossierId" 
           value="{{$dossier->id}}">
    <button type="submit" class="btn btn-secondary" id="openBtn">
        <i class="fa fa-folder"></i> Ouvrir dans l'explorateur
    </button>
</form>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
document.getElementById('openFolderForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const openBtn = document.getElementById('openBtn');
    const originalText = openBtn.innerHTML;
    const pathInput = document.getElementById('dossierId');
    const dossierId = pathInput.value;
    
    // Show loading state
    openBtn.innerHTML = '⏳ Opening...';
    openBtn.disabled = true;
    
    // Create form data for proper CSRF handling
    const formData = new FormData();
    formData.append('dossierId', dossierId);
    formData.append('_token', '{{ csrf_token() }}');
    
    fetch('{{ route("folder.open") }}', {
        method: 'POST',
        body: formData, // Use FormData instead of JSON
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            // If not JSON, get the text and throw error
            return response.text().then(text => {
                throw new Error('Server returned HTML instead of JSON. Possible validation error.');
            });
        }
    })
    .then(data => {
        const resultDiv = document.getElementById('result');
        if (data.success) {
            console.log(data);
            resultDiv.innerHTML = `
                <div class="alert alert-success alert-dismissible fade show">
                    ✅ ${data.message}<br>
                    <small>Path: ${data.network_path}</small>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show">
                    ❌ ${data.message}<br>
                    <small>Path: ${data.path}</small>
                    ${data.output ? `<br><small>Details: ${data.output}</small>` : ''}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('result').innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show">
                ❌ Error: ${error.message}<br>
                <small>Check console for details</small>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
    })
    .finally(() => {
        // Reset button
        openBtn.innerHTML = originalText;
        openBtn.disabled = false;
    });
});
</script>