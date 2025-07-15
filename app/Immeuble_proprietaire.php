<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Immeuble_proprietaire extends Model
{
    public $table = "immeuble_proprietaire";

    public function Immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    public function Proprietaire()
    {
        return $this->belongsTo(Proprietaire::class);
    }
}
