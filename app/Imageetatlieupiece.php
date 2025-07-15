<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Imageetatlieupiece extends Model
{
    public $table = "imageetatlieupieces";

    public function etatlieu_piece()
    {
        return $this->belongsTo(Etatlieu_piece::class);
    }

}
