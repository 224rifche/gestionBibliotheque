<?php

namespace App\Http\Controllers;

use App\Models\Auteur;
use Illuminate\Http\Request;

class AuteurController extends Controller
{
    /**
     * Afficher la liste des auteurs
     */
    public function index()
    {
        $auteurs = Auteur::orderBy('nom')->get();
        return view('auteurs.index', compact('auteurs'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('auteurs.create');
    }

    /**
     * Enregistrer un nouvel auteur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
        ]);

        Auteur::create($validated);

        return redirect()->route('auteurs.create')
            ->with('success', 'Auteur créé avec succès.');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Auteur $auteur)
    {
        return view('auteurs.edit', compact('auteur'));
    }

    /**
     * Mettre à jour un auteur
     */
    public function update(Request $request, Auteur $auteur)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
        ]);

        $auteur->update($validated);

        return redirect()->route('auteurs.index')
            ->with('success', 'Auteur mis à jour avec succès.');
    }

    /**
     * Supprimer un auteur
     */
    public function destroy(Auteur $auteur)
    {
        // Vérifier si l'auteur est utilisé
        if ($auteur->livres()->count() > 0) {
            return redirect()->route('auteurs.index')
                ->with('error', 'Impossible de supprimer cet auteur car il est associé à des livres.');
        }

        $auteur->delete();

        return redirect()->route('auteurs.index')
            ->with('success', 'Auteur supprimé avec succès.');
    }
}
