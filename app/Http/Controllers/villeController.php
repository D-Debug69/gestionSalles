<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use App\Models\Ville;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class villeController extends Controller
{
    public function create()
    {
        $pays = Pays::all();
        return view('addVille', ['pays' => $pays]);
    }

    public function store(Request $request)
    {
        // Valider la ville
        $request->validate([
            'nom' => 'required|string|max:255',
            'pays_id' => 'required|exists:pays,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            'salles' => 'required|array|min:1',
            'salles.*.nom' => 'required|string|max:255',
            'salles.*.capacite' => 'nullable|integer|min:1',
            'salles.*.prix' => 'nullable|numeric|min:0',
            'salles.*.equipements' => 'nullable|string|max:255',
            'salles.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload de l'image ville
        $villeImagePath = null;
    if ($request->hasFile('image')) {
        $villeImagePath = $request->file('image')->store('images/villes', 'public');
    }

        // Créer la ville
        $ville = Ville::create([
            'nom' => $request->nom,
            'pays_id' => $request->pays_id,
            'image' => $villeImagePath,
        ]);
         // Créer les salles associées
        foreach ($request->salles as $salleData) {
            $salleImagePath = null;
           foreach ($request->salles as $index => $salleData) {
            $salleImagePath = null;
                if ($request->hasFile("salles.$index.image")) {
                    $salleImagePath = $request->file("salles.$index.image")->store('images/salles', 'public');
                                                                }
            
                Salle::create([
                    'nom' => $salleData['nom'],
                    'capacite' => $salleData['capacite'] ?? null,
                    'equipements' => $salleData['equipements'] ?? null,
                    'prix' => $salleData['prix'] ?? null,
                    'ville_id' => $ville->id,
                    'statut' => 'indisponible', // ou une valeur par défaut
                    'image' => $salleImagePath,
                ]);
            
        }

        return redirect()->route('allSallesView')->with('success', 'Ville et salles créées avec succès !');
    }
    
    }

    public function edit($id) {
    $ville = Ville::findOrFail($id);
    $pays = Pays::all();
    $salle = Salle::findOrFail($id);
    return view('editVille', ['ville' => $ville, 'pays' => $pays]);
                                }

    public function update(Request $request, $id)
{
    $ville = Ville::findOrFail($id);
    $request->validate([
        'nom' => 'required|string|max:255',
        'pays_id' => 'required|exists:pays,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $imagePath = $ville->image;
    if ($request->hasFile('image')) {
        Storage::disk('public')->delete($ville->image);
        $imagePath = $request->file('image')->store('images/villes', 'public');
    }

    $ville->update([
        'nom' => $request->nom,
        'pays_id' => $request->pays_id,
        'image' => $imagePath,
    ]);

    return redirect()->route('allSallesView')->with('success', 'Ville modifiée !');
    }

    public function destroy($id)
    {
        abort_unless(auth()->check() && in_array(auth()->user()->role, ['admin', 'rgs', 'dg']), 403);
        
        $ville = Ville::findOrFail($id);
        $ville->delete(); // Les salles seront supprimées grâce à cascade

        return redirect()->route('allSallesView')->with('success', 'Ville supprimée !');
    }
}
