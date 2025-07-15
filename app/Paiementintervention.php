<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paiementintervention extends Model
{
    //

    protected $table = 'paiementinterventions';


    public function Factureintervention()
    {
        return $this->belongsTo(Factureintervention::class);
    }

    public function Modepaiement()
    {
        return $this->belongsTo(Modepaiement::class);
    }


}
