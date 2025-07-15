<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paiementecheance extends Model
{
    public $table = "paiementecheances";

    protected $fillable = [
        'avisecheance_id',
        'date',
        'montant',
        'modepaiement_id',
        'commentaire',
        'numero_cheque',
        'periodes',
        'justificatif',
        'receipt_number'
    ];
    public function contrat()
    {
        return $this->belongsTo(Contrat::class);
    }



    public function modepaiement()
    {
        return $this->belongsTo(Modepaiement::class);
    }


    public function avisecheance()
    {
        return $this->belongsTo(Avisecheance::class);
    }

   
}
