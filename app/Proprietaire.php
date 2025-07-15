<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proprietaire extends Model
{
    public $table = "proprietaires";

    public function Immeubles()
    {
        return $this->belongsToMany(Immeuble::class);
    }

    public function Appartements()
    {
        return $this->hasMany(Appartement::class);
    }

    public function Versementloyers()
    {
        return $this->hasMany(Versementloyer::class);
    }

 
    public function Messages()
    {
        return $this->belongsToMany(Message::class);
    }

    public function Questionnairesatisfactions()
    {
        return $this->belongsToMany(Questionnairesatisfaction::class);
    }
    public function Contratproprietaire(){
        return $this->hasMany(Contratproprietaire::class);
    }

    public function Factures()
    {
        return $this->hasMany(Facture::class);
    }

}
