<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Etatassurance extends Model
{
    public $table = "etatassurances";


    public function Assurances()
    {
        return $this->hasMany(Assurance::class);
    }

}
