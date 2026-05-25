<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Réservation #{{ $reservation->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { background: #0d6efd; color: white; padding: 15px; }
        .section { margin: 20px 0; }
        .row { display: flex; gap: 30px; }
        .col { flex: 1; }
        strong { color: #0d6efd; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Réservation #{{ $reservation->id }}</h1>
    </div>
    
    <div class="section">
        <h3>Informations générales</h3>
        <p><strong>Salle :</strong> {{ $reservation->nomSalle }}</p>
        <p><strong>Date :</strong> {{ $reservation->reservation_date?->format('d/m/Y') }}</p>
        <p><strong>Heure :</strong> {{ $reservation->start_time }} - {{ $reservation->end_time }}</p>
        <p><strong>Code OTP :</strong> {{ $reservation->otp }}</p>
        <!-- Ajouter d'autres champs selon vos besoins -->
    </div>
    <div class="card shadow-sm mt-4">
        <div class="card-body">
          <h5 class="card-title">Approuvements</h5>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              CC : {{ $reservation->approved_cc ? 'Validé' : 'Non validé' }}
              @if($reservation->approvedCcBy) - par {{ $reservation->approvedCcBy->name ?? $reservation->approvedCcBy->prenom ?? $reservation->approvedCcBy->id }} @endif
            </li>
            <li class="list-group-item">
              DFC : {{ $reservation->approved_dfc ? 'Validé' : 'Non validé' }}
              @if($reservation->approvedDfcBy) - par {{ $reservation->approvedDfcBy->name ?? $reservation->approvedDfcBy->prenom ?? $reservation->approvedDfcBy->id }} @endif
            </li>
            <li class="list-group-item">
              DG : {{ $reservation->approved_dg ? 'Validé' : 'Non validé' }}
              @if($reservation->approvedDgBy) - par {{ $reservation->approvedDgBy->name ?? $reservation->approvedDgBy->prenom ?? $reservation->approvedDgBy->id }} @endif
            </li>
            <li class="list-group-item">
              Admin : {{ $reservation->approved_admin ? 'Validé' : 'Non validé' }}
              @if($reservation->approvedAdminBy) - par {{ $reservation->approvedAdminBy->name ?? $reservation->approvedAdminBy->prenom ?? $reservation->approvedAdminBy->id }} @endif
            </li>
          </ul>
        </div>
      </div>

</body>
</html>