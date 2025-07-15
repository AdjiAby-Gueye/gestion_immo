<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fraisupplementaire extends Model
{
    public $table = "fraisupplementaires";

    public function avisecheance()
    {
        return $this->belongsTo(Avisecheance::class, 'avisecheance_id');
    }

}
