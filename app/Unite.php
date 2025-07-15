<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unite extends Model
{
    //

    public $table = 'unites';


    public function detaildevisdetail()
    {
        return $this->hasMany(Detaildevisdetail::class);
    }
}
