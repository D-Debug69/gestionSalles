<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin - Tableau de bord</title>
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
    url('{{ asset("images/header.jpg") }}');
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

/* Etat masqué */
.sidebar.collapsed {
  transform: translateX(calc(-1 * var(--sidebar-width)));
}

.sidebar .logo {
  width: 70px;
  height: 70px;
  object-fit: cover;
  display: block;
}

/* Content se décale */
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

/* bouton toggle (fixe en haut gauche) */
.sidebar-toggle { position: fixed; top: .75rem; left: .75rem; z-index:1040; }

/* Mobile behavior: translate default to hidden and show via .show */
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
    <h1>CBC-Vue Globale</h1>
  </div>
</header>

  <main class="content container-fluid">
    <div class="container py-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Tableau de bord</h1>
        @if (auth()->check() && auth()->user()->role === 'admin')
          <span class="text-muted">Bienvenue, {{ auth()->user()->prenom }}</span>
        @else
          <span class="text-muted">Bienvenue, {{ auth()->user()}}</span>
        @endif
      </div>

      <div class="row g-3 mb-4">
        <!-- Div du boutton de gestion du CRUD des salles-->
        <div class="col-md-3" onclick="window.location.href='/allSallesView';" style="cursor: pointer;">
          <div class="card card-overview shadow-sm">
            <div class="card-body">
              <h6 class="card-title">Salles</h6>
              <p class="h4 mb-0">{{ $salleCount }}</p>
            </div>
          </div>
        </div>

        <!-- Div du boutton de gestion du CRUD des reservations-->
        <div class="col-md-3" onclick="window.location.href='/allReservationsView';" style="cursor: pointer;">
          <div class="card card-overview shadow-sm">
            <div class="card-body">
              <h6 class="card-title">Réservations</h6>
              <p class="h4 mb-0">{{ $reservationCount }}</p>
            </div>
          </div>
        </div>

        <!-- Div du boutton de gestion du CRUD des utilisateurs-->
        <div class="col-md-3" onclick="window.location.href='/allUsersView';" style="cursor: pointer;">
          <div class="card card-overview shadow-sm">
            <div class="card-body">
              <h6 class="card-title">Utilisateurs</h6>
              <p class="h4 mb-0">{{ $userCount }}</p>
            </div>
          </div>
        </div>

        <!-- Div du boutton de gestion du CRUD des demandes-->
        <div class="col-md-3">
          <div class="card card-overview shadow-sm">
            <div class="card-body">
              <h6 class="card-title">Demandes en attente</h6>
              <p class="h4 mb-0">{{ $pendingRequestsCount }}</p>
            </div>
          </div>
        </div>
      </div>

      <section class="mb-4">
        <div class="card shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Dernières réservations</strong>
            <a href="/allReservationsView" class="small">Voir tout</a>
          </div>
          <div class="table-responsive">
            <table class="table table-striped mb-0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Salle</th>
                  <th>Demandeur</th>
                  <th>Date</th>
                  <th>Heures</th>
                  <th>Statut</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Salle A</td>
                  <td>Dupont</td>
                  <td>2026-04-20</td>
                  <td>09:00 - 11:00</td>
                  <td><span class="badge bg-success">Confirmée</span></td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Salle B</td>
                  <td>Martin</td>
                  <td>2026-04-19</td>
                  <td>14:00 - 16:00</td>
                  <td><span class="badge bg-warning text-dark">En attente</span></td>
                </tr>
                <!-- Ajoute d'autres lignes dynamiquement depuis ton contrôleur -->
              </tbody>
            </table>
          </div>
        </div>
      </section>
    </div>
  </main>

  <footer class="text-muted small py-3">
        © 2026 GestionSalles — Interface admin
      </footer>

 <script>
        (function(){
  const sidebar = document.getElementById('mainSidebar');
  const toggle = document.getElementById('sidebarToggle');
  const content = document.querySelector('.content') || document.querySelector('main') || document.body;

  if (!sidebar || !toggle) return;

  // initial state from localStorage
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

  // fermer/ouvrir avec Escape (utile si sidebar visible sur mobile)
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
