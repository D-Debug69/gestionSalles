<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pays;
use App\Models\User;
use App\Models\Salle;
use App\Models\entreprise;
use App\Models\association;
use App\Models\ReservationSalles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class generalController extends Controller
{
    public function reservGenerale()
    {
        return view('reservGenerale');
    }


    public function storeReservation(Request $request)
    {
    $isEntreprise = $request->has('nomEntreprise');
    $isAssociation = $request->has('nomAssociation');

    if ($isEntreprise) {
        $rules =[
            'nomSalle' => 'nullable|string|max:255',
            'nomEntreprise' => 'required|string|max:255',
            'forme' => 'required|string|in:sarl,sas,sa,ei,eirl,eurl,other',
            'dateCreationE' => 'required|date',
            'pays' => 'required|string',
            'ville' => 'required|string',
            'telephoneE' => 'required|string|max:30',
            'adresseCompleteE' => 'required|string',
            'adressePostaleE' => 'required|string',
            'rccm' => 'required|string',
            'ifu' => 'required|string',
            'autorisationMairieE' => 'required|mimes:pdf|max:5120',
            'documentForceE' => 'required|mimes:pdf|max:5120',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required|in:08:00-12:00,13:30-18:30,08:00-18:30',
            ];
            //validation
            $validator = \Validator::make($request->all(), $rules);

            // Ajout de la règle de conflit
        $validator->after(function ($validator) use ($request) {
            [$start, $end] = explode('-', $request->reservation_time);

            $conflict = ReservationSalles::where('nomSalle', $request->nomSalle)
                ->where('reservation_date', $request->reservation_date)
                ->where('statut', 'confirmed')
                ->where(function($q) use ($start, $end) {
                    $q->whereBetween('start_time', [$start, $end])
                      ->orWhereBetween('end_time', [$start, $end])
                      ->orWhere(function($q2) use ($start, $end) {
                          $q2->where('start_time', '<=', $start)
                             ->where('end_time', '>=', $end);
                      });
                })
                ->exists();
                if ($conflict) {
                $validator->errors()->add('reservation_time', 'Ce créneau est déjà réservé pour cette salle.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();

        $autorisationPath = $request->file('autorisationMairieE')->store('pdfs', 'public');
        $documentPath = $request->file('documentForceE')->store('pdfs', 'public');

        $entreprise = entreprise::create([
            'nomEntreprise' => $validated['nomEntreprise'],
            'typeEntreprise' => $validated['forme'],
            'dateCreationE' => $validated['dateCreationE'],
            'adresseCompleteE' => $validated['adresseCompleteE'],
            'pays' => $validated['pays'],
            'ville' => $validated['ville'],
            'telephoneE' => $validated['telephoneE'],
            'adressePostaleE' => $validated['adressePostaleE'],
            'rccm' => $validated['rccm'],
            'ifu' => $validated['ifu'],
            'autorisationMairieE' => $autorisationPath,
            'documentForceE' => $documentPath,
        ]);

        [$start, $end] = explode('-', $validated['reservation_time']);

        $r = ReservationSalles::create([
            'statut' => 'pending',
            'nomSalle' => $validated['nomSalle'],
            'reservation_date' => $validated['reservation_date'],
            'start_time' => $start,
            'end_time' => $end,
            'nom_demandeur' => $validated['nomEntreprise'],
            'telephone' => $validated['telephoneE'],
            'dateInscription' => now(),
            'user_id' => Auth::id(),
            'entreprise_id' => $entreprise->id,
            'otp' => rand(100000, 999999),
        ]);

    } 
    elseif ($isAssociation) 
        {

        $rules = [
            'nomSalle' => 'nullable|string|max:255',
            'nomAssociation' => 'required|string|max:255',
            'forme' => 'required|string|in:other',
            'dateCreationA' => 'required|date',
            'pays' => 'required|string',
            'ville' => 'required|string',
            'telephoneA' => 'required|string|max:30',
            'adresseCompleteA' => 'required|string',
            'adressePostaleA' => 'required|string',
            'email' => 'required|email',
            'recepisse' => 'required|string',
            'autorisationMairieA' => 'required|mimes:pdf|max:5120',
            'documentForceA' => 'required|mimes:pdf|max:5120',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required|in:08:00-12:00,13:30-18:30,08:00-18:30',
            ];
            //validation
            $validator = \Validator::make($request->all(), $rules);

            // Ajout de la règle de conflit
        $validator->after(function ($validator) use ($request) {
            [$start, $end] = explode('-', $request->reservation_time);

            $conflict = ReservationSalles::where('nomSalle', $request->nomSalle)
                ->where('reservation_date', $request->reservation_date)
                ->where('statut', 'confirmed')
                ->where(function($q) use ($start, $end) {
                    $q->whereBetween('start_time', [$start, $end])
                      ->orWhereBetween('end_time', [$start, $end])
                      ->orWhere(function($q2) use ($start, $end) {
                          $q2->where('start_time', '<=', $start)
                             ->where('end_time', '>=', $end);
                      });
                })
                ->exists();
                if ($conflict) {
                $validator->errors()->add('reservation_time', 'Ce créneau est déjà réservé pour cette salle.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();

        $autorisationPath = $request->file('autorisationMairieA')->store('pdfs', 'public');
        $documentPath = $request->file('documentForceA')->store('pdfs', 'public');

        $association = association::create([
            'nomAssociation' => $validated['nomAssociation'],
            'typeAssociation' => $validated['forme'],
            'dateCreationA' => $validated['dateCreationA'],
            'adresseCompleteA' => $validated['adresseCompleteA'],
            'pays' => $validated['pays'],
            'ville' => $validated['ville'],
            'telephoneA' => $validated['telephoneA'],
            'adressePostaleA' => $validated['adressePostaleA'],
            'email' => $validated['email'],
            'recepisse' => $validated['recepisse'],
            'autorisationMairieA' => $autorisationPath,
            'documentForceA' => $documentPath,
        ]);

        [$start, $end] = explode('-', $validated['reservation_time']);

        $r = ReservationSalles::create([
            'statut' => 'pending',
            'nomSalle' => $validated['nomSalle'],
            'reservation_date' => $validated['reservation_date'],
            'start_time' => $start,
            'end_time' => $end,
            'nom_demandeur' => $validated['nomAssociation'],
            'telephone' => $validated['telephoneA'],
            'email' => $validated['email'],
            'dateInscription' => now(),
            'user_id' => Auth::id(),
            'association_id' => $association->id,
            'otp' => rand(100000, 999999),
        ]);

    } else {
        return redirect()->back()->withErrors(['error' => 'Type de demande non reconnu.']);
    }

    return redirect()->back()->with('success', 'Réservation enregistrée avec succès.')->with('otp', $r->otp);
}

public function myReservationsForm(){
    return view('myReservationsForm');
}

public function searchReservations(Request $request){
    $data = $request->validate([
        'otp' => 'required|digits:6',
    ]);
$reservation = ReservationSalles::with(['entreprise', 'association', 'user', 'salle'])
    ->where('otp', $data['otp'])
    ->first();
    if (!$reservation) {
        return redirect()->back()->withErrors(['otp' => 'Aucune réservation trouvée pour ce code.']);
    }
    return view('myReservationsView', compact('reservation'));

}
    public function reservationJson($id)
{
        $reservation = ReservationSalles::with(['user','salle.ville','approvedCcBy','approvedDfcBy','approvedDgBy','approvedAdminBy'])
                        ->findOrFail($id);

        // Provide approver names to simplify frontend display (may be null)
        $data = $reservation->toArray();
        // Ensure a canonical salle name is present for frontend convenience
        $data['salle_nom'] = $reservation->salle ? ($reservation->salle->nom ?? $reservation->salle->name ?? $reservation->nomSalle) : ($reservation->nomSalle ?? null);
        // Ajouter la ville de la salle
        $data['salle_ville'] = $reservation->salle && $reservation->salle->ville ? $reservation->salle->ville->nom : null;
        $data['approved_cc_by_name'] = $reservation->approvedCcBy ? ($reservation->approvedCcBy->prenom ?? $reservation->approvedCcBy->name ?? $reservation->approvedCcBy->id) : null;
        $data['approved_dfc_by_name'] = $reservation->approvedDfcBy ? ($reservation->approvedDfcBy->prenom ?? $reservation->approvedDfcBy->name ?? $reservation->approvedDfcBy->id) : null;
        $data['approved_dg_by_name'] = $reservation->approvedDgBy ? ($reservation->approvedDgBy->prenom ?? $reservation->approvedDgBy->name ?? $reservation->approvedDgBy->id) : null;
        $data['approved_admin_by_name'] = $reservation->approvedAdminBy ? ($reservation->approvedAdminBy->prenom ?? $reservation->approvedAdminBy->name ?? $reservation->approvedAdminBy->id) : null;

        return response()->json($data);
}

    /**
     * Return JSON info for a salle (basic details used in modal)
     */
    public function salleJson($id)
    {
        $salle = Salle::findOrFail($id);
        return response()->json([
            'id' => $salle->id,
            'nom' => $salle->nom,
            'capacite' => $salle->capacite,
            'prix' => $salle->prix,
            'equipements' => $salle->equipements,
            'image_url' => $salle->getImageUrlAttribute() ?? asset('images/cbc.jpeg'),
            'ville' => $salle->ville ? $salle->ville->nom : null,
        ]);
    }

    /**
     * Return events for FullCalendar for a given salle id
     */
    public function salleCalendar($id)
    {
        $salle = Salle::findOrFail($id);

        $reservations = ReservationSalles::where('nomSalle', $salle->nom)
            ->whereIn('statut', ['confirmed'])
            ->get();

        $events = $reservations->map(function($r) {
            // prefer explicit reservation_date + start_time/end_time
            if (!empty($r->reservation_date) && !empty($r->start_time) && !empty($r->end_time)) {
                $start = Carbon::parse($r->reservation_date->format('Y-m-d') . ' ' . $r->start_time)->toIso8601String();
                $end = Carbon::parse($r->reservation_date->format('Y-m-d') . ' ' . $r->end_time)->toIso8601String();
            } else {
                // fallback to dateEmission
                $start = $r->dateEmission ? Carbon::parse($r->dateEmission)->toIso8601String() : null;
                $end = $start ? Carbon::parse($start)->addHours(2)->toIso8601String() : null;
            }

            return [
                'id' => $r->id,
                'title' => ($r->nom_demandeur ?? $r->nomSalle) . ' — ' . $r->statut,
                'start' => $start,
                'end' => $end,
                'url' => route('reservations.show', $r->id),
            ];
        })->filter()->values();

        return response()->json($events);
    }

    public function approveReservation(Request $request, $id)
{
    $user = Auth::user();
    if (!$user) return response()->json(['error'=>'Non authentifié'], 401);
    $role = strtoupper($user->role); // s'assure majuscules

    $allowedRoles = ['Admin', 'rgs', 'dfc', 'dg', 'cc'];
    $role = null;
    foreach ($allowedRoles as $r) {
        if ($user->hasRole($r)) {
            $role = strtoupper($r);
            break;
        }
    }
    if (!$role) {
        return response()->json(['error'=>'Rôle non autorisé'], 403);
    }

    $reservation = ReservationSalles::findOrFail($id);

    $map = [
      'CC' => ['flag'=>'approved_cc','by'=>'approved_cc_by','at'=>'approved_cc_at'],
      'DFC'=> ['flag'=>'approved_dfc','by'=>'approved_dfc_by','at'=>'approved_dfc_at'],
      'DG'=> ['flag'=>'approved_dg','by'=>'approved_dg_by','at'=>'approved_dg_at'],
      'ADMIN'=> ['flag'=>'approved_admin','by'=>'approved_admin_by','at'=>'approved_admin_at'],
    ];
    $m = $map[$role];
    if ($reservation->{$m['flag']}) {
        return response()->json(['error'=>'Vous avez déjà confirmé'], 422);
    }

    $reservation->{$m['flag']} = true;
    $reservation->{$m['by']} = $user->id;
    $reservation->{$m['at']} = now();
    $reservation->save();

    // Si CC + DFC + DG approuvés le statut est confirmé
$approvedCount= collect([
    $reservation->approved_cc,
    $reservation->approved_dfc,
    $reservation->approved_dg,
    $reservation->approved_admin,
    $reservation->approved_rgs,
])->filter()->count();

    if ($approvedCount >= 3) {
        $reservation->statut = 'confirmed';
        $reservation->dateTraitement = now();
        $reservation->save();
    }

    return response()->json(['success'=>true, 'reservation'=>$reservation->load('approvedCcBy','approvedDfcBy','approvedDgBy','approvedAdminBy')]);
}


    public function deleteReservation($id)
    {
       abort_unless(auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('rgs') || auth()->user()->hasRole('dg')), 403);
        
        $reservation = ReservationSalles::findOrFail($id);
        $reservation->delete();

        return redirect()->route('allReservationsView')->with('success', 'Réservation supprimée !');
    }
    public function adminView()
    {
        $userCount = User::count();
        $salleCount = Salle::count();
       $reservationCount = ReservationSalles::count();
    $pendingRequestsCount = ReservationSalles::where('statut', 'pending')->count();

    return view('adminView', compact('userCount', 'salleCount', 'reservationCount', 'pendingRequestsCount'));
    
    }
    public function allSallesView(){
         $pays = Pays::with('villes.salles')->get();
        return view('allSallesView', ['pays' => $pays]);
    }
    public function allReservationsView(){
       $reservations = ReservationSalles::with(['entreprise', 'association', 'user', 'salle'])
        ->orderBy('created_at','desc')
        ->get();
    return view('allReservationsView', compact('reservations'));
    }
    public function allUsersView(){
        $users = User::all();
        return view('allUsersView', ['users' => $users]);
    }
    public function allEntreprisesView(){
        return view('allEntreprisesView');
    }
    public function allAssociationsView(){
        return view('allAssociationsView');
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // condition d'upload des fichiers
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:2048', // max 2MB
        ]);
        //stockage dans un dossier
        $path = $request->file('pdf')->store('pdfs', 'public');
        return back()->with('success', 'Fichier PDF importé avec succès !');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
