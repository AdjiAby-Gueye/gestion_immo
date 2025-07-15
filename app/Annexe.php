<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Annexe extends Model
{
    //

    public $table = "annexes";

    protected $fillable = ['filepath', 'filename' ,'contrat_id' , 'numero']; 

    public function Contrat(){
        return $this->belongsTo(Contrat::class);
    }

}
