<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class association extends Model
{
    protected $fillable = [
        'nomAssociation',
        'typeAssociation',
        'dateCreationA',
        'adresseCompleteA',
        'pays',
        'ville',
        'telephoneA',
        'adressePostaleA',
        'email',
        'recepisse',
        'autorisationMairieA',
        'documentForceA'
    ];

    public function reservation(){
    return $this->hasOne(ReservationSalles::class);
}

}
