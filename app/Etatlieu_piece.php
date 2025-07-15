<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Etatlieu_piece extends Model
{
    public $table = "etatlieu_piece";

    public function Etatlieu()
    {
        return $this->belongsTo(Etatlieu::class);
    }
    public function composition()
    {
        return $this->belongsTo(Composition::class);
    }

    public function Pieceappartement()
    {
        return $this->belongsTo(Pieceappartement::class);
    }
    public function imageetatlieupieces()
    {
        return $this->hasMany(Imageetatlieupiece::class);
    }
    public function detailequipements()
    {
        return $this->hasMany(Detailequipement::class);
    }

    public function detailconstituants()
    {
        return $this->hasMany(Detailconstituant::class);
    }

}
