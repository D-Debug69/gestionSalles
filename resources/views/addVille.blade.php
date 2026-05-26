<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter une Ville et ses Salles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .salle-form { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .btn-add-salle { margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Ajouter une Ville et ses Salles</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('ville.store') }}" id="villeForm" enctype="multipart/form-data">
            @csrf

            <!-- Section Ville -->
            <fieldset class="mb-4 p-3 border rounded">
                <legend class="fs-5 fw-bold">Informations de la Ville</legend>

                <div class="mb-3">
                    <label for="pays_id" class="form-label">Pays</label>
                    <select class="form-control" id="pays_id" name="pays_id" required>
                        <option value="">-- Sélectionne un pays --</option>
                        @foreach($pays as $p)
                            <option value="{{ $p->id }}" {{ old('pays_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="nom_ville" class="form-label">Nom de la Ville</label>
                    <input type="text" class="form-control" id="nom_ville" name="nom" value="{{ old('nom') }}" required>
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

                <button type="button" class="btn btn-secondary btn-add-salle" id="addSalleBtn">Ajouter une Salle</button>
            </fieldset>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Créer</button>
                <a href="{{ route('allSallesView') }}" class="btn btn-secondary">Retour</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let salleCount = 0;

        
        function addSalleForm() {
    salleCount++;
    const html = `
        <div class="salle-form" id="salle-${salleCount}">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label for="salle_nom_${salleCount}" class="form-label">Nom de la Salle</label>
                    <input type="text" class="form-control" name="salles[${salleCount}][nom]" id="salle_nom_${salleCount}" placeholder="Ex: Salle A" required>
                </div>
                <div class="col-md-2">
                    <label for="salle_capacite_${salleCount}" class="form-label">Capacité</label>
                    <input type="number" class="form-control" name="salles[${salleCount}][capacite]" id="salle_capacite_${salleCount}" placeholder="Ex: 50" min="1">
                </div>
                
                <div class="col-md-2">
    <label for="salles[${salleCount}][prix_matin]" class="form-label">Prix matin</label>
    <input type="number" class="form-control" name="salles[${salleCount}][prix_matin]" step="0.01" min="0">
</div>
<div class="col-md-2">
    <label for="salles[${salleCount}][prix_apres_midi]" class="form-label">Prix après-midi</label>
    <input type="number" class="form-control" name="salles[${salleCount}][prix_apres_midi]" step="0.01" min="0">
</div>
<div class="col-md-2">
    <label for="salles[${salleCount}][prix_journee]" class="form-label">Prix journée</label>
    <input type="number" class="form-control" name="salles[${salleCount}][prix_journee]" step="0.01" min="0">
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

        function removeSalleForm(id) {
            const elem = document.getElementById(`salle-${id}`);
            if (elem) elem.remove();
        }

        document.getElementById('addSalleBtn').addEventListener('click', addSalleForm);

        // Ajoute une salle par défaut au chargement
        addSalleForm();
    </script>
</body>
</html>