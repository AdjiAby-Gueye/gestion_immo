<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    public $table = "observations";

    public function Equipementpieces()
    {
        return $this->belongsToMany(Equipementpiece::class);
    }

    public function Constituantpieces()
    {
        return $this->belongsToMany(Constituantpiece::class);
    }



}
