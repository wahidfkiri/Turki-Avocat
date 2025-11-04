<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DossierResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'numero_dossier' => $this->numero_dossier,
            'nom_dossier' => $this->nom_dossier,
            'objet' => $this->objet,
            'date_entree' => $this->date_entree,
            'domaine' => new DomaineResource($this->whenLoaded('domaine')),
            'sous_domaine' => new SousDomaineResource($this->whenLoaded('sousDomaine')),
            'conseil' => $this->conseil,
            'contentieux' => $this->contentieux,
            'numero_role' => $this->numero_role,
            'chambre' => $this->chambre,
            'numero_chambre' => $this->numero_chambre,
            'numero_parquet' => $this->numero_parquet,
            'numero_instruction' => $this->numero_instruction,
            'numero_plainte' => $this->numero_plainte,
            'archive' => $this->archive,
            'date_archive' => $this->date_archive,
            'boite_archive' => $this->boite_archive,
            'users' => UserResource::collection($this->whenLoaded('users')),
            'intervenants' => IntervenantResource::collection($this->whenLoaded('intervenants')),
            'dossiers_lies' => DossierResource::collection($this->whenLoaded('dossiersLies')),
            'time_sheets' => TimeSheetResource::collection($this->whenLoaded('timeSheets')),
            'agendas' => AgendaResource::collection($this->whenLoaded('agendas')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'factures' => FactureResource::collection($this->whenLoaded('factures')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}