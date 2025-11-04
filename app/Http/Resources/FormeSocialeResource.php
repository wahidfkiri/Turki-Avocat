<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FormeSocialeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'intervenants' => IntervenantResource::collection($this->whenLoaded('intervenants')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}