<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Réservation - Détails</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <style>
    body {
      padding-top: 70px;
      background: #f8f9fa;
    }
    .hero {
      min-height: 60vh;
      background: linear-gradient(180deg, rgba(13,110,253,0.85), rgba(13,110,253,0.55)),
                  url('{{ asset("images/cbc.jpeg") }}') center/cover no-repeat;
      color: #fff;
      display: flex;
      align-items: center;
    }
    .hero h1 {
      font-size: 3rem;
      font-weight: 700;
    }
    .hero p {
      font-size: 1.1rem;
      max-width: 540px;
    }
    .feature-card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 20px 45px rgba(0, 0, 0, 0.08);
    }
    .footer {
  background: #0d6efd;
  color: #fff;
  padding: 2rem 0;
  margin-top: 2rem;
}
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold" href="{{ route('accueil') }}">CBC Portail</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
        aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('accueil') }}">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">Salles</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="{{ route('reservations.form') }}">Réservations</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
          </li>
        </ul>
        <div class="d-flex ms-lg-3 mt-3 mt-lg-0">
          <a href="{{ route('login') }}" class="btn btn-light btn-sm">Se connecter</a>
        </div>
      </div>
    </div>
  </nav>

  <header class="hero">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <span class="badge bg-warning text-dark mb-3">Réservation entreprise & association</span>
          <h1>Bienvenue sur le portail CBC</h1>
          <p>Réservez votre salle en ligne rapidement, consultez vos demandes et suivez facilement toutes vos réservations.</p>
          <div class="d-flex gap-2">
            <a href="{{ route('reservations.form') }}" class="btn btn-light btn-lg">Voir mes réservations</a>
            <a href="{{ route('home') }}" class="btn btn-outline-light btn-lg">Découvrir</a>
          </div>
        </div>
      </div>
    </div>
  </header>

  <main class="content">
    <br>
    <div class="container">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4">Réservation #{{ $reservation->id }}</h2>
        <a href="{{ route('reservations.form') }}" class="btn btn-outline-secondary">Retour à la recherche</a>
      </div>

      <div class="row gy-4">
        <div class="col-lg-8">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Informations générales</h5>

              <dl class="row mb-0">
                <dt class="col-sm-4">Salle demandée</dt>
                <dd class="col-sm-8">{{ $reservation->nomSalle ?? 'Non renseignée' }}</dd>

                <dt class="col-sm-4">Date voulue</dt>
                  <dd class="col-sm-8">
                    {{ $reservation->reservation_date ? $reservation->reservation_date->format('d/m/Y') : 'Non renseignée' }}
                  </dd>

                <dt class="col-sm-4">Heure voulue</dt>
                  <dd class="col-sm-8">
                    {{ $reservation->start_time && $reservation->end_time? $reservation->start_time . ' - ' . $reservation->end_time: 'Non renseignée' }}
                  </dd>

                <dt class="col-sm-4">Code OTP</dt>
                <dd class="col-sm-8">{{ $reservation->otp ?? '—' }}</dd>

                <dt class="col-sm-4">Demandeur</dt>
                <dd class="col-sm-8">
                  {{ $reservation->nom_demandeur
                     ?? optional($reservation->entreprise)->nomEntreprise
                     ?? optional($reservation->association)->nomAssociation
                     ?? '—' }}
                </dd>

                <dt class="col-sm-4">Téléphone</dt>
                <dd class="col-sm-8">
                  {{ $reservation->telephone
                     ?? optional($reservation->entreprise)->telephoneE
                     ?? optional($reservation->association)->telephoneA
                     ?? '—' }}
                </dd>

                <dt class="col-sm-4">Email</dt>
                <dd class="col-sm-8">
                  {{ $reservation->email
                     ?? optional($reservation->association)->email
                     ?? optional($reservation->user)->email
                     ?? '—' }}
                </dd>

                <dt class="col-sm-4">Statut</dt>
                <dd class="col-sm-8">
                  @if($reservation->statut === 'pending')
                    <span class="badge bg-warning text-dark">En attente</span>
                  @elseif($reservation->statut === 'confirmed')
                    <span class="badge bg-success">Confirmée</span>
                  @elseif($reservation->statut === 'rejected')
                    <span class="badge bg-danger">Refusée</span>
                  @else
                    <span class="badge bg-secondary">{{ $reservation->statut }}</span>
                  @endif
                </dd>

                <dt class="col-sm-4">Motif de refus</dt>
                <dd class="col-sm-8">
                  {{ $reservation->motifRejet ?? '----' }}
                </dd>

                <dt class="col-sm-4">Date d'inscription</dt>
                <dd class="col-sm-8">
                  {{ optional($reservation->dateInscription)->format('d/m/Y H:i')
                     ?? optional($reservation->created_at)->format('d/m/Y H:i')
                     ?? '—' }}
                </dd>

                <dt class="col-sm-4">Créé par</dt>
                <dd class="col-sm-8">
                  {{ optional($reservation->user)->name ?? 'Utilisateur non connecté' }}
                </dd>
              </dl>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Détails du profil</h5>

              @if($reservation->entreprise)
                <p><strong>Type :</strong> Entreprise</p>
                <p><strong>Forme :</strong> {{ $reservation->entreprise->typeEntreprise ?? '—' }}</p>
                <p><strong>RCCM :</strong> {{ $reservation->entreprise->rccm ?? '—' }}</p>
                <p><strong>IFU :</strong> {{ $reservation->entreprise->ifu ?? '—' }}</p>
                <p><strong>Pays :</strong> {{ $reservation->entreprise->pays ?? '—' }}</p>
                <p><strong>Ville :</strong> {{ $reservation->entreprise->ville ?? '—' }}</p>
              @elseif($reservation->association)
                <p><strong>Type :</strong> Association</p>
                <p><strong>Forme :</strong> {{ $reservation->association->typeAssociation ?? '—' }}</p>
                <p><strong>Recepisse :</strong> {{ $reservation->association->recepisse ?? '—' }}</p>
                <p><strong>Pays :</strong> {{ $reservation->association->pays ?? '—' }}</p>
                <p><strong>Ville :</strong> {{ $reservation->association->ville ?? '—' }}</p>
              @else
                <p>Aucune info d'entreprise/association liée.</p>
              @endif
              @if ($reservation->statut === 'confirmed')
              <a href="{{ route('reservations.download-pdf', $reservation->id) }}" class="btn btn-primary" target="_blank">
                <i class="bi bi-file-pdf"></i> Confirmer ma présence (PDF)
              </a>
              @endif
            </div>
          </div>
        </div>
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
    </div>

   <div class="container mt-4">
    @if($reservation->statut === 'pending')
  <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment annuler cette réservation ?')">
      @csrf
      <button type="submit" class="btn btn-secondary">Annuler la réservation</button>
  </form>
      @elseif($reservation->statut === 'canceled')
        <span class="text-muted">Réservation annulée</span>
      @endif
    </div>
  </main>

  <footer class="footer py-4">
    © 2026 GestionSalles
  </footer>

  <script>
    (function(){
      const sidebar = document.getElementById('mainSidebar');
      const toggle = document.getElementById('sidebarToggle');
      const content = document.querySelector('.content') || document.querySelector('main') || document.body;

      if (!sidebar || !toggle) return;

      const saved = localStorage.getItem('sidebar-collapsed') === 'true';
      if (saved) {
        sidebar.classList.add('collapsed');
        content.classList.add('collapsed');
        toggle.setAttribute('aria-expanded', 'false');
        sidebar.setAttribute('aria-hidden', 'true');
      } else {
        toggle.setAttribute('aria-expanded', 'true');
        sidebar.setAttribute('aria-hidden', 'false');
      }

      toggle.addEventListener('click', () => {
        const isCollapsed = sidebar.classList.toggle('collapsed');
        content.classList.toggle('collapsed', isCollapsed);
        toggle.setAttribute('aria-expanded', String(!isCollapsed));
        sidebar.setAttribute('aria-hidden', String(isCollapsed));
        localStorage.setItem('sidebar-collapsed', String(isCollapsed));
      });

      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
          const isCollapsed = sidebar.classList.contains('collapsed');
          if (!isCollapsed && window.innerWidth <= 991.98) {
            sidebar.classList.add('collapsed');
            content.classList.add('collapsed');
            toggle.setAttribute('aria-expanded', 'false');
            sidebar.setAttribute('aria-hidden', 'true');
          }
        }
      });
    })();
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>