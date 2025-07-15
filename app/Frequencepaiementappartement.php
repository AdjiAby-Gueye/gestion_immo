<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Frequencepaiementappartement extends Model
{
    public $table = "frequencepaiementappartements";

    public function Appartements()
    {
        return $this->hasMany(Appartement::class);
    }
    public function Contratprestations()
    {
        return $this->hasMany(Contratprestation::class);
    }



}
