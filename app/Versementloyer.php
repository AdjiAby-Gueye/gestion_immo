<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Versementloyer extends Model
{
    public $table = "versementloyers";


    public function Proprietaire()
    {
        return $this->belongsTo(Proprietaire::class);
    }

    public function Contrat()
    {
        return $this->belongsTo(Contrat::class);
    }


}
