@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gestion des emails</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Emails</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Iframe for Snappymail -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Webmail Snappymail</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                    <i class="fas fa-expand"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            <!-- Fullscreen iframe -->
                            <iframe 
                                src="http://localhost?admin" 
                                style="width: 100%; height: 700px; border: none;"
                                id="snappymail-frame"
                                title="Snappymail Webmail"
                                allow="fullscreen"
                            ></iframe>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Snappymail webmail client. 
                                <a href="http://localhost/" target="_blank" class="btn btn-xs btn-default float-right">
                                    <i class="fas fa-external-link-alt"></i> Ouvrir dans un nouvel onglet
                                </a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// JavaScript for better iframe handling
document.addEventListener('DOMContentLoaded', function() {
    const iframe = document.getElementById('snappymail-frame');
    
    // Adjust iframe height based on window size
    function adjustIframeHeight() {
        const windowHeight = window.innerHeight;
        const headerHeight = document.querySelector('.content-header').offsetHeight;
        const cardHeaderHeight = iframe.closest('.card').querySelector('.card-header').offsetHeight;
        const cardFooterHeight = iframe.closest('.card').querySelector('.card-footer').offsetHeight;
        const padding = 50;
        
        const newHeight = windowHeight - headerHeight - cardHeaderHeight - cardFooterHeight - padding;
        iframe.style.height = Math.max(500, newHeight) + 'px';
    }
    
    // Adjust on load and resize
    adjustIframeHeight();
    window.addEventListener('resize', adjustIframeHeight);
    
    // Handle iframe messages (if Snappymail supports postMessage)
    window.addEventListener('message', function(event) {
        // Handle messages from iframe if needed
        console.log('Message from iframe:', event.data);
    });
    
    // Refresh iframe button (optional)
    const refreshBtn = document.createElement('button');
    refreshBtn.className = 'btn btn-tool';
    refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i>';
    refreshBtn.title = 'Rafraîchir';
    refreshBtn.addEventListener('click', function() {
        iframe.src = iframe.src;
    });
    
    // Add refresh button to card tools
    const cardTools = iframe.closest('.card').querySelector('.card-tools');
    if (cardTools) {
        cardTools.appendChild(refreshBtn);
    }
});
</script>
<style>
/* Custom styles for the iframe */
#snappymail-frame {
    min-height: 500px;
    background: #f8f9fa;
}

/* Fullscreen mode */
.fullscreen #snappymail-frame {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    z-index: 1050;
}

.card.maximized #snappymail-frame {
    height: calc(100vh - 150px) !important;
}
</style>
@endsection