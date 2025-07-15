<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Demandeintervention extends Model
{
    public $table = "demandeinterventions";

    public function Appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function Delaipreavi()
    {
        return $this->belongsTo(Delaipreavi::class);
    }

    public function Locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    public function Typepiece()
    {
        return $this->belongsTo(Typepiece::class);
    }

    public function Membreequipegestion()
    {
        return $this->belongsTo(Membreequipegestion::class);
    }

    public function Immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }
    public function Interventions()
    {
        return $this->hasMany(Intervention::class);
    }

    // une demande d'intervention a une devi
    public function devi()
    {
        return $this->hasOne(Devi::class);
    }
}
