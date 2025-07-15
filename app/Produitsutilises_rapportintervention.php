<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produitsutilises_rapportintervention extends Model
{
    public $table = "produitsutilises_rapportinterventions";


    public function Produitsutilise()
    {
        return $this->belongsTo(Produitsutilise::class);
    }
    public function Rapportintervention()
    {
        return $this->belongsTo(Rapportintervention::class);
    }
}
