<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Documentappartement extends Model
{
    public $table = "documentappartements";

    protected $fillable = ['document','appartement_id','nom'];


    public function appartement()
    {
        return $this->belongsTo(Appartement::class);
    }
}
