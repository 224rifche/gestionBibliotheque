<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Afficher la liste des utilisateurs
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Enregistrer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'login' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[A-Za-z0-9]+$/',
                    'unique:users,login'
                ],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/^(?=.*[a-zA-Z])(?=.*[0-9]).*$/'
                ],
                'password_confirmation' => 'required|same:password',
                'role' => ['required', Rule::in(['Rlecteur', 'Rbibliothecaire', 'Radmin'])],
                'actif' => 'sometimes|boolean'
            ], [
                'login.required' => 'Le login est obligatoire.',
                'login.unique' => 'Ce login existe déjà.',
                'login.regex' => 'Le login ne doit contenir que des lettres et des chiffres.',
                'password.required' => 'Le mot de passe est obligatoire.',
                'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
                'password.regex' => 'Le mot de passe doit contenir au moins une lettre et un chiffre.',
                'password_confirmation.required' => 'La confirmation du mot de passe est obligatoire.',
                'password_confirmation.same' => 'Les mots de passe ne correspondent pas.',
                'role.required' => 'Le rôle est obligatoire.',
                'role.in' => 'Le rôle sélectionné est invalide.'
            ]);

            $user = User::create([
                'login' => $validated['login'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'actif' => $request->has('actif') ? true : false,
            ]);

            return redirect()->route('users.index')
                ->with('success', 'Utilisateur créé avec succès.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la création de l\'utilisateur : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'login' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9]+$/',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/^(?=.*[a-zA-Z])(?=.*[0-9]).*$/'
            ],
            'password_confirmation' => 'required_with:password|same:password',
            'role' => ['required', Rule::in(['Rlecteur', 'Rbibliothecaire', 'Radmin'])],
            'actif' => 'boolean'
        ], [
            'login.required' => 'Le login est obligatoire.',
            'login.unique' => 'Ce login existe déjà.',
            'login.regex' => 'Le login ne doit contenir que des lettres et des chiffres.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.regex' => 'Le mot de passe doit contenir au moins une lettre et un chiffre.',
            'password_confirmation.same' => 'Les mots de passe ne correspondent pas.',
            'role.required' => 'Le rôle est obligatoire.',
            'role.in' => 'Le rôle sélectionné est invalide.'
        ]);

        $user->login = $validated['login'];
        $user->role = $validated['role'];
        $user->actif = $request->has('actif') ? true : false;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Supprimer un utilisateur (soft delete ou hard delete selon le rôle)
     */
    public function destroy(User $user)
    {
        // Empêcher la suppression de soi-même
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}
