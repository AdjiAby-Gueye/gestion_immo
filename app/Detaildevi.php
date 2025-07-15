<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detaildevi extends Model
{
    //
    public $table = "detaildevis";

    // un detail de devi appartient a une devi
    public function devi()
    {
        return $this->belongsTo(Devi::class);
    }
   
    // soustypeintervention
    public function soustypeintervention()
    {
        return $this->belongsTo(Soustypeintervention::class);
    }
    //categorieintervention_id

    public function detaildevisdetails()
    {
        return $this->hasMany(Detaildevisdetail::class);
    }

    
    
    public function categorieintervention()
    {
        return $this->belongsTo(Categorieintervention::class);
    }

   

   
}
