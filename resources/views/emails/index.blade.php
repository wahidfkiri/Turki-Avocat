@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold text-primary">
                        <i class="fas fa-envelope mr-2"></i>Gestion des Emails
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Emails - {{ $currentFolder ?? 'INBOX' }}</li>
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
                    <div class="alert alert-success alert-modern alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle mr-3 fa-lg"></i>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-1">Succès!</h6>
                                <p class="mb-0">{{ session('success') }}</p>
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-modern alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle mr-3 fa-lg"></i>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-1">Erreur!</h6>
                                <p class="mb-0">{{ session('error') }}</p>
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    @endif

                    @if(isset($error))
                    <div class="alert alert-danger alert-modern alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle mr-3 fa-lg"></i>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-1">Erreur!</h6>
                                <p class="mb-0">{{ $error }}</p>
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    @endif

                    @if(isset($warning))
                    <div class="alert alert-warning alert-modern alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle mr-3 fa-lg"></i>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-1">Attention!</h6>
                                <p class="mb-0">{{ $warning }}</p>
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <!-- Sidebar - Dossiers -->
                        <div class="col-md-3">
                            <div class="card card-modern">
                                <div class="card-header-modern text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="card-title mb-0">
                                            <i class="fas fa-folder mr-2"></i>
                                            Tous les Dossiers
                                            <span class="badge badge-light badge-modern ml-1">{{ $totalFolders ?? 0 }}</span>
                                        </h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0" style="max-height: 500px; overflow-y: auto;">
                                    <ul class="nav nav-pills flex-column">
                                        @forelse($folders as $folder)
                                            <li class="nav-item">
                                                <a href="{{ route('email.folder', $folder['name']) }}" 
                                                   class="nav-link {{ ($currentFolder ?? '') === $folder['name'] ? 'active' : '' }}
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

                            <!-- Card Actions -->
                            <div class="card card-modern">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-edit mr-1"></i>
                                        Nouvel Email
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary btn-modern btn-block" data-toggle="modal" data-target="#composeModal">
                                        <i class="fas fa-pen mr-1"></i>Composer
                                    </button>
                                    <a href="{{ route('email.reconnect') }}" class="btn btn-outline-secondary btn-modern btn-block mt-2">
                                        <i class="fas fa-sync-alt mr-1"></i>Rafraîchir
                                    </a>
                                </div>
                            </div>

                            <!-- Card Info -->
                            <div class="card card-modern">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Informations
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong>Compte:</strong></p>
                                    <p class="text-sm text-muted">{{ $account ?? 'wahid.fkiri@peakmind-solutions.com' }}</p>
                                    
                                    <p class="mb-1"><strong>Dossier actuel:</strong></p>
                                    <p class="text-sm text-muted">{{ $currentFolder ?? 'INBOX' }}</p>
                                    
                                    <p class="mb-1"><strong>Total dossiers:</strong></p>
                                    <p class="text-sm text-muted">{{ $totalFolders ?? 0 }}</p>
                                    
                                    <p class="mb-1"><strong>Emails affichés:</strong></p>
                                    <p class="text-sm text-muted">{{ count($emails ?? []) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Main Content -->
                        <div class="col-md-9">
                            <div class="card card-modern">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="card-title">
                                            @php
                                                $folderIcon = 'fa-folder';
                                                $folderNames = [
                                                    'INBOX' => 'fa-inbox',
                                                    'Sent' => 'fa-paper-plane', 
                                                    'Sent Items' => 'fa-paper-plane',
                                                    'Drafts' => 'fa-edit',
                                                    'Trash' => 'fa-trash',
                                                    'Deleted Items' => 'fa-trash',
                                                    'Bin' => 'fa-trash',
                                                    'Spam' => 'fa-exclamation-triangle',
                                                    'Junk' => 'fa-exclamation-triangle',
                                                    'Archive' => 'fa-archive',
                                                    'Archives' => 'fa-archive'
                                                ];
                                                
                                                foreach($folderNames as $name => $icon) {
                                                    if (strtoupper($currentFolder) == strtoupper($name)) {
                                                        $folderIcon = $icon;
                                                        break;
                                                    }
                                                }
                                            @endphp
                                            <i class="fas {{ $folderIcon }} mr-1 text-primary"></i>
                                            {{ $currentFolder ?? 'INBOX' }}
                                            @if(isset($emails) && count($emails) > 0)
                                                <span class="badge badge-primary badge-modern ml-2">{{ count($emails) }} email(s)</span>
                                            @endif
                                        </h3>
                                        <div class="card-tools">
                                            <div class="input-group input-group-modern" style="width: 250px;">
                                                <input type="text" name="table_search" class="form-control form-control-modern" placeholder="Rechercher..." id="emailSearch">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-primary btn-modern" id="searchButton">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body p-0">
                                    <!-- Bouton Attacher à un dossier (caché par défaut) -->
                                    <div id="attachButtonContainer" class="p-3 border-bottom" style="display: none;">
                                        <button type="button" class="btn btn-primary btn-modern" data-toggle="modal" data-target="#attachToFolderModal">
                                            <i class="fas fa-folder-plus mr-1"></i> Attacher à un dossier
                                        </button>
                                    </div>

                                    @if(isset($emails) && count($emails) > 0)
                                        <div class="table-responsive">
                                            <table class="table table-modern table-hover table-striped" id="emailsTable">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th style="width: 40px">
                                                            <div class="icheck-primary">
                                                                <input type="checkbox" id="checkAll">
                                                                <label for="checkAll"></label>
                                                            </div>
                                                        </th>
                                                        <th style="width: 40px"></th>
                                                        <th>Expéditeur</th>
                                                        <th>Sujet & Aperçu</th>
                                                        <th style="width: 40px"></th>
                                                        <th style="width: 150px">Date</th>
                                                        <th style="width: 100px">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($emails as $email)
                                                        @if(isset($email['error']))
                                                            <tr class="bg-warning">
                                                                <td colspan="7" class="text-center text-dark">
                                                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                                                    Erreur: {{ $email['error'] }}
                                                                </td>
                                                            </tr>
                                                        @else
                                                            <tr class="{{ $email['seen'] ? '' : 'font-weight-bold bg-light' }}">
                                                                <td>
                                                                    <div class="icheck-primary">
                                                                        <input type="checkbox" value="{{ $email['uid'] }}" id="check{{ $email['uid'] }}" class="email-checkbox">
                                                                        <label for="check{{ $email['uid'] }}"></label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <a href="#" class="text-warning mailbox-star">
                                                                        <i class="fas fa-star{{ $email['flagged'] ?? false ? '' : '-o' }}"></i>
                                                                    </a>
                                                                </td>
                                                                <td class="mailbox-name">
                                                                    <small>
                                                                        {{ $email['from_name'] ?: $email['from'] }}
                                                                    </small>
                                                                </td>
                                                                <td class="mailbox-subject">
                                                                    <a href="{{ route('email.show', ['folder' => $currentFolder, 'uid' => $email['uid']]) }}" 
                                                                       class="text-dark text-decoration-none">
                                                                        <div class="font-weight-bold">
                                                                            {{ $email['subject'] }}
                                                                        </div>
                                                                        @if(isset($email['preview']) && !empty(trim($email['preview'])))
                                                                            <div class="text-muted text-sm mt-1">
                                                                                {!! Str::limit($email['preview'], 80) !!}
                                                                            </div>
                                                                        @endif
                                                                    </a>
                                                                </td>
                                                                <td class="mailbox-attachment text-center">
                                                                    @if(isset($email['attachments_count']) && $email['attachments_count'] > 0)
                                                                        <i class="fas fa-paperclip text-muted" title="{{ $email['attachments_count'] }} pièce(s) jointe(s)"></i>
                                                                    @endif
                                                                </td>
                                                                <td class="mailbox-date text-sm">
                                                                    @if(isset($email['date']))
                                                                        <small>{{ $email['date'] }}</small>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div class="btn-group btn-group-sm">
                                                                        <a href="{{ route('email.show', ['folder' => $currentFolder, 'uid' => $email['uid']]) }}" 
                                                                           class="btn btn-info" title="Voir l'email">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            @php
                                                $emptyIcon = 'fa-folder-open';
                                                $emptyText = 'Aucun email trouvé dans ce dossier';
                                                
                                                if ($currentFolder == 'INBOX') {
                                                    $emptyIcon = 'fa-inbox';
                                                    $emptyText = 'Votre boîte de réception est vide';
                                                } elseif (in_array($currentFolder, ['Sent', 'Sent Items'])) {
                                                    $emptyIcon = 'fa-paper-plane';
                                                    $emptyText = 'Aucun email envoyé';
                                                } elseif ($currentFolder == 'Drafts') {
                                                    $emptyIcon = 'fa-edit';
                                                    $emptyText = 'Aucun brouillon';
                                                } elseif (in_array($currentFolder, ['Trash', 'Deleted Items', 'Bin'])) {
                                                    $emptyIcon = 'fa-trash';
                                                    $emptyText = 'Corbeille vide';
                                                }
                                            @endphp
                                            <i class="fas {{ $emptyIcon }} fa-3x text-muted mb-3"></i>
                                            <h4 class="text-muted">{{ $emptyText }}</h4>
                                            <p class="text-muted">Le dossier "{{ $currentFolder ?? 'INBOX' }}" ne contient aucun email.</p>
                                            <a href="{{ route('email.index') }}" class="btn btn-primary btn-modern mt-2">
                                                <i class="fas fa-inbox mr-1"></i> Retour à l'accueil
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                @if(isset($emails) && count($emails) > 0)
                                    <div class="card-footer clearfix">
                                        <div class="float-left">
                                            <button type="button" class="btn btn-default btn-sm btn-modern" onclick="location.reload()">
                                                <i class="fas fa-sync-alt mr-1"></i> Actualiser
                                            </button>
                                        </div>
                                        <div class="float-right">
                                            <small class="text-muted">
                                                Affichage de {{ count($emails) }} email(s)
                                            </small>
                                        </div>
                                    </div>
                                @endif
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
            <div class="modal-header bg-gradient-primary text-white">
                <h4 class="modal-title" id="composeModalLabel">
                    <i class="fas fa-pen mr-2"></i>Nouveau Message
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('email.send') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="email" class="form-control form-control-modern" name="to" placeholder="À:" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control form-control-modern" name="subject" placeholder="Sujet:" required>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control form-control-modern" name="content" style="height: 300px" required placeholder="Votre message..."></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btn-modern" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary btn-modern">
                        <i class="fas fa-paper-plane mr-1"></i>Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour attacher aux dossiers -->
<div class="modal fade" id="attachToFolderModal" tabindex="-1" role="dialog" aria-labelledby="attachToFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="attachToFolderModalLabel">
                    <i class="fas fa-folder-plus mr-2"></i>Attacher aux dossiers
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="attachToFolderForm">
                    @csrf
                    <div class="form-group">
                        <label for="folderSelect">Sélectionner un dossier :</label>
                        <select class="form-control form-control-modern" id="folderSelect" name="folder_id" required>
                            <option value="">-- Choisir un dossier --</option>
                            @foreach(\App\Models\Dossier::all() as $dossier)
                                <option value="{{ $dossier->id }}">{{ $dossier->numero_dossier }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="selectedEmailsInfo" class="alert alert-info">
                        <small><i class="fas fa-info-circle mr-1"></i> <span id="selectedCount">0</span> email(s) sélectionné(s)</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-modern" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary btn-modern" id="confirmAttach">
                    <i class="fas fa-link mr-1"></i> Attacher
                </button>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-color: #4361ee;
    --secondary-color: #6c757d;
    --success-color: #28a745;
    --danger-color: #dc3545;
    --warning-color: #ffc107;
    --info-color: #17a2b8;
    --light-color: #f8f9fa;
    --dark-color: #343a40;
    --border-radius: 10px;
    --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --transition: all 0.3s ease;
}

/* Modern Cards */
.card-modern {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    transition: var(--transition);
    background: #ffffff;
}

.card-modern:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    transform: translateY(-2px);
}

.card-header-modern {
    background: linear-gradient(135deg, var(--primary-color) 0%, #3a56e4 100%);
    border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
    border: none;
    color: white;
}

/* Modern Buttons */
.btn-modern {
    border-radius: 8px;
    font-weight: 500;
    transition: var(--transition);
    border: none;
    padding: 0.5rem 1.5rem;
    font-size: 0.875rem;
}

.btn-modern:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, #3a56e4 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #3a56e4 0%, #2f46c4 100%);
}

/* Modern Form Controls */
.form-control-modern {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    transition: var(--transition);
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
}

.form-control-modern:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    transform: translateY(-1px);
}

.input-group-modern .form-control {
    border-right: none;
}

.input-group-modern .input-group-append .btn {
    border-left: none;
    border-color: #e2e8f0;
}

/* Table Styles */
.table-modern {
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--box-shadow);
    background: white;
    width: 100%;
    border-collapse: collapse;
}

.table-modern thead th {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: none;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    padding: 1rem 1.5rem;
    text-align: left;
}

.table-modern tbody td {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.table-modern tbody tr {
    transition: var(--transition);
    cursor: pointer;
}

.table-modern tbody tr:hover {
    background-color: #f8fafc;
    transform: translateY(-1px);
}

.table-modern tbody tr.bg-light {
    background-color: #f0f9ff !important;
    font-weight: 600;
}

.table-modern tbody tr.bg-light:hover {
    background-color: #e1f0ff !important;
}

/* Badges */
.badge-modern {
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
}

/* Alerts */
.alert-modern {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    border-left: 4px solid;
    padding: 1rem 1.5rem;
    margin-bottom: 1rem;
}

.alert-success {
    border-left-color: var(--success-color);
    background: linear-gradient(135deg, #f0fff4 0%, #e6fffa 100%);
    color: #065f46;
}

.alert-danger {
    border-left-color: var(--danger-color);
    background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
    color: #7f1d1d;
}

.alert-warning {
    border-left-color: var(--warning-color);
    background: linear-gradient(135deg, #fffaf0 0%, #feebc8 100%);
    color: #78350f;
}

.alert-info {
    border-left-color: var(--info-color);
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    color: #0c4a6e;
}

/* Mailbox Styles */
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

/* Gradient Backgrounds */
.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, #3a56e4 100%) !important;
}

/* Modal Modern */
.modal-content {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.modal-header {
    border-radius: var(--border-radius) var(--border-radius) 0 0;
}

/* Star Animation */
.mailbox-star {
    transition: var(--transition);
}

.mailbox-star:hover {
    transform: scale(1.2);
}

/* Responsive */
@media (max-width: 768px) {
    .mailbox-name {
        width: 150px;
    }
    
    .mailbox-subject {
        min-width: 200px;
    }
    
    .table-modern {
        font-size: 0.875rem;
    }
    
    .input-group-modern {
        width: 200px !important;
    }
}
</style>

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('checkAll');
    const emailCheckboxes = document.querySelectorAll('.email-checkbox');
    const attachButtonContainer = document.getElementById('attachButtonContainer');
    const selectedCount = document.getElementById('selectedCount');
    
    // Fonction pour mettre à jour l'affichage du bouton
    function updateAttachButton() {
        const checkedBoxes = document.querySelectorAll('.email-checkbox:checked');
        const hasChecked = checkedBoxes.length > 0;
        
        attachButtonContainer.style.display = hasChecked ? 'block' : 'none';
        selectedCount.textContent = checkedBoxes.length;
    }
    
    // Événement pour "Tout cocher"
    checkAll.addEventListener('change', function() {
        emailCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateAttachButton();
    });
    
    // Événements pour les cases individuelles
    emailCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Mettre à jour l'état de "Tout cocher"
            const allChecked = document.querySelectorAll('.email-checkbox:checked').length === emailCheckboxes.length;
            const someChecked = document.querySelectorAll('.email-checkbox:checked').length > 0;
            
            checkAll.checked = allChecked;
            checkAll.indeterminate = someChecked && !allChecked;
            
            updateAttachButton();
        });
    });
    
    // Gestion de la soumission du formulaire
    document.getElementById('confirmAttach').addEventListener('click', function() {
        const selectedFolder = document.getElementById('folderSelect').value;
        const selectedEmails = Array.from(document.querySelectorAll('.email-checkbox:checked'))
            .map(checkbox => checkbox.value);
        
        if (!selectedFolder) {
            alert('Veuillez sélectionner un dossier');
            return;
        }
        
        if (selectedEmails.length === 0) {
            alert('Aucun email sélectionné');
            return;
        }
        
        // Afficher un indicateur de chargement
        const button = this;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Attachement...';
        button.disabled = true;
        
        // Envoi AJAX
        fetch('{{ route("email.attach-to-dossier") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                folder_id: selectedFolder,
                email_uids: selectedEmails,
                current_folder: '{{ $currentFolder }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Afficher message de succès
                showNotification('success', data.message);
                
                // Fermer le modal
                $('#attachToFolderModal').modal('hide');
                
                // Décocher toutes les cases
                document.querySelectorAll('.email-checkbox:checked').forEach(checkbox => {
                    checkbox.checked = false;
                });
                document.getElementById('checkAll').checked = false;
                
                // Masquer le bouton
                document.getElementById('attachButtonContainer').style.display = 'none';
                
            } else {
                showNotification('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Une erreur est survenue lors de l\'attachement');
        })
        .finally(() => {
            // Restaurer le bouton
            button.innerHTML = originalText;
            button.disabled = false;
        });
    });

    // Fonction pour afficher les notifications
    function showNotification(type, message) {
        // Utiliser Toastr ou une autre librairie de notification
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else {
            alert(message);
        }
    }
    
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
    
    // Réinitialiser le formulaire quand le modal se ferme
    $('#attachToFolderModal').on('hidden.bs.modal', function () {
        document.getElementById('folderSelect').value = '';
    });
});
</script>
@endsection