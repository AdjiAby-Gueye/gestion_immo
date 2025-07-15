<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Typelocataire extends Model
{
    public $table = "typelocataires";

    public function Locataires()
    {
        return $this->hasMany(Locataire::class);
    }
}
