<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
    public $table = "contrats";

    public function copreneur() {
        return $this->belongsTo(Copreneur::class);
    }
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

    public function Assurances()
    {
        return $this->hasMany(Assurance::class);
    }

    public function Caution()
    {
        return $this->belongsTo(Caution::class);
    }

    public function Delaipreavi()
    {
        return $this->belongsTo(Delaipreavi::class);
    }

    public function typerenouvellement()
    {
        return $this->belongsTo(Typerenouvellement::class);
    }

    public function Versementloyers()
    {
        return $this->hasMany(Versementloyer::class);
    }

    public function Versementchargecoproprietes()
    {
        return $this->hasMany(Versementchargecopropriete::class);
    }

    public function Paiementloyers()
    {
        return $this->hasMany(Paiementloyer::class);
    }

    public function Etatcontrat()
    {
        return $this->belongsTo(Etatcontrat::class);
    }

    public function Demanderesiliation()
    {
        return $this->hasMany(Demanderesiliation::class);
    }
    public function periodicite()
    {
        return $this->belongsTo(Periodicite::class);
    }

    public function usersigned()
    {
        return $this->belongsTo(User::class);
    }

    public function facturelocations()
    {
        return $this->hasMany(Facturelocation::class);
    }

    public function avisecheances()
    {
        return $this->hasMany(Avisecheance::class);
    }

    public function annexes()
    {
        return $this->hasMany(Annexe::class);
    }
    // public function factureacompte()
    // {
    //     return $this->hasOne(Factureacompte::class);
    // }

    public function factureeauxs()
    {
        return $this->hasMany(Factureeaux::class);
    }
    public function apportponctuel(){
        return $this->hasMany(Apportponctuel::class);
    }
    public function contratproprietaire(){
        return $this->hasMany(Contratproprietaire::class);
    }
}
