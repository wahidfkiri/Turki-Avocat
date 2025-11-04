<!-- Onglet Dossiers -->
<div class="tab-pane fade" id="dossiers" role="tabpanel" aria-labelledby="dossiers-tab">
    <div class="p-3">
        <h5 class="text-primary mb-3"><i class="fas fa-money-bill-wave"></i> Informations de dossiers liés</h5>

        @if($dossier->dossiersLies && $dossier->dossiersLies->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover w-100" id="dossiers-table">
                    <thead class="thead-light">
                        <tr>
                            <th>Numéro Dossier</th>
                            <th>Nom Dossier</th>
                            <th>Date Entrée</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dossier->dossiersLies as $dossierLie)
                        <tr>
                            <td>{{ $dossierLie->numero_dossier }}</td>
                            <td>{{ $dossierLie->nom_dossier }}</td>
                            <td>{{ $dossierLie->date_entree->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('dossiers.show', $dossierLie) }}" class="btn btn-sm btn-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info" style="color:black;">
                <h6><i class="icon fas fa-info"></i> Information</h6>
                <p class="mb-0">
                    Aucun dossier lié n'a été trouvé.
                </p>
            </div>
        @endif
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialisation de DataTables
        $('#dossiers-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json"
            },
            "responsive": true,
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
            "pageLength": 10,
            "order": [[0, 'asc']],
            "columnDefs": [
                {
                    "targets": 3, // Colonne Actions
                    "orderable": false,
                    "searchable": false
                }
            ]
        });
    });
</script>

<!-- Styles supplémentaires -->
<style>
    .dataTables_wrapper .dataTables_filter {
        float: right;
        margin-bottom: 15px;
    }
    
    .dataTables_wrapper .dataTables_length {
        float: left;
        margin-bottom: 15px;
    }
    
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 15px;
    }
    
    #dossiers-table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    
    .dataTables_wrapper .dataTables_info {
        padding-top: 15px;
    }
</style>