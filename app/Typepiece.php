<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Typepiece extends Model
{
    public $table = "typepieces";

    public function Immeubles()
    {
        return $this->belongsToMany(Immeuble::class);
    }

    public function Interventions()
    {
        return $this->belongsToMany(Intervention::class);
    }

    public function pieceimmeubles()
    {
        return $this->hasMany(Pieceimmeuble::class);
    }

    public function typepieceniveauappartements()
    {
        return $this->belongsToMany(Niveauappartement::class, 'typepiece_niveauappartement', 'typepiece_id', 'niveauappartement_id');
    }

}
