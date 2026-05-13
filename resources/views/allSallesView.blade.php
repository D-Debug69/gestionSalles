<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>
@if(auth()->check())
  @if(in_array(auth()->user()->role, ['admin','rgs','dg']))
    Admin - Toutes les Salles
  @else
    Utilisateur - Toutes les Salles
  @endif
@else
  Toutes les Salles - CBC
@endif
</title>
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

  @php
      $headerTitle = auth()->check() && in_array(auth()->user()->role, ['admin','rgs','dg'])
          ? 'CBC- Gestion des Salles'
          : 'Utilisateur - Bienvenue au portail du CBC';
  @endphp
  @include('partials.header', ['title' => $headerTitle])
  

<main class="content">
  <!-- le contenu existant de la page -->
<div class="container py-6">
  @auth
    @can('create pays')
      <div class="mb-3 d-flex gap-2">
        <a href="{{ route('pays.create') }}" class="btn btn-primary">Ajouter un Pays</a>
        <a href="{{ route('ville.create') }}" class="btn btn-outline-primary">Ajouter une Ville + Salles</a>
      </div>
    @endcan
  @endauth
  @guest
  <div class="mb-3 d-flex gap-2">
        <a href="{{ route('reservations.form') }}" class="btn btn-outline-primary">Voir mes réservations</a>
      </div>
  @endguest

  <div class="row g-3">
    @forelse($pays as $p)
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card card-overview shadow-sm">
          <img src="{{ asset('images/header.jpg') }}" border="20px" class="card-img-top" alt="{{ $p->nom }}">
          <div class="card-body">
            <h6 class="card-title">{{ $p->nom }}</h6>
            <p class="h4 mb-2">{{ $p->villes->count() }} villes</p>
            
            <!-- Afficher les villes du pays -->
            @if($p->villes->count() > 0)
              <div class="mb-2" style="font-size: 0.85rem;">
                @foreach($p->villes as $v)
                  <div class="badge bg-info mb-1">{{ $v->nom }} ({{ $v->salles->count() }} salles)</div>
                @endforeach
              </div>
            @endif

            <div class="d-flex gap-2">
              <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#paysModal{{ $p->id }}">Voir</button>

              @auth
                @can('delete pays')
                  <form method="POST" action="{{ route('pays.destroy', $p->id) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer le pays</button>
                  </form>
                @endcan
              @endauth
            </div>
          </div>
        </div>
      </div>
    @empty
      <p class="text-muted">Aucun pays pour le moment.</p>
    @endforelse
  </div>
</div>


<!-- Modals pour chaque pays -->
@foreach($pays as $p)
  <div class="modal fade" id="paysModal{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl">  <!-- modal-xl pour une grande fenêtre -->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ $p->nom }} - Villes et Salles</h5>  <!-- Titre de la modal -->
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>  <!-- Bouton fermer -->
        </div>
        <div class="modal-body">
          @if($p->villes->count() > 0)
            <div class="row g-3">  <!-- Grille pour afficher les villes -->
              @foreach($p->villes as $ville)
                <div class="col-md-6 col-lg-4">  <!-- Chaque ville dans une colonne -->
                  <div class="card">
                    <!-- Photo de la ville -->
                    <img src="{{ $ville->image_url }}" class="card-img-top" alt="{{ $ville->nom }}" style="height: 150px; object-fit: cover;">
                    <div class="card-body">
                      <h6 class="card-title">{{ $ville->nom }}</h6>  <!-- Nom de la ville -->
                      <p class="mb-2">{{ $ville->salles->count() }} salles</p>  <!-- Nombre de salles -->
                      
                      <!-- Liste des salles -->
                      @if($ville->salles->count() > 0)
                        <div class="mb-2">
                          @foreach($ville->salles as $salle)
                            <div class="d-flex align-items-center mb-1">
                              <!-- Photo de la salle -->
                              <img src="{{ $salle->image_url }}" alt="{{ $salle->nom }}" style="width: 30px; height: 30px; margin-right: 10px; object-fit: cover;">
                              <!-- Détails de la salle -->
                              <span>{{ $salle->nom }} (Capacité: {{ $salle->capacite ?? 'N/A' }}, Prix: {{ $salle->prix ?? 'N/A' }}, Équipements: {{ $salle->equipements ?? 'N/A' }})</span>

                            @guest
                              <a href="#" class="btn btn-sm btn-outline-primary">Voir </a>
                              <a href="{{ route('reservGenerale', ['salle_id' => $salle->id]) }}" class="btn btn-sm btn-outline-primary">Réserver</a>
                            @endguest
                            </div>
                          @endforeach
                        </div>
                      @endif

                      <!-- Boutons modifier/supprimer pour la ville -->
                      @auth                 
                        @can('update ville')
                          <div class="d-flex gap-2">
                            <a href="{{ route('ville.edit', $ville->id) }}" class="btn btn-sm btn-outline-warning">Modifier Ville</a>
                        @endcan
                        @can('delete ville')  
                            <form method="POST" action="{{ route('ville.destroy', $ville->id) }}" style="display:inline;">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cette ville ?')">Supprimer Ville</button>
                            </form>
                          </div>
                        @endcan
                      @endauth
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <p>Aucune ville dans ce pays.</p>
          @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>  <!-- Bouton fermer en bas -->
        </div>
      </div>
    </div>
  </div>
@endforeach
    
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
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</html>