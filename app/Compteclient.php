<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compteclient extends Model
{
    public $table = "compteclients";

    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }
}
