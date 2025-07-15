<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categorieintervention extends Model
{
    public $table = "categorieinterventions";


    public function Interventions()
    {
        return $this->hasMany(Intervention::class);
    }

    // une categorie d'intervention appartient plusier detaildevi

    public function detaildevi()
    {
        return $this->hasMany('App\Detaildevi');
    }

    // a plusieurs sous type d'intervention
    public function soustypeintervention()
    {
        return $this->hasMany(Soustypeintervention::class);
    }

    
   
}
