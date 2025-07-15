<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Etatappartement extends Model
{
    public $table = "etatappartements";

    public function Appartements()
    {
        return $this->hasMany(Appartement::class);
    }



}
