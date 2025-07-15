<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Infobancaire extends Model
{
    public $table = "infobancaires";

    public function entite() {

        return $this->belongsTo(Entite::class);
    }

}
