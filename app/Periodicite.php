<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Periodicite extends Model
{
    //

    public function Facturelocation(){
        return $this->hasMany(Facturelocation::class);
    }
    
}
