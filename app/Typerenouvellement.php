<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Typerenouvellement extends Model
{
    public $table = "typerenouvellements";


    public function Contrats()
    {
        return $this->hasMany(Contrat::class);
    }


}
