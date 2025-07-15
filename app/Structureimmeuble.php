<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Structureimmeuble extends Model
{
    public $table = "structureimmeubles";

    public function Immeubles()
    {
        return $this->hasMany(Immeuble::class);
    }
}
