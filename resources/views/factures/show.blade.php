@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Détails de la Facture</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('factures.index') }}">Factures</a></li>
                        <li class="breadcrumb-item active">Détails</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Informations de la facture</h3>
                            <div class="card-tools">
                                @if(auth()->user()->hasPermission('edit_factures'))
                                    <a href="{{ route('factures.edit', $facture->id ?? '') }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                @endif
                                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Retour
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Informations principales -->
                                <div class="col-md-6">
                                    <h5 class="section-title">Informations principales</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%;">Type de pièce</th>
                                            <td>
                                                @php
                                                    $typeColors = [
                                                        'facture' => 'primary',
                                                        'note_frais' => 'info',
                                                        'note_provision' => 'warning',
                                                        'avoir' => 'success'
                                                    ];
                                                    $typeClass = $typeColors[$facture->type_piece ?? ''] ?? 'secondary';
                                                @endphp
                                                <span class="badge badge-{{ $typeClass }} text-uppercase">
                                                    {{ isset($facture->type_piece) ? str_replace('_', ' ', $facture->type_piece) : 'Non spécifié' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Numéro</th>
                                            <td class="font-weight-bold">{{ $facture->numero ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date d'émission</th>
                                            <td>{{ ($facture->date_emission ?? now())->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Statut</th>
                                            <td>
                                                @if(($facture->statut ?? '') == 'payé')
                                                    <span class="badge badge-success text-uppercase">
                                                        <i class="fas fa-check-circle"></i> Payé
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger text-uppercase">
                                                        <i class="fas fa-clock"></i> Non payé
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Détails financiers -->
                                <div class="col-md-6">
                                    <h5 class="section-title">Détails financiers</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%;">Montant HT</th>
                                            <td>{{ number_format($facture->montant_ht ?? 0, 2, ',', ' ') }} DT</td>
                                        </tr>
                                        <tr>
                                            <th>Montant TVA</th>
                                            <td>{{ number_format($facture->montant_tva ?? 0, 2, ',', ' ') }} DT</td>
                                        </tr>
                                        <tr>
                                            <th>Montant TTC</th>
                                            <td class="font-weight-bold text-success" style="font-size: 1.2em;">
                                                {{ number_format($facture->montant ?? 0, 2, ',', ' ') }} DT
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <!-- Relations -->
                                <div class="col-md-6">
                                    <h5 class="section-title">Relations</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%;">Dossier</th>
                                            <td>
                                                @if($facture->dossier ?? null)
                                                    <a href="{{ route('dossiers.show', $facture->dossier->id ?? '') }}" class="text-primary">
                                                        <i class="fa fa-eye"></i> {{ $facture->dossier->numero_dossier ?? 'N/A' }}
                                                    </a>
                                                    @if($facture->dossier->nom_dossier ?? null)
                                                        <br><small class="text-muted">{{ $facture->dossier->nom_dossier }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Non assigné</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Client</th>
                                            <td>
                                                @if($facture->client ?? null)
                                                    {{ $facture->client->identite_fr ?? $facture->client->identite_ar ?? 'N/A' }}
                                                    @if($facture->client->email ?? null)
                                                        <br><small class="text-muted">{{ $facture->client->email }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Non assigné</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Métadonnées -->
                                <div class="col-md-6">
                                    <h5 class="section-title">Métadonnées</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%;">Créé le</th>
                                            <td>{{ ($facture->created_at ?? now())->format('d/m/Y à H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Modifié le</th>
                                            <td>{{ ($facture->updated_at ?? now())->format('d/m/Y à H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Pièce jointe</th>
                                            <td>
                                                @if($facture->piece_jointe ?? null)
                                                                            <div class="btn-group btn-group-sm d-block">
                                                                                <a href="{{url('factures/display')}}/{{ $facture->id ?? '#' }}" target="_blank" title="Voir">
                                                                                    <i class="fas fa-eye text-success"></i> Afficher
                                                                                </a><br>
                                                                                <a href="{{url('factures/download')}}/{{ $facture->id ?? '#' }}" download title="Télécharger">
                                                                                    <i class="fas fa-download text-info"></i>  Télécharger
                                                                                </a>
                                                                            </div>
                                                    <!-- <div class="d-flex align-items-center">
                                                        <i class="fas 
                                                            @if(($facture->piece_jointe_extension ?? '') == 'pdf') fa-file-pdf text-danger
                                                            @elseif(in_array($facture->piece_jointe_extension ?? '', ['doc', 'docx'])) fa-file-word text-primary
                                                            @elseif(in_array($facture->piece_jointe_extension ?? '', ['xls', 'xlsx'])) fa-file-excel text-success
                                                            @elseif(in_array($facture->piece_jointe_extension ?? '', ['jpg', 'jpeg', 'png', 'gif'])) fa-file-image text-info
                                                            @else fa-file text-secondary
                                                            @endif
                                                        "></i>
                                                        <a href="{{ $facture->piece_jointe_url ?? '#' }}" target="_blank" class="ml-2">
                                                            {{ $facture->piece_jointe }}
                                                        </a>
                                                        <span class="badge badge-info ml-2">
                                                            {{ strtoupper($facture->piece_jointe_extension ?? 'N/A') }}
                                                        </span>
                                                    </div> -->
                                                @else
                                                    <span class="text-muted">Aucune pièce jointe</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Commentaires -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="section-title">Commentaires</h5>
                                    <div class="card">
                                        <div class="card-body bg-light">
                                            <p class="mb-0" style="white-space: pre-wrap;">{{ $facture->commentaires ?? 'Aucun commentaire' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ url()->previous() }}" class="btn btn-default">
                                        <i class="fas fa-arrow-left"></i> Retour à la liste
                                    </a>
                                </div>
                                <div class="col-md-6 text-right">
                                    @if(auth()->user()->hasPermission('edit_factures'))
                                        <a href="{{ route('factures.edit', $facture->id ?? '') }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                    @endif

                                    @if(auth()->user()->hasPermission('delete_factures') && ($facture->id ?? null))
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $facture->id }}, '{{ addslashes($facture->numero ?? 'cette facture') }}')">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- Formulaire de suppression -->
@if(auth()->user()->hasPermission('delete_factures') && ($facture->id ?? null))
    <form id="delete-form-{{ $facture->id }}" 
          action="{{ route('factures.destroy', $facture->id) }}" 
          method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endif

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
    // Fonction de confirmation de suppression
    function confirmDelete(factureId, factureNumero = 'cette facture') {
        if (confirm('Êtes-vous sûr de vouloir supprimer la facture "' + factureNumero + '" ? Cette action est irréversible.')) {
            // Afficher un indicateur de chargement
            const deleteButton = document.querySelector('.btn-danger');
            if (deleteButton) {
                const originalText = deleteButton.innerHTML;
                deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression...';
                deleteButton.disabled = true;
            }

            // Soumettre le formulaire de suppression
            document.getElementById('delete-form-' + factureId).submit();
        }
    }

    $(document).ready(function() {
        // Ajouter un effet de surbrillance sur les cartes au survol
        $('.card').hover(
            function() {
                $(this).addClass('shadow-sm');
            },
            function() {
                $(this).removeClass('shadow-sm');
            }
        );
    });
</script>
<script>
$(document).ready(function() {
    // Gestion du clic sur le bouton "Voir"
    $(document).on('click', '.view-facture-btn', function() {
        const factureId = $(this).data('id');
        loadFactureDetails(factureId);
    });

    // Fonction pour charger les détails de la facture
    function loadFactureDetails(factureId) {
        // Afficher le spinner de chargement
        $('#factureDetailsModalBody').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
                <p class="mt-2">Chargement des détails...</p>
            </div>
        `);
        
        // Ouvrir le modal
        $('#factureDetailsModal').modal('show');
        
        // Charger les détails via AJAX
        $.ajax({
            url: '/factures/' + factureId,
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                // Parser la réponse HTML
                const parser = new DOMParser();
                const doc = parser.parseFromString(response, 'text/html');
                
                // Extraire le contenu principal
                let modalContent = '';
                
                // Informations principales
                const mainInfoSection = doc.querySelector('.col-md-6:first-child');
                if (mainInfoSection) {
                    modalContent += `
                        <div class="row">
                            <div class="col-md-6">
                                ${mainInfoSection.innerHTML}
                            </div>
                    `;
                }
                
                // Détails financiers
                const financialSection = doc.querySelector('.col-md-6:nth-child(2)');
                if (financialSection) {
                    modalContent += `
                            <div class="col-md-6">
                                ${financialSection.innerHTML}
                            </div>
                        </div>
                    `;
                }
                
                // Relations
                const relationsSection = doc.querySelector('.col-md-6:nth-child(1) .section-title:contains("Relations")')?.closest('.col-md-6');
                if (relationsSection) {
                    modalContent += `
                        <div class="row mt-4">
                            <div class="col-md-6">
                                ${relationsSection.innerHTML}
                            </div>
                    `;
                }
                
                // Métadonnées
                const metadataSection = doc.querySelector('.col-md-6:nth-child(2) .section-title:contains("Métadonnées")')?.closest('.col-md-6');
                if (metadataSection) {
                    modalContent += `
                            <div class="col-md-6">
                                ${metadataSection.innerHTML}
                            </div>
                        </div>
                    `;
                }
                
                // Commentaires
                const commentsSection = doc.querySelector('.col-12:has(.section-title:contains("Commentaires"))');
                if (commentsSection) {
                    modalContent += `
                        <div class="row mt-4">
                            ${commentsSection.innerHTML}
                        </div>
                    `;
                }
                
                // Mettre à jour le contenu du modal
                $('#factureDetailsModalBody').html(modalContent);
                
                // Ajouter les boutons d'action dans le footer
                const modalFooter = `
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Fermer
                        </button>
                        @if(auth()->user()->hasPermission('edit_factures'))
                            <a href="/factures/${factureId}/edit" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                        @endif
                        @if(auth()->user()->hasPermission('delete_factures'))
                            <button type="button" class="btn btn-danger" onclick="confirmDeleteFromModal(${factureId})">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        @endif
                    </div>
                `;
                
                // Ajouter le footer au modal
                $('#factureDetailsModal .modal-content').append(modalFooter);
                
                // Mettre à jour le titre du modal avec le numéro de facture
                const factureNumero = doc.querySelector('.font-weight-bold:contains("N/A")')?.textContent || 
                                     doc.querySelector('td.font-weight-bold')?.textContent || 
                                     '#' + factureId;
                $('#factureDetailsModalLabel').html(`<i class="fas fa-file-invoice"></i> Détails de la facture ${factureNumero}`);
            },
            error: function(xhr, status, error) {
                $('#factureDetailsModalBody').html(`
                    <div class="text-center py-5">
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>Erreur lors du chargement des détails.</p>
                            <a href="/factures/${factureId}" class="btn btn-primary mt-2" target="_blank">
                                Ouvrir dans une nouvelle fenêtre
                            </a>
                        </div>
                    </div>
                `);
            }
        });
    }

    // Fonction de confirmation de suppression depuis le modal
    window.confirmDeleteFromModal = function(factureId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette facture ? Cette action est irréversible.')) {
            // Afficher un indicateur de chargement
            const deleteButton = $('.btn-danger[onclick*="confirmDeleteFromModal"]');
            deleteButton.html('<i class="fas fa-spinner fa-spin"></i> Suppression...');
            deleteButton.prop('disabled', true);

            // Soumettre la suppression via AJAX
            $.ajax({
                url: '/factures/' + factureId,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function(response) {
                    // Fermer le modal
                    $('#factureDetailsModal').modal('hide');
                    
                    // Recharger la DataTable
                    if (typeof table !== 'undefined' && $.fn.DataTable.isDataTable('#facturesTable')) {
                        table.ajax.reload(null, false);
                    }
                    
                    // Afficher un message de succès
                    showAlert('success', 'Facture supprimée avec succès');
                },
                error: function(xhr) {
                    showAlert('danger', 'Erreur lors de la suppression');
                    
                    // Réactiver le bouton
                    deleteButton.html('<i class="fas fa-trash"></i> Supprimer');
                    deleteButton.prop('disabled', false);
                }
            });
        }
    };

    // Nettoyer le modal quand il est fermé
    $('#factureDetailsModal').on('hidden.bs.modal', function() {
        // Réinitialiser le contenu du modal
        $(this).find('.modal-content').html(`
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="factureDetailsModalLabel">
                    <i class="fas fa-file-invoice"></i> Détails de la facture
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="factureDetailsModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement des détails...</p>
                </div>
            </div>
        `);
    });

    // Fermer le modal avec la touche ESC
    $(document).keydown(function(e) {
        if (e.keyCode === 27 && $('#factureDetailsModal').hasClass('show')) {
            $('#factureDetailsModal').modal('hide');
        }
    });
});

// Fonction pour afficher les alertes
function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <button type="button" class="close" data-dismiss="alert" style="position: absolute; right: 10px; top: 10px;">
                <span>&times;</span>
            </button>
            <div class="d-flex align-items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'} mr-2" style="font-size: 1.5rem;"></i>
                <div>
                    <strong>${type === 'success' ? 'Succès' : 'Erreur'}</strong>
                    <div class="small">${message}</div>
                </div>
            </div>
        </div>
    `;
    
    // Supprimer les alertes existantes
    $('.alert[style*="position: fixed"]').remove();
    
    // Ajouter la nouvelle alerte
    $('body').append(alertHtml);
    
    // Supprimer automatiquement après 5 secondes
    setTimeout(() => {
        $('.alert[style*="position: fixed"]').alert('close');
    }, 5000);
}
</script>
<style>
    .section-title {
        color: #495057;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .card {
        border: 1px solid #dee2e6;
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    .badge {
        font-size: 0.85em;
    }
    
    .btn {
        margin: 0 2px;
    }
    
    .table-bordered {
        border: 1px solid #dee2e6;
    }
    
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
        padding: 0.75rem;
        vertical-align: top;
    }
</style>
@endsection