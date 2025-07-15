<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facturelocation extends Model
{
    //


    public function Contrat(){
        return $this->belongsTo(Contrat::class);
    }

    public function Paiementloyers(){
        return $this->hasMany(Paiementloyer::class);
    }
    public function facturelocationperiodes(){
        return $this->hasMany(Facturelocationperiode::class);
    }

    public function Typefacture(){
        return $this->belongsTo(Typefacture::class);
    }

    public function Periodicite(){
        return $this->belongsTo(Periodicite::class);
    }

    public function User(){
        return $this->belongsTo(User::class);
    }

    public function Situationdepot(){
        return $this->hasOne(Situationdepot::class);
       }


}
