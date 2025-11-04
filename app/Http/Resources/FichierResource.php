<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FichierResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type_module' => $this->type_module,
            'module_id' => $this->module_id,
            'nom_fichier' => $this->nom_fichier,
            'chemin_fichier' => $this->chemin_fichier,
            'url' => asset('storage/' . $this->chemin_fichier),
            'type_mime' => $this->type_mime,
            'taille' => $this->taille,
            'description' => $this->description,
            'date_upload' => $this->date_upload,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}