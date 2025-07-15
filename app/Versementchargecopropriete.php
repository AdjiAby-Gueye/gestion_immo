<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Versementchargecopropriete extends Model
{
    public $table = "versementchargecoproprietes";


    public function Proprietaire()
    {
        return $this->belongsTo(Proprietaire::class);
    }

    public function Contrat()
    {
        return $this->belongsTo(Contrat::class);
    }


}
