<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detailcomposition extends Model
{
    public $table = "detailcompositions";

    public function composition()
    {
        return $this->belongsTo(Composition::class);
    }

    public function appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function equipement()
    {
        return $this->belongsTo(Equipementpiece::class);
    }

}
