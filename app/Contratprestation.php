<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contratprestation extends Model
{
    public $table = "contratprestations";

    public function Prestataire()
    {
        return $this->belongsTo(Prestataire::class);
    }

    public function Frequencepaiementappartement()
    {
        return $this->belongsTo(Frequencepaiementappartement::class);
    }

    public function Categorieprestation()
    {
        return $this->belongsTo(Categorieprestation::class);
    }

}
