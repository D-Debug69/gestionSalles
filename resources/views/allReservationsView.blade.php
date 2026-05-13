<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin - Toutes les Reservations</title>
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

@include('partials.header', ['title' => 'CBC-Gestion Reservations'])

<main class="content">
  <!-- le contenu existant de la page -->
@foreach($reservations as $r)
  <div class="col">
    <div class="card card-overview shadow-sm">
      <div class="card-body">
        <h6 class="card-title">R{{ $r->id }}</h6>
        
        <p class="h5 mb-1">
  {{ $r->nomSalle ?? 'Salle non renseignée' }}
</p>

<p>
  Demandeur :
  {{ $r->nom_demandeur 
     ?? optional($r->entreprise)->nomEntreprise
     ?? optional($r->association)->nomAssociation
     ?? '—' }}
</p>

<p>
  Téléphone :
  {{ $r->telephone 
     ?? optional($r->entreprise)->telephoneE 
     ?? optional($r->association)->telephoneA 
     ?? '—' }}
</p>

<p>
  Email :
  {{ $r->email 
     ?? optional($r->association)->email 
     ?? optional($r->user)->email 
     ?? '—' }}
</p>

<p class="small mb-2">
  Inscrit le: 
  {{ $r->dateInscription 
       ? $r->dateInscription->format('Y-m-d H:i') 
       : ($r->created_at ? $r->created_at->format('Y-m-d H:i') : '—') }}
</p>
        
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-primary view-res-btn" data-id="{{ $r->id }}">Voir</button>
            
          @auth
                        @can('delete reservation')
                          <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('reservations.destroy', $r->id) }}" style="display:inline;">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cette réservation ?')">Supprimer Réservation</button>
                            </form>
                          </div>
                        @endcan
                      @endauth


          @if($r->statut === 'pending') <span class="badge bg-warning text-dark">En attente</span>@elseif($r->statut === 'confirmed') <span class="badge bg-success">Confirmée</span>@elseif($r->statut === 'rejected') <span class="badge bg-danger">Refusée</span>@endif
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


<!-- Modal de detail des reservations -->
<div class="modal fade" id="reservationModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Détails Réservation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="resContent">
          <p><strong>Salle :</strong> <span id="resSalle">—</span></p>
          <p><strong>Demandeur :</strong> <span id="resNom">—</span></p>
          <p><strong>Email :</strong> <span id="resEmail">—</span></p>
          <p><strong>Téléphone :</strong> <span id="resPhone">—</span></p>
          <p><strong>Détails :</strong> <span id="resDetails">—</span></p>

          <h6>Approvals</h6>
          <ul id="resApprovals">
            <li data-role="CC">CC: <span class="status">—</span></li>
            <li data-role="DFC">DFC: <span class="status">—</span></li>
            <li data-role="DG">DG: <span class="status">—</span></li>
            <li data-role="ADMIN">Admin: <span class="status">—</span></li>
          </ul>

          <div id="pdfWrapper" style="display:none;">
            <h6>Document PDF</h6>
            <a id="resPdfLink" class="btn btn-sm btn-outline-secondary" target="_blank">Ouvrir PDF</a>
            <div style="height:480px; margin-top:8px;">
              <iframe id="resPdfFrame" style="width:100%; height:100%; border:0;"></iframe>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="approveBtn" class="btn btn-success d-none">Confirmer</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
<!-- Script JS -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
  window.currentUserRole = "{{ auth()->check() ? strtoupper(auth()->user()->role) : '' }}";
  window.currentUserId = "{{ auth()->id() ?? '' }}";

  document.querySelectorAll('.view-res-btn').forEach(btn=>{
    btn.addEventListener('click', async () => {
      const id = btn.dataset.id;
      const res = await fetch(`/reservations/${id}/json`);
      if (!res.ok) return alert('Erreur chargement');
      const data = await res.json();

      // Salle: use canonical salle_nom provided by API
      const salleName = data.salle_nom || (data.nomSalle || (data.salle && (data.salle.nom || data.salle.name))) || '—';
      const salleVille = data.salle_ville ? ` (${data.salle_ville})` : '';
      document.getElementById('resSalle').textContent = salleName + salleVille;

      // Demandeur: prefer reservation field, then related user prenom/name
      const demandeur = data.nom_demandeur || (data.user && (data.user.prenom || data.user.name)) || '—';
      document.getElementById('resNom').textContent = demandeur;

      // Email: prefer reservation email, then related user email
      const email = data.email || (data.user && data.user.email) || '—';
      document.getElementById('resEmail').textContent = email;

      // Téléphone: prefer reservation telephone, then related user telephone or phone
      const phone = data.telephone || (data.user && (data.user.telephone || data.user.phone)) || '—';
      document.getElementById('resPhone').textContent = phone;
      document.getElementById('resDetails').textContent = data.details ?? '—';

      // approvals
      const map = {
        'CC': {flag: data.approved_cc, by: data.approved_cc_by, at: data.approved_cc_at, user: data.approved_cc_by ? data.approved_cc_by : null},
        'DFC': {flag: data.approved_dfc, by: data.approved_dfc_by, at: data.approved_dfc_at},
        'DG': {flag: data.approved_dg, by: data.approved_dg_by, at: data.approved_dg_at},
        'ADMIN': {flag: data.approved_admin, by: data.approved_admin_by, at: data.approved_admin_at},
      };

      document.querySelectorAll('#resApprovals li').forEach(li=>{
        const role = li.dataset.role;
        const info = map[role];
        const el = li.querySelector('.status');
        if (info && info.flag) {
          // show approver name when provided by API, admin sees name else generic
          const nameField = {
            'CC': data.approved_cc_by_name,
            'DFC': data.approved_dfc_by_name,
            'DG': data.approved_dg_by_name,
            'ADMIN': data.approved_admin_by_name,
          }[role];
          if (nameField && (window.currentUserRole === 'ADMIN')) {
            el.textContent = 'Confirmé par ' + nameField + (info.at ? ' à '+ info.at : '');
          } else {
            el.textContent = 'Confirmé';
          }
        } else {
          el.textContent = 'Non confirmé';
        }
      });

      // PDF
      if (data.pdf_path) {
        document.getElementById('pdfWrapper').style.display = 'block';
        const url = '/storage/' + data.pdf_path;
        document.getElementById('resPdfLink').href = url;
        document.getElementById('resPdfFrame').src = url;
      } else {
        document.getElementById('pdfWrapper').style.display = 'none';
      }

      // Approve button visibility
      const approveBtn = document.getElementById('approveBtn');
      approveBtn.classList.add('d-none');
      const allowed = ['CC','DFC','DG','ADMIN'];
      if (allowed.includes(window.currentUserRole)) {
        // check if this role already approved
        const flagName = {
          'CC':'approved_cc','DFC':'approved_dfc','DG':'approved_dg','ADMIN':'approved_admin'
        }[window.currentUserRole];
        if (!data[flagName]) {
          approveBtn.classList.remove('d-none');
          approveBtn.onclick = async () => {
            approveBtn.disabled = true;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const resp = await fetch(`/reservations/${data.id}/approve`, {
              method:'POST',
              headers: {'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept':'application/json'},
              body: JSON.stringify({})
            });
            const js = await resp.json();
            if (!resp.ok) {
              alert(js.error || 'Erreur');
              approveBtn.disabled = false;
              return;
            }
            // reload modal content by re-clicking
            btn.click();
          };
        }
      }

      // show modal
      const modalEl = new bootstrap.Modal(document.getElementById('reservationModal'));
      modalEl.show();
    });
  });
</script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
