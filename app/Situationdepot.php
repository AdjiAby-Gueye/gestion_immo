<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Situationdepot extends Model
{
    //



    // etatlieu_id

    public function etatlieu()
    {
        return $this->belongsTo(Etatlieu::class);
    }

    public function facturelocation()
    {
        return $this->belongsTo(Facturelocation::class);
    }
}
