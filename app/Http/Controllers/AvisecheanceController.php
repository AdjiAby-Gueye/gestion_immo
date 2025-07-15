<?php

namespace App\Http\Controllers;

use App\Fraisupplementaire;
use App\Outil;
use App\Paiementecheance;
use Throwable;
use App\Entite;
use App\Contrat;
use App\Periode;
use Carbon\Carbon;
use App\Periodicite;
use App\Avisecheance;
use App\Helpers\MyHelper;
use Illuminate\Http\Request;
use App\Jobs\ImportUserFileJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\SaveModelController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AvisecheanceController extends SaveModelController
{
    //
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "avisecheances";
    protected $model = Avisecheance::class;
    protected $job       = ImportUserFileJob::class;


    public function save(Request $request)
    {

        try {
            return DB::transaction(
                function () use ($request) {
                    $errors = null;
                    $ccopiesEmail = [];
                    $avis = new Avisecheance();
                    $user_connected = Auth::user();

                    if (isset($request->id)) {
                        if (is_numeric($request->id) === true) {
                            $avis = Avisecheance::find($request->id);

                            if (!$avis) {
                                $retour = array(
                                    "data" => null,
                                    "error" => "Le paiement que vous tentez de modifier n'existe pas ",
                                );
                                return $retour;
                            }
                        } else {
                            $retour = array(
                                "data" => null,
                                "error" => "L'id doit être un nombre entier",
                            );
                            return $retour;
                        }
                    }

                    // dd($request->periodes);


                    $periodicite = $this->validateObject($request, Periodicite::class, 'periodicite');
                    if (is_string($periodicite)) {
                        $errors =  $periodicite;
                    }else {
                        if (isset($periodicite) && isset($periodicite->id)) {
                            if (isset($request->periodes)) {
                                $type = $periodicite->nbr_mois;
                                switch ($type) {
                                    case 1:
                                        if (count($request->periodes) > 1) {
                                            $errors = "veuillez séléctionner une seule période car la périodicité est mensuelle.";
                                        }
                                        break;
                                    case 2:
                                        if (count($request->periodes) != 2) {
                                            $errors = "veuillez séléctionner deux périodes car la périodicité est bimensuelle.";
                                        }
                                        break;
                                    case 3:
                                        if (count($request->periodes) != 3) {
                                            $errors = "veuillez séléctionner trois périodes car la périodicité est trimestrielle.";
                                        }
                                        break;
                                    default:
                                        $errors = "une erreur est survenue";
                                        break;
                                }
                            }

                        }
                    }

                    $contract = $this->validateObject($request, Contrat::class, 'contrat');
                    if (is_string($contract)) {
                        $errors = $contract;
                    }

                    // $periode = Periode::where("designation" , $request->periodes)->first();
                    // if (!isset($periode) && !isset($periode->id)) {
                    //     $errors = "Veuillez renseigner une période valide";
                    // }


                    if (empty($request->objet)) {
                        $errors = "Veuillez renseigner l'objet de l'avis d'échéance";
                    }
                    if (empty($request->date)) {
                        $errors = "Veuillez renseigner la date de l'avis d'échéance";
                    }
                    if (empty($request->dateecheance)) {
                        $errors = "Veuillez renseigner la date d'échéance";
                    }

                    if (empty($request->amortissement)) {
                        $errors = "Veuillez renseigner le quote part amortissement ";
                    }else if (!is_numeric($request->amortissement)) {
                        $errors = "Le champs quote part amortissement doit être un entier";
                    }

                    if (empty($request->fraisgestion)) {
                        $errors = "Veuillez renseigner les frais de gestion ";
                    }else if (!is_numeric($request->fraisgestion)) {
                        $errors = "Le champs frais de gestion doit être un entier ";
                    }
                    if (empty($request->fraisdelocation)) {
                        $errors = "Veuillez renseigner les frais de location ";
                    }else if (!is_numeric($request->fraisdelocation)) {
                        $errors = "Le champs frais de location doit être un entier ";
                    }
                    if (empty($request->periodes)) {
                        $errors = "Veuillez renseigner la periode de l'échéance";
                    }
                    if (!isset($request->periodes)) {
                        $errors = "Veuillez renseigner une ou des période(s) valide(s)";
                    }

                    // $existingAvis = Avisecheance::where('date_echeance', $request->dateecheance)->first();
                    // if ($existingAvis) {

                    //     $errors = "Une échéance avec cette date d'échéance existe déjà.";

                    // }
                    $currentYear = isset($request->date) ? date('Y', strtotime($request->date)) : Carbon::now()->year;

                    $existingAvis  = $implodePeriodes = null;
                    if (isset ($request->periodes)) {
                        $implodePeriodes = count($request->periodes) > 1 ? implode(',',$request->periodes) : $request->periodes[0];
                    }
                    if (isset($request->periodes)) {

                        $existingAvis = Avisecheance::where('contrat_id', $request->contrat)
                        ->whereIn('periodes', $request->periodes)
                        ->whereYear('date_echeance', $currentYear)
                        ->first();
                    }


                    if ($existingAvis) {

                        $errors = "Une échéance avec la ou l'une des période(s) dans cette année existe déjà.";

                    }
                    $fraissupplementaires = isset($request->fraissupplementaire) ? json_decode($request->fraissupplementaire, true) : [];

                    if (!isset($errors)) {

                        $avis->contrat_id = $contract->id;
                        $avis->periodicite_id = $periodicite->id;
                        $avis->periodes = $implodePeriodes;
                        $avis->objet = $request->objet;
                        $avis->fraisgestion = $request->fraisgestion;
                        $avis->amortissement = $request->amortissement;
                        $avis->fraisdelocation = $request->fraisdelocation;
                        $avis->date = $request->date;
                        $avis->date_echeance = $request->dateecheance;
                        $avis->code_avis = isset($request->code_avis) ? $request->code_avis :  null;
                        $avis->created_at_user_id = (isset($user_connected) && isset($user_connected->id)) ? $user_connected->id : null;
                        $avis->save();

                        if(isset($fraissupplementaires) && count($fraissupplementaires) > 0){
                            $oldFrais = Fraisupplementaire::where('avisecheance_id', $avis->id);
                            if(isset($oldFrais)){
                                $oldFrais->delete();
                                $oldFrais->forceDelete();
                            }
                            //dd($fraissupplementaires);
                            foreach ($fraissupplementaires as $key=>$frai)
                            {
                                $frais = new Fraisupplementaire();

                                $frais->designation     = $frai['designation'];
                                $frais->frais           = (int)$frai['frais'];
                                $frais->avisecheance_id = $avis->id;
                                $frais->save();
                            }
                        }

                        if (!$errors) {
                            return Outil::redirectgraphql($this->queryName, "id:{$avis->id}", Outil::$queries['avisecheances2']);
                        }
                    }
                    throw new \Exception($errors);
                }
            );
        } catch (\Exception $th) {
            return Outil::getResponseError($th);
        }
    }

    public function annulerPaiment(Request $request) {
        // dd($request);
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $data = null;
                $item = null;
                $item2 = null;
                $user_connected = Auth::user();

                // dd($request);

                if (isset($request->echeance)) {
                    if (is_numeric($request->echeance) == true) {
                        $item2 = Paiementecheance::find($request->echeance);
                        $item = Avisecheance::find($item2->avisecheance_id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "L'avis d'echeance que vous tentez de valider n'existe pas ",
                            );
                            return $retour;
                        }
                    } else {
                        $retour = array(
                            "data" => null,
                            "error" => "L'id doit être un nombre entier",
                        );
                        return $retour;
                    }
                }

                if (empty($request->etat)) {
                    $errors = "etat est requis.";
                }else if(isset($request->etat) && $request->etat == 1) {
                    if (empty($request->date)) {
                        $errors = "La date d'annulation est obligatoire.";
                    }
                    if(empty($request->motif)) {
                        $errors = "Le motif d'annulation est obligatoire.";
                    }
                }




                if (!isset($errors)) {

                    $entite = Entite::where("code" , "RID")->first();
                    $gestionnaire = $entite->gestionnaire;

                    // est_active :
                    // 1 : non payé; 2 :payé ; 3: paiement annulé

                    // ? etat == 1 : mode annulation paiement
                    if ($request->etat == 1) {

                        //mis a jour ligne paiement au niveu de son champ etat
                        $item2->etat = -1;
                        $item2->save();
                        // dd($item2->modepaiement->code);

                        if($item2->modepaiement && $item2->modepaiement->code === "CC"){
                            $montantpaiement = $item2->montant;

                            if ($item->contrat && $item->contrat->locataire) {
                                $locataireCompteClient = $item->contrat->locataire->id;
                            }

                            if($montantpaiement && $locataireCompteClient){
                                //dans le cas ou on annule un paiement qui a ete faite avec le compte client
                                Outil::addCompteClient($montantpaiement,$locataireCompteClient,$item2->id);
                            }
                        }

                        $montantFacture = (int) str_replace(' ', '', $item->montant_total);
                        $montantenattente = Outil::GetMontantenattente($item->id,$montantFacture);
                        // dd($montantenattente);
                        if($montantenattente < $montantFacture){
                            $item->est_activer = 4; // paiement partiel
                        }else{
                            $item->est_activer = 1;
                        }

                        $item->motif_annulation_paiement = $request->motif;
                        $item->date_annulation_paiement = $request->date;
                    }else {
                        if($item2->modepaiement && $item2->modepaiement->code === "CC"){
                            $montantpaiement = $item2->montant;

                            if ($item->contrat && $item->contrat->locataire) {
                                $locataireCompteClient = $item->contrat->locataire->id;
                                $soldeclient = Outil::Soldeclient($locataireCompteClient);
                            }

                            if($montantpaiement && $locataireCompteClient){
                                if ($soldeclient < $montantpaiement){
                                    $errors = "Solde insuffisant pour reactiver le paiement";
                                }else{
                                    //dans le cas ou on reactive un paiement qui a ete faite avec le compte client
                                    Outil::addCompteClient((-1)*$montantpaiement,$locataireCompteClient,$item2->id);

                                    //mis a jour ligne paiement au niveu de son champ etat
                                    $item2->etat = null;
                                    $item2->save();
                                }
                            }
                        }

                        // //mis a jour ligne paiement au niveu de son champ etat
                        // $item2->etat = null;
                        // $item2->save();

                      //On se base pas seulement sr le nombre de paiement mais le montant en attente
                      $montantFacture = (int) str_replace(' ', '', $item->montant_total);
                      $montantenattente = Outil::GetMontantenattente($item->id,$montantFacture);

                      //si c'est 0, total regle, si c'est inferieur a totalFacture et different 0 c'est partiel,
                        if($montantenattente === 0){
                        $item->est_activer = 2; // paiement reglé
                        }else if ($montantenattente > 0 && $montantenattente < $montantFacture){
                            $item->est_activer = 4; // paiement partiel
                        }
                    }

                    $ccopiesEmail = [];

                    foreach ($entite->usersentite as $user) {
                        if ($user->id !=  $gestionnaire->id) {
                            $ccopiesEmail[] = $user->email;
                        }
                    }



                    if (!$errors) {
                        $item->updated_at_user_id = (isset($user_connected) && isset($user_connected->id)) ? $user_connected->id : null;
                        $item->save();

                        $data = 1;
                        if ($gestionnaire) {

                            $locataire = $lot = $ilot = "";
                            if ($item->contrat->locataire && $item->contrat->locataire->nomentreprise){
                                $locataire = $item->contrat->locataire->nomentreprise;
                            }
                            elseif ($item->contrat->locataire && $item->contrat->locataire->nom)
                            {
                                $locataire = $item->contrat->locataire->prenom.' '.$item->contrat->locataire->nom;
                            }


                            if ($item->contrat->copreneur && $item->contrat->copreneur->id){
                                $locataire.=$item->contrat->copreneur->prenom ." " .$item->contrat->copreneur->nom;
                            }

                            if ( $item->contrat->appartement &&  $item->contrat->appartement->lot) {
                                $lot = $item->contrat->appartement->lot;
                            }
                            if ($item->contrat->appartement->ilot && isset($item->contrat->appartement->ilot->id) ) {
                                $ilot = $item->contrat->appartement->ilot->numero." , Adresse :".  $item->contrat->appartement->ilot->adresse;
                            }


                            $firsttext = "Détails \n";
                            $firsttext.="Réservataire : ".$locataire."\n";
                            $firsttext.="Lot : N° ".$lot."\n";
                            $firsttext.="Ilot : N° ".$ilot."\n";
                            if ($request->etat == 1) {
                                $firsttext.="Date d'annulation : ".Outil::resolveAllDateFR($request->date)." \n";
                                $firsttext.="Motif : ".$request->motif;
                            }

                            if ($request->etat == 1) {
                                # code...
                                Outil::envoiEmail(
                                    $gestionnaire->email, "Notification d'annulation de paiement d'avis",
                                 "Bonjour, un paiement d'avis d'échéance viens d'etre annuler dont détails ci-dessous.\n".$firsttext
                                 ,'maileur' , null , $ccopiesEmail);

                            }
                            if ($request->etat == 2) {
                                # code...
                                Outil::envoiEmail(
                                    $gestionnaire->email, "Notification de réactivation de paiement d'avis",
                                 "Bonjour, un paiement d'avis d'échéance viens d'etre réactiver dont détails ci-dessous.\n".$firsttext
                                 ,'maileur' , null , $ccopiesEmail);

                            }
                        }

                    }

                }
                // dd("iciciic " , $data);

                return response()->json(["data" => ["data" => $data], "errors" => $errors]);
            });
        } catch (\Exception $e) {

            return response()->json(["data" => ["data" => null], "errors" => $e]);
        }
    }
    public function signature(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $data = null;
                $item = null;

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Avisecheance::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "L'avis d'echeance que vous tentez de valider n'existe pas ",
                            );
                            return $retour;
                        }
                    } else {
                        $retour = array(
                            "data" => null,
                            "error" => "L'id doit être un nombre entier",
                        );
                        return $retour;
                    }
                }

                if (empty($request->signature)) {
                    $errors = "Le signature du caissier est requis.";
                }

                if (!isset($request->param_sign)) {
                    $errors = "is_uploading est requis.";
                }


                if (!isset($errors)) {

                    $entite = Entite::where("code" , "RID")->first();
                    $gestionnaire = $entite->gestionnaire;

                    if ($request->param_sign == false) {
                        $signature = $this->upload($request->signature);
                    }else {
                        $signature = $request->signature ;
                    }

                    $item->signature     = $signature;
                    $item->est_signer    = 1;
                    $item->datesignature = now();

                    $ccopiesEmail = [];
                    foreach ($entite->usersentite as $user) {
                        if ($user->id !=  $gestionnaire->id) {
                            $ccopiesEmail[] = $user->email;
                        }
                    }

                    $item->save();

                    if (!$errors) {

                        $data = 1;
                        if ($gestionnaire) {
                            Outil::envoiEmail($gestionnaire->email, "Notification signature d'avis d'échéance", "Bonjour, le caissier vient de valider le nouveau avis soumis.",'maileur' , null , $ccopiesEmail);
                        }

                    }

                }
                // dd("iciciic " , $data);

                return response()->json(["data" => ["data" => $data], "errors" => $errors]);
            });
        } catch (\Exception $e) {
            return response()->json(["data" => ["data" => null], "errors" => $e]);
        }
    }
    public function upload($signature ,  $folderPath = "uploads/echeances/") {

        $base64Image = explode(";base64,", $signature);
        $explodeImage = explode("image/", $base64Image[0]);
        $imageType = $explodeImage[1];
        $image_base64 = base64_decode($base64Image[1]);
        $file = $folderPath . "signature_" . uniqid() . '.' . $imageType;
        file_put_contents($file, $image_base64);
        return $file;
    }

    public function orderRecipNumber($id) {
        return MyHelper::orderReceipNumber($id);

    }

    public function deleteAvisIntervaldate($id) {

        return Myhelper::deleteAvisIntervaldate($id);
    }



    // public function pdfnumberpage($data, $page, $customPaper = null)
    // {
    //     $pdf = App::make('dompdf.wrapper');
    //     $pdf->getDomPDF()->set_option("enable_php", true);
    //     $pdf->loadView('pdfs.' . $page, $data);
    //     if (isset($customPaper)) {
    //         $pdf->setPaper($customPaper);
    //     }
    //     return $pdf->stream($page . '.pdf');
    // }

    // public function generate_pdf_one_avis_echeance($id)
    // {

    //     $data  = self::outil_facture("id:" . $id, "avisecheances");
    //     //    dd($data);
    //     $customPaper = array(0, 0, 700, 900);
    //     return self::pdfnumberpage($data, 'pdfavisecheance', $customPaper);
    // }

    // public function outil_facture($filters = null, $type)
    // {
    //     $data           =  Outil::getAllItemsWithGraphQl($type, $filters);

    //     $retour = array(
    //         'item'                          => '',
    //         'data'                          => $data,
    //     );
    //     return $retour;
    // }
}
