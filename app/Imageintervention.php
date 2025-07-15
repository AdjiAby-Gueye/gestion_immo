<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Imageintervention extends Model
{
    public $table = "imageinterventions";


    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }

}
