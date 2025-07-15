<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produitsutilise extends Model
{
    public $table = "produitsutilises";


    public function Rapportinterventions()
    {
        return $this->belongsToMany(Rapportintervention::class);
    }

}
