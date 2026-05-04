<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ville;
use Illuminate\Support\Facades\Hash;


class logs extends Controller
{
 public function loginAdmin(Request $request)
    {   
    $credentials = $request->validate(['email'=>'required|email','password'=>'required']);

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/adminView');
    }
    return back()->withErrors(['email' => 'Identifiants incorrects'])->withInput();
}

public function showloginAdmin(){
    return view('loginAdmin');
}

public function register(Request $request)
{
 $validated = $request->validate([
        'name' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'ville' => 'required|string|max:255',
        'telephone' => 'required|string|max:20',
        'role' => 'required|in:admin,dfc,rgs,dg,cc,user',
        'password' => 'required|confirmed|min:4',
    ]);

    $validated['password'] = Hash::make($validated['password']);
    
    User::create($validated);

    return redirect()->route('allUsersView')->with('success', 'Utilisateur créé avec succès!');

}

public function showregister(){
    $villes = ville::all();
    return view('register', ['villes' => $villes]);
}

public function destroy($id)
{
    $user = User::findOrFail($id);

    if (auth()->user()->role !== 'admin') {
        abort(403, 'Accès interdit');
    }

    if ($user->id === auth()->id()) {
        return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
    }

    $user->delete();

    return redirect()->route('allUsersView')->with('success', 'Utilisateur supprimé avec succès.');
}

public function logout(){
    Auth::logout();
    return redirect('/');
}

}