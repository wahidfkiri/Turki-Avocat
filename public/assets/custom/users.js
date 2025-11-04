
    $(function () {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        // Sélection/Désélection globale des permissions par module
        $('.card-header').click(function() {
            const cardBody = $(this).next('.card-body');
            const checkboxes = cardBody.find('.permission-checkbox');
            const allChecked = checkboxes.length === checkboxes.filter(':checked').length;
            
            checkboxes.prop('checked', !allChecked);
        });

        // Validation côté client
        $('#userForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 8
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                },
                fonction: {
                    required: true
                },
                'roles': {
                    required: true
                },
                'permissions[]': {
                    required: false
                }
            },
            messages: {
                name: {
                    required: "Veuillez entrer le nom complet",
                    minlength: "Le nom doit contenir au moins 2 caractères"
                },
                email: {
                    required: "Veuillez entrer une adresse email",
                    email: "Veuillez entrer une adresse email valide"
                },
                password: {
                    required: "Veuillez entrer un mot de passe",
                    minlength: "Le mot de passe doit contenir au moins 8 caractères"
                },
                password_confirmation: {
                    required: "Veuillez confirmer le mot de passe",
                    equalTo: "Les mots de passe ne correspondent pas"
                },
                fonction: {
                    required: "Veuillez sélectionner une fonction"
                },
                'roles': {
                    required: "Veuillez sélectionner un rôle"
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

        // Gestion automatique des permissions basées sur les rôles
        $('#roles').on('change', function() {
            const selectedRoles = $(this).val();
            
            // Réinitialiser toutes les permissions
            $('.permission-checkbox').prop('checked', false);
            
            if (selectedRoles && selectedRoles.length > 0) {
                // Simuler la récupération des permissions des rôles (à adapter avec une requête AJAX si nécessaire)
                $.each(selectedRoles, function(index, role) {
                    // Cette partie devrait être adaptée pour récupérer les permissions réelles depuis le backend
                    // Pour l'instant, c'est une simulation
                    switch(role) {
                        case 'admin':
                            $('.permission-checkbox').prop('checked', true);
                            break;
                        case 'avocat':
                            $('.permission-checkbox').not('[value*="users"]').prop('checked', true);
                            break;
                        case 'secrétaire':
                            $('#permission_view_users, #permission_view_dossiers, #permission_create_dossiers, #permission_edit_dossiers, #permission_view_intervenants, #permission_create_intervenants, #permission_edit_intervenants, #permission_view_agendas, #permission_create_agendas, #permission_edit_agendas, #permission_view_tasks, #permission_create_tasks, #permission_edit_tasks').prop('checked', true);
                            break;
                    }
                });
            }
        });
    });