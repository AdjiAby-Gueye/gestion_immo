<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reponsequestionnaire extends Model
{
    public $table = "reponsequestionnaires";


    public function Questionnairesatisfaction()
    {
        return $this->belongsTo(Questionnairesatisfaction::class);
    }
}
