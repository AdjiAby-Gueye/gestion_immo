<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appartement_locataire extends Model
{
    public $table = "appartement_locataire";

    public function Appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function Locataire()
    {
        return $this->belongsTo(Locataire::class);
    }
}
