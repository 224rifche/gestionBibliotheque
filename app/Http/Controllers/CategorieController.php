<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Afficher la liste des catégories
     */
    public function index()
    {
        $categories = Categorie::orderBy('libelle')->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Enregistrer une nouvelle catégorie
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255|unique:categories,libelle',
        ], [
            'libelle.required' => 'Le libellé est obligatoire.',
            'libelle.unique' => 'Cette catégorie existe déjà.',
        ]);

        Categorie::create($validated);

        return redirect()->route('categories.create')
            ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Categorie $categorie)
    {
        return view('categories.edit', compact('categorie'));
    }

    /**
     * Mettre à jour une catégorie
     */
    public function update(Request $request, Categorie $categorie)
    {
        $validated = $request->validate([
            'libelle' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('categories')->ignore($categorie->id)
            ],
        ], [
            'libelle.required' => 'Le libellé est obligatoire.',
            'libelle.unique' => 'Cette catégorie existe déjà.',
        ]);

        $categorie->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Supprimer une catégorie
     */
    public function destroy(Categorie $categorie)
    {
        // Vérifier si la catégorie est utilisée
        if ($categorie->livres()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle est utilisée par des livres.');
        }

        $categorie->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}
