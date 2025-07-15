<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pieceimmeuble extends Model
{
    public $table = "pieceimmeubles";

    public function Immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    public function Typepiece()
    {
        return $this->belongsTo(Typepiece::class);
    }


}
