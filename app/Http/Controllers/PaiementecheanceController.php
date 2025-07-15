<?php

namespace App\Http\Controllers;

use App\Outil;
use App\Entite;
use App\Avisecheance;
use App\Modepaiement;
use App\Factureacompte;
use App\Paiementecheance;
use Illuminate\Http\Request;
use App\Jobs\ImportUserFileJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SaveModelController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Ramsey\Uuid\Type\Integer;
use App\Compteclient;

class PaiementecheanceController extends SaveModelController
{
    //
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "paiementecheances";
    protected $model = Paiementecheance::class;
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
        try {
            return DB::transaction(function () use ($request) {
                //  dd($request->all());
                $is_update = false;

                $errors = null;
                $user_connected = Auth::user();

                //  dd($request) ;
                $item = new Paiementecheance();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Paiementecheance::find($request->id);

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

                // dd($request);
                $factureacompte = $avisecheance = null;
                if ($request->isacompte == 1) {

                    $factureacompte = $this->validateObject($request, Factureacompte::class, 'factureacompte');
                    if (is_string($factureacompte)) {
                        $errors = $factureacompte;
                    }
                } else {
                    $avisecheance = $this->validateObject($request, Avisecheance::class, 'avisecheance');
                    if (is_string($avisecheance)) {
                        $errors = $avisecheance;
                    }
                    // else if(isset($avisecheance) && isset($avisecheance->id))  {
                    //     if($avisecheance->est_signer == 0) {
                    //         $errors = "Veuillez d'abord signer l'avis d'échéance!";
                    //     }
                    // }
                }
                // dd($avisecheance->contrat->locataire_id);
                // if (empty($request->justificatif)) {
                //     if (!$request->id) {
                //         $errors = "Veuillez renseigner le justificatif de paiement";
                //     }
                // }
                $modepaiement = $this->validateObject($request, Modepaiement::class, 'modepaiement');
                if (is_string($modepaiement)) {
                    $errors = $modepaiement;
                }

                if (empty($request->date)) {
                    $errors = "Veuillez renseigner la date de paiement";
                }
                if ($request->montantencaissement == 0) {
                    $errors = "Le montant d'encaissement doit être supérieur à 0";
                }

                // ? numero_cheque => reference_paiement

                if (!isset($errors)) {

                    if (!$is_update) {
                        $codeFacture = $this->generateCodeFacture();
                        $item->numero = $codeFacture;
                    }


                    $item->numero_cheque = isset($request->numero_cheque) ? $request->numero_cheque : null;
                    $item->avisecheance_id = isset($avisecheance) && isset($avisecheance->id) ? $avisecheance->id : null;
                    $item->factureacompte_id = isset($factureacompte) && isset($factureacompte->id) ? $factureacompte->id : null;

                    // $montant = isset($factureacompte) && isset($factureacompte->id)  ? $factureacompte->montant  : self::sumAmount($avisecheance);
                    // $item->montant = $montant;

                    $addCompteClient = false;
                    if ($avisecheance->id) {

                        $locataire_id = $avisecheance->contrat->locataire_id;
                        // reinterroger le montant a regle
                        $totalFacture = $avisecheance->montant;
                        $montantARegler = Outil::GetMontantenattente($avisecheance->id, $totalFacture);
                        $montantEncaissement = is_numeric($request->montantencaissement) ? intval($request->montantencaissement) : 0;

                        // // Cas où le montant encaissé est supérieur ou egal au montant reglé
                        if($montantARegler <= $montantEncaissement){
                            // Enregistrer le montant a reglé dans la facture
                            $item->montant = $montantARegler;
                            $avisecheance->est_activer = 2; // Marquer la facture comme totalement payée
                            $avisecheance->save();

                            if ($modepaiement->code === "CC"){
                                //dans le cas ou le montant du paiement doit etre enleve du compte client
                                Outil::addCompteClient((-1)*$item->montant,$locataire_id);
                                $addCompteClient = true;


                            } else if($montantARegler !== $montantEncaissement){
                                // Creer une nouvelle entrée dans le compte client pour l'excédent
                                $montantExcedent = $montantEncaissement - $montantARegler;

                                //dans le cas ou il y a un excedent et qu'il faut rajouter dans le compte client
                                Outil::addCompteClient($montantExcedent,$locataire_id);
                                $addCompteClient = true;
                            }
                        } else {
                          // // Cas où le montant encaissé est inferieur au montant reglé
                            $item->montant = $montantEncaissement;
                            $avisecheance->est_activer = 4; // Marquer la facture comme partiellement payée
                            $avisecheance->save();

                            if ($modepaiement->code === "CC"){
                                //dans le cas ou le montant du paiement doit etre enleve du compte client
                                Outil::addCompteClient((-1)*$item->montant,$locataire_id);
                                $addCompteClient = true;

                            }

                        }
                    }

                    $item->date = $request->date;
                    $item->periodes = isset($avisecheance) && isset($avisecheance->id) ? $avisecheance->periodes : null;

                    $item->modepaiement_id = $modepaiement->id;

                    if (isset($request->justificatif)) {
                        $uploadedFile = Outil::uploadFile($request, 'justificatif', public_path('uploads/avisecheances'));
                        $item->justificatif = "uploads/avisecheances/" . ($uploadedFile != null ?  $uploadedFile['name'] : null);
                    }


                    if (isset($avisecheance) && isset($avisecheance->id)) {
                        $nextReceiptNumber = $this->nextReceiptNumber($avisecheance->contrat_id);
                        $item->receipt_number = $nextReceiptNumber;
                    }
                    if (($request->id != null) &&  isset($item->id)) {
                        $item->updated_at_user_id = (isset($user_connected) && isset($user_connected->id)) ? $user_connected->id : null;
                    } else {
                        $item->created_at_user_id = (isset($user_connected) && isset($user_connected->id)) ? $user_connected->id : null;
                    }
                    $item->save();

                    // dd($addCompteClient);
                    if($avisecheance){
                        if($addCompteClient){
                            // Récupérer le dernier montant en attente dans le compte client
                            $compteclient = Compteclient::where('locataire_id', $avisecheance->contrat->locataire_id)
                            ->whereNull('etat')
                            ->orderBy('created_at', 'desc')
                            ->first();

                            if($compteclient){
                                $compteclient->paiementecheance_id = $item->id;
                                $compteclient->save();
                            }
                        }

                    }



                    // if (isset($avisecheance) && isset($avisecheance->id)) {
                    //     $avisecheance->est_activer = 2;
                    //     $avisecheance->save();
                    // }
                    if (isset($factureacompte) && isset($factureacompte->id)) {
                        $factureacompte->est_activer = 2;
                        $factureacompte->save();
                    }

                    $entite =  Entite::where("code", "RID")->first();

                    if (!$errors) {
                        if (isset($entite) && isset($entite->id)) {
                            $gestionnaireEmail = isset($entite->gestionnaire) && isset($entite->gestionnaire->id) ? $entite->gestionnaire->email : null;
                            if ($gestionnaireEmail != null &&  $request->isacompte != 1) {

                                $detailtext = "Détails \n";
                                $detailtext.="Réservataire : ".$avisecheance->contrat->locataire->prenom.' '.$avisecheance->contrat->locataire->nom."\n";
                                $detailtext.="Lot : N° ".$avisecheance->contrat->appartement->lot."\n";
                                $detailtext.="Ilot : N° ".$avisecheance->contrat->appartement->ilot->numero." , Adresse: ".$avisecheance->contrat->appartement->ilot->adresse."\n";

                                // dd($gestionnaireEmail);
                                Outil::envoiEmail(
                                    $gestionnaireEmail,
                                    "Notification de paiement d'avis",
                                    "Bonjour, un avis d'échéance viens d'etre payer.\n Vous pouvez maintenant générer le reçu de paiement.\n".$detailtext,
                                    'maileur',
                                    null,
                                    ['abou050793@gmail.com']
                                );
                            }
                        }
                        // return Outil::redirectgraphql($this->queryName, "id:{$item->id}", Outil::$queries[$this->queryName]);
                        return response()->json(
                            array(
                                "data" => $item->id,
                                // "data" => $item->avisecheance_id ? $item->avisecheance_id : $item->factureacompte_id ,
                                "message" => "success",
                                "redirect" => Outil::redirectgraphql($this->queryName, "id:{$item->id}", Outil::$queries[$this->queryName]),
                            )
                        );
                    }
                }


                throw new \Exception($errors);
            });
        } catch (\Exception $e) {
            return Outil::getResponseError($e);
        }
    }

    public static function sumAmount(Avisecheance $avis): int
    {
        $sum = intval($avis->amortissement) + intval($avis->fraisgestion) + intval($avis->fraisdelocation);
        $sum = Outil::getFraisAvisEcheance($avis->id,$sum);

        return $sum;
    }



    public function recu(int $id)
    {


        $gestionnaire = null;
        $entite =  Entite::where("code", "RID")->first();
        if ($entite && isset($entite->id)) {
            $gestionnaire = isset($entite->gestionnaire) && isset($entite->gestionnaire->id) ? $entite->gestionnaire : null;
        }
        $paiement = isset($id) ? Paiementecheance::find($id) : null;

        // $query = Paiementecheance::where("");
        $count = Avisecheance::join("contrats", "contrats.id", "avisecheances.contrat_id")
            ->join("paiementecheances", "paiementecheances.avisecheance_id", "avisecheances.id")
            ->where("avisecheances.est_activer", 2)
            ->where("contrats.locataire_id", $paiement->avisecheance->contrat->locataire->id)->count("paiementecheances.*");
        // dd($paiement);
        return view('recuPdf', ['data' =>  $paiement, 'gestionnaire' => $gestionnaire, 'count' => $count]);
    }


    public function nextReceiptNumber($contrat): int
    {
        // Obtenez le dernier paiement avec un numéro de reçu

        $nextReceiptNumber = Avisecheance::join("paiementecheances", "avisecheances.id", "=", "paiementecheances.avisecheance_id")
            ->where('avisecheances.contrat_id', $contrat)
            ->latest('avisecheances.date')
            ->select("avisecheances.*")
            ->count();

        return $nextReceiptNumber + 1;
    }
}
