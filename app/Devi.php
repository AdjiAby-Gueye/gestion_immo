<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devi extends Model
{
    //
    public $table = "devis";

    // une devi appartient a une demande d'intervention
    public function demandeintervention()
    {
        return $this->belongsTo(Demandeintervention::class);
    }

    // une devi a plusieurs detail de devi
    public function detaildevis()
    {
        return $this->hasMany(Detaildevi::class, 'devi_id');
    }


    // etatlieu_id
    public function etatlieu(){
        return $this->belongsTo(Etatlieu::class);
    }

}
