<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modepaiement extends Model
{
    //


    public function Paiementintervention()
    {
        return $this->hasOne(Paiementintervention::class, 'modepaiement_id');
    }
}
