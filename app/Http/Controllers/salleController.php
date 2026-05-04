<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use App\Models\Ville;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class salleController extends Controller
{
    
public function edit($id) {
    $salle = Salle::findOrFail($id);
    $villes = Ville::all();
    return view('editSalle', ['salle' => $salle, 'villes' => $villes]);
}
public function update(Request $request, $id) {
    $salle = Salle::findOrFail($id);
    $request->validate(['nom' => 'required', 'ville_id' => 'required|exists:villes,id', 'capacite' => 'nullable|integer', 'prix' => 'nullable|numeric', 'image' => 'nullable|image|max:2048']);
    $imagePath = $salle->image;
    if ($request->hasFile('image')) {
        Storage::disk('public')->delete($salle->image);
        $imagePath = $request->file('image')->store('images/salles', 'public');
    }
    $salle->update($request->only(['nom', 'ville_id', 'capacite', 'prix', 'image']) + ['image' => $imagePath]);
    return redirect()->route('allSallesView')->with('success', 'Salle modifiée');
}
public function destroy($id) {
    $salle = Salle::findOrFail($id);
    Storage::disk('public')->delete($salle->image);
    $salle->delete();
    return redirect()->route('allSallesView')->with('success', 'Salle supprimée');
}
}
