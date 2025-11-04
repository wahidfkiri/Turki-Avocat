$(document).ready(function() {
    // Initialiser DataTable
    var table = $('#intervenantsTable').DataTable({
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

    // Filtre par catégorie
    $('#categorieFilter').on('change', function() {
        var categorie = this.value;
        if (categorie) {
            table.column(4).search(categorie, true, false).draw();
        } else {
            table.column(4).search('').draw();
        }
    });

    // Filtre par type
    $('#typeFilter').on('change', function() {
        var type = this.value;
        if (type) {
            table.column(3).search(type, true, false).draw();
        } else {
            table.column(3).search('').draw();
        }
    });

    // Ajouter des classes CSS pour les badges après le chargement
    table.on('draw', function() {
        // Badges pour la colonne Type (index 3)
        table.cells(null, 2).every(function() {
            var cell = this;
            var type = cell.data();
            $(cell.node()).html('<span class="badge badge-info">' + type + '</span>');
        });

        // Badges pour la colonne Catégorie (index 4)
        table.cells(null, 2).every(function() {
            var cell = this;
            var categorie = cell.data();
            var badgeClass = getBadgeClass(categorie);
            $(cell.node()).html('<span class="badge ' + badgeClass + '">' + categorie + '</span>');
        });

        // Badges pour la colonne Archivé (index 8)
        table.cells(null, 8).every(function() {
            var cell = this;
            var archive = cell.data();
            var badgeClass = archive === 'Oui' ? 'badge-danger' : 'badge-success';
            $(cell.node()).html('<span class="badge ' + badgeClass + '">' + archive + '</span>');
        });
    });

    // Fonction pour déterminer la classe du badge selon la catégorie
    function getBadgeClass(categorie) {
        var badgeClasses = {
            'client': 'badge-success',
            'avocat': 'badge-primary',
            'adversaire': 'badge-danger',
            'contact': 'badge-secondary',
            'notaire': 'badge-warning',
            'huissier': 'badge-dark',
            'juridiction': 'badge-info',
            'administrateur_judiciaire': 'badge-light',
            'mandataire_judiciaire': 'badge-light',
            'expert_judiciaire': 'badge-info'
        };
        return badgeClasses[categorie] || 'badge-secondary';
    }

    // Confirmation de suppression
    $('.delete-form').on('submit', function(e) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cet intervenant ? Cette action est irréversible.')) {
            e.preventDefault();
        }
    });
});