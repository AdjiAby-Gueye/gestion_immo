<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipement_observation extends Model
{
    public $table = "equipement_observation";


    public function Equipement()
    {
        return $this->belongsTo(Equipementpiece::class);
    }
    public function Observation()
    {
        return $this->belongsTo(Observation::class);
    }
}
