<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Piece_equipement_observation extends Model
{
    public $table = "piece_equipement_observation";

    public function Piece()
    {
        return $this->belongsTo(Pieceappartement::class);
    }

    public function Equipement()
    {
        return $this->belongsTo(Equipementpiece::class);
    }
    public function Observation()
    {
        return $this->belongsTo(Observation::class);
    }
}
