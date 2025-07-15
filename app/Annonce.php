<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    public $table = "annonces";

    public function Immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    public function Appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function Documents()
    {
        return $this->hasMany(Annonce::class);
    }

}
