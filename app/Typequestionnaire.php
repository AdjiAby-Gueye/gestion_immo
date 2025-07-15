<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Typequestionnaire extends Model
{
    public $table = "typequestionnaires";

    public function Questionnaires()
    {
        return $this->hasMany(Questionnaire::class);
    }

}
