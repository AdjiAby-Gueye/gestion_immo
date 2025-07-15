<?php

namespace App\Helpers;

use DateTime;
use App\Outil;
use App\Entite;
use App\Contrat;
use App\Periode;

use Carbon\Carbon;
use App\Appartement;
use App\Periodicite;
use App\Avisecheance;
use App\Modepaiement;
use App\Paiementecheance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Countries\Package\Countries;


class MyHelper
{

    public static function countries()
    {
        $allCountries = (new Countries())->all();

        $countries = $allCountries->map(function ($country) {
            return [
                'name' => $country->name->common,
                'flag' => $country->flag['flag-icon'],
            ];
        })->values();

        return $countries;
    }

    public static function months()
    {
        $months = Periode::all();

        return $months;
    }

    public static function outil_by_graphql($filters = null, $type=null)
    {
        $data           =  Outil::getAllItemsWithGraphQl($type, $filters);

        $retour = array(
            'item'                          => '',
            'data'                          => $data,
        );
        return $retour;
    }


    public static function configPdfSendAvisGest($data)
    {
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        info('Observer data : ' . $data);
        $pdfFileName =  'pdfecheanceencours.pdf';
        info('Observer filename : ' . $pdfFileName);
        $pdf->loadView('pdfs.pdfecheanceencours', ['data' => $data]);
        info('Observer filename 2 : ' . $pdfFileName);
        $pdfFilePath = public_path("uploads/echeances/pdfecheanceencours.pdf");
        $pdf->save($pdfFilePath);
        info('Fichier PDF sauvegardé : ' . $pdfFilePath);
        return $pdfFilePath;
    }

    public static function configPdfSendAvisToReservataire($avis)
    {
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        info('Observer data avis : ' . $avis);
        $pdfFileName =  'pdfavisecheance.pdf';
        info('Observer filename : ' . $pdfFileName);
        $pdf->loadView('pdfs.pdfavisecheance', ['data' => $avis]);
        info('Observer filename 2 : ' . $pdfFileName);
        $pdfFilePath = public_path("uploads/echeances/pdfoneavisecheancereservataire.pdf");
        $pdf->save($pdfFilePath);
        info('Fichier PDF sauvegardé : ' . $pdfFilePath);
        return $pdfFilePath;
    }


    public static function rappelEcheance()
    {
        info('rappel echeance : ');
        // Récupère les avis non réglés
        $avisNonRegles =
            // Avisecheance::where([['est_activer', 1],['est_signer' , 1]])
            Avisecheance::where('est_activer', 1)
            ->with("contrat")
            ->whereDate('date_echeance', '<=', now())
            ->get();
        $entite =  Entite::where("code", "RID")->first();
        if ($entite) {
            if (count($avisNonRegles) > 0) {
                $gestionnaireEmail = $entite->gestionnaire->email;
                $ccopiesEmail = [];
                $raf = null;
                foreach ($entite->usersentite as $user) {
                    if ($user->id !=  $entite->gestionnaire->id) {
                        $ccopiesEmail[] = $user->email;
                    }
                }
//                $ccopiesEmail[] = "libassedev@gmail.com";
                $ccopiesEmail[] = "abou050793@gmail.com";
//                $ccopiesEmail[] = "mansourpouye36@gmail.com";
                $pdfFilePath = self::configPdfSendAvisGest($avisNonRegles);
                $message = "Bonjour,\n\nVoici la liste des avis non réglés dont la date d'échéance est passée.\n";
                $message .= "Le détail de chaque avis est inclus dans le document PDF joint.\n\n";
                Outil::envoiEmail($gestionnaireEmail, "Notification d'avis non réglé", $message, 'maileur', null, $ccopiesEmail, [$pdfFilePath], null);
                // Envoie des e-mails pour chaque avis non réglé
            }
        }
    }



    public static function relancePaiementRIDV2()
    {
        // Récupérez les avis d'échéance avec les contrats associés
        $avisEcheance = Avisecheance::where('est_activer', 1)
            ->where("est_signer",1)
            ->with("contrat")
            ->whereDate('date_echeance', '<=', now())
            ->get();
        foreach ($avisEcheance as $avis) {
            $dateEcheance = Carbon::parse($avis->date_echeance);
            // Calculer la différence de jours entre aujourd'hui et la date d'échéance
            $joursAvantEcheance = $dateEcheance->diffInDays(now(), false);
            $emailClient = $avis->contrat->locataire->email;
            // $emailClient = "libassedev@gmail.com";
            info("Calculer la différence de jours : $joursAvantEcheance");
            info("date échéance : $dateEcheance");
            $pdfFilePath = self::configPdfSendAvisToReservataire($avis);
            // Vérifier le type de relance à envoyer
            if ($joursAvantEcheance > 5 && $joursAvantEcheance <= 10) {
                // Premier rappel entre 5 et 10 jours avant la date d'échéance
                $message = self::getMessageFirstRappelRID($avis->contrat->appartement,$avis->periodes);
                Outil::envoiEmail($emailClient, "Rappel d'Avis d'échéance non réglé", $message,'maileur',null,null,[$pdfFilePath]);
            } else {
                // Deuxième rappel 8 jours après le premier rappel
                $datePremierRappel = $dateEcheance->copy()->addDays(5);
                $dateDeuxiemeRappel = $datePremierRappel->copy()->addDays(8);
                info("datePremierRappel: $datePremierRappel");
                info("dateDeuxiemeRappel: $dateDeuxiemeRappel");
                if (now() >= $dateDeuxiemeRappel) {
                    $message = self::getMessageSecondRappelPaiementRID($avis->contrat->appartement,$avis->periodes);
                    Outil::envoiEmail($emailClient, "Rappel d'Avis d'échéance non réglé ", $message);
                }
            }

        }
    }

    static function getMessageFirstRappelRID($appartement,$periodes): string {
        $date = date('d');
        $year = date('Y');
        $text = "Bonjour \n Nous sommes le $date du mois et le(s) loyer(s) de $periodes ";
        if (isset($appartement->ilot) && isset($appartement->ilot->id)) {
            $text .= " $year pour la villa que nous vous louons à " . $appartement->ilot->adresse . " / lot N° " . $appartement->lot . "/ ilot N° " . $appartement->ilot->numero;
        }
        $text .= " ne sont toujours pas réglés. \n";
        $text .= "Merci de prendre les dispositions nécessaires pour un règlement immédiat. \n";
        $text .= "Ci-joint le document pdf de l'avis.";
        return $text;

    }

    static function getMessageSecondRappelPaiementRID(): string
    {
        $message = "Bonjour cher client,\n
        Pour non règlement de votre loyer à date échue, une pénalité de 10% du montant vous sera appliquée à votre prochain paiement.";
        return $message;

    }


    public static function rappelForRafAfterTwoDays() {
        $echeances = Avisecheance::where([['est_activer',1],['est_signer',0]])->get();
        $count = 0;
        foreach ($echeances as $avis) {
            $createdAt = Carbon::parse($avis->date);
            // Calculer la différence de jours entre aujourd'hui et la date de creation
            $jours = $createdAt->diffInDays(now(), false);
            info(" date $createdAt");
            info("diff days info $jours");
            if ($jours >= 2) {
                $count++;
            }
        }
        if ($count > 0) {
            $entite = Entite::where("code" , "RID")->first();
            $ccopiesEmail = [];
            $raf = null;
            foreach ($entite->usersentite as $user) {
                if ($user->roles[0]->name == "RAF") {
                    $raf = $user->email;
                } else {
                    $ccopiesEmail[] = $user->email;
                }
            }
            $ccopiesEmail[] = "libassedev@gmail.com";
            $ccopiesEmail[] = "abou050793@gmail.com";
            $ccopiesEmail[] = "mansourpouye36@gmail.com";
            $pdfFilePath = self::configPdfSendAvisGest($echeances);
            $message = "Bonjour,\n\nNous vous rappelons que certains avis d'échéances n'ont pas encore été signés. Veuillez prendre un moment pour les examiner et apposer votre signature dès que possible.\n\nNous avons également attaché un récapitulatif de tous les avis non signés pour votre référence.\n\nMerci de votre attention.";
            Outil::envoiEmail($raf, "Rappel d'avis d'échéances non signés", $message, 'maileur', null, $ccopiesEmail, [$pdfFilePath], null);
        }

    }


    public static function saveAvisAndPaiementScript($data) {

        try {
            DB::beginTransaction(); // <= Starting the transaction

            if (isset($data) && count($data) > 0) {
                 $count =0;
                foreach($data as $avis) {
                    $count = $count + 1;
                    $lot = trim($avis[0]);
                    $appartement = Appartement::where("lot" , $lot)->first();
                    $nbr = null;
                    if (isset($appartement) && isset($appartement->id)) {
                        $contrat = Contrat::where("appartement_id" , $appartement->id)->first();
                        if (isset($contrat) && isset($contrat->id)) {

                            $user_connected = Auth::user();
                            $obj = new Avisecheance();
                            $obj->contrat_id = $contrat->id;
                            $periodicite = Periodicite::where("nbr_mois" , 1)->first();
                            $obj->periodicite_id = isset($periodicite) && isset($periodicite->id) ? $periodicite->id  : null;
                            $obj->periodes = $avis[8];
                            // $nbr = $avis[10];
                            $obj->fraisgestion = trim($avis[5]);
                            $obj->amortissement = trim($avis[2]);
                            $obj->fraisdelocation = trim($avis[3]);
                            $date = explode(" ",$avis[9]);
                            $createdate = Carbon::create($date[2], $date[1], $date[0], 0);
                            // $createdate2 = Carbon::create($date[2], $date[1], $date[0], 0);
                            // $newdate = $createdate->subMonth();
                            $obj->date = $createdate->toDateString();
                            $obj->objet = "Loyer ".$avis[8];
                            $obj->date_echeance = $createdate->addDays(10);
                            $obj->created_at_user_id = (isset($user_connected) && isset($user_connected->id)) ? $user_connected->id : null;
                            $obj->save();

                            if (isset($obj) && isset($obj->id)) {

                                $pay = new Paiementecheance();
                                $pay->numero = self::generateCodeFacture();
                                $pay->avisecheance_id =  $obj->id ;
                                $pay->montant = self::sumAmountAvis($obj);
                                $pay->date = $createdate->toDateString();
                                $pay->periodes = $obj->periodes ;
                                $modePaiement = Modepaiement::where("code" , "AUTRES")->first();
                                $pay->modepaiement_id = isset($modePaiement) && isset($modePaiement->id) ? $modePaiement->id : null;
                                $pay->created_at_user_id = (isset($user_connected) && isset($user_connected->id)) ? $user_connected->id : null;
                                $pay->receipt_number = $count;
                                $pay->save();
                                if (isset($pay) && isset($pay->id)) {
                                    $obj->est_activer = 2;
                                    $obj->save();
                                }

                            }
                        }
                    }

                }
            }

            DB::commit(); // <= Commit the changes
        } catch (\Exception $e) {

            report($e);
            DB::rollBack(); // <= Rollback in case of an exception
            dd($e);
        }


    }

    public static function deleteAvisIntervaldate($id) {
        try {

            $contrat = Contrat::find($id);
            if(isset($contrat) && isset($contrat->id)) {
                $from = date('2024-02-05');
                $to = date('2023-01-05');
                $avisAsupprimer = Avisecheance::join("paiementecheances", "avisecheances.id", "=", "paiementecheances.avisecheance_id")
                                ->where('avisecheances.contrat_id', $id)
                                ->whereBetween('avisecheances.date' ,[$from, $to])
                                ->select("avisecheances.*")->get();
                foreach($avisAsupprimer as $avis) {
                    $avis->Paiementecheance()->delete();
                    $avis->delete();
                    $avis->forceDelete();
                }
                //  dd($avisAsupprimer);
                return response()->json(["data" => ["data" => 1], "errors" => null]);
            }


        }catch (\Exception $e) {
            report($e);
            return response()->json(["data" => ["data" => null], "errors" => $e]);
        }
    }

    public static function deleteAvispaye($id) {
        try {

            $avis = Avisecheance::join("paiementecheances", "avisecheances.id", "=", "paiementecheances.avisecheance_id")
                ->where('avisecheances.id', $id)
                ->select("avisecheances.*")->first();

                $avis->Paiementecheance()->delete();
                $avis->delete();
                $avis->forceDelete();

            return response()->json(["data" => ["data" => 1], "errors" => null]);


        }catch (\Exception $e) {
            report($e);
            return response()->json(["data" => ["data" => null], "errors" => $e]);
        }
    }

    public static function orderReceipNumber($contratId) {


        try {
            // DB::beginTransaction(); // <    = Starting the transaction

            $contrat = Contrat::find($contratId);
            if(isset($contrat) && isset($contrat->id))  {
                $paiementEcheances = Paiementecheance::join("avisecheances", "avisecheances.id", "=", "paiementecheances.avisecheance_id")
                                        ->where("avisecheances.contrat_id" , $contratId)
                                        ->orderBy('avisecheances.date' , 'asc')
                                        ->select("paiementecheances.*")->get();
                $count = 0;
               foreach($paiementEcheances as $pay) {
                    $count++;
                    $pay->receipt_number = $count;
                    $pay->save();
               }
            //    dd($count);
            }


            // DB::commit(); // <= Commit the changes
            return response()->json(["data" => ["data" => 1], "errors" => null]);
        } catch (\Exception $e) {

          // <= Rollback in case of an exception
            return response()->json(["data" => ["data" => null], "errors" => $e]);
        }


    }
    public static function generateCodeFacture()
    {

        $chars = "023456789";
        srand((float)microtime() * 1000000);
        $i = 0;
        $pass = '';

        while ($i <= 10) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }

        return $pass;
    }

    public function nextReceiptNumber($contrat):int
    {
        // Obtenez le dernier paiement avec un numéro de reçu
        $avis = Avisecheance::join("paiementecheances", "avisecheances.id", "=", "paiementecheances.avisecheance_id")
                                ->where('avisecheances.contrat_id', $contrat)
                                ->latest('avisecheances.date')
                                ->select("avisecheances.*")
                                    ->first();
        $latestPayment = null;
        $nextReceiptNumber = 1;
        if (isset($avis) && isset($avis->id)) {
            $latestPayment = $avis->paiementecheance;
            $nextReceiptNumber = ($latestPayment) ? $latestPayment->receipt_number + 1 : 1;
        }

        return $nextReceiptNumber;
    }
    public static function sumAmountAvis(Avisecheance $avis):int {
        $sum = intval($avis->amortissement) + intval($avis->fraisgestion) + intval($avis->fraisdelocation);
        return $sum;
    }
}
