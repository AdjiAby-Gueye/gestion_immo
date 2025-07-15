<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Etatcontrat extends Model
{
    public $table = "etatcontrats";

    public function Contrats()
    {
        return $this->hasMany(Contrat::class);
    }



}
