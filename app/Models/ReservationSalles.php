<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ReservationSalles extends Model
{
    protected $fillable = [
        'statut',
        'otp',
        'nomSalle',
        'reservation_date',
        'start_time',
        'end_time',
        'dateInscription',
        'dateEmission',
        'dateTraitement',
        'motifRejet',
        'pdf_path',
        'nom_demandeur',
        'telephone',
        'email',
        'details',
        'user_id',
        'entreprise_id',
        'association_id',
        'approved_cc',
        'approved_cc_by',
        'approved_cc_at',
        'approved_dfc',
        'approved_dfc_by',
        'approved_dfc_at',
        'approved_dg',
        'approved_dg_by',
        'approved_dg_at',
        'approved_admin',
        'approved_admin_by',
        'approved_admin_at',
    ];

    protected $casts = [
        'dateInscription' => 'datetime',
        'dateEmission' => 'datetime',
        'dateTraitement' => 'datetime',
        'reservation_date' => 'date',
    ];


    public function salle()
    {
        return $this->belongsTo(Salle::class, 'nomSalle', 'nom');
    }

    public function entreprise()
    {
        return $this->belongsTo(entreprise::class);
    }

    public function association()
    {
        return $this->belongsTo(association::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvedCcBy()
    {
        return $this->belongsTo(User::class, 'approved_cc_by');
    }

    public function approvedDfcBy()
    {
        return $this->belongsTo(User::class, 'approved_dfc_by');
    }

    public function approvedDgBy()
    {
        return $this->belongsTo(User::class, 'approved_dg_by');
    }

    public function approvedAdminBy()
    {
        return $this->belongsTo(User::class, 'approved_admin_by');
    }
}