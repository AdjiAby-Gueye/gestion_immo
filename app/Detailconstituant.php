<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detailconstituant extends Model
{
    public $table = "detailconstituants";

    public function etatlieu_piece()
    {
        return $this->belongsTo(Etatlieu_piece::class);
    }

    public function constituantpiece()
    {
        return $this->belongsTo(Constituantpiece::class);
    }

    public function observation()
    {
        return $this->belongsTo(Observation::class);
    }
}
