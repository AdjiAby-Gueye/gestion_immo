<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Locataire extends Model
{
    public $table = "locataires";

    public function copreneurs()
    {
        return $this->hasMany(Copreneur::class);
    }
    public function Appartements()
    {
        return $this->belongsToMany(Appartement::class);
    }

    public function Typelocataire()
    {
        return $this->belongsTo(Typelocataire::class);
    }

    public function Contrats()
    {
        return $this->hasMany(Contrat::class);
    }

  

    // public function Messages()
    // {
    //     return $this->belongsToMany(Message::class);
    // }

 

    public function user()
    {
        return $this->belongsTo(user::class);
    }

    public function Paiementloyers()
    {
        return $this->hasMany(Paiementloyer::class);
    }

    public function entite()
    {
        return $this->belongsTo(Entite::class);
    }

    // demande d'intervention
    public function demandeintervention()
    {
        return $this->hasMany(Demandeintervention::class);
    }
    //factureintervention
    public function factureinterventions()
    {
        return $this->hasMany(Factureintervention::class);
    }
}
