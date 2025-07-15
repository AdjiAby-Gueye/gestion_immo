<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factureintervention extends Model
{
    public $table = "factureinterventions";
    protected $fillable = ["montant"];


    public function Appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function Typefacture()
    {
        return $this->belongsTo(Typefacture::class);
    }

    public function Intervention()
    {
        return $this->belongsTo(Intervention::class);
    }

    public function detailfactureinterventions()
    {
        return $this->hasMany(Detailfactureintervention::class, 'factureintervention_id');
    }
    public function Demandeintervention()
    {
        return $this->belongsTo(Demandeintervention::class);
    }
    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }


    public function paiementintervention()
    {
        return $this->hasOne(Paiementintervention::class, 'factureintervention_id');
    }

    public function Etatlieu()
    {
        return $this->belongsTo(Etatlieu::class);
    }
}
