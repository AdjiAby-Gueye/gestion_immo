<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fonction extends Model
{
    public $table = "fonctions";

    public function Membreequipegestions()
    {
        return $this->belongsToMany(Membreequipegestion::class);
    }


}
