<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ville extends Model
{
    protected $fillable = ['nom', 'pays_id', 'image'];

    public function pays(): BelongsTo
    {
        return $this->belongsTo(Pays::class);
    }
public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
    public function salles(): HasMany
    {
        return $this->hasMany(Salle::class);
    }
}
