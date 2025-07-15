<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Piece_constituant_observation extends Model
{
    public $table = "piece_constituant_observation";

    public function Piece()
    {
        return $this->belongsTo(Pieceappartement::class);
    }

    public function Constituantpiece()
    {
        return $this->belongsTo(Constituantpiece::class);
    }
    public function Observation()
    {
        return $this->belongsTo(Observation::class);
    }
}
