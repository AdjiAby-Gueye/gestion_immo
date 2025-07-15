<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Typeappartement_piece extends Model
{
    public $table = "typeappartement_piece";

    public function Typeappartement()
    {
        return $this->belongsTo(Typeappartement::class);
    }

    public function Typepiece()
    {
        return $this->belongsTo(Typepiece::class);
    }
    public function compositions()
    {
        return $this->hasMany(Composition::class);
    }

    public function Niveauappartement()
    {
        return $this->belongsTo(Niveauappartement::class);
    }
}
