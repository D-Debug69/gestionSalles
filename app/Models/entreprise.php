<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\ComparesCastableAttributes;
use Illuminate\Database\Eloquent\Model;

class entreprise extends Model
{
    protected $fillable = [
        'nomEntreprise',
        'typeEntreprise',
        'dateCreationE',
        'adresseCompleteE',
        'pays',
        'ville',
        'telephoneE',
        'adressePostaleE',
        'rccm',
        'ifu',
        'autorisationMairieE',
        'documentForceE'
        
    ];
    
public function reservation(){
    return $this->hasOne(ReservationSalles::class);
}

}