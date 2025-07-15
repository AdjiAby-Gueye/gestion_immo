<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Typeintervention extends Model
{
    public $table = "typeinterventions";


    public function Interventions()
    {
        return $this->hasMany(Intervention::class);
    }
}
