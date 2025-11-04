<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDomaineRequest;
use App\Http\Requests\UpdateDomaineRequest;
use App\Http\Resources\DomaineResource;
use App\Models\Domaine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DomaineController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $domaines = Domaine::with('sousDomaines')->get();
        return DomaineResource::collection($domaines);
    }

    public function store(StoreDomaineRequest $request): DomaineResource
    {
        $domaine = Domaine::create($request->validated());
        
        return new DomaineResource($domaine->load('sousDomaines'));
    }

    public function show(Domaine $domaine): DomaineResource
    {
        return new DomaineResource($domaine->load('sousDomaines', 'dossiers'));
    }

    public function update(UpdateDomaineRequest $request, Domaine $domaine): DomaineResource
    {
        $domaine->update($request->validated());
        
        return new DomaineResource($domaine->load('sousDomaines'));
    }

    public function destroy(Domaine $domaine): JsonResponse
    {
        // Vérifier si le domaine est utilisé dans des dossiers
        if ($domaine->dossiers()->count() > 0) {
            return response()->json([
                'message' => 'Impossible de supprimer ce domaine car il est associé à des dossiers.'
            ], 422);
        }
        
        $domaine->delete();
        
        return response()->json([
            'message' => 'Domaine supprimé avec succès.'
        ], 200);
    }
}