<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activite extends Model
{
    protected $table = 'activites';
    protected $fillable = [
        'designation',
        'description',
    ];
    public function entite(){
        return $this->belongsTo(Entite::class);
    }
}
