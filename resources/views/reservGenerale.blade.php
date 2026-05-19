<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Réservation Entreprise-Association</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
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

    @include('partials.header', ['title' => 'CBC-Reservation'])

<main class="content">


@if(session('otp'))
<!-- Modal OTP auto-fermable -->
<div class="modal fade" id="otpModal" tabindex="-1" style="background: rgba(0,0,0,0.5);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">✅ Votre code OTP</h5>
        <button type="button" class="btn-close btn-close-white" id="closeModalBtn" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <p class="mb-4">Conservez ce code pour consulter votre réservation :</p>
        <div style="font-size: 3rem; font-weight: bold; color: #28a745; text-align: center; padding: 2rem; border: 3px solid #28a745; border-radius: 10px; background: #f8f9fa; letter-spacing: 5px; font-family: 'Courier New', monospace;">
          {{ session('otp') }}
        </div>
        <p class="mt-4 text-muted">
          <small>Ce modal se fermera automatiquement dans <span id="countdown">10</span> secondes</small>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="manualCloseBtn">Fermer</button>
        <button type="button" class="btn btn-primary" onclick="copyToClipboard()">Copier le code</button>
      </div>
    </div>
  </div>
</div>

<script>
  window.addEventListener('load', () => {
    const modal = new bootstrap.Modal(document.getElementById('otpModal'), { backdrop: 'static', keyboard: false });
    modal.show();

  let seconds = 10;
  const countdownEl = document.getElementById('countdown');
  const interval = setInterval(() => {
    seconds--;
    countdownEl.textContent = seconds;
    if (seconds <= 0) {
      clearInterval(interval);
      modal.hide();
    }
  }, 1000);

  document.getElementById('closeModalBtn').addEventListener('click', () => {
    clearInterval(interval);
    modal.hide();
  });
  document.getElementById('manualCloseBtn').addEventListener('click', () => {
    clearInterval(interval);
    modal.hide();
  });

  function copyToClipboard() {
    const otp = document.querySelector('[style*="font-family"]').textContent.trim();
    navigator.clipboard.writeText(otp).then(() => {
      alert('Code copié !');
    });
  }
});
</script>
@endif



<div>
  <button class="btn btn-outline-secondary" onclick="window.location.href='/'">
    Retour à l'accueuil
  </button>
</div>

@if ($errors->any())
    <div class="alert alert-danger" style="position:fixed; top:20px; right:20px; z-index:9999;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div  class="container mt-5">
<p> Bienvenue veuillez choisir votre profil</p>

 <button onclick="openForm('entreprise')" id="toggleEntrepriseBtn" type="button" class="btn btn-primary" aria-expanded="false">
    Entreprise
</button>

  <button onclick="openForm('association')" id="toggleAssociationBtn" type="button" class="btn btn-primary" aria-expanded="false">
    Association
  </button>
</div>

<!-- Formulaire de réservation d'entreprise  -->
  <div id="entrepriseForm" class="d-none mt-3" data-target="entrepriseForm">
    <div class="card shadow-sm">
      <div class="card-body">
        <h1 class="h4 mb-3">Réservation — Entreprise</h1>
        <form action="{{ route('reservations.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="nomSalle" value="{{ request('nomSalle') }}">
           @if(request('nomSalle'))
              <div class="mb-3">
                <label for="salleSelectionnee" class="form-label">Salle sélectionnée</label>
                <div class="form-control-plaintext">{{ request('nomSalle') }}</div>
              </div>
            @endif

          <div class="mb-3">
            <label for="nomEntreprise" class="form-label">Nom de l'entreprise</label>
            <input
              type="text"
              name="nomEntreprise"
              id="nomEntreprise"
              class="form-control {{ $errors->has('nomEntreprise') ? 'is-invalid' : '' }}"
              value="{{ old('nomEntreprise') }}"
              placeholder="Nom de l'entreprise"
              required
            >
            @if($errors->has('nomEntreprise'))
              <div class="invalid-feedback">{{ $errors->first('nomEntreprise') }}</div>
            @endif
          </div>

          <fieldset class="mb-3">
            <legend class="form-label mb-2">Type d'entreprise</legend>
            <div class="d-flex gap-3">
              <div class="form-check">
                <input required class="form-check-input" type="radio" name="forme" id="isSarl" value="sarl" {{ old('forme') === 'sarl' ? 'checked' : '' }}>
                <label class="form-check-label" for="isSarl">SARL</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="forme" id="isSas" value="sas" {{ old('forme') === 'sas' ? 'checked' : '' }}>
                <label class="form-check-label" for="isSas">SAS</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="forme" id="isSa" value="sa" {{ old('forme') === 'sa' ? 'checked' : '' }}>
                <label class="form-check-label" for="isSa">SA</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="forme" id="isEi" value="ei" {{ old('forme') === 'ei' ? 'checked' : '' }}>
                <label class="form-check-label" for="isEi">EI</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="forme" id="isEirl" value="eirl" {{ old('forme') === 'eirl' ? 'checked' : '' }}>
                <label class="form-check-label" for="isEirl">EIRL</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="forme" id="isEurl" value="eurl" {{ old('forme') === 'eurl' ? 'checked' : '' }}>
                <label class="form-check-label" for="isEurl">EURL</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="forme" id="isOther" value="other" {{ old('forme') === 'other' ? 'checked' : '' }}>
                <label class="form-check-label" for="isOther">Autre</label>
              </div>
            </div>
            @if($errors->has('forme'))
              <div class="text-danger small mt-1">{{ $errors->first('forme') }}</div>
            @endif
          </fieldset>

          <div class="row g-3">
            <div class="col-md-6">
              <label for="dateCreationE" class="form-label">Date de création</label>
              <input type="date" name="dateCreationE" id="dateCreationE" class="form-control {{ $errors->has('dateCreationE') ? 'is-invalid' : '' }}" value="{{ old('dateCreationE') }}" required>
              @if($errors->has('dateCreationE'))
                <div class="invalid-feedback">{{ $errors->first('dateCreationE') }}</div>
              @endif
          </div>

          <div class="col-md-6">
            <label for="paysE" class="form-label">Pays</label>
              <select name="pays" id="paysE" required class="form-control {{ $errors->has('pays') ? 'is-invalid' : '' }}">
                <option value="">Sélectionner un pays</option>
                @php $countries = ['Burkina Faso'=>'Burkina Faso','Ghana'=>'Ghana','Benin'=>'Benin','Togo'=>'Togo','Cote d\'Ivoire'=>'Cote d\'Ivoire']; @endphp
                @foreach($countries as $code => $label)
                <option value="{{ $code }}" {{ old('pays') === $code ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
              </select>
                @if($errors->has('pays')) <div class="invalid-feedback">{{ $errors->first('pays') }}</div> @endif
          </div>

          <div class="col-md-6">
            <label for="villeE" class="form-label">Ville</label>
              <select name="ville" id="villeE" required class="form-control {{ $errors->has('ville') ? 'is-invalid' : '' }}">
                <option value="">Sélectionner une ville</option>
              </select>
              @if($errors->has('ville')) <div class="invalid-feedback">{{ $errors->first('ville') }}</div> @endif
          </div>

            <div class="col-md-6">
              <label for="telephoneE" class="form-label">Contact téléphonique</label>
              <input type="tel" name="telephoneE" id="telephoneE" class="form-control {{ $errors->has('telephoneE') ? 'is-invalid' : '' }}" value="{{ old('telephoneE') }}" placeholder="+243 99 000 0000" pattern="[\d +()-]+" maxlength="20" required>
              @if($errors->has('telephoneE'))
                <div class="invalid-feedback">{{ $errors->first('telephoneE') }}</div>
              @endif
            </div>

            <div class="col-12">
              <label for="adresseCompleteE" class="form-label">Adresse complète</label>
              <input type="text" name="adresseCompleteE" id="adresseCompleteE" class="form-control {{ $errors->has('adresseCompleteE') ? 'is-invalid' : '' }}" value="{{ old('adresseCompleteE') }}" placeholder="Rue, n°, quartier" required>
              @if($errors->has('adresseCompleteE'))
                <div class="invalid-feedback">{{ $errors->first('adresseCompleteE') }}</div>
              @endif
            </div>

            <div class="col-md-6">
              <label for="adressePostaleE" class="form-label">Adresse postale</label>
              <input type="text" name="adressePostaleE" id="adressePostaleE" class="form-control {{ $errors->has('adressePostaleE') ? 'is-invalid' : '' }}" value="{{ old('adressePostaleE') }}" placeholder="BP / Boîte postale" required>
              @if($errors->has('adressePostaleE'))
                <div class="invalid-feedback">{{ $errors->first('adressePostaleE') }}</div>
              @endif
            </div>

            <div class="col-md-6">
              <label for="rccm" class="form-label">RCCM</label>
              <input type="text" name="rccm" id="rccm" class="form-control {{ $errors->has('rccm') ? 'is-invalid' : '' }}" value="{{ old('rccm') }}" placeholder="RCCM" required>
              @if($errors->has('rccm'))
                <div class="invalid-feedback">{{ $errors->first('rccm') }}</div>
              @endif
            </div>

            <div class="col-md-6">
              <label for="ifu" class="form-label">IFU</label>
              <input type="text" name="ifu" id="ifu" class="form-control {{ $errors->has('ifu') ? 'is-invalid' : '' }}" value="{{ old('ifu') }}" placeholder="IFU" required>
              @if($errors->has('ifu'))
                <div class="invalid-feedback">{{ $errors->first('ifu') }}</div>
              @endif
            </div>

            <div class="col-md-6">
              <label for="reservation_date" class="form-label">Date souhaitée</label>
              <input type="date" name="reservation_date" id="reservation_date" class="form-control {{ $errors->has('reservation_date') ? 'is-invalid' : '' }}" value="{{ old('reservation_date') }}" required>
              @if($errors->has('reservation_date')) <div class="invalid-feedback">{{ $errors->first('reservation_date') }}</div> @endif
            </div>

            <div class="col-12">
              <label class="form-label">Choisir un créneau</label>
              <div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="reservation_time" id="slot1" value="08:00-12:00" {{ old('reservation_time')=='08:00-12:00' ? 'checked' : '' }} required>
                  <label class="form-check-label" for="slot1">08:00 — 12:00</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="reservation_time" id="slot2" value="13:30-18:30" {{ old('reservation_time')=='13:30-18:30' ? 'checked' : '' }}>
                  <label class="form-check-label" for="slot2">13:30 — 18:30</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="reservation_time" id="slot3" value="08:00-18:30" {{ old('reservation_time')=='08:00-18:30' ? 'checked' : '' }}>
                  <label class="form-check-label" for="slot3">08:00 — 18:30</label>
                </div>
                @if($errors->has('reservation_time')) <div class="text-danger small mt-1">{{ $errors->first('reservation_time') }}</div> @endif
              </div>
            </div>

            <div>
              <label for="autorisationMairieE">Autorisation Mairie :</label>
            <input type="file" name="autorisationMairieE" id="autorisationMairieE" accept="application/pdf" required>
            </div>

            <div>
              <label for="documentForceE">Document Force :</label>
            <input type="file" name="documentForceE" id="documentForceE" accept="application/pdf" required>
            </div>

          </div>

          <div class="mt-4 d-flex justify-content-between">
            <button type="button" id="closeEntrepriseBtn" class="btn btn-outline-secondary">Fermer</button>
            <button type="submit" class="btn btn-primary">Envoyer la demande</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<!-- Formulaire de réservation d'association  -->
    <div id="associationForm" class="d-none mt-3" data-target="associationForm">
    <div class="card shadow-sm">
      <div class="card-body">
        <h1 class="h4 mb-3">Réservation — Association</h1>
        <form action="{{ route('reservations.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
            <input type="hidden" name="nomSalle" value="{{ request('nomSalle') }}">

            @if(request('nomSalle'))
              <div class="mb-3">
                <label for="salleSelectionnee" class="form-label">Salle sélectionnée</label>
                <div class="form-control-plaintext">{{ request('nomSalle') }}</div>
              </div>
            @endif

          <div class="mb-3">
            <label for="nomAssociation" class="form-label">Nom de l'association</label>
            <input
              type="text"
              name="nomAssociation"
              id="nomAssociation"
              class="form-control {{ $errors->has('nomAssociation') ? 'is-invalid' : '' }}"
              value="{{ old('nomAssociation') }}"
              placeholder="Nom de l'association"
              required
            >
            @if($errors->has('nomAssociation'))
              <div class="invalid-feedback">{{ $errors->first('nomAssociation') }}</div>
            @endif
          </div>

          <fieldset class="mb-3">
            <legend class="form-label mb-2">Type d'association</legend>
            <div class="d-flex gap-3">
              <div class="form-check">
                <input required class="form-check-input" type="radio" name="forme" id="isOther" value="other" {{ old('forme') === 'other' ? 'checked' : '' }}>
                <label class="form-check-label" for="isOther">Autre</label>
              </div>
            </div>
            @if($errors->has('forme'))
              <div class="text-danger small mt-1">{{ $errors->first('forme') }}</div>
            @endif
          </fieldset>

          <div class="row g-3">
            <div class="col-md-6">
              <label for="dateCreationA" class="form-label">Date de création</label>
              <input type="date" name="dateCreationA" id="dateCreationA" required class="form-control {{ $errors->has('dateCreationA') ? 'is-invalid' : '' }}" value="{{ old('dateCreationA') }}">
              @if($errors->has('dateCreationA'))
                <div class="invalid-feedback">{{ $errors->first('dateCreationA') }}</div>
              @endif
            </div>

             <div class="col-12">
              <label for="adresseCompleteA" class="form-label">Adresse complète</label>
              <input type="text" name="adresseCompleteA" id="adresseCompleteA" required class="form-control {{ $errors->has('adresseCompleteA') ? 'is-invalid' : '' }}" value="{{ old('adresseCompleteA') }}" placeholder="Rue, n°, quartier">
              @if($errors->has('adresseCompleteA'))
                <div class="invalid-feedback">{{ $errors->first('adresseCompleteA') }}</div>
              @endif
            </div>

            <div class="col-md-6">
            <label for="paysA" class="form-label">Pays</label>
              <select name="pays" id="paysA" required class="form-control {{ $errors->has('pays') ? 'is-invalid' : '' }}">
                <option value="">Sélectionner un pays</option>
                @php $countries = ['Burkina Faso'=>'Burkina Faso','Ghana'=>'Ghana','Benin'=>'Benin','Togo'=>'Togo','Cote d\'Ivoire'=>'Cote d\'Ivoire']; @endphp
                @foreach($countries as $code => $label)
                <option value="{{ $code }}" {{ old('pays') === $code ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
              </select>
                @if($errors->has('pays')) <div class="invalid-feedback">{{ $errors->first('pays') }}</div> @endif
          </div>

            <div class="col-md-6">
            <label for="villeA" class="form-label">Ville</label>
              <select name="ville" id="villeA" required class="form-control {{ $errors->has('ville') ? 'is-invalid' : '' }}">
                <option value="">Sélectionner une ville</option>
              </select>
              @if($errors->has('ville')) <div class="invalid-feedback">{{ $errors->first('ville') }}</div> @endif
          </div>

            <div class="col-md-6">
              <label for="telephoneA" class="form-label">Contact téléphonique</label>
              <input type="tel" name="telephoneA" id="telephoneA" required class="form-control {{ $errors->has('telephoneA') ? 'is-invalid' : '' }}" value="{{ old('telephoneA') }}" placeholder="+243 99 000 0000" pattern="[\d +()-]+" maxlength="20">
              @if($errors->has('telephoneA'))
                <div class="invalid-feedback">{{ $errors->first('telephoneA') }}</div>
              @endif
            </div>

            <div class="col-md-6">
              <label for="adressePostaleA" class="form-label">Adresse postale</label>
              <input type="text" name="adressePostaleA" id="adressePostaleA" required class="form-control {{ $errors->has('adressePostaleA') ? 'is-invalid' : '' }}" value="{{ old('adressePostaleA') }}" placeholder="BP / Boîte postale">
              @if($errors->has('adressePostaleA'))
                <div class="invalid-feedback">{{ $errors->first('adressePostaleA') }}</div>
              @endif
            </div>

            <div class="col-md-6">
              <label for="email" class="form-label">Adresse email</label>
              <input type="email" name="email" id="email" required class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" placeholder="Email">
              @if($errors->has('email'))
                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
              @endif
            </div>

            <div class="col-md-6">
              <label for="recepisse" class="form-label">Recepisse</label>
              <input type="text" name="recepisse" id="recepisse" required class="form-control {{ $errors->has('recepisse') ? 'is-invalid' : '' }}" value="{{ old('recepisse') }}" placeholder="Numéro de reçu">
              @if($errors->has('recepisse'))
                <div class="invalid-feedback">{{ $errors->first('recepisse') }}</div>
              @endif
            </div>

            <div class="col-md-6">
              <label for="reservation_date" class="form-label">Date souhaitée</label>
              <input type="date" name="reservation_date" id="reservation_date" class="form-control {{ $errors->has('reservation_date') ? 'is-invalid' : '' }}" value="{{ old('reservation_date') }}" required>
              @if($errors->has('reservation_date')) <div class="invalid-feedback">{{ $errors->first('reservation_date') }}</div> @endif
            </div>

            <div class="col-12">
              <label class="form-label">Choisir un créneau</label>
              <div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="reservation_time" id="slot1A" value="08:00-12:00" {{ old('reservation_time')=='08:00-12:00' ? 'checked' : '' }} required>
                  <label class="form-check-label" for="slot1A">08:00 — 12:00</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="reservation_time" id="slot2A" value="13:30-18:30" {{ old('reservation_time')=='13:30-18:30' ? 'checked' : '' }}>
                  <label class="form-check-label" for="slot2A">13:30 — 18:30</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="reservation_time" id="slot3A" value="08:00-18:30" {{ old('reservation_time')=='08:00-18:30' ? 'checked' : '' }}>
                  <label class="form-check-label" for="slot3A">08:00 — 18:30</label>
                </div>
                @if($errors->has('reservation_time')) <div class="text-danger small mt-1">{{ $errors->first('reservation_time') }}</div> @endif
              </div>
            </div>

            <div>
              <label for="autorisationMairieA">Autorisation Mairie :</label>
            <input type="file" name="autorisationMairieA" id="autorisationMairieA" accept="application/pdf" required>
            </div>

            <div>
              <label for="documentForceA">Document Force :</label>
            <input type="file" name="documentForceA" id="documentForceA" accept="application/pdf" required>
            </div>

          </div>

          <div class="mt-4 d-flex justify-content-between">
            <button type="button" id="closeAssociationBtn" class="btn btn-outline-secondary">Fermer</button>
            <button type="submit" class="btn btn-primary">Envoyer la demande</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!--Script d'apparition du formulaire d'entreprise  -->
<script>
  // Fonction globale pour ouvrir un seul formulaire à la fois
  function openForm(name) {
    const eForm = document.getElementById('entrepriseForm');
    const aForm = document.getElementById('associationForm');
    const eBtn = document.getElementById('toggleEntrepriseBtn');
    const aBtn = document.getElementById('toggleAssociationBtn');

    if (name === 'entreprise') {
      if (eForm) eForm.classList.remove('d-none');
      if (aForm) aForm.classList.add('d-none');
      if (eBtn) eBtn.setAttribute('aria-expanded', 'true');
      if (aBtn) aBtn.setAttribute('aria-expanded', 'false');
      const first = eForm ? eForm.querySelector('input,select,textarea,button') : null;
      if (first) first.focus();
    }

    if (name === 'association') {
      if (aForm) aForm.classList.remove('d-none');
      if (eForm) eForm.classList.add('d-none');
      if (aBtn) aBtn.setAttribute('aria-expanded', 'true');
      if (eBtn) eBtn.setAttribute('aria-expanded', 'false');
      const first = aForm ? aForm.querySelector('input,select,textarea,button') : null;
      if (first) first.focus();
    }
  }

  (function(){
    const btn = document.getElementById('toggleEntrepriseBtn');
    const formWrap = document.getElementById('entrepriseForm');
    const closeBtn = document.getElementById('closeEntrepriseBtn');

    function toggle(open) {
      const isOpen = typeof open === 'boolean' ? open : formWrap.classList.contains('d-none');
      formWrap.classList.toggle('d-none', !isOpen);
      btn.setAttribute('aria-expanded', String(isOpen));
      if (isOpen) {
        // fermer l'autre formulaire quand on ouvre celui-ci
        const other = document.getElementById('associationForm');
        const otherBtn = document.getElementById('toggleAssociationBtn');
        if (other) other.classList.add('d-none');
        if (otherBtn) otherBtn.setAttribute('aria-expanded', 'false');

        const first = formWrap.querySelector('input,select,textarea,button');
        if (first) first.focus();
      } else {
        btn.focus();
      }
    }
    btn.addEventListener('click', () => toggle(true));
    if (closeBtn) closeBtn.addEventListener('click', () => toggle(false));

    // fermer au Escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && !formWrap.classList.contains('d-none')) toggle(false);
    });
  })();
</script>

  <!--Script d'apparition du formulaire d'association --> 
<script>
  (function(){
    const btn = document.getElementById('toggleAssociationBtn');
    const formWrap = document.getElementById('associationForm');
    const closeBtn = document.getElementById('closeAssociationBtn');

    function toggle(open) {
      const isOpen = typeof open === 'boolean' ? open : formWrap.classList.contains('d-none');
      formWrap.classList.toggle('d-none', !isOpen);
      btn.setAttribute('aria-expanded', String(isOpen));
      if (isOpen) {
        // fermer l'autre formulaire quand on ouvre celui-ci
        const other = document.getElementById('entrepriseForm');
        const otherBtn = document.getElementById('toggleEntrepriseBtn');
        if (other) other.classList.add('d-none');
        if (otherBtn) otherBtn.setAttribute('aria-expanded', 'false');

        const first = formWrap.querySelector('input,select,textarea,button');
        if (first) first.focus();
      } else {
        btn.focus();
      }
    }
    btn.addEventListener('click', () => toggle(true));
    if (closeBtn) closeBtn.addEventListener('click', () => toggle(false));

    // fermer au Escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && !formWrap.classList.contains('d-none')) toggle(false);
    });
  })();
</script>

<!--Script de gestion des pays et villes -->
  <script>
  const cityMap = {
    'Burkina Faso': ['Ouagadougou','Bobo-Dioulasso'],
    'Ghana': ['Accra','Kumasi','Tamale'],
    'Benin': ['Cotonou','Porto-Novo','Saint-Georges'],
    'Togo': ['Lomé','Sokodé','Kara'],
    'Cote d\'Ivoire': ['Abidjan','Yamoussoukro','Bouaké']
  };

  function populateCitiesFor(paysId, villeId, selectedVille) {
    const pays = document.getElementById(paysId);
    const ville = document.getElementById(villeId);
    if (!pays || !ville) return;
    function fill(selected) {
      ville.innerHTML = '<option value="">Sélectionner une ville</option>';
      const list = cityMap[selected] || [];
      list.forEach(c => {
        const o = document.createElement('option');
        o.value = c; o.textContent = c;
        if (selectedVille && selectedVille === c) o.selected = true;
        ville.appendChild(o);
      });
    }
    pays.addEventListener('change', () => fill(pays.value));
    // init
    fill(pays.value);
  }

  document.addEventListener('DOMContentLoaded', () => {
    populateCitiesFor('paysE','villeE', @json(old('ville')));
    populateCitiesFor('paysA','villeA', @json(old('ville')));
  });
</script>

<!--Script de la sidebar -->
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

</main>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>