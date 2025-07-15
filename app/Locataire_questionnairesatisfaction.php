<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Locataire_questionnairesatisfaction extends Model
{
    public $table = "locataire_questionnairesatisfaction";


    public function Locataire()
    {
        return $this->belongsTo(Locataire::class);
    }
    public function Quesionnairesatisfaction()
    {
        return $this->belongsTo(Questionnairesatisfaction::class);
    }
}
