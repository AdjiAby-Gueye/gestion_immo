<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historiquerelance extends Model
{
    public $table = "historiquerelances";

    public $fillable = [
        "contrat_id",
        "locataire_id",
        "user_id",
        "inbox_id",
        "avisecheance_id",
        "facturelocation_id"
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }
    public function contrat()
    {
        return $this->belongsTo(Contrat::class);
    }
    public function inbox()
    {
        return $this->belongsTo(Inbox::class);
    }

    public function avisecheance()
    {
        return $this->belongsTo(Avisecheance::class);
    }

    public function facturelocation()
    {
        return $this->belongsTo(Facturelocation::class);
    }
}
