<!-- Onglet Intervenants -->
<div class="tab-pane fade" id="intervenants" role="tabpanel" aria-labelledby="intervenants-tab">
    <div class="p-3">
        <h5 class="text-primary mb-3"><i class="fas fa-handshake"></i> Intervenants du dossier</h5>
        
        <!-- Client principal -->
        <div class="row mb-4 d-none">
            <div class="col-md-12">
                <div class="info-card bg-light p-3 rounded mb-3">
                    <label class="font-weight-bold">Client principal</label>
                    @php
                        $clientPrincipal = $dossier->intervenants()->wherePivot('role', 'client')->first();
                    @endphp
                    @if($clientPrincipal)
                        <div class="mt-2">
                            <h6 class="mb-1">{{ $clientPrincipal->identite_fr }}</h6>
                            <small class="text-muted">
                                {{ $clientPrincipal->categorie }} • 
                                {{ $clientPrincipal->email ?? 'Email non disponible' }} • 
                                {{ $clientPrincipal->telephone ?? 'Téléphone non disponible' }}
                            </small>
                        </div>
                    @else
                        <p class="text-muted mb-0">
                            Aucun client principal défini
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Autres intervenants -->
        <div class="row">
            <div class="col-md-12">
                
                @if($dossier->intervenants && count($dossier->intervenants) > 0)
                    @php
                        $autresIntervenants = $dossier->intervenants->filter(function($intervenant) use ($clientPrincipal) {
                            return $intervenant->id !== $clientPrincipal?->id;
                        });
                    @endphp
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover w-100" id="intervenants-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Intervenant</th>
                                    <th>Role</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @php
                                    // Prioritize intervenants whose pivot role is 'client'
                                    $intervenants = $dossier->intervenants->sortByDesc(function($i) {
                                        return (int) ($i->pivot->role === 'client');
                                    });
                                @endphp

                                @foreach($intervenants as $intervenantLie)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                <i class="fas fa-user-circle fa-2x text-primary"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $intervenantLie->identite_fr }}</strong>
                                                @if($intervenantLie->identite_ar)
                                                    <br>
                                                    <small class="text-muted">{{ $intervenantLie->identite_ar }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $intervenantLie->pivot->role ?? 'Non défini' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $intervenantLie->type ?? 'Non défini' }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('intervenants.show', $intervenantLie->id) }}" 
                                               class="btn btn-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('intervenants.edit', $intervenantLie->id) }}" 
                                               class="btn btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Aucun intervenant lié</h4>
                        <p class="text-muted">Cet dossier n'est pas encore lié à d'autres intervenants.</p>
                        <a href="{{ route('dossiers.edit', $dossier->id) }}#intervenants-lies" 
                           class="btn btn-primary mt-2">
                            <i class="fas fa-link mr-1"></i> Ajouter des liens
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<!-- Script DataTables pour Intervenants -->
<script>
    $(document).ready(function() {
        // Vérifier si la table existe avant d'initialiser DataTables
        if ($('#intervenants-table').length) {
            // Initialisation de DataTables pour les intervenants
            $('#intervenants-table').DataTable({
                "language": {
                    "url": "{{ asset('assets/plugins/datatables/fr-FR.json') }}"
                },
                },
                "responsive": true,
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
                "pageLength": 10,
                "order": [[0, 'asc']],
                "columnDefs": [
                    {
                        "targets": 3, // Colonne Actions (index 3 car on compte à partir de 0)
                        "orderable": false,
                        "searchable": false
                    },
                    {
                        "targets": [1, 2], // Colonnes Role (1) et Type (2)
                        "searchable": true,
                        "orderable": true
                    }
                ],
                "drawCallback": function(settings) {
                    // Ajouter un effet de survol personnalisé
                    $('#intervenants-table tbody tr').hover(
                        function() {
                            $(this).addClass('hover-row');
                        },
                        function() {
                            $(this).removeClass('hover-row');
                        }
                    );
                }
            });
        }
    });
</script>

<!-- Styles supplémentaires pour les intervenants -->
<style>
    #intervenants-table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    
    .hover-row {
        background-color: #f8f9fa !important;
        transition: background-color 0.3s ease;
    }
    
    .info-card {
        border-left: 4px solid #007bff;
    }
    
    .badge {
        font-size: 0.8em;
        padding: 0.4em 0.6em;
    }
    
    .table-responsive {
        border-radius: 0.375rem;
        overflow: hidden;
    }
    
    /* Style pour DataTables */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        margin-left: 0.5em;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
    }
</style>