<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'fonction',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    // Relations
    public function dossiers()
    {
        return $this->belongsToMany(Dossier::class, 'dossier_user')
                    ->withPivot('ordre', 'role')
                    ->withTimestamps();
    }

    public function timeSheets()
    {
        return $this->hasMany(TimeSheet::class, 'utilisateur_id');
    }

    public function agendas()
    {
        return $this->hasMany(Agenda::class, 'utilisateur_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'utilisateur_id');
    }

    public function notifications()
{
    return $this->hasMany(Notification::class);
}


    public function hasPermission($permissionName)
    {
        if(\DB::table('model_has_permissions')
            ->join('permissions', 'model_has_permissions.permission_id', '=', 'permissions.id')
            ->where('permissions.name', $permissionName)
            ->where('model_id', $this->id)
            ->exists()){
            return true;
        }
        return false;
    }

    public function emailSetting()
    {
        return $this->hasOne(EmailSetting::class);
    }

    // Méthode pour générer une couleur aléatoire basée sur le nom
public function getAvatarColor()
{
    $name = $this->name;
    $colors = [
        '#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#9b59b6',
        '#1abc9c', '#d35400', '#c0392b', '#16a085', '#27ae60',
        '#2980b9', '#8e44ad', '#2c3e50', '#f1c40f', '#e67e22'
    ];
    
    // Utiliser le premier caractère pour choisir une couleur cohérente
    $index = ord(strtolower(substr($name, 0, 1))) % count($colors);
    return $colors[$index];
}

// Méthode pour obtenir les initiales
public function getInitials()
{
    $name = $this->name;
    $initials = '';
    
    // Prendre les deux premiers caractères
    $initials = strtoupper(substr($name, 0, 2));
    
    return $initials;
}

// Dans le modèle User
public function checkPermissions()
{
    $permissions = [
        'edit_dossiers',
        'delete_dossiers',
        'create_dossiers',
        'view_dossiers'
    ];

    $results = [];
    foreach ($permissions as $permission) {
        $results[$permission] = $this->hasPermissionTo($permission);
    }

    return $results;
}
}