<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avenant extends Model
{
    public $table = "avenants";

   
    public function Locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    public function Appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function Typecontrat()
    {
        return $this->belongsTo(Typecontrat::class);
    }
    public function Contrat()
    {
        return $this->belongsTo(Contrat::class);
    }


    public function Delaipreavi()
    {
        return $this->belongsTo(Delaipreavi::class);
    }

    public function typerenouvellement()
    {
        return $this->belongsTo(Typerenouvellement::class);
    }
   

   
    public function periodicite()
    {
        return $this->belongsTo(Periodicite::class);
    }

    

   
}
