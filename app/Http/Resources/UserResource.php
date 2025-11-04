<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'fonction' => $this->fonction,
            'is_active' => $this->is_active,
            'can_facture' => $this->can_facture,
            'roles' => $this->getRoleNames(),
            'permissions' => $this->getAllPermissions()->pluck('name'),
            'dossiers' => DossierResource::collection($this->whenLoaded('dossiers')),
            'time_sheets' => TimeSheetResource::collection($this->whenLoaded('timeSheets')),
            'agendas' => AgendaResource::collection($this->whenLoaded('agendas')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}