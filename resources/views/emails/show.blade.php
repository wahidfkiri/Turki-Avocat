{{-- resources/views/email/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Lecture d'Email</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
              <li class="breadcrumb-item"><a href="{{ route('email.index') }}">Emails</a></li>
              <li class="breadcrumb-item"><a href="{{ route('email.folder', $currentFolder) }}">{{ $currentFolder }}</a></li>
              <li class="breadcrumb-item active">Lecture</li>
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
            
            <!-- Alert Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Succès!</h5>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Erreur!</h5>
                {{ session('error') }}
            </div>
            @endif

            <div class="row">
              <!-- Sidebar - Dossiers -->
              <div class="col-md-3">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="fas fa-folder mr-1"></i>
                      Dossiers Email
                    </h3>
                  </div>
                  <div class="card-body p-0">
                    <ul class="nav nav-pills flex-column">
                      @foreach($folders as $folder)
                        <li class="nav-item">
                          <a href="{{ route('email.folder', $folder['name']) }}" 
                             class="nav-link {{ $currentFolder === $folder['name'] ? 'active' : '' }}">
                            <i class="fas fa-folder-open mr-2"></i>
                            {{ $folder['name'] }}
                          </a>
                        </li>
                      @endforeach
                    </ul>
                  </div>
                </div>

                <!-- Card Actions Rapides -->
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="fas fa-bolt mr-1"></i>
                      Actions
                    </h3>
                  </div>
                  <div class="card-body">
                    <div class="d-grid gap-2">
                      <a href="{{ route('email.folder', $currentFolder) }}" class="btn btn-default btn-block">
                        <i class="fas fa-arrow-left mr-1"></i> Retour
                      </a>
                      <button type="button" class="btn btn-info btn-block">
                        <i class="fas fa-reply mr-1"></i> Répondre
                      </button>
                      <button type="button" class="btn btn-info btn-block">
                        <i class="fas fa-share mr-1"></i> Transférer
                      </button>
                      <button type="button" class="btn btn-danger btn-block" onclick="deleteEmail('{{ $currentFolder }}', '{{ $email['uid'] }}')">
    <i class="fas fa-trash mr-1"></i> Supprimer
</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Main Content - Email Detail -->
              <div class="col-md-9">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="fas fa-envelope-open mr-1"></i>
                      {{ $email['subject'] ?? 'Sans objet' }}
                    </h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Imprimer">
                        <i class="fas fa-print"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Télécharger">
                        <i class="fas fa-download"></i>
                      </button>
                    </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <!-- Email Header -->
                    <div class="row mb-4">
                      <div class="col-md-12">
                        <div class="callout callout-info">
                          <div class="row">
                            <div class="col-md-6">
                              <p class="mb-1"><strong>De:</strong> {{ $email['from_name'] ? $email['from_name'] . ' <' . $email['from'] . '>' : $email['from'] }}</p>
                              <p class="mb-1"><strong>À:</strong> Moi</p>
                              @if(isset($email['cc']) && count($email['cc']) > 0)
                                <p class="mb-1"><strong>Cc:</strong> 
                                  @foreach($email['cc'] as $cc)
                                    {{ $cc['name'] ? $cc['name'] . ' <' . $cc['email'] . '>' : $cc['email'] }}{{ !$loop->last ? ', ' : '' }}
                                  @endforeach
                                </p>
                              @endif
                            </div>
                            <div class="col-md-6 text-md-right">
                              <p class="mb-1"><strong>Date:</strong> 
                                @if(isset($email['date']))
                                  {{ \Carbon\Carbon::parse($email['date'])->format('d/m/Y à H:i') }}
                                @endif
                              </p>
                              <p class="mb-0">
                                <span class="badge badge-{{ $email['seen'] ? 'success' : 'warning' }}">
                                  {{ $email['seen'] ? 'Lu' : 'Non lu' }}
                                </span>
                                @if(isset($email['attachments_count']) && $email['attachments_count'] > 0)
                                  <span class="badge badge-info">
                                    <i class="fas fa-paperclip mr-1"></i>{{ $email['attachments_count'] }} pièce(s) jointe(s)
                                  </span>
                                @endif
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Email Content -->
                    <div class="row">
  <div class="col-md-12">
    <div class="email-content">
      @if(isset($email['body']['html']) && !empty(trim($email['body']['html'])))
        <div class="border rounded p-4 bg-white">
          <div class="email-html-content">
            {!! $email['body']['html'] !!}
          </div>
        </div>
      @elseif(isset($email['body']['text']) && !empty(trim($email['body']['text'])))
        <div class="border rounded p-4 bg-white">
          <div class="email-text-content">
            <pre style="
              white-space: pre-wrap; 
              font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
              background: transparent; 
              border: none; 
              padding: 0; 
              margin: 0;
              font-size: 14px;
              line-height: 1.6;
              color: #333;
            ">{{ $email['body']['text'] }}</pre>
          </div>
        </div>
      @else
        <div class="text-center py-5">
          <i class="fas fa-exclamation-circle fa-3x text-muted mb-3"></i>
          <h4 class="text-muted">Aucun contenu disponible</h4>
          <p class="text-muted">Cet email ne contient aucun texte.</p>
        </div>
      @endif
    </div>
  </div>
</div>

                    <!-- Pièces jointes -->
                    @if(isset($email['attachments']) && count($email['attachments']) > 0)
                      <div class="row mt-4">
                        <div class="col-md-12">
                          <div class="card">
                            <div class="card-header">
                              <h5 class="card-title mb-0">
                                <i class="fas fa-paperclip mr-1"></i>
                                Pièces jointes ({{ count($email['attachments']) }})
                              </h5>
                            </div>
                            <div class="card-body">
                              <div class="row">
                                @foreach($email['attachments'] as $attachment)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="attachment-item border rounded p-3">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            @php
                                $icon = 'fa-file';
                                $extension = strtolower(pathinfo($attachment['name'], PATHINFO_EXTENSION));
                                $iconMap = [
                                    'pdf' => 'fa-file-pdf',
                                    'doc' => 'fa-file-word',
                                    'docx' => 'fa-file-word',
                                    'xls' => 'fa-file-excel',
                                    'xlsx' => 'fa-file-excel',
                                    'ppt' => 'fa-file-powerpoint',
                                    'pptx' => 'fa-file-powerpoint',
                                    'zip' => 'fa-file-archive',
                                    'rar' => 'fa-file-archive',
                                    'jpg' => 'fa-file-image',
                                    'jpeg' => 'fa-file-image',
                                    'png' => 'fa-file-image',
                                    'gif' => 'fa-file-image',
                                ];
                                $icon = $iconMap[$extension] ?? 'fa-file';
                            @endphp
                            <i class="fas {{ $icon }} fa-2x text-muted"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="font-weight-bold text-truncate" title="{{ $attachment['name'] }}">
                                {{ $attachment['name'] }}
                            </div>
                           <div class="text-muted small">
                            {{ $formatFileSize($attachment['size'] ?? 0) }}
                          </div>
                        </div>
                        <div class="ml-2">
                            <button class="btn btn-sm btn-outline-primary download-attachment"
                                    data-attachment="{{ json_encode([
                                        'id' => $attachment['id'],
                                        'name' => $attachment['name'],
                                        'folder' => $currentFolder,
                                        'uid' => $email['uid']
                                    ]) }}"
                                    title="Télécharger {{ $attachment['name'] }}">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endif
                  </div>
                  <!-- /.card-body -->
                  
                  <div class="card-footer">
                    <div class="row">
                      <div class="col-md-6">
                        <a href="{{ route('email.folder', $currentFolder) }}" class="btn btn-default">
                          <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
                        </a>
                      </div>
                      <div class="col-md-6 text-right">
                        <div class="btn-group">
                          <button type="button" class="btn btn-info">
                            <i class="fas fa-reply mr-1"></i> Répondre
                          </button>
                          <button type="button" class="btn btn-info">
                            <i class="fas fa-share mr-1"></i> Transférer
                          </button>
                          <button type="button" class="btn btn-danger btn-block" onclick="deleteEmail('{{ $currentFolder }}', '{{ $email['uid'] }}')">
    <i class="fas fa-trash mr-1"></i> Supprimer
</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card -->
              </div>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    
    <!-- /.content -->
  </div>
<style>
  .email-content {
    font-size: 14px;
    line-height: 1.6;
  }
  .email-content img {
    max-width: 100%;
    height: auto;
  }
  .attachment-item {
    transition: all 0.3s ease;
  }
  .attachment-item:hover {
    background-color: #f8f9fa;
    border-color: #007bff;
  }
  .callout {
    border-left: 5px solid #17a2b8;
  }
</style>
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

<script>
$(function () {
    // Enable tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Print functionality
    $('.fa-print').closest('button').click(function() {
        window.print();
    });

    // Download attachment - VERSION AVEC DEBUG
    $('.download-attachment').click(function(e) {
        e.preventDefault();
        
        console.log('Download button clicked');
        
        const button = $(this);
        const attachmentData = button.data('attachment');
        
        console.log('Attachment data:', attachmentData);
        
        if (!attachmentData) {
            console.error('No attachment data found');
            alert('Données de pièce jointe manquantes');
            return;
        }

        // Vérifier que toutes les données nécessaires sont présentes
        if (!attachmentData.folder || !attachmentData.uid || !attachmentData.id || !attachmentData.name) {
            console.error('Missing required attachment data:', attachmentData);
            alert('Données de pièce jointe incomplètes');
            return;
        }

        console.log('Starting download for:', attachmentData.name);
        
        // Méthode de téléchargement directe
        downloadAttachmentDirect(attachmentData);
    });

    function downloadAttachmentDirect(attachmentData) {
        // Construire l'URL
        const params = new URLSearchParams({
            folder: attachmentData.folder,
            uid: attachmentData.uid,
            attachment_id: attachmentData.id,
            filename: attachmentData.name
        });
        
        const url = '{{ route("email.download.attachment") }}?' + params.toString();
        console.log('Download URL:', url);
        
        // Méthode 1: Ouvrir dans un nouvel onglet
        window.open(url, '_blank');
        
        // Méthode 2: Créer un lien et le cliquer
        const link = document.createElement('a');
        link.href = url;
        link.target = '_blank';
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        console.log('Download initiated');
    }
});
</script>
<script>
function deleteEmail(folder, uid, permanent = false) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet email ?')) {
        return;
    }

    // Afficher un indicateur de chargement
    const button = event.target;
    const originalHtml = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression...';
    button.disabled = true;

    fetch('{{ route("email.delete") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            folder: folder,
            uid: uid,
            permanent: permanent
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Afficher notification de succès
            showNotification(data.message, 'success');
            
            // Supprimer la ligne du tableau ou recharger
            setTimeout(() => {
                // Option 1: Recharger la page
                location.reload();
                
                // Option 2: Supprimer la ligne dynamiquement
                // const row = button.closest('tr');
                // row.style.opacity = '0';
                // setTimeout(() => row.remove(), 500);
            }, 1000);
        } else {
            showNotification(data.error, 'error');
            button.innerHTML = originalHtml;
            button.disabled = false;
        }
    })
    .catch(error => {
        showNotification('Erreur réseau: ' + error, 'error');
        button.innerHTML = originalHtml;
        button.disabled = false;
    });
}

// Fonction pour afficher les notifications (si vous n'en avez pas)
function showNotification(message, type = 'info') {
    // Utiliser Toastr si disponible
    if (typeof toastr !== 'undefined') {
        toastr[type](message);
    } 
    // Sinon utiliser alert simple
    else if (type === 'error') {
        alert('Erreur: ' + message);
    } else {
        alert(message);
    }
}

// Suppression multiple avec cases à cocher
function deleteSelectedEmails() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
    const uids = Array.from(checkboxes).map(cb => cb.value).filter(uid => uid !== 'on');
    
    if (uids.length === 0) {
        alert('Veuillez sélectionner au moins un email à supprimer.');
        return;
    }

    if (!confirm(`Êtes-vous sûr de vouloir supprimer ${uids.length} email(s) ?`)) {
        return;
    }

    fetch('{{ url("email.delete.multiple") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            folder: '{{ $currentFolder }}',
            uids: uids,
            permanent: false
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.error, 'error');
        }
    });
}
</script>
@endsection