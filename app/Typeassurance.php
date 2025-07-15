<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Typeassurance extends Model
{
    public $table = "typeassurances" ;


    public function Assurances()
    {
        return $this->hasMany(Assurance::class) ;
    }
}
