<?php

return [
    // Textes d'indication pour les formulaires
    'hints' => [
        // Utilisateur
        'login' => 'Uniquement des lettres et des chiffres (ex: ETU202401)',
        'password' => 'Minimum 8 caractères avec au moins une lettre et un chiffre',
        'password_confirmation' => 'Répétez le mot de passe',
        'password_optional' => 'Si rempli : minimum 8 caractères avec au moins une lettre et un chiffre',
        'password_keep_current' => 'Laissez vide pour conserver l\'actuel',
        'role' => 'Sélectionnez un rôle pour l\'utilisateur',
        'actif' => 'Cochez pour activer le compte',
        
        // Auteur
        'auteur_nom' => 'Ex: Victor Hugo, Jules Verne...',
        
        // Catégorie
        'categorie_libelle' => 'Ex: Roman, Science-fiction, Histoire...',
        
        // Livre
        'livre_titre' => 'Titre complet du livre',
        'livre_isbn' => 'Numéro ISBN du livre',
        'livre_resume' => 'Résumé ou description du livre',
        'livre_exemplaires' => 'Nombre d\'exemplaires disponibles',
        'livre_disponible' => 'Indique si le livre est disponible',
        'livre_categorie' => 'Sélectionnez la catégorie du livre',
        'livre_auteurs' => 'Sélectionnez un ou plusieurs auteurs',
    ],
    
    // Placeholders
    'placeholders' => [
        'login' => 'Ex: ETU202401',
        'password' => 'Minimum 8 caractères',
        'password_confirmation' => 'Répétez le mot de passe',
        'password_optional' => 'Laissez vide pour conserver l\'actuel',
        'password_confirmation_optional' => 'Répétez le nouveau mot de passe',
        'auteur_nom' => 'Ex: Victor Hugo, Jules Verne...',
        'categorie_libelle' => 'Ex: Roman, Science-fiction, Histoire...',
    ],
];
