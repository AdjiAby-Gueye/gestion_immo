<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proprietaire_message extends Model
{
    public $table = "proprietaire_message";


    public function Proprietaire()
    {
        return $this->belongsTo(Proprietaire::class);
    }
    public function Message()
    {
        return $this->belongsTo(Message::class);
    }
}
