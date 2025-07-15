<?php

namespace App;

use App\Helpers\NombreEnLettre;
use Illuminate\Database\Eloquent\Model;

class Avisecheance extends Model
{
    //
    public $table = "avisecheances";

    protected $fillable = ['periodicite_id', 'periodes', 'objet', 'amortissement', 'fraisgestion', 'date', 'date_echeance', 'contrat_id', 'signature', 'est_signer' ,'fraisdelocation','motif_annulation_paiement','date_annulation_paiement','code_avis'];
    protected $observe = [
        \App\Observers\AvisecheanceObserver::class,
    ];

    public function Contrat()
    {
        return $this->belongsTo(Contrat::class);
    }

    public function Paiementecheance()
    {
        return $this->hasOne(Paiementecheance::class);
    }

    public function Periodicite()
    {
        return $this->belongsTo(Periodicite::class);
    }
    public function fraisupplementaires()
    {
        return $this->hasMany(Fraisupplementaire::class, 'avisecheance_id');
    }

    // public function User(){
    //     return $this->belongsTo(User::class);
    // }

    function getFraisLocatifAttribute(){
    return number_format($this->fraisdelocation, 0, ' ', ' ');
    }

    public function getMontantTotalAttribute()
    {
        $total = $this->amortissement + $this->fraisgestion + $this->fraisdelocation;
        $total = Outil::getFraisAvisEcheance($this->id,$total);
        return number_format($total, 0, ' ', ' ');
    }
    public function getMontantTotalPeriodiciteAttribute()
    {
        $total = $this->amortissement + $this->fraisgestion + $this->fraisdelocation;
        $total = $total * $this->periodicite->nbr_mois;
        $total = Outil::getFraisAvisEcheance($this->id,$total);
        return number_format($total, 0, ' ', ' ');
    }
    public function getMontantAttribute()
    {
        $total = $this->amortissement + $this->fraisgestion +  $this->fraisdelocation;
        $total = Outil::getFraisAvisEcheance($this->id,$total);
        return $total;
    }

    public function getMontantTotalLetterAttribute()
    {
        $total = $this->amortissement + $this->fraisgestion + $this->fraisdelocation;
        $total = Outil::getFraisAvisEcheance($this->id,$total);
        $text = NombreEnLettre::convertirEnLettres($total);
        return $text;
    }

    public function getIsfraisuppAttribute()
    {
        $frais = Fraisupplementaire::where('avisecheance_id',$this->id)->get();

        return isset($frais) && count($frais) > 0 ? 1 : null;
    }

    public function getDateEcheanceFrAttribute()
    {
        $date = $this->date_echeance;
        return Outil::dateFR($date);
    }
    public function getDateCreateFrAttribute()
    {
        $date = $this->date;
        return Outil::dateFR($date);
    }

    protected function getAnneeEcheanceAttribute()
    {
        $tab = explode("-", $this->date_echeance);
        $mois = $tab ? $tab[0] : null;
        return $mois;
    }


    protected function getCodeGenereAttribute()
    {
        if ($this->contrat) {
            $lot = $this->contrat->appartement->lot;
            $date = $this->date;
            $time = strtotime($date);
            $month = date("m", $time);
            $year = date("Y", $time);
            return $this->getNombreEcheanceAttribute() . "-" . $month . "/" . $year . "-" . $lot;
        }

        return null;
    }

    protected function getNombreEcheanceAttribute()
    {
        $count = 1;
        $pos = 0;
        if ($this->contrat) {
            $idClient = $this->contrat->locataire->id;
            $idContrat= $this->contrat->id;
            $avis = Avisecheance::join("contrats", "contrats.id", "avisecheances.contrat_id")
                ->where("contrats.locataire_id", $idClient)
                ->where("contrats.id", $idContrat)
                ->orderBy('avisecheances.date' , 'asc')
                ->select("avisecheances.*")->get();

                for ($i = 0; $i < count($avis); $i++) {
                    if ($avis[$i]->id == $this->id) {
                        $pos = $i + 1;
                        break;
                    }
                }

        }

        return $pos;
    }
    protected function getCopreneurAttribute()
    {
        return (isset($this->contrat->copreneur) && isset($this->contrat->copreneur->id)) ? $this->contrat->copreneur->prenom . " " . $this->contrat->copreneur->nom : null;
    }

    protected function getLocataireAttribute()
    {
        $locataire = "";
        if (isset($this->contrat->locataire) && isset($this->contrat->locataire->id)) {
            if ($this->contrat->locataire && $this->contrat->locataire->nomentreprise){
                $locataire = $this->contrat->locataire->nomentreprise;
            }
            elseif ($this->contrat->locataire && $this->contrat->locataire->nom)
            {
                $locataire = $this->contrat->locataire->prenom.' '.$this->contrat->locataire->nom;
            }
            if ($this->getCopreneurAttribute()) {
               $locataire .= " & ".$this->getCopreneurAttribute();
            }
        }
        return $locataire;
    }

    protected function getIlotAttribute()
    {
        $ilot = "";
        if (isset($this->contrat->appartement) && isset($this->contrat->appartement->id)) {
            if ($this->contrat->appartement->ilot && isset($this->contrat->appartement->ilot->id)){
                $ilot = $this->contrat->appartement->ilot;
            }

        }
        return $ilot;
    }

    protected function getLotAttribute()
    {
        $lot = "";
        if (isset($this->contrat->appartement) && isset($this->contrat->appartement->id)) {
                $lot = $this->contrat->appartement->lot;
        }
        return $lot;
    }




    // ...

    public function calculerProchainPaiement()
    {
        // Récupérer la périodicité actuelle de la facture
        $periodicite = $this->periodicite->nbr_mois;
        // Logique pour calculer la prochaine date de paiement en fonction de la périodicité
        switch ($periodicite) {
            case 1:
                break;
            case 2:
                break;
            case 3:
                break;
            default:
                // Gérer le cas par défaut
                break;
        }
    }
}
