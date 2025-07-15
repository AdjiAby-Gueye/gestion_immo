<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delaipreavi extends Model
{
    public $table = "delaipreavis";


    public function Contrats()
    {
        return $this->hasMany(Contrat::class);
    }

    public function Demanderesiliations()
    {
        return $this->hasMany(Demanderesiliation::class);
    }

}
