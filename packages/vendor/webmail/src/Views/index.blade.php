{{-- resources/views/webmail/simple.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-envelope me-2"></i>Webmail Access
                        <small class="opacity-75 ms-2">{{ Auth::user()->email }}</small>
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 text-center mb-4">
                            <div class="p-4 border rounded bg-light">
                                <i class="fas fa-window-restore fa-4x text-primary mb-3"></i>
                                <h4>Embedded View</h4>
                                <p>Try loading Roundcube inside this page</p>
                                <button onclick="loadEmbedded()" class="btn btn-primary btn-lg">
                                    <i class="fas fa-play me-2"></i>Load Here
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 text-center mb-4">
                            <div class="p-4 border rounded bg-light">
                                <i class="fas fa-external-link-alt fa-4x text-success mb-3"></i>
                                <h4>Direct Access</h4>
                                <p>Open Roundcube in a new tab</p>
                                <a href="http://localhost:8082" target="_blank" class="btn btn-success btn-lg">
                                    <i class="fas fa-external-link-alt me-2"></i>Open New Tab
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div id="embeddedContainer" class="mt-4" style="display: none;">
                        <div class="border rounded overflow-hidden">
                            <div class="bg-light p-3 border-bottom d-flex justify-content-between">
                                <span><i class="fas fa-spinner fa-spin me-2"></i>Loading Roundcube...</span>
                                <button onclick="closeEmbedded()" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div style="height: 600px;">
                                {{-- Content will be loaded here --}}
                                <iframe 
                                    src="http://localhost:8082" 
                                    style="width: 100%; height: 100%; border: none;"
                                    id="simpleFrame"
                                ></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loadEmbedded() {
    document.getElementById('embeddedContainer').style.display = 'block';
    document.getElementById('simpleFrame').src = 'http://localhost:8082';
    
    // Try to auto-fill after 3 seconds
    setTimeout(() => {
        try {
            const iframe = document.getElementById('simpleFrame');
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            const usernameField = iframeDoc.querySelector('input[name="_user"]');
            if (usernameField) {
                usernameField.value = '{{ Auth::user()->email }}';
                usernameField.focus();
            }
        } catch(e) {
            // Cross-origin error, ignore
        }
    }, 3000);
}

function closeEmbedded() {
    document.getElementById('embeddedContainer').style.display = 'none';
    document.getElementById('simpleFrame').src = 'about:blank';
}
</script>
@endsection