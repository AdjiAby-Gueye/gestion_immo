<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assurance extends Model
{
    public $table = "assurances";


    public function Contrat()
    {
        return $this->belongsTo(Contrat::class);
    }

    public function Typeassurance()
    {
        return $this->belongsTo(Typeassurance::class);
    }

    public function Assureur()
    {
        return $this->belongsTo(Assureur::class);
    }

    public function Prestataire()
    {
        return $this->belongsTo(Prestataire::class);
    }

    public function Etatassurance()
    {
        return $this->belongsTo(Etatassurance::class);
    }


}
