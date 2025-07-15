<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Constituantpiece extends Model
{
    public $table = "constituantpieces";

    public function Etatlieu()
    {
        return $this->belongsTo(Etatlieu::class);
    }

    public function Observation()
    {
        return $this->belongsTo(Observation::class);
    }

    public function Detailconstituants()
    {
        return $this->hasMany(Detailconstituant::class);
    }


}
