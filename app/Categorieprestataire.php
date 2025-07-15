<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categorieprestataire extends Model
{
    public $table = "categorieprestataires";

    public function prestataires()
    {
        return $this->hasMany(Prestataire::class);
    }

}
