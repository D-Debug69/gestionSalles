<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salle extends Model
{
    protected $fillable = ['nom', 'capacite', 'equipements','prix_matin', 'prix_apres_midi', 'prix_journee', 'ville_id','statut', 'image'];


public function priceForSlot(string $slot)
{
    return match ($slot) {
        '07:00-14:00' => $this->prix_matin ?? $this->prix ?? 0,
        '14:00-21:00' => $this->prix_apres_midi ?? $this->prix ?? 0,
        '07:00-21:00' => $this->prix_journee ?? $this->prix ?? 0,
        default => $this->prix ?? 0,
    };
}

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
