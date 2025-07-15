<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Typefacture extends Model
{
    public $table = "typefactures";


    public function Factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function facturelocations()
    {
        return $this->hasMany(Facturelocation::class);
    }





}
