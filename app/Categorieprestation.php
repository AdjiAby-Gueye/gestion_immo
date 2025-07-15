<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categorieprestation extends Model
{
    public $table = "categorieprestations";

    public function Contratprestations()
    {
        return $this->hasMany(Contratprestation::class);
    }

}
