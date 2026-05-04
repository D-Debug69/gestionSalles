<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pays extends Model
{
     protected $fillable = ['nom'];

    // Une pays a plusieurs villes
    public function villes(): HasMany
    {
        return $this->hasMany(Ville::class);
    }
}
