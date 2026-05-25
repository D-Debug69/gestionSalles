<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Portail CBC</title>
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
            <a class="nav-link active" href="{{ route('accueil') }}">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">Salles</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('reservations.form') }}">Réservations</a>
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
            <a href="{{ route('home') }}" class="btn btn-outline-light btn-lg">Découvrir</a>
            <a href="{{ route('reservations.form') }}" class="btn btn-light btn-lg">Voir mes réservations</a>
          </div>
        </div>
      </div>
    </div>
  </header>

  <main class="content">
    <section class="py-5">
      <div class="container">
        <div class="row text-center mb-5">
          <div class="col-lg-8 mx-auto">
            <h2 class="fw-bold">Un espace simple pour gérer vos réservations</h2>
            <p class="text-muted">Découvrez les salles disponibles, suivez les demandes et accédez aux fonctionnalités selon votre rôle.</p>
          </div>
        </div>

        <div class="row g-4">
          <div class="col-md-4">
            <div class="card feature-card p-4">
              <div class="card-body">
                <h5 class="card-title">Salles disponibles</h5>
                <p class="card-text text-muted">Consultez rapidement la liste des salles et leurs informations.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card feature-card p-4">
              <div class="card-body">
                <h5 class="card-title">Gestion des réservations</h5>
                <p class="card-text text-muted">Suivez l’état de vos demandes et planifiez vos événements.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card feature-card p-4">
              <div class="card-body">
                <h5 class="card-title">Accès facile</h5>
                <p class="card-text text-muted">Interface responsive, propre et adaptée aux utilisateurs et aux administrateurs.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
<br>
<br>
<br>
  <footer class="footer py-4">
    <div class="container text-center">
      <p class="mb-0">© 2026 GestionSalles — Portail CBC</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>