<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pays;

class paysController extends Controller
{
     public function create()
    {
        return view('addPays');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:pays,nom',
        ]);

        Pays::create([
            'nom' => $request->nom,
        ]);

        return redirect()->route('allSallesView')->with('success', 'Pays ajouté avec succès !');
    }
    public function destroy($id)
{
    abort_unless(auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('rgs') || auth()->user()->hasRole('dg')), 403);
    
    $pays = Pays::findOrFail($id);
    $pays->delete();

    return redirect()->route('allSallesView')->with('success', 'Pays supprimé !');
}
}
