<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factureacompte extends Model
{
    //


    public function Contrat(){
        return $this->belongsTo(Contrat::class);
    }



}
