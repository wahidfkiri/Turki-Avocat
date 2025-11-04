
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        // Calcul automatique du total
        function calculateTotal() {
            var quantite = parseFloat($('#quantite').val()) || 0;
            var prix = parseFloat($('#prix').val()) || 0;
            var total = quantite * prix;
            
            $('#total_calcule').val(total.toFixed(2) + ' DT');
        }

        // Écouter les changements sur quantité et prix
        $('#quantite, #prix').on('input', function() {
            calculateTotal();
        });

        // Calcul initial
        calculateTotal();

        // Validation côté client
        $('#timesheetForm').validate({
            rules: {
                date_timesheet: {
                    required: true
                },
                utilisateur_id: {
                    required: true
                },
                quantite: {
                    required: true,
                    min: 0
                },
                prix: {
                    required: true,
                    min: 0
                },
                description: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                date_timesheet: {
                    required: "La date est obligatoire"
                },
                utilisateur_id: {
                    required: "L'utilisateur est obligatoire"
                },
                quantite: {
                    required: "La quantité est obligatoire",
                    min: "La quantité doit être positive"
                },
                prix: {
                    required: "Le prix est obligatoire",
                    min: "Le prix doit être positif"
                },
                description: {
                    required: "La description est obligatoire",
                    minlength: "La description doit contenir au moins 10 caractères"
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) {
                // Afficher un loader ou désactiver le bouton pendant la soumission
                $('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Création en cours...');
                form.submit();
            }
        });

       
    });