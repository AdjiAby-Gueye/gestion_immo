<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membreequipegestion extends Model
{
    public $table = "membreequipegestions";

    public function Equipegestions()
    {
        return $this->belongsToMany(Equipegestion::class);
    }
    public function Fonctions()
    {
        return $this->belongsToMany(Fonction::class);
    }

    public function Interventions()
    {
        return $this->belongsToMany(Intervention::class);
    }
    public function Demandeinterventions()
    {
        return $this->hasMany(Demandeintervention::class);
    }
}
