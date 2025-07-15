<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Puhtva extends Model
{
    //
    public $table = "puhtvas";

    // un deatdevidetail appartient a puhtva

    public function detaildevisdetail()
    {
        return $this->hasMany(Detaildevisdetail::class);
    }


}
