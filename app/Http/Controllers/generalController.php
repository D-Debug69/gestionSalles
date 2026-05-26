<?php

namespace App\Http\Controllers;

use Gate;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;
use App\Models\Pays;
use App\Models\User;
use App\Models\Salle;
use App\Models\entreprise;
use App\Models\association;
use App\Models\ReservationSalles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class generalController extends Controller
{
    public function reservGenerale()
    {
        return view('reservGenerale');
    }

    
public function downloadPdf(ReservationSalles $reservation)
{
    if ($reservation->statut !== 'confirmed') {
        abort(403, 'Seules les réservations confirmées peuvent être téléchargées');
    }

    $clientNom = $reservation->entreprise?->nomEntreprise
        ?? $reservation->association?->nomAssociation
        ?? $reservation->nom_demandeur
        ?? 'N/C';

    $clientType = $reservation->entreprise ? 'Entreprise'
        : ($reservation->association ? 'Association' : 'Particulier');

    $clientAdresse = $reservation->entreprise?->adresseCompleteE
        ?? $reservation->association?->adresseCompleteA
        ?? 'N/C';

    $clientVille = $reservation->entreprise?->ville
        ?? $reservation->association?->ville
        ?? $reservation->salle?->ville?->nom
        ?? 'N/C';

    $clientTelephone = $reservation->telephone;
    $clientEmail = $reservation->email;
    $generatedAt = now();
    $prix = $reservation->salle?->prix ?? 0;
    $bloc = $reservation->start_time && $reservation->end_time
        ? ($reservation->start_time >= '07:00:00' && $reservation->end_time <= '14:00:00' ? 'matin' : 'après-midi')
        : 'N/C';

    $pdf = Pdf::loadView('pdf', compact(
        'reservation',
        'clientNom',
        'clientType',
        'clientAdresse',
        'clientVille',
        'clientTelephone',
        'clientEmail',
        'generatedAt',
        'prix',
        'bloc'
    ));

    return $pdf->download('reservation_' . $reservation->id . '.pdf');
}


public function downloadReceipt(ReservationSalles $reservation)
{
    if ($reservation->statut !== 'confirmed') {
        abort(403, 'Seules les réservations confirmées peuvent télécharger un reçu.');
    }

    $generatedAt = now();

    $pdf = Pdf::loadView('recu', [
        'reservation' => $reservation,
        'generatedAt' => $generatedAt,
    ]);

    return $pdf->download('recu_reservation_' . $reservation->id . '.pdf');
}

    public function cancelReservation($id)
    {
    $reservation = ReservationSalles::findOrFail($id);

    // Si tu veux empêcher d'annuler les réservations déjà annulées
    if ($reservation->statut === 'canceled') {
        return redirect()->back()->with('warning', 'Cette réservation est déjà annulée.');
    }

    $reservation->statut = 'canceled';
    $reservation->save();

    return redirect()->back()->with('success', 'La réservation a bien été annulée.');
    }
    public function accueil()
    {
        return view('acceuil');
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
            'reservation_time' => 'required|in:07:00-14:00,14:00-21:00,07:00-21:00',
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

        // trouver la salle demandée
$salle = Salle::where('nom', $validated['nomSalle'])->first();

// déterminer le prix selon le créneau (utilise les nouveaux champs prix_matin/prix_apres_midi/prix_journee)
$prixReservation = 0;
if ($salle) {
    $prixReservation = match($validated['reservation_time']) {
        '07:00-14:00' => $salle->prix_matin ?? $salle->prix,
        '14:00-21:00' => $salle->prix_apres_midi ?? $salle->prix,
        '07:00-21:00' => $salle->prix_journee ?? $salle->prix,
        default => $salle->prix ?? 0,
    };
}

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
            'prix' => $prixReservation,
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
            'reservation_time' => 'required|in:07:00-14:00,14:00-21:00,07:00-21:00',
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

        // trouver la salle demandée
$salle = Salle::where('nom', $validated['nomSalle'])->first();

// déterminer le prix selon le créneau (utilise les nouveaux champs prix_matin/prix_apres_midi/prix_journee)
$prixReservation = 0;
if ($salle) {
    $prixReservation = match($validated['reservation_time']) {
        '07:00-14:00' => $salle->prix_matin ?? $salle->prix,
        '14:00-21:00' => $salle->prix_apres_midi ?? $salle->prix,
        '07:00-21:00' => $salle->prix_journee ?? $salle->prix,
        default => $salle->prix ?? 0,
    };
}

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
            'prix' => $prixReservation,
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
        $reservation = ReservationSalles::with(['user','salle.ville','entreprise','association','approvedCcBy','approvedDfcBy','approvedDgBy','approvedAdminBy'])
                        ->findOrFail($id);

        // Provide approver names to simplify frontend display (may be null)
        $data = $reservation->toArray();
        //gestion des pdf
$data['entreprise_autorisation'] = $reservation->entreprise?->autorisationMairieE;
$data['entreprise_document_force'] = $reservation->entreprise?->documentForceE;
$data['association_autorisation'] = $reservation->association?->autorisationMairieA;
$data['association_document_force'] = $reservation->association?->documentForceA;
        //
$data['entreprise_autorisation_url'] = $data['entreprise_autorisation'] ? Storage::url($data['entreprise_autorisation']) : null;
$data['entreprise_document_force_url'] = $data['entreprise_document_force'] ? Storage::url($data['entreprise_document_force']) : null;
$data['association_autorisation_url'] = $data['association_autorisation'] ? Storage::url($data['association_autorisation']) : null;
$data['association_document_force_url'] = $data['association_document_force'] ? Storage::url($data['association_document_force']) : null;
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
            ];
        })->filter()->values();

        return response()->json($events);
    }

    public function approveReservation(Request $request, $id)
{
    $user = Auth::user();
    if (!$user) return response()->json(['error'=>'Non authentifié'], 401);
    $role = strtoupper($user->role); // s'assure majuscules

    $role = strtoupper($user->role); // clé dérivée du rôle détenu
    if (! isset($map[$role]) && ! PermissionHelper::hasPermission($user, 'accept reservation')) {
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

    // Si 3 approuvés le statut est confirmé
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


    public function refuseReservation(Request $request, $id)
{
    $user = Auth::user();
    if (!$user) return response()->json(['error'=>'Non authentifié'], 401);

    if (Gate::denies('refuse reservation')) {
        return response()->json(['error'=>'Rôle non autorisé'], 403);
    }
    $role = strtoupper($user->role); // s'assure majuscules

    $reservation = ReservationSalles::findOrFail($id);

    // Seul DFC remplit la raison et change le statut
    if ($role === 'DFC') {
        $validated = $request->validate([
            'motifRejet' => 'required|string|max:500',
        ]);
        $reservation->motifRejet = $validated['motifRejet'];
        $reservation->statut = 'rejected';
        $reservation->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Réservation refusée',
            'reservation' => $reservation
        ]);
    }

    // Les autres rôles refusent sans changer le statut
    return response()->json([
        'success' => true,
        'message' => 'Refus enregistré',
        'reservation' => $reservation
    ]);
    }
    public function deleteReservation($id)
    {
       abort_unless(auth()->check() && (auth()->user()->hasRole('admin') ||auth()->user()->hasRole('Admin')|| auth()->user()->hasRole('rgs') || auth()->user()->hasRole('dg')), 403);
        
        $reservation = ReservationSalles::findOrFail($id);
        $reservation->delete();

        return redirect()->route('allReservationsView')->with('success', 'Réservation supprimée !');
    }
    public function adminView()
    {
        $userCount = User::count();

        //salles en fonction de la ville de l'user
        $salleQuery = Salle::query()->with('ville');
        if (auth()->check() && !auth()->user()->hasRole('Admin')) {
            $userCity = auth()->user()->ville;
        if ($userCity) {
            $salleQuery->whereHas('ville', function ($q) use ($userCity) {
            $q->where('nom', $userCity);
            });
                        }
        }
        $salleCount = $salleQuery->count();

       //reservations en fonction de la ville de l'user
        $reservationQuery = ReservationSalles::query()->with('salle.ville');
            if (auth()->check() && !auth()->user()->hasRole('Admin')) {
                $userCity = auth()->user()->ville;
            if ($userCity) {
                $reservationQuery->whereHas('salle.ville', function ($q) use ($userCity) {
                $q->where('nom', $userCity);
            });
                }
            }
        $reservationCount = $reservationQuery->count();

        //reservations en attente de la ville de l'user
        $pendingQuery = ReservationSalles::query()->with('salle.ville')->where('statut', 'pending');

            if (auth()->check() && !auth()->user()->hasRole('Admin')) {
                $userCity = auth()->user()->ville;
            if ($userCity) {
                $pendingQuery->whereHas('salle.ville', function ($q) use ($userCity) {
                    $q->where('nom', $userCity);
                });
                }
        }
                $pendingRequestsCount = $pendingQuery->count();

                $recentReservations = $reservationQuery->orderBy('created_at', 'desc')->take(5)->get();

    return view('adminView', compact('userCount', 'salleCount', 'reservationCount', 'pendingRequestsCount','recentReservations'));
    
    }
    public function allSallesView(){
          $query = Pays::with('villes.salles');

                if (auth()->check() && !auth()->user()->hasRole('Admin')) {
                    $userCity = auth()->user()->ville;
        
                if ($userCity) {
                    $query->whereHas('villes', function ($q) use ($userCity) {
                        $q->where('nom', $userCity);
                        });
                    }
                    }

                $pays = $query->get();
                return view('allSallesView', ['pays' => $pays]);

    }
    public function allReservationsView(Request $request)
    {
        $query = ReservationSalles::with(['entreprise', 'association', 'user', 'salle.ville']);

    if (auth()->check() && !auth()->user()->hasRole('Admin')) {
        $userCity = auth()->user()->ville;
        if ($userCity) {
            $query->whereHas('salle.ville', function ($q) use ($userCity) {
                $q->where('nom', $userCity);
            });
        }
    }

    if ($request->query('archived') === '1') {
        $query->where('statut', 'canceled');
        $archived = true;
    } else {
        $query->where('statut', '!=', 'canceled');
        $archived = false;
    }

    $reservations = $query->orderBy('created_at','desc')->get();
    return view('allReservationsView', compact('reservations', 'archived'));
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
