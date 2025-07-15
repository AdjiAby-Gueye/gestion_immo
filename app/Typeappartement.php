<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Typeappartement extends Model
{
    public $table = "typeappartements";

    public function Appartements()
    {
        return $this->hasMany(Appartement::class);
    }
    public function Typeappartement_pieces()
    {
        return $this->hasMany(Typeappartement_piece::class);
    }



}
