<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Categorie;
use App\Models\Auteur;
use App\Models\Emprunt;
use Illuminate\Http\Request;

class LivreController extends Controller
{
    public function index()
    {
        $livres = Livre::with(['categorie', 'auteurs'])->get();
        return view('livres.index', compact('livres'));
    }

    public function catalogue(Request $request)
    {
        $query = Livre::query()
            ->with(['categorie', 'auteurs']);

        if ($request->filled('titre')) {
            $query->where('titre', 'like', '%'.$request->input('titre').'%');
        }

        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->input('categorie_id'));
        }

        if ($request->filled('auteur_id')) {
            $auteurId = $request->input('auteur_id');
            $query->whereHas('auteurs', function ($q) use ($auteurId) {
                $q->where('auteurs.id', $auteurId);
            });
        }

        $livres = $query->get();
        $categories = Categorie::all();
        $auteurs = Auteur::all();

        return view('catalogue', compact('livres', 'categories', 'auteurs'));
    }

    public function create()
    {
        $categories = Categorie::all();
        $auteurs = Auteur::all();
        return view('livres.create', compact('categories', 'auteurs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required',
            'isbn' => 'required|unique:livres',
            'resume' => 'nullable',
            'categorie_id' => 'required',
            'nombre_exemplaires' => 'required|integer|min:0',
        ]);

        $livre = Livre::create([
            'titre' => $validated['titre'],
            'isbn' => $validated['isbn'],
            'resume' => $validated['resume'] ?? null,
            'categorie_id' => $validated['categorie_id'],
            'nombre_exemplaires' => $validated['nombre_exemplaires'],
            'exemplaires_disponibles' => $validated['nombre_exemplaires'],
            'disponible' => $validated['nombre_exemplaires'] > 0,
        ]);

        $livre->auteurs()->attach($request->input('auteurs', []));

        return redirect()->route('livres.create')
            ->with('success', 'Livre créé avec succès.');
    }

    public function edit(Livre $livre)
    {
        $categories = Categorie::all();
        $auteurs = Auteur::all();
        return view('livres.edit', compact('livre', 'categories', 'auteurs'));
    }

    public function update(Request $request, Livre $livre)
    {
        $validated = $request->validate([
            'titre' => 'required',
            'isbn' => 'required|unique:livres,isbn,'.$livre->id,
            'resume' => 'nullable',
            'categorie_id' => 'required',
            'nombre_exemplaires' => 'required|integer|min:0',
        ]);

        $empruntsActifs = Emprunt::query()
            ->where('livre_id', $livre->id)
            ->whereNull('date_retour_effective')
            ->whereIn('statut', ['en_cours', 'en_retard'])
            ->count();

        if ($validated['nombre_exemplaires'] < $empruntsActifs) {
            return redirect()->back();
        }

        $exemplairesDisponibles = $validated['nombre_exemplaires'] - $empruntsActifs;

        $livre->update([
            'titre' => $validated['titre'],
            'isbn' => $validated['isbn'],
            'resume' => $validated['resume'] ?? null,
            'categorie_id' => $validated['categorie_id'],
            'nombre_exemplaires' => $validated['nombre_exemplaires'],
            'exemplaires_disponibles' => $exemplairesDisponibles,
            'disponible' => $exemplairesDisponibles > 0,
        ]);

        $livre->auteurs()->sync($request->input('auteurs', []));

        return redirect()->route('livres.index');
    }

    public function indisponible(Livre $livre)
    {
        $livre->update([
        'exemplaires_disponibles' => 0,
        'disponible' => false
    ]);

         return redirect()->route('livres.index')
             ->with('success', 'Livre rendu indisponible');
}

}

