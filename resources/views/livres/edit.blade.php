<h2>Modifier le livre</h2>

<form method="POST" action="{{ route('livres.update', $livre) }}">
    @csrf
    @method('PUT')

    <div>
        <label>Titre</label>
        <input
            type="text"
            name="titre"
            value="{{ old('titre', $livre->titre) }}"
        >
    </div>

    <div>
        <label>ISBN</label>
        <input
            type="text"
            name="isbn"
            value="{{ old('isbn', $livre->isbn) }}"
        >
    </div>

    <div>
        <label>Nombre d'exemplaires</label>
        <input
            type="number"
            min="0"
            name="nombre_exemplaires"
            value="{{ old('nombre_exemplaires', $livre->nombre_exemplaires) }}"
        >
        <div>
            Disponibles: {{ (int)($livre->exemplaires_disponibles ?? 0) }}
        </div>
    </div>

    <div>
        <label>Résumé</label>
        <textarea name="resume">{{ old('resume', $livre->resume) }}</textarea>
    </div>

    <div>
        <label>Catégorie</label>
        <select name="categorie_id">
            @foreach($categories as $cat)
                <option
                    value="{{ $cat->id }}"
                    {{ $livre->categorie_id == $cat->id ? 'selected' : '' }}
                >
                    {{ $cat->libelle }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Auteurs</label><br>
        @foreach($auteurs as $auteur)
            <input
                type="checkbox"
                name="auteurs[]"
                value="{{ $auteur->id }}"
                {{ $livre->auteurs->contains($auteur->id) ? 'checked' : '' }}
            >
            {{ $auteur->nom }} <br>
        @endforeach
    </div>

    <button type="submit">Mettre à jour</button>
</form>
