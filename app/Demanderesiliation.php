<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Demanderesiliation extends Model
{
    public $table = "demanderesiliations";

    public function Contrat()
    {
        return $this->belongsTo(Contrat::class);
    }

    public function Delaipreavi()
    {
        return $this->belongsTo(Delaipreavi::class);
    }

}
