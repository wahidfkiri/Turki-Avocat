<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use App\Models\Dossier;
use App\Models\Intervenant;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class FactureController extends Controller
{
    /**
     * Get factures data for DataTable
     */
    public function getFacturesData(Request $request)
{
    if(!auth()->user()->hasPermission('view_factures')){
        abort(403, 'Unauthorized action.');
    }

    $query = Facture::with([
        'dossier:id,numero_dossier',
        'client:id,identite_fr,identite_ar'
    ])->select('factures.*');

    // Filtre par numéro
    if ($request->has('numero') && !empty($request->numero)) {
        $query->where('numero', 'LIKE', '%' . $request->numero . '%');
    }

    // Filtre par type de pièce
    if ($request->has('type_piece') && !empty($request->type_piece)) {
        $query->where('type_piece', $request->type_piece);
    }

    // Filtre par statut
    if ($request->has('statut') && !empty($request->statut)) {
        $query->where('statut', $request->statut);
    }

    // Filtre par dossier
    if ($request->has('dossier_id') && !empty($request->dossier_id)) {
        $query->where('dossier_id', $request->dossier_id);
    }

    // Filtre par client
    if ($request->has('client_id') && !empty($request->client_id)) {
        $query->where('client_id', $request->client_id);
    }

    // Filtre par date début
    if ($request->has('date_debut') && !empty($request->date_debut)) {
        $query->whereDate('date_emission', '>=', $request->date_debut);
    }

    // Filtre par date fin
    if ($request->has('date_fin') && !empty($request->date_fin)) {
        $query->whereDate('date_emission', '<=', $request->date_fin);
    }

    // Filtre par mois
    if ($request->has('month') && !empty($request->month)) {
        $query->whereMonth('date_emission', $request->month);
    }

    // Filtre par année
    if ($request->has('year') && !empty($request->year)) {
        $query->whereYear('date_emission', $request->year);
    }

    // Filtre par mois et année combinés
    if ($request->has('month') && !empty($request->month) && $request->has('year') && !empty($request->year)) {
        $query->whereYear('date_emission', $request->year)
              ->whereMonth('date_emission', $request->month);
    }

    // Filtre par montant minimum
    if ($request->has('min_montant') && !empty($request->min_montant)) {
        $query->where('montant', '>=', $request->min_montant);
    }

    // Filtre par montant maximum
    if ($request->has('max_montant') && !empty($request->max_montant)) {
        $query->where('montant', '<=', $request->max_montant);
    }

    // Recherche globale DataTables
    if ($request->has('search') && !empty($request->search['value'])) {
        $search = $request->search['value'];
        $query->where(function ($q) use ($search) {
            $q->where('numero', 'LIKE', "%{$search}%")
              ->orWhere('commentaires', 'LIKE', "%{$search}%")
              ->orWhereHas('dossier', function ($q) use ($search) {
                  $q->where('numero_dossier', 'LIKE', "%{$search}%");
              })
              ->orWhereHas('client', function ($q) use ($search) {
                  $q->where('identite_fr', 'LIKE', "%{$search}%")
                    ->orWhere('identite_ar', 'LIKE', "%{$search}%");
              });
        });
    }

    return DataTables::eloquent($query)
        ->addColumn('action', function (Facture $facture) {
            $actions = '<div class="btn-group">';
            
            // Bouton Voir
            if (auth()->user()->hasPermission('view_factures')) {
                $actions .= '<a href="' . route('factures.show', $facture) . '" class="btn btn-info btn-sm" title="Voir">
                    <i class="fas fa-eye"></i>
                </a>';
            }
            
            // Bouton Modifier
            if (auth()->user()->hasPermission('edit_factures')) {
                $actions .= '<a href="' . route('factures.edit', $facture) . '" class="btn btn-primary btn-sm" title="Modifier">
                    <i class="fas fa-edit"></i>
                </a>';
            }
            
            // Bouton Supprimer
            if (auth()->user()->hasPermission('delete_factures')) {
                $actions .= '<button type="button" class="btn btn-danger btn-sm delete-btn" data-id="' . $facture->id . '" title="Supprimer">
                    <i class="fas fa-trash"></i>
                </button>';
            }

            // Bouton PDF
            if (auth()->user()->hasPermission('export_data')) {
                $actions .= '<a href="' . route('factures.pdf', $facture) . '" class="btn btn-secondary btn-sm" title="PDF">
                    <i class="fas fa-file-pdf"></i>
                </a>';
            }
            
            $actions .= '</div>';
            return $actions;
        })
        ->editColumn('date_emission', function (Facture $facture) {
            return $facture->date_emission ? \Carbon\Carbon::parse($facture->date_emission)->format('d/m/Y') : '-';
        })
        ->editColumn('montant_ht', function (Facture $facture) {
            return number_format($facture->montant_ht, 2, ',', ' ') . ' DT';
        })
        ->editColumn('montant_tva', function (Facture $facture) {
            return number_format($facture->montant_tva, 2, ',', ' ') . ' DT';
        })
        ->editColumn('montant', function (Facture $facture) {
            return number_format($facture->montant, 2, ',', ' ') . ' DT';
        })
        ->editColumn('commentaires', function (Facture $facture) {
            return $facture->commentaires ? 
                (strlen($facture->commentaires) > 50 ? 
                 substr($facture->commentaires, 0, 50) . '...' : 
                 $facture->commentaires) : '-';
        })
        ->rawColumns(['action', 'type_piece', 'statut'])
        ->toJson();
}

    
    public function getPaidFacturesData(Request $request)
    {
       if(!auth()->user()->hasPermission('view_factures')){
         abort(403, 'Unauthorized action.');
       }

        $query = Facture::with([
            'dossier:id,numero_dossier',
            'client:id,identite_fr,identite_ar'
        ])->where('statut', 'payé')->select('factures.*');

        // Filtre par numéro
        if ($request->has('numero') && !empty($request->numero)) {
            $query->where('numero', 'LIKE', '%' . $request->numero . '%');
        }

        // Filtre par type de pièce
        if ($request->has('type_piece') && !empty($request->type_piece)) {
            $query->where('type_piece', $request->type_piece);
        }

        // Filtre par statut
        if ($request->has('statut') && !empty($request->statut)) {
            $query->where('statut', $request->statut);
        }

        // Filtre par dossier
        if ($request->has('dossier_id') && !empty($request->dossier_id)) {
            $query->where('dossier_id', $request->dossier_id);
        }

        // Filtre par client
        if ($request->has('client_id') && !empty($request->client_id)) {
            $query->where('client_id', $request->client_id);
        }

        // Filtre par date début
        if ($request->has('date_debut') && !empty($request->date_debut)) {
            $query->whereDate('date_emission', '>=', $request->date_debut);
        }

        // Filtre par date fin
        if ($request->has('date_fin') && !empty($request->date_fin)) {
            $query->whereDate('date_emission', '<=', $request->date_fin);
        }

        // Filtre par montant minimum
        if ($request->has('min_montant') && !empty($request->min_montant)) {
            $query->where('montant', '>=', $request->min_montant);
        }

        // Filtre par montant maximum
        if ($request->has('max_montant') && !empty($request->max_montant)) {
            $query->where('montant', '<=', $request->max_montant);
        }

        // Recherche globale DataTables
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('numero', 'LIKE', "%{$search}%")
                  ->orWhere('commentaires', 'LIKE', "%{$search}%")
                  ->orWhereHas('dossier', function ($q) use ($search) {
                      $q->where('numero_dossier', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('client', function ($q) use ($search) {
                      $q->where('identite_fr', 'LIKE', "%{$search}%")
                        ->orWhere('identite_ar', 'LIKE', "%{$search}%");
                  });
            });
        }

        return DataTables::eloquent($query)
            ->addColumn('action', function (Facture $facture) {
                $actions = '<div class="btn-group">';
                
                // Bouton Voir
                if (auth()->user()->hasPermission('view_factures')) {
                    $actions .= '<a href="' . route('factures.show', $facture) . '" class="btn btn-info btn-sm" title="Voir">
                        <i class="fas fa-eye"></i>
                    </a>';
                }
                
                // Bouton Modifier
                if (auth()->user()->hasPermission('edit_factures')) {
                    $actions .= '<a href="' . route('factures.edit', $facture) . '" class="btn btn-primary btn-sm" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>';
                }
                
                // Bouton Supprimer
                if (auth()->user()->hasPermission('delete_factures')) {
                    $actions .= '<button type="button" class="btn btn-danger btn-sm delete-btn" data-id="' . $facture->id . '" title="Supprimer">
                        <i class="fas fa-trash"></i>
                    </button>';
                }

                // Bouton PDF
                if (auth()->user()->hasPermission('export_data')) {
                    $actions .= '<a href="' . route('factures.pdf', $facture) . '" class="btn btn-secondary btn-sm" title="PDF">
                        <i class="fas fa-file-pdf"></i>
                    </a>';
                }
                
                $actions .= '</div>';
                return $actions;
            })
            ->editColumn('date_emission', function (Facture $facture) {
                return $facture->date_emission ? \Carbon\Carbon::parse($facture->date_emission)->format('d/m/Y') : '-';
            })
            ->editColumn('montant_ht', function (Facture $facture) {
                return number_format($facture->montant_ht, 2, ',', ' ') . ' DT';
            })
            ->editColumn('montant_tva', function (Facture $facture) {
                return number_format($facture->montant_tva, 2, ',', ' ') . ' DT';
            })
            ->editColumn('montant', function (Facture $facture) {
                return number_format($facture->montant, 2, ',', ' ') . ' DT';
            })
            ->editColumn('commentaires', function (Facture $facture) {
                return $facture->commentaires ? 
                    (strlen($facture->commentaires) > 50 ? 
                     substr($facture->commentaires, 0, 50) . '...' : 
                     $facture->commentaires) : '-';
            })
            ->rawColumns(['action', 'type_piece', 'statut'])
            ->toJson();
    }

    
    public function getUnpaidFacturesData(Request $request)
    {
       if(!auth()->user()->hasPermission('view_factures')){
         abort(403, 'Unauthorized action.');
       }

        $query = Facture::with([
            'dossier:id,numero_dossier',
            'client:id,identite_fr,identite_ar'
        ])->where('statut', 'non_payé')->select('factures.*');

        // Filtre par numéro
        if ($request->has('numero') && !empty($request->numero)) {
            $query->where('numero', 'LIKE', '%' . $request->numero . '%');
        }

        // Filtre par type de pièce
        if ($request->has('type_piece') && !empty($request->type_piece)) {
            $query->where('type_piece', $request->type_piece);
        }

        // Filtre par statut
        if ($request->has('statut') && !empty($request->statut)) {
            $query->where('statut', $request->statut);
        }

        // Filtre par dossier
        if ($request->has('dossier_id') && !empty($request->dossier_id)) {
            $query->where('dossier_id', $request->dossier_id);
        }

        // Filtre par client
        if ($request->has('client_id') && !empty($request->client_id)) {
            $query->where('client_id', $request->client_id);
        }

        // Filtre par date début
        if ($request->has('date_debut') && !empty($request->date_debut)) {
            $query->whereDate('date_emission', '>=', $request->date_debut);
        }

        // Filtre par date fin
        if ($request->has('date_fin') && !empty($request->date_fin)) {
            $query->whereDate('date_emission', '<=', $request->date_fin);
        }

        // Filtre par montant minimum
        if ($request->has('min_montant') && !empty($request->min_montant)) {
            $query->where('montant', '>=', $request->min_montant);
        }

        // Filtre par montant maximum
        if ($request->has('max_montant') && !empty($request->max_montant)) {
            $query->where('montant', '<=', $request->max_montant);
        }

        // Recherche globale DataTables
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('numero', 'LIKE', "%{$search}%")
                  ->orWhere('commentaires', 'LIKE', "%{$search}%")
                  ->orWhereHas('dossier', function ($q) use ($search) {
                      $q->where('numero_dossier', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('client', function ($q) use ($search) {
                      $q->where('identite_fr', 'LIKE', "%{$search}%")
                        ->orWhere('identite_ar', 'LIKE', "%{$search}%");
                  });
            });
        }

        return DataTables::eloquent($query)
            ->addColumn('action', function (Facture $facture) {
                $actions = '<div class="btn-group">';
                
                // Bouton Voir
                if (auth()->user()->hasPermission('view_factures')) {
                    $actions .= '<a href="' . route('factures.show', $facture) . '" class="btn btn-info btn-sm" title="Voir">
                        <i class="fas fa-eye"></i>
                    </a>';
                }
                
                // Bouton Modifier
                if (auth()->user()->hasPermission('edit_factures')) {
                    $actions .= '<a href="' . route('factures.edit', $facture) . '" class="btn btn-primary btn-sm" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>';
                }
                
                // Bouton Supprimer
                if (auth()->user()->hasPermission('delete_factures')) {
                    $actions .= '<button type="button" class="btn btn-danger btn-sm delete-btn" data-id="' . $facture->id . '" title="Supprimer">
                        <i class="fas fa-trash"></i>
                    </button>';
                }

                // Bouton PDF
                if (auth()->user()->hasPermission('export_data')) {
                    $actions .= '<a href="' . route('factures.pdf', $facture) . '" class="btn btn-secondary btn-sm" title="PDF">
                        <i class="fas fa-file-pdf"></i>
                    </a>';
                }
                
                $actions .= '</div>';
                return $actions;
            })
            ->editColumn('date_emission', function (Facture $facture) {
                return $facture->date_emission ? \Carbon\Carbon::parse($facture->date_emission)->format('d/m/Y') : '-';
            })
            ->editColumn('montant_ht', function (Facture $facture) {
                return number_format($facture->montant_ht, 2, ',', ' ') . ' DT';
            })
            ->editColumn('montant_tva', function (Facture $facture) {
                return number_format($facture->montant_tva, 2, ',', ' ') . ' DT';
            })
            ->editColumn('montant', function (Facture $facture) {
                return number_format($facture->montant, 2, ',', ' ') . ' DT';
            })
            ->editColumn('commentaires', function (Facture $facture) {
                return $facture->commentaires ? 
                    (strlen($facture->commentaires) > 50 ? 
                     substr($facture->commentaires, 0, 50) . '...' : 
                     $facture->commentaires) : '-';
            })
            ->rawColumns(['action', 'type_piece', 'statut'])
            ->toJson();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       if(!auth()->user()->hasPermission('view_factures')){
         abort(403, 'Unauthorized action.');
       }
        if(auth()->user()->hasRole('admin')){
        $dossiers = Dossier::all();
        }else{
        $dossiers = Dossier::with(['domaine', 'sousDomaine', 'users', 'intervenants'])
    ->whereHas('users', function($query) {
        $query->where('users.id', auth()->id());
    })
    ->where('archive', false)
    ->get();
        }
        $clients = Intervenant::where('categorie', 'client')->get();
        
        return view('factures.index', compact('dossiers', 'clients'));
    }
    
    public function indexPaid()
    {
       if(!auth()->user()->hasPermission('view_factures')){
         abort(403, 'Unauthorized action.');
       }
        $dossiers = Dossier::all();
        $clients = Intervenant::where('categorie', 'client')->get();
        
        return view('factures.order.paid', compact('dossiers', 'clients'));
    }
    
    public function indexUnpaid()
    {
       if(!auth()->user()->hasPermission('view_factures')){
         abort(403, 'Unauthorized action.');
       }
        
        $dossiers = Dossier::all();
        $clients = Intervenant::where('categorie', 'client')->get();
        
        return view('factures.order.unpaid', compact('dossiers', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       if(!auth()->user()->hasPermission('create_factures')){
         abort(403, 'Unauthorized action.');
       }
        
        $dossiers = Dossier::with('intervenants')->get();
        $clients = Intervenant::where('categorie', 'client')->get();
        
        // Générer le prochain numéro de facture
        $lastFacture = Facture::orderBy('id', 'desc')->first();
        $nextNumber = 'FACT-' . date('Y') . '-' . str_pad(($lastFacture ? $lastFacture->id + 1 : 1), 4, '0', STR_PAD_LEFT);
        
        return view('factures.create', compact('dossiers', 'clients', 'nextNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!auth()->user()->hasPermission('create_factures')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $validated = $request->validate([
            'dossier_id' => 'nullable|exists:dossiers,id',
            'client_id' => 'nullable|exists:intervenants,id',
            'type_piece' => 'required|in:facture,note_frais,note_provision,avoir',
            'numero' => 'required|string|max:100|unique:factures,numero',
            'date_emission' => 'required|date',
            'montant_ht' => 'required|numeric|min:0',
            'montant_tva' => 'required|numeric|min:0',
            'montant' => 'required|numeric|min:0',
            'statut' => 'required|in:payé,non_payé',
            'commentaires' => 'nullable|string',
            'piece_jointe' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240', 
        ]);

        // Vérifier la cohérence des montants
        $calculatedMontant = $validated['montant_ht'] + $validated['montant_tva'];
        if (abs($calculatedMontant - $validated['montant']) > 0.01) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Le montant TTC doit être égal à HT + TVA.');
        }

        // Gestion de la pièce jointe
    if ($request->hasFile('piece_jointe')) {
        $file = $request->file('piece_jointe');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('factures', $fileName, 'public');
        $validated['piece_jointe'] = $fileName;
    }

        $facture = Facture::create($validated);

        if($request->hasFile('piece_jointe')) {
        $facture->file_name = $file->getClientOriginalName();
        $facture->save();
        }

        return redirect()->route('factures.index')
            ->with('success', 'Facture créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Facture $facture)
    {
       if(!auth()->user()->hasPermission('view_factures')){
         abort(403, 'Unauthorized action.');
       }
        
        return view('factures.show', compact('facture'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facture $facture)
    {
       if(!auth()->user()->hasPermission('edit_factures')){
         abort(403, 'Unauthorized action.');
       }
        
        $dossiers = Dossier::with('intervenants')->get();
        $clients = Intervenant::where('categorie', 'client')->get();
        
        return view('factures.edit', compact('facture', 'dossiers', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Facture $facture)
    {
       if(!auth()->user()->hasPermission('edit_factures')){
         abort(403, 'Unauthorized action.');
       }
        
        $validated = $request->validate([
            'dossier_id' => 'nullable|exists:dossiers,id',
            'client_id' => 'nullable|exists:intervenants,id',
            'type_piece' => 'required|in:facture,note_frais,note_provision,avoir',
            'numero' => 'required|string|max:100|unique:factures,numero,' . $facture->id,
            'date_emission' => 'required|date',
            'montant_ht' => 'required|numeric|min:0',
            'montant_tva' => 'required|numeric|min:0',
            'montant' => 'required|numeric|min:0',
            'statut' => 'required|in:payé,non_payé',
            'commentaires' => 'nullable|string',
            'piece_jointe' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240', 
        ]);

        // Vérifier la cohérence des montants
        $calculatedMontant = $validated['montant_ht'] + $validated['montant_tva'];
        if (abs($calculatedMontant - $validated['montant']) > 0.01) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Le montant TTC doit être égal à HT + TVA.');
        }
        // Gestion de la pièce jointe
    if ($request->hasFile('piece_jointe')) {
        // Supprimer l'ancien fichier s'il existe
        if ($facture->piece_jointe) {
            Storage::disk('public')->delete('factures/' . $facture->piece_jointe);
        }
        
        $file = $request->file('piece_jointe');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('factures', $fileName, 'public');
        $validated['piece_jointe'] = $fileName;
    }

        $facture->update($validated);

        return redirect()->route('factures.index')
            ->with('success', 'Facture mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facture $facture)
    {
       if(!auth()->user()->hasPermission('delete_factures')){
         abort(403, 'Unauthorized action.');
       }
        
        $facture->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Facture supprimée avec succès.'
            ]);
        }

        return redirect()->route('factures.index')
            ->with('success', 'Facture supprimée avec succès.');
    }

    /**
     * Generate PDF for facture
     */
    public function downloadPDF(Facture $facture)
    {
    
    // Vérifier si la facture a un fichier PDF
    if (!$facture->piece_jointe) {
        return redirect()->back()->with('error', 'Aucun fichier PDF trouvé pour cette facture.');
    }
    
    // Chemin complet du fichier
    $filePath = storage_path('app/public/factures/' . $facture->piece_jointe);
    
    // Vérifier si le fichier existe
    if (!file_exists($filePath)) {
        return redirect()->back()->with('error', 'Le fichier PDF est introuvable.');
    }
    
    // Télécharger le fichier
    return response()->download($filePath, 'facture-' . $facture->numero . '.pdf', [
        'Content-Type' => 'application/pdf',
    ]);
}

    public function downloadFile($id)
    {
        if (!auth()->user()->hasPermission('view_factures')) {
            abort(403, 'Unauthorized action.');
        }

        $facture = Facture::find($id);

        if (!$facture || !$facture->piece_jointe) {
            return redirect()->back()->with('error', 'Fichier introuvable pour cette facture.');
        }

        $filePath = storage_path('app/public/factures/' . $facture->piece_jointe);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Le fichier est introuvable sur le serveur.');
        }

        $downloadName = $facture->file_name ?? $facture->piece_jointe;
        $mime = @mime_content_type($filePath) ?: 'application/octet-stream';

        return response()->download($filePath, $downloadName, ['Content-Type' => $mime]);
    }

    public function displayFile($id)
    {
        if (!auth()->user()->hasPermission('view_factures')) {
            abort(403, 'Unauthorized action.');
        }

        $facture = Facture::find($id);

        if (!$facture || !$facture->piece_jointe) {
            return redirect()->back()->with('error', 'Fichier introuvable pour cette facture.');
        }

        $filePath = storage_path('app/public/factures/' . $facture->piece_jointe);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Le fichier est introuvable sur le serveur.');
        }

        $mime = @mime_content_type($filePath) ?: 'application/octet-stream';
        $inlineTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/jpg'
        ];

        $headers = ['Content-Type' => $mime];
        $displayName = $facture->file_name ?? $facture->piece_jointe;

        if (in_array($mime, $inlineTypes, true)) {
            // Afficher directement dans le navigateur
            return response()->file($filePath, $headers);
        }

        // Pour les autres types, forcer le téléchargement
        return response()->download($filePath, $displayName, $headers);
    }
}