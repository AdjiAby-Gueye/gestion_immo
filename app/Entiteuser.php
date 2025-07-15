<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entiteuser extends Model
{
    public $table = "entite_user";

    public function entite() {

        return $this->belongsTo(Entite::class);
    }
    public function user() {

        return $this->belongsTo(User::class);
    }
}
