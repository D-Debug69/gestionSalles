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

@include('partials.header', ['title' => 'CBC-Gestion Utilisateurs'])

<body>
    
    <main class="content">


<div class="container mt-5">
        <h1>Modifier Ville</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('ville.update') }}" id="villeForm" enctype="multipart/form-data">
            @csrf

            <!-- Section Ville -->
            <fieldset class="mb-4 p-3 border rounded">
                <legend class="fs-5 fw-bold">Informations de la Ville</legend>

                <div class="mb-3">
                    <label for="nom_ville" class="form-label">Nom de la Ville</label>
                    <input type="text" class="form-control" id="nom_ville" name="nom" placeholder="Nom" required value="{{ $ville->nom }}">
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Photo de la Ville</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
            </fieldset>

            <!-- Section Salles -->
            <fieldset class="mb-4 p-3 border rounded">
                <legend class="fs-5 fw-bold">Salles</legend>

                <div id="sallesContainer">
                    <!-- Les salles seront ajoutées ici -->
                </div>

                <button type="button" class="btn btn-secondary btn-edit-salle" id="editSalleBtn">COnfirmer la modification</button>
            </fieldset>

            <div class="d-flex gap-2">
                <a href="{{ route('allSallesView') }}" class="btn btn-secondary">Retour</a>
            </div>
        </form>
    </div>


    </main>
    
</body>





<footer class="text-muted small py-3">
        © 2026 GestionSalles — Interface admin
      </footer>

<script>
        let salleCount = 0;

        
        function editSalleForm() {
    salleCount++;
    const html = `
        <div class="salle-form" id="salle-${salleCount}">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label for="salle_nom_${salleCount}" class="form-label">Nom de la Salle</label>
                    <input type="text" class="form-control" name="salles[${salleCount}][nom]" id="salle_nom_${salleCount}" placeholder="Ex: Salle A" required value={{ $salle->nom }}>
                </div>
                <div class="col-md-2">
                    <label for="salle_capacite_${salleCount}" class="form-label">Capacité</label>
                    <input type="number" class="form-control" name="salles[${salleCount}][capacite]" id="salle_capacite_${salleCount}" placeholder="Ex: 50" min="1">
                </div>
                <div class="col-md-2">
                    <label for="salle_prix_${salleCount}" class="form-label">Prix (XoF)</label>
                    <input type="number" class="form-control" name="salles[${salleCount}][prix]" id="salle_prix_${salleCount}" placeholder="Ex: 50.00" step="0.01" min="0">
                </div>
                <div class="col-md-3">
                    <label for="salle_equipements_${salleCount}" class="form-label">Équipements</label>
                    <input type="text" class="form-control" name="salles[${salleCount}][equipements]" id="salle_equipements_${salleCount}" placeholder="Ex: Projecteur, Tables">
                </div>
                <div class="col-md-2">
                    <label for="salle_image_${salleCount}" class="form-label">Photo</label>
                    <input type="file" class="form-control" name="salles[${salleCount}][image]" id="salle_image_${salleCount}" accept="image/*">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeSalleForm(${salleCount})">Supprimer</button>
                </div>
            </div>
        </div>
    `;
    document.getElementById('sallesContainer').insertAdjacentHTML('beforeend', html);
}

        // Ajoute une salle par défaut au chargement
        editSalleForm();
    </script>

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