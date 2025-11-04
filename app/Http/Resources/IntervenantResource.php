<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IntervenantResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'identite_fr' => $this->identite_fr,
            'identite_ar' => $this->identite_ar,
            'type' => $this->type,
            'numero_cni' => $this->numero_cni,
            'rne' => $this->rne,
            'numero_cnss' => $this->numero_cnss,
            'forme_sociale' => new FormeSocialeResource($this->whenLoaded('formeSociale')),
            'categorie' => $this->categorie,
            'fonction' => $this->fonction,
            'adresse1' => $this->adresse1,
            'adresse2' => $this->adresse2,
            'portable1' => $this->portable1,
            'portable2' => $this->portable2,
            'mail1' => $this->mail1,
            'mail2' => $this->mail2,
            'site_internet' => $this->site_internet,
            'fixe1' => $this->fixe1,
            'fixe2' => $this->fixe2,
            'fax' => $this->fax,
            'notes' => $this->notes,
            'archive' => $this->archive,
            'dossiers' => DossierResource::collection($this->whenLoaded('dossiers')),
            'intervenants_lies' => IntervenantResource::collection($this->whenLoaded('intervenantsLies')),
            'factures' => FactureResource::collection($this->whenLoaded('factures')),
            'agendas' => AgendaResource::collection($this->whenLoaded('agendas')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}