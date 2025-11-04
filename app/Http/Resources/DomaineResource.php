<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DomaineResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'sous_domaines' => SousDomaineResource::collection($this->whenLoaded('sousDomaines')),
            'dossiers' => DossierResource::collection($this->whenLoaded('dossiers')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}