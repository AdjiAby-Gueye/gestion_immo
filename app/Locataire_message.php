<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Locataire_message extends Model
{
    public $table = "locataire_message";


    public function Locataire()
    {
        return $this->belongsTo(Locataire::class);
    }
    public function Message()
    {
        return $this->belongsTo(Message::class);
    }
}
