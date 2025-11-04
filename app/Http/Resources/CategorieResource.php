<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategorieResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'time_sheets' => TimeSheetResource::collection($this->whenLoaded('timeSheets')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}