<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public $table = "messages";


    public function Locataires()
    {
        return $this->belongsToMany(Locataire::class);
    }

    public function Proprietaires()
    {
        return $this->belongsToMany(Proprietaire::class);
    }

    public function Documents()
    {
        return $this->hasMany(Document::class);
    }
}
