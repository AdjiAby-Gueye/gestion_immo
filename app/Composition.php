<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Composition extends Model
{
    public $table = "compositions";

    public function Appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function etatlieu_piece()
    {
        return $this->hasMany(Etatlieu_piece::class);
    }

    public function imagecomposition()
    {
        return $this->hasMany(Imagecomposition::class);
    }

    public function Typeappartement_piece()
    {
        return $this->belongsTo(Typeappartement_piece::class);
    }
    public function detailcompositions()
    {
        return $this->hasMany(Detailcomposition::class);
    }
    public function Niveauappartement()
    {
        return $this->belongsTo(Niveauappartement::class);
    }
}
