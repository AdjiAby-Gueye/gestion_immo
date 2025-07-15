<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pieceappartement extends Model
{
    public $table = "pieceappartements";

    public function Appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function Typepiece()
    {
        return $this->belongsTo(Typepiece::class);
    }

    public function Immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    public function Etatlieux()
    {
        return $this->hasMany(Etatlieu::class);
    }



}
