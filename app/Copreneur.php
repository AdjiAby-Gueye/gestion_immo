<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Copreneur extends Model
{
    public $table = "copreneurs";
    protected $guarded = [];
    public function locataire()
    {
        return $this->hasOne(Locataire::class);
    }


}
