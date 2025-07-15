<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Soustypeintervention extends Model
{
    //
    public $table = "soustypeinterventions";


    //un sous type d'intervention appartient a detaildevidetaildevis
    public function categorieintervention()
    {
        return $this->belongsTo(Categorieintervention::class);
    }
    

   

   
}
