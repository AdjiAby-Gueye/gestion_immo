<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Imagecomposition extends Model
{
    public $table = "imagecompositions";

    public function composition()
    {
        return $this->belongsTo(Composition::class);
    }

}
