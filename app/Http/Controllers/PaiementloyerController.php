<?php

namespace App\Http\Controllers;


use App\User;
use App\Outil;
use App\Entite;
use App\Avenant;
use App\Contrat;
use App\Immeuble;
use Carbon\Carbon;
use App\Appartement;
use App\Factureeaux;
use App\Periodicite;
use App\Modepaiement;
use App\DomaineDetude;
use App\Paiementloyer;
use App\Detailpaiement;
use App\Facturelocation;
use App\Typeappartement;
use App\UserDepartement;
use App\Pieceappartement;
use Illuminate\Http\Request;
use App\Jobs\ImportUserFileJob;
use App\Jobs\ImportEntiteFileJob;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class PaiementloyerController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "paiementloyers";
    protected $model = Paiementloyer::class;
    protected $job = ImportUserFileJob::class;


    function generateCodeFacture()
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

    public function save(Request $request)
    {
        //  dd($request->all());
        try {

            return DB::transaction(function () use ($request) {
                $is_update = false;

                $errors = null;
                $user_connected = Auth::user();

                $factureLoyerObj = null;
                $factureEauxObj = null;
                //  dd($request) ;
                $item = new Paiementloyer();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Paiementloyer::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "Le paiement que vous tentez de modifier n'existe pas ",
                            );
                            return $retour;
                        }
                        $is_update = true;
                    } else {
                        $retour = array(
                            "data" => null,
                            "error" => "L'id doit être un nombre entier",
                        );
                        return $retour;
                    }
                }


                // $paiement_loyer = json_decode($request->periodepaiementloyer, true);

                $appartement = $this->validateObject($request, Appartement::class, 'appartement');
                if (is_string($appartement)) {
                    $errors = $appartement;
                }

                $contrat = $this->validateObject($request, Contrat::class, 'contrat');
                if (is_string($contrat)) {
                    $errors = $contrat;
                }

                $modepaiement = $this->validateObject($request, Modepaiement::class, 'modepaiement');
                if (is_string($modepaiement)) {
                    $errors = $modepaiement;
                }

                $periodicite = $this->validateObject($request, Periodicite::class, 'periodicite');
                if (is_string($periodicite)) {
                    $errors =  $periodicite;
                }

                if (empty($request->datepaiement)) {
                    $errors = "Veuillez renseigner la date de paiement";
                }
                if (empty($request->montantfacture)) {
                    $errors = "Veuillez renseigner le montant de la facture";
                }


                if (isset($request->factureeaux_id)) {
                    $factureEauxObj = Factureeaux::find($request->factureeaux_id);
                    if (isset($periodicite) && isset($periodicite->id)) {
                        $nbrMois = $periodicite->nbr_mois;
                        if($nbrMois != 1) {
                            $errors = "Veuillez sélectionnez la périodicité mensuelle pour les paiements de facture d'eaux.";
                        }
                    }
                }
                $typeFactureObj = null;
                if ($request->facturelocation_id) {
                    $facture = Facturelocation::find($request->facturelocation_id);
                    $factureLoyerObj = $facture;
                    if ($facture != null) {
                        $typeFactureObj = $facture->typefacture;
                    }
                }

                // if ($contrat && $contrat->etat != 2 ) {
                //     $errors = "Veuillez valider le contrat";
                // }
                // $idsP = [];
                // $idsP = array_map(function ($pay) {
                //     return $pay['periode_id'];
                // }, $paiement_loyer);
                $typeF = isset($typeFactureObj) ? $typeFactureObj->designation : "eaux";

                // $currentYear = explode("-", $request->datepaiement)[0];
                // $existingDetails = Detailpaiement::whereIn('periode_id', $idsP)
                //     ->where("contrat_id", $contrat->id)
                //     ->where("type", $typeF)
                //     ->whereYear("date_paiement", $currentYear)->first();
                // if ($existingDetails != null) {
                //     $errors = "Un paiement avec l'une des périodes dans cette année existe déjà.";
                // }

                if (!isset($errors)) {

                    if (!$is_update) {
                        $codeFacture = $this->generateCodeFacture();
                        $item->codefacture = $codeFacture;
                    }

                    // TODO: numero_cheque devient reference de paiement
                    $item->numero_cheque = isset($request->numero_cheque) ? $request->numero_cheque :  null;

                    $item->facturelocation_id = ($request->facturelocation_id) ? $request->facturelocation_id : null;
                    $item->factureeaux_id = ($request->factureeaux_id) ? $request->factureeaux_id : null;
                    $item->montantfacture = $this->calculeMontantFacture($request,$contrat,$periodicite);
                    $item->locataire_id = $contrat->locataire_id;
                    $item->contrat_id = $contrat->id;
                    $item->datepaiement = $request->datepaiement;
                    $item->modepaiement_id = $modepaiement->id;
                    $item->debutperiodevalide = 'neant';
                    $item->finperiodevalide = 'neant';
                    $item->created_at_user_id = (isset($user_connected) && isset($user_connected->id)) ? $user_connected->id : null;
                    $item->est_activer =  2;
                    $this->saveJustificatifAndReceipNumber($item, $request);
                    $item->save();
                        $paiement_loyer = isset($item->facturelocation_id) ? $factureLoyerObj->facturelocationperiodes : [];

                        if (isset($paiement_loyer) && count($paiement_loyer) > 0) {

                            foreach ($paiement_loyer as $pay) {
                                $detail                          = new Detailpaiement();
                                $detail->periode_id           = $pay->periode_id ;
                                $detail->montant = $pay->montant;
                                $detail->paiementloyer_id    = $item->id;
                                $detail->contrat_id           = $contrat->id;
                                $detail->type           = $typeF;
                                $detail->date_paiement = $request->datepaiement;
                                $detail->save();
                            }
                        }



                    if (!$errors) {
                        if (isset($factureEauxObj) && isset($factureEauxObj->id)) {
                            $factureEauxObj->est_activer = 2;
                            $factureEauxObj->save();
                        }
                        if (isset($factureLoyerObj) && isset($factureLoyerObj->id)) {
                            $factureLoyerObj->est_activer = 2;
                            $factureLoyerObj->save();
                        }
                        return Outil::redirectgraphql(
                            $this->queryName,
                            "id:{$item->id}",
                            Outil::$queries[$this->queryName]
                        );
                    }
                }


                throw new \Exception($errors);
            });
        } catch (\Exception $e) {
            return Outil::getResponseError($e);
        }
    }

    private function getAvenantActive($idContrat) {
        $existing = Avenant::where([["contrat_id" , $idContrat],["est_activer" , 2]])->first();
        return ($existing) ? $existing :  null;
    }
    private function getAmountLoyer(Contrat $contrat ,Request $request) {
        $avenant = $this->getAvenantActive($contrat->id);
        $facture = Facturelocation::find($request->facturelocation_id);
        $total = 0;
        if ($avenant != null && $facture != null) {
            $yearAvenat =  explode("-",$avenant->dateenregistrement)[0];
            $yearContrat =  explode("-",$facture->datefacture)[0];
            if ($yearAvenat <= $yearContrat) {
                $total = $avenant->montantloyerbase + $avenant->montantloyertom + $avenant->montantcharge;
            }
        }else {
            $total = $contrat->montantloyerbase + $contrat->montantloyertom + $contrat->montantcharge;
        }
       return $total;
    }

    private function calculeMontantFacture(Request $request , Contrat $contrat , Periodicite $p){
        $montantLoyer =  intval($this->getAmountLoyer($contrat,$request)) * $p->nbr_mois;
        $montant = ($request->facturelocation_id) ? $montantLoyer : $request->montantfacture;
        return $montant;
    }

    private function saveJustificatifAndReceipNumber(Paiementloyer $item, Request $request): void
    {
        if (isset($request->justificatif)) {
            $uploadedFile = Outil::uploadFile($request, 'justificatif', public_path('uploads/paiementloyers'));
            $item->justificatif_paiement = "uploads/paiementloyers/" . ($uploadedFile != null ?  $uploadedFile['name'] : null);
        }

        $nextReceiptNumber = $this->nextReceiptNumber();
        $item->receipt_number = $nextReceiptNumber;
    }
    private function nextReceiptNumber(): int
    {
        // Obtenez le dernier paiement avec un numéro de reçu
        $latestPayment = Paiementloyer::whereNotNull('receipt_number')
            ->orderBy('created_at', 'desc')
            ->first();

        // Si aucun paiement avec un numéro de reçu n'est trouvé, commencez par 1
        $nextReceiptNumber = ($latestPayment) ? $latestPayment->receipt_number + 1 : 1;

        return $nextReceiptNumber;
    }
    public function annulerPaiment(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $data = null;
                $item = null;
                $user_connected = Auth::user();


                if (isset($request->loyer)) {
                    if (is_numeric($request->loyer) == true) {
                        $item = Paiementloyer::find($request->loyer);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "Le paiement que vous tentez de valider n'existe pas ",
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
                } else if (isset($request->etat) && $request->etat == 1) {
                    if (empty($request->date)) {
                        $errors = "La date d'annulation est obligatoire.";
                    }
                    if (empty($request->motif)) {
                        $errors = "Le motif d'annulation est obligatoire.";
                    }
                }




                if (!isset($errors)) {

                    $entite = Entite::where("code", "SCI")->first();
                    $gestionnaire = $entite->gestionnaire;

                    // est_active :
                    // 1 : non payé; 2 :payé ; 3: paiement annulé

                    // ? etat == 1 : mode annulation paiement
                    if ($request->etat == 1) {
                        $item->est_activer =  3;
                        $item->motif_annulation_paiement = $request->motif;
                        $item->date_annulation_paiement = $request->date;
                    } else {
                        $item->est_activer =  2;
                        $item->date_reactivation_paiement = date("Y-m-d");
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

                            $locataire = $immeuble = $appartement = "";
                            if ($item->contrat->locataire && $item->contrat->locataire->nomentreprise) {
                                $locataire = $item->contrat->locataire->nomentreprise;
                            } elseif ($item->contrat->locataire && $item->contrat->locataire->nom) {
                                $locataire = $item->contrat->locataire->prenom . ' ' . $item->contrat->locataire->nom;
                            }

                            if ($item->contrat->copreneur && $item->contrat->copreneur->id) {
                                $locataire .= $item->contrat->copreneur->prenom . " " . $item->contrat->copreneur->nom;
                            }

                            if ($item->contrat->appartement &&  $item->contrat->appartement->id) {
                                $appartement = $item->contrat->appartement->nom;
                            }
                            if ($item->contrat->appartement->immeuble) {
                                $immeuble = $item->contrat->appartement->immeuble->nom . " , Adresse :" .  $item->contrat->appartement->immeuble->adresse;
                            }


                            $firsttext = "Détails \n";
                            $firsttext .= "Loctaire : " . $locataire . "\n";
                            $firsttext .= "Immeuble : " . $immeuble . "\n";
                            $firsttext .= "Appartement : N° " . $appartement . "\n";
                            if ($request->etat == 1) {
                                $firsttext .= "Date d'annulation : " . Outil::resolveAllDateFR($request->date) . " \n";
                                $firsttext .= "Motif : " . $request->motif;
                            }
                            $typefacture = (isset($item->facturelocation) && isset($item->facturelocation->id)) ? $item->facturelocation->typefacture->designation : "eaux";

                            if ($request->etat == 1) {
                                # code...
                                Outil::envoiEmail(
                                    $gestionnaire->email,
                                    "Notification d'annulation de paiement de facture",
                                    "Bonjour, un paiement de facture $typefacture viens d'etre annuler dont détails ci-dessous.\n" . $firsttext,
                                    'maileur',
                                    null,
                                    $ccopiesEmail
                                );
                            }
                            if ($request->etat == 2) {
                                # code...
                                Outil::envoiEmail(
                                    $gestionnaire->email,
                                    "Notification de réactivation de paiement de facture",
                                    "Bonjour, un paiement de facture $typefacture viens d'etre réactiver dont détails ci-dessous.\n" . $firsttext,
                                    'maileur',
                                    null,
                                    $ccopiesEmail
                                );
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

    public function recu(int $id)
    {


        $gestionnaire = null;
        $entite =  Entite::where("code", "SCI")
        ->orWhere("code", "SERTEM")
        ->first();

        if ($entite && isset($entite->id)) {
            $gestionnaire = isset($entite->gestionnaire) && isset($entite->gestionnaire->id) ? $entite->gestionnaire : null;
        }
        $paiement = isset($id) ? Paiementloyer::find($id) : null;


        // dd($paiement);
        return view('recuLoyerSciPdf', ['data' =>  $paiement, 'gestionnaire' => $gestionnaire, 'count' => 1]);
    }
}
