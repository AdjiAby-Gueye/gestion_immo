<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contactprestataire extends Model
{
    public $table = "contactprestataires";


    public function Prestataire()
    {
        return $this->belongsTo(Prestataire::class);
    }

}
