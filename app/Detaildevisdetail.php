<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detaildevisdetail extends Model
{
    //
    public $table = "detaildevisdetails";

    // un detail de devi appartient a un sous type d'intervention
    public function soustypeintervention()
    {
        return $this->belongsTo(Soustypeintervention::class);
    }
    // un detaildevidetail a plus

    public function detaildevi(){
        return $this->belongsTo(Detaildevi::class);
    }


    public function unite(){
        return $this->belongsTo(Unite::class);
    }

    public function quantite(){
        return $this->belongsTo(Quantite::class);
    }

    public function puhtva(){
        return $this->belongsTo(Puhtva::class);
    }
    
}
