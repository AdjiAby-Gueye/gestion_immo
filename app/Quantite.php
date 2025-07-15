<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quantite extends Model
{
    //

    public $table = 'quantites';


    public function detaildevisdetail()
    {
        return $this->hasMany(Detaildevisdetail::class);
    }
}
