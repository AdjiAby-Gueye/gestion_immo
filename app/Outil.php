<?php

namespace App;

use App\Mail\Maileurcourierrelance;
use Log;
use DateTime;
use App\ClientJwt;
use Carbon\Carbon;
use App\Mail\Maileur;
use GuzzleHttp\Client;
use App\Events\RtEvent;
use App\Mail\DemoEmail;
use App\Mail\InboxMail;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use App\Events\SendNotifEvent;
use NumberFormatter;
use NumberToWords\NumberToWords;
use App\Mail\RappelPaiementLoyer;
use Illuminate\Support\Facades\DB;
//Authentification client
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Exception\RequestException;
use Swift_TransportException;

class Outil extends Model
{

    public static function getNumeroCompte($item)
    {
        $initiale = '';
        $nb_client_type_compte = \App\Client::query()
            ->join('compte_credits', 'compte_credits.client_id', 'clients.id')
            ->join('mode_paiements', 'mode_paiements.id', 'compte_credits.mode_paiement_id')
            ->where('mode_paiements.id', $item->id)
            //            ->groupBy('client.id')
            ->count('clients.id');
        //   dd($item->designation);
        if ($item->designation == 'Compte Crédit') {

            $initiale  =  'CC';
        } else

            if ($item->designation == 'Conso Interne') {

            $initiale  =  'CI';
        }

        if (!isset($nb_client_type_compte) || $nb_client_type_compte == 0) {

            $nb_client_type_compte = 1;
        } else {

            $nb_client_type_compte++;
        }
        return self::faireMatricule($nb_client_type_compte, $initiale);
    }


    public static function dateFR($date)
    {
        $date_at = $date;
        if ($date_at !== null) {
            $date_at = $date_at;
            $date_at = date_create($date_at);
            return date_format($date_at, "d/m/Y");
        } else {
            return null;
        }
    }
    public static function chargeAllInterventions($id)
    {
        try {

            $query = DB::table('devis');

            $items = $query
                ->leftJoin('demandeinterventions', 'devis.demandeintervention_id', '=', 'demandeinterventions.id')
                ->leftJoin('etatlieus', 'devis.etatlieu_id', '=', 'etatlieus.id')
                ->join('detaildevis', 'devis.id', '=', 'detaildevis.devi_id')
                ->join('categorieinterventions', 'detaildevis.categorieintervention_id', '=', 'categorieinterventions.id')
                ->join('detaildevisdetails', 'detaildevis.id', '=', 'detaildevisdetails.detaildevi_id')
                ->join('soustypeinterventions', 'detaildevisdetails.soustypeintervention_id', '=', 'soustypeinterventions.id')
                ->select(
                    'devis.id as devis_id',
                    'devis.object as object',
                    'etatlieus.id as etatlieu_id',
                    'demandeinterventions.id as demandeintervention_id',
                    'detaildevis.id as detaildevis_id',
                    'categorieinterventions.id as categorieintervention_id',
                    'categorieinterventions.designation as categorie_designation',
                    'detaildevisdetails.id as detaildevisdetails_id',
                    'detaildevisdetails.detaildevi_id as detaildevi_id',
                    'detaildevisdetails.soustypeintervention_id as soustypeintervention_id',
                    'soustypeinterventions.designation as soustype_designation'
                )
                ->where('detaildevis.devi_id', $id)
                ->get();


            $addedCategoryIDs = [];

            foreach ($items as $item) {
                if (!in_array($item->categorieintervention_id, $addedCategoryIDs)) {
                    $intervention = new Intervention();
                    $intervention->demandeintervention_id = ($item->demandeintervention_id) ? $item->demandeintervention_id : null;
                    $intervention->etatlieu_id = ($item->etatlieu_id) ? $item->etatlieu_id : null;
                    $intervention->dateintervention = date("Y-m-d");
                    $intervention->etat = "";
                    $intervention->descriptif = "probleme de " . $item->categorie_designation;
                    $intervention->categorieintervention_id = $item->categorieintervention_id;
                    $intervention->save();
                    // Ajoutez l'ID de la catégorie à la liste
                    $addedCategoryIDs[] = $item->categorieintervention_id;
                }
            }







            return 1;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }






    //envoie mail de rappel paiement loyer
    public static function newrappelPaiement()
    {
        $periode = $now = $month = $year = $jour = "";
        $periode = "periode";
        $query = DB::table('locataires');
        $locataires = $query
            ->join('contrats', 'locataires.id', '=', 'contrats.locataire_id')
            ->select('locataires.*', 'contrats.rappelpaiement', 'contrats.etat', 'contrats.montantloyer', 'contrats.descriptif')
            ->where('contrats.etat', '=', 2)
            ->whereNotIn('contrats.id', function ($qr) {
                $now = date("Y-m-d");
                $jour = strtotime($now . "- 1 months");
                $jour = date("Y-m-d", $jour);
                $jour = explode("-", $jour);
                $month = $jour[1];
                $year = $jour[0];
                $periode = self::getMonthString($month);
                $periode = $periode;
                // $periode = $periode . " " . $year;
                // dd($periode);
                $qr->select('paiementloyers.contrat_id')->from('paiementloyers')
                    // ->join('details', 'paiementloyers.detail_id', '=', 'details.id')
                    // ->where('details.periode', '=', $periode);
                    ->join('paiementloyers as pl', 'contrats.id', '=', 'pl.contrat_id')
                    ->join('detailpaiements', 'detailpaiements.paiementloyer_id', '=', 'pl.id')
                    ->join('periodes', 'periodes.id', 'detailpaiements.periode_id')
                    ->where('periodes.designation', '=', $periode);
            })->get();

        foreach ($locataires as $locataire) {
            // Vérifiez si le locataire a déjà payé pour la période spécifique
            $periode = self::getMonthString($month) . " " . $year;
            $hasPaidForPeriod = DB::table('locataires')
                ->join('contrats', 'locataires.id', '=', 'contrats.locataire_id')
                ->join('paiementloyers', 'contrats.id', '=', 'paiementloyers.contrat_id')
                ->join('detailpaiements', 'detailpaiements.paiementloyer_id', '=', 'paiementloyers.id')
                ->join('periodes', 'periodes.id', 'detailpaiements.periode_id')
                ->where('locataires.id', '=', $locataire->id)
                ->where('periodes.designation', '=', $periode)
                ->exists();

            if (!$hasPaidForPeriod) {
                // Envoyez le rappel de paiement
                self::sendMailRappelPaiementLoyer($locataire);
            }
        }

    }

    public static function resolveContratlocationVente(){
        $query = Contrat::query();
        $query = $query->join("appartements", "appartements.id", "contrats.appartement_id")
            ->join("entites", "entites.id", "appartements.entite_id")
            ->where('entites.code', "RID")->select("contrats.*");
        $contrats =   $query->get();
        $cont = 0;
        if(isset($contrats) && count($contrats) > 0){
            foreach($contrats as $key=> $contrat){
                $locataire = Locataire::find($contrat->locataire_id);
                if(!isset($contrat->email)){
                    $contrat->email = $locataire->email;
                    $contrat->save();
                    $cont++;
                }

            }
        }
        return $cont;
    }

    public static function getLocataireImppayeavisecheanceByPeriode(){

        $now = date("Y-m-d");
        $now = explode('-',$now);
        $month = $now[1];
        $periode = self::getMonthString($month);

        $paiementecheance = Paiementecheance::query()->where('periodes',  Outil::getOperateurLikeDB(), '%' . $periode . '%')->get(['avisecheance_id']);
        $contrats = Contrat::query()
            ->join("appartements", "appartements.id", "contrats.appartement_id")
            ->join("entites", "entites.id", "appartements.entite_id")
            ->join("avisecheances", "avisecheances.contrat_id", "contrats.id")
            ->where('entites.code', "RID")
            ->where('avisecheances.periodes',  Outil::getOperateurLikeDB(), '%' . $periode . '%')
            ->whereNotIn('avisecheances.id',$paiementecheance)
            ->groupBy(['contrats.id'])
            ->selectRaw('contrats.*')
            ->get();

        return $contrats;

    }

    public static function getLocataireavisecheanceretard($contrat_id){

        $avis = Avisecheance::where('contrat_id',$contrat_id)->where('est_activer','!=', 2)
            ->get();

        //$contrat = Contrat::find($contrat_id);
       // $reservataire = Locataire::find($contrat->locataire_id);

        $avisecheancetotalimpaye = 0;
        $nombreavisecheanceimpaye = 0;
        if(isset($avis) && count($avis) > 0){
            foreach ($avis as $key=>$av)
            {
                $amortissement   = (int)$av->amortissement;
                $fraisgestion    = (int)$av->fraisgestion;
                $fraisdelocation = (int)$av->fraisdelocation;

                $avisecheancetotalimpaye += $amortissement + $fraisgestion + $fraisdelocation;
            }
            $nombreavisecheanceimpaye = count($avis);
        }


        return array(
            "avisecheancetotalimpaye" => $avisecheancetotalimpaye,
            "nombreavisecheanceimpaye"=> $nombreavisecheanceimpaye,
            "reservataire"               => ''
        );

    }


    public static function getfielMountdAvisecheance($contrat_id){

        $avis = Avisecheance::where('contrat_id',$contrat_id)
//            ->selectRaw('CAST(sum(amortissement) AS INT) as totalamortissement')
//            ->selectRaw(DB::raw('CAST(amortissement AS INT) AS amortissement'))
            ->where('est_activer', 2)
            ->get('avisecheances.*');


        $totalAmortissement                   = 0;
        $totalFraisgestion                    = 0;
        $totalFraislocatif                    = 0;
        $total       = 0;

        if(isset($avis) && count($avis) > 0){
            foreach ($avis as $key=>$av)
            {
                $totalAmortissement   += (int)$av->amortissement;
                $totalFraisgestion    += (int)$av->fraisgestion;
                $totalFraislocatif    += (int)$av->fraisdelocation;

                $total                 += ($totalAmortissement + $totalFraisgestion + $totalFraislocatif);
            }
        }


        return array(
            "totalAmortissement"              => $totalAmortissement,
            "totalFraisgestion"               => $totalFraisgestion,
            "totalFraislocatif"               => $totalFraislocatif,
            "total"                           => $total
        );

    }

    public static function meillleurpayeur($query, $args = null){

//        $avis = Paiementecheance::query()
//            ->join("avisecheances", "avisecheances.id", "paiementecheances.avisecheance_id")
//            ->whereDate('paiementecheances.date','<',now())
//            ->get(['paiementecheances.avisecheance_id']);


        $avis = Avisecheance::query()
            ->join('paiementecheances','paiementecheances.avisecheance_id', 'avisecheances.id')
            ->whereRaw('paiementecheances.date < avisecheances.date_echeance')
            ->get(['avisecheances.id']);


        $query =  Contrat::query()
            ->join("avisecheances", "avisecheances.contrat_id", "contrats.id")
//            ->where('avisecheances.periodes',  Outil::getOperateurLikeDB(), '%' . $periode . '%')
            ->whereIn('avisecheances.id',$avis)
//            ->where('contrats.id',$root['id'])
            ->groupBy(['contrats.id'])
            ->selectRaw('contrats.*, count(avisecheances.id) as nbpaiement');

        return $query;
    }



    public static function sendeRelancePaiement(){

        $contrats = self::getLocataireImppayeavisecheanceByPeriode();

        $now = date("Y-m-d");
        $now = explode('-',$now);
        $month = $now[1];
        $day = $now[2];
        $periode = self::getMonthString($month);

//        foreach ($contrats as $key=>$ctr)
//        {
//            if(isset($ctr) && isset($ctr->id) && isset($ctr->locataire_id)){
//
//                $locataire = Locataire::find($ctr->locataire_id);
//
//                if(isset($locataire) && isset($locataire->email)){
//                    $reservataire = $locataire->prenom . ' ' .$locataire->nom;
//
//                    Mail::to('abou050793@gmail.com')
//                        ->send(new Maileurcourierrelance('RELANCE D\'AVIS D\'ECHEANCE', $periode, $reservataire, 'relancethird'));
//                }
//            }
//
//        }

        if($day == 11 ){

            foreach ($contrats as $key=>$ctr)
            {
                if(isset($ctr) && isset($ctr->id) && isset($ctr->locataire_id)){

                    $locataire = Locataire::find($ctr->locataire_id);

                    if(isset($locataire) && isset($locataire->email)){
                        $reservataire = $locataire->prenom . ' ' .$locataire->nom;
                        //$locataire->email

                        // try {
                            Mail::to('oumou.diop@sertem-pm.com')
                            ->cc(['makhtar.sadji@sertem-pm.com','abou050793@gmail.com','kwami@sertemgroupe.com'])
                            ->send(new Maileurcourierrelance('RELANCE D\'AVIS D\'ECHEANCE', $periode, $reservataire, 'relancefirst'));
                        // }
                        // catch (Swift_TransportException $e) {
                        //     return $e->getMessage();
                        // }
                    }
                }

            }

        }else if($day == 18 ){
            foreach ($contrats as $key=>$ctr)
            {
                if(isset($ctr) && isset($ctr->id) && isset($ctr->locataire_id)){

                    $impayeContrat = self::getLocataireavisecheanceretard($ctr->id);
                    $montantpenalite = 0;
                    $nombreretard    = 0;
                    $reservataire       = '';
                    if(isset($impayeContrat)){
                        $montantpenalite =  ($impayeContrat['avisecheancetotalimpaye'] * 10) / 100;
                        $montantpenalite = $impayeContrat['avisecheancetotalimpaye'] + $montantpenalite;
                        $montantpenalite = round($montantpenalite);
                        $montantpenalite = self::formatPrixToMonetaire($montantpenalite);
                        $nombreretard    = $impayeContrat['nombreavisecheanceimpaye'];
                    }

                    $locataire = Locataire::find($ctr->locataire_id);
                    if(isset($locataire) && isset($locataire->email)){
                        $reservataire = $locataire->prenom . ' ' .$locataire->nom;
//                        Mail::to($ctr->email)
//                            ->cc(['makhtar.sadji@sertem-pm.com','kwami@sertemgroupe.com','oumou.diop@sertem-pm.com','abou050793@gmail.com'])
//                            ->send(new Maileurcourierrelance('RELANCE D\'AVIS D\'ECHEANCE', $periode, $reservataire, 'relancesecond',null,$montantpenalite , $nombreretard));

                        // try {
                            Mail::to('abou050793@gmail.com')
                            ->cc(['makhtar.sadji@sertem-pm.com','kwami@sertemgroupe.com','oumou.diop@sertem-pm.com'])
                            ->send(new Maileurcourierrelance('RELANCE D\'AVIS D\'ECHEANCE', $periode, $reservataire, 'relancesecond',null,$montantpenalite , $nombreretard));
                        // }
                        // catch (Swift_TransportException $e) {
                        //     return $e->getMessage();
                        // }


                    }
                }

            }


        }else if($day == 26){
            foreach ($contrats as $key=>$ctr)
            {
                if(isset($ctr) && isset($ctr->id) && isset($ctr->locataire_id)){

                    $locataire = Locataire::find($ctr->locataire_id);

                    if(isset($locataire) && isset($locataire->email)){
                        $reservataire = $locataire->prenom . ' ' .$locataire->nom;

//                        Mail::to($locataire->email)
                        // try {
                            Mail::to('abou050793@gmail.com')
                            ->cc(['makhtar.sadji@sertem-pm.com','abou050793@gmail.com','kwami@sertemgroupe.com','oumou.diop@sertem-pm.com'])
                            ->send(new Maileurcourierrelance('RELANCE D\'AVIS D\'ECHEANCE', $periode, $reservataire, 'relancethird'));
                        // }
                        // catch (Swift_TransportException $e) {
                        //     return $e->getMessage();
                        // }


                    }
                }

            }
        }

        return $contrats;

    }
    public static function locataireRappelPaiement()
    {
        $periode = $now = $month = $year = $jour = "";
        $periode = "periode";
        $query = DB::table('locataires');
        //  dd($periode);
        $locataires = $query
            ->join('contrats', 'locataires.id', '=', 'contrats.locataire_id')
            ->select('locataires.*', 'contrats.rappelpaiement', 'contrats.etat', 'contrats.montantloyer', 'contrats.descriptif')
            ->where('contrats.etat', '=', 1)
            ->whereNotIn('contrats.id', function ($qr) {
                $now = date("Y-m-d");
                $jour = strtotime($now . "- 1 months");
                $jour = date("Y-m-d", $jour);
                $jour = explode("-", $jour);
                $month = $jour[1];
                $year = $jour[0];
                $periode = self::getMonthString($month);
                $periode = $periode . " " . $year;
                $qr->select('contrat_id')->from('paiementloyers')
                    ->where('paiementloyers.periode', '!=', $periode);
            })->get();

        self::sendMailRappelPaiementLoyer($locataires);
    }
    public static function sendMailRappelPaiementLoyer($locataires)
    {

        $now = $dayNow = "";
        $now = date("Y-m-d");
        $now = explode("-", $now);
        $dayNow = $now[2];
        $newLocataire = [];
        $newLocataire = json_decode(json_encode($locataires), true);


        try {
            $montant = self::formatPrixToMonetaire($newLocataire['montantloyer']);
            $maileur = new RappelPaiementLoyer($newLocataire, $montant);

            // try {
                Mail::to($newLocataire['email'])->send($maileur);
            // }
            // catch (Swift_TransportException $e) {
            //     return $e->getMessage();
            // }

        } catch (\Exception $e) {
            // Gérer l'exception, par exemple, en journalisant l'erreur
            // Log::error('Erreur lors de l\'envoi de l\'e-mail de rappel : ' . $e->getMessage());
        }
    }
    public static function getMonthString($month)
    {
        $monthString = "";
        if (isset($month)) {
            switch ($month) {
                case 1:
                    $monthString = "Janvier";
                    break;
                case 2:
                    $monthString = "Février";
                    break;
                case 3:
                    $monthString = "Mars";
                    break;
                case 4:
                    $monthString = "Avril";
                    break;
                case 5:
                    $monthString = "Mai";
                    break;
                case 6:
                    $monthString = "Juin";
                    break;
                case 7:
                    $monthString = "Juillet";
                    break;
                case 8:
                    $monthString = "Août";
                    break;
                case 9:
                    $monthString = "Septembre";
                    break;
                case 10:
                    $monthString = "Octobre";
                    break;
                case 11:
                    $monthString = "Novembre";
                    break;
                case 12:
                    $monthString = "Décembre";
                    break;
            }
            return $monthString;
        }
        return false;
    }
    public static function getMonthNumber($month)
    {
        $monthNumer = 0;
        if (isset($month)) {
            switch ($month) {
                case "Janvier":
                    $monthNumer = 1;
                    break;
                case "Février":
                    $monthNumer = 2;
                    break;
                case "Mars":
                    $monthNumer = 3;
                    break;
                case "Avril":
                    $monthNumer = 4;
                    break;
                case "Mai":
                    $monthNumer = 5;
                    break;
                case "Juin":
                    $monthNumer = 6;
                    break;
                case "Juillet":
                    $monthNumer = 7;
                    break;
                case "Août":
                    $monthNumer = 8;
                    break;
                case "Septembre":
                    $monthNumer = 9;
                    break;
                case "Octobre":
                    $monthNumer = 10;
                    break;
                case "Novembre":
                    $monthNumer = 11;
                    break;
                case "Décembre":
                    $monthNumer = 12;
                    break;
            }
            return $monthNumer;
        }
        return false;
    }
    //

    // Add automatically common attr to table
    public static function listenerUsers(&$table)
    {
        $table->unsignedBigInteger('created_at_user_id')->nullable();
        $table->unsignedBigInteger('updated_at_user_id')->nullable();
        $table->foreign('created_at_user_id')->references('id')->on('users');
        $table->foreign('updated_at_user_id')->references('id')->on('users');
    }

    // Add automatically common attr to table
    public static function stringToTimeInCel($celTime)
    {
        $celTime .= ":00";
        $time_cel = explode(':', $celTime);
        $time_exact = '';
        if (isset($time_cel) && count($time_cel) > 0) {
            if (isset($time_cel[0])) {
                if (is_numeric($time_cel[0])) {
                    $time_exact .= $time_cel[0];
                    if (isset($time_cel[1])) {
                        if (is_numeric($time_cel[1])) {
                            $time_exact .= ":" . $time_cel[1];
                        }
                    } else {
                        $time_exact .= ":00";
                    }
                }
            }
        }
        return $time_exact;
    }


    public static function differenceEntreTime()
    {
        $duree = null;
        $strStart = '06/19/13 18:25';
        $strEnd = '06/19/13 21:47';

        $dteStart = new DateTime($strStart);
        $dteEnd = new DateTime($strEnd);

        $duree = $dteStart->diff($dteEnd);
        return $duree;
    }
    // function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
    public static function dateDifference($timeDebut, $timeFin)
    {
        // $datetime1 = date_create($date_1);
        // $datetime2 = date_create($date_2);
        //dd($timeDebut . $timeFin);
        $strStart = date_create('06/19/13 ' . $timeDebut);
        $strEnd = date_create('06/19/13 ' . $timeFin);

        $interval = date_diff($strStart, $strEnd);
        $differenceFormat = '%h';
        return $interval->format($differenceFormat);
        //return $interval;

    }
    //add nature in some entities
    // Add automatically common attr to table specific
    public static function statusOfObject(&$table)
    {
        $table->integer('est_activer')->default(1);
    }


    // Control nom negatif
    public static function isNegatifOrNotInt($prix)
    {
        if ($prix < 0 || !is_numeric($prix))
            return true;
        else
            return false;
    }

    public static function getCode($item, $date = null)
    {
        $model = self::getQueryNameOfModel($item->getTable());
        $dateCode = '';

        if ($date) {
            $dateCode = self::getDateEng($date);
        } else {
            $dateCode = self::getDateEng(now());
        }
        $dateCode = str_replace('-', '', $dateCode);

        $code = self::generateIndicatif($model) . '-' . $dateCode . '' . self::generateCode($item->id);

        $item->code = $code;
        $item->save();
        return $code;
    }

    public static function getDateEng($date, $format = null)
    {
        $date_at = $date;
        if (!isset($format)) {
            $format = 'Y-m-d';
        }

        if ($date_at !== null) {
            $date_at = $date_at;
            $date_at = date_create($date_at);
            return date_format($date_at, $format);
        } else {
            return '';
        }
    }

    public static function generateCode($id)
    {
        $count = "";
        $id = intval($id);
        if ($id <= 9) {
            if ($id == 0) {
                $id = 1;
            }
            $count = "000" . $id;
        } else if ($id >= 10 && $id <= 99) {
            $count = "00" . $id;
        } else if ($id >= 100 && $id <= 999) {
            $count = "0" . $id;
        } else if ($id > 999) {
            $count = $id;
        } else {
            $count = $id;
        }

        return $count;
    }

    public static function generateIndicatif($model)
    {

        $modelName = class_basename($model);
        $alias = '';
        if (strtolower($modelName) == 'produits') {
            $alias = 'PRD';
        }
        if (strtolower($modelName) == 'clients') {
            $alias = 'CLI';
        }
        if (strtolower($modelName) == 'depots') {
            $alias = 'DPO';
        }
        if (strtolower($modelName) == 'commandes') {
            $alias = 'CMD';
        }
        if (strtolower($modelName) == 'departements') {
            $alias = 'DPT';
        }
        if (strtolower($modelName) == 'fournisseurs') {
            $alias = 'FRS';
        }
        if (strtolower($modelName) == 'bcis') {
            $alias = 'BCI';
        }

        if (strtolower($modelName) == 'bces') {
            $alias = 'BCE';
        }

        if (strtolower($modelName) == 'bes') {
            $alias = 'BEN';
        }

        if (strtolower($modelName) == 'actions') {
            $alias = 'ACT';
        }

        if (strtolower($modelName) == 'proformas') {
            $alias = 'PRF';
        }
        if (strtolower($modelName) == 'bts') {
            $alias = 'BTS';
        }
        if (strtolower($modelName) == 'depenses') {
            $alias = 'DEP';
        }
        if (strtolower($modelName) == 'factures') {
            $alias = 'FAC';
        }

        if (strtolower($modelName) == 'assemblages') {
            $alias = 'ASS';
        }
        if (strtolower($modelName) == 'cloture_caisses' || strtolower($modelName) == 'cloturecaisses') {
            $alias = 'CTC';
        }

        return $alias;
    }


    public static function getCodeMenu($model, $entite)
    {
        $count = count(app($model)::where('is_menu', true)->get());
        $count = $count == 0 ?: 1;
        return 'Menu-' . $entite . '-' . $count;
    }


    public static function Checkdetail($olddata, array $newdata, $model, $columns)
    {
        if (!is_array($columns)) {
            $columns = array($columns);
        }
        foreach ($olddata as $onedetail) {
            $retour = false;
            foreach ($newdata as $value) {
                $retour = true;
                foreach ($columns as $keyColumn => $onecolumn) {
                    if (isset($value[$onecolumn]))
                        if ($onedetail->$onecolumn != $value[$onecolumn]) {
                            $retour = false;
                            break;
                        }
                }
                if ($retour)
                    break;
            }
            // dd($retour);
            if ($retour == false) {
                $iem = app($model)::find($onedetail->id);
                // dd($iem);
                if ($iem) {
                    $iem->delete();
                    $iem->forceDelete();
                }
            }
        }
    }

    public static function redirectIfModeliSSaved($item, $queryName = null)
    {
        $item->save();
        // self::setUpdatedAtUserId($item);
        $id = $item->id;
        if (!isset($queryName)) {
            $queryName = self::getQueryNameOfModel($item->getTable());
        }

       // Outil::publishEvent(['type' => substr($queryName, 0, (strlen($queryName) - 1)), 'add' => true]);

        return self::redirectGraphql($queryName, "id:{$id}", self::$queries[$queryName]);
    }

    // Upload any file
    public static function uploadFileToModel(&$request, &$item, $file = "image")
    {
        if (!empty($request->file($file))) {
            $fichier = $_FILES[$file]['name'];
            $fichier_tmp = $_FILES[$file]['tmp_name'];
            $ext = explode('.', $fichier);
            $rename = config('view.uploads')[self::getQueryNameOfModel($item->getTable())] . "/{$file}_" . $item->id . "." . end($ext);
            //$rename = config('view.uploads')['actualites']."/{$file}_".$item->id.".".end($ext);
            move_uploaded_file($fichier_tmp, $rename);
            $item->$file = $rename;
        } else if ($request->$file . "_erase") // Allows you to delete the user's image
        {
            $item->$file = null;
        }
        $item->save();
    }


    public static function setUpdatedAtUserId(Model &$item)
    {
        if ($item->wasChanged() || $item->isDirty()) {
            $item->updated_at_user_id = Auth::user()->id;
            $item->save();
        }
    }

    public static function saveZone($item, $array)
    {
        $item = new Zone();
        $item->designation = $array["designation"];
        $item->zone_id     = $array["zone_id"];
        $item->save();
        return $item;
    }

    // Used to send the report after importing an Excel file
    public static function atEndUploadData($pathFile, $generateLink, $report, $user, $totalToUpload, $totalUpload, $importOf, $type = null, $type_second = null)
    {
        // After import, we can delete the file
        File::delete($pathFile);
        // dd($report);
        // Sending notification
        /*$notif = new Notif();
        $notif->message = "<strong>Fin de l'import du fichier excel {$importOf}</strong>,<br>Merci de consulter vos mails pour le rapport";
        $notif->link = "#!/list-{$generateLink}";
        $notif->save();
        $notifPermUser  = new NotifPermUser();
        $notifPermUser->notif_id = $notif->id;
        $notifPermUser->permission_id = Permission::where('name', "creation-{$generateLink}")->first()->id;
        $notifPermUser->user_id = $user->id;
        $notifPermUser->save();*/

        //  $eventNotif = new SendNotifEvent($notifPermUser);
        // event($eventNotif);

        // Outil::publishEvent(['type' => $generateLink, 'add' => true]);

        // Sending the email conaining the report

        // Send data to the view using loadView function of PDF facade
        // $pdf = App::make('dompdf.wrapper');

        //     $pdf =    $pdf->loadView('pdfs.report-uploadfile-item', array(
        //         'reports'       => $report,
        //         'user'          => $user,
        //         'title'         => 'Rapport de l\'import du fichier ' . $importOf,
        //         'totals'        => [
        //             'toUpload'     => $totalToUpload,
        //             'upload'       => $totalUpload,
        //         ],
        //         'addToData' => array('entete' => null, 'hidefooter' => true)
        //     ));
        // $objDemo = new \stdClass();
        // $objDemo->demo_one = $importOf;
        // $objDemo->demo_two = 'Demo Two Value';
        // $objDemo->sender = 'SCI REYHAN';
        // $objDemo->receiver = isset($user) ? $user->name : 'Non reconnu';
        // Mail::to(isset($user->email) ?$user->email :  "abou050793@gmail.com")->send(new DemoEmail($objDemo,$pdf));
        //    Mail::to("libasselayembayedev@gmail.com")
        //        ->cc("lmbaye@h-tsoft.com")
        //        ->send(new DemoEmail($objDemo,$pdf));
        // $user->notify(new EndUploadExcelFileNotification($pdf, $importOf));
        // Outil::publishEvent(['type' => substr($type, 0, (strlen($type) - 1)), 'add' => true]);
        // return self::redirectGraphql($type, null, self::$queries[$type]);

        return isset($type) ? Outil::redirectIfModeliSSaved($type) : null;
    }

    //Elle formate les dates au format anglais en format français
    public static function getCreatedDateFr($root, $args)
    {

        $created_at = !(isset($root['created_at'])) ? $root->created_at : $root['created_at'];

        if (!isset($created_at))
            return null;
        return Carbon::parse($created_at)->format('d/m/Y H:i:s');
    }

    //Elle formate les dates au format anglais en format français
    public static function getUpdatedDateFr($root, $args)
    {

        $updated_at = !(isset($root['updated_at'])) ? $root->updated_at : $root['updated_at'];

        if (!isset($updated_at))
            return null;
        return Carbon::parse($updated_at)->format('d/m/Y H:i:s');
    }

    public static function getPermissionTypeTransaction()
    {
        return config('env.PERMISSION_TRANSACTION');
    }

    public static function getPermissionTypeTransaction2()
    {
        return config('env.PERMISSION_TRANSACTION2');
    }
    public static function isAuthorize($currentUser = true, $userId = null)
    {
        //Récupration utilisateur
        if ($currentUser) {
            $user = Auth::user();
            if (empty($user)) {
                $user = User::where('email', 'info-scireyhan@gmail.com')->first();
            }
        } else {
            $user = User::find($userId);
        }

        //Test
        if ($user->can(self::getPermissionTypeTransaction2())) {
            $retour = 2;
        } else if ($user->can(self::getPermissionTypeTransaction())) {
            $retour = 1;
        } else {
            $retour = 0;
        }

        return $retour;
    }

    public static function canCreateWithSelfValidation($for)
    {
        $permission = "creation-" . (substr($for, 0, strlen($for) - 1)) . "-auto-validation";
        return (Auth::user() && auth()->user()->can($permission));
    }

    public static function getNameRoleOfOthersDepots()
    {
        return 'autre-depot';
    }

    public static function saveUserPassword($user, $password = null)
    {
        $user->password = Hash::make(isset($password) ? $password : 'passer@123');
    }


    public static function getAPI()
    {
        return config('env.APP_URL');
    }

    public static function getResponseError(\Exception $e)
    {
        return response()->json(array(
            'errors' => [$e->getMessage()],
            'errors_debug' => [$e->getMessage()],
            'errors_line' => [$e->getLine()],
        ));
    }

    protected function resolveDateToTexte($value)
    {
        $date = \Carbon\Carbon::parse($value);
        return $date->translatedFormat(' j F Y');
    }

    protected function resolveDateFrDField($date)
    {
        $date_at = date_create($date);
        return date_format($date_at, "d");
    }

    protected function resolveDateFrMField($date)
    {
        $date_at = date_create($date);
        return date_format($date_at, "m");
    }

    protected function resolveDateFrYField($date)
    {
        $date_at = date_create($date);
        return date_format($date_at, "Y");
    }

    protected function resolveDateFr($date)
    {

        $date_at = date_create($date);
        return date_format($date_at, "d/m/Y");
    }



    public static function getOperateurLikeDB()
    {
        return config('database.default') == "mysql" ? "like" : "ilike";
    }


    // Gives the normalized name of the query according to the name of the database
    public static function getQueryNameOfModel($nameTable)
    {
        return str_replace("_", "", $nameTable);
    }

    public static function getTypeDecimalDB()
    {
        return config('database.default') == "mysql" ? "" : "::decimal";
    }
    public static function getMsgError()
    {
        return config('env.MSG_ERROR');
    }

    // Like name, resolve image with correct base_url
    public static function resolveImageField($image)
    {
        if (!isset($image)) {
            $image = "/assets/images/upload.jpg";
        } else {
            // In the event that an image exists in the database, in versioning
            $image = $image . '?date=' . (date('Y-m-d H:i'));
        }

        if (isset($image))
            return Outil::getAPI() . $image;
        return $image;
    }

    // Publish the event on the channel for RealTime
    public static function publishEvent($data)
    {
        $eventRT = new RtEvent($data);
        event($eventRT);
    }

    public static function getEtat($etat)
    {
        switch ($etat) {
            case 1:
                return 2;
                break;
            case 2:
                return 3;
                break;
            case 4:
                return 4;
                break;
            case 0 || null:
                return 2;
                break;
            default;
        }
    }

    public static $queries = array(
        //-------------DEBUT ==> MES REQUETES PERSONNALISEES--------------------//
        //markme-AJOUT
        "avenants" => "id,descriptif",
        "permissions" => "id,name,display_name,guard_name",
        "securites" => "id",
        "roles" => "id,name,guard_name,permissions{id,name,display_name,guard_name}",
        "users" => "id,name,email,image,roles{id,name,permissions{name}},created_at_fr",
        "preferences" => "id,designation,valeur,description",
        "typeappartements" => "id,designation,appartements{id}",
        "typecontrats" => "id,designation,contrats{id}",
        "categorieprestataires" => "id,designation,prestataires{id}",
        "categorieinterventions" => "id,designation,image,interventions{id}",
        "horaires" => "id,designation,debut,fin",
        "typedocuments" => "id,designation,documents{id}",
        "typefactures" => "id,designation,factures{id}",
        "typeinterventions" => "id,designation,interventions{id}",
        "typelocataires" => "id,designation,locataires{id}",
        "typeobligationadministratives" => "id,designation,obligationadministratives{id}",
        "typepieces" => "id,designation,immeubles{id}",
        "questionnaires" => "id,designation,nom,nombre,reponsetype,typequestionnaire{id,designation}",
        "typerenouvellements" => "id,designation,contrats{id}",
        "pieceappartements" => "id,designation,appartement{id,nom} ,immeuble{id,nom},typepiece{id,designation} ,  appartement_id,immeuble_id,typepiece_id,etatlieus{id}",

        "appartements" => "id,location_details,position,codeappartement,isdemanderesiliation , locataire_id, niveau , image, etatlieu , superficie , immeuble_id,nom,isassurance,iscontrat,islocataire,immeuble{id,nom,adresse,equipegestion{id,designation}}, proprietaire{id,prenom,nom} ,typeappartement{id,designation}, frequencepaiementappartement{id,designation},etatappartement{id,designation},pieceappartements{id,designation},locataires{id,date_naissance,lieux_naissance,pays_naissance,mandataire},contrats{id,montantcommission,appartement{id,nom,proprietaire{id,nom,prenom}} , codeappartement,document,scanpreavis,descriptif,documentretourcaution,montantloyer,montantloyerbase,montantloyertom,montantcharge,tauxrevision,frequencerevision,dateenregistrement,daterenouvellement,datepremierpaiement,dateretourcaution,datedebutcontrat,typecontrat{id},typerenouvellement{id},delaipreavi{id},locataire{id,date_naissance,lieux_naissance,pays_naissance,mandataire,prenom,nom,nomentreprise},caution{id,document,montantloyer,montantcaution,codeappartement,dateversement,datepaiement,etat,contrat_id},etat},factures{id},annonces{id},contrats{id,paiementloyers{datepaiement,facturelocation{id,datefacture},montant_paiement}},",
        "villas" => "id,codeappartement, isdemanderesiliation , locataire_id, niveau , image, etatlieu , superficie , immeuble_id,nom,isassurance,iscontrat,islocataire,immeuble{id,nom,adresse,equipegestion{id,designation}}, proprietaire{id,prenom,nom} ,typeappartement{id,designation}, frequencepaiementappartement{id,designation},etatappartement{id,designation},pieceappartements{id,designation},locataires{id,date_naissance,lieux_naissance,pays_naissance,mandataire},contrats{id,appartement{id,nom,proprietaire{id,nom,prenom}} , codeappartement,document,scanpreavis,descriptif,documentretourcaution,montantloyer,montantloyerbase,montantloyertom,montantcharge,tauxrevision,frequencerevision,dateenregistrement,daterenouvellement,datepremierpaiement,dateretourcaution,datedebutcontrat,typecontrat{id},typerenouvellement{id},delaipreavi{id},locataire{id,date_naissance,lieux_naissance,pays_naissance,mandataire,prenom,nom,nomentreprise},caution{id,document,montantloyer,montantcaution,codeappartement,dateversement,datepaiement,etat,contrat_id},etat},obligationadministratives{id},factures{id},annonces{id},rapportinterventions{id},contrats{id},",



        "immeubles" => "id,nom,adresse,gardien{id,prenom,nom,adresse,telephone1,telephone2}, structureimmeuble_id , structureimmeuble{id,designation,etages}, equipegestion{id,designation}, nombreappartement,nombregroupeelectrogene, nombreascenseur,nombrepiscine,iscopropriete,equipegestion{id,designation},pieceappartements{id,designation},proprietaires{id,nom},appartements{id,nom,codeappartement,immeuble{id}},annonces{id,titre,debut,fin,description},,created_at_fr",

        "proprietaires" => "id,montanttotalloyer,sommedepense,nom,adresse,prenom,telephone,profession,age,telephoneportable,telephonebureau,immeubles{id,nom,adresse,nombreascenseur,nombrepiscine,iscopropriete},appartements{id,nom,codeappartement,contrats{montantloyer}},versementloyers{id},factures{id,montant}",

        "immeuble_proprietaires" => "id,immeuble{id,nom},proprietaire{id,nom}",


        "locataires" => "id,date_naissance,lieux_naissance,pays_naissance,mandataire,prenom,nom,telephoneportable1,telephoneportable2,telephonebureau,profession, email,age,cni,passeport,nomentreprise,adresseentreprise,ninea,documentninea,numerorg,documentnumerorg,documentstatut,personnehabiliteasigner,fonctionpersonnehabilite,nompersonneacontacter,prenompersonneacontacter,emailpersonneacontacter,telephone1personneacontacter,telephone2personneacontacter,etatlocataire,typelocataire_id,observation_id,appartements{id}",
        "equipegestions" => "id,designation,immeubles{id,nom,adresse}",


        "contrats" => "id,descriptif,locataire{nomentreprise,nom,prenom},montantloyer,montantloyerformat,appartement{immeuble{adresse}} ",


        "contrats2" => "id,est_copreuneur,est_soumis,recap_amount_ridwan,recap_amount_ridwan_format,frais_gestion_format,frais_gestion,periodes_non_payes{id,designation},signaturedirecteur,signatureclient,document,caution_format,montantloyerformat,montantloyerbaseformat,montantloyertomformat,montantchargeformat,montantloyerformatletter,total_loyer_format,total_loyer,frequencerevision_format,preavis_format,montantchargeformat,scanpreavis,descriptif,documentretourcaution,documentrecucaution,montantloyer,montantloyerbase,montantloyertom,montantloyertomformat,montantcharge,tauxrevision,frequencerevision,dateenregistrement,daterenouvellement,datepremierpaiement,dateretourcaution,datedebutcontrat,datedebutcontrat_format,etat,typecontrat{id},typerenouvellement{id},delaipreavi{id,designation},appartement{id,nom,codeappartement, proprietaire{id,prenom,nom},lot,ilot{id,numero,adresse},entite{id,code,image},immeuble{id,adresse,nom},typeappartement{id,designation,typeappartement_pieces{id,designation,typepiece{id,designation}}}},locataire{id,date_naissance,date_naissance_format,lieux_naissance,pays_naissance,mandataire,prenom,email,nom,nomentreprise,typelocataire_id,adresseentreprise,cni,telephoneportable1,emailpersonneacontacter,ninea,documentninea,numerorg,personnehabiliteasigner,fonctionpersonnehabilite,nompersonneacontacter,prenompersonneacontacter},caution{id,montantcaution,dateversement},assurances{id},versementloyers{id},versementchargecoproprietes{id},paiementloyers{id},demanderesiliations{id}",



        "locationventes" => "id,apportiponctuel_format,nbr_loyer_payes_ridwan,total_loyer_verser_ridwan,est_copreuneur,copreneur_id,copreneur{ id , nom ,prenom ,email ,adresse, lieunaissance, datenaissance , datenaissance_format, situationfamiliale, codepostal , ville , pays , nationalite, profession , njf , cni , passeport , telephone1 , telephone2 },apportinitial_format_lettre,est_soumis,prixtotalvilla,prixtotalvilla_format,numerodossier,signaturedirecteur,signatureclient,acompte_percent,ridwan_montant_verse,ridwan_montant_restant,recap_amount_ridwan,recap_amount_ridwan_format,frais_gestion_format,frais_gestion,echeance_encours,montantloyerformatletter,periodes_non_payes{id,designation},apportinitial_format,apportinitial,acompteinitial_format,acompteinitial_format_lettre,acompteinitial,fraiscoutlocationvente_format,document,scanpreavis,acompte_valeur,reliquat,depot_initial,depot_initial_format,prixvilla,prixvillaformat,acompteinitial,maturite,periodicite_id,periodicite{id,designation},dateremisecles,dateremiseclesformat,apportinitial,apportiponctuel,dateecheance,dateecheanceformat,dureelocationvente,clausepenale,fraiscoutlocationvente,acompteinitial,prixvilla,indemnite,document,retourcaution,status,etat_text,etat_badge,scanpreavis,descriptif,documentretourcaution,documentrecucaution,montantloyer,montantloyerformat,montantloyerbase,montantloyerbaseformat,montantloyertom,montantloyertomformat,montantcharge,montantchargeformat,tauxrevision,frequencerevision,dateenregistrement,dateenregistrement_format,daterenouvellement,daterenouvellement_format,datepremierpaiement,datepremierpaiement_format,dateretourcaution,datedebutcontrat,datedebutcontrat_format,rappelpaiement,etat,typecontrat{id,designation},typerenouvellement{id,designation},delaipreavi{id,designation},appartement{id,superficie,entite{id,designation, nomcompletnotaire,adressenotaire,adresseetudenotaire, emailnotaire, telephone1notaire , nometudenotaire, emailetudenotaire,  telephoneetudenotaire , assistantetudenotaire},nom,lot,ilot_id,compositions{id, image , superficie, typeappartement_piece{id,designation,typeappartement{id,designation},typepiece_id,typepiece{id,designation},commentaire,typeappartement_id},appartement{id,nom},appartement_id,typeappartement_piece_id,niveauappartement_id,niveauappartement{id,designation}},typeappartement{id,designation,typeappartement_pieces{id,designation,typepiece_id,typepiece{id,designation}niveauappartement_id,niveauappartement{id,designation}}},
        ilot{id,numero,adresse,numerotitrefoncier,datetitrefoncier,adressetitrefoncier},iscontrat,isdemanderesiliation,codeappartement,etatlieu,proprietaire{id,prenom,nom},immeuble{id,nom,adresse}},locataire{id,est_copreuneur,copreneurs{ id , nom ,prenom ,email ,adresse, lieunaissance, datenaissance, situationfamiliale, codepostal , ville , pays , nationalite, profession , njf , cni , passeport , telephone1 , telephone2 },ville,situationfamiliale,codepostal,nationalite,njf,numeroclient,prenom,passeport,nom,telephoneportable1,telephoneportable2,telephonebureau,profession, email,age,cni,passeport,nomentreprise,adresseentreprise,ninea,documentninea,numerorg,documentnumerorg,documentstatut,personnehabiliteasigner,fonctionpersonnehabilite,nompersonneacontacter,prenompersonneacontacter,emailpersonneacontacter,telephone1personneacontacter,telephone2personneacontacter,etatlocataire, revenus, contrattravail , expatlocale , nomcompletpersonnepriseencharge , telephonepersonnepriseencharge,date_naissance,date_naissance_format ,pays_naissance , lieux_naissance , mandataire,typelocataire_id,typelocataire{id,designation},observation_id,appartements{id,nom,proprietaire{id,prenom,nom}},contrats{id,descriptif,etat,datedebutcontrat,montantloyer,appartement{id,nom,frequencepaiementappartement_id,proprietaire{id,prenom,nom}}},interventions{id},messages{id},questionnairesatisfactions{id}},caution{id,montantcaution,dateversement,document},assurances{id},versementloyers{id},versementchargecoproprietes{id},paiementloyers{id,datepaiement,codefacture,montantfacture,periode,debutperiodevalide,finperiodevalide,contrat{id,document,scanpreavis,descriptif,documentretourcaution,documentrecucaution,montantloyer,montantloyerbase,montantloyertom,montantcharge,tauxrevision,frequencerevision,dateenregistrement,daterenouvellement,datepremierpaiement,dateretourcaution,datedebutcontrat,etat,typecontrat{id},typerenouvellement{id,designation},delaipreavi{id,designation},appartement{id,nom,codeappartement,immeuble{id,nom,adresse}},locataire{id,prenom,nom,nomentreprise},caution{id},assurances{id},versementloyers{id},versementchargecoproprietes{id}}},demanderesiliations{id}",



        "paiementloyers" => "id,datepaiement,numero_cheque,datepaiement_format,codefacture,montantfacture,montant_paiement_format,montantfacture_format,montant_paiement,periode,modepaiement{id,designation,code},detailpaiements{id,montant,montant_format,periode_id,periode_text,periode{id,designation}},debutperiodevalide,finperiodevalide,contrat{id,document,scanpreavis,descriptif,documentretourcaution,documentrecucaution,montantloyer,total_loyer_format,total_loyer,montantloyerbase,montantloyerbaseformat,montantloyertom,montantloyertomformat,montantcharge,montantchargeformat,tauxrevision,frequencerevision,dateenregistrement,daterenouvellement,datepremierpaiement,dateretourcaution,datedebutcontrat,etat,typecontrat{id},typerenouvellement{id},delaipreavi{id},appartement{id,nom,codeappartement,entite{id,designation,code,image},immeuble{id,nom,adresse}},locataire{id,date_naissance,lieux_naissance,pays_naissance,mandataire,prenom,nom,nomentreprise,adresseentreprise,telephone1personneacontacter,emailpersonneacontacter,telephoneportable1,email}, locataire_id , caution{id},versementloyers{id}}",



        "demanderesiliations" =>  "id,datedebutcontrat,datedemande,delaipreavisrespecte,raisonnonrespectdelai,delaipreavi{id,designation},dateeffectivite,contrat{id,appartement{id,nom,entite{id,image,designation},locataire{id,nom,prenom,nomentreprise,ninea}},retourcaution,descriptif,montantloyer,montantloyerformat,montantloyerbase,montantloyertom,montantloyertomformat,montantcharge,montantchargeformat,tauxrevision,tauxrevision_format,frequencerevision,dateenregistrement,daterenouvellement,datepremierpaiement,rappelpaiement,dateretourcaution,datedebutcontrat,datedebutcontrat_format,typecontrat{id,designation},delaipreavi{id,designation},typerenouvellement{id,designation},locataire{id,typelocataire{designation},appartements{id,nom,lot,immeuble{id,nom,adresse}},prenom,nom,email,telephoneportable1,nomentreprise,adresseentreprise,ninea,entite{id,image,designation}},caution{id,montantloyer,montantcaution,dateversement,datepaiement},versementloyers{id},paiementloyers{id}},document",

        "demandeinterventions" =>  "id,interventions{id},devi{id,code,est_activer},designation,locataire{date_naissance,lieux_naissance,pays_naissance,mandataire,prenom,nom,nomentreprise},appartement{id,nom},membreequipegestion{id},immeuble{id,nom}",
        "etatlieus" => "id,appartement_id,factureintervention{id,montant,montant_format,est_activer},locataire_id,locataire{id,typelocataire{id,designation},adresseentreprise,entite{id,image},nom,prenom,nomentreprise,contrats{id, date_dernier_facture_eau,etatlieu_sortie{id  factureintervention{montant}} ,somme_a_restituer,montant_dernier_facture_eau,factureeauxs{id,montantfacture,soldeanterieur,finperiode},facturelocations{is_paid_text,datefacture,mois_echeance_format,annee_echeance_format,date_echeance_format,date_echeance,id,is_paid,typefacture{id designation}},caution{id,montantcaution},locataire{id},appartement{id}}},designation,devi{id},dateredaction,particularite,etatgenerale,appartement{id,locataire_id,entite{id,image},factureinterventions{id,montant,appartement_id,locataire_id,montant_format,est_activer},nom,immeuble{id,nom,adresse},contrats{id,locataire{id},appartement{id},total_loyer_format,facturelocations{id objetfacture paiementloyers{id} typefacture{id designation }},caution{id,montantcaution},locataire{id,factureinterventions{id,montant,montant_format,est_activer},prenom,nom,nomentreprise}}}",
        "prestataires" => "id,nom,adresse,email,telephone1,telephone2,interventions{id},contacts{id},contratprestations{id}",
        "interventions" => "id,descriptif,etatlieu{id},dateintervention,datefinintervention,categorieintervention{id,designation},typeintervention{id,designation},demandeintervention{id,designation},prestataire{id},locataire{prenom,nom,nomentreprise},etat,facture{id},",
        "factures" => "id,datefacture,datefacture_format,moisfacture,documentfacture,recupaiement,montant,intervenantassocie,periode,partiecommune,intervention{id,descriptif},typefacture{id,designation},appartement{id,nom,immeuble{id,nom,adresse},locataire{id,nom,prenom,nomentreprise,telephoneportable1,email,emailpersonneacontacter,telephone1personneacontacter}}",
        "factureinterventions" => "id,etatlieu{devi{object,code,id,detaildevis{id,detaildevisdetails{id,quantite,prixunitaire,unite{designation},soustypeintervention{designation}},categorieintervention{id designation}}}},montant,appartement_id,locataire_id,est_activer,paiementintervention{id est_activer modepaiement_id modepaiement{designation,} montant date},demandeintervention{devi{object,code,id,detaildevis{id,detaildevisdetails{id,quantite,prixunitaire,unite{designation},soustypeintervention{designation}},categorieintervention{id designation}}}},datefacture,montant,appartement{id,nom immeuble{id adresse}},locataire{id,nom,nomentreprise,email,ninea,prenom,entite{id,image,designation}},intervention{id,descriptif}",
        "paiementinterventions" => "id,date,montant,est_activer",
        "cautions" => "id,document,montantloyer,montantcaution,codeappartement,dateversement,datepaiement,etat",
        "assurances" => "id,descriptif,montant,debut,fin,document,assureur{id},etatassurance{id},contrat{id}",
       
        "versementloyers" => "id,dateversement,debut,fin,montant,document,contrat{id,document,scanpreavis,descriptif,documentretourcaution,documentrecucaution,montantloyer,montantloyerbase,montantloyertom,montantcharge,tauxrevision,frequencerevision,dateenregistrement,daterenouvellement,datepremierpaiement,dateretourcaution,datedebutcontrat,etat,typecontrat{id},typerenouvellement{id},delaipreavi{id},appartement{id,nom,codeappartement,immeuble{id,nom,adresse}},locataire{id,prenom,nom,nomentreprise},caution{id},assurances{id},versementloyers{id}},proprietaire{id,nom,prenom,adresse,telephone,profession,age,telephoneportable,telephonebureau,immeubles{id,nom,adresse,nombreascenseur,nombrepiscine,iscopropriete},appartements{id,nom,codeappartement},questionnairesatisfactions{id}}",

        "obligationadministratives" => "id,designation,debut,fin,montant,document,typeobligationadministrative_id,immeuble_id,appartement_id,typeobligationadministrative{id},immeuble{id},appartement{id}",

        "versementchargecoproprietes" => "id,dateversement,anneecouverte,montant,document,contrat{id,document,scanpreavis,descriptif,documentretourcaution,documentrecucaution,montantloyer,montantloyerbase,montantloyertom,montantcharge,tauxrevision,frequencerevision,dateenregistrement,daterenouvellement,datepremierpaiement,dateretourcaution,datedebutcontrat,etat,typecontrat{id},typerenouvellement{id},delaipreavi{id},appartement{id,nom,codeappartement,immeuble{id,nom,adresse}},locataire{id,prenom,nom,nomentreprise},caution{id},assurances{id},versementloyers{id},versementchargecoproprietes{id}},proprietaire{id,nom,prenom,adresse,telephone,profession,age,telephoneportable,telephonebureau,immeubles{id,nom,adresse,nombreascenseur,nombrepiscine,iscopropriete},appartements{id,nom,codeappartement},versementloyers{id},versementchargecoproprietes{id},messages{id},questionnairesatisfactions{id}}",
        "contratprestations" => "id,datesignaturecontrat,datedemarragecontrat,daterenouvellementcontrat,datepremiereprestation,datepremierefacture,document,montant,frequencepaiementappartement{id},categorieprestation{id},prestataire{id}",
        "annonces" => "id,titre,debut,fin,description,appartement{id,nom,immeuble{id,nom}}",
        "messages" => "id,objet,contenu,locataires{id,prenom,nom},proprietaires{id,prenom,nom}, documents{id,chemin}",
        "questionnairesatisfactions" => "id,titre,contenu,locataires{id,prenom,nom,nomentreprise},proprietaires{id,prenom,nom}",
        "membreequipegestions" => "id,prenom,nom,email,telephone,interventions{id,descriptif},demandeinterventions{id} ",
        "rapportinterventions" => "id,prenom,compagnietechnicien, intervention{id} , debut,fin,duree,observations,etat,recommandations,appartement{id,nom,immeuble{id,nom}}",
        "typeassurances" =>  "id,designation,assurances{id}",
        "structureimmeubles" =>  "id,designation,immeubles{id}",
        "equipementpieces" => "id,designation,generale",
        "ilots" => "id,numero,adresse",
        "entites" => "id,designation,description,image",
        "detailcompositions" => "id,idDetailtypeappartement ,appartement_id,appartement{id},composition{id,typeappartement_piece{id,designation,typeappartement{id,designation},typepiece{id,designation},commentaire,typeappartement_id},appartement{id,nom},appartement_id,typeappartement_piece_id},equipement{id,designation,generale},equipement_id,composition_id",
        "modepaiements" => "id,designation,description",
        "secteuractivites" => "id,designation,description",
        "compositions" => "id, image , superficie, typeappartement_piece{id,designation,typeappartement{id,designation},niveauappartement_id,niveauappartement{id,designation},typepiece_id,typepiece{id,designation},commentaire,typeappartement_id},appartement{id,nom},appartement_id,typeappartement_piece_id,niveauappartement_id,niveauappartement{id,designation}",

        "inboxs" => "id,subject,appartement_id,locataire_id",


        "facturelocations" => "id ,paiementloyers{id,montant_paiement},periodes_text,montant_total,montant,montantloyer_avenant,montantloyerbase_avenant,montantloyertom_avenant,montantcharge_avenant,annee_echeance_format,date_echeance,date_echeance_format,mois_echeance_format,periodicite_id,contrat_id,objetfacture,datefacture,datefacture_format,nbremoiscausion
            contrat{id montantcommission nomcompletbeneficiaire telephonebeneficiaire emailbeneficiaire montantloyerbase apportinitial prixvilla prixvillaformat total_loyer_format total_loyer_format total_loyer montantloyer frais_gestion_format frais_gestion echeance_encours recap_amount_ridwan recap_amount_ridwan_format dateecheanceformat montantloyerformat
                 locataire{nomentreprise nom prenom email nompersonneacontacter adresse prenompersonneacontacter adresseentreprise } montantloyertom appartement{id,tlvmountant,contratproprietaire{commissionpourcentage,commissionvaleur},proprietaire{nom,id,prenom},tlv,tvamountant,tva,nom,entite{id,code,image},immeuble{id,nom,adresse} lot ilot{id,numero,adresse}}  montantcharge descriptif }
                  periodicite{id designation nbr_mois} ,typefacture{id designation} motif_annulation_paiement , date_annulation_paiement_format, date_reactivation_paiement_format , justificatif_paiement",


        "facturelocations2" => "id",
        "devis" => "id date date_fr demandeintervention_id est_activer  object  code  detaildevi{id categorieintervention_id} detaildevisdetails{id  unite{id designation} quantite prixunitaire  id soustypeintervention{id designation}  detaildevi{id devi{id} categorieintervention{id  designation}}}  id date  object    id demandeintervention{id appartement{id nom entite{id designation description } } immeuble{nom adresse}}  ",
        "detaildevis" => "id devi_id  categorieintervention{id designation} categorieintervention_id detaildevisdetails{   unite{id designation} id quantite prixunitaire soustypeintervention{id designation}} devi{id date code est_activer} categorieintervention{id designation}",
        "soustypeinterventions" => "id designation",
        "detaildevisdetails" => "id unite{id designation} quantite prixunitaire soustypeintervention{id designation} detaildevi{id devi{id est_activer code date object etatlieu{id appartement{id immeuble{nom adresse} nom entite{id designation image description }} } demandeintervention{id appartement{id nom entite{id designation image description } } immeuble{nom adresse}}} categorieintervention{id  designation}}",
        "unites" => "id designation",
        "paiementinterventions" => "id",
        "avisecheances" => "id,created_at,montant_total,total_montant,fraisgestion,amortissement,fraisdelocation,annee_echeance_format,date_echeance,date_echeance_format,mois_echeance_format,periodicite_id,contrat_id,objet,date,periodes, contrat{ id montantloyerbase apportinitial prixvilla prixvillaformat total_loyer_format total_loyer_format total_loyer montantloyer frais_gestion_format frais_gestion echeance_encours recap_amount_ridwan recap_amount_ridwan_format dateecheanceformat montantloyerformat locataire{nomentreprise, nom, prenom, email, soldeclient, soldeclient_format} montantloyertom appartement{id,nom,immeuble{id,nom} lot ilot{id,numero,adresse}}  montantcharge descriptif } periodicite{id designation},get_montantenattente,get_montantenattente_format",
        "avisecheances2" => "id,contrat_id,contrat{id , locataire_id}",
        "paiementecheances" => "id,montant,montant_format,montant_format_letter,date,avisecheance_id,avisecheance{id ,contrat_id , fraisgestion , amortissement , date , date_echeance},montantenattente",
        "factureacomptes" => "id,montant,montant_format,date_echeance,date_echeance_format,contrat_id,est_activer,commentaire,date, contrat{ id est_copreuneur copreneur_id copreneur{ id nom prenom email } montantloyerbase apportinitial prixvilla prixvillaformat total_loyer_format total_loyer_format total_loyer montantloyer frais_gestion_format frais_gestion echeance_encours recap_amount_ridwan recap_amount_ridwan_format dateecheanceformat montantloyerformat locataire{nomentreprise nom prenom email} montantloyertom appartement{id,nom,immeuble{id,nom} lot ilot{id,numero,adresse}}  montantcharge descriptif }",
        "factureeauxs" => "id,montantfacture,soldeanterieur,prixmetrecube,consommation,quantitefin,quantitedebut,debutperiode_format,finperiode_format,finperiode,dateecheance_fr,debutperiode,montantfacture_format,finperiode_fr,contrat{nomcompletbeneficiaire, telephonebeneficiaire, emailbeneficiaire,locataire{nomentreprise nom prenom email nompersonneacontacter prenompersonneacontacter adresseentreprise }, date_dernier_facture_eau,appartement{nom immeuble{nom adresse}} locataire{entite{image},typelocataire{designation},nomentreprise,nom,prenom,adresseentreprise}}",
        "niveauappartements" => "id,designation,nombre",
        "apportponctuels" =>"id",
        "typeapportponctuels"=>"id",
        "contratproprietaires"=>"id,date,commissionvaleur,commissionpourcentage,proprietaire{id,nom,prenom,,immeubles{nom,adresse,appartements{nom}},appartements{id,nom,locataires{id,nom,nomentreprise,prenom},commissionpourcentage,commissionvaleur}}",
        "modelcontrats"=>"id",

        //-------------FIN ==> MES REQUETES PERSONNALISEES--------------------//
    );


    public static $guzzleOptions = ['cert' => 'gestion_immo/app/cacert.pem'];

    // Permet de créer les elements default du systeme
    public static function FirstLaunch()
    {
        $query = null;
        return $query;
    }


    public static function redirectgraphql($itemName, $critere, $liste_attributs)
    {
        $path = '{' . $itemName;

        if (isset($critere)) {
            $path .= '(' . $critere . ')';
        }

        $path .= '{' . $liste_attributs . '}}';
        return redirect('graphql?query=' . urlencode($path));
    }

    public static function getTotalMontantPourTaxe($taxe, $proprietaireId)
    {
        // return DB::table('appartements')
        // ->where('proprietaire_id', $proprietaireId)
        // ->where($taxe, 1)
        // ->sum('montantloyer');

        return Appartement::where('proprietaire_id', $proprietaireId)
            ->where(strtolower($taxe), 1)
            ->sum('montantloyer');
    }

    public static function getValeurTaxe($taxe){
        // return DB::table('taxes')
        // ->where('designation', $taxe)
        // ->value('valeur');

        return Taxe::where('designation', strtoupper($taxe))
            ->value('valeur');
    }

    public static function isUnique(array $columnNme, $value, $id = null, $model=null, $columnIdName = null)
    {
        $exist = true;
        if ($id != null) {
            if ($columnIdName != null) {
                $query = app($model)->where($columnIdName, '!=', $id);
            } else {
                $query = app($model)->where('id', '!=', $id);
            }
        } else {
            $query = app($model);
        }
        for ($i = 0; $i < count($columnNme); $i++) {
            $query = $query->where($columnNme[$i], $value[$i]);
        }
        return $query->first() == null;
    }

    public static function formatdate($for_edit = false, $getSeparator = false)
    {
        if (!$getSeparator)
            return $for_edit ? "d/m/Y H:i:s" : "Y-m-d H:i:s";
        else
            return "/";
    }




    public static function dateEnFrancais($date)
    {
        $date_at = $date;
        $date_at = $date_at;
        $date_at = date_create($date_at);
        return date_format($date_at, "d-m-Y");
    }


    public static function getOneItemWithGraphQl($queryName, $id_critere, $justone = true, $filter = '')
    {
        self::setParametersExecution();
        $guzzleClient = new Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false,),));

        $critere = (is_numeric($id_critere)) ? "id:{$id_critere}" : $id_critere;

        $critere .= $filter;

        $queryAttr = Outil::$queries[$queryName];

        $url = self::getAPI() . "graphql?query={{$queryName}({$critere}){{$queryAttr}}}";
        // dd($url);

        $response = $guzzleClient->request('GET', $url, self::$guzzleOptions);

        $data = json_decode($response->getBody(), true);


        return ($justone) ? count($data['data'][$queryName]) > 0 ? $data['data'][$queryName][0] : null : $data;
    }


    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    public static function getFraisAvisEcheance($id,$total){
        $frais = Fraisupplementaire::where('avisecheance_id',$id)->get();
        $fraistotal = 0;
        if(isset($frais) && count($frais) > 0){
            foreach ($frais as $key=>$fr)
            {
                $fraistotal +=$fr->frais;
            }
        }
        if(isset($fraistotal) && $fraistotal > 0){
            $total +=$fraistotal;
        }
        return $total;
    }



    public static function getAllItemsWithGraphQl($queryName, $filter = null, $attribus = null)
    {

        self::setParametersExecution();

        $critere = isset($filter) ? '(' . $filter . ')' : "";
        // dd($url);
        //  dd(app_path()) ;
        try {
            $guzzleClient = new Client(['verify' => false]);
            // dd($guzzleClient) ;
            $queryAttr = isset($attribus) ? $attribus : Outil::$queries[$queryName];
            //dd($queryAttr);
            if ($queryName == "contrats2") {
                $queryName = "contrats";
            }
            $queries = $queryName . $critere;
            //dd($queries);
            $apppath = app_path() . "/cacert.pem";
            $url = self::getAPI() . "graphql?query={{$queries}{{$queryAttr}}}";
            //dd($url);
            $response = $guzzleClient->request('GET', $url);
            //dd  ($response);
            $data = json_decode($response->getBody(), true);
        //  dd($data);
        } catch (RequestException $re) {
            dd($re);
        }
      // dd($response);

        //dd($data);

        return $data['data'][$queryName];
    }


    public static function getAllItemsWithModel(string $model, $filters = null, $attribus = null)
    {
        self::setParametersExecution();
        $classe = ucfirst(strtolower($model));
        $obj = "App" . "\\" . $classe;
        $query = app($obj)::query();

        if (isset($filters)) {

            $filters = substr($filters, 1);
            $tab = explode(",", $filters);
            $newTabFilters = [];

            foreach ($tab as $key) {

                $cley = explode(":", $key);
                $newTabFilters[$cley[0]] = $cley[1];
            }

            foreach ($newTabFilters as $filtre => $value) {

                $value = str_replace('"', '', $value);
                $query = $query->where($filtre, $value);
            }
        }
        $data = $query->get();
        return $data;
    }


    public static function getAllItemsWithEtat(string $model, $filters = null, $attribus = null)
    {


        $query = app(Contrat::class)::query()
            ->join("paiementloyers", "contrats.id", "=", "paiementloyers.contrat_id")
            ->join("appartements", "contrats.appartement_id", "=", "appartements.id")
            ->select("contrats.*", "paiementloyers.periode", "paiementloyers.montantfacture", "appartements.immeuble_id", "appartements.proprietaire_id");

        if (isset($filters)) {

            $filters = substr($filters, 1);
            $tab = explode(",", $filters);
            $newTabFilters = [];

            foreach ($tab as $key) {

                $cley = explode(":", $key);
                $value = str_replace('"', '', $cley[1]);

                $newTabFilters[$cley[0]] = $value;
            }

            if (isset($newTabFilters["locataire_id"])) {
                $query = $query->where("contrats.locataire_id", $newTabFilters["locataire_id"]);
            }
            if (isset($newTabFilters["immeuble_id"])) {
                $query = $query->where("appartements.immeuble_id", $newTabFilters["immeuble_id"]);
            }
            if (isset($newTabFilters["periode"])) {
                $year = date("Y");
                $periode = $newTabFilters["periode"] . " " . $year;
                $query = $query->where("paiementloyers.periode", $periode);
            }
        }
        $data = $query->get();
        return $data;
    }



    public static function getOneItemWithFilterGraphQl($queryName, $filter, array $listeattributs_filter = null)
    {
        $guzzleClient = new Client();

        $critere = !empty($filter) ? '(' . $filter . ')' : "";
        if ($queryName == "pdffactures") {
            $queryName = "factures";
            $queryAttr = "id,code,date_facture,date_facture_fr,etat_livraison,remise,prix_ttc,total_net,id_devise,libelle_devise,taux_devise,signe_devise,numfac,date_depart,date_arrivee,observations,id_fournisseur,fournisseur{id,code,nom_fournisseur,civilite,adresse,pays,telephone,email,devise{id,libelle,taux_echange,signe}},user{id,name,image},detailFactures{id,quantite_colis,quantite_unitaire,colisage,etat_livraison,present_dans_reception,remise,prix_achat_ttc,prix_achat_hors_ttc,prix_achat_off,id_facture,id_det_comm,detComm{id,id_commande,id_produit,quantite_colis,quantite_unitaire,colisage,prix_achat_hors_ttc,prix_achat_off,remise,produit{id,code,code_current_fournisseur,etat,designation_fr,designation_en,uniteMesure{id,libelle},image,codeFournisseurs{id,code,id_produit,id_fournisseur,fournisseur{id,code,nom_fournisseur}}}},detailReceptions{id,quantite_colis,quantite_unitaire,est_ok,prix_revient,prix_revient_off,observations,id_reception,id_detail_facture}},created_at,created_at_fr,updated_at,updated_at_fr";
        } else if ($queryName == "pdfcommandes") {
            // $queryName = "commandes";
            // $queryAttr = "id,code,date_commande,date_commande_fr,remise,prix_ttc,total_net,total,totaloff,qte_en_cmde,nbre_prod,etat_livraison,id_devise,libelle_devise,taux_devise,signe_devise,numproforma,id_fournisseur,id_dp,etat,fournisseur{id,nom_fournisseur,civilite,adresse,pays,telephone,email,devise{id,libelle,taux_echange,signe}},user{id,name,image},detailCommandes{id,quantite_colis,quantite_unitaire,qte_restante,colisage,etat_livraison,present_dans_facture,remise,prix_achat_ttc,prix_achat_hors_ttc,prix_achat_off,id_commande,id_produit,produit{id,code,code_current_fournisseur,designation_fr,designation_en,uniteMesure{id,libelle},image,codeFournisseurs{id,code,id_produit,id_fournisseur,fournisseur{id,nom_fournisseur}}},detailFactures{id,id_facture,id_det_comm,quantite_colis,quantite_unitaire,colisage,remise,prix_achat_ttc,prix_achat_hors_ttc,prix_achat_off,detailReceptions{id,quantite_colis,quantite_unitaire,est_ok,observations,id_reception,id_detail_facture}}},detailVersements{id_commande,id_versement,montant,versement{id,id_user,date_versement,montant,created_at,created_at_fr,updated_at,updated_at_fr,user{id,name,email}}},created_at,created_at_fr,updated_at,updated_at_fr";
        } else if ($queryName == "pdfdemandeprix") {
            $queryName = "demandeprix";
            $queryAttr = "id,code,date,id_devise,libelle_devise,taux_devise,signe_devise,id_fournisseur,etat,fournisseur{id,code,nom_fournisseur,civilite,adresse,pays,telephone,email,devise{id,libelle,taux_echange,signe}},user{id,name,image},detailDemandePrix{id,quantite_colis,quantite_unitaire,colisage,id_dp,id_produit,produit{id,code,code_current_fournisseur,etat,designation_fr,designation_en,image,prix_achat,prix_achat_off,uniteMesure{id,libelle},codeFournisseurs{id,code,id_produit,id_fournisseur,fournisseur{id,code,nom_fournisseur}}}},created_at,created_at_fr,updated_at,updated_at_fr";
        } else if ($queryName == "pdfinventaires") {
            $queryName = "inventaires";
            $queryAttr = 'id,date_inventaire,date_inventaire_fr,code,motif,id_depot,depot{id,code,libelle}, motif, date_regulation,id_user,detail_inventaires{id,quantite_colis_stock,quantite_unitaire_stock,quantite_colis_inv,quantite_unitaire_inv,produit{designation_fr,code,uniteMesure{id,libelle}}}';
        } else {
            $queryAttr = Outil::$queries[$queryName];
        }


        $add_text_filter = "";
        if (isset($listeattributs_filter)) {
            foreach ($listeattributs_filter as $key => $one) {

                $queryAttr = str_replace($one . ",", "", $queryAttr); // Si le paramètre existe, on le remplace dans la chaine de caractère

                $getAttr = $one;
                $reste = "";
                if (strpos($one, "{") !== false) {
                    $getAttr = substr($one, 0, strpos($one, "{"));
                    $reste = substr($one, strpos($one, "{"));
                }

                $add_text_filter .= (($key === 0) ? ',' : '') . $getAttr . $critere . $reste . (count($listeattributs_filter) - $key > 1 ? ',' : '');
            }
        }


        $url = self::getAPI() . "graphql?query={{$queryName}{$critere}{{$queryAttr}{$add_text_filter}}}";
        //dd($url);

        $response = $guzzleClient->request('GET', $url, self::$guzzleOptions);

        $data = json_decode($response->getBody(), true);



        return $data['data'][$queryName];
    }
    // getPdf : permet de générer un pdf à partir d'une requête graphql
    public static function getPdf($queryName, $id_critere, $justone = true, $addToData = null)
    {
        $data = Outil::getOneItemWithGraphQl($queryName, $id_critere, $justone);

        $data['addToData'] = $addToData;

        $pdf = PDF::loadView("pdfs.{$queryName}", $data);
        return $pdf->setPaper('a4', 'orientation')->stream();
    }

    public static function createValidation($object, $rules, $messages)
    {
        // Create validation using Validation facade and pass in the Inputs, the rules andd the error messages
        $validator = Validator::make($object, $rules, $messages);

        $obj = null;

        // If Validation fails 8
        if ($validator->fails()) {

            // Get all validations errors
            $errors = $validator->errors();

            // Create array and cast it to object
            $obj = (object)array('data' => null, 'errors' => $errors);
        }

        return $obj;
    }

    public static function updateValidation($object, $rules, $messages, $model, $unique = [])
    {

        // Add id validation rule to the general rules
        $rules['id'] = 'required|exists:' . $model;

        if (count($unique) != 0) {
            foreach ($unique as $x) {
                $rules[$x] = $rules[$x] . ',' . $object['id'];
            }
        }

        // Add id validation message to the general rules
        $messages['id.required'] = 'L\'id de ' . $model . ' est requis';
        $messages['id.exists'] = 'Cet id n\'existe pas';

        $validator = Validator::make($object, $rules, $messages);

        $obj = null;

        // If Validation fails 8
        if ($validator->fails()) {

            // Get all validations errors
            $errors = $validator->errors();

            // Create array and cast it to object
            $obj = (object)array('data' => null, 'errors' => $errors);
        }

        return $obj;
    }


    //Vérifier présence objet dans un array (Demande Prix)
    public static function verifierPresence($array, $unElement)
    {
        $retour = false;
        foreach ($array as $key => $value) {
            if (isset($value["id"])) {
                if ($value['id'] == $unElement) {
                    $retour = true;
                    return true;
                }
            }
        }
        return $retour;
    }

    //Pour 2 colonnes
    public static function verifierPresenceDeuxColonnes($array, $unElement, $autreElement)
    {
        $retour = false;
        foreach ($array as $key => $value) {
            if (isset($value["id"]) && isset($value["idDepot"])) {
                if ($value['id'] == $unElement && $value["idDepot"] == $autreElement) {
                    $retour = true;
                    return true;
                }
            }
        }
        return $retour;
    }


    public static function getWithSousFamille($id_sous_famille, $column)
    {
        $id_sous_famille = !empty(trim($id_sous_famille)) ? $id_sous_famille : null;
        return (isset($id_sous_famille) ? "AND " . $column . " in (select id from produits where id_sous_famille IN ($id_sous_famille) OR id_sous_famille in (select id from familles where parent_id IN ($id_sous_famille) ))" : "");
    }

    public static function getPrixDeVenteProduitEntite($id_produit, $entite)
    {
        return null;
    }


    public static function numberToLetter($nbr)
    {
        $numberToWords = new NumberToWords();

        $numberTransformer = $numberToWords->getNumberTransformer('fr');

        return $numberTransformer->toWords($nbr);

        // return "text";
    }

    public static function getCount($array, $ct, $max)
    {

        $modulo = count($array) % $ct;
        $quotient = floor(count($array) / $ct);

        $count = 0;

        if ($modulo == 0 && $quotient == 1) {
            $count = $max;
        } else if ($modulo == 0 && $quotient > 1) {
            $count = $max - (($ct + $quotient) * 10);
        } else if ($modulo > 0 && $quotient > 1) {
            $count = $max - (($ct + $modulo) * 10);
        } else {
            $count = $max;
        }

        return $count;
    }



    // Voire si une table est reservee ou pas
    public static function ifReservationTable($item)
    {
    }

    // initialisation des parameters init
    public static function setParametersExecution()
    {
        ini_set('max_execution_time', '600');
        ini_set('max_input_time', -1);
        ini_set('pcre.backtrack_limit', 50000000000);
        ini_set('memory_limit', -1);
        set_time_limit(600);
    }


    public static function in_outProdution($item = null)
    {
        $in_outs      =  Entresortiestock::query();
        if (isset($item)) {
            $in_outs   = $in_outs->where('assemblage_id', $item->id)->get();
        }
    }


    public static function getDateRapelByDate($date, $frequence)
    {
        $date_rappel  = '';
        if (isset($date)) {

            if ($frequence == 'Par jour') {
                $date_rappel   = date('Y-m-d', strtotime($date . ' + 1 days'));
            }

            if ($frequence == 'Par semaine') {
                $date_rappel   = date('Y-m-d', strtotime($date . ' + 1 weeks'));
            }

            if ($frequence == 'Par mois') {
                $date_rappel   = date('Y-m-d', strtotime($date . ' + 1 months'));
            }

            if ($frequence == 'Par année') {
                $date_rappel   = date('Y-m-d', strtotime($date . ' + 1 years'));
            }

            if ($frequence == '15 jours') {
                $date_rappel   = date('Y-m-d', strtotime($date . ' + 15 days'));
            }

            if ($frequence == '2 mois') {
                $date_rappel   = date('Y-m-d', strtotime($date . ' + 2 months'));
            }

            if ($frequence == '3 mois') {
                $date_rappel   = date('Y-m-d', strtotime($date . ' + 3 months'));
            }

            if ($frequence == '6 mois') {
                $date_rappel   = date('Y-m-d', strtotime($date . ' + 6 months'));
            }
        }
        return $date_rappel;
    }









    //Donne date par rapport à l'ajout de nombre de jours
    public static function donneDateParRapportNombreJour($madate, $nbre)
    {
        $retour = date("Y-m-d", strtotime(date($madate) . " +$nbre days"));
        return $retour;
    }

    //Nomnre de jours entre 2 dates
    public static function nombreJoursEntreDeuxDates($date_debut, $date_fin)
    {
        $date_debut = strtotime($date_debut);
        $date_fin = strtotime($date_fin);
        $retour = round(($date_fin - $date_debut) / 60 / 60 / 24, 0);
        return $retour;
    }

    public static function donneElementsPreselectionAleatoire($argumentsArray, $query)
    {
        $cpt = 0; //Nombre de parcours au grand total
        $offset = -1;
        $somme = 0;
        $montant_preselection_aleatoire = $argumentsArray['montant_preselection_aleatoire'];
        $date_fin = $argumentsArray['date_end'];
        $idsArray = array();
        while ($somme <= $montant_preselection_aleatoire && $offset <= 30)  //Limite à 30 parcours de commande pour un jour
        {
            $date_debut = $argumentsArray['date_start'];
            $offset++;
            $cpt++;
            while ($somme <= $montant_preselection_aleatoire && $date_debut <= $date_fin) {
                $cpt++;
                $date_debut = Outil::donneDateParRapportNombreJour($date_debut, 1);
                $date_debut_filtre = $date_debut . ' 00:00:00';
                $date_fin_filtre = $date_debut . ' 23:59:59';

                $item = new Commande();
                $item = $item->whereBetween('date', [$date_debut_filtre, $date_fin_filtre]);
                $item = $item->whereNotNull('montant_total_commande');
                $item = $item->whereNotIn('id', DetailFacture::whereNotNull('commande_id')->get(['commande_id']));
                $item = $item->orderBy('date', 'asc');
                $item = $item->offset($offset)->first();
                if (isset($item)) {
                    $sommeTmp = $somme + $item->montant_total_commande;
                    if ($sommeTmp > $montant_preselection_aleatoire) {
                        break;
                    } else {
                        $somme = $sommeTmp;
                        array_push($idsArray, $item->id);
                    }
                }
            }
        }
        //dd($somme);
        //dd($cpt);

        $query = $query->whereIn('id', $idsArray);
        return $query;
    }
    public static function regulePaiementCommande()
    {

        $commandes = Commande::query();
        if (isset($commandes)) {
            foreach ($commandes as $key => $commande) {
                if (isset($commande)) {
                    $query = DB::table('paiements')
                        ->join('commandes', 'commandes.id', '=', 'paiements.commande_id')
                        ->selectRaw('sum(paiements.montant) as montant_total_paye')
                        ->where('commandes.id', $commande->id);

                    if (isset($query->first()->montant_total_paye)) {
                        $commande->restant_payer      = $commande->montant_total_commande - $query->first()->montant_total_paye;
                        $commande->montant_total_paye = $query->first()->montant_total_paye;
                        $commande->save();
                    }
                }
            }
        }
    }


    public static function getMointantTraiteur($item)
    {
    }

    public static function enregistrerBci($myItem, $myArray, $traiteur)
    {
        $pdf = null;
        if (isset($myArray)) {
            $item = $myItem;
            $item->save();



            if ($item->code == null || $item->code == '') {
                self::getCode($item);
            }

            $allbciproduits = Bciproduit::where('bci_id', $item->id)->get();
            $details = $myArray;
            if ($allbciproduits) {
                self::Checkdetail($allbciproduits, $details, Bciproduit::class, 'produit_id');
            }

            if (isset($details)) {
                $montant = 0;
                foreach ($details as $key => $value) {

                    if (empty($value['produit_compose_id'])) {
                        $errors = "Veuillez choisir un produit à la ligne " . ($key + 1);
                    } else {
                        $ligneproduit = Bciproduit::where('bci_id', $item->id)->where('produit_id', $value['produit_compose_id'])->first();

                        if ($ligneproduit && !isset($request->id)) {
                            //$errors = "le produit à la ligne " . ($key + 1) . " existe déja";
                        } else {
                            $produit = Produit::find($value['produit_compose_id']);
                            if (!isset($produit)) {
                                $errors = "le produit à la ligne " . ($key + 1) . " n'existe plus";
                            }
                        }
                    }

                    if (!isset($value['quantite']) || !is_numeric($value['quantite'])) {
                        $errors = "Veuillez definir la quantite à la ligne" . ($key + 1);
                    }

                    if (!isset($errors)) {
                        if (!isset($ligneproduit)) {
                            $ligneproduit = new Bciproduit();
                            $ligneproduit->bci_id = $item->id;
                        }

                        if (!isset($errors)) {
                            if ($value['quantite'] > 0) {

                                $ligneproduit->produit_id = $value['produit_compose_id'];
                                $ligneproduit->quantite = $value['quantite'];
                                $ligneproduit->save();

                                $montant += ((int)$value['quantite'] * $value["pa"]);
                            } else if (isset($ligneproduit)) {
                                $ligneproduit->delete();
                            }
                        }
                    }
                }
                //   dd($errors);
                $data = array('item' => ["code" => $item->code, "montant" => $montant], 'details' => $details, 'traiteur' => $traiteur);

                //                $pdf = \PDF::loadView('pdfs.bci', $data);

                $pdf = \App::make('dompdf.wrapper');
                $pdf->getDomPDF()->set_option("enable_php", true);
                $pdf->loadView('pdfs.bci', $data);
            }
        }
        return $pdf;
    }

    public static function enregistrerPaimentsDejaFaitsSurFacture($myItem, $myArray)
    {
        if (!empty($myArray)) {
            //Au cas ou c'est un element on redéfinit le tableau
            if (empty($myArray[0])) {
                if (isset($myArray)) {
                    //Cas une seule ligne qui ne passait pas
                    $arrayTmp = array();
                    array_push($arrayTmp, $myArray);
                    $myArray = $arrayTmp;
                }
            }
            foreach ($myArray as $value) {
                $itemId = $value["id"];
                $itemGeneralId = $myItem->id;
                $filtres = "commande_id:" . $itemId;
                $paiements = Outil::getOneItemWithFilterGraphQl("paiements", $filtres);
                if (!empty($paiements)) {
                    foreach ($paiements as $value2) {
                        $itemArrayPaiement = array("mode_paiement_id" => $value2["mode_paiement_id"], "montant" => $value2["montant"], "facture_id" => $itemGeneralId, "compta" => $myItem->compta);
                        $retourSavePaiement = Outil::enregistrerPaiement('facture', $itemArrayPaiement);
                    }
                }
            }
        }
    }

    public static function enregistrerFamilleLiaisonProduit($familleliaisonproduits, $item)
    {
        /* if (isset($allfamilleliaisonproduits)) {
            Outil::Checkdetail($allfamilleliaisonproduits, $familleliaisonproduits, FamilleLiaisonProduit::class, ['famille_id', 'pour_menu']);
        }*/
        $errors = null;
        if (isset($familleliaisonproduits)) {
            foreach ($familleliaisonproduits as $key => $value) {
                $pour_menu = (isset($value['pour_menu']) && $value['pour_menu'] == true) ? true : false;
                if (empty($value['famille_id'])) {
                    $errors = "Veuillez choisir une famille à la ligne " . ($key + 1);
                } else {
                    if (is_numeric($value['famille_id']) == true) {
                        $value['famille_id'] = (int)$value['famille_id'];
                    } else {
                        $designation = $value['famille_id'];
                        $famille = Famille::whereRaw('TRIM(lower(designation)) = TRIM(lower(?))', ["$designation"])->first();
                        if (isset($famille->id)) {
                            $value['famille_id'] = $famille->id;
                            $lignefamilleliaison = FamilleLiaisonProduit::where('produit_id', $item->id)
                                ->where('famille_id', $value['famille_id'])
                                ->where('pour_menu', $pour_menu)->first();
                        } else {
                            $errors = "Veuillez choisir une famille à la ligne " . ($key + 1);
                        }
                    }
                }
                if (!isset($value['quantite']) || !is_numeric($value['quantite'])) {
                    $errors = "Veuillez définir la quantité à la ligne" . ($key + 1) . '==>' . $value['quantite'];
                }
                // dd($errors);

                if (!isset($errors)) {
                    if (!isset($lignefamilleliaison)) {
                        $lignefamilleliaison = new FamilleLiaisonProduit();
                    }

                    if ($value['quantite'] > 0) {
                        $lignefamilleliaison->famille_id = $value['famille_id'];
                        $lignefamilleliaison->produit_id = $item->id;
                        $lignefamilleliaison->quantite = intval($value['quantite']);
                        $lignefamilleliaison->pour_menu = $pour_menu;
                        $lignefamilleliaison->save();
                    } else {
                        $errors = 'Le montant doit être positif';
                    }
                }
            }
        }
    }
    public static function enregistrerPaProduit($prixachats, $item)
    {
        $errors = null;
        /*if (isset($allprixachats)) {
            Outil::Checkdetail($allprixachats, $prixachats, FournisseurProduit::class, 'fournisseur_id');
        }*/
        if (isset($prixachats)) {
            //   dd($prixachats);
            foreach ($prixachats as $key => $value) {
                $prix = $value['montant_achat'];
                if (empty($value['fournisseur_id'])) {
                    $errors = "Veuillez choisir un fournisseur à la ligne " . ($key + 1);
                } else {
                    if (is_numeric($value['fournisseur_id']) == true) {
                        $value['fournisseur_id'] = (int)$value['fournisseur_id'];
                    } else {
                        $designation = $value['fournisseur_id'];
                        $fournisseur = Fournisseur::whereRaw('TRIM(lower(designation)) = TRIM(lower(?))', ["$designation"])->first();
                        if (isset($fournisseur->id)) {
                            $value['fournisseur_id'] = $fournisseur->id;
                            $ligneprixachat = FournisseurProduit::where('produit_id', $item->id)->where('fournisseur_id', $value['fournisseur_id'])->first();
                        } else {
                            $errors = "Veuillez choisir un fournisseur à la ligne " . ($key + 1);
                        }
                    }
                    if ($ligneprixachat && !isset($request->id)) {
                        $errors = "Un prix pour le fournisseur à la ligne " . ($key + 1) . " est déjà défini";
                    }
                }
                if (!isset($prix) || !is_numeric($prix)) {
                    $errors = "Veuillez définir le prix d'achat à la ligne " . ($key + 1);
                }


                if (!isset($errors)) {
                    if (!isset($ligneprixachat)) {
                        $ligneprixachat = new FournisseurProduit();
                        $ligneprixachat->produit_id = $item->id;
                        $ligneprixachat->fournisseur_id = $value['fournisseur_id'];
                    }

                    if ($prix > 0) {
                        $ligneprixachat->montant_achat = $prix;
                        $ligneprixachat->save();
                    } else if (isset($ligneprixachat)) {
                        $ligneprixachat->delete();
                    }
                }
            }
        }
        return $errors;
    }
    public static function enregistrerPrixventeProduit($prixventes, $item)
    {
        /*if (isset($allprixventes)) {
            Outil::Checkdetail($allprixventes, $prixventes, Prixdevente::class, 'type_prix_de_vente_id');
        }*/
        $errors = null;
        if (isset($prixventes)) {

            foreach ($prixventes as $key => $value) {
                //  dd($value['type_prix_de_vente_id']);
                if (empty($value['type_prix_de_vente_id'])) {
                    $errors = "Veuillez choisir un le type de prix de vente à la ligne " . ($key + 1);
                } else {

                    if (is_numeric($value['type_prix_de_vente_id']) == true) {
                        $value['type_prix_de_vente_id'] = (int)$value['type_prix_de_vente_id'];
                    } else {
                        $designation = $value['type_prix_de_vente_id'];
                        $type_prix_vente = TypePrixDeVente::whereRaw('TRIM(lower(designation)) = TRIM(lower(?))', ["$designation"])->first();
                        //  dd($type_prix_vente->designation);
                        if (isset($type_prix_vente->id)) {
                            $value['type_prix_de_vente_id'] = $type_prix_vente->id;
                            $ligneprixvente = Prixdevente::where('produit_id', $item->id)->where('type_prix_de_vente_id', $value['type_prix_de_vente_id'])->first();
                        } else {
                            $errors = "Veuillez choisir un le type de prix de vente à la ligne " . ($key + 1);
                        }
                    }
                }
                if (!isset($value['montant']) || !is_numeric($value['montant'])) {
                    $errors = "Veuillez definir le prix de vente à la ligne " . ($key + 1);
                }

                if (!isset($errors)) {
                    if (!isset($ligneprixvente)) {
                        $ligneprixvente = new Prixdevente();
                    }
                    $ligneprixvente->produit_id = $item->id;
                    if (is_numeric($value['montant'])) {
                        if ((int)$value['montant'] > 0) {
                            $ligneprixvente->type_prix_de_vente_id = $value['type_prix_de_vente_id'];
                            $ligneprixvente->montant = (int)$value['montant'];
                            $ligneprixvente->save();
                        } else {
                            $errors = 'Le montant doit etre positif';
                        }
                    } else {
                        $errors = 'Le montant doit etre positif';
                    }
                } else {
                    // dd($errors);
                }
            }
        } else {
            //  dd('error');
        }
        return $errors;
    }

    public static function mettreEnComptaElements($compta, $myArray)
    {
        if (!empty($myArray)) {
            foreach ($myArray as $value) {
                $itemId = $value["id"];
                $item = Commande::find($itemId);
                if (isset($item)) {
                    $item->compta = $compta;
                    $item->save();
                }
            }
        }
    }

    public static function enregistrerDispatchingParEntite($item, $items)
    {
        $tableName = strtolower($item->getTable());

        $id = $item->id;

        $detailsToDelete = new EntiteTransactionCaisse();
        if ($tableName == "sortie_cashs") {
            $detailsToDelete = $detailsToDelete->where("sortie_cash_id", $id);
        } else if ($tableName == "versements") {
            $detailsToDelete = $detailsToDelete->where("versement_id", $id);
        } else if ($tableName == "depenses") {
            $detailsToDelete = $detailsToDelete->where("depense_id", $id);
        }
        $detailsToDelete->delete();
        $detailsToDelete->forceDelete();
        foreach ($items as $key => $value) {
            $itemDetail = new EntiteTransactionCaisse();
            if (isset($value["id"])) {
                $itemDetailId = $value["id"];
                $itemDetail = EntiteTransactionCaisse::find($itemDetailId);
            }

            if (empty($itemDetail->id)) {
                $itemDetail = new EntiteTransactionCaisse();
            }

            $itemDetail->entite_id = $value["entite_id"];
            $itemDetail->montant = $value["montant"];
            if ($tableName == "sortie_cashs") {
                $itemDetail->sortie_cash_id = $id;
            } else if ($tableName == "versements") {
                $itemDetail->versement_id = $id;
            } else if ($tableName == "depenses") {
                $itemDetail->depense_id = $id;
            }
            $itemDetail->save();
        }
    }

    //Calcul du prix de revient des produits de sortie production
    public static function calculPrixRevientApresProduction($item)
    {
        $retour  =  null;
        if ($item->entre_sortie !== 1) {
            $produit_production   = DetailAssemblage::query()->where('assemblage_id', $item->id)->first();

            if (isset($produit_production)) {
                $produit                            = Produit::find($produit_production->produit_id);
                $produits_sortie                    = DetailDetailAssemblage::query()->where('detail_assemblage_id', $produit_production->id)->where('perte', '!=', 1)->get();
                $quntite_total_produits_sortie      = DB::table('detail_detail_assemblages')
                    ->join('produits', 'produits.id', 'detail_detail_assemblages.produit_id')
                    ->join('detail_assemblages', 'detail_assemblages.id', 'detail_detail_assemblages.detail_assemblage_id')
                    ->join('assemblages', 'assemblages.id', 'detail_assemblages.assemblage_id')
                    ->where('assemblages.id', $item->id)
                    ->where('detail_detail_assemblages.perte', '!=', 1)
                    ->selectRaw("SUM(detail_detail_assemblages.poids) as quantite_total_sortie")
                    ->first();

                $prix_achat_unitaire_production   = $produit->prix_achat_unitaire;
                $quantite_production              = $produit_production->qte_unitaire;
                if (isset($quntite_total_produits_sortie) && $quntite_total_produits_sortie->quantite_total_sortie) {
                    $quntite_total_produits_sortie    =  $quntite_total_produits_sortie->quantite_total_sortie;

                    //Calcul du nouveau prix d'achat après production
                    $nouveaux_prix_achat_production   = ($quantite_production / $quntite_total_produits_sortie)  * $prix_achat_unitaire_production;

                    //Calcul de prix de revient de chaque produit de sortie
                    if (isset($produits_sortie) && count($produits_sortie) > 0) {
                        foreach ($produits_sortie as $key => $value) {
                            $produit_sortie              = Produit::find($value->produit_id);
                            if (isset($produit_sortie) && isset($produit_sortie->unite_de_mesure)) {
                                //On reajuste le poids net du produit en sortie
                                if ($produit_sortie->unite_de_mesure->designation  == 'KG') {
                                    $poids_net_production   =  $value->qte_unitaire  = $value->poids;
                                } else if ($produit_sortie->unite_de_mesure->designation  == 'U') {
                                    $poids_net_production  = $value->poids / $value->qte_unitaire;
                                }

                                //Apres on applique la formule pour calcul son prix de revient unitaire
                                if (isset($poids_net_production)) {
                                    $nouveaux_prix_revient_unitaire_sortie       =  $nouveaux_prix_achat_production   *  $poids_net_production;

                                    $produit_sortie->prix_de_revient_unitaire    = $nouveaux_prix_revient_unitaire_sortie;
                                    $produit_sortie->save();
                                }
                            }
                        }
                    } else {
                        $retour   =  'Cette production n\'a pas de produit en sortie.';
                    }
                }
            } else {
                $retour   =  'Cette production n\'a pas de produit en entrée.';
            }
        }

        return $retour;
    }


    //Donne l'id de l'utilisateur actuellement connecté
    public static function donneUserId()
    {
        $user = Auth::user();
        $retour = isset($user) ? Auth::user()->id : null;
        return $retour;
    }

    //Donne le solde qui se basait sur un champs de la base de données
    public static function donneSolde($type = "caisse", $item_id=null, $typeDeCalcul = 0, $montant = 0)
    {
        /*
        - $typeDeCalcul == 0 //Pas de calcul juste le solde
        - $typeDeCalcul == 1 //Additionner avec le solde
        - $typeDeCalcul == 2 //Soustraire du solde
        */
        $retour = 0;
        if (isset($item_id)) {
            if ($type == "banque") {
                $item = Banque::find($item_id);
            } else {
                $item = Caisse::find($item_id);
            }
            if (isset($item)) {
                $retour = $item->solde;
            }

            if (isset($typeDeCalcul) && isset($montant)) {
                if ($typeDeCalcul == 1) {
                    $retour = $retour + $montant;
                } else if ($typeDeCalcul == 2) {
                    $retour = $retour - $montant;
                }

                if ($retour < 0) {
                    $retour = 0;
                }
            }
        }

        return $retour;
    }

    //Donne le solde calculé
    public static function donneSoldeCalculei($item_id, $soldeComptable = false, $from = "societefacturation", $date_debut = null, $date_fin = null)
    {
        //Solde  = appros[receveur] - (appros[emetteur] + sorties cash + versements banques)
        $retour = 0;



        if (isset($item_id)) {
            if ($soldeComptable == true) {
                if ($from == "societefacturation") {
                    $caissesToSearch = Caisse::whereNotNull('entite_id')->whereIn('entite_id', Entite::where('societe_facturation_id', $item_id)->get(['id']))->get(['id']);
                } else if ($from == "caisse") {
                    $caissesToSearch = Caisse::whereNotNull('entite_id')->where('entite_id', $item_id)->get(['id']);
                }
            }

            //****Total entrée****
            //Total appros receveur
            $queryApprosReceveurs = DB::table("approcashs")->select(DB::raw("COALESCE(SUM(montant),0) as total"));
            if (isset($date_debut) && isset($date_fin)) {
                $queryApprosReceveurs = $queryApprosReceveurs->whereBetween('date', [$date_debut, $date_fin]);
            }

            if ($soldeComptable == true) {
                $queryApprosReceveurs = $queryApprosReceveurs->whereIn('caisse_destinataire_id', $caissesToSearch);
                $queryApprosReceveurs = $queryApprosReceveurs->whereNull('caisse_source_id');
            } else {
                $queryApprosReceveurs = $queryApprosReceveurs->where('caisse_destinataire_id', $item_id);
            }
            $queryApprosReceveurs = $queryApprosReceveurs->first();

            //Total paiements commande
            $queryPaiementsComms = DB::table("paiements")->select(DB::raw("COALESCE(SUM(montant),0) as total"))->whereNotNull('commande_id');
            if (isset($date_debut) && isset($date_fin)) {
                $queryPaiementsComms = $queryPaiementsComms->whereBetween('date', [$date_debut, $date_fin]);
            }
            if ($soldeComptable == true) {
                $queryPaiementsComms = $queryPaiementsComms->whereIn('caisse_id', $caissesToSearch);
            } else {
                $queryPaiementsComms = $queryPaiementsComms->where('caisse_id', $item_id);
            }
            $queryPaiementsComms = $queryPaiementsComms->whereIn('mode_paiement_id', Modepaiement::where('est_cash', 1)->get(['id']));

            if ($soldeComptable == true) {
                $queryPaiementsComms = $queryPaiementsComms->whereIn('commande_id', Commande::where('compta', 0)->get(['id']));
            }
            $queryPaiementsComms     = $queryPaiementsComms->first();

            //Total paiements facture
            $queryPaiementsFacs = DB::table("paiements")->select(DB::raw("COALESCE(SUM(montant),0) as total"))->whereNotNull('facture_id');
            if (isset($date_debut) && isset($date_fin)) {
                $queryPaiementsFacs = $queryPaiementsFacs->whereBetween('created_at', [$date_debut, $date_fin]);
            }
            if ($soldeComptable == true) {
                $queryPaiementsFacs = $queryPaiementsFacs->whereIn('caisse_id', $caissesToSearch);
            } else {
                $queryPaiementsFacs = $queryPaiementsFacs->where('caisse_id', $item_id);
            }
            $queryPaiementsFacs = $queryPaiementsFacs->whereIn('mode_paiement_id', Modepaiement::where('est_cash', 1)->get(['id']));

            if ($soldeComptable == true) {
                $queryPaiementsFacs = $queryPaiementsFacs->whereIn('facture_id', Facture::where('compta', 0)->get(['id']));
            }
            $queryPaiementsFacs = $queryPaiementsFacs->first();


            //****Total sortie****
            //Total appros emetteur
            $queryApprosEmetteurs = DB::table("approcashs")->select(DB::raw("COALESCE(SUM(montant),0) as total"));
            if (isset($date_debut) && isset($date_fin)) {
                $queryApprosEmetteurs = $queryApprosEmetteurs->whereBetween('date', [$date_debut, $date_fin]);
            }
            if ($soldeComptable == true) {
                $queryApprosEmetteurs = $queryApprosEmetteurs->whereIn('caisse_source_id', $caissesToSearch);
                $queryApprosEmetteurs = $queryApprosEmetteurs->whereNull('caisse_source_id');
            } else {
                $queryApprosEmetteurs = $queryApprosEmetteurs->where('caisse_source_id', $item_id);
            }
            $queryApprosEmetteurs = $queryApprosEmetteurs->first();

            //Total sorties cash
            $querySortieCashs = DB::table("sortie_cashs")->select(DB::raw("COALESCE(SUM(montant),0) as total"));
            if (isset($date_debut) && isset($date_fin)) {
                $querySortieCashs = $querySortieCashs->whereBetween('date', [$date_debut, $date_fin]);
            }
            if ($soldeComptable == true) {
                $querySortieCashs = $querySortieCashs->whereIn('caisse_id', $caissesToSearch);
            } else {
                $querySortieCashs = $querySortieCashs->where('caisse_id', $item_id);
            }
            $querySortieCashs = $querySortieCashs->first();

            //Total versements
            $queryVersements = DB::table("versements")->select(DB::raw("COALESCE(SUM(montant),0) as total"));
            if (isset($date_debut) && isset($date_fin)) {
                $queryVersements = $queryVersements->whereBetween('date', [$date_debut, $date_fin]);
            }
            if ($soldeComptable == true) {
                $queryVersements = $queryVersements->whereIn('caisse_id', $caissesToSearch);
            } else {
                $queryVersements = $queryVersements->where('caisse_id', $item_id);
            }
            $queryVersements = $queryVersements->first();

            //Total dépenses
            $queryDepenses = DB::table("paiements")->select(DB::raw("COALESCE(SUM(montant),0) as total"))->whereNotNull('depense_id');
            if (isset($date_debut) && isset($date_fin)) {
                $queryDepenses = $queryDepenses->whereBetween('date', [$date_debut, $date_fin]);
            }
            if ($soldeComptable == true) {
                $entitesToSearch = Entite::where('societe_facturation_id', $item_id)->get(['id']);
                $depensesToSearch = Depense::whereIn('entite_id', $entitesToSearch)->get(['id']);
                $queryDepenses = $queryDepenses->whereIn('depense_id', $depensesToSearch);
            } else {
                $queryDepenses = $queryDepenses->where('caisse_id', $item_id);
            }
            $queryDepenses = $queryDepenses->whereIn('mode_paiement_id', Modepaiement::where('est_cash', 1)->get(['id']));
            //$queryDepenses = $queryDepenses->whereIn('depense_id', Depense::where('compta', 0)->get(['id']));
            $queryDepenses = $queryDepenses->first();


            $retour = ($queryApprosReceveurs->total + $queryPaiementsComms->total + $queryPaiementsFacs->total) - ($queryApprosEmetteurs->total + $querySortieCashs->total + $queryVersements->total + $queryDepenses->total);
            //$retour = $queryApprosReceveurs->total;
        }
        //dd($retour);

        return $retour;
    }

    //Donne total dépense
    public static function donneTotalDepense($params = null, $nonValides = false)
    {
        $date_start      = null;
        $date_end        = null;
        $date_end        = null;
        $collumn         = 'date';
        $entite_id       = null;
        $caisse_id       = null;
        $fournisseur_id  = null;
        $motif           = null;
        $poste_depense_id = null;

        $etat             = null;
        $compta           = null;
        $echu             = null;
        $payer            = null;

        // dd($params);
        if (isset($params)) {
            $date_start       = isset($params['date_debut']) ? $params["date_debut"] : null;
            $date_end         = isset($params['date_fin'])   ? $params["date_fin"] : null;
            $date_saisie      = isset($params['date_saisie']) ? $params["date_saisie"] : null;

            $entite_id        = isset($params['entite_id'])  ? $params["entite_id"] : null;
            $caisse_id        = isset($params['caisse_id'])  ? $params["caisse_id"] : null;
            $fournisseur_id   = isset($params['fournisseur_id'])  ? $params["fournisseur_id"] : null;
            $motif            = isset($params['motif'])  ? $params["motif"] : null;
            $poste_depense_id = isset($params['poste_depense_id'])  ? $params["poste_depense_id"] : null;

            $etat             = isset($params['etat'])      ? $params["etat"] : null;
            $compta           = isset($params['compta'])  ? $params["compta"] : null;
            $echu             = isset($params['echu'])  ? $params["echu"] : null;
            $payer            = isset($params['payer'])  ? $params["payer"] : null;

            if (isset($date_saisie)) {
                if ($date_saisie == 2) {
                    $collumn = 'date_piece';
                }
            }
        }
        $retour = 0;

        $queryTotal = DB::table("depenses")->select(DB::raw("COALESCE(SUM(montant),0) as total"));
        if ($nonValides == true) {
            $queryTotal = $queryTotal->where('etat', 0);
        }

        if (isset($date_start) && isset($date_end)) {
            $from = date($date_start . ' 00:00:00');
            $to = date($date_end . ' 23:59:59');
            $queryTotal = $queryTotal->whereBetween('depenses.' . $collumn, array($from, $to));
        }
        if (isset($caisse_id)) {
            $queryTotal = $queryTotal->where('depenses.caisse_id', $caisse_id);
        }
        if (isset($entite_id)) {
            $queryTotal = $queryTotal->where('depenses.entite_id', $entite_id);
        }
        if (isset($fournisseur_id)) {
            $queryTotal = $queryTotal->where('depenses.fournisseur_id', $fournisseur_id);
        }
        if (isset($motif)) {
            $queryTotal = $queryTotal->where('depenses.motif', Outil::getOperateurLikeDB(), '%' . $motif . '%');
        }
        if (isset($poste_depense_id)) {
            $queryTotal = $queryTotal->whereIn('depenses.id', DepensePosteDepense::where('poste_depense_id', $poste_depense_id)->get(['depense_id']));
        }
        if (isset($etat)) {
            $queryTotal = $queryTotal->where('depenses.etat', $etat);
        }
        //        if (isset($compta))
        //        {
        //            $queryTotal = $queryTotal->where('depenses.compta', $compta);
        //        }
        if (isset($echu)) {
            $date  = now();
            $date   = explode(' ', $date);

            if ($echu == 1) {
                $queryTotal = $queryTotal->where('depenses.date_echeance', '<', $date[0]);
            } else {
                $queryTotal = $queryTotal->where('depenses.date_echeance', '>=',  $date[0]);
            }
        }
        if (isset($payer)) {
            if ($payer == 0) {
                //Solde non
                $queryTotal = $queryTotal->whereRaw("((select ROUND(COALESCE(SUM(p.montant),0)) as total from paiements p,depenses d WHERE p.depense_id = depenses.id))=0");
            } else if ($payer == 1) {
                //Solde total
                $queryTotal = $queryTotal->whereRaw("(select ROUND(COALESCE(SUM(d.montant),0)) as total from depenses d WHERE d.id=depenses.id)<=(select ROUND(COALESCE(SUM(p.montant),0)) as total from paiements p WHERE p.depense_id = depenses.id)");
            } else if ($payer == 2) {
                //Solde partiel
                $queryTotal = $queryTotal->whereRaw("((select ROUND(COALESCE(SUM(p.montant),0)) as total from paiements p,depenses d WHERE p.depense_id = depenses.id))!=0");
                $queryTotal = $queryTotal->whereRaw("(select ROUND(COALESCE(SUM(d.montant),0)) as total from depenses d WHERE d.id=depenses.id)>(select ROUND(COALESCE(SUM(p.montant),0)) as total from paiements p WHERE p.depense_id = depenses.id)");
            }
        }

        $sesCaisses = Outil::donneAllCaissesUser();
        if (Outil::voirDepenseTrancheHoraireEnCours() == 1) {
            //Pour les caissiers en général ==> ne voir que les dépenses de leur tranche horaire et de leurs caisse affectées
            $trancheHoraireEnCours = Outil::donneTrancheHoraire();
            if (isset($trancheHoraireEnCours)) {
                $queryTotal = $queryTotal->whereIn('caisse_id', $sesCaisses);

                $dateToday = date('Y-m-d');
                $heureStart = substr($trancheHoraireEnCours->heure_debut, 11, 5);
                $heureEnd = substr($trancheHoraireEnCours->heure_fin, 11, 5);
                $dateStart = $dateToday . " " . $heureStart . ":00";
                $dateEnd = $dateToday . " " . $heureEnd . ":00";

                $queryTotal = $queryTotal->whereBetween('date', array($dateStart, $dateEnd));
            } else {
                //Ne voir aucune dépense
                $queryTotal = $queryTotal->id('id', 0);
            }
        } else {
            $queryTotal = $queryTotal->where(function ($queryTotal) use ($sesCaisses) {
                return $queryTotal->where('user_id', Outil::donneUserId())
                    ->orWhereIn('caisse_id', $sesCaisses);
            });
        }

        if (!self::isAuthorize()) {
            //***[Barrane = NO] ==> [compta = 0]***//
            $queryTotal = $queryTotal->where('depenses.compta', 0);
        } else if (Outil::isAuthorize() == 1 && !isset($compta)) {
            $queryTotal = $queryTotal->where('depenses.compta', '<=', 1);
        } else {
            //***[Barrane = YES] ==> Aurorisé à filtrer**//
            if (isset($compta)) {
                $queryTotal = $queryTotal->where('depenses.compta', $compta);
            }
        }

        $queryTotal = $queryTotal->first();
        $retour = $queryTotal->total;

        return $retour;
    }

    public static function compilePaiementFactureCommande()
    {
        $retour   = false;
        $factures = Facture::whereIn('id', Paiement::whereNotNull('facture_id')->get(['facture_id']))->get();
        //$factures = Facture::whereIn('id', Paiement::whereNotNull('facture_id')->get(['facture_id']))->get();

        if (isset($factures) && count($factures) > 0) {
            foreach ($factures as $key => $fact) {
                $paiement_factures  = Paiement::where('facture_id', $fact->id)->get();

                if (isset($paiement_factures) && count($paiement_factures) > 0) {
                    foreach ($paiement_factures as $key => $paim) {
                        Outil::ventilePaiementFactureCommande($fact->id, $paim->montant, $paim->caisse_id, $paim->mode_paiement_id, $paim->compta);
                        $retour   = true;
                    }
                }
            }
        }
        return $retour;
    }

    public static function compileMontantFactureCommande()
    {
        $retour               = false;
        $factures             = Facture::query()->where('type', 1)->get();

        if (isset($factures) && count($factures) > 0) {
            foreach ($factures as $key => $fact) {
                $commandes    = Commande::query()
                    ->join('detail_factures', 'detail_factures.commande_id', '=', 'commandes.id')
                    ->join('factures', 'factures.id', '=', 'detail_factures.facture_id')
                    ->where('factures.id', $fact->id);

                if (isset($commandes)) {
                    //Toutes les commandes de la facture
                    $commandes = $commandes->orderBy('commandes.date', 'ASC')->selectRaw('commandes.*')->get();

                    if (isset($commandes) && count($commandes) > 0) {
                        $montant_facture  = 0;
                        foreach ($commandes as $key => $cmd) {
                            $commande                          = self::regulePaiement($cmd->id);
                            $montant_facture                  += $commande->restant_payer  + $commande->montant_total_paye;
                            $detail_facture                    = DetailFacture::where('commande_id', $cmd->id)->first();
                            if (isset($detail_facture)) {
                                $detail_facture->montant_total = $commande->restant_payer  + $commande->montant_total_paye;
                                $detail_facture->save();
                            }
                        }

                        if ($fact->montant !== $montant_facture) {
                            $fact->montant               = $montant_facture;
                        }

                        $fact->save();
                        $retour = true;
                    }
                }
            }
        }
        return $retour;
    }

    public static function compilePlafondCompteCreditConsoClient()
    {
        $retour               = false;
        $comptes              = CompteCredit::where('etat', 1)->get();
        $conso_text           = Modepaiement::conso_interne()->designation;
        $credit_text          = Modepaiement::credit()->designation;

        $clients              = Client::whereIn('id', CompteCredit::where('etat', 1)->get(['client_id']))->get();

        if (isset($clients) && count($clients) > 0) {
            foreach ($clients as $key => $cli) {
                $cli->plafond_ci_autorise     = false;
                $cli->plafond_ci_value        = null;
                $cli->plafond_credit_autorise = false;
                $cli->plafond_value           = null;
                $cli->save();
            }
        }

        if (isset($comptes) && count($comptes) > 0) {
            foreach ($comptes as $key => $cpt) {
                if (isset($cpt->client_id)) {
                    $client         = Client::find($cpt->client_id);
                    $plafond_compte = $cpt->plafond;


                    if (isset($cpt->mode_paiement_id)) {
                        $type_compte  = Modepaiement::find($cpt->mode_paiement_id);
                        if (isset($type_compte)) {
                            if ($type_compte->designation == $conso_text) {
                                $client->plafond_ci_autorise     = true;
                                $client->plafond_ci_value        = $plafond_compte;
                            } else if ($type_compte->designation == $credit_text) {
                                $client->plafond_credit_autorise = true;
                                $client->plafond_value           = $plafond_compte;
                            }
                            $client->save();
                            $retour = true;
                        }
                    }
                }
            }
        }
        return $retour;
    }

    public static function compilePaiementConsoCommande()
    {
        $mode_paiement_id   = Modepaiement::conso_interne()->id;
        $retour = false;

        $query = DB::table('commandes')
            //->whereNotIn('commandes.c_interne', [1,2])
            ->whereIn('commandes.id', PaiementCredit::where('mode_paiement_id', $mode_paiement_id)
                ->get(['commande_id']));

        if (isset($query)) {
            $query = $query->groupBy('commandes.id')->get();

            foreach ($query as $key => $cmd) {
                $paiement            = 0;
                $conso_interne       = 1;
                $paiement        = self::donnePaiementCommandeConsoInternePartielClient(null, null, $cmd->id);
                if (!empty($paiement) && $paiement > 0) {
                    $conso_interne = 2;
                }

                $retour              = true;
                $commande            = Commande::find($cmd->id);

                $commande->c_interne = $conso_interne;
                $commande->save();
            }
        }
        return $retour;
    }

    public static function GetMontantenattente($avis_id, $totalFacture)
    {
        $montantenattente = 0;
        $montantRegler = 0;

        // on recupere les paiements liés à l'avis avec etat = null
        $paiementEcheances = Paiementecheance::where('avisecheance_id', $avis_id)
            ->whereNull('etat')
            ->orderBy('created_at', 'desc')
            ->get();

        if(count($paiementEcheances) > 0){
            $montantRegler = $paiementEcheances->sum('montant');
            if ($totalFacture > 0) {
                $montantenattente = $totalFacture - $montantRegler;
            }
        } else{
            $montantenattente = $totalFacture;
        }

        return $montantenattente;
    }

    public static function addCompteClient($montant, $locataire_id, $paiementecheance_id = null)
    {
        $compteclient = new Compteclient();
        $compteclient->montant = $montant;
        $compteclient->locataire_id = $locataire_id;
        $compteclient->paiementecheance_id = $paiementecheance_id;
        $compteclient->date = now();
        $compteclient->save();
    }

    public static function Soldeclient($locataire_id)
    {
        $solde = 0;
        if (isset($locataire_id)) {
            $montants = Compteclient::where('locataire_id', $locataire_id)
            ->whereNull('etat')
            ->orderBy('created_at', 'desc')
            ->pluck('montant');

            foreach ($montants as $montant) {
                $solde += is_numeric($montant) ? intval($montant) : 0;
            }
        }

        return $solde;

    }

    //Donne l'état et la couleur du badge au niveau de la liste
    public static function donneEtatGeneral($type, $itemArray = null)
    {
        $retour = null;

        if ($type == "demanderesiliation") {
            if (isset($itemArray)) {
                $etat = $itemArray["etat"];
                if ($etat == 0) {
                    $retour = array("texte" => "en cours", "badge" => "bg-info");
                } else if ($etat == 1) {
                    $retour = array("texte" => "en état des lieux", "badge" => "bg-info");
                } else if ($etat == 2) {
                    $retour = array("texte" => "en intervention", "badge" => "bg-info");
                } else if ($etat == 3) {
                    $retour = array("texte" => "attente de validation caution", "badge" => "bg-dark");
                } else if ($etat == 4) {
                    $retour = array("texte" => "demande validée", "badge" => "bg-success");
                }
            }
        }
        if ($type == "avenant") {
            if (isset($itemArray)) {
                $etat = $itemArray["etat"];
                if ($etat == 2) {
                    $retour = array("texte" => "en cours", "badge" => "bg-success");
                } else if ($etat == 1) {
                    $retour = array("texte" => "non activé", "badge" => "bg-info");
                }
            }
        }

        if ($type == "contrat") {
            if (isset($itemArray)) {
                $etat = $itemArray["etat"];
                if ($etat == 0) {
                    $retour = array("texte" => "résilié", "badge" => "bg-danger");
                } else if ($etat == 1) {
                    $retour = array("texte" => "en cours", "badge" => "bg-info");
                } else if ($etat == 2) {
                    $retour = array("texte" => "en location", "badge" => "bg-success");
                }
            }
        }
        if ($type == "locationvente") {
            if (isset($itemArray)) {
                $etat = $itemArray["etat"];
                if ($etat == 1) {
                    $retour = array("texte" => "en cours", "badge" => "bg-info");
                } else if ($etat == 2) {
                    $retour = array("texte" => "en location", "badge" => "bg-success");
                }
            }
        }
        if ($type == "avisecheance") {
            if (isset($itemArray)) {
                $etat = $itemArray["etat"];
                if ($etat == 1) {
                    $retour = array("texte" => "non réglé", "badge" => "bg-danger");
                } else if ($etat == 2) {
                    $retour = array("texte" => "réglé", "badge" => "bg-success");
                } else if ($etat == 3) {
                    $retour = array("texte" => "Paiement annulé", "badge" => "bg-warning");
                } else if ($etat == 4) {
                    $retour = array("texte" => "Paiement partiel", "badge" => "bg-warning");
                }
            }
        }



        if ($type == "facturelocation") {
            if (isset($itemArray)) {
                $etat = $itemArray["etat"];
                if ($etat == 0) {
                    $retour = array("texte" => "non reglée", "badge" => "bg-danger");
                } else if ($etat == 1) {
                    $retour = array("texte" => "reglée", "badge" => "bg-success");
                } else if ($etat == 3) {
                    $retour = array("texte" => "Paiement annulé", "badge" => "bg-warning");
                }
            }
        }


        if ($type == "factureeaux") {
            if (isset($itemArray)) {
                $etat = $itemArray["etat"];
                if ($etat == 0) {
                    $retour = array("texte" => "non reglée", "badge" => "bg-danger");
                } else if ($etat == 1) {
                    $retour = array("texte" => "reglée", "badge" => "bg-success");
                } else if ($etat == 3) {
                    $retour = array("texte" => "Paiement annulé", "badge" => "bg-warning");
                }
            }
        }


        if ($type == "appartement") {
            if (isset($itemArray)) {
                $etat = $itemArray['designation'];
                if ($etat == "En location") {
                    $retour = array("texte" => "En location", "badge" => "bg-warning");
                } else if ($etat == "En construction") {
                    $retour = array("texte" => "En construction", "badge" => "bg-info");
                } else if ($etat == "Libre") {
                    $retour = array("texte" => "Libre", "badge" => "bg-success");
                }
            }
        }
        if ($type == "appartement_etatlieu") {
            if (isset($itemArray)) {
                $etat = $itemArray['etatlieu'];
                if ($etat == "0") {
                    $retour = array("texte" => "Entrée", "badge" => "bg-info");
                } else if ($etat == "1") {
                    $retour = array("texte" => "Sortie", "badge" => "bg-info");
                }
            }
        }


        return $retour;
    }

    public static function compileDispatchingDepenseEntite()
    {
        $retour = false;

        $query = DB::table('depenses')
            ->whereNotIn('depenses.id', EntiteTransactionCaisse::whereNotNull('depense_id')->get(['depense_id']));

        if (isset($query)) {
            $query = $query->groupBy('depenses.id')->get();

            //dd(count($query));

            foreach ($query as $key => $depense) {
                //dd($depense->id);
                $item                     = Depense::find($depense->id);
                $itemsEntitesTransactions = array();
                array_push($itemsEntitesTransactions, array(
                    "entite_id" => $depense->entite_id,
                    "montant"  => 100,
                ));
                Outil::enregistrerDispatchingParEntite($item, $itemsEntitesTransactions);
                $retour = true;
            }
        }
        return $retour;
    }
    public static function compileTitreTraiteur()
    {
        $retour = false;

        $query = PropositionCommerciale::query()->get();

        if (isset($query)) {

            //dd(count($query));

            foreach ($query as $key => $proposition) {
                $prop  = PropositionCommerciale::find($proposition->id);
                if (isset($prop->titre)) {
                    $categorie   = CategorieService::where('designation', Outil::getOperateurLikeDB(), '%' . $prop->titre . '%')->first();
                    if (!isset($categorie)) {
                        $categorie                   = new CategorieService();
                        $categorie->designation      = $prop->titre;
                        $categorie->save();
                        $prop->categorie_service_id  = $categorie->id;
                        $prop->save();
                    } else {
                        if (!isset($prop->categorie_service_id)) {
                            $prop->categorie_service_id  = $categorie->id;
                            $prop->save();
                        }
                    }
                }

                $retour = true;
            }
        }
        return $retour;
    }
    public static function compileCodeBe()
    {
        $retour = false;

        $query = Be::query()
            ->where('codification', 0)
            ->orWhereNull('codification')
            ->get();

        if (isset($query)) {

            foreach ($query as $key => $qe) {
                $be                         = Be::find($qe->id);

                if (isset($be)) {
                    $be->codification       = Outil::donneCodification('be', $be);
                    $code = Outil::getCode($be);
                    if (isset($code)) {
                        $retour = true;
                    } else {
                        $retour = false;
                        break;
                    }
                }
            }
        }
        return $retour;
    }
    public static function compileCodeBci()
    {
        $retour = false;

        $query = Bci::query()
            ->where('codification', 0)
            ->orWhereNull('codification')
            ->get();

        if (isset($query)) {

            foreach ($query as $key => $qe) {
                $bci                         = Bci::find($qe->id);

                if (isset($bci)) {
                    $bci->codification       = Outil::donneCodification('bci', $bci);
                    $code                    = Outil::getCode($bci);
                    if (isset($code)) {
                        $retour              = true;
                    } else {
                        $retour = false;
                        break;
                    }
                }
            }
        }
        return $retour;
    }
    public static function compileMotifDepenseBe()
    {
        $retour = false;

        $query = Depense::query()
            ->whereNotNull('be_id')
            ->get();

        if (isset($query)) {

            foreach ($query as $key => $dep) {
                $depense                      = Depense::find($dep->id);

                if (isset($depense) && isset($depense->be_id)) {
                    $be                       = Be::find($depense->be_id);
                    if (isset($be->fournisseur_id)) {
                        $fournisseur          = Fournisseur::find($be->fournisseur_id);
                    }

                    if (isset($be) && isset($fournisseur)) {
                        $depense->motif       = "BE (code: " . $be->code . ") " . $fournisseur->designation;
                        $depense->save();
                        $retour               = true;
                    }
                }
            }
        }
        return $retour;
    }
    public static function depenseMotifCodeBeSansBe()
    {
        $retour = false;
        $search = 'BE (code:BEN-';

        $allDept = Depense::query()
            ->get();

        $allDeptSansBe = Depense::query()
            ->whereNull('be_id')
            ->get();

        $query = Depense::query()
            ->whereNull('be_id')
            ->where('motif', Outil::getOperateurLikeDB(), '%' . $search . '%')
            ->get();
        //dd(count($query), count($allDept), count($allDeptSansBe));
        if (isset($query)) {

            foreach ($query as $key => $dep) {
                $depense                      = Depense::find($dep->id);
                //dd($depense);

                if (isset($depense) && isset($depense->motif) && empty($depense->be_id)) {
                    $motif  = $depense->motif;

                    $motif  = explode('-', $motif);
                    if (isset($motif) && count($motif)  > 0) {
                        $motif                  = $motif[1];
                        $motif                  = explode(')', $motif);

                        if (isset($motif) && count($motif)  > 0) {
                            $motif              = $motif[0];
                            if (isset($motif)) {
                                $motif          = trim($motif);
                                //dd($motif);
                                $be             = Be::where('code', Outil::getOperateurLikeDB(), '%' . $motif . '%')
                                    ->first();
                                if (isset($be)) {
                                    $depense->be_id = $be->id;
                                    $depense->save();
                                }
                            }
                        }
                    }
                }
            }
        }
        return count($query);
    }

    public static function archiveMenuEchu()
    {
        $dateToday          = now();
        $heureToday         = null;
        $date   = explode(' ', $dateToday);
        $trouve  = false;
        //dd($date[0],$date[1]);
        if (isset($date) && isset($date[0]) && isset($date[1])) {
            $dateToday  =  $date[0];
            $heureToday =  $date[1];
        }
        $query_echu_daufice              = Produit::query()->where('is_menu', true)
            ->where('date_fin_menu', '<', $dateToday)
            ->where('activer', true)
            ->get();
        if (isset($query_echu_daufice)) {
            foreach ($query_echu_daufice as $key => $vald) {
                $vald->activer = false;
                $vald->save();
                $trouve = true;
            }
        }
        $query_echu              = Produit::query()->where('is_menu', true)
            ->where('date_fin_menu', '=', $dateToday)
            ->where('activer', true)
            //                                ->where('heure_fin_menu','<=', '11:42')
            ->get();
        $m = array();
        if (isset($query_echu)) {
            foreach ($query_echu as $key => $valechu) {
                if ($valechu->heure_fin_menu < $heureToday) {
                    $valechu->activer = false;
                    $valechu->save();
                    $trouve = true;
                }
            }
        }

        return  $trouve;
    }

    public static function compileRegule()
    {
        $retour = false;

        $query = Regule::query()
            ->whereNull('entite_id')
            ->get();

        $entite_principale  = Entite::entite_principale();

        if (isset($query) && isset($entite_principale)) {

            foreach ($query as $key => $regl) {
                $regule                      = Regule::find($regl->id);

                if (isset($regule) && !isset($regule->entite_id)) {
                    $regule->entite_id = $entite_principale->id;
                    $regule->save();
                    $retour = true;
                }
            }
        }
        return $retour;
    }
    public static function compileObservationEntreSortie()
    {
        //        $retour = false;
        //
        //        $query = Entresortiestock::query()
        //            ->whereNotNull('be_id')
        //            ->whereNull('observation')
        //            ->get();
        //
        //
        //        if(isset($query)){
        //
        //            foreach ($query as $key=>$es)
        //            {
        //                $mv                      = Entresortiestock::find($es->id);
        //                $be                      = Be::find($es->be_id);
        //                $mv->observation         = 'Bon d\'entrée N°' . $be->code;
        //                $mv->save();
        //                $details                 = Entresortiestockproduit::where('entre_sortie_stock_id',$mv->id)->get();
        //                if(isset($details)){
        //                    foreach ($details as $keyy=>$ds)
        //                    {
        //                        $ds->observation = $mv->observation;
        //                        $ds->save();
        //
        //                        $retour          = true;
        //                    }
        //                }
        //            }
        //        }
        //        return $retour;

        $retour = false;

        $query = Entresortiestock::query()
            ->whereNotNull('commande_id')
            ->get();


        if (isset($query)) {

            foreach ($query as $key => $es) {
                $mv                      = Entresortiestock::find($es->id);
                $commande                = Commande::find($es->commande_id);
                $mv->observation         = 'Commande N°' . $commande->code;
                $mv->save();
                $details                 = Entresortiestockproduit::where('entre_sortie_stock_id', $mv->id)->get();
                if (isset($details)) {
                    foreach ($details as $keyy => $ds) {
                        $ds->observation = $mv->observation;
                        $ds->save();

                        $retour          = true;
                    }
                }
            }
        }
        return $retour;
    }

    public static function compileLigneCredit()
    {
        $retour = false;

        $query = Lignecredit::query()
            ->whereNull('entite_id')
            ->get();

        $entite_principale  = Entite::entite_principale();

        if (isset($query) && isset($entite_principale)) {

            foreach ($query as $key => $regl) {
                $lc              = Lignecredit::find($regl->id);

                if (isset($lc) && !isset($lc->entite_id)) {
                    $lc->entite_id = $entite_principale->id;
                    $lc->save();
                    $retour = true;
                }
            }
        }
        return $retour;
    }

    public static function  getCommandeNoSortieStock()
    {
        $retour  = false;
        $commandes  = Commande::
            //whereNotIn('id', Entresortiestock::whereNotNull('commande_id')->get(['commande_id']))
            whereBetween('date', array('2021-09-01', '2021-09-22'))
            ->where('etat_commande', 8)
            //            ->where('id', 12491)
            ->get();
        // $e_s = Entresortiestock::where('commande_id', 12491)->get();
        //dd($e_s);
        // dd(count($commandes));
        if (isset($commandes) && count($commandes) > 0) {
            foreach ($commandes as $key => $val) {
                Outil::createInOutStock($val);
                $retour  = true;
            }
        }
        return $retour;
    }

    public static function getHoursBydate($date)
    {
        $date_at = $date;
        if ($date_at !== null) {
            $date_at = $date_at;
            $date_at = date_create($date_at);

            return date_format($date_at,  "H:i:s");
        } else {
            return null;
        }
    }

    //Tester si le client peut faire l'objet d'un credit
    public static function donneSoldeCreditClient($itemId, $date = null)
    {
        $client                     = Client::find($itemId);
        $ca_credit                  = 0;
        $montant_c_interne_aprtiel  = 0;
        $ca_commande_menu           = 0;

        $nombre_cumulable = $client->mois_cumullable_credit;

        if (!isset($date)) {
            $date         = now();
        }

        $date             = explode(' ', $date);
        $date             = isset($date) && count($date) > 0 ? $date[0] : null;

        if (isset($date)) {

            if (!isset($nombre_cumulable) || $nombre_cumulable == 1) {
                $date_fin = $date;
            } else {
                $nombre_cumulable = $nombre_cumulable - 1;
                $date_fin         = date('Y-m-d', strtotime($date . ' - ' . $nombre_cumulable . ' months'));
            }

            $date_end                 = date('Y-m-t', strtotime($date));
            $date_start               = date("Y-m-01", strtotime($date_fin));

            //                    $date                 = date('Y-m-t', strtotime($date));
            //                    $date_fin             = date("Y-m-01", strtotime($date_fin));
            if (isset($date_start) && isset($date_end)) {
                $date                 = $date_start;
                $date_fin             = $date_end;
            }


            //Somme total des commandes
            $query                  = DB::table('commande_produits')
                ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                ->where('commande_produits.offre', '!=', true)
                ->whereNull('commande_produits.perte')
                ->whereNull('commande_produits.menu_commande_id')
                ->where('commandes.c_interne', '!=', 1)
                //                                            ->whereIn('commandes.credit', [1,2])
                ->where('commandes.etat_commande', '>=', 8);

            if (isset($date) && isset($date_fin)) {
                $query = $query->whereBetween('commandes.date', [$date . ' 00:00:00', $date_fin . ' 23:59:59']);
            }

            if (isset($client)) {
                $query = $query->where('commandes.client_id', $client->id);
            }

            if (isset($commade_id)) {
                $query = $query->where('commandes.id', '=', $commade_id);
            }

            $query                  = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total');
            $ca_commande            = isset($query) ? $query->first()->total : 0; //Ca des produits commandes

            //On recuperer le ca_commande menu

            if (isset($date) && isset($date_fin)) {
                $ca_commande_menu   =  self::ca_commande_menu($date, $date_fin, null, null, null, null, null, 0, $itemId);
            }

            $montant_c_interne_aprtiel = self::donneSoldeConsoInternePartielClient($itemId, $date);

            //si on a des ca_menu, C'EST A AJOUTER
            $ca_commande            = $ca_commande + $ca_commande_menu;

            //Somme total des paiements commandes
            $ca_paiement            = DB::table('paiements')
                ->join('commandes', 'commandes.id', '=', 'paiements.commande_id')
                ->whereNotNull('commandes.id')
                ->where('commandes.client_id', $client->id)
                ->whereBetween('commandes.date', [$date . ' 00:00:00', $date_fin . ' 23:59:59']);

            $ca_paiement            = $ca_paiement->selectRaw('COALESCE(SUM(paiements.montant),0) as total');
            $ca_paiement            = isset($ca_paiement) ? $ca_paiement->first()->total : 0;
            //SI IL Y'A UNE VALEUR EN CONSO INTERNE PARTIELLE, C'EST A ENLEVER
            $ca_paiement            = $ca_paiement + $montant_c_interne_aprtiel;

            //Calcul solde

            $ca_credit    = $ca_commande - $ca_paiement;
            // }
        }
        return $ca_credit;
    }

    //Tester si le client peut faire l'objet d'un conso interne
    public static function donneSoldeConsoInterneClient($itemId = null, $date = null, $commade_id = null)
    {

        $client               = null;
        $nombre_cumulable     = null;
        if (isset($itemId)) {

            $client           = Client::find($itemId);

            $nombre_cumulable = $client->mois_cumullable_ci;
        }

        $nombre_cumulable = 1;

        if (!isset($date)) {
            $date             = now();
        }

        $date                 = explode(' ', $date);
        $date                 = isset($date) && count($date) > 0 ? $date[0] : null;

        if (isset($date)) {

            if (!isset($nombre_cumulable) || $nombre_cumulable == 1) {
                $date_fin = $date;
            } else {
                $nombre_cumulable = $nombre_cumulable - 1;
                $date_fin         = date('Y-m-d', strtotime($date . ' - ' . $nombre_cumulable . ' months'));
            }

            $date_end                 = date('Y-m-t', strtotime($date));
            $date_start               = date("Y-m-01", strtotime($date_fin));

            //                    $date                 = date('Y-m-t', strtotime($date));
            //                    $date_fin             = date("Y-m-01", strtotime($date_fin));
            if (isset($date_start) && isset($date_end)) {
                $date                 = $date_start;
                $date_fin             = $date_end;
            }
        }

        $query                    = DB::table('commande_produits')
            ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
            ->where('commande_produits.offre', '=', false)
            ->whereNull('commande_produits.perte')
            ->whereNull('commande_produits.menu_commande_id')
            ->whereIn('commandes.c_interne', [1, 2]);

        if (isset($date) && isset($date_fin)) {
            $query                = $query->whereBetween('commandes.date', [$date . ' 00:00:00', $date_fin . ' 23:59:59']);
        }

        if (isset($client)) {
            $query                = $query->where('commandes.client_id', $client->id);
        }

        if (isset($commade_id)) {
            $query                = $query->where('commandes.id', '=', $commade_id);
        }

        $query              = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total');
        $ca_commande        = isset($query) ? $query->first()->total : 0; //C'est le ca des commandes produits

        //On recuperer le ca_commande menu
        $ca_commande_menu   = 0;
        if (isset($date) && isset($date_fin)) {
            $ca_commande_menu   =  self::ca_commande_menu($date, $date_fin, null, null, null, null, null, 1, $itemId);
        }

        //si on a des ca_menu
        $ca_commande        += $ca_commande_menu;

        $paiements              = self::donnePaiementCommandeConsoInternePartielClient($itemId, $date, $commade_id);
        if (isset($paiements) && $paiements > 0) {
            $ca_commande        = $ca_commande - $paiements;
        }

        $ca_conso_intenre    = $ca_commande;

        return $ca_conso_intenre;
    }

    //Tester si le client peut faire l'objet d'un conso interne
    public static function donnePaiementCommandeConsoInternePartielClient($itemId = null, $date = null, $commade_id = null, $entite_id = null)
    {

        $client               = null;
        $nombre_cumulable     = null;
        $paiements            = 0;
        if (isset($itemId)) {

            $client           = Client::find($itemId);

            $nombre_cumulable = $client->mois_cumullable_ci;
        }

        if (!isset($date)) {
            $date             = now();
        }

        $date             = explode(' ', $date);
        $date             = isset($date) && count($date) > 0 ? $date[0] : null;

        if (isset($date)) {

            if (!isset($nombre_cumulable) || $nombre_cumulable == 1) {
                $date_fin = $date;
            } else {
                $nombre_cumulable = $nombre_cumulable - 1;
                $date_fin = date('Y-m-d', strtotime($date . ' + ' . $nombre_cumulable . ' months'));
            }

            $date = date('Y-m-01', strtotime($date));
            $date_fin = date("Y-m-t", strtotime($date_fin));
        }

        $query        = Commande::query()
            ->join('commande_produits', 'commande_produits.commande_id', '=', 'commandes.id')
            ->where('commande_produits.offre', '=', false)
            ->whereNull('commande_produits.perte');
        //            ->whereNull('commande_produits.menu_commande_id');


        if (isset($date) && isset($date_fin)) {
            $query = $query->whereBetween('commandes.date', [$date . ' 00:00:00', $date_fin . ' 23:59:59']);
        }

        if (isset($client)) {
            $query = $query->where('commandes.client_id', $client->id);
        }

        if (isset($entite_id)) {
            $query = $query->where('commandes.entite_id', $entite_id);
        }

        if (isset($commade_id)) {
            $query = $query->where('commandes.id', '=', $commade_id);
        } else {
            $query = $query->where('commandes.c_interne', 2);
        }



        if (isset($query)) {
            $query        = $query->get(['commandes.id']);
            $paiements    = DB::table("paiements")->select(DB::raw("COALESCE(SUM(montant),0) as total"));
            $paiements    = $paiements->whereIn('paiements.commande_id', $query);
            $paiements    = isset($paiements) ? $paiements->first()->total : 0;
        } else {
            $paiements    = 0;
        }


        return $paiements;
    }

    public static function donneSoldeConsoInternePartielClient($itemId = null, $date = null, $commade_id = null, $entite_id = null)
    {

        $client               = null;
        $nombre_cumulable     = null;
        if (isset($itemId)) {

            $client           = Client::find($itemId);

            $nombre_cumulable = $client->mois_cumullable_ci;
        }

        if (!isset($date)) {
            $date             = now();
        }

        $date             = explode(' ', $date);
        $date             = isset($date) && count($date) > 0 ? $date[0] : null;

        if (isset($date)) {

            if (!isset($nombre_cumulable) || $nombre_cumulable == 1) {
                $date_fin = $date;
            } else {
                $nombre_cumulable = $nombre_cumulable - 1;
                $date_fin = date('Y-m-d', strtotime($date . ' + ' . $nombre_cumulable . ' months'));
            }

            $date = date('Y-m-01', strtotime($date));
            $date_fin = date("Y-m-t", strtotime($date_fin));
        }

        $query        = DB::table('commande_produits')
            ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
            ->where('commande_produits.offre', '=', false)
            ->whereNull('commande_produits.perte')
            ->whereNull('commande_produits.menu_commande_id')
            ->where('commandes.c_interne', 2);

        if (isset($date) && isset($date_fin)) {
            $query = $query->whereBetween('commandes.date', [$date . ' 00:00:00', $date_fin . ' 23:59:59']);
        }

        if (isset($client)) {
            $query = $query->where('commandes.client_id', $client->id);
        }

        if (isset($commade_id)) {
            $query = $query->where('commandes.id', '=', $commade_id);
        }
        if (isset($entite_id)) {
            $query = $query->where('commandes.entite_id', '=', $entite_id);
        }

        $query              = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total');
        $ca_commande        = isset($query) ? $query->first()->total : 0; //C'est le ca des commandes produits

        //On recuperer le ca_commande menu
        $ca_commande_menu   = 0;
        if (isset($date) && isset($date_fin)) {
            $ca_commande_menu   =  self::ca_commande_menu($date, $date_fin, null, null, null, null, null, 2, $itemId, $entite_id);
        }

        //si on a des ca_menu
        $ca_commande        += $ca_commande_menu;

        $paiements              = self::donnePaiementCommandeConsoInternePartielClient($itemId, $date, $commade_id, $entite_id);
        if (isset($paiements) && $paiements > 0) {
            $ca_commande        = $ca_commande - $paiements;
        }

        $ca_conso_intenre    = $ca_commande;

        return $ca_conso_intenre;
    }


    //Donne le total des paiements
    public static function donneTotalPaiement($from = "depense", $itemId=null, $typefacture = 'restau')
    {
        $retour = 0;

        if (isset($itemId)) {
            $query = DB::table("paiements")->select(DB::raw("COALESCE(SUM(montant),0) as total"));
            if ($from == "depense") {
                $query = $query->where('depense_id', $itemId);
            } else if ($from == "paiement") {
                $query = $query->where('commande_id', $itemId);
            } else if ($from == "facture") {
                if ($typefacture  == 'restau') {
                    $query = $query->whereIn('commande_id', DetailFacture::where('facture_id', $itemId)->get(['commande_id']));
                } else if ($typefacture  == 'traiteur') {
                    $query = $query->where('facture_id', $itemId);
                }
            } else if ($from == "be") {
                $query = $query->whereIn('depense_id', Depense::where('be_id', $itemId)->get(['id']));
            }

            $retour = $query->first()->total;
        }

        return isset($retour) ? round($retour) : $retour;
    }

    //Donne le total des offerts
    public static function donneTotalOffert($itemId)
    {
        $retour = 0;

        $montant_total_offert = Commandeproduit::query()
            ->join('produits', 'produits.id', '=', 'commande_produits.produit_id')
            ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
            ->where('commandes.id', $itemId)
            ->where('commande_produits.offre', 1)
            ->selectRaw('SUM(commande_produits.montant) as montant_total_offre')
            ->first();

        if (isset($montant_total_offert->montant_total_offre)) {
            $retour           = $montant_total_offert->montant_total_offre;
        }

        return $retour;
    }

    //Donne le total des offerts
    public static function donneTotalPerte($itemId)
    {
        $retour = 0;

        $pertes = DB::table('commande_produits')
            ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
            ->selectRaw('sum(commande_produits.montant) as montant_total_perte')
            ->where('commandes.id', $itemId)
            ->whereNull('commande_produits.menu_commande_id')
            ->where('commande_produits.perte', 1)
            ->first();


        return $retour;
    }

    public static function donneTotalFactureTraiteur($args)
    {
        $retour = 0;

        $query = DB::table("factures")->select(DB::raw("COALESCE(SUM(montant),0) as total"));

        $query = $query->where('type', 2);

        if (!Outil::isAuthorize()) {
            //***[Barrane = NO] ==> [compta = 0]***//
            $query = $query->where('compta', 0);
        } else if (Outil::isAuthorize() == 1) {
            $query = $query->where('compta', '<=', 1);
        } else {
            //***[Barrane = YES] ==> Aurorisé à filtrer**//
            if (isset($args) && isset($args['compta'])) {
                $query = $query->where('compta', $args['compta']);
            }
        }

        if (isset($args)) {

            if (isset($args['facturetraiteur_id'])) {
                $args['id']  = $args['facturetraiteur_id'];
            }

            if (isset($args['id'])) {
                $query = $query->where('id', $args['id']);
            }

            if (isset($args['designation'])) {
                $query = $query->where('designation', Outil::getOperateurLikeDB(), '%' . $args['designation'] . '%');
            }

            if (isset($args['date'])) {
                $query = $query->whereDate('date', '=', $args['date']);
            }

            if (isset($args['exonoration'])) {
                $query = $query->where('exonoration', $args['exonoration']);
            }

            if (isset($args['client_id'])) {
                $query = $query->where('client_id', $args['client_id']);
            }

            if (isset($args['societe_facturation_id'])) {
                $query = $query->where('societe_facturation_id', $args['societe_facturation_id']);
            }

            if (isset($args['entite_id'])) {
                //var_dump($args['entite_id']);
                $query = $query->where('entite_id', $args['entite_id']);
            }

            if (isset($args['date_start']) && isset($args['date_end'])) {
                $query = $query->whereBetween('date', [$args['date_start'], $args['date_end']]);
            }
        }

        $retour = $query->first()->total;

        return isset($retour) ? round($retour) : $retour;
    }

    public static function donneTotalGeneral($from = "be", $itemId = null)
    {
        $retour = 0;

        if ($from == "be") {
            $query = DB::table("be_produits")->select(DB::raw("COALESCE(SUM(prix_achat * quantite),0) as total"));
            if (isset($itemId)) {
                $query = $query->where('be_id', $itemId);
            }
            $retour = $query->first()->total;
        }
        if ($from == "be_produit") {
            $query = DB::table("be_produits")->select(DB::raw("COALESCE(SUM(prix_achat * quantite),0) as total"));
            if (isset($itemId)) {
                $query = $query->where('id', $itemId);
            }
            $retour = $query->first()->total;
        }

        return round($retour);
    }
    public static function donneTotalBeFournisseur($type = "chiffre_affaire", $itemId = null, $entite_id  = null)
    {
        $retour = 0;
        $retour = Be::query();
        if (isset($itemId)) {
            $retour = $retour->where('fournisseur_id', $itemId);
        }
        if (isset($entite_id)) {
            $retour = $retour->whereIn('depot_id', Depot::where('entite_id', $entite_id)->get(['id']));
        }
        if ($type == "chiffre_affaire") {
            if (isset($retour)) {
                //                $retour     = $retour->selectRaw("COALESCE(SUM(montant),0) as total")->first();
                $retour     = $retour->join('be_produits', 'be_produits.be_id', 'bes.id');
                $retour     = $retour->select(DB::raw("COALESCE(SUM(prix_achat * quantite),0) as total"));
                $retour     = $retour->first();
                if (isset($retour) && isset($retour->total)) {
                    $retour = $retour->total;
                }
            }
        } else if ($type == "quantite") {
            if (isset($retour)) {
                $retour = $retour->count();
            }
        }
        return $retour;
    }

    public static function donneTotalFournisseur($type = "chiffre_affaire", $itemId = null, $entite_id  = null)
    {
        $retour = 0;
        if ($type == "chiffre_affaire") {
            //Total dépense + Total régule crédit
            $queryDepense = DB::table("depenses")->select(DB::raw("COALESCE(SUM(montant),0) as total"));
            if (isset($itemId)) {
                $queryDepense = $queryDepense->where('fournisseur_id', $itemId);
            }
            $queryDepense = $queryDepense->first();

            //            $queryReguleCredit = DB::table("regules")->select(DB::raw("COALESCE(SUM(montant),0) as total"));
            //            $queryReguleCredit = $queryReguleCredit->whereNotNull('fournisseur_id');
            //            $queryReguleCredit = $queryReguleCredit->whereIn('type_regule_id', TypeRegule::where('est_debit', 0)->get(['id']));
            //            if (isset($itemId))
            //            {
            //                $queryReguleCredit = $queryReguleCredit->where('fournisseur_id', $itemId);
            //            }
            //            $queryReguleCredit = $queryReguleCredit->first();

            //$retour = $queryDepense->total + $queryReguleCredit->total;
            $retour = $queryDepense->total;
        } else if ($type == "solde") {
            //(Total dépense + Total régule crédit) - (Total règlement + Total régule débit)

            //---Depenses
            $queryDepense = DB::table("depenses")->select(DB::raw("COALESCE(SUM(montant),0) as total"));
            if (isset($itemId)) {
                $queryDepense = $queryDepense->where('fournisseur_id', $itemId);
            }
            if (isset($entite_id)) {
                $queryDepense = $queryDepense->where('entite_id', $entite_id);
            }
            $queryDepense = $queryDepense->first();

            //---Régules crédit
            $queryReguleCredit = DB::table("regules")->select(DB::raw("COALESCE(SUM(montant),0) as total"));
            $queryReguleCredit = $queryReguleCredit->whereNotNull('fournisseur_id');
            $queryReguleCredit = $queryReguleCredit->whereIn('type_regule_id', TypeRegule::where('est_debit', 0)->get(['id']));
            if (isset($itemId)) {
                $queryReguleCredit = $queryReguleCredit->where('fournisseur_id', $itemId);
            }
            if (isset($entite_id)) {
                $queryReguleCredit = $queryReguleCredit->where('entite_id', $entite_id);
            }
            $queryReguleCredit = $queryReguleCredit->first();

            //---Règlements
            $queryReglement = DB::table("paiements")->select(DB::raw("COALESCE(SUM(montant),0) as total"));
            $queryReglement = $queryReglement->whereNotNull('depense_id');
            if (isset($itemId)) {
                $queryReglement = $queryReglement->whereIn('depense_id', Depense::where('fournisseur_id', $itemId)->get(['id']));
            }
            if (isset($entite_id)) {
                $queryReglement = $queryReglement->whereIn('depense_id', Depense::where('entite_id', $entite_id)->get(['id']));
            }

            $queryReglement = $queryReglement->first();

            //---Régules débit
            $queryReguleDebit = DB::table("regules")->select(DB::raw("COALESCE(SUM(montant),0) as total"));
            $queryReguleDebit = $queryReguleDebit->whereNotNull('fournisseur_id');
            $queryReguleDebit = $queryReguleDebit->whereIn('type_regule_id', TypeRegule::where('est_debit', 1)->get(['id']));
            if (isset($itemId)) {
                $queryReguleDebit = $queryReguleDebit->where('fournisseur_id', $itemId);
            }
            if (isset($entite_id)) {
                $queryReguleDebit = $queryReguleDebit->where('entite_id', $entite_id);
            }
            $queryReguleDebit = $queryReguleDebit->first();

            $retour = ($queryDepense->total + $queryReguleCredit->total) - ($queryReglement->total + $queryReguleDebit->total);
        } else if ($type == "deja_paye") {
            //Total Règlements
            $queryReglement = DB::table("paiements")->select(DB::raw("COALESCE(SUM(montant),0) as total"));
            $queryReglement = $queryReglement->whereNotNull('depense_id');
            if (isset($itemId)) {
                $queryReglement = $queryReglement->whereIn('depense_id', Depense::where('fournisseur_id', $itemId)->get(['id']));
            }
            $queryReglement = $queryReglement->first();

            $retour = $queryReglement->total;
        }

        return $retour;
    }

    public static  function statiqueCommande($type = 'quantite', $date_start = null, $date_end = null, $idEntite = null)
    {
        $retour  = 0;

        $query = Commande::query();
        if (isset($date_start) && isset($date_end)) {
            $date_start = $date_start . " 00:00:00";
            $date_end = $date_end . " 23:59:59";

            $query = $query->whereBetween('date', array($date_start, $date_end));
        }
        if (isset($idEntite)) {
            $query = $query->where('entite_id', $idEntite);
        }
        if ($type == 'quantite') {
            if (isset($query)) {
                $retour = $query->get();
                $retour = count($retour);
            }
        }


        return $retour;
    }
    public static  function statiqueDepense($type = 'quantite', $date_start = null, $date_end = null, $idEntite = null)
    {
        $retour  = 0;

        $query = Depense::query();
        if (isset($date_start) && isset($date_end)) {
            $date_start = $date_start . " 00:00:00";
            $date_end = $date_end . " 23:59:59";

            $query = $query->whereBetween('date', array($date_start, $date_end));
        }
        if (isset($idEntite)) {
            $query = $query->where('entite_id', $idEntite);
        }
        if ($type == 'quantite') {
            if (isset($query)) {
                $retour = $query->get();
                $retour = count($retour);
            }
        } else if ($type == 'ca') {
            $retour = $query->selectRaw("COALESCE(SUM(montant),0) as total")->first();
            if (isset($retour)) {
                $retour   = $retour->total;
            }
        }


        return $retour;
    }
    public static  function statiqueTraiteur($type = 'quantite', $date_start = null, $date_end = null, $idEntite = null)
    {
        $retour  = 0;

        $query = Proforma::query()->where('etat', '>', 0);
        if (isset($date_start) && isset($date_end)) {
            $date_start = $date_start . " 00:00:00";
            $date_end = $date_end . " 23:59:59";

            $query = $query->whereBetween('created_at', array($date_start, $date_end));
        }
        if (isset($idEntite)) {
            $query = $query->where('entite_id', $idEntite);
        }

        $query = $query->get();

        if ($type == 'quantite') {
            if (isset($query)) {

                $retour = count($query);
            }
        }

        return $retour;
    }
    public static  function outil_proforma_traiteur($traiteur)
    {
        //$traiteur       = Outil::getOneItemWithGraphQl("traiteurs", $id);
        if (isset($traiteur)) {
            $propositions  = $traiteur['proposition_commericales'];
        }
        // $proposition    = PropositionCommerciale::where('proforma_id', $id)->where('est_activer',1)->first();
        if (isset($propositions) && count($propositions) > 0) {
            $montant_total_ttc = 0;

            foreach ($propositions as $key => $val) {
                if ($val['est_activer'] == 1) {
                    $proposition = $val;
                    $montant_total_ht = 0;

                    $montant_total_ht_remise = 0;
                    $montant_tva = 0;
                    $montant_remise = 0;
                    if (isset($proposition['forfait_direct_menu']) && $proposition['forfait_direct_menu'] == true) {
                        $montant_total_ht = $proposition['forfait'];
                    } else {
                        if ($proposition['nombre_personne'] == 0) {
                            $proposition['nombre_personne'] = $traiteur['nombre_personne'];
                        }

                        $montant_total_ht = $proposition['montant_par_personne'] * $proposition['nombre_personne'];
                    }

                    //Forfait materiel
                    if (isset($proposition['forfait_option_materiel'])) {
                        $montant_total_ht += $proposition['forfait_option_materiel'];
                    }

                    //Remise
                    if (isset($proposition['remise'])) {
                        $remise                  = ($montant_total_ht * $proposition['remise']) / 100;
                        if ($montant_total_ht > $remise) {
                            $montant_remise          = $remise;
                            $montant_total_ht_remise = $montant_total_ht - $montant_remise;
                        }
                    }

                    //exotva
                    if (!isset($proposition['exotva']) || $proposition['exotva'] == false) {
                        $montant_tva = ($montant_total_ht_remise * 18) / 100;
                    }

                    $montant_total_ttc = $montant_total_ht_remise + $montant_tva;
                }
            }
            $data = array(
                'item' => '',
                'montant_total_ttc'       => round($montant_total_ttc),
            );
        }


        return $data;
    }

    public static function donneProduitsCommande($parametres, $type = 'vente')
    {
        $dateStart          = isset($parametres["dateStart"])    ? $parametres["dateStart"]  : null;
        $dateEnd            = isset($parametres["dateEnd"])      ? $parametres["dateEnd"]    : null;
        $caisseId           = isset($parametres["caisseId"])     ? $parametres["caisseId"]   : null;
        $permission         = isset($parametres["permission"])   ? $parametres["permission"] : null;

        $heure_debut        = isset($parametres["heure_debut"])   ? $parametres["heure_debut"] : null;
        $heure_fin          = isset($parametres["heure_fin"])   ? $parametres["heure_fin"] : null;

        $entite_id          = isset($parametres["entite_id"])   ? $parametres["entite_id"] : null;
        $client_id          = isset($parametres["client_id"])   ? $parametres["client_id"] : null;
        $type_commande_id   = isset($parametres["type_commande_id"])   ? $parametres["type_commande_id"] : null;
        $table_id           = isset($parametres["table_id"])   ? $parametres["table_id"] : null;
        $commande_id        = isset($parametres["commande_id"])   ? $parametres["commande_id"] : null;

        $etat_commande      = isset($parametres["etat_commande"])   ? $parametres["etat_commande"] : null;
        $etat_paiement      = isset($parametres["etat_paiement"])   ? $parametres["etat_paiement"] : null;
        $perte              = isset($parametres["perte"])   ? $parametres["perte"] : null;
        $client_passage     = isset($parametres["client_passage"])   ? $parametres["client_passage"] : null;
        $tranche_horaire_id = isset($parametres["tranche_horaire_id"])   ? $parametres["tranche_horaire_id"] : null;
        $type_client_id     = isset($parametres["type_client_id"])   ? $parametres["type_client_id"] : null;
        $mode_paiement_id   = isset($parametres["mode_paiement_id"])   ? $parametres["mode_paiement_id"] : null;
        $famille_id         = isset($parametres["famille_id"])   ? $parametres["famille_id"] : null;

        if (empty($dateStart) || empty($dateEnd)) {
            $trancheHoraireEnCours  = Outil::donneTrancheHoraire();
            if (isset($trancheHoraireEnCours)) {
                $dateToday          = date('Y-m-d');
                $heureStart         = substr($trancheHoraireEnCours->heure_debut, 11, 5);
                $heureEnd           = substr($trancheHoraireEnCours->heure_fin, 11, 5);
                $dateStart          = $dateToday;
                $dateEnd            = $dateToday;
            }
        }

        if (isset($dateStart) || isset($dateEnd)) {
            if (!isset($heure_debut) || !isset($heure_fin)) {
                if (isset($tranche_horaire_id)) {
                    $tranche_horaire     = Tranchehoraire::find($tranche_horaire_id);

                    $heure_debut = Carbon::parse($tranche_horaire->heure_debut)->format('H:i:s');
                    $heure_fin   = Carbon::parse($tranche_horaire->heure_fin)->format('H:i:s');
                } else {
                    $heure_debut  = '00:00:00';
                    $heure_fin    = '23:59:59';
                }
            } else {

                $heure_debut  = $heure_debut . ':00';
                $heure_fin    = $heure_fin . ':59';
            }

            if ($heure_debut > $heure_fin) {
                if ($dateStart == $dateEnd) {
                    $dateEnd = date('Y-m-d', strtotime($dateEnd . ' + 1 days'));
                }
            }

            $dateStart        = $dateStart . ' ' . $heure_debut;
            $dateEnd          = $dateEnd . ' ' . $heure_fin;
        }

        if (empty($caisseId)) {
            $caisseUserConnected = Outil::donneCaisseUser();
            $caisseId = isset($caisseUserConnected) ? $caisseUserConnected : null;
        }

        $query = Commandeproduit::query()
            ->join('produits', 'produits.id', '=', 'commande_produits.produit_id')
            ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
            ->whereNull('commande_produits.menu_commande_id')
            ->groupBy(['commande_produits.produit_id', 'produits.designation'])
            ->selectRaw('COALESCE(SUM(commande_produits.montant),0) as montant,
                                   count(commande_produits.produit_id) as quantite,
                                   produits.designation as designation,
                                   commande_produits.produit_id as id');
        if ($type !== 'conso') {
            $query = $query->whereNotIn('commandes.c_interne', [1, 2]);
        } else {
            $query = $query->where('commandes.c_interne', 1);
        }
        if (!isset($dateStart) && !isset($dateEnd)) {
            if (isset($tranche_horaire_id)) {
                $query              = $query->where('commandes.tranche_horaire_id', $tranche_horaire_id);
            }
        }


        if (isset($famille_id)) {
            $query              = $query
                ->join('familles', 'familles.id', 'produits.famille_id')
                ->where('produits.famille_id', $famille_id);
        }

        if (isset($mode_paiement_id)) {
            $query      = $query->whereIn('commandes.id', Paiement::whereNotNull('commande_id')->where('mode_paiement_id', $mode_paiement_id)->get(['commande_id']));
        }
        if (isset($type_client_id)) {
            $query = $query
                ->whereIn('commandes.client_id', Client::where('type_client_id', $type_client_id)
                    ->get(["id"]));
        }
        if ((!empty($dateStart)) && (!empty($dateEnd))) {

            $query->whereBetween('commandes.date', array($dateStart, $dateEnd));

            if ((!empty($caisseId))) {
                $caisse = Caisse::find($caisseId);
                if (isset($caisse->entite_id) && isset($caisse->entite_id)) {
                    $query->where('commandes.entite_id', $caisse->entite_id);
                } else {
                    $query->where('commandes.entite_id', null);
                }
            }
        }

        if ($permission == 'list-commande-departement' || $permission == "list-commande-encour" || $permission == "list-commande") {
            $date   = now();
            $date   = explode(' ', $date);

            $query  = $query->where('commandes.etat_commande', '!=', 8);
        }

        if (isset($entite_id)) {
            $query = $query->where('commandes.entite_id', $entite_id);
        }
        if (isset($client_id)) {
            $query = $query->where('commandes.client_id', $client_id);
        }
        if (isset($type_commande_id)) {
            $query = $query->where('commandes.type_commande_id', $type_commande_id);
        }
        if (isset($table_id)) {
            $query = $query->whereIn('commandes.id', CommandeTable::where('table_id', $table_id)->get(['commande_id']));
        }
        if (isset($commande_id)) {
            $query = $query->where('commandes.id', $commande_id);
        }
        if (isset($etat_paiement)) {
            if ($etat_paiement == 0) {
                $query = $query->where('commandes.montant_total_paye', '=', 0);
            } else if ($etat_paiement == 1) {
                $query = $query->where('commandes.montant_total_paye', '>', 0);
                $query = $query->where('commandes.restant_payer', '>', 0);
            } else if ($etat_paiement == 2) {
                $query = $query->where('restant_payer', '=', 0);
            }
        }
        if (isset($etat_commande)) {
            if ($etat_commande == 0) {
                $query = $query->where('commandes.etat_commande', '<', 4);
            } else {
                $query = $query->where('commandes.etat_commande', $etat_commande);
            }
        }
        if (isset($perte)) {
            $query = $query->where('commandes.perte', 1);
        }
        if (isset($client_passage)) {
            $query     = $query->whereNull('commandes.client_id');
        }

        if ($type !== 'perte') {
            $query = $query
                ->whereNull('commande_produits.perte');
        } else {
            $query = $query
                ->where('commande_produits.perte', 1);
        }

        if ($type !== 'offert') {
            $query = $query->where('commande_produits.offre', '=', false);
        } else {
            $query = $query->where('commande_produits.offre', true);
        }

        return  $query->get();
    }

    public static function donneTotalCommande($parametres)
    {
        $dateStart          = isset($parametres["dateStart"])    ? $parametres["dateStart"]  : null;
        $dateEnd            = isset($parametres["dateEnd"])      ? $parametres["dateEnd"]    : null;
        $caisseId           = isset($parametres["caisseId"])     ? $parametres["caisseId"]   : null;
        $permission         = isset($parametres["permission"])   ? $parametres["permission"] : null;

        $heure_debut        = isset($parametres["heure_debut"])   ? $parametres["heure_debut"] : null;
        $heure_fin          = isset($parametres["heure_fin"])   ? $parametres["heure_fin"] : null;

        $entite_id          = isset($parametres["entite_id"])   ? $parametres["entite_id"] : null;
        $client_id          = isset($parametres["client_id"])   ? $parametres["client_id"] : null;
        $type_commande_id   = isset($parametres["type_commande_id"])   ? $parametres["type_commande_id"] : null;
        $table_id           = isset($parametres["table_id"])   ? $parametres["table_id"] : null;
        $commande_id        = isset($parametres["commande_id"])   ? $parametres["commande_id"] : null;

        $etat_commande      = isset($parametres["etat_commande"])   ? $parametres["etat_commande"] : null;
        $etat_paiement      = isset($parametres["etat_paiement"])   ? $parametres["etat_paiement"] : null;
        $perte              = isset($parametres["perte"])   ? $parametres["perte"] : null;
        $client_passage     = isset($parametres["client_passage"])   ? $parametres["client_passage"] : null;
        $tranche_horaire_id = isset($parametres["tranche_horaire_id"])   ? $parametres["tranche_horaire_id"] : null;
        $type_client_id     = isset($parametres["type_client_id"])   ? $parametres["type_client_id"] : null;
        $mode_paiement_id   = isset($parametres["mode_paiement_id"])   ? $parametres["mode_paiement_id"] : null;
        $famille_id         = isset($parametres["famille_id"])   ? $parametres["famille_id"] : null;
        $menu_id            = isset($parametres["menu_id"])   ? $parametres["menu_id"] : null;
        $nomenclature_id    = isset($parametres["nomenclature_id"])   ? $parametres["nomenclature_id"] : null;

        $montant_offert = 0;
        $montant_perte = 0;
        $montant_conso = 0;
        $total_paiement_commande_conso_partiel = 0;

        if (empty($dateStart) || empty($dateEnd)) {
            $trancheHoraireEnCours  = Outil::donneTrancheHoraire();
            if (isset($trancheHoraireEnCours)) {
                $dateToday          = date('Y-m-d');
                $heureStart         = substr($trancheHoraireEnCours->heure_debut, 11, 5);
                $heureEnd           = substr($trancheHoraireEnCours->heure_fin, 11, 5);
                $dateStart          = $dateToday;
                $dateEnd            = $dateToday;
            }
        }

        if (isset($dateStart) || isset($dateEnd)) {
            if (!isset($heure_debut) || !isset($heure_fin)) {
                if (isset($tranche_horaire_id)) {
                    $tranche_horaire     = Tranchehoraire::find($tranche_horaire_id);

                    $heure_debut = Carbon::parse($tranche_horaire->heure_debut)->format('H:i:s');
                    $heure_fin   = Carbon::parse($tranche_horaire->heure_fin)->format('H:i:s');
                } else {
                    $heure_debut  = '00:00:00';
                    $heure_fin    = '23:59:59';
                }
            } else {
                $heure_debut  = $heure_debut . ':00';
                $heure_fin    = $heure_fin . ':59';
            }
            if ($heure_debut > $heure_fin) {
                if ($dateStart == $dateEnd) {
                    $dateEnd = date('Y-m-d', strtotime($dateEnd . ' + 1 days'));
                }
            }
            $dateStart        = $dateStart . ' ' . $heure_debut;
            $dateEnd          = $dateEnd . ' ' . $heure_fin;
        }

        if (empty($caisseId)) {
            $caisseUserConnected = Outil::donneCaisseUser();
            $caisseId = isset($caisseUserConnected) ? $caisseUserConnected : null;
        }

        // var_dump($dateStart . ' ' . $dateEnd);

        $montant_offert = Outil::donneTotalCommandeOffert($parametres);
        $montant_perte  = Outil::donneTotalCommandePerte($parametres);
        //$montant_conso  = Outil::donneTotalCommandeConsoInterne($parametres);
        if (!isset($famille_id) && !isset($nomenclature_id)) {
            $total_paiement_commande_conso_partiel  = self::donneTotalPaiementCommandeConsoInternePartiel($parametres);
        } else {
            $total_paiement_commande_conso_partiel = 0;
        }

        $query = null;
        $query = Commandeproduit::query()
            ->join('produits', 'produits.id', '=', 'commande_produits.produit_id')
            ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
            ->whereNull('commande_produits.menu_commande_id')
            ->selectRaw('SUM(commande_produits.montant) as total_periode');
        $query = $query->whereNotIn('commandes.c_interne', [1, 2]);


        $query_menu = CommandeMenu::query()
            ->join('commandes', 'commandes.id', '=', 'commande_menus.commande_id')
            ->selectRaw('sum(commande_menus.montant) as montant_offert_menu');

        $query_menu = $query_menu->whereNotIn('commandes.c_interne', [1, 2]);

        if (isset($famille_id)) {
            $query              = $query
                ->join('familles', 'familles.id', 'produits.famille_id')
                ->where('produits.famille_id', $famille_id);
        }

        if (isset($nomenclature_id)) {
            $query              = $query
                ->join('nomenclatures', 'nomenclatures.id', 'produits.nomenclature_id')
                ->where('produits.nomenclature_id', $nomenclature_id);
        }

        if (isset($menu_id)) {
            $query_menu         = $query_menu->where('commande_menus.menu_id', $menu_id);
        }

        if (isset($mode_paiement_id)) {
            $query      = $query->whereIn('commandes.id', Paiement::whereNotNull('commande_id')->where('mode_paiement_id', $mode_paiement_id)->get(['commande_id']));
            $query_menu = $query_menu->whereIn('commandes.id', Paiement::whereNotNull('commande_id')->where('mode_paiement_id', $mode_paiement_id)->get(['commande_id']));
        }
        if (isset($type_client_id)) {
            $query = $query
                ->whereIn('commandes.client_id', Client::where('type_client_id', $type_client_id)
                    ->get(["id"]));


            $query_menu = $query_menu
                ->whereIn('commandes.client_id', Client::where('type_client_id', $type_client_id)
                    ->get(["id"]));
        }
        if ((!empty($dateStart)) && (!empty($dateEnd))) {

            //var_dump($heure_debut,$heure_fin);

            $query->whereBetween('commandes.date', array($dateStart, $dateEnd));
            $query_menu->whereBetween('commandes.date', array($dateStart, $dateEnd));

            if ((!empty($caisseId))) {
                $caisse = Caisse::find($caisseId);
                if (isset($caisse->entite_id) && isset($caisse->entite_id)) {
                    $query->where('commandes.entite_id', $caisse->entite_id);
                    $query_menu->where('commandes.entite_id', $caisse->entite_id);
                } else {
                    $query->where('commandes.entite_id', null);
                    $query_menu->where('commandes.entite_id', null);
                }
            }
        } else {
            if (isset($tranche_horaire_id)) {
                $query              = $query->where('commandes.tranche_horaire_id', $tranche_horaire_id);
                $query_menu         = $query_menu->where('commandes.tranche_horaire_id', $tranche_horaire_id);
            }
        }

        if ($permission == 'list-commande-departement' || $permission == "list-commande-encour" || $permission == "list-commande") {
            $date   = now();
            $date   = explode(' ', $date);

            $query  = $query->where('commandes.etat_commande', '!=', 8);
            $query_menu  = $query_menu->where('commandes.etat_commande', '!=', 8);
        }

        if (isset($entite_id)) {
            $query = $query->where('commandes.entite_id', $entite_id);
            $query_menu = $query_menu->where('entite_id', $entite_id);
        }
        if (isset($client_id)) {
            $query = $query->where('commandes.client_id', $client_id);
            $query_menu = $query_menu->where('commandes.client_id', $client_id);
        }
        if (isset($type_commande_id)) {
            $query      = $query->where('commandes.type_commande_id', $type_commande_id);
            $query_menu = $query_menu->where('commandes.type_commande_id', $type_commande_id);
        }
        if (isset($table_id)) {
            $query = $query->whereIn('commandes.id', CommandeTable::where('table_id', $table_id)->get(['commande_id']));
            $query_menu = $query_menu->whereIn('commandes.id', CommandeTable::where('table_id', $table_id)->get(['commande_id']));
        }
        if (isset($commande_id)) {
            $query = $query->where('commandes.id', $commande_id);
            $query_menu = $query_menu->where('commandes.id', $commande_id);
        }
        if (isset($etat_paiement)) {
            if ($etat_paiement == 0) {
                $query = $query->where('commandes.montant_total_paye', '=', 0);
                $query_menu = $query_menu->where('commandes.montant_total_paye', '=', 0);
            } else if ($etat_paiement == 1) {
                $query = $query->where('commandes.montant_total_paye', '>', 0);
                $query = $query->where('commandes.restant_payer', '>', 0);

                $query_menu = $query_menu->where('commandes.montant_total_paye', '>', 0);
                $query_menu = $query_menu->where('commandes.restant_payer', '>', 0);
            } else if ($etat_paiement == 2) {
                $query = $query->where('restant_payer', '=', 0);
            }
        }
        if (isset($etat_commande)) {
            if ($etat_commande == 0) {
                $query = $query->where('commandes.etat_commande', '<', 4);
                $query_menu = $query_menu->where('commandes.etat_commande', '<', 4);
            } else {
                $query = $query->where('commandes.etat_commande', $etat_commande);
                $query_menu = $query_menu->where('commandes.etat_commande', $etat_commande);
            }
        }
        if (isset($perte)) {
            $query = $query->where('commandes.perte', 1);
            $query_menu = $query_menu->where('commandes.perte', 1);
        }
        if (isset($client_passage)) {
            $query     = $query->whereNull('commandes.client_id');
            $query_menu     = $query_menu->whereNull('commandes.client_id');
        }


        if (!isset($menu_id)) {
            $query               =  $query->first()->total_periode;
            $retour              = isset($query) ? $query : 0;
        } else {
            $retour = 0;
        }
        $query_menu          =  $query_menu->first()->montant_offert_menu;

        if (isset($query_menu)) {

            if (!isset($famille_id) && !isset($nomenclature_id)) {
                $retour += $query_menu;
            }
        }

        return ($retour + $total_paiement_commande_conso_partiel) - ($montant_offert + $montant_perte);
    }

    public static function donneTotalCommandeOffert($parametres)
    {
        $dateStart   = isset($parametres["dateStart"])    ? $parametres["dateStart"]  : null;
        $dateEnd     = isset($parametres["dateEnd"])      ? $parametres["dateEnd"]    : null;
        $caisseId    = isset($parametres["caisseId"])     ? $parametres["caisseId"]   : null;
        $permission  = isset($parametres["permission"])   ? $parametres["permission"] : null;

        $heure_debut = isset($parametres["heure_debut"])   ? $parametres["heure_debut"] : null;
        $heure_fin   = isset($parametres["heure_fin"])   ? $parametres["heure_fin"] : null;

        $entite_id          = isset($parametres["entite_id"])   ? $parametres["entite_id"] : null;
        $client_id          = isset($parametres["client_id"])   ? $parametres["client_id"] : null;
        $type_commande_id   = isset($parametres["type_commande_id"])   ? $parametres["type_commande_id"] : null;
        $table_id           = isset($parametres["table_id"])   ? $parametres["table_id"] : null;
        $commande_id        = isset($parametres["commande_id"])   ? $parametres["commande_id"] : null;

        $etat_commande      = isset($parametres["etat_commande"])   ? $parametres["etat_commande"] : null;
        $etat_paiement      = isset($parametres["etat_paiement"])   ? $parametres["etat_paiement"] : null;
        $perte              = isset($parametres["perte"])   ? $parametres["perte"] : null;
        $client_passage     = isset($parametres["client_passage"])   ? $parametres["client_passage"] : null;
        $tranche_horaire_id = isset($parametres["tranche_horaire_id"])   ? $parametres["tranche_horaire_id"] : null;
        $type_client_id     = isset($parametres["type_client_id"])   ? $parametres["type_client_id"] : null;
        $mode_paiement_id   = isset($parametres["mode_paiement_id"])   ? $parametres["mode_paiement_id"] : null;
        $famille_id         = isset($parametres["famille_id"])   ? $parametres["famille_id"] : null;
        $menu_id            = isset($parametres["menu_id"])   ? $parametres["menu_id"] : null;
        $nomenclature_id    = isset($parametres["nomenclature_id"])   ? $parametres["nomenclature_id"] : null;


        $offert      = 0;

        if (empty($dateStart) || empty($dateEnd)) {
            $trancheHoraireEnCours  = Outil::donneTrancheHoraire();
            if (isset($trancheHoraireEnCours)) {
                $dateToday          = date('Y-m-d');
                $heureStart         = substr($trancheHoraireEnCours->heure_debut, 11, 5);
                $heureEnd           = substr($trancheHoraireEnCours->heure_fin, 11, 5);
                $dateStart          = $dateToday;
                $dateEnd            = $dateToday;
            }
        }

        if (isset($dateStart) || isset($dateEnd)) {
            if (!isset($heure_debut) || !isset($heure_fin)) {
                if (isset($tranche_horaire_id)) {
                    $tranche_horaire     = Tranchehoraire::find($tranche_horaire_id);

                    $heure_debut = Carbon::parse($tranche_horaire->heure_debut)->format('H:i:s');
                    $heure_fin   = Carbon::parse($tranche_horaire->heure_fin)->format('H:i:s');
                } else {
                    $heure_debut  = '00:00:00';
                    $heure_fin    = '23:59:59';
                }
            } else {
                $heure_debut  = $heure_debut . ':00';
                $heure_fin    = $heure_fin . ':59';
            }
            if ($heure_debut > $heure_fin) {
                if ($dateStart == $dateEnd) {
                    $dateEnd = date('Y-m-d', strtotime($dateEnd . ' + 1 days'));
                }
            }
            $dateStart = $dateStart . ' ' . $heure_debut;
            $dateEnd   = $dateEnd . ' ' . $heure_fin;
        }

        if (empty($caisseId)) {
            $caisseUserConnected = Outil::donneCaisseUser();
            $caisseId = isset($caisseUserConnected) ? $caisseUserConnected : null;
        }

        //        $query = null;
        //        $query = DB::table('commandes')
        //            ->selectRaw('sum(commandes.montant_total_commande) as total_periode');

        $montant_total_offert = Commandeproduit::query()
            ->join('produits', 'produits.id', '=', 'commande_produits.produit_id')
            ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
            ->where('commande_produits.offre', true)
            ->whereNull('commande_produits.menu_commande_id')
            ->selectRaw('SUM(commande_produits.montant) as montant_total_offre');

        $montant_total_offert_menu = CommandeMenu::query()
            ->join('commandes', 'commandes.id', '=', 'commande_menus.commande_id')
            ->where('commande_menus.offre', true)
            ->selectRaw('sum(commande_menus.montant) as montant_offert_menu');



        if (isset($famille_id)) {
            $montant_total_offert              = $montant_total_offert
                ->join('familles', 'familles.id', 'produits.famille_id')
                ->where('produits.famille_id', $famille_id);
        }
        if (isset($nomenclature_id)) {
            $montant_total_offert              = $montant_total_offert
                ->join('nomenclatures', 'nomenclatures.id', 'produits.nomenclature_id')
                ->where('produits.nomenclature_id', $nomenclature_id);
        }
        if (isset($menu_id)) {
            $montant_total_offert_menu         = $montant_total_offert_menu->where('commande_menus.menu_id', $menu_id);
        }
        if (isset($mode_paiement_id)) {
            $montant_total_offert = $montant_total_offert->whereIn('commandes.id', Paiement::whereNotNull('commande_id')->where('mode_paiement_id', $mode_paiement_id)->get(['commande_id']));
            $montant_total_offert_menu = $montant_total_offert_menu->whereIn('commandes.id', Paiement::whereNotNull('commande_id')->where('mode_paiement_id', $mode_paiement_id)->get(['commande_id']));
        }
        if (isset($type_client_id)) {
            $montant_total_offert = $montant_total_offert
                ->whereIn('commandes.client_id', Client::where('type_client_id', $type_client_id)
                    ->get(["id"]));
            $montant_total_offert_menu = $montant_total_offert_menu
                ->whereIn('commandes.client_id', Client::where('type_client_id', $type_client_id)
                    ->get(["id"]));
        }

        if ((!empty($dateStart)) && (!empty($dateEnd))) {
            if (!isset($heure_debut) || !isset($heure_fin)) {
                $heure_debut  = '00:00:00';
                $heure_fin    = '23:59:59';
            } else {
                $heure_debut  = $heure_debut . ':00';
                $heure_fin    = $heure_fin . ':59';
            }

            //var_dump($heure_debut,$heure_fin);

            $montant_total_offert->whereBetween('commandes.date', array($dateStart, $dateEnd));
            $montant_total_offert_menu->whereBetween('commandes.date', array($dateStart, $dateEnd));
            //            if((!empty($caisseId)))
            //            {
            //                $caisse = Caisse::find($caisseId);
            //                if(isset($caisse->entite_id) && isset($caisse->entite_id))
            //                {
            //                    $query->where('entite_id',$caisse->entite_id);
            //                }
            //                else
            //                {
            //                    $query->where('entite_id',null);
            //                }
            //            }

        } else {
            if (isset($tranche_horaire_id)) {
                $montant_total_offert = $montant_total_offert->where('commandes.tranche_horaire_id', $tranche_horaire_id);
                $montant_total_offert_menu = $montant_total_offert_menu->where('commandes.tranche_horaire_id', $tranche_horaire_id);
            }
        }

        if ($permission == 'list-commande-departement' || $permission == "list-commande-encour" || $permission == "list-commande") {
            $date   = now();
            $date   = explode(' ', $date);

            $montant_total_offert  = $montant_total_offert->where('commandes.etat_commande', '!=', 8);
            $montant_total_offert_menu  = $montant_total_offert_menu->where('commandes.etat_commande', '!=', 8);
        }

        if (isset($entite_id)) {
            $montant_total_offert = $montant_total_offert->where('commandes.entite_id', $entite_id);
            $montant_total_offert_menu = $montant_total_offert_menu->where('commandes.entite_id', $entite_id);
        }
        if (isset($client_id)) {
            $montant_total_offert = $montant_total_offert->where('commandes.client_id', $client_id);
            $montant_total_offert_menu = $montant_total_offert_menu->where('commandes.client_id', $client_id);
        }
        if (isset($type_commande_id)) {
            $montant_total_offert = $montant_total_offert->where('commandes.type_commande_id', $type_commande_id);
            $montant_total_offert_menu = $montant_total_offert_menu->where('commandes.type_commande_id', $type_commande_id);
        }
        if (isset($table_id)) {
            $montant_total_offert = $montant_total_offert->whereIn('commandes.id', CommandeTable::where('table_id', $table_id)->get(['commande_id']));
            $montant_total_offert_menu = $montant_total_offert_menu->whereIn('commandes.id', CommandeTable::where('table_id', $table_id)->get(['commande_id']));
        }
        if (isset($commande_id)) {
            $montant_total_offert = $montant_total_offert->where('commandes.id', $commande_id);
            $montant_total_offert_menu = $montant_total_offert_menu->where('commandes.id', $commande_id);
        }
        if (isset($etat_paiement)) {
            if ($etat_paiement == 0) {
                $montant_total_offert = $montant_total_offert->where('commandes.montant_total_paye', '=', 0);
                $montant_total_offert_menu = $montant_total_offert_menu->where('commandes.montant_total_paye', '=', 0);
            } else if ($etat_paiement == 1) {
                $montant_total_offert = $montant_total_offert->where('commandes.montant_total_paye', '>', 0);
                $montant_total_offert_menu = $montant_total_offert_menu->where('commandes.restant_payer', '>', 0);
            } else if ($etat_paiement == 2) {
                $montant_total_offert = $montant_total_offert->where('commandes.restant_payer', '=', 0);
                $montant_total_offert_menu = $montant_total_offert_menu->where('commandes.restant_payer', '=', 0);
            }
        }
        if (isset($etat_commande)) {
            if ($etat_commande == 0) {
                $montant_total_offert = $montant_total_offert->where('commandes.etat_commande', '<', 4);
                $montant_total_offert_menu = $montant_total_offert_menu->where('commandes.etat_commande', '<', 4);
            } else {
                $montant_total_offert = $montant_total_offert->where('commandes.etat_commande', $etat_commande);
                $montant_total_offert_menu = $montant_total_offert_menu->where('commandes.etat_commande', $etat_commande);
            }
        }
        if (isset($perte)) {
            $montant_total_offert = $montant_total_offert->where('commandes.perte', 1);
            $montant_total_offert_menu = $montant_total_offert_menu->where('commandes.perte', 1);
        }
        if (isset($client_passage)) {
            $montant_total_offert     = $montant_total_offert->whereNull('commandes.client_id');
            $montant_total_offert_menu     = $montant_total_offert_menu->whereNull('commandes.client_id');
        }


        if (isset($montant_total_offert)) {
            $montant_total_offert = $montant_total_offert->first();
        }
        if (isset($montant_total_offert_menu)) {
            $montant_total_offert_menu = $montant_total_offert_menu->first();
        }

        if (isset($montant_total_offert) && isset($montant_total_offert->montant_total_offre)) {
            if (!isset($menu_id)) {
                $offert += $montant_total_offert->montant_total_offre;
            } else {
                $offert = 0;
            }
        }
        if (isset($montant_total_offert_menu) && isset($montant_total_offert_menu->montant_offert_menu)) {
            if (!isset($famille_id) && !isset($nomenclature_id)) {
                $offert += $montant_total_offert_menu->montant_offert_menu;
            }
        }

        return $offert;
    }

    public static function donneTotalCommandePerte($parametres)
    {
        $dateStart   = isset($parametres["dateStart"])    ? $parametres["dateStart"]  : null;
        $dateEnd     = isset($parametres["dateEnd"])      ? $parametres["dateEnd"]    : null;
        $caisseId    = isset($parametres["caisseId"])     ? $parametres["caisseId"]   : null;
        $permission  = isset($parametres["permission"])   ? $parametres["permission"] : null;

        $heure_debut = isset($parametres["heure_debut"])   ? $parametres["heure_debut"] : null;
        $heure_fin   = isset($parametres["heure_fin"])   ? $parametres["heure_fin"] : null;

        $entite_id          = isset($parametres["entite_id"])   ? $parametres["entite_id"] : null;
        $client_id          = isset($parametres["client_id"])   ? $parametres["client_id"] : null;
        $type_commande_id   = isset($parametres["type_commande_id"])   ? $parametres["type_commande_id"] : null;
        $table_id           = isset($parametres["table_id"])   ? $parametres["table_id"] : null;
        $commande_id        = isset($parametres["commande_id"])   ? $parametres["commande_id"] : null;

        $etat_commande      = isset($parametres["etat_commande"])   ? $parametres["etat_commande"] : null;
        $etat_paiement      = isset($parametres["etat_paiement"])   ? $parametres["etat_paiement"] : null;
        $perteQ             = isset($parametres["perte"])   ? $parametres["perte"] : null;
        $client_passage     = isset($parametres["client_passage"])   ? $parametres["client_passage"] : null;
        $tranche_horaire_id = isset($parametres["tranche_horaire_id"])   ? $parametres["tranche_horaire_id"] : null;
        $type_client_id     = isset($parametres["type_client_id"])   ? $parametres["type_client_id"] : null;
        $mode_paiement_id   = isset($parametres["mode_paiement_id"])   ? $parametres["mode_paiement_id"] : null;
        $famille_id         = isset($parametres["famille_id"])   ? $parametres["famille_id"] : null;
        $menu_id            = isset($parametres["menu_id"])   ? $parametres["menu_id"] : null;
        $nomenclature_id    = isset($parametres["nomenclature_id"])   ? $parametres["nomenclature_id"] : null;



        $perte      = 0;

        if (empty($dateStart) || empty($dateEnd)) {
            $trancheHoraireEnCours  = Outil::donneTrancheHoraire();
            if (isset($trancheHoraireEnCours)) {
                $dateToday          = date('Y-m-d');
                $heureStart         = substr($trancheHoraireEnCours->heure_debut, 11, 5);
                $heureEnd           = substr($trancheHoraireEnCours->heure_fin, 11, 5);
                $dateStart          = $dateToday;
                $dateEnd            = $dateToday;
            }
        }

        if (isset($dateStart) || isset($dateEnd)) {
            if (!isset($heure_debut) || !isset($heure_fin)) {
                if (isset($tranche_horaire_id)) {
                    $tranche_horaire     = Tranchehoraire::find($tranche_horaire_id);

                    $heure_debut = Carbon::parse($tranche_horaire->heure_debut)->format('H:i:s');
                    $heure_fin   = Carbon::parse($tranche_horaire->heure_fin)->format('H:i:s');
                } else {
                    $heure_debut  = '00:00:00';
                    $heure_fin    = '23:59:59';
                }
            } else {
                $heure_debut  = $heure_debut . ':00';
                $heure_fin    = $heure_fin . ':59';
            }
            if ($heure_debut > $heure_fin) {
                if ($dateStart == $dateEnd) {
                    $dateEnd = date('Y-m-d', strtotime($dateEnd . ' + 1 days'));
                }
            }
            $dateStart = $dateStart . ' ' . $heure_debut;
            $dateEnd   = $dateEnd . ' ' . $heure_fin;
        }

        if (empty($caisseId)) {
            $caisseUserConnected = Outil::donneCaisseUser();
            $caisseId = isset($caisseUserConnected) ? $caisseUserConnected : null;
        }

        $query = null;

        $montant_total_perte = Commandeproduit::query()
            ->join('produits', 'produits.id', '=', 'commande_produits.produit_id')
            ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
            ->where('commande_produits.perte', true)
            ->selectRaw('SUM(commande_produits.montant) as montant_total_perte');

        $montant_total_perte_menu = CommandeMenu::query()
            ->join('commandes', 'commandes.id', '=', 'commande_menus.commande_id')
            ->where('commande_menus.perte', true)
            ->selectRaw('sum(commande_menus.montant) as montant_perte_menu');



        if (isset($famille_id)) {
            $montant_total_perte              = $montant_total_perte
                ->join('familles', 'familles.id', 'produits.famille_id')
                ->where('produits.famille_id', $famille_id);
        }

        if (isset($nomenclature_id)) {
            $montant_total_perte              = $montant_total_perte
                ->join('nomenclatures', 'nomenclatures.id', 'produits.nomenclature_id')
                ->where('produits.nomenclature_id', $nomenclature_id);
        }

        if (isset($menu_id)) {
            $montant_total_perte_menu         = $montant_total_perte_menu->where('commande_menus.menu_id', $menu_id);
        }

        if (isset($mode_paiement_id)) {
            $montant_total_perte = $montant_total_perte->whereIn('commandes.id', Paiement::whereNotNull('commande_id')->where('mode_paiement_id', $mode_paiement_id)->get(['commande_id']));
            $montant_total_perte_menu = $montant_total_perte_menu->whereIn('commandes.id', Paiement::whereNotNull('commande_id')->where('mode_paiement_id', $mode_paiement_id)->get(['commande_id']));
        }
        if (isset($type_client_id)) {
            $montant_total_perte = $montant_total_perte
                ->whereIn('commandes.client_id', Client::where('type_client_id', $type_client_id)
                    ->get(["id"]));
            $montant_total_perte_menu = $montant_total_perte_menu
                ->whereIn('commandes.client_id', Client::where('type_client_id', $type_client_id)
                    ->get(["id"]));
        }

        if ((!empty($dateStart)) && (!empty($dateEnd))) {
            if (!isset($heure_debut) || !isset($heure_fin)) {
                $heure_debut  = '00:00:00';
                $heure_fin    = '23:59:59';
            } else {
                $heure_debut  = $heure_debut . ':00';
                $heure_fin    = $heure_fin . ':59';
            }

            //var_dump($heure_debut,$heure_fin);

            $montant_total_perte->whereBetween('commandes.date', array($dateStart, $dateEnd));
            $montant_total_perte_menu->whereBetween('commandes.date', array($dateStart, $dateEnd));
            //            if((!empty($caisseId)))
            //            {
            //                $caisse = Caisse::find($caisseId);
            //                if(isset($caisse->entite_id) && isset($caisse->entite_id))
            //                {
            //                    $query->where('entite_id',$caisse->entite_id);
            //                }
            //                else
            //                {
            //                    $query->where('entite_id',null);
            //                }
            //            }

        } else {
            if (isset($tranche_horaire_id)) {
                $montant_total_perte = $montant_total_perte->where('commandes.tranche_horaire_id', $tranche_horaire_id);
                $montant_total_perte_menu = $montant_total_perte_menu->where('commandes.tranche_horaire_id', $tranche_horaire_id);
            }
        }

        if ($permission == 'list-commande-departement' || $permission == "list-commande-encour" || $permission == "list-commande") {
            $date   = now();
            $date   = explode(' ', $date);

            $montant_total_perte  = $montant_total_perte->where('commandes.etat_commande', '!=', 8);
            $montant_total_perte_menu  = $montant_total_perte_menu->where('commandes.etat_commande', '!=', 8);
        }

        if (isset($entite_id)) {
            $montant_total_perte = $montant_total_perte->where('commandes.entite_id', $entite_id);
            $montant_total_perte_menu = $montant_total_perte_menu->where('commandes.entite_id', $entite_id);
        }
        if (isset($client_id)) {
            $montant_total_perte = $montant_total_perte->where('commandes.client_id', $client_id);
            $montant_total_perte_menu = $montant_total_perte_menu->where('commandes.client_id', $client_id);
        }
        if (isset($type_commande_id)) {
            $montant_total_perte = $montant_total_perte->where('commandes.type_commande_id', $type_commande_id);
            $montant_total_perte_menu = $montant_total_perte_menu->where('commandes.type_commande_id', $type_commande_id);
        }
        if (isset($table_id)) {
            $montant_total_perte = $montant_total_perte->whereIn('commandes.id', CommandeTable::where('table_id', $table_id)->get(['commande_id']));
            $montant_total_perte_menu = $montant_total_perte_menu->whereIn('commandes.id', CommandeTable::where('table_id', $table_id)->get(['commande_id']));
        }
        if (isset($commande_id)) {
            $montant_total_perte = $montant_total_perte->where('commandes.id', $commande_id);
            $montant_total_perte_menu = $montant_total_perte_menu->where('commandes.id', $commande_id);
        }
        if (isset($etat_paiement)) {
            if ($etat_paiement == 0) {
                $montant_total_perte = $montant_total_perte->where('commandes.montant_total_paye', '=', 0);
                $montant_total_perte_menu = $montant_total_perte_menu->where('commandes.montant_total_paye', '=', 0);
            } else if ($etat_paiement == 1) {
                $montant_total_perte = $montant_total_perte->where('commandes.montant_total_paye', '>', 0);
                $montant_total_perte_menu = $montant_total_perte_menu->where('commandes.restant_payer', '>', 0);
            } else if ($etat_paiement == 2) {
                $montant_total_perte        = $montant_total_perte->where('commandes.restant_payer', '=', 0);
                $montant_total_perte_menu   = $montant_total_perte_menu->where('commandes.restant_payer', '=', 0);
            }
        }
        if (isset($etat_commande)) {
            if ($etat_commande == 0) {
                $montant_total_perte        = $montant_total_perte->where('commandes.etat_commande', '<', 4);
                $montant_total_perte_menu   = $montant_total_perte_menu->where('commandes.etat_commande', '<', 4);
            } else {
                $montant_total_perte = $montant_total_perte->where('commandes.etat_commande', $etat_commande);
                $montant_total_perte_menu = $montant_total_perte_menu->where('commandes.etat_commande', $etat_commande);
            }
        }
        if (isset($perteQ)) {
            $montant_total_perte = $montant_total_perte->where('commandes.perte', 1);
            $montant_total_perte_menu = $montant_total_perte_menu->where('commandes.perte', 1);
        }
        if (isset($client_passage)) {
            $montant_total_perte     = $montant_total_perte->whereNull('commandes.client_id');
            $montant_total_perte_menu     = $montant_total_perte_menu->whereNull('commandes.client_id');
        }


        if (isset($montant_total_perte)) {
            $montant_total_perte = $montant_total_perte->first();
        }
        if (isset($montant_total_perte_menu)) {
            $montant_total_perte_menu = $montant_total_perte_menu->first();
        }

        if (!isset($menu_id)) {
            if (isset($montant_total_perte) && isset($montant_total_perte->montant_total_perte)) {
                $perte += $montant_total_perte->montant_total_perte;
            }
        } else {
            $perte = 0;
        }

        if (isset($montant_total_perte_menu) && isset($montant_total_perte_menu->montant_perte_menu)) {

            if (!isset($famille_id) && !isset($nomenclature_id)) {
                $perte += $montant_total_perte_menu->montant_perte_menu;
            }
        }

        return $perte;
    }

    public static function donneTotalCommandeNombre($parametres)
    {
        $retour             = 0;
        $dateStart          = isset($parametres["dateStart"])    ? $parametres["dateStart"]  : null;
        $dateEnd            = isset($parametres["dateEnd"])      ? $parametres["dateEnd"]    : null;
        $caisseId           = isset($parametres["caisseId"])     ? $parametres["caisseId"]   : null;
        $permission         = isset($parametres["permission"])   ? $parametres["permission"] : null;

        $heure_debut        = isset($parametres["heure_debut"])   ? $parametres["heure_debut"] : null;
        $heure_fin          = isset($parametres["heure_fin"])   ? $parametres["heure_fin"] : null;

        $entite_id          = isset($parametres["entite_id"])   ? $parametres["entite_id"] : null;
        $client_id          = isset($parametres["client_id"])   ? $parametres["client_id"] : null;
        $type_commande_id   = isset($parametres["type_commande_id"])   ? $parametres["type_commande_id"] : null;
        $table_id           = isset($parametres["table_id"])   ? $parametres["table_id"] : null;
        $commande_id        = isset($parametres["commande_id"])   ? $parametres["commande_id"] : null;

        $etat_commande      = isset($parametres["etat_commande"])   ? $parametres["etat_commande"] : null;
        $etat_paiement      = isset($parametres["etat_paiement"])   ? $parametres["etat_paiement"] : null;
        $perte              = isset($parametres["perte"])   ? $parametres["perte"] : null;
        $client_passage     = isset($parametres["client_passage"])   ? $parametres["client_passage"] : null;
        $tranche_horaire_id = isset($parametres["tranche_horaire_id"])   ? $parametres["tranche_horaire_id"] : null;
        $type_client_id     = isset($parametres["type_client_id"])   ? $parametres["type_client_id"] : null;
        $mode_paiement_id   = isset($parametres["mode_paiement_id"])   ? $parametres["mode_paiement_id"] : null;

        $montant_offert = 0;

        if (empty($dateStart) || empty($dateEnd)) {
            $trancheHoraireEnCours  = Outil::donneTrancheHoraire();
            if (isset($trancheHoraireEnCours)) {
                $dateToday          = date('Y-m-d');
                $heureStart         = substr($trancheHoraireEnCours->heure_debut, 11, 5);
                $heureEnd           = substr($trancheHoraireEnCours->heure_fin, 11, 5);
                $dateStart          = $dateToday;
                $dateEnd            = $dateToday;
            }
        }

        if (isset($dateStart) || isset($dateEnd)) {
            if (!isset($heure_debut) || !isset($heure_fin)) {
                if (isset($tranche_horaire_id)) {
                    $tranche_horaire     = Tranchehoraire::find($tranche_horaire_id);

                    $heure_debut = Carbon::parse($tranche_horaire->heure_debut)->format('H:i:s');
                    $heure_fin   = Carbon::parse($tranche_horaire->heure_fin)->format('H:i:s');
                } else {
                    $heure_debut  = '00:00:00';
                    $heure_fin    = '23:59:59';
                }
            } else {
                $heure_debut  = $heure_debut . ':00';
                $heure_fin    = $heure_fin . ':59';
            }
            if ($heure_debut > $heure_fin) {
                if ($dateStart == $dateEnd) {
                    $dateEnd = date('Y-m-d', strtotime($dateEnd . ' + 1 days'));
                }
            }
            $dateStart        = $dateStart . ' ' . $heure_debut;
            $dateEnd          = $dateEnd . ' ' . $heure_fin;
        }

        if (empty($caisseId)) {
            $caisseUserConnected = Outil::donneCaisseUser();
            $caisseId = isset($caisseUserConnected) ? $caisseUserConnected : null;
        }

        // var_dump($dateStart . ' ' . $dateEnd);

        //        $montant_offert = Outil::donneTotalCommandeOffert($parametres);
        //        $montant_perte  = Outil::donneTotalCommandePerte($parametres);

        $query = null;
        $query = DB::table('commandes');


        if (isset($mode_paiement_id)) {
            $query = $query->whereIn('commandes.id', Paiement::whereNotNull('commande_id')->where('mode_paiement_id', $mode_paiement_id)->get(['commande_id']));
        }
        if (isset($type_client_id)) {
            $query = $query
                ->whereIn('commandes.client_id', Client::where('type_client_id', $type_client_id)
                    ->get(["id"]));
        }
        if ((!empty($dateStart)) && (!empty($dateEnd))) {


            $query->whereBetween('commandes.date', array($dateStart, $dateEnd));

            if ((!empty($caisseId))) {
                $caisse = Caisse::find($caisseId);
                if (isset($caisse->entite_id) && isset($caisse->entite_id)) {
                    $query->where('commandes.entite_id', $caisse->entite_id);
                } else {
                    $query->where('commandes.entite_id', null);
                }
            }
        } else {
            if (isset($tranche_horaire_id)) {
                $query = $query->where('commandes.tranche_horaire_id', $tranche_horaire_id);
            }
        }

        if ($permission == 'list-commande-departement' || $permission == "list-commande-encour" || $permission == "list-commande") {
            $date   = now();
            $date   = explode(' ', $date);

            $query  = $query->where('commandes.etat_commande', '!=', 8);
        }

        if (isset($entite_id)) {
            $query = $query->where('commandes.entite_id', $entite_id);
        }
        if (isset($client_id)) {
            $query = $query->where('commandes.client_id', $client_id);
        }
        if (isset($type_commande_id)) {
            $query = $query->where('commandes.type_commande_id', $type_commande_id);
        }
        if (isset($table_id)) {
            $query = $query->whereIn('commandes.id', CommandeTable::where('table_id', $table_id)->get(['commande_id']));
        }
        if (isset($commande_id)) {
            $query = $query->where('commandes.id', $commande_id);
        }
        if (isset($etat_paiement)) {
            if ($etat_paiement == 0) {
                $query = $query->where('commandes.montant_total_paye', '=', 0);
            } else if ($etat_paiement == 1) {
                $query = $query->where('commandes.montant_total_paye', '>', 0);
                $query = $query->where('commandes.restant_payer', '>', 0);
            } else if ($etat_paiement == 2) {
                $query = $query->where('restant_payer', '=', 0);
            }
        }
        if (isset($etat_commande)) {
            if ($etat_commande == 0) {
                $query = $query->where('commandes.etat_commande', '<', 4);
            } else {
                $query = $query->where('commandes.etat_commande', $etat_commande);
            }
        }
        if (isset($perte)) {
            $query = $query->where('commandes.perte', 1);
        }
        if (isset($client_passage)) {
            $query     = $query->whereNull('commandes.client_id');
        }

        return  $query->count();
    }

    public static function donneTotalCommandeConsoInterne($parametres)
    {
        $dateStart          = isset($parametres["dateStart"])    ? $parametres["dateStart"]  : null;
        $dateEnd            = isset($parametres["dateEnd"])      ? $parametres["dateEnd"]    : null;
        $caisseId           = isset($parametres["caisseId"])     ? $parametres["caisseId"]   : null;
        $permission         = isset($parametres["permission"])   ? $parametres["permission"] : null;

        $heure_debut        = isset($parametres["heure_debut"])   ? $parametres["heure_debut"] : null;
        $heure_fin          = isset($parametres["heure_fin"])   ? $parametres["heure_fin"] : null;

        $entite_id          = isset($parametres["entite_id"])   ? $parametres["entite_id"] : null;
        $client_id          = isset($parametres["client_id"])   ? $parametres["client_id"] : null;
        $type_commande_id   = isset($parametres["type_commande_id"])   ? $parametres["type_commande_id"] : null;
        $table_id           = isset($parametres["table_id"])   ? $parametres["table_id"] : null;
        $commande_id        = isset($parametres["commande_id"])   ? $parametres["commande_id"] : null;

        $etat_commande      = isset($parametres["etat_commande"])   ? $parametres["etat_commande"] : null;
        $etat_paiement      = isset($parametres["etat_paiement"])   ? $parametres["etat_paiement"] : null;
        $perte              = isset($parametres["perte"])   ? $parametres["perte"] : null;
        $client_passage     = isset($parametres["client_passage"])   ? $parametres["client_passage"] : null;
        $tranche_horaire_id = isset($parametres["tranche_horaire_id"])   ? $parametres["tranche_horaire_id"] : null;
        $type_client_id     = isset($parametres["type_client_id"])   ? $parametres["type_client_id"] : null;
        $mode_paiement_id   = isset($parametres["mode_paiement_id"])   ? $parametres["mode_paiement_id"] : null;
        $famille_id         = isset($parametres["famille_id"])   ? $parametres["famille_id"] : null;
        $menu_id            = isset($parametres["menu_id"])   ? $parametres["menu_id"] : null;
        $nomenclature_id    = isset($parametres["nomenclature_id"])   ? $parametres["nomenclature_id"] : null;


        $total_commande = 0;
        $total_offert = 0;
        $total_perte = 0;
        $total_paiement_commande_conso_partiel = 0;
        $total_commande_conso_partiel          = 0;

        if (empty($dateStart) || empty($dateEnd)) {
            $trancheHoraireEnCours  = Outil::donneTrancheHoraire();
            if (isset($trancheHoraireEnCours)) {
                $dateToday          = date('Y-m-d');
                $heureStart         = substr($trancheHoraireEnCours->heure_debut, 11, 5);
                $heureEnd           = substr($trancheHoraireEnCours->heure_fin, 11, 5);
                $dateStart          = $dateToday;
                $dateEnd            = $dateToday;
            }
        }

        if (isset($dateStart) || isset($dateEnd)) {
            if (!isset($heure_debut) || !isset($heure_fin)) {
                if (isset($tranche_horaire_id)) {
                    $tranche_horaire     = Tranchehoraire::find($tranche_horaire_id);

                    $heure_debut = Carbon::parse($tranche_horaire->heure_debut)->format('H:i:s');
                    $heure_fin   = Carbon::parse($tranche_horaire->heure_fin)->format('H:i:s');
                } else {
                    $heure_debut  = '00:00:00';
                    $heure_fin    = '23:59:59';
                }
            } else {
                $heure_debut  = $heure_debut . ':00';
                $heure_fin    = $heure_fin . ':59';
            }
            if ($heure_debut > $heure_fin) {
                if ($dateStart == $dateEnd) {
                    $dateEnd = date('Y-m-d', strtotime($dateEnd . ' + 1 days'));
                }
            }
            $dateStart        = $dateStart . ' ' . $heure_debut;
            $dateEnd          = $dateEnd . ' ' . $heure_fin;
        }

        if (empty($caisseId)) {
            $caisseUserConnected = Outil::donneCaisseUser();
            $caisseId = isset($caisseUserConnected) ? $caisseUserConnected : null;
        }

        if (!isset($famille_id) && !isset($nomenclature_id)) {
            $total_paiement_commande_conso_partiel = self::donneTotalPaiementCommandeConsoInternePartiel($parametres);
            $total_commande_conso_partiel          = self::donneTotalCommandeConsoPartiel($parametres);
        } else {
            $total_paiement_commande_conso_partiel = 0;
            $total_commande_conso_partiel          = 0;
        }

        $query = null;
        $query = Commandeproduit::query()
            ->join('produits', 'produits.id', '=', 'commande_produits.produit_id')
            ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
            ->whereNull('commande_produits.menu_commande_id')
            ->selectRaw('SUM(commande_produits.montant) as total_periode');

        $query = $query->where('commandes.c_interne', 1);

        $query_menu = CommandeMenu::query()
            ->join('commandes', 'commandes.id', '=', 'commande_menus.commande_id')
            ->selectRaw('sum(commande_menus.montant) as montant_offert_menu');

        $query_menu = $query_menu->where('commandes.c_interne', 1);


        if (isset($famille_id)) {
            $query              = $query
                ->join('familles', 'familles.id', 'produits.famille_id')
                ->where('produits.famille_id', $famille_id);
        }
        if (isset($nomenclature_id)) {
            $query              = $query
                ->join('nomenclatures', 'nomenclatures.id', 'produits.nomenclature_id')
                ->where('produits.nomenclature_id', $nomenclature_id);
        }
        if (isset($menu_id)) {
            $query_menu         = $query_menu->where('commande_menus.menu_id', $menu_id);
        }
        if (isset($mode_paiement_id)) {
            $query      = $query->whereIn('commandes.id', Paiement::whereNotNull('commande_id')->where('mode_paiement_id', $mode_paiement_id)->get(['commande_id']));
            $query_menu = $query_menu->whereIn('commandes.id', Paiement::whereNotNull('commande_id')->where('mode_paiement_id', $mode_paiement_id)->get(['commande_id']));
        }
        if (isset($type_client_id)) {
            $query = $query
                ->whereIn('commandes.client_id', Client::where('type_client_id', $type_client_id)
                    ->get(["id"]));
            $query_menu = $query_menu
                ->whereIn('commandes.client_id', Client::where('type_client_id', $type_client_id)
                    ->get(["id"]));
        }
        if ((!empty($dateStart)) && (!empty($dateEnd))) {

            //var_dump($heure_debut,$heure_fin);

            $query->whereBetween('commandes.date', array($dateStart, $dateEnd));
            $query_menu->whereBetween('commandes.date', array($dateStart, $dateEnd));

            if ((!empty($caisseId))) {
                $caisse = Caisse::find($caisseId);
                if (isset($caisse->entite_id) && isset($caisse->entite_id)) {
                    $query->where('commandes.entite_id', $caisse->entite_id);
                    $query_menu->where('commandes.entite_id', $caisse->entite_id);
                } else {
                    $query->where('commandes.entite_id', null);
                    $query_menu->where('commandes.entite_id', null);
                }
            }
        } else {
            if (isset($tranche_horaire_id)) {
                $query              = $query->where('commandes.tranche_horaire_id', $tranche_horaire_id);
                $query_menu         = $query_menu->where('commandes.tranche_horaire_id', $tranche_horaire_id);
            }
        }

        if ($permission == 'list-commande-departement' || $permission == "list-commande-encour" || $permission == "list-commande") {
            $date   = now();
            $date   = explode(' ', $date);

            $query  = $query->where('commandes.etat_commande', '!=', 8);
            $query_menu  = $query_menu->where('commandes.etat_commande', '!=', 8);
        }

        if (isset($entite_id)) {
            $query = $query->where('commandes.entite_id', $entite_id);
            $query_menu = $query_menu->where('entite_id', $entite_id);
        }
        if (isset($client_id)) {
            $query = $query->where('commandes.client_id', $client_id);
            $query_menu = $query_menu->where('commandes.client_id', $client_id);
        }
        if (isset($type_commande_id)) {
            $query = $query->where('commandes.type_commande_id', $type_commande_id);
            $query_menu = $query_menu->where('commandes.type_commande_id', $type_commande_id);
        }
        if (isset($table_id)) {
            $query = $query->whereIn('commandes.id', CommandeTable::where('table_id', $table_id)->get(['commande_id']));
            $query_menu = $query_menu->whereIn('commandes.id', CommandeTable::where('table_id', $table_id)->get(['commande_id']));
        }
        if (isset($commande_id)) {
            $query = $query->where('commandes.id', $commande_id);
            $query_menu = $query_menu->where('commandes.id', $commande_id);
        }
        if (isset($etat_paiement)) {
            if ($etat_paiement == 0) {
                $query = $query->where('commandes.montant_total_paye', '=', 0);
                $query_menu = $query_menu->where('commandes.montant_total_paye', '=', 0);
            } else if ($etat_paiement == 1) {
                $query = $query->where('commandes.montant_total_paye', '>', 0);
                $query = $query->where('commandes.restant_payer', '>', 0);

                $query_menu = $query_menu->where('commandes.montant_total_paye', '>', 0);
                $query_menu = $query_menu->where('commandes.restant_payer', '>', 0);
            } else if ($etat_paiement == 2) {
                $query = $query->where('restant_payer', '=', 0);
            }
        }
        if (isset($etat_commande)) {
            if ($etat_commande == 0) {
                $query = $query->where('commandes.etat_commande', '<', 4);
                $query_menu = $query_menu->where('commandes.etat_commande', '<', 4);
            } else {
                $query = $query->where('commandes.etat_commande', $etat_commande);
                $query_menu = $query_menu->where('commandes.etat_commande', $etat_commande);
            }
        }
        if (isset($perte)) {
            $query = $query->where('commandes.perte', 1);
            $query_menu = $query_menu->where('commandes.perte', 1);
        }
        if (isset($client_passage)) {
            $query     = $query->whereNull('commandes.client_id');
            $query_menu     = $query_menu->whereNull('commandes.client_id');
        }

        if (!isset($menu_id)) {
            $query =  $query->first()->total_periode;
            $retour = isset($query) ? $query : 0;
        } else {
            $retour = 0;
        }
        $query_menu =  $query_menu->first()->montant_offert_menu;


        if (isset($query_menu)) {
            if (!isset($famille_id) && !isset($nomenclature_id)) {
                $retour += $query_menu;
            }
        }

        return  $retour + ($total_commande_conso_partiel - $total_paiement_commande_conso_partiel);
    }

    public static function donneTotalPaiementCommandeConsoInternePartiel($parametres)
    {
        $retour             = 0;
        $dateStart          = isset($parametres["dateStart"])    ? $parametres["dateStart"]  : null;
        $dateEnd            = isset($parametres["dateEnd"])      ? $parametres["dateEnd"]    : null;
        $caisseId           = isset($parametres["caisseId"])     ? $parametres["caisseId"]   : null;
        $permission         = isset($parametres["permission"])   ? $parametres["permission"] : null;

        $heure_debut        = isset($parametres["heure_debut"])   ? $parametres["heure_debut"] : null;
        $heure_fin          = isset($parametres["heure_fin"])   ? $parametres["heure_fin"] : null;

        $entite_id          = isset($parametres["entite_id"])   ? $parametres["entite_id"] : null;
        $client_id          = isset($parametres["client_id"])   ? $parametres["client_id"] : null;
        $type_commande_id   = isset($parametres["type_commande_id"])   ? $parametres["type_commande_id"] : null;
        $table_id           = isset($parametres["table_id"])   ? $parametres["table_id"] : null;
        $commande_id        = isset($parametres["commande_id"])   ? $parametres["commande_id"] : null;

        $etat_commande      = isset($parametres["etat_commande"])   ? $parametres["etat_commande"] : null;
        $etat_paiement      = isset($parametres["etat_paiement"])   ? $parametres["etat_paiement"] : null;
        $perte              = isset($parametres["perte"])   ? $parametres["perte"] : null;
        $client_passage     = isset($parametres["client_passage"])   ? $parametres["client_passage"] : null;
        $tranche_horaire_id = isset($parametres["tranche_horaire_id"])   ? $parametres["tranche_horaire_id"] : null;
        $type_client_id     = isset($parametres["type_client_id"])   ? $parametres["type_client_id"] : null;
        $mode_paiement_id   = isset($parametres["mode_paiement_id"])   ? $parametres["mode_paiement_id"] : null;
        $famille_id         = isset($parametres["famille_id"])   ? $parametres["famille_id"] : null;
        $menu_id            = isset($parametres["menu_id"])   ? $parametres["menu_id"] : null;

        $total_paiement_commande_conso_partiel = 0;

        if (empty($dateStart) || empty($dateEnd)) {
            $trancheHoraireEnCours  = Outil::donneTrancheHoraire();
            if (isset($trancheHoraireEnCours)) {
                $dateToday          = date('Y-m-d');
                $heureStart         = substr($trancheHoraireEnCours->heure_debut, 11, 5);
                $heureEnd           = substr($trancheHoraireEnCours->heure_fin, 11, 5);
                $dateStart          = $dateToday;
                $dateEnd            = $dateToday;
            }
        }

        if (isset($dateStart) || isset($dateEnd)) {
            if (!isset($heure_debut) || !isset($heure_fin)) {
                if (isset($tranche_horaire_id)) {
                    $tranche_horaire     = Tranchehoraire::find($tranche_horaire_id);

                    $heure_debut = Carbon::parse($tranche_horaire->heure_debut)->format('H:i:s');
                    $heure_fin   = Carbon::parse($tranche_horaire->heure_fin)->format('H:i:s');
                } else {
                    $heure_debut  = '00:00:00';
                    $heure_fin    = '23:59:59';
                }
            } else {
                $heure_debut  = $heure_debut . ':00';
                $heure_fin    = $heure_fin . ':59';
            }
            if ($heure_debut > $heure_fin) {
                if ($dateStart == $dateEnd) {
                    $dateEnd = date('Y-m-d', strtotime($dateEnd . ' + 1 days'));
                }
            }
            $dateStart        = $dateStart . ' ' . $heure_debut;
            $dateEnd          = $dateEnd . ' ' . $heure_fin;
        }

        if (empty($caisseId)) {
            $caisseUserConnected = Outil::donneCaisseUser();
            $caisseId = isset($caisseUserConnected) ? $caisseUserConnected : null;
        }

        // var_dump($dateStart . ' ' . $dateEnd);

        //        $montant_offert = Outil::donneTotalCommandeOffert($parametres);
        //        $montant_perte  = Outil::donneTotalCommandePerte($parametres);

        $query = null;
        $query = Commande::query();


        if (isset($mode_paiement_id)) {
            $query = $query->whereIn('commandes.id', Paiement::whereNotNull('commande_id')->where('mode_paiement_id', $mode_paiement_id)->get(['commande_id']));
        }
        if (isset($type_client_id)) {
            $query = $query
                ->whereIn('commandes.client_id', Client::where('type_client_id', $type_client_id)
                    ->get(["id"]));
        }
        if ((!empty($dateStart)) && (!empty($dateEnd))) {


            $query->whereBetween('commandes.date', array($dateStart, $dateEnd));

            if ((!empty($caisseId))) {
                $caisse = Caisse::find($caisseId);
                if (isset($caisse->entite_id) && isset($caisse->entite_id)) {
                    $query->where('commandes.entite_id', $caisse->entite_id);
                } else {
                    $query->where('commandes.entite_id', null);
                }
            }
        } else {
            if (isset($tranche_horaire_id)) {
                $query = $query->where('commandes.tranche_horaire_id', $tranche_horaire_id);
            }
        }

        if ($permission == 'list-commande-departement' || $permission == "list-commande-encour" || $permission == "list-commande") {
            $date   = now();
            $date   = explode(' ', $date);

            $query  = $query->where('commandes.etat_commande', '!=', 8);
        }

        if (isset($entite_id)) {
            $query = $query->where('commandes.entite_id', $entite_id);
        }
        if (isset($client_id)) {
            $query = $query->where('commandes.client_id', $client_id);
        }
        if (isset($type_commande_id)) {
            $query = $query->where('commandes.type_commande_id', $type_commande_id);
        }
        if (isset($table_id)) {
            $query = $query->whereIn('commandes.id', CommandeTable::where('table_id', $table_id)->get(['commande_id']));
        }
        if (isset($commande_id)) {
            $query = $query->where('commandes.id', $commande_id);
        }
        if (isset($etat_paiement)) {
            if ($etat_paiement == 0) {
                $query = $query->where('commandes.montant_total_paye', '=', 0);
            } else if ($etat_paiement == 1) {
                $query = $query->where('commandes.montant_total_paye', '>', 0);
                $query = $query->where('commandes.restant_payer', '>', 0);
            } else if ($etat_paiement == 2) {
                $query = $query->where('restant_payer', '=', 0);
            }
        }
        if (isset($etat_commande)) {
            if ($etat_commande == 0) {
                $query = $query->where('commandes.etat_commande', '<', 4);
            } else {
                $query = $query->where('commandes.etat_commande', $etat_commande);
            }
        }
        if (isset($perte)) {
            $query = $query->where('commandes.perte', 1);
        }
        if (isset($client_passage)) {
            $query     = $query->whereNull('commandes.client_id');
        }

        return  Outil::soldeConsoInterne($dateStart, $dateEnd, $entite_id, $type_commande_id, $query);
    }

    public static function donneTotalCommandeConsoPartiel($parametres)
    {
        $dateStart          = isset($parametres["dateStart"])    ? $parametres["dateStart"]  : null;
        $dateEnd            = isset($parametres["dateEnd"])      ? $parametres["dateEnd"]    : null;
        $caisseId           = isset($parametres["caisseId"])     ? $parametres["caisseId"]   : null;
        $permission         = isset($parametres["permission"])   ? $parametres["permission"] : null;

        $heure_debut        = isset($parametres["heure_debut"])   ? $parametres["heure_debut"] : null;
        $heure_fin          = isset($parametres["heure_fin"])   ? $parametres["heure_fin"] : null;

        $entite_id          = isset($parametres["entite_id"])   ? $parametres["entite_id"] : null;
        $client_id          = isset($parametres["client_id"])   ? $parametres["client_id"] : null;
        $type_commande_id   = isset($parametres["type_commande_id"])   ? $parametres["type_commande_id"] : null;
        $table_id           = isset($parametres["table_id"])   ? $parametres["table_id"] : null;
        $commande_id        = isset($parametres["commande_id"])   ? $parametres["commande_id"] : null;

        $etat_commande      = isset($parametres["etat_commande"])   ? $parametres["etat_commande"] : null;
        $etat_paiement      = isset($parametres["etat_paiement"])   ? $parametres["etat_paiement"] : null;
        $perte              = isset($parametres["perte"])   ? $parametres["perte"] : null;
        $client_passage     = isset($parametres["client_passage"])   ? $parametres["client_passage"] : null;
        $tranche_horaire_id = isset($parametres["tranche_horaire_id"])   ? $parametres["tranche_horaire_id"] : null;
        $type_client_id     = isset($parametres["type_client_id"])   ? $parametres["type_client_id"] : null;
        $mode_paiement_id   = isset($parametres["mode_paiement_id"])   ? $parametres["mode_paiement_id"] : null;

        $montant_offert = 0;
        $montant_perte = 0;
        $montant_conso = 0;
        $total_paiement_commande_conso_partiel = 0;

        if (empty($dateStart) || empty($dateEnd)) {
            $trancheHoraireEnCours  = Outil::donneTrancheHoraire();
            if (isset($trancheHoraireEnCours)) {
                $dateToday          = date('Y-m-d');
                $heureStart         = substr($trancheHoraireEnCours->heure_debut, 11, 5);
                $heureEnd           = substr($trancheHoraireEnCours->heure_fin, 11, 5);
                $dateStart          = $dateToday;
                $dateEnd            = $dateToday;
            }
        }

        if (isset($dateStart) || isset($dateEnd)) {
            if (!isset($heure_debut) || !isset($heure_fin)) {
                if (isset($tranche_horaire_id)) {
                    $tranche_horaire     = Tranchehoraire::find($tranche_horaire_id);

                    $heure_debut = Carbon::parse($tranche_horaire->heure_debut)->format('H:i:s');
                    $heure_fin   = Carbon::parse($tranche_horaire->heure_fin)->format('H:i:s');
                } else {
                    $heure_debut  = '00:00:00';
                    $heure_fin    = '23:59:59';
                }
            } else {
                $heure_debut  = $heure_debut . ':00';
                $heure_fin    = $heure_fin . ':59';
            }
            if ($heure_debut > $heure_fin) {
                if ($dateStart == $dateEnd) {
                    $dateEnd = date('Y-m-d', strtotime($dateEnd . ' + 1 days'));
                }
            }
            $dateStart        = $dateStart . ' ' . $heure_debut;
            $dateEnd          = $dateEnd . ' ' . $heure_fin;
        }

        if (empty($caisseId)) {
            $caisseUserConnected = Outil::donneCaisseUser();
            $caisseId = isset($caisseUserConnected) ? $caisseUserConnected : null;
        }

        // $retour = Outil::donneTotalCommande($parametres,2);
        $query = null;
        $query = Commandeproduit::query()
            ->join('produits', 'produits.id', '=', 'commande_produits.produit_id')
            ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
            ->whereNull('commande_produits.menu_commande_id')
            ->selectRaw('SUM(commande_produits.montant) as total_periode');


        $query = $query->where('commandes.c_interne', 2);


        $query_menu = CommandeMenu::query()
            ->join('commandes', 'commandes.id', '=', 'commande_menus.commande_id')
            ->selectRaw('sum(commande_menus.montant) as montant_offert_menu');

        $query_menu = $query_menu->where('commandes.c_interne', 2);


        if (isset($mode_paiement_id)) {
            $query      = $query->whereIn('commandes.id', Paiement::whereNotNull('commande_id')->where('mode_paiement_id', $mode_paiement_id)->get(['commande_id']));
            $query_menu = $query_menu->whereIn('commandes.id', Paiement::whereNotNull('commande_id')->where('mode_paiement_id', $mode_paiement_id)->get(['commande_id']));
        }
        if (isset($type_client_id)) {
            $query = $query
                ->whereIn('commandes.client_id', Client::where('type_client_id', $type_client_id)
                    ->get(["id"]));
            $query_menu = $query_menu
                ->whereIn('commandes.client_id', Client::where('type_client_id', $type_client_id)
                    ->get(["id"]));
        }
        if ((!empty($dateStart)) && (!empty($dateEnd))) {

            //var_dump($heure_debut,$heure_fin);

            $query->whereBetween('commandes.date', array($dateStart, $dateEnd));
            $query_menu->whereBetween('commandes.date', array($dateStart, $dateEnd));

            if ((!empty($caisseId))) {
                $caisse = Caisse::find($caisseId);
                if (isset($caisse->entite_id) && isset($caisse->entite_id)) {
                    $query->where('commandes.entite_id', $caisse->entite_id);
                    $query_menu->where('commandes.entite_id', $caisse->entite_id);
                } else {
                    $query->where('commandes.entite_id', null);
                    $query_menu->where('commandes.entite_id', null);
                }
            }
        } else {
            if (isset($tranche_horaire_id)) {
                $query              = $query->where('commandes.tranche_horaire_id', $tranche_horaire_id);
                $query_menu         = $query_menu->where('commandes.tranche_horaire_id', $tranche_horaire_id);
            }
        }

        if ($permission == 'list-commande-departement' || $permission == "list-commande-encour" || $permission == "list-commande") {
            $date   = now();
            $date   = explode(' ', $date);

            $query  = $query->where('commandes.etat_commande', '!=', 8);
            $query_menu  = $query_menu->where('commandes.etat_commande', '!=', 8);
        }

        if (isset($entite_id)) {
            $query = $query->where('commandes.entite_id', $entite_id);
            $query_menu = $query_menu->where('entite_id', $entite_id);
        }
        if (isset($client_id)) {
            $query = $query->where('commandes.client_id', $client_id);
            $query_menu = $query_menu->where('commandes.client_id', $client_id);
        }
        if (isset($type_commande_id)) {
            $query = $query->where('commandes.type_commande_id', $type_commande_id);
            $query_menu = $query_menu->where('commandes.type_commande_id', $type_commande_id);
        }
        if (isset($table_id)) {
            $query = $query->whereIn('commandes.id', CommandeTable::where('table_id', $table_id)->get(['commande_id']));
            $query_menu = $query_menu->whereIn('commandes.id', CommandeTable::where('table_id', $table_id)->get(['commande_id']));
        }
        if (isset($commande_id)) {
            $query = $query->where('commandes.id', $commande_id);
            $query_menu = $query_menu->where('commandes.id', $commande_id);
        }
        if (isset($etat_paiement)) {
            if ($etat_paiement == 0) {
                $query = $query->where('commandes.montant_total_paye', '=', 0);
                $query_menu = $query_menu->where('commandes.montant_total_paye', '=', 0);
            } else if ($etat_paiement == 1) {
                $query = $query->where('commandes.montant_total_paye', '>', 0);
                $query = $query->where('commandes.restant_payer', '>', 0);

                $query_menu = $query_menu->where('commandes.montant_total_paye', '>', 0);
                $query_menu = $query_menu->where('commandes.restant_payer', '>', 0);
            } else if ($etat_paiement == 2) {
                $query = $query->where('restant_payer', '=', 0);
            }
        }
        if (isset($etat_commande)) {
            if ($etat_commande == 0) {
                $query = $query->where('commandes.etat_commande', '<', 4);
                $query_menu = $query_menu->where('commandes.etat_commande', '<', 4);
            } else {
                $query = $query->where('commandes.etat_commande', $etat_commande);
                $query_menu = $query_menu->where('commandes.etat_commande', $etat_commande);
            }
        }
        if (isset($perte)) {
            $query = $query->where('commandes.perte', 1);
            $query_menu = $query_menu->where('commandes.perte', 1);
        }
        if (isset($client_passage)) {
            $query     = $query->whereNull('commandes.client_id');
            $query_menu     = $query_menu->whereNull('commandes.client_id');
        }

        $query =  $query->first()->total_periode;
        $query_menu =  $query_menu->first()->montant_offert_menu;
        $retour = isset($query) ? $query : 0;

        if (isset($query_menu)) {
            $retour += $query_menu;
        }
        return  $retour;
    }

    public static function donneNombreMenuCommande($parametres, $type = 'vente')
    {

        $dateStart          = isset($parametres["dateStart"])    ? $parametres["dateStart"]  : null;
        $dateEnd            = isset($parametres["dateEnd"])      ? $parametres["dateEnd"]    : null;
        $caisseId           = isset($parametres["caisseId"])     ? $parametres["caisseId"]   : null;
        $permission         = isset($parametres["permission"])   ? $parametres["permission"] : null;

        $heure_debut        = isset($parametres["heure_debut"])   ? $parametres["heure_debut"] : null;
        $heure_fin          = isset($parametres["heure_fin"])   ? $parametres["heure_fin"] : null;

        $entite_id          = isset($parametres["entite_id"])   ? $parametres["entite_id"] : null;
        $client_id          = isset($parametres["client_id"])   ? $parametres["client_id"] : null;
        $type_commande_id   = isset($parametres["type_commande_id"])   ? $parametres["type_commande_id"] : null;
        $table_id           = isset($parametres["table_id"])   ? $parametres["table_id"] : null;
        $commande_id        = isset($parametres["commande_id"])   ? $parametres["commande_id"] : null;

        $etat_commande      = isset($parametres["etat_commande"])   ? $parametres["etat_commande"] : null;
        $etat_paiement      = isset($parametres["etat_paiement"])   ? $parametres["etat_paiement"] : null;
        $perte              = isset($parametres["perte"])   ? $parametres["perte"] : null;
        $client_passage     = isset($parametres["client_passage"])   ? $parametres["client_passage"] : null;
        $tranche_horaire_id = isset($parametres["tranche_horaire_id"])   ? $parametres["tranche_horaire_id"] : null;
        $type_client_id     = isset($parametres["type_client_id"])   ? $parametres["type_client_id"] : null;
        $mode_paiement_id   = isset($parametres["mode_paiement_id"])   ? $parametres["mode_paiement_id"] : null;
        $menu_id   = isset($parametres["menu_id"])   ? $parametres["menu_id"] : null;


        if (empty($dateStart) || empty($dateEnd)) {
            $trancheHoraireEnCours  = Outil::donneTrancheHoraire();
            if (isset($trancheHoraireEnCours)) {
                $dateToday          = date('Y-m-d');
                $heureStart         = substr($trancheHoraireEnCours->heure_debut, 11, 5);
                $heureEnd           = substr($trancheHoraireEnCours->heure_fin, 11, 5);
                $dateStart          = $dateToday;
                $dateEnd            = $dateToday;
            }
        }

        if (isset($dateStart) || isset($dateEnd)) {
            if (!isset($heure_debut) || !isset($heure_fin)) {
                if (isset($tranche_horaire_id)) {
                    $tranche_horaire     = Tranchehoraire::find($tranche_horaire_id);

                    $heure_debut = Carbon::parse($tranche_horaire->heure_debut)->format('H:i:s');
                    $heure_fin   = Carbon::parse($tranche_horaire->heure_fin)->format('H:i:s');
                } else {
                    $heure_debut  = '00:00:00';
                    $heure_fin    = '23:59:59';
                }
            } else {
                $heure_debut  = $heure_debut . ':00';
                $heure_fin    = $heure_fin . ':59';
            }
            if ($heure_debut > $heure_fin) {
                if ($dateStart == $dateEnd) {
                    $dateEnd = date('Y-m-d', strtotime($dateEnd . ' + 1 days'));
                }
            }
            $dateStart        = $dateStart . ' ' . $heure_debut;
            $dateEnd          = $dateEnd . ' ' . $heure_fin;
        }

        if (empty($caisseId)) {
            $caisseUserConnected = Outil::donneCaisseUser();
            $caisseId = isset($caisseUserConnected) ? $caisseUserConnected : null;
        }

        $menu_ids = CommandeMenu::query()
            ->join('commandes', 'commandes.id', 'commande_menus.commande_id');


        if ($type !== 'conso') {
            $menu_ids = $menu_ids->whereNotIn('commandes.c_interne', [1, 2]);
        } else {
            $menu_ids = $menu_ids->where('commandes.c_interne', 1);
        }

        if (isset($entite_id)) {
            $menu_ids = $menu_ids->where('commandes.entite_id', $entite_id);
        }

        if (isset($type_commande_id)) {
            $menu_ids = $menu_ids->where('commandes.type_commande_id', $type_commande_id);
        }

        if (isset($dateStart) && isset($dateEnd)) {
            $menu_ids = $menu_ids->whereBetween('commandes.date', [$dateStart, $dateEnd]);
        }

        if ($menu_id) {
            $menu_ids  = $menu_ids->where('commande_menus.menu_id', $menu_id);
        }

        if ($type !== 'perte') {
            $menu_ids = $menu_ids
                ->whereNull('commandes.perte');
        } else {
            $menu_ids = $menu_ids
                ->where('commandes.perte', 1);
        }

        if ($type !== 'offert') {
            $menu_ids = $menu_ids->where('commandes.offre', '=', false);
        } else {
            $menu_ids = $menu_ids->where('commandes.offre', true);
        }

        $menu_ids  = $menu_ids->count();

        return $menu_ids;
    }



    public static function donneTotalPourLesEntites($items)
    {
        $retour = 0;

        if (isset($items)) {
            foreach ($items as $key => $value) {
                $retour += $value["total_cloture_caisse"];
            }
        }

        return $retour;
    }

    public static function donneTrancheHoraire($heure = null)
    {
        if (empty($heure)) {
            $heure = date('H:i');
        }
        $retour = Tranchehoraire::where('heure_debut', '<=', $heure)->where('heure_fin', '>=', $heure)->first();
        if (empty($retour)) {
            $retour = null;
        }
        return $retour;
    }

    public static function donneCaisse($user_id = null)
    {
        $retour = null;
        $entite_id = null;
        if (empty($user_id)) {
            $user_id = Auth::user()->id;
        }

        $user = User::find($user_id);
        if (isset($user)) {
            $entite_id = $user->entite_id;
        }

        $retour = Caisse::where('entite_id', $entite_id)->first();
        return $retour;
    }

    //Test si le mode de paiement contient au moins un mode de paiement cash
    public static function contientModePaiementCash($items)
    {
        $retour = false;
        if (count($items) > 0) {
            foreach ($items as $key => $value) {
                $mode_paiement_id = $value["mode_paiement_id"];
                $test = Modepaiement::where("id", $mode_paiement_id)->where("est_cash", 1)->first();
                if (isset($test)) {
                    $retour = true;
                    return $retour;
                }
            }
        }
        return $retour;
    }

    //Test si le billetage correspond aux encaissements
    public static function testBilletage($billetages, $encaissements)
    {
        $retour = true;
        if (count($billetages) > 0 && count($encaissements) > 0) {
            $sommeBilletages = 0;
            $sommeEncaissements = 0;

            foreach ($billetages as $key => $value) {
                $typebillet_id = $value["typebillet_id"];
                $test = Typebillet::find($typebillet_id);
                if (isset($test)) {
                    $sommeBilletages += $value["nombre"] * $test->nombre;
                }
            }

            foreach ($encaissements as $key2 => $value2) {
                $mode_paiement_id = $value2["mode_paiement_id"];
                $test = Modepaiement::where("id", $mode_paiement_id)->where("est_cash", 1)->first();
                if (isset($test)) {
                    $sommeEncaissements += $value2["montant"];
                }
            }

            if ($sommeBilletages != $sommeEncaissements) {
                $retour = false;
            }
            //dd("sommeBilletages".$sommeBilletages." / sommeEncaissements".$sommeEncaissements);
        }
        return $retour;
    }

    //Donne la première caisse de l'utilisateur
    public static function donneCaisseUser($user_id = null)
    {
        if (empty($user_id)) {
            $user_id = Outil::donneUserId();
        }

        $retour = null;
        $user_caisse = UserCaisse::where('user_id', $user_id)->first();
        if (isset($user_caisse)) {
            $retour = $user_caisse->caisse_id;
        }
        return $retour;
    }

    //Donne les caisses accessibles par l'utilisateur
    public static function reguleCaissePaiement()
    {
        $retour = Paiement::whereNull('caisse_id')->get();
        if (isset($retour) && count($retour) > 0) {
            foreach ($retour as $key => $pai) {
                if (isset($pai->entite_id)) {
                    if (!isset($pai->caisse_id)) {
                        $caisse  = Caisse::where('entite_id', $pai->entite_id)->first();
                        if (isset($caisse)) {
                            $pai->caisse_id = $caisse->id;
                            $pai->save();
                        }
                    }
                }
            }
        }
    }

    //Donne les caisses accessibles par l'utilisateur
    public static function donneAllCaissesUser($user_id = null)
    {
        if (empty($user_id)) {
            $user_id = Outil::donneUserId();
        }

        $retour = UserCaisse::where('user_id', $user_id)->get(['caisse_id']);
        if (count($retour) == 0) {
            $retour = Caisse::where('id', '>', 0)->get(['id']);
        }
        return $retour;
    }

    //Test si l'utilisateur doit voir l'élément ou pas
    public static function canSeeItemUser($type = "entite")
    {
        $retour = true;
        $nbre = 0;
        $user_id = Outil::donneUserId();

        if ($type == "entite") {
            $nbre = UserEntite::where('user_id', $user_id)->count();
        } else if ($type == "caisse") {
            $nbre = UserCaisse::where('user_id', $user_id)->count();
        }
        if ($nbre == 1) {
            $retour = false;
        }

        return $retour;
    }

    //Donne l' item  de l'utilisateur
    public static function giveItemUser($type = "entite", $item_id=null, $etat = null)
    {
        $test = Outil::canSeeItemUser($type);
        $user_id = Outil::donneUserId();

        if ($test == false) {
            //il n'a qu'un élément
            if ($type == "entite") {
                $item_id = UserEntite::where('user_id', $user_id)->first()->entite_id;
            } else if ($type == "caisse") {
                $item_id = UserCaisse::where('user_id', $user_id)->first()->caisse_id;
            }
        }
        if ($type == "departement") {
            $item_id = isset($etat) && $etat == true ? UserDepartement::where('user_id', $user_id)
                ->where('etat', $etat)
                ->where('departement_id', $item_id)
                ->first()
                : UserDepartement::where('user_id', $user_id)->first();
        }

        return $item_id;
    }
    public static function giveDepartementByUser()
    {
        $user_id = Outil::donneUserId();
        $allDepartement = Departement::query();
        $query = null;
        if (isset($user_id)) {
        }
        $query = $allDepartement
            ->join('user_departements', 'user_departements.departement_id', '=', 'departements.id')
            ->join('users', 'users.id', '=', 'user_departements.user_id')
            ->where('users.id', $user_id);

        if (!isset($query)) {
            $query = $allDepartement;
        }

        return $query;
    }



    //Restriction user-entite-caisse
    public static function checkIfIntervalleDate($dateDebut, $dateFin)
    {
        $startdate = $dateDebut;
        $enddate = $dateFin;
        $test = 'Erreur sur le controle de date';
        if (isset($startdate) && isset($enddate)) {
            if ($startdate <= $enddate) {
                $test = null;
            } else {
                $test = "Cet interval de date n'est pas correct";
            }
        }

        return $test;
    }
    public static function checkIfIntervalleHeure($dateDebut, $dateFin)
    {
        $startdate = $dateDebut;
        $enddate = $dateFin;
        $test = 'Erreur sur le controle d\'horaire';
        if (isset($startdate) && isset($enddate)) {
            if ($startdate <= $enddate) {
                $test = null;
            } else {
                $test = "Cet horaire n'est pas correct";
            }
        }

        return $test;
    }
    public static function restrictItemUser($type = "entite", $query=null, $user = null)
    {
        $user_id = Outil::donneUserId();
        if (!isset($user_id)) {
            $user_id = $user;
        }
        if (isset($user_id)) {
            if ($type == 'entite') {
                $test = UserEntite::where('user_id', $user_id)->count();
                if ($test > 0) {
                    $query = $query->whereIn('id', UserEntite::where('user_id', $user_id)->get(['entite_id']));
                }
            }
            if ($type == 'carte' || $type == 'menu') {
                $test = UserEntite::where('user_id', $user_id)->count();
                if ($test > 0) {
                    $query = $query->whereIn('entite_id', UserEntite::where('user_id', $user_id)->get(['entite_id']));
                }
            }
            if ($type == 'be' || $type == 'inventaire' || $type == 'entreestock' || $type == 'sortiestock' || $type == 'stockactuelproduitdepot' || $type == 'stockactuelproduitliquidedepot') {
                $test = UserEntite::where('user_id', $user_id)->count();
                if ($test > 0) {
                    $query = $query->whereIn('depot_id', Depot::whereIn('entite_id', UserEntite::where('user_id', $user_id)->get(['entite_id']))->get(['id']));
                }
            }
            if ($type == 'bt') {
                $test = UserEntite::where('user_id', $user_id)->count();
                if ($test > 0) {
                    $query = $query->where(function ($query) {
                        return $query->whereIn('depot_expediteur_id', Depot::whereIn('entite_id', UserEntite::where('user_id', $user_id)->get(['entite_id']))->get(['id']))
                            ->orWhereIn('depot_destinataire_id', Depot::whereIn('entite_id', UserEntite::where('user_id', $user_id)->get(['entite_id']))->get(['id']));
                    });
                }
            }
            if ($type == 'assemblage' || $type == 'production') {
                $test = UserEntite::where('user_id', $user_id)->count();
                if ($test > 0) {
                    $query = $query->where(function ($query) {
                        return $query->whereIn('depot_id', Depot::whereIn('entite_id', UserEntite::where('user_id', $user_id)->get(['entite_id']))->get(['id']))
                            ->orWhereIn('depot_sortie_id', Depot::whereIn('entite_id', UserEntite::where('user_id', $user_id)->get(['entite_id']))->get(['id']));
                    });
                }
            } else if ($type == 'caisse') {
                $test = UserCaisse::where('user_id', $user_id);
                if ($test->count() > 0) {
                    $query = $query->whereIn('id', UserCaisse::where('user_id', $user_id)->get(['caisse_id']));
                }
            } else if ($type == 'cloturecaisse') {
                $test = UserCaisse::where('user_id', $user_id);
                if ($test->count() > 0) {
                    $query = $query->whereIn('caisse_id', UserCaisse::where('user_id', $user_id)->get(['caisse_id']));
                    if (!Auth::user()->can("all-cloture")) {
                        $query = $query->where('user_id', $user_id);
                    }
                    if (!Auth::user()->can("all-cloture-etat")) {
                        $query = $query->where('etat', 0);
                    }
                }
            } else if ($type == 'depot') {
                $test = UserEntite::where('user_id', $user_id)->get();

                if (isset($test) && count($test) > 0) {
                    $query = $query->whereIn('entite_id', UserEntite::where('user_id', $user_id)->get(['entite_id']));
                }
            } else if ($type == 'bci') {
                $test = UserEntite::where('user_id', $user_id)->get();
                if (isset($test) && count($test) > 0) {
                    $query = $query->whereIn('entite_id', UserEntite::where('user_id', $user_id)->get(['entite_id']));
                }
            } else if ($type == 'proforma') {
                $test = UserEntite::where('user_id', $user_id)->get();
                if (isset($test) && count($test) > 0) {
                    $query = $query->whereIn('entite_id', UserEntite::where('user_id', $user_id)->get(['entite_id']));
                }
            }
        }

        return $query;
    }



    public static function enregistrerDetailAction($itemAction)
    {
        $item = new DetailAction();

        $item->designation = $itemAction->designation;
        $item->date = $itemAction->date;
        $item->observations = $itemAction->observations;
        $item->commentaire = $itemAction->commentaire;
        $item->frequence_qhse_id = $itemAction->frequence_qhse_id;
        $item->famille_action_id = $itemAction->famille_action_id;
        $item->zone_id = $itemAction->zone_id;
        $item->be_id = $itemAction->be_id;
        $item->proforma_id = $itemAction->proforma_id;
        $item->montant = $itemAction->montant;
        $item->action_id = $itemAction->id;

        $item->save();
        $id = $item->id;

        return $id;
    }

    //Enregistrer une appro au cas ou on fait appel à lui
    public static function enregistrerApproCash($from = 'cloturecaisse', $itemGraphQl=null, $request = null)
    {
        $item = new Approcash();
        $id = null;

        $user_id = Outil::donneUserId();

        if ($from == 'cloturecaisse') {
            if (isset($request)) {
                if ($itemGraphQl["total_reel_encaissement_cash"] > 0) {
                    $item->date = date('Y-m-d');
                    $item->montant = $itemGraphQl["total_reel_encaissement_cash"];
                    $item->motif = $request->motif;
                    $item->caisse_source_id = $itemGraphQl["caisse_id"];;
                    $item->caisse_destinataire_id = $request->caisse_destinataire_id;
                    $item->cloture_caisse_id = $itemGraphQl["id"];

                    $item->save();
                    $id = $item->id;

                    if (isset($item->caisse_destinataire_id)) {
                        $itemDestinataire = Caisse::find($item->caisse_destinataire_id);
                        if (isset($itemDestinataire)) {
                            $itemDestinataire->solde = Outil::donneSolde("caisse", $itemDestinataire->id, 1, $item->montant);
                            $itemDestinataire->save();
                        }
                    }

                    if (isset($item->caisse_source_id)) {
                        $itemSource = Caisse::find($item->caisse_source_id);
                        if (isset($itemSource)) {
                            $itemSource->solde = Outil::donneSolde("caisse", $itemSource->id, 2, $item->montant);
                            $itemSource->save();
                        }
                    }
                }
            }
        }

        return $id;
    }

    //Enregistrer une dépense
    public static function enregistrerDepense($from = 'traiteur', $itemArray=null)
    {
        $item = new Depense();
        $user_id = Outil::donneUserId();
        $retour = null;

        if (isset($itemArray)) {
            if ($from == 'traiteur') {
                $item->date = date('Y-m-d H:i:s');
                $item->montant = $itemArray["montant"];
                $item->traiteur_id = $itemArray["traiteur_id"];
                $item->motif = 'Dépense traiteur';
                $item->nombre_jour_rappel = 0;

                $retour = $item->save();
            }
        }

        return $retour;
    }

    //Enregistrer une paiement
    public static function enregistrerPaiement($from = 'commande', $itemArray=null)
    {

        $item = new Paiement();
        $retour = null;

        if (isset($itemArray)) {
            $item->code                             = '';
            $item->date                             = isset($itemArray['date']) ? $itemArray['date'] :  now();
            $item->mode_paiement_id                 = $itemArray['mode_paiement_id'];
            $item->montant                          = $itemArray['montant'];
            $item->caisse_id                        = isset($itemArray['caisse_id']) ? $itemArray['caisse_id'] : null;
            $item->created_at_user_id               = Outil::donneUserId();
            $item->compta                           = isset($itemArray['compta']) ? $itemArray['compta'] : 0;

            if ($from == 'commande') {
                $item->commande_id                  = $itemArray['commande_id'];
            }
            if ($from == 'depense') {
                $item->depense_id                   = $itemArray['depense_id'];
            }
            if ($from == 'facture') {
                $item->facture_id                   = $itemArray['facture_id'];
            }

            $item->save();
            $retour = $item;

            if (isset($item->id)) {
                Outil::getCode($item);
            }
        }


        return $retour;
    }

    public static function xCode($separateur, $itemArray)
    {
        $array = null;
        if (isset($itemArray)) {
            $array = explode($separateur, $itemArray);
        }
        return $array;
    }

    public static function zCode($separateur1, $itemArray, $columns, $separateur2 = null)
    {
        $result = array();
        //  dd($itemArray);
        $array = self::xCode($separateur1, $itemArray);
        if (isset($columns)) {
            if (!is_array($columns)) {
                $columns = array($columns);
            }
        }

        if (isset($array) && count($array) > 0) {
            foreach ($array as $key => $value) {
                $cel = self::xCode($separateur2, $value);
                if (isset($cel) && count($cel) > 0) {
                    $object = array();
                    foreach ($cel as $keyCel => $valueCel) {
                        array_push(
                            $object,
                            [$columns[$keyCel] => $valueCel]
                        );
                    }
                    $object_merge = array();
                    foreach ($object as $keyCelMerge => $valueCelMerge) {
                        $object_merge = array_merge($object_merge, $valueCelMerge);
                    }
                    array_push($result, array($object_merge));
                }
            }
        }

        return $result;
    }
    public static function getInitial($string)
    {
        $string_separe = explode(' ', $string);
        $initiale = '';
        if (isset($string_separe) && count($string_separe) > 0) {
            foreach ($string_separe as $key => $value) {
                $initiale .= substr($value, 0, strlen($value) >= 2 ? 3 : 1);
            }
        }
        return $initiale;
    }
    public static function saveMatriculeUser($item)
    {
        if (isset($item) && isset($item->id)) {
            if (!isset($item->matricule) || $item->matricule == '') {
                if (isset($item->roles)) {
                    $roles = $item->roles;
                    if (count($roles) >= 1) {
                        $getFirstRole   = $roles[0];
                        if (isset($getFirstRole["id"])) {
                            $get_role = Role::findById($getFirstRole["id"]);
                            if (isset($get_role) && isset($get_role->id)) {
                                $laste_user = null;
                                if (isset($get_role->last_user)) {
                                    $laste_user = $get_role->last_user + 1;
                                } else {
                                    $laste_user  = 1;
                                }
                                $get_role->last_user = $laste_user;
                                $get_role->save();
                                $item->matricule = mb_strtoupper(self::getInitial($get_role->name)) . '-' . $get_role->last_user;
                                $item->save();
                            }
                        }
                    }
                }
            }
        }

        return $item;
    }

    public static function enregistrerPosteDepense($item, $itemAray, $compte_sages = null)
    {
        $item->designation          = $itemAray["designation"];
        $item->categorie_depense_id = $itemAray["categorie_depense_id"];
        $item->poste_depense_id     = isset($itemAray["poste_depense_id"]) ? $itemAray["poste_depense_id"] : null;
        $item->save();

        if (isset($compte_sages)) {
            foreach ($compte_sages as $key => $cs) {
                if (isset($cs) && count($cs) > 0) {
                    Outil::enregistrerCompteSage('postedepense', $cs, $item->id, true);
                }
            }
        }
        return $item;
    }
    public static function enregistrerBudgetPosteDepense($from = '', $itemArray=null, $idFrom=null, $fromExcel = false)
    {
        $item = null;
        $foreignKey = 'poste_depense_id';
        $last_item = null;

        if (isset($itemArray)) {
            /* if ($from == 'fournisseur') {
                $foreignKey = 'fournisseur_id';
            }
            if ($from == 'entite') {
                $foreignKey = 'entite_id';
            }

            if ($from == 'client') {
                $foreignKey = 'client_id';
            }

            if ($from == 'postedepense') {
                $foreignKey = 'poste_depense_id';
            }
            if(!isset($idFrom)){
                dd($itemArray);
            }*/

            $allbudget_poste_depense                         = PosteDepenseEntite::where($foreignKey,  $idFrom)->get();

            if (!$fromExcel) {
                if (isset($allcompte_sage_entity) && count($allcompte_sage_entity) > 0) {
                    Outil::Checkdetail($allbudget_poste_depense, $itemArray, PosteDepenseEntite::class, [$foreignKey, 'entite_id']);
                }
            }

            if (isset($itemArray) && count($itemArray) > 0) {
                foreach ($itemArray as $key => $value) {
                    $column_entite          = 'entite_id';
                    if (is_numeric($value['entite_id']) == false) {

                        $entite     = Entite::query()->where('designation', $value['entite_id'])->first();
                        if (isset($entite) && isset($entite->id)) {
                            $value['entite_id'] = $entite->id;
                        } else {
                            $value['entite_id'] = null;
                        }
                    }
                    if (isset($value['montant']) && $value['entite_id']) {
                        $poste_depense_entite                         =  PosteDepenseEntite::query()->where($foreignKey, $idFrom)
                            ->where($column_entite, $value['entite_id'])->first();
                        if (!isset($poste_depense_entite) || !isset($poste_depense_entite->id)) {
                            $poste_depense_entite = new PosteDepenseEntite();
                        }

                        $poste_depense_entite->montant                = $value['compte_sage'];
                        $poste_depense_entite->entite_id              = $value['societe_facturation_id'];
                        $poste_depense_entite->$foreignKey            = $idFrom;
                        $last_item                                    = $poste_depense_entite->save();
                    }
                }
            }
        }
        return $last_item;
    }

    //Enregistrer une compte sage
    public static function enregistrerCompteSage($from = '', $itemArray=null, $idFrom=null, $fromExcel = false)
    {
        $item = null;
        $foreignKey = '';
        $last_item = null;

        if (isset($itemArray)) {
            if ($from == 'fournisseur') {
                $foreignKey = 'fournisseur_id';
            }
            if ($from == 'entite') {
                $foreignKey = 'entite_id';
            }

            if ($from == 'client') {
                $foreignKey = 'client_id';
            }

            if ($from == 'postedepense') {
                $foreignKey = 'poste_depense_id';
            }
            if (!isset($idFrom)) {
                //dd($itemArray);
            }

            $allcompte_sage_entity                         = CompteSage::where($foreignKey,  $idFrom)->get();

            if (!$fromExcel) {
                if (isset($allcompte_sage_entity) && count($allcompte_sage_entity) > 0) {
                    Outil::Checkdetail($allcompte_sage_entity, $itemArray, CompteSage::class, [$foreignKey, 'societe_facturation_id', 'compte_sage']);
                }
            }

            if (isset($itemArray) && count($itemArray) > 0) {
                foreach ($itemArray as $key => $value) {
                    $column_fact = 'societe_facturation_id';
                    if (is_numeric($value['societe_facturation_id']) == false) {

                        $societe_fact     = Societefacturation::query()->where('denominationsociale', $value['societe_facturation_id'])->first();
                        if (isset($societe_fact) && isset($societe_fact->id)) {
                            $value['societe_facturation_id'] = $societe_fact->id;
                        } else {
                            $value['societe_facturation_id'] = null;
                        }
                    }
                    if (isset($value['compte_sage']) && $value['societe_facturation_id']) {
                        $compte     =  CompteSage::query()->where($foreignKey, $idFrom)
                            ->where($column_fact, $value['societe_facturation_id'])->first();
                        if (!isset($compte) || !isset($compte->id)) {
                            $compte = new CompteSage();
                        }
                        $compte->compte_sage            = $value['compte_sage'];
                        $compte->societe_facturation_id = $value['societe_facturation_id'];
                        $compte->$foreignKey            = $idFrom;
                        $last_item                      = $compte->save();
                    }
                }
            }
        }

        return $last_item;
    }
    //Get code bon cadeau
    public static function codeBonCadeau($item)
    {
        $code = '';
        $increment           = self::generateCode($item->id);
        $code               .= 'BONCADEAU-' . $increment;
        $item->codeboncadeau = $code;
        $item->save();
        return $item;
    }

    //Get code Lc
    public static function codeLc($item)
    {
        $code = '';
        $increment           = self::generateCode($item->id);
        $code               .= 'LC-' . $increment;
        $item->codeboncadeau = $code;
        $item->save();
        return $item;
    }

    //Get code Lc
    public static function codeCoupon($item)
    {
        $code = '';
        $increment           = self::generateCode($item->id);
        $code               .= 'CPS-' . $increment;
        $item->codeboncadeau = $code;
        $item->save();
        return $item;
    }

    //Get code Client
    public static function codeClient($type_client, $key = 'designation', $client = null)
    {
        $code = null;

        if (isset($type_client)) {

            if ($key == 'designation') {
                $type_client = TypeClient::query()
                    ->where("designation", $type_client)
                    ->first();
            } else {
                $type_client = TypeClient::find($type_client);
            }
            $genere_code = true;
            $last_client = null;


            if (isset($client)) {
                if (
                    isset($client->code) && $client->code !== ''
                    && str_contains($client->code, $type_client->designation) == true
                ) {
                    $genere_code = false;
                    $code = $client->code;
                }
            }
            if ($genere_code == true) {
                if (isset($type_client) && isset($type_client->id)) {
                    $last_client = $type_client->last_client ? $type_client->last_client : null;
                    $last_client = (int)$last_client;
                    if (!isset($last_client) || $last_client == 0) {
                        $last_client = 1;
                    } else {
                        $last_client = $last_client + 1;
                    }
                    $get_nb_client_by_type_client = $last_client;

                    if (isset($get_nb_client_by_type_client) && is_numeric($get_nb_client_by_type_client)) {
                        $increment = self::generateCode($get_nb_client_by_type_client);
                        $code = $type_client->designation . '-' . $increment;
                    }
                }
            }
        }
        $client->code             = $code;
        $client->save();
        $type_client->last_client = $last_client;
        $type_client->save();
        return $client->code;
    }

    //Get code Operateur
    public static function codeOperateur($type_operateur, $key = 'designation', $operateur = null)
    {
        $code = null;
        if (isset($type_operateur)) {

            if ($key == 'designation') {
                $type_operateur = TypeOperateur::query()
                    ->where("designation", $type_operateur)
                    ->first();
            } else {
                $type_operateur = TypeOperateur::find($type_operateur);
            }
            $genere_code = true;

            if (isset($operateur)) {
                if (
                    isset($operateur->matricule) && $operateur->matricule !== ''
                    && str_contains($operateur->matricule, $type_operateur->designation) == true
                ) {
                    $genere_code = false;
                    $code = $operateur->matricule;
                }
            }
            if ($genere_code == true) {
                if (isset($type_operateur) && isset($type_operateur->id)) {
                    $last_operateur = $type_operateur->last_operateur;
                    $last_operateur = (int)$last_operateur;
                    if (!isset($last_operateur) || $last_operateur == 0) {
                        $last_operateur = 1;
                    } else {
                        $last_operateur = $last_operateur + 1;
                    }
                    $get_nb_operateur_by_type_operateur = $last_operateur;

                    if (isset($get_nb_operateur_by_type_operateur) && is_numeric($get_nb_operateur_by_type_operateur)) {
                        $increment = self::generateCode($get_nb_operateur_by_type_operateur);
                        $code = $type_operateur->designation . '-' . $increment;
                    }
                }
            }
        }
        $operateur->matricule           = $code;
        $operateur->save();
        $type_operateur->last_operateur = $last_operateur;
        $type_operateur->save();
        return $code;
    }

    //Get code Operateur
    public static function codeEmploye($departement, $key = 'designation', $employe = null)
    {
        $code = null;
        if (isset($departement)) {

            if ($key == 'designation') {
                $departement = Departement::query()
                    ->where("designation", $departement)
                    ->first();
            } else {
                $departement = Departement::find($departement);
            }
            $genere_code = true;

            if (isset($employe)) {
                if (
                    isset($employe->matricule) && $employe->matricule !== ''
                    && str_contains($employe->matricule, $departement->designation) == true
                ) {
                    $genere_code = false;
                    $code = $employe->matricule;
                }
            }
            if ($genere_code == true) {
                if (isset($departement) && isset($departement->id)) {
                    $last_employe = $departement->last_employe;
                    $last_employe = (int)$last_employe;
                    if (!isset($last_employe) || $last_employe == 0) {
                        $last_employe = 1;
                    } else {
                        $last_employe = $last_employe + 1;
                    }
                    $get_nb_operateur_by_type_operateur = $last_employe;

                    if (isset($get_nb_operateur_by_type_operateur) && is_numeric($get_nb_operateur_by_type_operateur)) {
                        $increment = self::generateCode($get_nb_operateur_by_type_operateur);
                        $code = substr($departement->designation, 0, 2) . '-' . $increment;
                    }
                }
            }
        }
        $employe->matricule           = $code;
        $employe->save();
        $departement->last_employe    = $last_employe;
        $departement->save();
        return $code;
    }

    public static function codeProduit($produit)
    {
        $code = '';
        $genere_code = true;

        if (isset($produit) && isset($produit->id)) {

            if (isset($produit->famille_id)) {
                $famille = Famille::find($produit->famille_id);
                if (isset($famille)) {
                    $dim_famille = '';
                    $dim_famille = mb_substr($famille->designation, 0, 3);
                    if (isset($famille->parent_famille_id)) {
                        $famille_parent = Famille::find($famille->parent_famille_id);
                        if (isset($famille_parent)) {
                            $dim_famille_parent = '';
                            $dim_famille_parent = mb_substr($famille_parent->designation, 0, 3);
                            $code = mb_strtoupper($dim_famille_parent);
                        }
                    }
                    $code .= mb_strtoupper($dim_famille);
                    if (
                        isset($produit->code) && $produit->code !== ''
                        && str_contains($produit->code, $code)
                    ) {
                        $genere_code = false;
                    }

                    if ($genere_code) {
                        $increment = self::generateCode($produit->id);
                        $code .= $increment;
                    }
                }
            }
        }

        return $code;
    }

    //Rappel date cle
    public static function rappelDateCle()
    {
        $date = now();

        $dates          = explode(' ', $date);
        $now_date      = date("m-d", strtotime($date));
        //dd($now_date);
        //where('date', $dates[0]. ' 00:00:00')

        $date_cles = Dateclemotif::query()->get();
        if (isset($date_cles) && count($date_cles) > 0) {
            foreach ($date_cles as $key => $date_c) {
                if (isset($date_c)) {
                    if (isset($date_c->date)) {
                        $monthCleMotif = date("m-d", strtotime($date_c->date));
                        if ($monthCleMotif == $now_date) {
                            $client   = Client::find($date_c['client_id']);

                            $rappel   = Rappel::query()->where('dateclemotif_id', $date_c['id'])->first();

                            if (!isset($rappel)) {

                                $titre                    = 'Rappel sur le client : ' . $client->raison_sociale;
                                $description              = $date_c->motif;

                                $rappel                   = new Rappel();
                                $rappel->date             = now();
                                $rappel->dateclemotif_id  = $date_c->id;
                                $rappel->description      = $description;
                                $rappel->titre            = $titre;
                                $rappel->etat             = 0;
                                $rappel->type             = 1;

                                $rappel->save();
                            }
                        }
                    }
                }
            }
        }
    }


    //Enregistrer une caisse
    public static function enregistrerCaisse($from = 'societefacturation', $itemFrom=null)
    {
        $item = new Caisse();
        $retour = null;

        if (isset($itemFrom)) {
            if ($from == 'societefacturation') {
                $designation = "caisse " . $itemFrom->denominationsociale;
                $item->designation = $designation;
                $item->societe_facturation_id = $itemFrom->id;
                $retour = $item->save();
            }
        }

        return $retour;
    }

    //Fonction générale pour supprimer un élément
    public static function supprimerElement($model, $id)
    {
        // dd('----------------Delet outil client----');
        try {
            return DB::transaction(function () use ($model, $id) {
                $errors = null;

                if ((int) $id) {
                    $item = app($model)::find($id);
                    if (isset($item)) {
                        if ($model == "App\Avisecheance") {
                            $item->Paiementecheance()->delete();
                        }
                        $item->delete();
                        $item->forceDelete();
                        $data = 1;

                        $queryName = self::getQueryNameOfModel($item->getTable());
                        Outil::publishEvent(['type' => substr($queryName, 0, (strlen($queryName) - 1)), 'add' => true]);
                    } else {
                        $errors = "Cet élément n'existe pas";
                    }
                } else {
                    $errors = "Données manquantes";
                }
                if ($errors) {
                    throw new \Exception($errors);
                } else {
                    $retour = array(
                        'data' => $data,
                    );
                }
                return response()->json($retour);
            });
        } catch (\Exception $e) {
            if (isset($e->errorInfo)) {
                if (strpos($e->errorInfo[0], '23503') !== false || strpos($e->errorInfo[1], '23503') !== false) {
                    //23503 = code erreur liasions clès étrangères (Postgres ca se trouve dans l'index 0 et MySQL ca se trouve dans l'index 1)
                    $errors = 'Impossible de supprimer cet élément car il est lié à des données';
                    return response()->json(array(
                        'errors' => [$errors],
                        'errors_debug' => [$errors],
                        'errors_line' => [$e->getLine()],
                    ));
                } else {
                    return Outil::getResponseError($e);
                }
            } else {
                return Outil::getResponseError($e);
            }
        }
    }


    //Donne la date avec heure, minute, seconde en anglais
    public static function donneDateCompletEn($date, $avecSeconde = true)
    {
        $date_at = $date;
        if ($date_at !== null) {
            $date_at = str_replace("T", " ", $date_at);
            $date_at = date_create($date_at);
            if ($avecSeconde == false) {
                $date_at = date_format($date_at, "Y-m-d H:i");
            } else {
                $date_at = date_format($date_at, "Y-m-d H:i:s");
            }
            return $date_at;
        } else {
            return null;
        }
    }

    //Obtenir les produits de NML
    public static function getProduitFromNml()
    {
        //Récupérer les dépots avec code externe
        $depots = Depot::whereNotNull("code_externe")->where("code_externe", "!=", '')->get();

        //Récupérer les depots deja dans stock liquide pour tester si on a enlevé le code externe pour un depot pour supprimer ses produits
        $depotsToSearch = Depot::whereNotNull("code_externe")->where("code_externe", "!=", '')->get(['id']);
        $nbreLignesToDelete = Stockactuelproduitliquidedepot::whereNotIn('depot_id', $depotsToSearch)->count();
        if ($nbreLignesToDelete > 0) {
            $lignesToDelete = Stockactuelproduitliquidedepot::whereNotIn('depot_id', $depotsToSearch);
            $lignesToDelete->delete();
            $lignesToDelete->forceDelete();
        }

        $params = array(
            'client_id' => 4,
            'depots' => $depots,
        );

        $url = 'https://nml-soft.com/api/getproduitclient';
        $method = 'POST';

        if (function_exists('curl_version')) {
            try {
                $curl = curl_init();
                if ($method == 'POST') {
                    $postfield = '';
                    foreach ($params as $index => $value) {
                        $postfield .= $index . '=' . $value . "&";
                    }
                    $postfield = substr($postfield, 0, -1);
                } else {
                    $postfield = null;
                }
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 45,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => $method,
                    CURLOPT_POSTFIELDS => $postfield,
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/x-www-form-urlencoded",
                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    throw new \Exception("Error :" . $err);
                } else {
                    return $response;
                }
            } catch (Exception $e) {
                throw new \Exception($e);
            }
        } else if (ini_get('allow_url_fopen')) {
            try {
                // Build Http query using params
                $query = http_build_query($params);
                // Create Http context details
                $options = array(
                    'http' => array(
                        'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                            "Content-Length: " . strlen($query) . "\r\n" .
                            "User-Agent:MyAgent/1.0\r\n",
                        'method' => "POST",
                        'content' => $query,
                    ),
                );
                // Create context resource for our request
                $context = stream_context_create($options);
                // Read page rendered as result of your POST request
                $result = file_get_contents(
                    $url, // page url
                    false,
                    $context
                );
                return trim($result);
            } catch (Exception $e) {
                throw new \Exception($e);
            }
        } else {
            throw new \Exception("Vous devez activer curl ou allow_url_fopen pour utiliser ce lien");
        }
    }
    //**************************************************************************************** */
    //Récupérer les montants totaux
    public static function donneMontantTotal($type = "paiement", $itemArray=null)
    {
        $filtres = "";
        $typeAvecS = $type . "s";
        $data = null;
        if (isset($itemArray)) {
            if ($type == "paiement") {
                $date_debut = Outil::donneDateCompletEn($itemArray["date_debut"], false);
                $date_fin = Outil::donneDateCompletEn($itemArray["date_fin"], false);
                $caisse_id = $itemArray["caisse_id"];
                $filtres = 'date_start:"' . $date_debut . '",date_end:"' . $date_fin . '",caisse_id:' . $caisse_id . ',avec_total_periode:true';
            } else if ($type == "reglement") {
                $date_debut = Outil::donneDateCompletEn($itemArray["date_debut"], false);
                $date_fin = Outil::donneDateCompletEn($itemArray["date_fin"], false);
                $caisse_id = $itemArray["caisse_id"];
                $filtres = 'date_start:"' . $date_debut . '",date_end:"' . $date_fin . '",caisse_id:' . $caisse_id . ',avec_total_periode:true';
            } else if ($type == "commande") {
                $date_debut = Outil::donneDateCompletEn($itemArray["date_debut"], false);
                $date_fin = Outil::donneDateCompletEn($itemArray["date_fin"], false);
                $caisse_id = $itemArray["caisse_id"];
                $filtres = 'date_start:"' . $date_debut . '",date_end:"' . $date_fin . '",caisse_id:' . $caisse_id . ',avec_total_periode:true';
            }
        }
        $data = Outil::getOneItemWithGraphQl($typeAvecS, $filtres);
        return $data;
    }

    //Récupérer les détails des éléments des états
    public static function rajouteElements($query, $itemArray, $table = null)
    {
        if ((!empty($itemArray["entite_id"]))) {
            if (isset($table)) {
                if ($table == 'encaissements') {
                    $query = $query->whereIn('caisse_id', Caisse::where('entite_id', $itemArray['entite_id'])->get(['id']));
                }
                if ($table == 'commande_produits') {
                    $query = $query->where('commandes.entite_id', $itemArray["entite_id"]);
                }
            } else {
                $query = $query->where('entite_id', $itemArray["entite_id"]);
            }
        }
        if ((!empty($itemArray["type_commande_id"]))) {
            $query = $query->where('type_commande_id', $itemArray["type_commande_id"]);
        }
        if ((!empty($itemArray["tranche_horaire_id"]))) {
            if (isset($table)) {
                if ($table == 'commande_produits') {
                } else {

                    $query = $query->where('tranche_horaire_id', $itemArray["tranche_horaire_id"]);
                }
            }
        }

        return $query;
    }

    //Récupérer les détails des éléments des états
    public static function getContext($type, $id)
    {
        $context = '';
        if ($type == 'typecommande') {
            $typecommande = Typecommande::find($id);
            if (isset($typecommande)) {
                $context .= $typecommande->designation . '  ';
            }
        }

        if ($type == 'entite') {
            $entite   = Entite::find($id);
            if (isset($entite)) {
                $context .= $entite->designation . '  ';
            }
        }

        if ($type == 'tranchehoraire') {
            $trancheHoraire     = Tranchehoraire::find($id);
            if (isset($trancheHoraire)) {
                $context       .= $trancheHoraire->designation . '  ';
            }
        }

        return $context;
    }

    public static function ca_commande_menu($date_debut, $date_fin, $type = null, $id_type = null, $itemArray = null, $offre = null, $perte = null, $conso_interne = null, $client_id = null, $entite_id = null)
    {

        $commandeProduit_menu  = CommandeMenu::query()
            ->join('produits', 'produits.id', '=', 'commande_menus.menu_id')
            ->join('commandes', 'commandes.id', '=', 'commande_menus.commande_id');

        if (isset($date_debut) && isset($date_fin)) {
            $commandeProduit_menu = $commandeProduit_menu->whereBetween('commandes.date', [$date_debut, $date_fin]);
        }
        if (isset($entite_id) && isset($entite_id)) {
            $commandeProduit_menu = $commandeProduit_menu->where('commandes.entite_id', $entite_id);
        }

        if (isset($client_id)) {
            if (isset($client_id)) {
                $commandeProduit_menu = $commandeProduit_menu->where('commandes.client_id', $client_id);
            }
        }

        if (isset($conso_interne) && $conso_interne == 1) {

            $commandeProduit_menu =   $commandeProduit_menu->where('commandes.c_interne', 1);
        } else {
            if (isset($conso_interne) && $conso_interne == 2) {
                $commandeProduit_menu =   $commandeProduit_menu->where('commandes.c_interne', 2);
            } else {
                $commandeProduit_menu =   $commandeProduit_menu->whereNotIn('commandes.c_interne', [1, 2]);
            }
        }

        if ($offre) {
            $commandeProduit_menu  = $commandeProduit_menu->where('commande_menus.offre', $offre);
        } else if ($offre == false) {
            $commandeProduit_menu  = $commandeProduit_menu->whereNull('commande_menus.offre');
        }

        if (isset($perte) && $perte == true) {
            $commandeProduit_menu  = $commandeProduit_menu->where('commande_menus.perte', $perte);
        } else if ($perte == null) {
            $commandeProduit_menu  = $commandeProduit_menu->whereNull('commande_menus.perte');
        }

        if (isset($type)) {
            $commandeProduit_menu =   $commandeProduit_menu->where('commandes.type_commande_id', $id_type);
        }

        if (isset($itemArray)) {
            $commandeProduit_menu = Outil::rajouteElements($commandeProduit_menu, $itemArray, 'commande_produits');
        }

        $commandeProduit_menu = $commandeProduit_menu->selectRaw('COALESCE(SUM(commande_menus.montant),0) as total, count(produits.id) as nombre, menu_id')
            ->groupBy(['produits.id', 'commande_menus.menu_id'])
            ->get();
        $montant_menu = 0;

        if (isset($commandeProduit_menu) && count($commandeProduit_menu) > 0) {
            foreach ($commandeProduit_menu as $value) {
                $produit = Produit::find($value->menu_id);
                if (isset($produit)) {
                    $montant_menu += $value->total;
                }
            }

            //   dd($retour);
        }

        return $montant_menu;
    }

    public static function ca_commande_menu2point0($parametres, $date_debut, $date_fin, $type = null, $id_type = null, $itemArray = null, $offre = null, $perte = null, $conso_interne = null, $client_id = null, $entite_id = null)
    {

        $dateStart          = isset($parametres["dateStart"])    ? $parametres["dateStart"]  : null;
        $dateEnd            = isset($parametres["dateEnd"])      ? $parametres["dateEnd"]    : null;
        $caisseId           = isset($parametres["caisseId"])     ? $parametres["caisseId"]   : null;
        $permission         = isset($parametres["permission"])   ? $parametres["permission"] : null;

        $heure_debut        = isset($parametres["heure_debut"])   ? $parametres["heure_debut"] : null;
        $heure_fin          = isset($parametres["heure_fin"])   ? $parametres["heure_fin"] : null;

        $entite_id          = isset($parametres["entite_id"])   ? $parametres["entite_id"] : null;
        $client_id          = isset($parametres["client_id"])   ? $parametres["client_id"] : null;
        $type_commande_id   = isset($parametres["type_commande_id"])   ? $parametres["type_commande_id"] : null;
        $table_id           = isset($parametres["table_id"])   ? $parametres["table_id"] : null;
        $commande_id        = isset($parametres["commande_id"])   ? $parametres["commande_id"] : null;

        $etat_commande      = isset($parametres["etat_commande"])   ? $parametres["etat_commande"] : null;
        $etat_paiement      = isset($parametres["etat_paiement"])   ? $parametres["etat_paiement"] : null;
        $perte              = isset($parametres["perte"])   ? $parametres["perte"] : null;
        $client_passage     = isset($parametres["client_passage"])   ? $parametres["client_passage"] : null;
        $tranche_horaire_id = isset($parametres["tranche_horaire_id"])   ? $parametres["tranche_horaire_id"] : null;
        $type_client_id     = isset($parametres["type_client_id"])   ? $parametres["type_client_id"] : null;
        $mode_paiement_id   = isset($parametres["mode_paiement_id"])   ? $parametres["mode_paiement_id"] : null;
        $famille_id         = isset($parametres["famille_id"])   ? $parametres["famille_id"] : null;

        $commandeProduit_menu  = CommandeMenu::query()
            ->join('produits', 'produits.id', '=', 'commande_menus.menu_id')
            ->join('commandes', 'commandes.id', '=', 'commande_menus.commande_id');

        if (empty($dateStart) || empty($dateEnd)) {
            $trancheHoraireEnCours  = Outil::donneTrancheHoraire();
            if (isset($trancheHoraireEnCours)) {
                $dateToday          = date('Y-m-d');
                $heureStart         = substr($trancheHoraireEnCours->heure_debut, 11, 5);
                $heureEnd           = substr($trancheHoraireEnCours->heure_fin, 11, 5);
                $dateStart          = $dateToday;
                $dateEnd            = $dateToday;
            }
        }

        if (isset($dateStart) || isset($dateEnd)) {
            if (!isset($heure_debut) || !isset($heure_fin)) {
                $heure_debut  = '00:00:00';
                $heure_fin    = '23:59:59';
            } else {
                $heure_debut  = $heure_debut . ':00';
                $heure_fin    = $heure_fin . ':59';
            }
            $dateStart        = $dateStart . ' ' . $heure_debut;
            $dateEnd          = $dateEnd . ' ' . $heure_fin;
        }

        if (empty($caisseId)) {
            $caisseUserConnected = Outil::donneCaisseUser();
            $caisseId = isset($caisseUserConnected) ? $caisseUserConnected : null;
        }

        if (isset($dateStart) && isset($dateEnd)) {
            $commandeProduit_menu = $commandeProduit_menu->whereBetween('commandes.date', [$dateStart, $dateEnd]);
        }
        if (isset($entite_id) && isset($entite_id)) {
            $commandeProduit_menu = $commandeProduit_menu->where('commandes.entite_id', $entite_id);
        }

        if (isset($client_id)) {
            if (isset($client_id)) {
                $commandeProduit_menu = $commandeProduit_menu->where('commandes.client_id', $client_id);
            }
        }

        if (isset($conso_interne) && $conso_interne == 1) {

            $commandeProduit_menu =   $commandeProduit_menu->where('commandes.c_interne', 1);
        } else {
            if (isset($conso_interne) && $conso_interne == 2) {
                $commandeProduit_menu =   $commandeProduit_menu->where('commandes.c_interne', 2);
            } else {
                $commandeProduit_menu =   $commandeProduit_menu->whereNotIn('commandes.c_interne', [1, 2]);
            }
        }

        if ($offre) {
            $commandeProduit_menu  = $commandeProduit_menu->where('commande_menus.offre', $offre);
        } else if ($offre == false) {
            $commandeProduit_menu  = $commandeProduit_menu->whereNull('commande_menus.offre');
        }

        if (isset($perte) && $perte == true) {
            $commandeProduit_menu  = $commandeProduit_menu->where('commande_menus.perte', $perte);
        } else if ($perte == null) {
            $commandeProduit_menu  = $commandeProduit_menu->whereNull('commande_menus.perte');
        }

        if (isset($type_commande_id)) {
            $commandeProduit_menu =   $commandeProduit_menu->where('commandes.type_commande_id', $type_commande_id);
        }

        //        if(isset($itemArray)){
        //            $commandeProduit_menu = Outil::rajouteElements($commandeProduit_menu, $itemArray, 'commande_produits');
        //        }

        $commandeProduit_menu = $commandeProduit_menu->selectRaw('COALESCE(SUM(commande_menus.montant),0) as total, count(produits.id) as nombre, menu_id')
            ->groupBy(['produits.id', 'commande_menus.menu_id'])
            ->get();
        $montant_menu = 0;

        if (isset($commandeProduit_menu) && count($commandeProduit_menu) > 0) {
            foreach ($commandeProduit_menu as $value) {
                $produit = Produit::find($value->menu_id);
                if (isset($produit)) {
                    $montant_menu += $value->total;
                }
            }

            //   dd($retour);
        }

        return $montant_menu;
    }
    public static function getTrancheByHoure($houre, $restauration = 1)
    {
        $query = DB::select(DB::raw("select
                  t1.id
                from
                  tranche_horaires t1
                  inner join tranche_horaires t2 on t1.id = t2.id
                where
                  (t1.heure_debut > t2.heure_fin)"));

        $arr = array();
        foreach ($query as  $key => $value) {
            array_push($arr, $value->id);
        }

        //dd($arr);

        $tranche = Tranchehoraire::query();
        if (isset($restauration)) {
            $tranche = $tranche->where('restauration', $restauration);
        }
        $tranche   = $tranche->orderBy('heure_debut', 'DESC');

        $tranche = $tranche->where(function ($tranche) use ($houre, $arr) {
            return $tranche->where('heure_debut', '<=', $houre)->where('heure_fin', '>=', $houre)
                ->orWhere('heure_debut', '<', $houre)->Where('heure_fin', '<', $houre);
        });

        $tranche = $tranche->first();

        if (!isset($tranche)) {
            $tranche = Tranchehoraire::query();
            if (isset($restauration)) {
                $tranche = $tranche->where('restauration', $restauration);
            }
            $tranche   = $tranche
                ->orderBy('heure_debut', 'DESC')
                ->WhereIn('id', $arr)
                ->first();
        }

        // dd($tranche);

        return $tranche;
    }

    //Récupérer les détails des éléments des états
    public static function majTrancheHoraireCommande()
    {
        // $tranche_horaire   = Outil::getTrancheByHoure('00:35:27', 1);

        $retour  = 'Done';
        $commandes = Commande::query()->where('tranche_horaire_id', 8)->get();
        //$commandes = Commande::query()->whereNull('tranche_horaire_id')->get();
        //$commandes = Commande::query()->get();

        if (isset($commandes) && count($commandes) > 0) {
            //dd(count($commandes));
            foreach ($commandes as $key => $item) {

                if (isset($item) && isset($item->date)) {
                    //$retour  .='/'.$item->date;

                    $heures = explode(' ', $item->date);
                    //dd($heures);
                    if (isset($heures) && count($heures) > 1 && isset($heures[1])) {
                        $heures = $heures[1];
                    } else {
                        $heures = explode(' ', $item->date);
                        if (isset($heures) && count($heures) > 1 && isset($heures[1])) {
                            $heures = $heures[1];
                        }
                    }


                    //dd($heures);
                    if (isset($heures)) {
                        $heures = Carbon::parse($heures)->format('H:i:s');

                        $tranche_horaire   = Outil::getTrancheByHoure($heures, 1);
                        //dd($heures);
                        if (isset($tranche_horaire)) {

                            //dd($tranche_horaire->designation, $heures);
                            $item->tranche_horaire_id = $tranche_horaire->id;
                            $item->save();
                        }
                    }
                }
            }
        }
        echo $retour;
    }

    public static function getTotalMountQuery($table, $collum)
    {

        $query = null;

        $query = DB::table('commandes')
            ->selectRaw('sum(montant) as total');

        $retour = isset($query) ? $query : 0;

        return $retour;
    }

    public  static  function  trieParFamille($table)
    {

        $familles = array();
        foreach ($table as $key => $val) {
            $trouve    = false;
            $keySearch = null;

            if (isset($familles) && count($familles) > 0) {

                foreach ($familles as $keyfam => $fam) {
                    if (isset($val['famille'])) {
                        if ($val['famille']['id'] == $fam['famille']['id']) {
                            $trouve     = true;
                            $keySearch  = $keyfam;
                            break;
                        }
                    }
                }
            }

            if ($trouve !== true || !isset($keySearch)) {
                array_push($familles, array(
                    "famille" => $val['famille'],
                    "produits" => array()
                ));

                $keySearch                        = array_keys($familles)[count($familles) - 1];
            }
            array_push($familles[$keySearch]['produits'], array(
                'produit'       => $val['produit'],
                'vendu'         => $val['vendu'],
                'montant'       => $val['montant'],
                'revient'       => $val['revient'],
            ));
        }


        return $familles;
    }



    //Récupérer les détails des éléments des états
    public static function donneElementsEtat($type = "commande", $itemArray=null, $query = null, $args = null)
    {
        $filtres        = "";
        $typeAvecS      = $type . "s";
        $retour         = null;
        $date_debut     = null;
        $date_fin       = null;
        $caisse_id      = null;
        $entite_id      = null;
        $fournisseur_id = null;
        $entites        = null;

        $retourArray = array();

        if (isset($itemArray)) {
            $date_debut = isset($itemArray["date_debut"]) ? $itemArray["date_debut"] : null;
            $date_fin =  isset($itemArray["date_fin"]) ? $itemArray["date_fin"] : null;
            if (
                $type == "reglement" || $type == "commande"         || $type == "produits_commandes_non_offerts"
                ||
                $type == "produits_commandes_offerts"               || $type == "ca_commandes_non_offerts"
                ||
                $type == "ca_commandes_offerts"                     || $type == "ca_commandes_liquide_non_offerts"
                ||
                $type == "ca_commandes_liquide_offerts"             || $type == "ca_commandes_solide_non_offerts"
                ||
                $type == "ca_commandes_solide_offerts"              || $type == "elements_caisse"
                ||
                $type == "ca_commandes_sur_place"                   || $type == 'nombre_de_couverts'
                ||
                $type == 'recap_cloture_caisse'                     || $type == 'ca_commandes_a_livrer'
                ||
                $type == 'nombre_livraison'                         || $type == 'ca_commandes_a_emporter'
                ||
                $type == 'nombre_emporter'                          || $type == 'ca_commandes_liquide_pertes'
                ||
                $type == 'ca_commandes_solide_pertes'               || $type == 'ca_commandes_solide_offerts'
                ||
                $type == 'ca_commandes_conso_interne'
            ) {
                $fournisseur_id                         = isset($itemArray["fournisseur_id"])           ? $itemArray["fournisseur_id"] : null;
                $entites                                = isset($itemArray["entites"])                  ? $itemArray["entites"]        : null;

                $date_debut = Outil::donneDateCompletEn($date_debut, false);
                $date_fin = Outil::donneDateCompletEn($date_fin, false);
                if ((!empty($itemArray["caisse_id"]))) {
                    $caisse_id = $itemArray["caisse_id"];
                    $caisse  = Caisse::find($caisse_id);
                    if (isset($caisse) && isset($caisse->entite_id)) {
                        $entite_id  = $caisse->entite_id;
                    }

                    //var_dump($entite_id);
                }

                if (isset($query)) {
                    $retour = $query;
                }

                if (!empty($itemArray["cloture_caisse_id"])) {
                    $cloture_caisse_id = $itemArray["cloture_caisse_id"];
                    $cloturecaisse = Cloturecaisse::find($cloture_caisse_id);
                    if (isset($cloturecaisse)) {
                        $date_debut = $cloturecaisse->date_debut;
                        $date_fin = $cloturecaisse->date_fin;
                        $caisse_id = $cloturecaisse->caisse_id;
                        $caisse  = Caisse::find($caisse_id);
                        if (isset($caisse) && isset($caisse->entite_id)) {
                            $entite_id  = $caisse->entite_id;
                        }
                    }
                }
                if (!empty($itemArray["tranche_horaire_id"])) {
                    if (empty($itemArray["cloture_caisse_id"])) {
                        $tranche_horaire = Tranchehoraire::find($itemArray["tranche_horaire_id"]);

                        $heure_debut_tranche = Carbon::parse($tranche_horaire->heure_debut)->format('H:i:s');
                        $heure_fin_tranche = Carbon::parse($tranche_horaire->heure_fin)->format('H:i:s');

                        $paras_heure_debut = explode(' ', $date_debut);
                        $paras_heure_fin   = explode(' ', $date_fin);

                        if (isset($paras_heure_debut) && isset($paras_heure_debut[0]) && isset($paras_heure_fin) && isset($paras_heure_fin[0])) {
                            $date_debut = $paras_heure_debut[0] . ' ' . $heure_debut_tranche;
                            $date_fin   = $paras_heure_fin[0] . ' ' . $heure_fin_tranche;
                        }
                    } else {
                    }
                } else {
                }
            }

            if ($type == "reglement") {
                if (isset($date_debut) && isset($date_fin)) {
                    if (isset($query)) {
                        $retour = $retour->whereBetween('date', [$date_debut, $date_fin]);
                    }
                }
                if ((!empty($caisse_id))) {
                    if (isset($query)) {
                        $retour = $retour->where('caisse_id', $caisse_id);
                    }
                }
            }

            if ($type == "commande") {
                if (isset($date_debut) && isset($date_fin)) {
                    if (isset($query)) {
                        $retour = $retour->whereBetween('commandes.date', [$date_debut, $date_fin]);
                    }
                }
                if ((!empty($caisse_id))) {
                    if (isset($query)) {
                        $caisse = Caisse::find($caisse_id);
                        if (isset($caisse->entite_id) && isset($caisse->entite_id)) {
                            $retour = $retour->where('entite_id', $caisse->entite_id);
                        } else {
                            $retour = $retour->where('entite_id', null);
                        }
                    }
                }
            }

            if ($type == "produits_commandes_non_offerts") {
                //Selectionner tous les produits des commandes
                $mode_paiement_id   = Modepaiement::conso_interne()->id;
                //$filterStr          = "commande_produits.offre = false and commande_produits.perte is null and commande_produits.menu_commande_id is null and (commandes.date between '$date_debut' and '$date_fin') and commandes.id not in (select commande_id from paiement_credits where mode_paiement_id  = '$mode_paiement_id' )  ";
                $filterStr          = "commande_produits.offre = false and commande_produits.perte is null and commande_produits.menu_commande_id is null and (commandes.date between '$date_debut' and '$date_fin') and commandes.c_interne != 1 ";
                $subQuery           = "(select COALESCE(SUM(commande_produits.montant),0) as total from commande_produits join produits on commande_produits.produit_id = produits.id join commandes on commandes.id = commande_produits.commande_id where $filterStr and produits.famille_id in (WITH RECURSIVE c AS (
                                    SELECT familles.id::bigint AS id
                                    UNION ALL
                                    SELECT familles.id
                                    FROM familles JOIN c ON c.id = familles.parent_famille_id
                                    )
                                    SELECT id FROM c))";

                $query     = Famille::whereNull('parent_famille_id')
                    ->selectRaw("familles.*, $subQuery as total")
                    ->whereRaw("$subQuery > 0")->orderBy('total', 'desc')->get();
                $query     = $query->sortBy('total', SORT_REGULAR, 'asc');
                //dd($query);
                foreach ($query as  $key => $famille) {
                    $produits                = Commandeproduit::join('produits', 'produits.id', '=', 'commande_produits.produit_id')
                        ->join('commandes', 'commandes.id', 'commande_produits.commande_id')
                        ->whereRaw("$filterStr")
                        ->whereRaw("produits.famille_id in (WITH RECURSIVE c AS (
                                                                SELECT {$famille->id}::bigint AS id
                                                                UNION ALL
                                                                SELECT familles.id
                                                                FROM familles JOIN c ON c.id = familles.parent_famille_id
                                                                )
                                                                SELECT id FROM c)")
                        ->selectRaw('commande_produits.*')->get();

                    $query[$key]['produits'] = collect($produits)->groupBy('produit_id')->sortBy('');
                    //dd($query[$key]['produits']);
                }
                //Fin code de jacques
                //Fin code de jacques

                $total_commande_produits = $query->sum('total');

                $query                   = DB::table('commande_produits')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    ->where('commande_produits.offre', '=', false)
                    ->whereNull('commande_produits.perte')
                    ->whereNull('commande_produits.menu_commande_id')
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);

                $query                   = self::sansConsoInterne($query);

                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }

                $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');
                $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total, count(commande_produits.produit_id) as nombre, produit_id')
                    ->groupBy('commande_produits.produit_id');
                $query = $query->get();

                //dd($query->sum('total'), $itemArray);

                //Selectionner tous les menus des commandes

                $commandeProduit_menu  = CommandeMenu::query()
                    ->join('produits', 'produits.id', '=', 'commande_menus.menu_id')
                    ->join('commandes', 'commandes.id', '=', 'commande_menus.commande_id')
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);

                $commandeProduit_menu = self::sansConsoInterne($commandeProduit_menu);


                $commandeProduit_menu = Outil::rajouteElements($commandeProduit_menu, $itemArray, 'commande_produits');

                $commandeProduit_menu = $commandeProduit_menu->selectRaw('COALESCE(SUM(commande_menus.montant),0) as total, count(produits.id) as nombre, menu_id')
                    ->groupBy(['produits.id', 'commande_menus.menu_id'])
                    ->get();

                $retour = array();
                foreach ($query as $value) {
                    $produit = Produit::find($value->produit_id);
                    if (isset($produit)) {
                        $famille = Famille::find($produit->famille_id);
                        $parametres = array(
                            'total'                         => $produit->total,
                            'prix_de_revient_unitaire'      => $produit->prix_de_revient_unitaire,
                            'prix_achat_ttc'                => $produit->prix_achat_ttc,
                            'prix_achat_unitaire'           => $produit->prix_achat_unitaire,
                        );
                        $revient = Outil::donnePrixRevient($parametres);

                        $one = array(
                            'produit'       => $produit,
                            'famille'       => $famille,
                            'vendu'         => $value->nombre,
                            'montant'       => $value->total,
                            'revient'       => $revient,
                        );
                        array_push($retour, $one);
                    }
                }
                //Charger les menus
                if (isset($commandeProduit_menu) && count($commandeProduit_menu) > 0) {
                    foreach ($commandeProduit_menu as $value) {
                        $produit = Produit::find($value->menu_id);
                        if (isset($produit)) {
                            $famille = isset($produit->famille_id) ? Famille::find($produit->famille_id) : null;
                            $parametres = array(
                                'total'                         => $produit->total,
                                'prix_de_revient_unitaire'      => $produit->prix_de_revient_unitaire,
                                'prix_achat_ttc'                => $produit->prix_achat_ttc,
                                'prix_achat_unitaire'           => $produit->prix_achat_unitaire,
                            );
                            $revient = Outil::donnePrixRevient($parametres);

                            $one = array(
                                'produit'       => $produit,
                                'famille'       => $famille,
                                'vendu'         => $value->nombre,
                                'montant'       => $value->total,
                                'revient'       => $revient,
                            );
                            array_push($retour, $one);
                        }
                    }
                }
                //trier par famille
                //$retour = Outil::trieParFamille($retour);
                //$retour = collect($retour);
                //$retour = $retour->sortBy('montant', SORT_REGULAR, 'DESC');
            }
            if ($type == "produits_commandes_offerts") {
                $query = DB::table('commande_produits')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    ->where('commande_produits.offre', true)
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);
                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }
                $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');
                $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total, count(commande_produits.produit_id) as nombre, produit_id')
                    ->groupBy('commande_produits.produit_id');
                $query = $query->get();
                //dd($query);

                $retour = array();
                foreach ($query as $value) {
                    $produit = Produit::find($value->produit_id);
                    if (isset($produit)) {
                        $famille = Famille::find($produit->famille_id);
                        $parametres = array(
                            'total'                         => $produit->total,
                            'prix_de_revient_unitaire'      => $produit->prix_de_revient_unitaire,
                            'prix_achat_ttc'                => $produit->prix_achat_ttc,
                            'prix_achat_unitaire'           => $produit->prix_achat_unitaire,
                        );
                        $revient = Outil::donnePrixRevient($parametres);

                        $one = array(
                            'produit'       => $produit,
                            'famille'       => $famille,
                            'vendu'         => $value->nombre,
                            'montant'       => $value->total,
                            'revient'       => $revient,
                        );
                        array_push($retour, $one);
                    }
                }
            }
            if ($type == "produits_commandes_pertes") {
                $query = DB::table('commande_produits')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    ->where('commande_produits.perte', 1)
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);
                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }
                $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');
                $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total, count(commande_produits.produit_id) as nombre, produit_id')
                    ->groupBy('commande_produits.produit_id');
                $query = $query->get();
                //dd($query);

                $retour = array();
                foreach ($query as $value) {
                    $produit = Produit::find($value->produit_id);
                    if (isset($produit)) {
                        $famille = Famille::find($produit->famille_id);
                        $parametres = array(
                            'total'                         => $produit->total,
                            'prix_de_revient_unitaire'      => $produit->prix_de_revient_unitaire,
                            'prix_achat_ttc'                => $produit->prix_achat_ttc,
                            'prix_achat_unitaire'           => $produit->prix_achat_unitaire,
                        );
                        $revient = Outil::donnePrixRevient($parametres);

                        $one = array(
                            'produit'       => $produit,
                            'famille'       => $famille,
                            'vendu'         => $value->nombre,
                            'montant'       => $value->total,
                            'revient'       => $revient,
                        );
                        array_push($retour, $one);
                    }
                }
            }
            if ($type == "clients_commandes_offerts") {
                $query = DB::table('commande_produits')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    ->whereNotNull('commandes.client_id')
                    ->where('commande_produits.offre', true)
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);
                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }
                $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');
                $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total, count(commande_produits.produit_id) as nombre, client_id')
                    ->groupBy('commandes.client_id');
                $query = $query->get();

                $retour = array();
                foreach ($query as $value) {
                    $client = Client::find($value->client_id);
                    if (isset($client)) {
                        $one = array(
                            'client'        => $client,
                            'revient'       => 0, //A coder car c'est le prix de tous les produits
                            'qte'           => $value->nombre,
                            'montant'       => $value->total,
                        );
                        array_push($retour, $one);
                    }
                }
            }
            if ($type == "ca_commandes_non_offerts") {

                //   dd($date_debut . '====' . $date_fin);
                $query = DB::table('commande_produits')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    ->where('commande_produits.offre', false)
                    ->whereNull('commande_produits.perte')
                    ->whereNull('commande_produits.menu_commande_id')
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);

                //On enleve les commandes conso interne
                $query = self::sansConsoInterne($query);

                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }
                $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');
                $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total');
                $retour = $query->first()->total;



                $commandeProduit_menu =  self::ca_commande_menu($date_debut, $date_fin, null, null, $itemArray, false, null);
                // $commandeProduit_menu =  self::ca_commande_menu($date_debut, $date_fin,null,null,$itemArray);

                if (isset($commandeProduit_menu)) {

                    //$total_menu = $commandeProduit_menu->total;
                    $total_menu = $commandeProduit_menu;
                    if (isset($total_menu)) {
                        $retour += $total_menu;
                    }
                }

                //On ajoute le montant total des paiements commandes conso interne partiel
                $montant_commande_conso_partiel = self::soldeConsoInterne($date_debut, $date_fin, $entite_id);
                if (isset($montant_commande_conso_partiel)) {
                    $retour = $retour + $montant_commande_conso_partiel;
                }
            }
            if ($type == "ca_commandes_offerts") {
                $query = DB::table('commande_produits')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    ->where('commande_produits.offre', true)
                    ->whereNull('commande_produits.menu_commande_id')
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);
                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }
                $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');
                $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total');
                $retour = $query->first()->total;
                //($date_debut, $date_fin, $type = null, $id_type = null, $itemArray =null, $offre = null)
                $commandeProduit_menu =  self::ca_commande_menu($date_debut, $date_fin, null, null, null, 1, null);

                if (isset($commandeProduit_menu)) {

                    //$total_menu = $commandeProduit_menu->total;
                    $total_menu = $commandeProduit_menu;
                    if (isset($total_menu)) {
                        $retour += $total_menu;
                    }
                }
            }
            if ($type == "ca_commandes_pertes") {
                $query = DB::table('commande_produits')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    ->where('commande_produits.perte', 1)
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);
                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }
                $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');
                $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total');
                $retour = $query->first()->total;
                //($date_debut, $date_fin, $type = null, $id_type = null, $itemArray =null, $offre = null, $perte = null)
                $commandeProduit_menu =  self::ca_commande_menu($date_debut, $date_fin, null, null, null, null, true);


                if (isset($commandeProduit_menu)) {

                    $total_menu = $commandeProduit_menu;
                    if (isset($total_menu)) {
                        $retour += $total_menu;
                    }
                }


                //dd($retour);
            }
            if ($type == "ca_commandes_liquide_pertes") {
                $query = DB::table('commande_produits')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    ->join('produits', 'produits.id', '=', 'commande_produits.produit_id')
                    ->join('nomenclatures', 'nomenclatures.id', '=', 'produits.nomenclature_id')
                    ->where('nomenclatures.designation', Outil::getOperateurLikeDB(), '%LIQUIDE%')
                    ->where('commande_produits.perte', 1)
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);
                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }
                $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');
                $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total');
                $retour = $query->first()->total;
                //dd($retour);
            }
            if ($type == "ca_commandes_liquide_non_offerts") {
                $query = DB::table('commande_produits')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    ->join('produits', 'produits.id', '=', 'commande_produits.produit_id')
                    ->join('nomenclatures', 'nomenclatures.id', '=', 'produits.nomenclature_id')
                    ->where('nomenclatures.designation', Outil::getOperateurLikeDB(), '%LIQUIDE%')
                    ->where('commande_produits.offre', false)
                    ->whereNull('commande_produits.perte')
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);
                $query = self::sansConsoInterne($query);
                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }
                $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');
                $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total');
                $retour = $query->first()->total;
            }
            if ($type == "ca_commandes_liquide_offerts") {
                $query = DB::table('commande_produits')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    ->join('produits', 'produits.id', '=', 'commande_produits.produit_id')
                    ->join('nomenclatures', 'nomenclatures.id', '=', 'produits.nomenclature_id')
                    ->where('nomenclatures.designation', Outil::getOperateurLikeDB(), '%LIQUIDE%')
                    ->where('commande_produits.offre', true)
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);
                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }
                $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');
                $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total');
                $retour = $query->first()->total;
                //dd($retour);
            }
            if ($type == "ca_commandes_solide_non_offerts") {
                $query = DB::table('commande_produits')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    ->join('produits', 'produits.id', '=', 'commande_produits.produit_id')
                    ->join('nomenclatures', 'nomenclatures.id', '=', 'produits.nomenclature_id')
                    ->where('nomenclatures.designation', Outil::getOperateurLikeDB(), '%SOLIDE%')
                    ->where('commande_produits.offre', false)
                    ->whereNull('commande_produits.perte')
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);
                $query = self::sansConsoInterne($query);
                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }
                $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');
                $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total');
                $retour = $query->first()->total;
                //dd($retour);
            }
            if ($type == "ca_commandes_solide_pertes") {
                $query = DB::table('commande_produits')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    ->join('produits', 'produits.id', '=', 'commande_produits.produit_id')
                    ->join('nomenclatures', 'nomenclatures.id', '=', 'produits.nomenclature_id')
                    ->where('nomenclatures.designation', Outil::getOperateurLikeDB(), '%SOLIDE%')
                    ->where('commande_produits.perte', 1)
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);
                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }
                $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');
                $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total');
                $retour = $query->first()->total;
                //dd($retour);
            }
            if ($type == "ca_commandes_solide_offerts") {
                $query = DB::table('commande_produits')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    ->join('produits', 'produits.id', '=', 'commande_produits.produit_id')
                    ->join('nomenclatures', 'nomenclatures.id', '=', 'produits.nomenclature_id')
                    ->where('nomenclatures.designation', Outil::getOperateurLikeDB(), '%SOLIDE%')
                    ->where('commande_produits.offre', true)
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);
                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }
                $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');
                $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total');
                $retour = $query->first()->total;
                //dd($retour);
            }
            if ($type == "ca_commandes_a_livrer") {
                $type_commande_id = null;
                $typecommande = Typecommande::where('designation', 'à livrer')->first();
                if (isset($typecommande)) {
                    $type_commande_id = $typecommande->id;
                }

                $query = DB::table('commande_produits')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    ->where('commandes.type_commande_id', $type_commande_id)
                    ->whereNull('commande_produits.menu_commande_id')
                    ->whereNull('commande_produits.perte')
                    ->where('commande_produits.offre', false)
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);
                $query = self::sansConsoInterne($query);
                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }
                $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');
                $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total');
                $retour = $query->first()->total;

                //($date_debut, $date_fin, $type = null, $id_type = null, $itemArray =null, $offre = null, $perte = null)

                $commandeProduit_menu =  self::ca_commande_menu($date_debut, $date_fin, 'alivrer', $type_commande_id);

                if (isset($commandeProduit_menu)) {

                    //$total_menu = $commandeProduit_menu->total;
                    $total_menu = $commandeProduit_menu;
                    if (isset($total_menu)) {
                        $retour += $total_menu;
                    }
                }
                //On ajoute le montant total des commandes conso interne partiel
                $montant_commande_conso_partiel = self::soldeConsoInterne($date_debut, $date_fin, $entite_id, $type_commande_id);
                if (isset($montant_commande_conso_partiel)) {
                    $retour = $retour + $montant_commande_conso_partiel;
                }
                //dd($retour);
            }
            if ($type == "ca_commandes_a_emporter") {
                $type_commande_id = null;
                $typecommande = Typecommande::where('designation', 'à emporter')->first();
                if (isset($typecommande)) {
                    $type_commande_id = $typecommande->id;
                }

                $query = DB::table('commande_produits')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    ->where('commandes.type_commande_id', $type_commande_id)
                    ->whereNull('commande_produits.menu_commande_id')
                    ->whereNull('commande_produits.perte')
                    ->where('commande_produits.offre', false)
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);

                $query = self::sansConsoInterne($query);

                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }
                $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');
                $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total');
                $retour = $query->first()->total;

                $commandeProduit_menu =  self::ca_commande_menu($date_debut, $date_fin, 'emporter', $type_commande_id);

                if (isset($commandeProduit_menu)) {

                    //$total_menu = $commandeProduit_menu->total;
                    $total_menu = $commandeProduit_menu;
                    if (isset($total_menu)) {
                        $retour += $total_menu;
                    }
                }

                $montant_commande_conso_partiel = self::soldeConsoInterne($date_debut, $date_fin, $entite_id, $type_commande_id);
                if (isset($montant_commande_conso_partiel)) {
                    $retour = $retour + $montant_commande_conso_partiel;
                }
                //dd($retour);
            }
            if ($type == "ca_commandes_sur_place") {
                //var_dump($date_debut . '====' . $date_fin);
                $type_commande_id = null;
                $typecommande = Typecommande::where('designation', 'sur place')->first();
                if (isset($typecommande)) {
                    $type_commande_id = $typecommande->id;
                }

                $query = DB::table('commande_produits')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    ->where('commandes.type_commande_id', $type_commande_id)
                    ->whereNull('commande_produits.menu_commande_id')
                    ->whereNull('commande_produits.perte')
                    ->where('commande_produits.offre', false)
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);

                $query = self::sansConsoInterne($query);

                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }
                $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');
                $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total');
                $retour = $query->first()->total;

                $commandeProduit_menu =  self::ca_commande_menu($date_debut, $date_fin, 'surplace', $type_commande_id);

                if (isset($commandeProduit_menu)) {

                    $total_menu = $commandeProduit_menu;
                    if (isset($total_menu)) {
                        $retour += $total_menu;
                    }
                }

                $montant_commande_conso_partiel = self::soldeConsoInterne($date_debut, $date_fin, $entite_id, $type_commande_id);
                if (isset($montant_commande_conso_partiel)) {
                    $retour = $retour + $montant_commande_conso_partiel;
                }
            }
            if ($type == "ca_commandes_conso_interne") {
                //                $text_mode_paiement = 'Conso Interne';
                //                $modepaiement_id = null;
                //
                //                $modePaiment = Modepaiement::where('designation', Outil::getOperateurLikeDB(), '%' . $text_mode_paiement . '%')->first();
                //                if($modePaiment){
                //                    $modepaiement_id = $modePaiment->id;
                //                }
                //if(isset($modepaiement_id)){
                //var_dump($date_debut, $date_fin);

                // var_dump($date_debut, $date_fin);

                $query = DB::table('commande_produits')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    //                        ->whereIn('commandes.id', PaiementCredit::where('mode_paiement_id', $modepaiement_id)->get(['commande_id']))
                    ->where('commande_produits.offre', false)
                    ->whereNull('commande_produits.perte')
                    ->whereNull('commande_produits.menu_commande_id')
                    ->whereIn('commandes.c_interne', [1, 2])
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);

                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }
                $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');

                $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total');

                $retour = $query->first()->total;

                $commandeProduit_menu =  self::ca_commande_menu($date_debut, $date_fin, null, null, $itemArray, false, null, 1);

                if (isset($commandeProduit_menu)) {
                    $total_menu = $commandeProduit_menu;
                    if (isset($total_menu)) {
                        $retour = $retour + $total_menu;
                    }
                }
                $commandeProduit_menu =  self::ca_commande_menu($date_debut, $date_fin, null, null, $itemArray, false, null, 2);

                if (isset($commandeProduit_menu)) {
                    $total_menu = $commandeProduit_menu;
                    if (isset($total_menu)) {
                        $retour = $retour + $total_menu;
                    }
                }

                //Recuperer le montant total des paiements commandes consos interne partiel
                $montant_commande_conso_partiel = self::soldeConsoInterne($date_debut, $date_fin, $entite_id);

                if (isset($montant_commande_conso_partiel)) {
                    $retour =  $retour - $montant_commande_conso_partiel;
                }
            }
            if ($type == "produits_commandes_conso_interne") {
                //Selectionner tous les produits des commandes

                $query = DB::table('commande_produits')
                    ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                    ->whereNull('commande_produits.menu_commande_id')
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);

                $query = self::sansConsoInterne($query, false);

                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }

                $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');
                $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total, count(commande_produits.produit_id) as nombre, produit_id')
                    ->groupBy('commande_produits.produit_id');
                $query = $query->get();

                //Selectionner tous les menus des commandes

                $commandeProduit_menu  = CommandeMenu::query()
                    ->join('produits', 'produits.id', '=', 'commande_menus.menu_id')
                    ->join('commandes', 'commandes.id', '=', 'commande_menus.commande_id')
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);

                $commandeProduit_menu = self::sansConsoInterne($commandeProduit_menu, false);


                $commandeProduit_menu = Outil::rajouteElements($commandeProduit_menu, $itemArray, 'commande_produits');

                $commandeProduit_menu = $commandeProduit_menu->selectRaw('COALESCE(SUM(commande_menus.montant),0) as total, count(produits.id) as nombre, menu_id')
                    ->groupBy(['produits.id', 'commande_menus.menu_id'])
                    ->get();

                $retour = array();
                foreach ($query as $value) {
                    $produit = Produit::find($value->produit_id);
                    if (isset($produit)) {
                        $famille = Famille::find($produit->famille_id);
                        $parametres = array(
                            'total'                         => $produit->total,
                            'prix_de_revient_unitaire'      => $produit->prix_de_revient_unitaire,
                            'prix_achat_ttc'                => $produit->prix_achat_ttc,
                            'prix_achat_unitaire'           => $produit->prix_achat_unitaire,
                        );
                        $revient = Outil::donnePrixRevient($parametres);

                        $one = array(
                            'produit'       => $produit,
                            'famille'       => $famille,
                            'vendu'         => $value->nombre,
                            'montant'       => $value->total,
                            'revient'       => $revient,
                        );
                        array_push($retour, $one);
                    }
                }
                //Charger les menus
                if (isset($commandeProduit_menu) && count($commandeProduit_menu) > 0) {
                    foreach ($commandeProduit_menu as $value) {
                        $produit = Produit::find($value->menu_id);
                        if (isset($produit)) {
                            $famille = isset($produit->famille_id) ? Famille::find($produit->famille_id) : null;
                            $parametres = array(
                                'total'                         => $produit->total,
                                'prix_de_revient_unitaire'      => $produit->prix_de_revient_unitaire,
                                'prix_achat_ttc'                => $produit->prix_achat_ttc,
                                'prix_achat_unitaire'           => $produit->prix_achat_unitaire,
                            );
                            $revient = Outil::donnePrixRevient($parametres);

                            $one = array(
                                'produit'       => $produit,
                                'famille'       => $famille,
                                'vendu'         => $value->nombre,
                                'montant'       => $value->total,
                                'revient'       => $revient,
                            );
                            array_push($retour, $one);
                        }
                    }

                    //   dd($retour);
                }
            }
            if ($type == "nombre_livraison") {
                $type_commande_id = null;
                $typecommande = Typecommande::where('designation', 'à livrer')->first();
                if (isset($typecommande)) {
                    $type_commande_id = $typecommande->id;
                }

                $query = DB::table('commandes')
                    ->where('commandes.type_commande_id', $type_commande_id)
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);
                $query = self::sansConsoInterne($query);
                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }
                $query = Outil::rajouteElements($query, $itemArray);
                $retour = $query->count();
                //dd($retour);
            }
            if ($type == "nombre_emporter") {
                $type_commande_id = null;
                $typecommande = Typecommande::where('designation', 'à emporter')->first();
                if (isset($typecommande)) {
                    $type_commande_id = $typecommande->id;
                }

                $query = DB::table('commandes')
                    ->where('commandes.type_commande_id', $type_commande_id)
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);
                $query = self::sansConsoInterne($query);
                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }
                $query = Outil::rajouteElements($query, $itemArray);
                $retour = $query->count();
                //dd($retour);
            }
            if ($type == "nombre_de_couverts") {

                $query = DB::table('commandes')
                    ->whereBetween('commandes.date', [$date_debut, $date_fin]);
                $query = self::sansConsoInterne($query);
                if ($entite_id) {
                    $itemArray['entite_id']  = $entite_id;
                }

                $query = Outil::rajouteElements($query, $itemArray);
                $query = $query->selectRaw('COALESCE(SUM(commandes.nombre_couvert),0) as total');
                $retour = $query->first()->total;
            }
            if ($type == "recap_cloture_caisse") {

                $ca_sur_place = Outil::donneElementsEtat("ca_commandes_sur_place", $itemArray, $query);
                $nombre_de_couverts = Outil::donneElementsEtat("nombre_de_couverts", $itemArray, $query);

                $ca_a_livrer = Outil::donneElementsEtat("ca_commandes_a_livrer", $itemArray, $query);
                $nombre_livraison = Outil::donneElementsEtat("nombre_livraison", $itemArray, $query);

                $ca_a_emporter = Outil::donneElementsEtat("ca_commandes_a_emporter", $itemArray, $query);
                $nombre_emporter = Outil::donneElementsEtat("nombre_emporter", $itemArray, $query);

                $couvert_moyen = 0;
                $panier_moyen_livraison = 0;
                $panier_moyen_emporter = 0;
                if ($nombre_de_couverts > 0) {
                    $couvert_moyen = round($ca_sur_place / $nombre_de_couverts, 2);
                }
                if ($nombre_livraison > 0) {
                    $panier_moyen_livraison = round($ca_a_livrer / $nombre_livraison, 2);
                }
                if ($ca_a_emporter > 0) {
                    $panier_moyen_emporter = round($ca_sur_place / $nombre_emporter, 2);
                }

                $retourArrayOne = array(
                    "ca_non_offerts"            => Outil::donneElementsEtat("ca_commandes_non_offerts", $itemArray, $query),
                    "ca_offerts"                => Outil::donneElementsEtat("ca_commandes_offerts", $itemArray, $query),
                    "ca_liquide_pertes"         => Outil::donneElementsEtat("ca_commandes_liquide_pertes", $itemArray, $query),
                    "ca_liquide_non_offerts"    => Outil::donneElementsEtat("ca_commandes_liquide_non_offerts", $itemArray, $query),
                    "ca_liquide_offerts"        => Outil::donneElementsEtat("ca_commandes_liquide_offerts", $itemArray, $query),
                    "ca_solide_non_offerts"     => Outil::donneElementsEtat("ca_commandes_solide_non_offerts", $itemArray, $query),
                    "ca_solide_pertes"          => Outil::donneElementsEtat("ca_commandes_solide_pertes", $itemArray, $query),
                    "ca_solide_offerts"         => Outil::donneElementsEtat("ca_commandes_solide_offerts", $itemArray, $query),
                    "ca_a_livrer"               => $ca_a_livrer,
                    "ca_a_emporter"             => $ca_a_emporter,
                    "ca_sur_place"              => $ca_sur_place,
                    "nombre_de_couverts"        => $nombre_de_couverts,
                    "nombre_livraison"          => $nombre_livraison,
                    "nombre_emporter"           => $nombre_emporter,
                    "couvert_moyen"             => $couvert_moyen,
                    "panier_moyen_livraison"    => $panier_moyen_livraison,
                    "panier_moyen_emporter"     => $panier_moyen_emporter,
                );
                $retour = $retourArrayOne;
            }
            if ($type == "elements_caisse") {
                //Total appros receveur
                $queryApprosReceveurs = DB::table("approcashs");
                $queryApprosReceveurs = $queryApprosReceveurs->whereBetween('date', [$date_debut, $date_fin]);
                $queryApprosReceveurs = $queryApprosReceveurs->where('caisse_destinataire_id', $caisse_id);
                $queryApprosReceveurs = $queryApprosReceveurs->orderBy('date', 'ASC')->get();

                //Total appros destinatair
                $queryApprosEmmetteurs = DB::table("approcashs");
                $queryApprosEmmetteurs = $queryApprosEmmetteurs->whereBetween('date', [$date_debut, $date_fin]);
                $queryApprosEmmetteurs = $queryApprosEmmetteurs->where('caisse_source_id', $caisse_id);
                $queryApprosEmmetteurs = $queryApprosEmmetteurs->orderBy('date', 'ASC')->get();

                //Total sortie
                $querySorties          = DB::table("sortie_cashs");
                $querySorties          = $querySorties->whereBetween('date', [$date_debut, $date_fin]);
                $querySorties          = $querySorties->where('caisse_id', $caisse_id);
                $querySorties          = $querySorties->orderBy('date', 'ASC')->get();

                $caisse                = Caisse::find($caisse_id);
                $fournisseur           = null;
                $solde_caisse          = null;

                //Solde veille

                if (isset($caisse_id)) {

                    $parametres = array(
                        'date_debut'                    => isset($args['date_start']) ? $args["date_start"] : null,
                        'date_fin'                      => isset($args['date_end']) ? $args["date_end"] : null,
                        'caisse_id'                     =>  isset($args['caisse_id']) ? $args["caisse_id"] : null,
                        'cloture_caisse_id'             => isset($args['cloture_caisse_id']) ? $args['cloture_caisse_id'] : null,
                        'fournisseur_id'                => isset($args['fournisseur_id']) ? $args['fournisseur_id'] : null,
                    );

                    $solde_caisse                       = $parametres;
                    if (isset($solde_caisse) && count($solde_caisse) > 0) {
                        // $solde_caisse = $solde_caisse[0]["depense_caisse"];
                    }
                }

                //Total reglements dépenses
                $filtres = 'date_start:"' . $date_debut . '",date_end:"' . $date_fin . '",caisse_id:' . $caisse_id . ',ordre:2';
                if (isset($fournisseur_id)) {
                    $filtres      = $filtres . ',fournisseur_id:' . $fournisseur_id . '';

                    $fournisseur  = Fournisseur::find($fournisseur_id);
                }
                if (isset($entites)) {
                    $filtres      = $filtres . ',entites:"' . $entites . '"';
                }
                //                else{
                //                    $filtres      = $filtres . ',caisse_id:'.$caisse_id . '';
                //                }
                $filtres      = $filtres . ',est_cash:1';
                $queryRegelments  = Outil::getAllItemsWithGraphQl("reglements", $filtres);

                //Toutes les entités
                $queryEntites     = DB::table("entites");
                $queryEntites     = $queryEntites->get();

                $totalGeneralAppros          = 0; //Total de tous les appros
                $totalGeneralDepenses        = 0; //Total de tous les reglements dépenses
                $totalGeneralApprosEmetteurs = 0; //Total de tous les appros emetteurs
                $totalGeneralSortie          = 0; //Total de toutes les sorties

                foreach ($queryApprosReceveurs as $valueappro) {
                    $totalGeneralAppros += $valueappro->montant;
                }

                foreach ($queryApprosEmmetteurs as $valueappro) {
                    $totalGeneralApprosEmetteurs += $valueappro->montant;
                }

                foreach ($querySorties as $valuesortie) {
                    $totalGeneralSortie += $valuesortie->montant;
                }

                //Total par entité
                $totaux_entites  = array();

                foreach ($queryEntites as $value) {
                    $totalGlobal = 0;
                    $totalCompta = 0;
                    $totalHorsCompta = 0;
                    foreach ($queryRegelments as $value2) {
                        if ($value->id == $value2["depense"]["entite_id"]) {
                            $totalGlobal += $value2["montant"];
                            if ($value2["depense"]["compta"] == 0) {
                                $totalCompta += $value2["montant"];
                            } else if ($value2["depense"]["compta"] > 0) {
                                $totalHorsCompta += $value2["montant"];
                            }
                        }
                    }
                    array_push($totaux_entites,  array(
                        "entite_id"             => $value->id,
                        "entite"                => $value->designation,
                        "total_global"          => $totalGlobal,
                        "total_compta"          => $totalCompta,
                        "total_hors_compta"     => $totalHorsCompta,
                    ));

                    $totalGeneralDepenses += $totalGlobal;
                }

                $retour = array(
                    'approcahs'             => $queryApprosReceveurs,
                    'approcahs_emetteur'    => $queryApprosEmmetteurs,
                    'sortie'                => $querySorties,
                    'reglements'            => $queryRegelments,
                    'entites'               => $queryEntites,
                    'totaux_entites'        => $totaux_entites,
                    'total_depense'         => $totalGeneralDepenses,
                    'total_appro'           => $totalGeneralAppros,
                    'total_appro_emetteur'  => $totalGeneralApprosEmetteurs,
                    'total_sortie'          => $totalGeneralSortie,
                    'caisse'                => $caisse,
                    "date_debut"            => self::resolveAllDateCompletFR($date_debut, false),
                    "date_fin"              => self::resolveAllDateCompletFR($date_fin, false),
                    "fournisseur"           => $fournisseur,
                    "solde_caisse"          => $solde_caisse
                );
                // dd($retour);
            }
            if ($type == "elements_resume") {
                $date_debut = $itemArray["date_debut"];
                $date_fin = $itemArray["date_fin"];

                $diffJours = Outil::nombreJoursEntreDeuxDates($date_debut, $date_fin);

                $donneesJour = array();
                $donneesCouvert = array();
                $donneesCaNonOffert = array();
                $donneesCaOffert = array();
                $donneesCaTotal = array();
                $donneesLivraison = array();
                $donneesEmporte = array();
                $donneesEncCash = array();
                $donneesEncBanque = array();
                $donneesEncaissement = array();
                $donneesManquant = array();
                $donneesBilletage = array();
                for ($i = 0; $i <= $diffJours; $i++) {
                    $dateRequete = Outil::donneDateParRapportNombreJour($date_debut, $i);
                    $dateRequeteDebut = $dateRequete . " 00:00";
                    $dateRequeteFin = $dateRequete . " 23:59";

                    $datedateRequeteFr = Outil::dateEnFrancais($dateRequete);
                    $totalCa = 0;

                    //Jours
                    array_push($donneesJour,  array(
                        "date"                  => $dateRequete,
                        "date_fr"               => $datedateRequeteFr,
                    ));

                    //Nombre de couverts
                    $query = DB::table('commandes')
                        ->whereBetween('commandes.date', [$dateRequeteDebut, $dateRequeteFin]);
                    $query = Outil::rajouteElements($query, $itemArray);
                    $query = $query->selectRaw('COALESCE(SUM(commandes.nombre_couvert),0) as total');
                    $query = $query->first()->total;
                    array_push($donneesCouvert,  array(
                        "date"                  => $dateRequete,
                        "date_fr"               => $datedateRequeteFr,
                        "total"                 => $query,
                    ));

                    //CA non offerts
                    $query = DB::table('commande_produits')
                        ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                        ->where('commande_produits.offre', false)
                        ->whereBetween('commandes.date', [$dateRequeteDebut, $dateRequeteFin]);
                    $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');
                    $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total');
                    $query = $query->first()->total;
                    $totalCa += $query;
                    array_push($donneesCaNonOffert,  array(
                        "date"                  => $dateRequete,
                        "date_fr"               => $datedateRequeteFr,
                        "total"                 => $query,
                    ));

                    //CA offerts
                    $query = DB::table('commande_produits')
                        ->join('commandes', 'commandes.id', '=', 'commande_produits.commande_id')
                        ->where('commande_produits.offre', true)
                        ->whereBetween('commandes.date', [$dateRequeteDebut, $dateRequeteFin]);
                    $query = Outil::rajouteElements($query, $itemArray, 'commande_produits');
                    $query = $query->selectRaw('COALESCE(SUM(commande_produits.montant),0) as total');
                    $query = $query->first()->total;
                    $totalCa += $query;
                    array_push($donneesCaOffert,  array(
                        "date"                  => $dateRequete,
                        "date_fr"               => $datedateRequeteFr,
                        "total"                 => $query,
                    ));

                    //CA total
                    array_push($donneesCaTotal,  array(
                        "date"                  => $dateRequete,
                        "date_fr"               => $datedateRequeteFr,
                        "total"                 => $totalCa,
                    ));

                    //Nbre commande à livrer
                    $typecommande = Typecommande::where('designation', 'à livrer')->first();
                    if (isset($typecommande)) {
                        $query = DB::table('commandes')
                            ->where('commandes.type_commande_id', $typecommande->id)
                            ->whereBetween('commandes.date', [$dateRequeteDebut, $dateRequeteFin]);
                        $query = Outil::rajouteElements($query, $itemArray);
                        $query = $query->count();
                        array_push($donneesLivraison,  array(
                            "date"                  => $dateRequete,
                            "date_fr"               => $datedateRequeteFr,
                            "total"                 => $query,
                        ));
                    }

                    //Nbre commande à emporter
                    $typecommande = Typecommande::where('designation', 'à emporter')->first();
                    if (isset($typecommande)) {
                        $query = DB::table('commandes')
                            ->where('commandes.type_commande_id', $typecommande->id)
                            ->whereBetween('commandes.date', [$dateRequeteDebut, $dateRequeteFin]);
                        $query = Outil::rajouteElements($query, $itemArray);
                        $query = $query->count();
                        array_push($donneesEmporte,  array(
                            "date"                  => $dateRequete,
                            "date_fr"               => $datedateRequeteFr,
                            "total"                 => $query,
                        ));
                    }

                    //Total encaissement cash
                    $modepaiements = Modepaiement::where('est_cash', 1)->get();
                    $modePaiementsArray = array();
                    $totalEncCash = 0;
                    foreach ($modepaiements as $value) {
                        $query = DB::table('encaissements')
                            ->join('cloture_caisses', 'cloture_caisses.id', '=', 'encaissements.cloture_caisse_id')
                            ->where('cloture_caisses.type', 0)
                            ->where('encaissements.mode_paiement_id', $value->id)
                            ->whereBetween('cloture_caisses.created_at', [$dateRequeteDebut, $dateRequeteFin]);
                        $query = Outil::rajouteElements($query, $itemArray, 'encaissements');
                        $query = $query->selectRaw('COALESCE(SUM(encaissements.montant),0) as total');
                        $query = $query->first()->total;
                        $totalEncCash += $query;
                    }
                    array_push($donneesEncCash,  array(
                        "date"                  => $dateRequete,
                        "date_fr"               => $datedateRequeteFr,
                        "total"                 => $totalEncCash,
                    ));

                    //Total encaissement banque
                    $modepaiements = Modepaiement::where('banque', 1)->get();
                    $modePaiementsArray = array();
                    $totalEncBanque = 0;
                    foreach ($modepaiements as $value) {
                        $query = DB::table('encaissements')
                            ->join('cloture_caisses', 'cloture_caisses.id', '=', 'encaissements.cloture_caisse_id')
                            ->where('cloture_caisses.type', 0)
                            ->where('encaissements.mode_paiement_id', $value->id)
                            ->whereBetween('cloture_caisses.created_at', [$dateRequeteDebut, $dateRequeteFin]);
                        $query = Outil::rajouteElements($query, $itemArray, 'encaissements');
                        $query = $query->selectRaw('COALESCE(SUM(encaissements.montant),0) as total');
                        $query = $query->first()->total;
                        $totalEncBanque += $query;
                    }
                    array_push($donneesEncBanque,  array(
                        "date"                  => $dateRequete,
                        "date_fr"               => $datedateRequeteFr,
                        "total"                 => $totalEncBanque,
                    ));

                    //Encaissements clotures caisse
                    $modepaiements = Modepaiement::all();
                    $modePaiementsArray = array();
                    foreach ($modepaiements as $value) {
                        $query = DB::table('encaissements')
                            ->join('cloture_caisses', 'cloture_caisses.id', '=', 'encaissements.cloture_caisse_id')
                            ->where('cloture_caisses.type', 0)
                            ->where('encaissements.mode_paiement_id', $value->id)
                            ->whereBetween('cloture_caisses.created_at', [$dateRequeteDebut, $dateRequeteFin]);
                        $query = Outil::rajouteElements($query, $itemArray, 'encaissements');
                        $query = $query->selectRaw('COALESCE(SUM(encaissements.montant),0) as total');
                        $query = $query->first()->total;
                        array_push($modePaiementsArray,  array(
                            "date"                  => $dateRequete,
                            "date_fr"               => $datedateRequeteFr,
                            "modepaiement"          => $value->designation,
                            "total"                 => $query,
                        ));
                    }
                    array_push($donneesEncaissement,  array(
                        "date"                  => $dateRequete,
                        "date_fr"               => $datedateRequeteFr,
                        "modepaiements"         => $modePaiementsArray,
                    ));

                    //Manquant
                    $query = DB::table('cloture_caisses')
                        ->where('cloture_caisses.type', 0)
                        ->whereBetween('cloture_caisses.created_at', [$dateRequeteDebut, $dateRequeteFin]);
                    $query = Outil::rajouteElements($query, $itemArray, 'encaissements');
                    $query = $query->selectRaw('COALESCE(SUM(cloture_caisses.montant_manquant),0) as total');
                    $query = $query->first()->total;
                    array_push($donneesManquant,  array(
                        "date"                  => $dateRequete,
                        "date_fr"               => $datedateRequeteFr,
                        "total"                 => $query,
                    ));

                    //Billetages
                    /*  $filtres = 'date_start:"'.$dateRequeteDebut.'",date_end:"'.$dateRequeteFin.'"';
                    $billetages = Outil::getOneItemWithFilterGraphQl("billetages", $filtres);
                    array_push($donneesBilletage,  array(
                        "date"                  => $dateRequete,
                        "date_fr"               => $datedateRequeteFr,
                        "billetages"            => $billetages,
                    )); */
                }

                //Billetages
                $dateGlobalDebut = $date_debut . " 00:00";
                $dateGlobalFin = $date_fin . " 23:59";
                $typebillets = Typebillet::all();
                $totalBilletage = 0;

                foreach ($typebillets as $value) {
                    $query = DB::table('billetages')
                        ->join('cloture_caisses', 'cloture_caisses.id', '=', 'billetages.cloture_caisse_id')
                        ->where('cloture_caisses.type', 0)
                        ->where('billetages.type_billet_id', $value->id)
                        ->whereBetween('cloture_caisses.created_at', [$dateGlobalDebut, $dateGlobalFin]);

                    $query = Outil::rajouteElements($query, $itemArray, 'encaissements');

                    $query = $query->selectRaw('COALESCE(SUM(billetages.nombre),0) as nombre');

                    $query = $query->first()->nombre;
                    //                    dd($dateGlobalFin);
                    $total = $query * $value->nombre;

                    $totalBilletage += $total;
                    array_push($donneesBilletage,  array(
                        "typebillet"            => $value->designation,
                        "nombre"                => $query,
                        "total"                 => $total,
                    ));
                }


                $retour = array(
                    'jours'                     => $donneesJour,
                    'nbre_couvert'              => $donneesCouvert,
                    'ca_total_non_offert'       => $donneesCaNonOffert,
                    'ca_total_offert'           => $donneesCaOffert,
                    'ca_total'                  => $donneesCaTotal,
                    'nbre_livraison'            => $donneesLivraison,
                    'nbre_a_emporter'           => $donneesEmporte,
                    'total_cash'                => $donneesEncCash,
                    'total_banque'              => $donneesEncBanque,
                    'encaissements'             => $donneesEncaissement,
                    'manquant'                  => $donneesManquant,
                    'billetages'                => $donneesBilletage,
                    'total_billetage'           => $totalBilletage,
                );
            }
            if ($type == "ca_commandes_menu_non_offerts") {
                $retour = 0;

                $commandeProduit_menu =  self::ca_commande_menu($date_debut, $date_fin, null, null, $itemArray, false, null);
                //$query = self::sansConsoInterne($query);
                // $commandeProduit_menu =  self::ca_commande_menu($date_debut, $date_fin,null,null,$itemArray);

                if (isset($commandeProduit_menu)) {

                    $total_menu = $commandeProduit_menu;
                    if (isset($total_menu)) {
                        $retour += $total_menu;
                    }
                }
            }
        }
        return $retour;
    }


    public static function sansConsoInterne($query, $sans_conso_interne = true)
    {
        //Noueaux scenario sur les consos internes, ce process suivant , est le bon et c'est a decommente

        if ($sans_conso_interne) {
            $query = $query->whereNotIn('commandes.c_interne', [1, 2]);
        } else {
            $query = $query->where('commandes.c_interne', 1);
        }
        //        $modePaiement        = Modepaiement::conso_interne();
        //        if($modePaiement) {
        //
        //            if($avecousans){
        //                $query = $query->whereNotIn('commandes.id', PaiementCredit::where('mode_paiement_id', $modePaiement->id)->get(['commande_id']));
        //            }else{
        //                $query = $query->whereIn('commandes.id', PaiementCredit::where('mode_paiement_id', $modePaiement->id)->get(['commande_id']));
        //            }
        //        }

        return $query;
    }

    public static function soldeConsoInterne($date = null, $date_fin = null, $entite_id = null, $type_commande_id = null, $query = null)
    {

        if (!isset($query)) {
            $query        = Commande::query()
                ->join('commande_produits', 'commande_produits.commande_id', '=', 'commandes.id')
                ->where('commande_produits.offre', '=', false)
                ->whereNull('commande_produits.perte');

            if (isset($date) && isset($date_fin)) {
                $query = $query->whereBetween('commandes.date', [$date, $date_fin]);
            }
            if (isset($entite_id)) {
                $query = $query->where('commandes.entite_id', $entite_id);
            }
            if (isset($type_commande_id)) {
                $query = $query->where('commandes.type_commande_id', $type_commande_id);
            }

            if (isset($commade_id)) {
                $query = $query->where('commandes.id', '=', $commade_id);
            } else {
                $query = $query->where('commandes.c_interne', 2);
            }
        } else {
            $query     = $query->where('commandes.c_interne', 2);
        }

        if (isset($query)) {
            $query        = $query->get(['commandes.id']);
            $paiements    = DB::table("paiements")->select(DB::raw("COALESCE(SUM(montant),0) as total"));
            $paiements    = $paiements->whereIn('paiements.commande_id', $query);
            $paiements    = isset($paiements) ? $paiements->first()->total : 0;
        } else {
            $paiements    = 0;
        }


        return $paiements;
    }



    //Remise à 0 selected pour tous les panier_produit du panier
    public static function remiseZeroSelectedPanierProduit($panier)
    {
        $retour = false;
        if (isset($panier)) {
            DB::table('panier_produits')->where('panier_id', $panier->id)->update(['selected' => 0]);
            $retour = true;
        }
        return $retour;
    }

    //Remise à 0 les adresses par defaut du client
    public static function remiseZeroAdresseParDefautClient($client)
    {
        $retour = false;
        if (isset($client)) {
            DB::table('adresses')->where('client_id', $client->id)->update(['par_defaut' => 0]);
            $retour = true;
        }
        return $retour;
    }

    //Marquer les selected sur les produits séléctionnés de panier_produit
    public static function marquerSelectedPanierProduit($panier, $produitSelectionnes)
    {
        $retour = false;
        if (isset($panier) && isset($produitSelectionnes)) {
            $retour = true;
            Outil::remiseZeroSelectedPanierProduit($panier);
            foreach ($produitSelectionnes as $value) {
                $panierProduit = new PanierProduit();
                $panierProduit = panierProduit::where('panier_id', $panier->id)->where('produit_id', $value)->first();
                if (isset($panierProduit)) {
                    $panierProduit->selected = 1;
                    $panierProduit->save();
                }
            }
        }
        return $retour;
    }

    public static function donneSelectedProduitArray($produitSelectionnes)
    {
        $produits = $produitSelectionnes;
        //$produits = json_decode($produitSelectionnes, true);
        $retour = array();
        foreach ($produits as $value) {
            array_push($retour, $value);
            //  array_push($retour, $value["id"]);
        }
        return $retour;
    }

    public static function getConnectedClient($valeurIdClient = null)
    {
        if (isset($valeurIdClient)) {
            $client_id = $valeurIdClient;
        } else {
            $client_id = 1;
        }

        return $client_id;
    }

    public static function getUser()
    {
        $user = auth()->user();
        $user1 = Auth::user();

        return $user1;
    }

    //Test la connection du client par token
    public static function getConnectedClientToken(Request $request)
    {
        $retour = 0;

        $item = Client::where("token", $request->token)->first();
        if ($item) {
            $retour = $item->id;
        }
        return $retour;

        //A tester pourqoi sur les querys ca ne marchait pas (avant de faire sauter le return)
        $item = new ClientJwt();
        $valeurObjet = $item->handle($request);
        //dd($valeurObjet);
        $valeurObjetParsei = $valeurObjet->getData();
        $status = $valeurObjetParsei->status;
        if (isset($status)) {
            //dd($valeurObjetParsei->err_);
            if ($status == true || $status == 1) {
                //Le token est bon
                //$retour = true;
                $item = Client::where("token", $request->token)->first();
                if ($item) {
                    $retour = $item->id;
                }
            }
        }

        return $retour;
    }

    //Donner les mois antérieurs par rapport à un chiffres
    public static function moisAnterieurs($nbre)
    {
        for ($i = 0; $i <= $nbre; $i++) {
            $moisAnnees[] = date("Y-m", strtotime(date('Y-m-01') . " -$i months"));
        }
        return $moisAnnees;
    }

    //Donner les mois antérieurs par rapport à un intervalle de mois
    public static function moisAnterieursParDates($debut, $fin)
    {
        $debut = $debut . '-01';
        $fin = $fin . '-01';
        $date_start = date_create($debut . '-01');
        $date_end = date_create($fin . '-01');
        $nbre = date_diff($date_start, $date_end);
        //Diffrérence en mois
        $nbre = $nbre->format('%m');
        for ($i = 0; $i <= $nbre; $i++) {
            $moisAnnees[] = date("Y-m", strtotime($fin . " -$i months"));
        }
        return $moisAnnees;
    }

    //Donner les ids users des dépots sélectionnés
    public static function donneIdUserDepotsParIdDepot($idDepots)
    {
        $id_user_depots = array();
        if ($idDepots) {
            $depotUsers = DB::select(DB::raw("SELECT user_id FROM depot_users WHERE depot_id IN ($idDepots)"));
            foreach ($depotUsers as $value) {
                array_push($id_user_depots, $value->user_id);
            }
        }
        return $id_user_depots;
    }

    //Donner le mois en lettres
    public static function donneMoisEnLettres($mydate, $nomMoisComplet = false)
    {
        //#tags: mois, lettres, changer mois, formater mois
        //Janvier = 0, Février = 1, etc
        $nomMois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jui', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        if ($nomMoisComplet) {
            $nomMois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        }
        $mois = substr($mydate, 5, 2);
        $mois = intval($mois);
        $mois = $mois - 1;
        $retour = $nomMois[$mois];
        return $retour;
    }

    //Remplace les espaces par vide
    public static function enleveEspaces($val)
    {
        $retour = str_replace(" ", "", $val); //Espace normal
        $retour = str_replace(' ', '', $retour); //Espace bizarre sur le fichier Excel d'import fourni par DMD (les 2 espaces ne sont pas pareils)
        return $retour;
    }

    public static function mettreEnMiniscule($val)
    {
        $retour = strtolower($val);
        return $retour;
    }

    public static function minisculeSansEspaces($val)
    {
        $retour = trim($val);
        $retour = Outil::enleveEspaces($retour);
        $retour = Outil::mettreEnMiniscule($retour);
        return $retour;
    }

    public static function remplaEspaceBizarre($val)
    {
        $retour = str_replace(' ', '', $val);
        return $retour;
    }

    //Générer un mot de passe aléatoire
    public static function generer_password($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    //Donne le format de la devise TTC
    public static function donneFormatDevise()
    {
        $retour = ' F TTC';
        return $retour;
    }

    //Donne le format de la devise Ht
    public static function donneFormatDeviseHt()
    {
        $retour = ' F HT';
        return $retour;
    }

    //Donne le format de la devise simple
    public static function donneFormatDeviseSimple()
    {
        $retour = ' F';
        return $retour;
    }

    //Formater le prix
    public static function formatPrixToMonetaire($nbre, $arrondir = false, $avecDevise = false)
    {
        //Ajouté pour arrondir le montant
        if ($arrondir == true) {
            $nbre = Outil::enleveEspaces($nbre);
            $nbre = round($nbre);
        }
        $rslt = "";
        $position = strpos($nbre, '.');
        if ($position === false) {
            //---C'est un entier---//
            //Cas 1 000 000 000 Ã  9 999 000
            if (strlen($nbre) >= 9) {
                $c = substr($nbre, -3, 3);
                $b = substr($nbre, -6, 3);
                $d = substr($nbre, -9, 3);
                $a = substr($nbre, 0, strlen($nbre) - 9);
                $rslt = $a . ' ' . $d . ' ' . $b . ' ' . $c;
            } //Cas 100 000 000 Ã  9 999 000
            elseif (strlen($nbre) >= 7 && strlen($nbre) < 9) {
                $c = substr($nbre, -3, 3);
                $b = substr($nbre, -6, 3);
                $a = substr($nbre, 0, strlen($nbre) - 6);
                $rslt = $a . ' ' . $b . ' ' . $c;
            } //Cas 100 000 Ã  999 000
            elseif (strlen($nbre) >= 6 && strlen($nbre) < 7) {
                $a = substr($nbre, 0, 3);
                $b = substr($nbre, 3);
                $rslt = $a . ' ' . $b;
                //Cas 0 Ã  99 000
            } elseif (strlen($nbre) < 6) {
                if (strlen($nbre) > 3) {
                    $a = substr($nbre, 0, strlen($nbre) - 3);
                    $b = substr($nbre, -3, 3);
                    $rslt = $a . ' ' . $b;
                } else {
                    $rslt = $nbre;
                }
            }
        } else {
            //---C'est un décimal---//
            $partieEntiere = substr($nbre, 0, $position);
            $partieDecimale = substr($nbre, $position, strlen($nbre));
            //Cas 1 000 000 000 Ã  9 999 000
            if (strlen($partieEntiere) >= 9) {
                $c = substr($partieEntiere, -3, 3);
                $b = substr($partieEntiere, -6, 3);
                $d = substr($partieEntiere, -9, 3);
                $a = substr($partieEntiere, 0, strlen($partieEntiere) - 9);
                $rslt = $a . ' ' . $d . ' ' . $b . ' ' . $c;
            } //Cas 100 000 000 Ã  9 999 000
            elseif (strlen($partieEntiere) >= 7 && strlen($partieEntiere) < 9) {
                $c = substr($partieEntiere, -3, 3);
                $b = substr($partieEntiere, -6, 3);
                $a = substr($partieEntiere, 0, strlen($partieEntiere) - 6);
                $rslt = $a . ' ' . $b . ' ' . $c;
            } //Cas 100 000 Ã  999 000
            elseif (strlen($partieEntiere) >= 6 && strlen($partieEntiere) < 7) {
                $a = substr($partieEntiere, 0, 3);
                $b = substr($partieEntiere, 3);
                $rslt = $a . ' ' . $b;
                //Cas 0 Ã  99 000
            } elseif (strlen($partieEntiere) < 6) {
                if (strlen($partieEntiere) > 3) {
                    $a = substr($partieEntiere, 0, strlen($partieEntiere) - 3);
                    $b = substr($partieEntiere, -3, 3);
                    $rslt = $a . ' ' . $b;
                } else {
                    $rslt = $partieEntiere;
                }
            }
            if ($partieDecimale == '.0' || $partieDecimale == '.00' || $partieDecimale == '.000') {
                $partieDecimale = '';
            }
            $rslt = $rslt . '' . $partieDecimale;
        }
        if ($avecDevise == true) {
            $formatDevise = Outil::donneFormatDevise();
            $rslt = $rslt . '' . $formatDevise;
        }
        return $rslt;
    }

    public static function montantEnLettres($montant)
    {
        $retour = '';
        $partieEntiere = $montant;
        $partieDecimale = 0;
        $position = strpos($montant, '.');
        $formatter = \NumberFormatter::create('fr_FR', \NumberFormatter::SPELLOUT);
        $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
        $formatter->setAttribute(\NumberFormatter::ROUNDING_MODE, \NumberFormatter::ROUND_HALFUP);

        if ($position == false) {
            //---C'est un entier---//
            $retour = $formatter->format($partieEntiere);
        } else {
            //---C'est un décimal---//
            $partieEntiere = (int)substr($montant, 0, $position);
            $partieDecimale = (int)substr($montant, $position + 1, strlen($montant));
            //Mis ici pour ne pas qu'il prenne les point 0 (.0)
            $partieDecimaleParsei = (int)$partieDecimale;
            if ($partieDecimaleParsei > 0) {
                $retour = $formatter->format($partieEntiere) . ' point ' . $formatter->format($partieDecimale);
            } else {
                $retour = $formatter->format($partieEntiere);
            }
        }

        return $retour;
    }

    public static function convertirEnLettres($montant)
    {

        // $formatter = new NumberFormatter('fr', NumberFormatter::SPELLOUT);
        // return $formatter->format($montant);

        // $dictionary = array(
        //     1 => 'zero',
        //     1 => 'un',
        //     2 => 'deux',
        //     3 => 'trois',
        //     4 => 'quatre',
        //     5 => 'cinq',
        //     6 => 'six',
        //     7 => 'sept',
        //     8 => 'huit',
        //     9 => 'neuf',
        // );
        // // example 422.6543
        // $fraction = (integer) 6543;
        // $decimal = 'quatre deux deux / ';

        // if (null !== $fraction && is_numeric($fraction)) {
        //     $string .= $decimal;
        //     $words = array();
        //     $fraction = (string) $fraction;
        //     for ($i = 0; $i < strlen($fraction); ++$i) {
        //         $words[] = $dictionary[$fraction{$i}];
        //     }
        //     $string .= implode(' ', $words);
        // }

        $unites = ['', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf'];
        $dizaines = ['', 'dix', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante', 'soixante-dix', 'quatre-vingts', 'quatre-vingt-dix'];
        $centaines = ['', 'cent', 'deux cent', 'trois cent', 'quatre cent', 'cinq cent', 'six cent', 'sept cent', 'huit cent', 'neuf cent'];
        $lettreSpeciale = ['', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf'];

        if ($montant == 0) {
            return 'zéro';
        }

        $resultat = '';
        if ($montant >= 1000000) {
            $millions = floor($montant / 1000000);
            // $resultat .= self::convertirEnLettres($millions) . ' million ';
            $resultat .= self::convertirEnLettres($millions) . ' million' . ($millions > 1 ? 's' : '');
            $montant %= 1000000;
            if ($montant > 0) {
                $resultat .= ' ';
            }
        }
        if ($montant >= 1000) {
            $milliers = floor($montant / 1000);
            $resultat .= self::convertirEnLettres($milliers) . ' mille ';
            $montant %= 1000;
        }

        if ($montant >= 100) {
            $centaine = floor($montant / 100);
            $resultat .= $centaines[$centaine] . ' ';
            $montant %= 100;
        }

        if (($montant >= 91 && $montant <= 99) || ($montant >= 71 && $montant <= 79)) {
            $unite = $montant % 10;

            $prefixe = $montant >= 91 ? 'quatre-vingt-' : 'soixante-';
            $resultat .= $prefixe . $lettreSpeciale[$unite];
            $montant = 0;
        }

        if ($montant >= 11 && $montant <= 19) {
            $resultat .= $lettreSpeciale[$montant - 10];
            $montant = 0;
        }

        if ($montant >= 10) {
            $dizaine = floor($montant / 10);
            $resultat .= $dizaines[$dizaine] . ' ';
            $montant %= 10;
        }

        if ($montant > 0) {
            $resultat .= $unites[$montant] . ' ';
        }

        return trim($resultat);


    }


    public static function resolveAllDateCompletFR($date, $getHour = true, $format = null)
    {
        $date_at = $date;
        if ($date_at !== null) {
            $date_at = $date_at;
            $date_at = date_create($date_at);

            return $getHour ? date_format($date_at, !isset($format) ? "d-m-Y H:i:s" : $format) : date_format($date_at, !isset($format) ? "d-m-Y" : $format);
        } else {
            return null;
        }
    }

    public static function resolveAllDateCompletFRSlash($date, $getHour = true)
    {
        $date_at = $date;
        if ($date_at !== null) {
            $date_at = $date_at;
            $date_at = date_create($date_at);

            return $getHour ? date_format($date_at, "d/m/Y H:i:s") : date_format($date_at, "d-m-Y");
        } else {
            return null;
        }
    }



    //Texte à afficher s'il n'y a pas de prix
    public static function textPourSansPrix()
    {
        $retour = "Consultez-nous";
        $preference = Preference::where('designation', 'text-sans-prix')->first();
        if (isset($preference)) {
            $retour = $preference->valeur;
        }
        return $retour;
    }

    //Controller si le client est bloqué ou pas
    public static function checkBlocageClient($bloque, $plafond, $dette)
    {
        $retour = 0;
        if (isset($bloque) && isset($plafond) && isset($dette)) {
            if ($bloque == 1) {
                //le client est bloqué manuellement
                $retour = 1;
            } else {
                if ($dette > $plafond) {
                    //Blocage automatique du client
                    $retour = 1;
                }
            }
        }

        return $retour;
    }

    //Fonction qui créée le matricule
    public static function faireMatricule($alias, $maxi, $inclureAnnee = null)
    {
        if ($inclureAnnee == null) {
            $annee = '';
        } else {
            $annee = substr(date('Y'), 2, 2);
        }
        $alias = $alias . '-';
        if ($maxi < 10) {
            $matri = $alias . '' . $annee . '000' . $maxi;
        } elseif ($maxi < 100) {
            $matri = $alias . '' . $annee . '00' . $maxi;
        } elseif ($maxi < 1000) {
            $matri = $alias . '' . $annee . '0' . $maxi;
        } else {
            $matri = $alias . '' . $annee . '' . $maxi;
        }
        return $matri;
    }

    //Envoi les emails
    public static function envoiEmail($destinataire, $sujet, $texte, $page = 'maileur', $contratid = null, $copies = null, $attachs = null, $link = null)
    {

        // try {
            dispatch(function () use ($destinataire, $sujet, $texte, $page, $contratid, $copies, $attachs, $link) {
                Mail::to($destinataire)
                    ->send(new Maileur($sujet, $texte, $page, $contratid, $copies, $attachs, $link));
            });
        // }
        // catch (Swift_TransportException $e) {
        //     return $e->getMessage();
        // }

        return true;
    }

    //Envoi les emails
    public static function inboxMail($destinataire, $sujet, $texte, $attachs, $page = 'inboxmaileur')
    {
        // dd($attachs);
        // try {
            dispatch(function () use ($destinataire, $sujet, $texte, $attachs, $page) {
                Mail::to($destinataire)
                    ->send(new InboxMail($sujet, $texte, $page, $attachs));
            });
        // }
        // catch (Swift_TransportException $e) {
        //     return $e->getMessage();
        // }

        return true;
    }

    //Inregistre les notifications
    public static function saveNotification($type, $codeElement = null)
    {
        $designation = "";
        $description = "";
        $date_notif = date('Y-m-d');
        $image = "assets/images/notif.png";
        $lien = "";
        $deja_vu = 0;

        $codeElementRetour = "";
        if (isset($codeElement)) {
            $codeElementRetour = $codeElement;
        }

        if ($type == "demandeacces") {
            $designation = "Nouvelle demande d'accès " . $codeElementRetour . " à valider";
            $lien = "list-demandeacces";
        } else if ($type == "proforma") {
            $designation = "Une nouvelle proforma " . $codeElementRetour . " a été créée";
            $lien = "list-proforma";
        } else if ($type == "commande") {
            $designation = "Une nouvelle commande " . $codeElementRetour . " a été créée";
            $lien = "list-commande";
        }

        $item = new Notification();
        $item->designation = $designation;
        $item->description = $description;
        $item->date_notif = $date_notif;
        $item->image = $image;
        $item->lien = $lien;
        $item->deja_vu = $deja_vu;
        $item->save();

        $id = $item->id;
        return $id;
    }

    //Message synchronisation terminée
    public static function msgSyncTerminee()
    {
        $retour = "Synchronisation terminée";
        return $retour;
    }

    //Convertir base 64 en image
    public static function base64ToImage($b64, $queryName, $code = null)
    {
        // Obtain the original content (usually binary data)
        $bin = base64_decode($b64);

        // Load GD resource from binary data
        $im = imageCreateFromString($bin);

        if (!$im) {
            die('Base64 value is not a valid image');
        }

        // Specify the location where you want to save the image
        $dateHeure = date('Y_m_d_H_i_s');
        if (empty($code)) {
            $code = "";
        }
        $img_file = config('view.uploads')[$queryName] . "/produit_" . $code . "_" . $dateHeure . ".png";
        $img_file = strtolower($img_file);

        // Save the GD resource as PNG in the best possible quality (no compression)
        // This will strip any metadata or invalid contents (including, the PHP backdoor)
        // To block any possible exploits, consider increasing the compression level
        $retour = imagepng($im, $img_file, 0);
        if ($retour == 1 || $retour == true) {
            $retour = $img_file;
        } else {
            $retour = 'assets/images/default.png';
        }

        return $retour;
    }

    //ENvoir sur le front pour déconnecter le client
    public static function retourPourDeconnecterClient()
    {
        $retour = array(
            "dataone" => null,
            "deconnect" => "Votre session a été déconnectée car votre compte est en train d'être utilisé sur un autre support",
        );
        return $retour;
    }

    public static function donneTypeTvaClient($client_id = null)
    {
        $avec_tva = 1;
        if (isset($client_id)) {
            $item = Client::find($client_id);
            if (isset($item)) {
                $avec_tva = $item->avec_tva;
            }
        }
        return $avec_tva;
    }

    public static function calculTotalTtc($totalHt)
    {
        $tva = 18;
        $retour = $totalHt * (1 + ($tva / 100));
        $retour = round($retour);
        return $retour;
    }

    public static function calculTotalSansTva($totalTtc)
    {
        $tva = 18;
        $totalHt = 0;
        $diviseur = 1 + ($tva / 100);
        if ($diviseur > 0) {

            $totalHt = $totalTtc / $diviseur;
        }

        $retour = round($totalHt);
        return $retour;
    }

    public static function calculTotalTva($totalTtc, $totalHt)
    {
        $totalTva = $totalTtc - $totalHt;
        $retour = round($totalTva);
        return $retour;
    }

    //Total avec montant livraison
    public static function calculTotalGlobal($totalTtc, $zone_livraison_prix)
    {
        $retour = $totalTtc + $zone_livraison_prix;
        return $retour;
    }

    // static function uploadFile($request, $inputName, $uploadPath)
    // {
    //     if ($request->hasFile($inputName)) {
    //         $documentFile = $request->file($inputName);
    //         $originalName = explode(".", $documentFile->getClientOriginalName());
    //         $fileName = $originalName[0] . '_' . uniqid() . '.' . $documentFile->getClientOriginalExtension();
    //         $filePath = $uploadPath . '/' . $fileName;

    //         if (file_put_contents($filePath, file_get_contents($documentFile))) {
    //             return [
    //                 "path" => $filePath,
    //                 "name" => $fileName,
    //             ]; // Retourne le nom du fichier téléchargé avec succès
    //         }
    //     }

    //     return null; // Aucun fichier n'a été téléchargé
    // }

    static function uploadFile($request, $inputName, $uploadPath)
    {
        if ($request->hasFile($inputName)) {
            $documentFile = $request->file($inputName);
            $originalName = explode(".", $documentFile->getClientOriginalName());
            $fileName = $originalName[0] . '_' . uniqid() . '.' . $documentFile->getClientOriginalExtension();
            $filePath = $uploadPath . '\\' . $fileName; // Utilisez '\\' comme séparateur de répertoire sous Windows

            if ($documentFile->move($uploadPath, $fileName)) {
                return [
                    "path" => $filePath,
                    "name" => $fileName,
                ]; // Retourne le nom du fichier téléchargé avec succès
            } else {
                return null; // Échec de déplacement du fichier
            }
        }

        return null; // Aucun fichier n'a été téléchargé
    }


    static function resolveAllDateFR($date)
    {
        $date_at = $date;
        if ($date_at !== null) {
            $date_at = $date_at;
            $date_at = date_create($date_at);
            return date_format($date_at, "d/m/Y");
        } else {
            return null;
        }
    }
}
