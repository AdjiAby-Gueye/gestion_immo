<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caution extends Model
{
    public $table = "cautions";


    public function Contrat()
    {
        return $this->belongsTo(Contrat::class);
    }


}
