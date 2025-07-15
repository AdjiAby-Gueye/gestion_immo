<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Securite extends Model
{
    public $table = "securites";

    public function immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    public function horaire()
    {
        return $this->belongsTo(Horaire::class);
    }
    public function prestataire()
    {
        return $this->belongsTo(Prestataire::class);
    }

}
