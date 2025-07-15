<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Niveauappartement extends Model
{
    public $table = "niveauappartements";

    public function Appartements()
    {
        return $this->hasMany(Appartement::class);
    }

    public function niveauappartementtypepieces()
    {
        return $this->belongsToMany(Typepiece::class, 'typepiece_niveauappartement', 'niveauappartement_id', 'typepiece_id');
    }
    public function Typeappartement_pieces()
    {
        return $this->hasMany(Typeappartement_piece::class);
    }
}
