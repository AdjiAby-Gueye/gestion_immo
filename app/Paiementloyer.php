<?php

namespace App;

use App\Helpers\NombreEnLettre;
use Illuminate\Database\Eloquent\Model;

class Paiementloyer extends Model
{
    public $table = "paiementloyers";


    public function Contrat()
    {
        return $this->belongsTo(Contrat::class);
    }

    public function Appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function Locataire()
    {
        return $this->belongsTo(Locataire::class);
    }


    public function modepaiement()
    {
        return $this->belongsTo(Modepaiement::class);
    }

    public function detailpaiements()
    {
        return $this->hasMany(Detailpaiement::class);
    }

    public function Facturelocation()
    {
        return $this->belongsTo(Facturelocation::class);
    }

    public function Factureeaux(){
        return $this->belongsTo(Factureeaux::class);
    }

    protected function getTotalAmountAttribute(){
        return Detailpaiement::where("paiementloyer_id" , $this->id)
            ->sum("montant");
    }
    protected function getTotalAmountLetterAttribute(){
        $total = $this->getTotalAmountAttribute();
        return NombreEnLettre::CustomNumberToWords($total);
    }
    protected function getDateFormatAttribute(){
        return Outil::dateFR($this->datepaiement);
    }
}
