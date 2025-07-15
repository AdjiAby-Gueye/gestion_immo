<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factureeaux extends Model
{
    // table factureeauxs

    public $table = "factureeauxs";
    


    public function contrat()
    {
        return $this->belongsTo(Contrat::class);
    }

    // public function paiementloyers()

    public function paiementloyer(){
        return $this->hasOne(Paiementloyer::class);
    }

}
