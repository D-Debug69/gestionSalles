<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Réservation Entreprise-Association</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
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
        <div id="otpCode" style="font-size: 3rem; font-weight: bold; color: #28a745; text-align: center; padding: 2rem; border: 3px solid #28a745; border-radius: 10px; background: #f8f9fa; letter-spacing: 5px; font-family: 'Courier New', monospace;">
          {{ session('otp') }}
        </div>
        <p class="mt-4 text-muted">
          <small>Ce modal se fermera automatiquement dans <span id="countdown">10</span> secondes</small>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="manualCloseBtn">Fermer</button>
        <button type="button" class="btn btn-primary" onclick="downloadOtp()">Télécharger le code</button>
      </div>
    </div>
  </div>
</div>

@if(session('otp'))
<script>
  sessionStorage.removeItem('otpModalShown');
</script>
@endif

<script>
  function downloadOtp() {
    const otp = document.getElementById('otpCode')?.textContent.trim();
    if (!otp) return;

    const content = `Votre code OTP : ${otp}\n`;
    const blob = new Blob([content], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);

    const a = document.createElement('a');
    a.href = url;
    a.download = `otp-${otp}.txt`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);

    URL.revokeObjectURL(url);
  }

  function hideOtpModal() {
    const otpModalEl = document.getElementById('otpModal');
    if (!otpModalEl) return;

    const modalInstance = bootstrap.Modal.getInstance(otpModalEl);
    if (modalInstance) {
      modalInstance.hide();
    } else {
      otpModalEl.classList.remove('show');
      otpModalEl.style.display = 'none';
    }
  }

  window.addEventListener('load', () => {
    const otpModalEl = document.getElementById('otpModal');
    if (!otpModalEl) return;

    if (sessionStorage.getItem('otpModalShown')) {
      hideOtpModal();
      return;
    }

    sessionStorage.setItem('otpModalShown', '1');

    const modal = new bootstrap.Modal(otpModalEl, {
      backdrop: 'static',
      keyboard: false
    });
    modal.show();

    let seconds = 10;
    const countdownEl = document.getElementById('countdown');
    const interval = setInterval(() => {
      seconds--;
      if (countdownEl) countdownEl.textContent = seconds;
      if (seconds <= 0) {
        clearInterval(interval);
        modal.hide();
      }
    }, 1000);

    document.getElementById('closeModalBtn')?.addEventListener('click', () => {
      clearInterval(interval);
      modal.hide();
    });

    document.getElementById('manualCloseBtn')?.addEventListener('click', () => {
      clearInterval(interval);
      modal.hide();
    });
  });

  window.addEventListener('pageshow', (event) => {
    if (event.persisted || sessionStorage.getItem('otpModalShown')) {
      hideOtpModal();
    }
  });
</script>
@endif

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

 <button onclick="openForm('entreprise')" id="toggleEntrepriseBtn" type="button" class="btn btn-primary btn-lg" aria-expanded="false">
    Entreprise
</button>

  <button onclick="openForm('association')" id="toggleAssociationBtn" type="button" class="btn btn-primary btn-lg" aria-expanded="false">
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
                <input type="date" name="reservation_date" id="reservation_date" class="form-control {{ $errors->has('reservation_date') ? 'is-invalid' : '' }}" value="{{ old('reservation_date') }}" min="{{ now()->addDays(7)->format('Y-m-d') }}"required>
              @if($errors->has('reservation_date')) 
                <div class="invalid-feedback">{{ $errors->first('reservation_date') }}</div> 
              @endif
            </div>

            <div class="col-12">
              <label class="form-label">Choisir un créneau</label>
              <div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="reservation_time" id="slot1" value="07:00-14:00" {{ old('reservation_time')=='07:00-14:00' ? 'checked' : '' }} required>
                  <label class="form-check-label" for="slot1">07:00 — 14:00</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="reservation_time" id="slot2" value="14:00-21:00" {{ old('reservation_time')=='14:00-21:00' ? 'checked' : '' }}>
                  <label class="form-check-label" for="slot2">14:00 — 21:00</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="reservation_time" id="slot3" value="07:00-21:00" {{ old('reservation_time')=='07:00-21:00' ? 'checked' : '' }}>
                  <label class="form-check-label" for="slot3">07:00 — 21:00</label>
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
                <input type="date" name="reservation_date" id="reservation_date" class="form-control {{ $errors->has('reservation_date') ? 'is-invalid' : '' }}" value="{{ old('reservation_date') }}" min="{{ now()->addDays(7)->format('Y-m-d') }}"required>
              @if($errors->has('reservation_date')) 
                <div class="invalid-feedback">{{ $errors->first('reservation_date') }}</div> 
              @endif
            </div>

            <div class="col-12">
              <label class="form-label">Choisir un créneau</label>
              <div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="reservation_time" id="slot1A" value="07:00-14:00" {{ old('reservation_time')=='07:00-14:00' ? 'checked' : '' }} required>
                  <label class="form-check-label" for="slot1A">07:00 — 14:00</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="reservation_time" id="slot2A" value="14:00-21:00" {{ old('reservation_time')=='14:00-21:00' ? 'checked' : '' }}>
                  <label class="form-check-label" for="slot2A">14:00 — 21:00</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="reservation_time" id="slot3A" value="07:00-21:00" {{ old('reservation_time')=='07:00-21:00' ? 'checked' : '' }}>
                  <label class="form-check-label" for="slot3A">07:00 — 21:00</label>
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

</main>
<br>
<br>
<br>


<footer class="footer py-4">
    <div class="container text-center">
      <p class="mb-0">© 2026 GestionSalles — Portail CBC</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>