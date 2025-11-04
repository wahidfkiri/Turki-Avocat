<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les permissions
        $permissions = [
            // Gestion des intervenants
            'view_intervenants',
            'create_intervenants',
            'edit_intervenants',
            'delete_intervenants',
            
            // Gestion des dossiers
            'view_dossiers',
            'create_dossiers',
            'edit_dossiers',
            'delete_dossiers',
            
            // Gestion des utilisateurs
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // Gestion de la facturation
            'view_factures',
            'create_factures',
            'edit_factures',
            'delete_factures',
            
            // Gestion de l'agenda
            'view_agendas',
            'create_agendas',
            'edit_agendas',
            'delete_agendas',
            
            // Gestion des tâches
            'view_tasks',
            'create_tasks',
            'edit_tasks',
            'delete_tasks',
            
            // Gestion des timesheets
            'view_timesheets',
            'create_timesheets',
            'edit_timesheets',
            'delete_timesheets',
            
            // Administration
            'access_admin_panel',
            'manage_settings',
            'view_reports',
            'export_data',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Créer les rôles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $avocatRole = Role::firstOrCreate(['name' => 'avocat']);
        $secretaireRole = Role::firstOrCreate(['name' => 'secrétaire']);
        $clercRole = Role::firstOrCreate(['name' => 'clerc']);
        $stagiaireRole = Role::firstOrCreate(['name' => 'stagiaire']);

        // Assigner toutes les permissions au rôle admin
        $adminRole->syncPermissions(Permission::all());

        // Permissions pour avocat
        $avocatPermissions = [
            'view_intervenants', 'create_intervenants', 'edit_intervenants',
            'view_dossiers', 'create_dossiers', 'edit_dossiers',
            'view_factures', 'create_factures', 'edit_factures',
            'view_agendas', 'create_agendas', 'edit_agendas', 'delete_agendas',
            'view_tasks', 'create_tasks', 'edit_tasks', 'delete_tasks',
            'view_timesheets', 'create_timesheets', 'edit_timesheets',
            'view_reports', 'export_data',
        ];
        $avocatRole->syncPermissions($avocatPermissions);

        // Permissions pour secrétaire
        $secretairePermissions = [
            'view_intervenants', 'create_intervenants', 'edit_intervenants',
            'view_dossiers', 'create_dossiers', 'edit_dossiers',
            'view_agendas', 'create_agendas', 'edit_agendas',
            'view_tasks', 'create_tasks', 'edit_tasks',
            'view_timesheets', 'create_timesheets', 'edit_timesheets',
        ];
        $secretaireRole->syncPermissions($secretairePermissions);

        // Permissions pour clerc
        $clercPermissions = [
            'view_intervenants',
            'view_dossiers', 'edit_dossiers',
            'view_agendas', 'create_agendas', 'edit_agendas',
            'view_tasks', 'create_tasks', 'edit_tasks',
            'view_timesheets', 'create_timesheets', 'edit_timesheets',
        ];
        $clercRole->syncPermissions($clercPermissions);

        // Permissions pour stagiaire (lecture seule)
        $stagiairePermissions = [
            'view_intervenants',
            'view_dossiers',
            'view_agendas',
            'view_tasks',
            'view_timesheets',
        ];
        $stagiaireRole->syncPermissions($stagiairePermissions);

        // Créer un utilisateur admin par défaut
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@cabinet.com'],
            [
                'name' => 'Administrateur',
                'password' => bcrypt('password'),
                'fonction' => 'admin',
                'is_active' => true,
                
            ]
        );
        $adminUser->assignRole('admin');

        // Créer un utilisateur avocat par défaut
        $avocatUser = User::firstOrCreate(
            ['email' => 'avocat@cabinet.com'],
            [
                'name' => 'Maître Avocat',
                'password' => bcrypt('password'),
                'fonction' => 'avocat',
                'is_active' => true,
                
            ]
        );
        $avocatUser->assignRole('avocat');

        // Créer un utilisateur secrétaire par défaut
        $secretaireUser = User::firstOrCreate(
            ['email' => 'secretaire@cabinet.com'],
            [
                'name' => 'Secrétaire',
                'password' => bcrypt('password'),
                'fonction' => 'secrétaire',
                'is_active' => true,
            ]
        );
        $secretaireUser->assignRole('secrétaire');

        $this->command->info('Rôles et permissions créés avec succès!');
        $this->command->info('Utilisateurs par défaut créés:');
        $this->command->info('- Admin: admin@cabinet.com / password');
        $this->command->info('- Avocat: avocat@cabinet.com / password');
        $this->command->info('- Secrétaire: secretaire@cabinet.com / password');
    }
}