<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipegestion_membreequipegestion extends Model
{
    public $table = "equipegestion_membreequipegestion";


    public function Equipegestion()
    {
        return $this->belongsTo(Equipegestion::class);
    }
    public function Membreequipegestion()
    {
        return $this->belongsTo(Membreequipegestion::class);
    }
    public function Fonction()
    {
        return $this->belongsTo(Fonction::class);
    }
}
