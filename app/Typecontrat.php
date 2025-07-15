<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Typecontrat extends Model
{
    public $table = "typecontrats";


    public function Contrats()
    {
        return $this->hasMany(Contrat::class);
    }
}
