<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contratproprietaire extends Model
{
    public $table = 'contratproprietaires';

    protected $fillable = [
        'date',
        'descriptif',
        'commissionvaleur',
        'commissionpourcentage',
        'entite_id',
        'proprietaire_id',
        'is_tva',
        'is_brs',
        'is_tlv',
        'modelcontrat_id',
    ];

    public function proprietaire(){
        return $this->belongsTo(Proprietaire::class);
    }
    public function entite(){
        return $this->belongsTo(Entite::class);
    }
    public function modelcontrat(){
        return $this->belongsTo(Modelcontrat::class);
    }
    public function appartement(){
        return $this->hasMany(Appartement::class);
    }

}
