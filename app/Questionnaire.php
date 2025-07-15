<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    public $table = "questionnaires";

    public function Typequestionnaire()
    {
        return $this->belongsTo(Typequestionnaire::class);
    }

    public function Appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function Immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

}
