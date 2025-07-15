<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proprietaire_questionnairesatisfaction extends Model
{
    public $table = "proprietaire_questionnairesatisfaction";


    public function Proprietaire()
    {
        return $this->belongsTo(Proprietaire::class);
    }
    public function Questionnairesatisfaction()
    {
        return $this->belongsTo(Questionnairesatisfaction::class);
    }
}
