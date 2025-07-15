<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detailintervention extends Model
{
    public $table = "detailinterventions";

    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }

    public function detailconstituant()
    {
        return $this->belongsTo(Detailconstituant::class);
    }

    public function detailequipement()
    {
        return $this->belongsTo(Detailequipement::class);
    }

}
