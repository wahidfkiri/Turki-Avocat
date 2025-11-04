$(document).ready(function() {
    // Initialisation des sélecteurs
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: 'Sélectionnez...'
    });

    // Chargement dynamique des sous-domaines via AJAX
    $('#domaine_id').change(function() {
        const domaineId = $(this).val();
        const sousDomaineSelect = $('#sous_domaine_id');
        const loadingText = '<option value="">Chargement...</option>';
        const noDataText = '<option value="">Aucun sous-domaine disponible</option>';

        if (domaineId) {
            // Afficher l'indicateur de chargement
            sousDomaineSelect.html(loadingText);
            sousDomaineSelect.prop('disabled', true);

            // Requête AJAX
            $.ajax({
                url: '/sous-domaines/by-domaine',
                type: 'GET',
                data: {
                    domaine_id: domaineId
                },
                success: function(response) {
                    if (response.length > 0) {
                        let options = '<option value="">Sélectionnez un sous-domaine</option>';
                        
                        response.forEach(function(sousDomaine) {
                            const selected = '{{ old("sous_domaine_id") }}' == sousDomaine.id ? 'selected' : '';
                            options += `<option value="${sousDomaine.id}" ${selected}>${sousDomaine.nom}</option>`;
                        });
                        
                        sousDomaineSelect.html(options);
                        sousDomaineSelect.prop('disabled', false);
                    } else {
                        sousDomaineSelect.html(noDataText);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur:', error);
                    sousDomaineSelect.html('<option value="">Erreur de chargement</option>');
                },
                complete: function() {
                    // Réinitialiser Select2
                    sousDomaineSelect.trigger('change.select2');
                }
            });
        } else {
            sousDomaineSelect.html('<option value="">Sélectionnez d\'abord un domaine</option>');
            sousDomaineSelect.prop('disabled', true);
        }
    });

    // Déclencher le changement au chargement si valeur existante
    const initialDomaineId = '{{ old("domaine_id") }}';
    if (initialDomaineId) {
        $('#domaine_id').val(initialDomaineId).trigger('change');
    }

    // Gestion de la navigation par onglets
    let currentTab = 0;
    const tabs = $('#dossierTabs .nav-link');
    const totalTabs = tabs.length;

    function updateNavigationButtons() {
        if (currentTab === 0) {
            $('.btn-previous').hide();
            $('.btn-next').show();
        } else if (currentTab === totalTabs - 1) {
            $('.btn-previous').show();
            $('.btn-next').hide();
        } else {
            $('.btn-previous').show();
            $('.btn-next').show();
        }
    }

    $('.btn-next').click(function() {
        if (currentTab < totalTabs - 1) {
            if (validateCurrentTab()) {
                currentTab++;
                $(tabs[currentTab]).tab('show');
                updateNavigationButtons();
            }
        }
    });

    $('.btn-previous').click(function() {
        if (currentTab > 0) {
            currentTab--;
            $(tabs[currentTab]).tab('show');
            updateNavigationButtons();
        }
    });

    tabs.on('shown.bs.tab', function(e) {
        currentTab = tabs.index(e.target);
        updateNavigationButtons();
    });

    function validateCurrentTab() {
        const currentTabId = tabs.eq(currentTab).attr('aria-controls');
        const requiredFields = $('#' + currentTabId).find('[required]');
        let isValid = true;

        requiredFields.each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            alert('Veuillez remplir tous les champs obligatoires de cet onglet avant de continuer.');
        }

        return isValid;
    }

    // Initialiser l'état des boutons
    updateNavigationButtons();
});

// Fonctions pour les DataTables (à utiliser dans les pages spécifiques)
function initIntervenantsDataTable() {
    return $('#intervenantsTable').DataTable({
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
        "order": [[0, 'desc']],
        "initComplete": function() {
            $('.dataTables_filter').hide();
        }
    });
}

function initDossiersDataTable() {
    return $('#dossiersTable').DataTable({
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
        "order": [[0, 'desc']],
        "initComplete": function() {
            $('.dataTables_filter').hide();
        }
    });
}