<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin - Toutes les Salles</title>
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
@guest
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
@endguest


@auth
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
@endauth
    </style>
</head>
<body>
@auth    
@include('partials.sidebar')
@include('partials.header')
<button id="sidebarToggle" class="btn btn-sm btn-outline-secondary sidebar-toggle" aria-controls="mainSidebar" aria-expanded="true">☰</button>
@endauth

 @guest 
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
          <p>Suivez facilement toutes vos réservations grace aux codes otp fournis après la réservation.</p>
          <div class="d-flex gap-2">
            <a href="{{ route('accueil') }}" class="btn btn-light btn-lg">Acceuil</a>
            <a href="{{ route('home') }}" class="btn btn-outline-light btn-lg">Découvrir</a>
          </div>
        </div>
      </div>
    </div>
  </header>
  @endguest

<main class="content">
  <div class="container py-6">
    <div class="row g-3">
      <form action="{{ route('reservations.search') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="otp" class="form-label">Code OTP</label>
        <input type="text" name="otp" id="otp" class="form-control" maxlength="6" required>
        @error('otp')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">Rechercher ma réservation</button>

    <a href="{{ route('home') }}" class="btn btn-outline-secondary">Retour à l'accueil</a>
      </form>
    </div> 
  </div>  
</main>

        <footer class="footer py-4">
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
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>
</html>