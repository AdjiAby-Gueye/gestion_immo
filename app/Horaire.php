<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Horaire extends Model
{
    public $table = "horaires";


    public function securites()
    {
        return $this->hasMany(Securite::class);
    }
}
