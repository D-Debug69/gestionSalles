<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter un Pays</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Ajouter un Pays</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pays.store') }}">
            @csrf
            <div class="mb-3">
                <label for="nom" class="form-label">Nom du Pays</label>
                <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <a href="{{ route('allSallesView') }}" class="btn btn-secondary">Retour</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>