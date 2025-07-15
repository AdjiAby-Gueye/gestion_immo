<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commentaireintervention extends Model
{
    public $table = "commentaireinterventions";


    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }

    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    public function prestataire()
    {
        return $this->belongsTo(Prestataire::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
