<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Typedocument extends Model
{
    public $table = "typedocuments";


    public function Documents()
    {
        return $this->hasMany(Document::class);
    }

}
