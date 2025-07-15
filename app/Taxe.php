<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taxe extends Model
{
    protected $table = 'taxes';
    protected $fillable = [
        'designation',
        'description',
        'valeur',
    ];
}
