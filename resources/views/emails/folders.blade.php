{{-- resources/views/email/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Gestion des Emails</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            </ol>
          </div>
        </div>
      </div>
    </section>

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

            @if(isset($error))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Erreur!</h5>
                {{ $error }}
            </div>
            @endif

            @if(isset($warning))
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-exclamation-triangle"></i> Attention!</h5>
                {{ $warning }}
            </div>
            @endif

            <div class="row">
              <!-- Sidebar - Dossiers -->
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="fas fa-folder mr-1"></i>
                      Tous les Dossiers
                      <span class="badge badge-info ml-1">{{ $totalFolders ?? 0 }}</span>
                    </h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body p-0" style="max-height: 500px; overflow-y: auto;">
                    <ul class="nav nav-pills flex-column">
                       
                      @forelse($folders as $folder)
                        <li class="nav-item">
                          <a href="{{ route('email.folder', $folder['name']) }}" 
                             class="nav-link 
                                    {{ $folder['is_common'] ?? false ? 'font-weight-bold' : '' }}"
                             title="{{ $folder['full_name'] ?? $folder['name'] }}">
                            <i class="fas 
                                @if($folder['name'] == 'INBOX') fa-inbox
                                @elseif($folder['name'] == 'Sent' || $folder['name'] == 'Sent Items') fa-paper-plane
                                @elseif($folder['name'] == 'Drafts') fa-edit
                                @elseif($folder['name'] == 'Trash' || $folder['name'] == 'Deleted Items' || $folder['name'] == 'Bin') fa-trash
                                @elseif($folder['name'] == 'Spam' || $folder['name'] == 'Junk') fa-exclamation-triangle
                                @elseif($folder['name'] == 'Archive' || $folder['name'] == 'Archives') fa-archive
                                @elseif($folder['has_children'] ?? false) fa-folder-open
                                @else fa-folder
                                @endif
                                mr-2">
                            </i>
                            {{ $folder['name'] }}
                            @if($folder['has_children'] ?? false)
                              <small class="text-muted ml-1">
                                <i class="fas fa-folder-plus"></i>
                              </small>
                            @endif
                          </a>
                        </li>
                      @empty
                        <li class="nav-item">
                          <span class="nav-link text-muted">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            Aucun dossier trouvé
                          </span>
                        </li>
                      @endforelse
                    </ul>
                  </div>
                  <div class="card-footer">
                    <small class="text-muted">
                      <i class="fas fa-info-circle mr-1"></i>
                      {{ $totalFolders ?? 0 }} dossier(s) disponible(s)
                    </small>
                  </div>
                </div>


                <!-- Card Info -->
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="fas fa-info-circle mr-1"></i>
                      Informations
                    </h3>
                  </div>
                  <div class="card-body">
                    <p class="mb-1"><strong>Compte:</strong></p>
                    <p class="text-sm text-muted">{{ $account ?? 'wahid.fkiri@peakmind-solutions.com' }}</p>
                    
                    <p class="mb-1"><strong>Total dossiers:</strong></p>
                    <p class="text-sm text-muted">{{ $totalFolders ?? 0 }}</p>
                    
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Compose Modal -->
  <div class="modal fade" id="composeModal" tabindex="-1" role="dialog" aria-labelledby="composeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="composeModalLabel">Nouveau Message</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="{{ route('email.send') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <input type="email" class="form-control" name="to" placeholder="À:" required>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="subject" placeholder="Sujet:" required>
            </div>
            <div class="form-group">
              <textarea class="form-control" name="content" style="height: 300px" required placeholder="Votre message..."></textarea>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-paper-plane mr-1"></i>Envoyer
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
<style>
  .mailbox-name {
    width: 180px;
  }
  .mailbox-subject {
    min-width: 300px;
  }
  .mailbox-date {
    width: 120px;
  }
  .table-responsive {
    min-height: 400px;
  }
  .bg-light {
    background-color: #f8f9fa !important;
  }
  .card-body {
    scrollbar-width: thin;
  }
</style>
<!-- jQuery -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

<script>
  $(function () {
    // Check/Uncheck all
    $('#checkAll').click(function () {
      $('input[type="checkbox"]').prop('checked', this.checked);
    });

    // Star functionality
    $('.mailbox-star').click(function (e) {
      e.preventDefault();
      var $icon = $(this).find('i');
      $icon.toggleClass('fa-star fa-star-o');
    });

    // Search functionality
    $('#emailSearch').on('keyup', function () {
      var value = $(this).val().toLowerCase();
      $('#emailsTable tbody tr').filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });

    $('#searchButton').click(function() {
      $('#emailSearch').trigger('keyup');
    });
  });
</script>
@endsection