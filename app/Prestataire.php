<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prestataire extends Model
{
    public $table = "prestataires";

    public function Interventions()
    {
        return $this->hasMany(Intervention::class);
    }

    public function securites()
    {
        return $this->hasMany(Securite::class);
    }

    public function Contacts()
    {
        return $this->hasMany(Contactprestataire::class);
    }

    public function Assurances()
    {
        return $this->hasMany(Assurance::class);
    }

    public function Contratprestations()
    {
        return $this->hasMany(Contratprestation::class);
    }

    public function categorieprestataire()
    {
        return $this->belongsTo(Categorieprestataire::class);
    }
}
