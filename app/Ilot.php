<?php

namespace App;

use App\Appartement;
use Illuminate\Database\Eloquent\Model;

class Ilot extends Model
{
    //
    public $table = "ilots";

    public function appartements()
    {
        return $this->hasMany(Appartement::class);
    }
}
