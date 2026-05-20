<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Réservation - Détails</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { padding-top: 70px; }
    .top-brand { font-weight:700; letter-spacing: .5px; }
    .card-overview { min-height: 120px; }
    :root { --sidebar-width: 240px; }

    .page-hero {
      height: 220px;
      background-image:
        linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.05)),
        url('{{ asset("images/cbc.jpeg") }}');
      background-size: cover;
      background-position: center;
      display: flex;
      align-items: flex-end;
      color: #fff;
      padding: 1rem;
      margin-top: -100px;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      width: var(--sidebar-width);
      background:#fff;
      border-right:1px solid #e6e6e6;
      z-index:1030;
      overflow:auto;
      transform: translateX(0);
      transition: transform .25s ease, width .25s ease;
    }

    .sidebar.collapsed {
      transform: translateX(calc(-1 * var(--sidebar-width)));
    }

    .sidebar .logo {
      width: 70px;
      height: 70px;
      object-fit: cover;
      display: block;
    }

    .content {
      margin-left: var(--sidebar-width);
      width: calc(100% - var(--sidebar-width));
      min-height: 100vh;
      padding: 1.25rem;
      transition: margin-left .25s ease, width .25s ease;
    }
    .content.collapsed {
      margin-left: 0;
      width: 100%;
    }

    .sidebar-toggle { position: fixed; top: .75rem; left: .75rem; z-index:1040; }

    @media (max-width: 991.98px) {
      .sidebar { transform: translateX(-100%); }
      .sidebar.show { transform: translateX(0); }
      .content { margin-left: 0; }
    }
  </style>
</head>
<body>
  @include('partials.sidebar')

  <button id="sidebarToggle" class="btn btn-sm btn-outline-secondary sidebar-toggle" aria-controls="mainSidebar" aria-expanded="true">☰</button>

  <header class="page-hero">
    <div class="hero-content container">
      <h1>Détails de la réservation</h1>
    </div>
  </header>

  <main class="content">
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
  </main>

  <footer class="text-muted small py-3">
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