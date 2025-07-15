<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Typeobligationadministrative extends Model
{
    public $table = "typeobligationadministratives";


    public function Obligationadministratives()
    {
        return $this->hasMany(Obligationadministrative::class);
    }


}
