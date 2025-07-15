<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detailequipement extends Model
{
    public $table = "detailequipements";

    public function etatlieu_piece()
    {
        return $this->belongsTo(Etatlieu_piece::class);
    }

    public function equipementpiece()
    {
        return $this->belongsTo(Equipementpiece::class);
    }

    public function observation()
    {
        return $this->belongsTo(Observation::class);
    }
}
