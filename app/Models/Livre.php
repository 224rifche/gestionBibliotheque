<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livre extends Model
{
    public function auteurs()
    {
        return $this->belongsToMany(Auteur::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    protected $fillable = [
    'titre',
    'isbn',
    'resume',
    'categorie_id',
    'nombre_exemplaires',
    'exemplaires_disponibles',
    'disponible',
];

}

