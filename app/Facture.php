<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    public $table = "factures";

    public function immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    public function Appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function Typefacture()
    {
        return $this->belongsTo(Typefacture::class);
    }

    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }

    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }
    public function proprietaire()
    {
        return $this->belongsTo(Proprietaire::class);
    }



}
