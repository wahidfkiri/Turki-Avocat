<?php

namespace App\Http\Controllers;

use App\Models\Domaine;
use App\Models\SousDomaine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DomaineController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:domaines'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $domaine = Domaine::create([
            'nom' => $request->nom
        ]);

        return response()->json([
            'id' => $domaine->id,
            'nom' => $domaine->nom
        ]);
    }

    public function storeSubdomaine(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domaine_id' => 'required|exists:domaines,id',
            'nom' => 'required|string|max:255|unique:sous_domaines,nom,NULL,id,domaine_id,' . $request->domaine_id
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $sousDomaine = SousDomaine::create([
            'domaine_id' => $request->domaine_id,
            'nom' => $request->nom
        ]);

        return response()->json([
            'id' => $sousDomaine->id,
            'nom' => $sousDomaine->nom,
            'domaine_id' => $sousDomaine->domaine_id
        ]);
    }

    public function getByDomaine(Request $request)
    {
        $sousDomaines = SousDomaine::where('domaine_id', $request->domaine_id)
            ->orderBy('nom')
            ->pluck('nom', 'id');

        return response()->json($sousDomaines);
    }
}