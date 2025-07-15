<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facturelocationperiode extends Model
{
    //
    public $table = "facturelocationperiodes";

    public function facturelocation() {
        return $this->belongsTo(Facturelocation::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
    public function typefacture()
    {
        return $this->belongsTo(Typefacture::class);
    }


}
