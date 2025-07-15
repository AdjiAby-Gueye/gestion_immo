<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Typeapportponctuel extends Model
{
    public $table = 'typeapportponctuels';

    protected $fillable = [
        'designation',
        'description',
    ];

    public function apportponctuels(){
        return $this->hasMany(Apportponctuel::class);
    }


}
