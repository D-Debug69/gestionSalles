<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu de réservation - {{ $reservation->id }}</title>
    <style>
        * { margin: 0; padding: 0; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
            background: #fff;
        }
        .page {
            max-width: 900px;
            margin: 0 auto;
            padding: 30px;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid #000;
            margin-bottom: 25px;
            padding-bottom: 20px;
        }
        .logo img {
            max-height: 70px;
            width: auto;
        }
        .header-text {
            text-align: right;
        }
        .header-text h1 {
            font-size: 18pt;
            margin-bottom: 6px;
        }
        .header-text p {
            font-size: 10pt;
            color: #555;
            margin: 4px 0;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .info-box, .details-box {
            border: 1px solid #ccc;
            padding: 12px 14px;
            margin-bottom: 14px;
            background: #f8f8f8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px 8px;
            border: 1px solid #ddd;
            font-size: 10pt;
        }
        th {
            background: #f1f1f1;
            text-align: left;
            width: 32%;
        }
        .status-confirmed {
            color: #198754;
            font-weight: bold;
        }
        .status-other {
            color: #333;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ccc;
            padding-top: 12px;
            font-size: 9pt;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
@php
    $clientNom = $reservation->entreprise?->nomEntreprise
        ?? $reservation->association?->nomAssociation
        ?? $reservation->nom_demandeur
        ?? 'N/C';

    $clientType = $reservation->entreprise ? 'Entreprise'
        : ($reservation->association ? 'Association' : 'Particulier');

    $clientAdresse = $reservation->entreprise?->adresseCompleteE
        ?? $reservation->association?->adresseCompleteA
        ?? ($reservation->details ?? 'N/C');

    $clientVille = $reservation->entreprise?->ville
        ?? $reservation->association?->ville
        ?? $reservation->salle?->ville?->nom;

    $clientPays = $reservation->entreprise?->pays
        ?? $reservation->association?->pays
        ?? $reservation->salle?->ville?->pays?->nom;

    $salleNom = $reservation->salle?->nom ?? $reservation->nomSalle ?? 'N/C';
    $salleVille = $reservation->salle?->ville?->nom ?? 'N/C';
    $sallePays = $reservation->salle?->ville?->pays?->nom ?? 'N/C';
    $prix = $reservation->salle?->prix ?? 0;
@endphp

<div class="page">
    <div class="header">
        <div class="logo">
            <img src="{{ public_path('images/cbc.jpeg') }}" alt="Logo CBC">
        </div>
        <div class="header-text">
            <h1>Reçu de réservation</h1>
            <p>Réservation N° {{ $reservation->id }}</p>
            <p>Généré le {{ $generatedAt->format('d/m/Y à H:i') }}</p>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Informations de la réservation</div>
        <div class="info-box">
            <table>
                <tr>
                    <th>Réservation</th>
                    <td>{{ $reservation->id }}</td>
                </tr>
                <tr>
                    <th>Date de réservation</th>
                    <td>{{ $reservation->reservation_date?->format('d/m/Y') ?? 'N/C' }}</td>
                </tr>
                <tr>
                    <th>Horaire</th>
                    <td>{{ $reservation->start_time ?? 'N/C' }} - {{ $reservation->end_time ?? 'N/C' }}</td>
                </tr>
                <tr>
                    <th>Salle</th>
                    <td>{{ $salleNom }}</td>
                </tr>
                <tr>
                    <th>Ville / Pays</th>
                    <td>{{ $salleVille }}{{ $sallePays ? ' / '.$sallePays : '' }}</td>
                </tr>
                <tr>
                    <th>Statut</th>
                    <td>
                        <span class="{{ $reservation->statut === 'confirmed' ? 'status-confirmed' : 'status-other' }}">
                            {{ ucfirst($reservation->statut) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Motif de refus</th>
                    <td>{{ $reservation->motifRejet ?? '—' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Informations du preneur</div>
        <div class="info-box">
            <table>
                <tr>
                    <th>Nom / Dénomination</th>
                    <td>{{ $clientNom }}</td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td>{{ $clientType }}</td>
                </tr>
                <tr>
                    <th>Adresse</th>
                    <td>{{ $clientAdresse }}</td>
                </tr>
                <tr>
                    <th>Ville</th>
                    <td>{{ $clientVille ?? 'N/C' }}</td>
                </tr>
                <tr>
                    <th>Pays</th>
                    <td>{{ $clientPays ?? 'N/C' }}</td>
                </tr>
                <tr>
                    <th>Téléphone</th>
                    <td>{{ $reservation->telephone ?? $reservation->entreprise?->telephoneE ?? $reservation->association?->telephoneA ?? 'N/C' }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $reservation->email ?? $reservation->association?->email ?? $reservation->user?->email ?? 'N/C' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Montants</div>
        <div class="details-box">
            <table>
                <tr>
                    <th>Prix de la salle</th>
                    <td>{{ number_format($prix, 0, ',', ' ') }} FCFA</td>
                </tr>
                @if(isset($reservation->montant_options) && $reservation->montant_options > 0)
                    <tr>
                        <th>Options additionnelles</th>
                        <td>{{ number_format($reservation->montant_options, 0, ',', ' ') }} FCFA</td>
                    </tr>
                @endif
                <tr>
                    <th>Total</th>
                    <td><strong>{{ number_format($prix + ($reservation->montant_options ?? 0), 0, ',', ' ') }} FCFA</strong></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Notes & compléments</div>
        <div class="info-box">
            <p><strong>Détails :</strong></p>
            <p>{{ $reservation->details ?? 'Aucun détail supplémentaire.' }}</p>
            <p><strong>Code OTP :</strong> {{ $reservation->otp ?? '—' }}</p>
            <p><strong>Date d'inscription :</strong> {{ optional($reservation->dateInscription)->format('d/m/Y H:i') ?? optional($reservation->created_at)->format('d/m/Y H:i') ?? '—' }}</p>
        </div>
    </div>

    <div class="footer">
        Reçu généré automatiquement le {{ $generatedAt->format('d/m/Y à H:i') }}.
    </div>
</div>
</body>
</html>