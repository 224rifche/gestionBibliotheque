<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Afficher le formulaire de profil
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Mettre à jour le login et le mot de passe
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'login' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9]+$/',
                Rule::unique('users')->ignore($user->id)
            ],
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^&*(),.?":{}|<>]).*$/'
            ],
            'password_confirmation' => 'required|same:password',
        ], [
            'login.required' => 'Le login est obligatoire.',
            'login.unique' => 'Ce login existe déjà.',
            'login.regex' => 'Le login ne doit contenir que des lettres et des chiffres.',
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'password.required' => 'Le nouveau mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.regex' => 'Le mot de passe doit contenir des lettres, des chiffres et des caractères spéciaux.',
            'password_confirmation.required' => 'La confirmation du mot de passe est obligatoire.',
            'password_confirmation.same' => 'Les mots de passe ne correspondent pas.',
        ]);

        $user->login = $validated['login'];
        $user->password = Hash::make($validated['password']);
        $user->save();

        return Redirect::route('profile.edit')
            ->with('success', 'Profil mis à jour avec succès.');
    }
}
