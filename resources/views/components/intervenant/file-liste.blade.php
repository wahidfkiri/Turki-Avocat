<div class="tab-pane fade" id="fichiers" role="tabpanel" aria-labelledby="fichiers-tab">
    <div class="p-3">
        <h5 class="text-primary mb-3">
            <i class="fas fa-files-o"></i> Fichiers Attachés
        </h5>
        
        @if(($intervenant->files ?? null) && count($intervenant->files) > 0)
            <div class="alert alert-info">
                <h5><i class="icon fas fa-info"></i> Informations</h5>
                <p class="mb-0">
                    Cet intervenant a {{ count($intervenant->files) }} fichier(s) attaché(s). 
                    Vous pouvez visualiser ou télécharger chaque fichier.
                </p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped w-100" id="fichiers-table">
                    <thead>
                        <tr>
                            <th>Nom du fichier</th>
                            <th>Date d'upload</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($intervenant->files as $file)
                        <tr>
                            <td>
                                <span class="badge badge-info">{{ pathinfo($file->file_path ?? '', PATHINFO_EXTENSION) }}</span> 
                                {{ $file->file_name ?? $file->file_path ?? 'N/A' }}
                            </td>
                            <td>{{ ($file->created_at ?? now())->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ url('intervenant/display') }}/{{ $file->id ?? '#' }}" 
                                       target="_blank" class="btn btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ url('intervenant/download') }}/{{ $file->id ?? '#' }}" 
                                       download class="btn btn-success" title="Télécharger">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Aucun fichier attaché</h4>
                <p class="text-muted">Aucun fichier n'a été attaché à cet intervenant.</p>
                <a href="{{ route('intervenants.edit', $intervenant->id ?? '') }}#fichiers" 
                   class="btn btn-primary mt-2">
                    <i class="fas fa-upload mr-1"></i> Ajouter des fichiers
                </a>
            </div>
        @endif
    </div>
</div>


<!-- DataTables CSS (if not already included) -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<!-- DataTables Script for Fichiers -->
<script>
    $(document).ready(function() {
        // Initialisation de DataTables pour les fichiers
        $('#fichiers-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json"
            },
            "responsive": true,
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
            "pageLength": 10,
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Tous"]],
            "order": [[1, 'desc']], // Tri par date d'upload décroissante par défaut
            "columnDefs": [
                {
                    "targets": 2, // Colonne Actions
                    "orderable": false,
                    "searchable": false
                },
                {
                    "targets": [0, 1], // Colonnes Nom du fichier et Date d'upload
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

<!-- Styles supplémentaires pour les fichiers -->
<style>
    #fichiers-table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    
    .hover-row {
        background-color: #f8f9fa !important;
        transition: background-color 0.3s ease;
    }
    
    /* Styles pour DataTables */
    #fichiers-table_wrapper .dataTables_filter input {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        margin-left: 0.5rem;
    }
    
    #fichiers-table_wrapper .dataTables_length select {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
    }
    
    #fichiers-table_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.375rem;
        margin: 0 2px;
        border: 1px solid #dee2e6;
    }
    
    #fichiers-table_wrapper .dataTables_paginate .paginate_button.current {
        background: #007bff;
        color: white !important;
        border: 1px solid #007bff;
    }
    
    #fichiers-table_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e9ecef;
        border: 1px solid #dee2e6;
    }
    
    #fichiers-table_wrapper .dataTables_info {
        padding-top: 0.85em;
    }
    
    .badge {
        font-size: 0.75rem;
        margin-right: 5px;
    }
</style>