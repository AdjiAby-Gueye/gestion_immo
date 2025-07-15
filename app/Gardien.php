<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gardien extends Model
{
    public $table = "gardiens";

    public function Immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

}
