<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Promesse Bilatérale de Location - Réservation N° {{ $reservation->id }}</title>
    <style>
        * { margin: 0; padding: 0; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
        }
        .page {
            max-width: 850px;
            margin: 0 auto;
            padding: 30px 40px;
            background: white;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #1a1a1a;
            padding-bottom: 20px;
            margin-bottom: 25px;
            background: linear-gradient(180deg, #f8f8f8 0%, #ffffff 100%);
            padding: 20px 0;
        }
        .header h1 {
            font-size: 14pt;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header p {
            font-size: 10pt;
            color: #666;
            margin: 3px 0;
        }
        .parties {
            margin: 20px 0 25px 0;
            line-height: 1.7;
        }
        .partie-label {
            font-weight: bold;
            margin-top: 12px;
            margin-bottom: 5px;
            color: #1a1a1a;
            font-size: 10pt;
        }
        .partie-content {
            margin-left: 0;
            font-size: 10pt;
            line-height: 1.5;
        }
        .section {
            margin-top: 20px;
            margin-bottom: 15px;
        }
        .section-title {
            font-weight: bold;
            font-size: 11pt;
            color: #1a1a1a;
            margin-bottom: 10px;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .article-content {
            margin-left: 15px;
            font-size: 10pt;
            line-height: 1.6;
            text-align: justify;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
        }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 10pt;
        }
        td:first-child {
            font-weight: 600;
            color: #1a1a1a;
            width: 40%;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        ol, ul {
            margin-left: 25px;
            font-size: 10pt;
        }
        li {
            margin-bottom: 8px;
            text-align: justify;
            line-height: 1.5;
        }
        .signatures {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            text-align: center;
            font-size: 10pt;
        }
        .signature-line {
            border-top: 1px solid #333;
            padding-top: 5px;
            margin-top: 40px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 9pt;
            color: #666;
        }
        .info-box {
            background: #f5f5f5;
            padding: 10px;
            margin: 10px 0;
            border-left: 3px solid #333;
            font-size: 10pt;
            line-height: 1.5;
        }
        hr {
            margin: 15px 0;
            border: none;
            border-top: 1px solid #ccc;
        }
    </style>
</head>
<body>
@php
    $clientNom = $reservation->entreprise?->nomEntreprise
        ?? $reservation->association?->nomAssociation
        ?? $reservation->nom_demandeur
        ?? 'N/C';

    if ($reservation->entreprise) {
        $clientType = 'Entreprise';
        $clientAdresse = $reservation->entreprise->adresseCompleteE;
        $clientVille = $reservation->entreprise->ville;
        $clientPays = $reservation->entreprise->pays;
        $clientTelephone = $reservation->entreprise->telephoneE;
        $clientEmail = $reservation->entreprise->email ?? $reservation->email;
    } elseif ($reservation->association) {
        $clientType = 'Association';
        $clientAdresse = $reservation->association->adresseCompleteA;
        $clientVille = $reservation->association->ville;
        $clientPays = $reservation->association->pays;
        $clientTelephone = $reservation->association->telephoneA;
        $clientEmail = $reservation->association->email;
    } else {
        $clientType = 'Particulier';
        $clientAdresse = $reservation->details ?? 'N/C';
        $clientVille = null;
        $clientPays = null;
        $clientTelephone = $reservation->telephone;
        $clientEmail = $reservation->email;
    }

    $salleNom = $reservation->salle?->nom ?? $reservation->nomSalle ?? 'N/C';
    $salleVille = $reservation->salle?->ville?->nom;
    $sallePays = $reservation->salle?->ville?->pays?->nom;
    $dateReservation = $reservation->reservation_date?->format('d/m/Y') ?? 'N/C';
    $prix = $reservation->salle?->prix ?? 0;
    $startTime = $reservation->start_time ?? 'N/C';
    $endTime = $reservation->end_time ?? 'N/C';

    if ($startTime !== 'N/C' && $endTime !== 'N/C') {
        if ($endTime <= '14:00:00') {
            $bloc = 'matin';
        } elseif ($startTime >= '14:00:00') {
            $bloc = 'après-midi';
        } else {
            $bloc = 'journée';
        }
    } else {
        $bloc = 'N/C';
    }
@endphp

<div class="page">

    <div class="header">
        <h1>PROMESSE BILATÉRALE DE LOCATION DE SALLE</h1>
        <p>Réservation N° <strong>{{ $reservation->id }}</strong></p>
        <p style="font-size: 9pt; margin-top: 8px;">Généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <p style="font-weight: bold; font-size: 11pt; margin: 20px 0 10px 0;">ENTRE LES SOUSSIGNÉS :</p>

    <div class="parties">
        <p class="partie-label">D'UNE PART,</p>
        <p class="partie-content">
            Le <strong>Conseil Burkinabè des Chargeurs (C.B.C.)</strong>, Établissement Public à Caractère Professionnel,au capital de trente millions (30.000.000)FCFA, dont le siège est sis à Ouagadougou, 01 B.P 1771 Ouagadougou 01, tel.: 25 30 62 11/12, représenté par son <strong>Directeur Général</strong> ;<br>
            <strong>Ci-après dénommé « le CBC</strong> »
        </p>

        <p class="partie-label" style="margin-top: 15px;">ET D'AUTRE PART,</p>
        <p class="partie-content">
            <strong>Dénomination/Identité ......</strong> {{ $clientNom }}<br>
            <strong>Forme juridique/Profession ......</strong> {{ $clientType }}<br>
            <strong>Siège social/Domicile :</strong> {{ $clientAdresse ?? 'N/C' }}<br>
            @if($clientTelephone)
                <strong>Tél. :......</strong> {{ $clientTelephone }}<br>
            @endif
            Ci-après dénommée « <strong>le preneur</strong> ».
        </p>
    </div>

    <hr>

    <p style="font-weight: bold; font-size: 10pt; text-align: center; margin: 15px 0;">IL A ÉTÉ CONVENU ET ARRÊTÉ CE QUI SUIT :</p>

    <div class="section">
        <div class="section-title">ARTICLE 1 : OBJET</div>
        <div class="article-content">
            Par les présentes, le CBC promet de donner en location au preneur qui accepte, aux clauses et conditions ci-après définies :
            <div class="info-box">
                <strong>Salle de conférence :</strong> {{ $salleNom }}<br>
                <strong>Localisation :</strong>
                {{ $salleVille ?? $clientVille ?? 'N/C' }}
                @if($sallePays || $clientPays)
                    - {{ $sallePays ?? $clientPays }}
                @endif
                <br>
                <strong>Équipements :</strong> Selon liste établie à la location
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">ARTICLE 2 : DURÉE</div>
        <div class="article-content">
            Cette location est prévue pour la période suivante :
            <table style="margin-top: 12px;">
                <tr>
                    <td><strong>Date</strong></td>
                    <td>{{ $dateReservation }}</td>
                </tr>
                <tr>
                    <td><strong>Horaires</strong></td>
                    <td>{{ $startTime }} - {{ $endTime }}</td>
                </tr>
                <tr>
                    <td><strong>Bloc horaire</strong></td>
                    <td>{{ ucfirst($bloc) }}</td>
                </tr>
            </table>
            <p>le cas où le CBC ne fera pas jouer son droit de préférence</p>
        </div>
    </div>

    <div class="section">
        <div class="section-title">ARTICLE 3 : DROIT DE PRÉFÉRENCE</div>
        <div class="article-content">
            Le CBC dispose d'un droit de préférence. Ainsi, dans le cas où, après la signature des présentes, le CBC a besoin des lieux loués pour y tenir ses propres activités et/ou celles de son ministère de tutelle ou du Gouvernement, il peut révoquer sa promesse jusqu'au huitième jour précédant le premier jour de lalocation. 
<br>le CBC sera de plein droit déchu de cette faculté par la seule expiration du délai et sans qu'il ne soit besoin d'en informer le preneur.
        </div>
    </div>

    <div class="section">
        <div class="section-title">ARTICLE 4 : CONDITIONS PARTICULIÈRES</div>
        <div class="article-content">
            La présente location est consentie et acceptée sous les conditions que le Preneur s'oblige à exécuter et accomplir indépendamment de celles pouvant résulter de la loi ou de l'usage ainsi que des règlements.
        </div>
    </div>

    <div class="section">
        <div class="section-title">ARTICLE 5 : USAGE ET CONDITIONS DE JOUISSANCE DES LIEUX</div>
        <div class="article-content">
            <ul>
                <li>Le preneur s'engage à occuper les lieux dans l'état où ils se trouvent sans possibilité de modification.</li>
                <li>La sous-location est interdite.</li>
                <li>Le preneur ne pourra tenir dans les lieux loués que l'activité mentionnée dans sa demande de location.</li>
                <li>Le Preneur n'aura le droit d'apposer sur la façade extérieure des lieux loués que les enseignes, emblèmes ou plaques indicatrices relatives à son activité ou manifestations.</li>
                <li>Le Preneur s'engage à ne rien faire qui puisse porter atteinte à la propreté de l'immeuble et de ses abords.</li>
                <li>Ne rien faire qui puisse troubler la tranquillité de l'immeuble ou des voisins.</li>
                <li>Les frais de surveillance et de gardiennage des lieux loués sont à la charge et sous la responsabilité du Preneur pendant toute la durée de location.</li>
                <li>En somme, le preneur doit se conformer à toutes les prescriptions et directives qui lui seront données par le CBC pour l'entretien de la salle et des équipements.</li>
                <li>Le preneur prendra les lieux loués en bon état de réparation locative, en jouira en bon père de famille et les restituera en fin de location en bon état.</li>
                <li>Le CBC, en dehors de l'exercice de son droit de préférence prévu à l'article 3 de la présente, s'engage à ne pas troubler la jouissance du preneur.</li>
            </ul>
        </div>
    </div>

    <div class="section">
        <div class="section-title">ARTICLE 6 : CONDITIONS FINANCIÈRES</div>
        <div class="article-content">
            <ul>
                <li>La location est consentie et acceptée moyennant les tarifs suivants, payables d'avance :</li>
                <li>En outre, pour l'exécution des présentes et pour répondre des dégâts qui pourraient être causés au lieu et équipements loués, le preneur verse à l'instant même une somme de xxxxxxxx FCFA à titre de dépôt de garantie.</li>
                <li>Le montant de ce dépôt, qui ne sera pas productif d'intérêt, sera remboursé en fin de jouissance après remise des clés, déduction faite de toutes les sommes dont il pourrait être débiteur envers le CBC ou dont celui-ci pourrait être rendu responsable par lui, au titre des éventuelles dégradations occasionnées lors de l'occupation ou de toute dégradation intervenue de son fait et/ou de tout occupant de son chef.</li>
            </ul>
            <table style="margin-top: 12px;">
                <tr>
                    <td><strong>Prix location</strong></td>
                    <td style="text-align: right;">{{ number_format($prix, 0, ',', ' ') }} FCFA</td>
                </tr>

                @if(isset($reservation->montant_options) && $reservation->montant_options > 0)
                    <tr>
                        <td><strong>Options additionnelles</strong></td>
                        <td style="text-align: right;">{{ number_format($reservation->montant_options, 0, ',', ' ') }} FCFA</td>
                    </tr>
                @endif

                <tr style="background-color: #e8e8e8; font-weight: bold; border-top: 2px solid #1a1a1a;">
                    <td><strong>MONTANT TOTAL</strong></td>
                    <td style="text-align: right;">
                        <strong>{{ number_format($prix + ($reservation->montant_options ?? 0), 0, ',', ' ') }} FCFA</strong>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-title">ARTICLE 7 : CONCLUSION DU CONTRAT DÉFINITIF</div>
        <div class="article-content">
            Le contrat de location sera considéré comme conclu et définitif le jour du paiement du loyer par le preneur et après expiration du délai de droit de préférence dont jouit le CBC.
        </div>
    </div>

    <div class="section">
        <div class="section-title">ARTICLE 8 : CLAUSES RÉSOLUTOIRES</div>
        <div class="article-content">
            La présente promesse de location sera résiliée à la demande de l'une des parties pour les motifs suivants :
            <ol style="margin-top: 8px;">
                <li>L'exercice du droit de préférence du CBC</li>
                <li>Le défaut de paiement de l'avance de location</li>
                <li>L'inexécution par l'une des parties d'une quelconque des clauses du présent contrat</li>
                <li>Le défaut de notification du report de location ou de l'annulation</li>
            </ol>
        </div>
    </div>

    <div class="section">
        <div class="section-title">ARTICLE 9 : ATTRIBUTION DE JURIDICTION</div>
        <div class="article-content">
            Les parties conviennent dès à présent que tout différend qui viendrait à naître de l'exécution des présentes, et qui n'aura pu être résolu à l'amiable, sera porté par la partie la plus diligente devant la juridiction compétente.
        </div>
    </div>

    <p style="text-align: center; margin-top: 30px; font-weight: bold; font-size: 10pt;">
        Fait à Ouagadougou, le {{ now()->format('d/m/Y') }}<br>
        En deux (02) exemplaires originaux
    </p>

    <div class="signatures">
        <div class="signature-box">
            <strong>P/ LE CONSEIL BURKINABÈ DES CHARGEURS</strong>
            <div class="signature-line">
                Signature et cachet du Directeur
            </div>
        </div>
        <div class="signature-box">
            <strong>LE PRENEUR</strong><br>
            {{ $clientNom }}
            <div class="signature-line">
                Signature et cachet
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Document généré automatiquement le {{ now()->format('d/m/Y à H:i') }}</p>
        <p style="margin-top: 8px; font-size: 8pt;">Réservation: {{ $reservation->id }}</p>
    </div>
</div>

</body>
</html>