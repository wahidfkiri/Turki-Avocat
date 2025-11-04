<!-- Onglet Facturation -->
<div class="tab-pane fade" id="facturation" role="tabpanel" aria-labelledby="facturation-tab">
    <div class="p-3">
        <div style="display: flow-root;">
            <h5 class="text-primary mb-3"><i class="fas fa-money-bill-wave"></i> Informations de facturation</h5>
            <a href="{{ route('dossiers.facturation.create', ['dossier' => $dossier->id]) }}" class="btn btn-primary mb-3" style="float: right;">
                <i class="fas fa-plus"></i> Ajouter une facture 
            </a>
        </div>

        @if($dossier->factures && $dossier->factures->count() > 0)
            <div class="table-responsive">
                <table id="facturesTable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Numéro</th>
                            <th>Date émission</th>
                            <th>Montant HT</th>
                            <th>Montant TVA</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dossier->factures as $facture)
                        <tr>
                            <td>{{ $facture->numero }}</td>
                            <td>{{ $facture->date_emission->format('d/m/Y') }}</td>
                            <td data-value="{{ $facture->montant_ht }}">{{ number_format($facture->montant_ht, 2) }} DT</td>
                            <td data-value="{{ $facture->montant_tva }}">{{ number_format($facture->montant_tva, 2) }} DT</td>
                            <td data-value="{{ $facture->montant }}">{{ number_format($facture->montant, 2) }} DT</td>
                            <td>
                                <span class="badge 
                                    @if($facture->statut == 'payé') badge-success
                                    @elseif($facture->statut == 'non_payé') badge-warning
                                    @else badge-secondary
                                    @endif">
                                    @if($facture->statut == 'payé')
                                    Payée
                                    @else 
                                    Non Payée
                                    @endif
                                </span>
                            </td>
                            <td>
                                <a href="{{route('factures.show', $facture)}}" class="btn btn-sm btn-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($facture->piece_jointe)
                                <a href="{{ url('factures/download')}}/{{ $facture->id }}" 
                                   download class="btn btn-sm btn-success" title="Télécharger">
                                    <i class="fas fa-download"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-light font-weight-bold">
                            <td colspan="2" class="text-right">TOTAUX :</td>
                            <td id="totalHT" class="text-success">0.00 DT</td>
                            <td id="totalTVA" class="text-success">0.00 DT</td>
                            <td id="totalMontant" class="text-success">0.00 DT</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="alert alert-info" style="color:black;">
                <h6><i class="icon fas fa-info"></i> Information</h6>
                <p class="mb-0">
                    Aucune facture n'a été ajoutée à ce dossier.
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
    // Function to calculate totals
    function calculateTotals() {
        var totalHT = 0;
        var totalTVA = 0;
        var totalMontant = 0;

        $('#facturesTable tbody tr').each(function() {
            var ht = parseFloat($(this).find('td:eq(2)').data('value')) || 0;
            var tva = parseFloat($(this).find('td:eq(3)').data('value')) || 0;
            var montant = parseFloat($(this).find('td:eq(4)').data('value')) || 0;

            totalHT += ht;
            totalTVA += tva;
            totalMontant += montant;
        });

        $('#totalHT').text(totalHT.toFixed(2) + ' DT');
        $('#totalTVA').text(totalTVA.toFixed(2) + ' DT');
        $('#totalMontant').text(totalMontant.toFixed(2) + ' DT');
    }

    // Initialize DataTable
    var table = $('#facturesTable').DataTable({
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
            // Recalculate totals when table changes (pagination, filtering, etc.)
            setTimeout(calculateTotals, 100);
        },
        "initComplete": function() {
            // Add custom filter for status
            // this.api().columns([5]).every(function() {
            //     var column = this;
            //     var select = $('<select class="form-control form-control-sm"><option value="">Tous les statuts</option></select>')
            //         .appendTo($(column.header()).empty())
            //         .on('change', function() {
            //             var val = $.fn.dataTable.util.escapeRegex($(this).val());
            //             column.search(val ? '^' + val + '$' : '', true, false).draw();
            //         });

            //     column.data().unique().sort().each(function(d, j) {
            //         select.append('<option value="' + d + '">' + d + '</option>');
            //     });
            // });

            // Calculate initial totals
            calculateTotals();
        },
        "drawCallback": function() {
            // Recalculate totals after each draw
            calculateTotals();
        }
    });

    // Recalculate totals when search/filter changes
    table.on('search.dt', function() {
        calculateTotals();
    });
});
</script>
