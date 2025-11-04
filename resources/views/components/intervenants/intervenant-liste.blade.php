<div class="tab-pane fade" id="intervenants-lies" role="tabpanel" aria-labelledby="intervenants-lies-tab">
    <div class="p-3">
        @if(($intervenant->intervenantsLies ?? null) && count($intervenant->intervenantsLies) > 0)
            <div class="alert alert-info">
                <h5><i class="icon fas fa-info"></i> Relations établies</h5>
                <p class="mb-0">
                    Cet intervenant est lié à {{ count($intervenant->intervenantsLies) }} autre(s) intervenant(s) avec les relations suivantes.
                </p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped w-100" id="intervenants-lies-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Intervenant</th>
                            <th>Relation</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($intervenant->intervenantsLies as $intervenantLie)
                        <tr>
                            <td>{{ $intervenantLie->id ?? 'N/A' }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $intervenantLie->identite_fr ?? 'N/A' }}</strong>
                                        @if($intervenantLie->identite_ar ?? null)
                                            <br>
                                            <small class="text-muted">{{ $intervenantLie->identite_ar }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $intervenantLie->pivot->relation ?? 'N/A' }}</td>
                            <td>{{ $intervenantLie->type ?? 'N/A' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @if(auth()->user()->hasPermission('view_intervenants'))
                                    <a href="{{ route('intervenants.show', $intervenantLie->id ?? '') }}" 
                                       class="btn btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endif
                                    @if(auth()->user()->hasPermission('edit_intervenants'))
                                    <a href="{{ route('intervenants.edit', $intervenantLie->id ?? '') }}" 
                                       class="btn btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Aucun intervenant lié</h4>
                <p class="text-muted">Cet intervenant n'est pas encore lié à d'autres intervenants.</p>
                <a href="{{ route('intervenants.edit', $intervenant->id ?? '') }}#intervenants-lies" 
                   class="btn btn-primary mt-2">
                    <i class="fas fa-link mr-1"></i> Ajouter des liens
                </a>
            </div>
        @endif
    </div>
</div>

<!-- DataTables CSS (if not already included) -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<!-- Script DataTables pour Intervenants Liés -->
<script>
    $(document).ready(function() {
        // Initialisation de DataTables pour les intervenants liés
        $('#intervenants-lies-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json"
            },
            "responsive": true,
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
            "pageLength": 10,
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Tous"]],
            "order": [[0, 'asc']], // Tri par ID par défaut
            "columnDefs": [
                {
                    "targets": 4, // Colonne Actions
                    "orderable": false,
                    "searchable": false
                },
                {
                    "targets": [0, 1, 2, 3], // Toutes les autres colonnes
                    "searchable": true,
                    "orderable": true
                }
            ],
            "drawCallback": function(settings) {
                // Ajouter un effet de survol personnalisé
                $('tr').hover(
                    function() {
                        $(this).addClass('hover-row');
                    },
                    function() {
                        $(this).removeClass('hover-row');
                    }
                );
            }
        });
    });
</script>

<!-- Styles supplémentaires pour les intervenants liés -->
<style>
    #intervenants-lies-table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    
    .hover-row {
        background-color: #f8f9fa !important;
        transition: background-color 0.3s ease;
    }
    
    /* Styles pour DataTables */
    #intervenants-lies-table_wrapper .dataTables_filter input {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        margin-left: 0.5rem;
    }
    
    #intervenants-lies-table_wrapper .dataTables_length select {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
    }
    
    #intervenants-lies-table_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.375rem;
        margin: 0 2px;
        border: 1px solid #dee2e6;
    }
    
    #intervenants-lies-table_wrapper .dataTables_paginate .paginate_button.current {
        background: #007bff;
        color: white !important;
        border: 1px solid #007bff;
    }
    
    #intervenants-lies-table_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e9ecef;
        border: 1px solid #dee2e6;
    }
    
    #intervenants-lies-table_wrapper .dataTables_info {
        padding-top: 0.85em;
    }
</style>