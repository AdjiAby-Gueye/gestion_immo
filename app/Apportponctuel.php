<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apportponctuel extends Model
{
    public $table = 'apportponctuels';
    protected $fillable =
    [
        'montant',
        'date',
        'contrat_id',
        'observations',
        'typeapportponctuel_id'
    ];

    public function typeapportponctuel(){
        return $this->belongsTo(Typeapportponctuel::class);
    }
    public function contrat(){
        return $this->belongsTo(Contrat::class);
    }
}
