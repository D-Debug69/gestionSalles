<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salle extends Model
{
    protected $fillable = ['nom', 'capacite', 'equipements', 'prix', 'ville_id','statut', 'image'];

    public function ville(): BelongsTo
    {
        return $this->belongsTo(Ville::class);
    }
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
    public function getPrixFormatAttribute()
    {
        return $this->prix ? number_format($this->prix, 2) . ' XOF' : null;
    }
}
