<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Etatlieu extends Model
{
    public $table = "etatlieus";

    public function demanderesiliation()
    {
        return $this->belongsTo(Demanderesiliation::class);
    }

    public function Pieceappartement()
    {
        return $this->belongsTo(Pieceappartement::class);
    }

    public function Appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function Locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    public function Constituantpieces()
    {
        return $this->belongsToMany(Constituantpiece::class);
    }


    public function Equipementpieces()
    {
        return $this->belongsToMany(Equipementpiece::class);
    }


    public function etatlieu_pieces()
    {
        return $this->hasMany(Etatlieu_piece::class);
    }

    public function devi(){
        return $this->hasOne(Devi::class);
    }

    public function Interventions()
    {
        return $this->hasMany(Intervention::class);
    }

    // factureintervention
    public function Factureintervention()
    {
        return $this->hasOne(Factureintervention::class);
    }

   public function Situationdepot(){
    return $this->hasOne(Situationdepot::class);
   }

   

}
