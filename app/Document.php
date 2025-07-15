<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public $table = "documents";


    public function Message()
    {
        return $this->belongsTo(Message::class);
    }

    public function Typedocument()
    {
        return $this->belongsTo(Typedocument::class);
    }

    public function Annonce()
    {
        return $this->belongsTo(Annonce::class);
    }

}
