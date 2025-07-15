<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assureur extends Model
{
    public $table = "assureurs";


    public function Assurances()
    {
        return $this->hasMany(Assurance::class);
    }

}
