<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bien_questionnaire extends Model
{
    public $table = "bien_questionnaire";

    public function Appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function Questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function Immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }
}
