<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipementpiece extends Model
{
    public $table = "equipementpieces";

    public function Etatlieu()
    {
        return $this->belongsTo(Etatlieu::class);
    }
    public function Observation()
    {
        return $this->belongsTo(Observation::class);
    }



}
