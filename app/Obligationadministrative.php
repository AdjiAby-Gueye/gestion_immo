<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obligationadministrative extends Model
{
    public $table = "obligationadministratives";


    public function Immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    public function Appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function Typeobligationadministrative()
    {
        return $this->belongsTo(Typeobligationadministrative::class);
    }


}
