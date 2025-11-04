

$(document).ready(function() {
    // Initialiser DataTable
    var table = $('#dossiersTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "pageLength": 10,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json"
        },
        "order": [[0, 'asc']],
        "initComplete": function() {
            // Masquer la recherche par défaut de DataTable
            $('.dataTables_filter').hide();
        }
    });

    // Recherche globale
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Réinitialiser la recherche
    $('#resetSearch').on('click', function() {
        $('#searchInput').val('');
        table.search('').draw();
    });

    // Filtre par domaine
    $('#domaineFilter').on('change', function() {
        var domaine = this.value;
        if (domaine) {
            table.column(3).search(domaine, true, false).draw();
        } else {
            table.column(3).search('').draw();
        }
    });

    // Filtre par statut
    $('#statutFilter').on('change', function() {
        var statut = this.value;
        if (statut) {
            if (statut === 'archive') {
                table.column(8).search('Oui', true, false).draw();
            } else if (statut === 'conseil') {
                table.column(6).search('Conseil', true, false).draw();
            } else if (statut === 'contentieux') {
                table.column(6).search('Contentieux', true, false).draw();
            }
        } else {
            table.column(6).search('').draw();
            table.column(8).search('').draw();
        }
    });

    // Ajouter des classes CSS pour les badges après le chargement
    table.on('draw', function() {
        // Badges pour la colonne Type (index 6)
        table.cells(null, 6).every(function() {
            var cell = this;
            var typeText = cell.data();
            var badgeClass = 'badge-secondary';
            
            if (typeText.includes('Mixte')) badgeClass = 'badge-warning';
            else if (typeText.includes('Conseil')) badgeClass = 'badge-info';
            else if (typeText.includes('Contentieux')) badgeClass = 'badge-primary';
            
            $(cell.node()).html('<span class="badge ' + badgeClass + '">' + typeText + '</span>');
        });

        // Badges pour la colonne Statut (index 7)
        table.cells(null, 7).every(function() {
            var cell = this;
            var statut = cell.data();
            var badgeClass = statut.includes('En cours') ? 'badge-success' : 'badge-secondary';
            $(cell.node()).html('<span class="badge ' + badgeClass + '">' + statut + '</span>');
        });

        // Badges pour la colonne Archivé (index 8)
        table.cells(null, 8).every(function() {
            var cell = this;
            var archive = cell.data();
            var badgeClass = archive === 'Oui' ? 'badge-danger' : 'badge-success';
            $(cell.node()).html('<span class="badge ' + badgeClass + '">' + archive + '</span>');
        });
    });

    // Confirmation de suppression
    $('.delete-form').on('submit', function(e) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer ce dossier ? Cette action est irréversible.')) {
            e.preventDefault();
        }
    });
});