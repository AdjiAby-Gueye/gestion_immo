<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detailfactureintervention extends Model
{
    public $table = "detailfactureinterventions";

    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }
    public function Factureintervention()
    {
        return $this->belongsTo(Factureintervention::class);
    }

    

}
