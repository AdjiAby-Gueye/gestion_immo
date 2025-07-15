<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Immeuble extends Model
{
    public $table = "immeubles";

    public function pieceimmeubles()
    {
        return $this->hasMany(Pieceimmeuble::class);
    }

    public function Equipegestion()
    {
        return $this->belongsTo(Equipegestion::class);
    }

    public function Structureimmeuble()
    {
        return $this->belongsTo(Structureimmeuble::class);
    }

    public function Proprietaires()
    {
        return $this->belongsToMany(Proprietaire::class);
    }

    public function Appartements()
    {
        return $this->hasMany(Appartement::class);
    }

    public function securites()
    {
        return $this->hasMany(Securite::class);
    }

  

    public function Factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function Annonces()
    {
        return $this->hasMany(Annonce::class);
    }

    

    // de meme un immeuble a plusieurs demande d'intervention

    public function demandeintervention()
    {
        return $this->hasMany(Demandeintervention::class);
    }
    public function contratproprietaire(){
        return $this->belongsTo(Contratproprietaire::class);
    }

}
