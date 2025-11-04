<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Dossier;

class DossierPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view_dossiers');
    }

    public function view(User $user, Dossier $dossier)
    {
        return $user->hasPermissionTo('view_dossiers');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create_dossiers');
    }

    public function update(User $user, Dossier $dossier)
    {
        return $user->hasPermissionTo('edit_dossiers');
    }

    public function delete(User $user, Dossier $dossier)
    {
        return $user->hasPermissionTo('delete_dossiers');
    }
}