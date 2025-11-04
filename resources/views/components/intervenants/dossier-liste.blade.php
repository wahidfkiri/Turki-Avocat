<div class="tab-pane fade" id="dossiers-lies" role="tabpanel" aria-labelledby="dossiers-tab">
    <div class="p-3">
        <!-- Dossiers -->
        <h5 class="text-primary mb-3">
            <i class="fas fa-folder-open"></i> Dossiers Liés
        </h5>
        @if(($intervenant->dossiers ?? null) && count($intervenant->dossiers) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped w-100" id="dossiers-table">
                    <thead>
                        <tr>
                            <th>Numéro Dossier</th>
                            <th>Nom Dossier</th>
                            <th>Objet</th>
                            <th>Archivé</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($intervenant->dossiers as $dossier)
                        <tr>
                            <td>{{ $dossier->numero_dossier ?? 'N/A' }}</td>
                            <td>{{ $dossier->nom_dossier ?? 'N/A' }}</td>
                            <td>{{ $dossier->objet ?? 'N/A' }}</td>
                            <td>{{ ($dossier->archive ?? false) ? 'Oui' : 'Non' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @if(auth()->user()->hasPermission('view_dossiers'))
                                        <a href="{{ route('dossiers.show', $dossier->id ?? '') }}" 
                                           class="btn btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    @if(auth()->user()->hasPermission('edit_dossiers'))
                                        <a href="{{ route('dossiers.edit', $dossier->id ?? '') }}" 
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
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>
                Aucun dossier lié à cet intervenant.
            </div>
        @endif
    </div>
</div>

<!-- DataTables CSS (if not already included) -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<!-- Script DataTables pour Dossiers Liés -->
<script>
    $(document).ready(function() {
        // Initialisation de DataTables pour les dossiers liés
        $('#dossiers-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json"
            },
            "responsive": true,
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
            "pageLength": 10,
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Tous"]],
            "order": [[0, 'asc']],
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

<!-- Styles supplémentaires pour les dossiers liés -->
<style>
    #dossiers-table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    
    .hover-row {
        background-color: #f8f9fa !important;
        transition: background-color 0.3s ease;
    }
    
    /* Styles pour DataTables */
    #dossiers-table_wrapper .dataTables_filter input {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        margin-left: 0.5rem;
    }
    
    #dossiers-table_wrapper .dataTables_length select {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
    }
    
    #dossiers-table_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.375rem;
        margin: 0 2px;
        border: 1px solid #dee2e6;
    }
    
    #dossiers-table_wrapper .dataTables_paginate .paginate_button.current {
        background: #007bff;
        color: white !important;
        border: 1px solid #007bff;
    }
    
    #dossiers-table_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e9ecef;
        border: 1px solid #dee2e6;
    }
    
    #dossiers-table_wrapper .dataTables_info {
        padding-top: 0.85em;
    }
</style>