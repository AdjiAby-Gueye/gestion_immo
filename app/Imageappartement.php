<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Imageappartement extends Model
{
    public $table = "imageappartements";

    public function appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

}
