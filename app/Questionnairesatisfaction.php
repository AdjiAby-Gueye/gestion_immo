<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questionnairesatisfaction extends Model
{
    public $table = "questionnairesatisfactions";


    public function Locataires()
    {
        return $this->belongsToMany(Locataire::class);
    }

    public function Proprietaires()
    {
        return $this->belongsToMany(Proprietaire::class);
    }

    public function Documents()
    {
        return $this->hasMany(Document::class);
    }

    public function Reponsequestionnaires()
    {
        return $this->hasMany(Reponsequestionnaire::class);
    }

    public function Intervention()
    {
        return $this->belongsTo(Intervention::class);
    }
}
