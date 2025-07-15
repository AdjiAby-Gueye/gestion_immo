<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membreequipegestion_fonction extends Model
{
    public $table = "membreequipegestion_fonction";


    public function Equipegestion()
    {
        return $this->belongsTo(Equipegestion::class);
    }
    public function Membreequipegestion()
    {
        return $this->belongsTo(Membreequipegestion::class);
    }

}
