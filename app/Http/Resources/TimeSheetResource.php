<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimeSheetResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date_timesheet' => $this->date_timesheet,
            'utilisateur' => new UserResource($this->whenLoaded('user')),
            'dossier' => new DossierResource($this->whenLoaded('dossier')),
            'description' => $this->description,
            'categorie' => new CategorieResource($this->whenLoaded('categorieRelation')),
            'type' => new TypeResource($this->whenLoaded('typeRelation')),
            'quantite' => (float) $this->quantite,
            'prix' => (float) $this->prix,
            'total' => (float) $this->total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}