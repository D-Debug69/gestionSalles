<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier les permissions - {{ $role->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 70px; }
        .permission-group { margin-bottom: 2rem; }
        .permission-group h5 { border-bottom: 2px solid #0d6efd; padding-bottom: 10px; margin-bottom: 1rem; }
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


    <main class="content">
      @include('partials.header', ['title' => 'CBC-Gestion Rôles et Permissions'])
        <div class="container py-5">
            <h1 class="mb-4">Modifier les permissions : <strong>{{ ucfirst($role->name) }}</strong></h1>

            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    @php
                        // Grouper les permissions par catégorie
                        $categories = [
                            'reservation' => ['create reservation', 'view reservation', 'update reservation', 'delete reservation', 'refuse reservation', 'accept reservation'],
                            'salle' => ['create salle','view salle','update salle','delete salle',],
                            'utilisateur' => ['create user', 'update user', 'delete user', 'view user'],
                            'localisation' => ['create pays', 'delete pays', 'create ville', 'update ville', 'delete ville'],
                            'autre' => ['create chreno', 'update chreno'],
                        ];
                    @endphp

                    @foreach($categories as $category => $permNames)
                        <div class="col-md-6">
                            <div class="permission-group">
                                <h5>{{ ucfirst($category) }}</h5>
                                @foreach($permissions as $permission)
                                    @if(in_array($permission->name, $permNames))
                                        <div class="form-check mb-2">
                                            <input 
                                                class="form-check-input" 
                                                type="checkbox" 
                                                name="permissions[]" 
                                                value="{{ $permission->id }}"
                                                id="permission_{{ $permission->id }}"
                                                @if(in_array($permission->id, $rolePermissions)) checked @endif
                                            >
                                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success btn-lg">
                        ✓ Mettre à jour les permissions
                    </button>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-lg">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </main>

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