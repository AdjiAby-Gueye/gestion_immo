<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelcontrat extends Model
{
    protected $table = 'modelcontrats';
    protected $fillable = [
        'id',
        'designation',
        'description'
    ];
    public function contrat(){
        return $this->hasMany(Modelcontrat::class);
    }
}
