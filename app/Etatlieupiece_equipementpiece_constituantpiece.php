<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Etatlieupiece_equipementpiece_constituantpiece extends Model
{
    public $table = "etatlieupiece_equipementpiece_constituantpiece";

    public function Etatlieupiece()
    {
        return $this->belongsTo(Etatlieu_piece::class);
    }

    public function Equipementpiece()
    {
        return $this->belongsTo(Piece_equipement_observation::class);
    }

    public function Constituantpiece()
    {
        return $this->belongsTo(Piece_constituant_observation::class);
    }
    public function Equipementgeneral()
    {
        return $this->belongsTo(Equipement_observation::class);
    }
}
