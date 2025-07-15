<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipegestion extends Model
{
    public $table = "equipegestions";

    public function Immeubles()
    {
        return $this->hasMany(Immeuble::class);
    }
    public function Membreequipegestions()
    {
        return $this->belongsToMany(Equipegestion::class);
    }
}
