<!-- Onglet Feuille de temps -->
<div class="tab-pane fade" id="timesheet" role="tabpanel" aria-labelledby="timesheet-tab">
    <div class="p-3">
        <div style="display: flow-root;">
            <h5 class="text-primary mb-3"><i class="fas fa-money-bill-wave"></i> Informations des feuilles de temps</h5>
            <a href="{{ route('dossiers.timesheets.create', ['dossier' => $dossier->id]) }}" class="btn btn-primary mb-3" style="float: right;">
                <i class="fas fa-plus"></i> Ajouter une feuille de temps 
            </a>
        </div>

        @if($dossier->timeSheets && $dossier->timeSheets->count() > 0)
            <div class="table-responsive">
                <table id="timesheetsTable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Utilisateur</th>
                            <th>Dossier</th>
                            <th>Catégorie</th>
                            <th>Type</th>
                            <th>Quantité</th>
                            <th>Prix</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dossier->timeSheets as $time_sheet)
                        <tr>
                            <td>{{ $time_sheet->date_timesheet->format('d/m/Y') }}</td>
                            <td>{{ $time_sheet->description }}</td>
                            <td>{{ $time_sheet->user->name }}</td>
                            <td>{{ $time_sheet->dossier->numero_dossier }}</td>
                            <td>{{ $time_sheet->categorieRelation->nom ?? '' }}</td>
                            <td>{{ $time_sheet->typeRelation->nom ?? ''}}</td>
                            <td data-value="{{ $time_sheet->quantite ?? 0 }}">{{ $time_sheet->quantite ?? ''}}</td>
                            <td data-value="{{ $time_sheet->prix ?? 0 }}">{{ number_format($time_sheet->prix ?? 0, 2, ',', ' ') }} DT</td>
                            <td data-value="{{ $time_sheet->total ?? 0 }}">{{ number_format($time_sheet->total ?? 0, 2, ',', ' ') }} DT</td>
                            <td>
                                @if(auth()->user()->hasPermission('view_timesheets'))
                                <a href="{{route('time-sheets.show', $time_sheet)}}" class="btn btn-sm btn-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endif
                                @if(auth()->user()->hasPermission('edit_timesheets'))
                                <a href="{{route('time-sheets.edit', $time_sheet)}}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-light font-weight-bold">
                            <td colspan="6" class="text-right">TOTAUX :</td>
                            <td id="totalQuantite">0</td>
                            <td id="totalPrix">0,00 DT</td>
                            <td id="totalMontant">0,00 DT</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="alert alert-info" style="color:black;">
                <h6><i class="icon fas fa-info"></i> Information</h6>
                <p class="mb-0">
                    Aucune feuille de temps n'a été ajoutée à ce dossier.
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
    // Function to parse French number format with spaces
    function parseFrenchNumber(numberStr) {
        if (!numberStr) return 0;
        
        // Remove currency symbol and trim
        var cleanStr = numberStr.toString()
            .replace(' DT', '')
            .replace(/\s/g, '')  // Remove all spaces
            .replace(',', '.')   // Replace French decimal comma with dot
            .trim();
            
        var result = parseFloat(cleanStr) || 0;
        console.log('Parsing:', numberStr, '->', cleanStr, '->', result); // Debug
        return result;
    }

    // Function to calculate totals for timesheets
    function calculateTimesheetTotals() {
        var totalQuantite = 0;
        var totalPrix = 0;
        var totalMontant = 0;

        $('#timesheetsTable tbody tr').each(function() {
            // Get values from data attributes first (more reliable)
            var quantiteData = $(this).find('td:eq(6)').data('value');
            var prixData = $(this).find('td:eq(7)').data('value');
            var montantData = $(this).find('td:eq(8)').data('value');
            
            // Use data attributes if available, otherwise parse from text
            var quantite = quantiteData !== undefined ? parseFloat(quantiteData) || 0 : parseFrenchNumber($(this).find('td:eq(6)').text());
            var prix = prixData !== undefined ? parseFloat(prixData) || 0 : parseFrenchNumber($(this).find('td:eq(7)').text());
            var montant = montantData !== undefined ? parseFloat(montantData) || 0 : parseFrenchNumber($(this).find('td:eq(8)').text());

            totalQuantite += quantite;
            totalPrix += prix;
            totalMontant += montant;
        });

        // Format totals in French format
        $('#totalQuantite').text(totalQuantite.toFixed(0));
        $('#totalPrix').text(totalPrix.toFixed(2).replace('.', ',') + ' DT');
        $('#totalMontant').text(totalMontant.toFixed(2).replace('.', ',') + ' DT');
    }

    // Initialize DataTable for timesheets
    var timesheetTable = $('#timesheetsTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/French.json"
        },
        "responsive": true,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "pageLength": 10,
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        "footerCallback": function (row, data, start, end, display) {
            setTimeout(calculateTimesheetTotals, 100);
        },
        "initComplete": function() {
            // Add custom filters for category and type columns
            this.api().columns([4]).every(function() {
                var column = this;
                var select = $('<select class="form-control form-control-sm"><option value="">Toutes les catégories</option></select>')
                    .appendTo($(column.header()).empty())
                    .on('change', function() {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });

                column.data().unique().sort().each(function(d, j) {
                    if (d) {
                        select.append('<option value="' + d + '">' + d + '</option>');
                    }
                });
            });

            this.api().columns([5]).every(function() {
                var column = this;
                var select = $('<select class="form-control form-control-sm"><option value="">Tous les types</option></select>')
                    .appendTo($(column.header()).empty())
                    .on('change', function() {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });

                column.data().unique().sort().each(function(d, j) {
                    if (d) {
                        select.append('<option value="' + d + '">' + d + '</option>');
                    }
                });
            });

            setTimeout(calculateTimesheetTotals, 200);
        },
        "drawCallback": function() {
            setTimeout(calculateTimesheetTotals, 100);
        }
    });

    // Recalculate totals when search/filter changes
    timesheetTable.on('search.dt', function() {
        setTimeout(calculateTimesheetTotals, 100);
    });
    
    timesheetTable.on('draw', function() {
        setTimeout(calculateTimesheetTotals, 100);
    });

    // Initial calculation
    setTimeout(calculateTimesheetTotals, 500);
});
</script>