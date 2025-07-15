<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detailpaiement extends Model
{
    //
    public $table = "detailpaiements";

    public function paiementLoyer() {
        return $this->belongsTo(Paiementloyer::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }


}
