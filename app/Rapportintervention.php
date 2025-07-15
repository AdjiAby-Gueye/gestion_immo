<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rapportintervention extends Model
{
    public $table = "rapportinterventions";


    public function Produitsutilises()
    {
        return $this->belongsToMany(Produitsutilise::class);
    }

    public function Appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function Immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }
    public function Intervention()
        {
            return $this->belongsTo(Intervention::class);
        }

}
