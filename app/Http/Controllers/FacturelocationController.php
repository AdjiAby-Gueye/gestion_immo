<?php

namespace App\Http\Controllers;


use App\Outil;
use Mpdf\Tag\S;
use App\Avenant;
use App\Contrat;
use App\Periodicite;
use App\Typefacture;
use App\Facturelocation;
use Illuminate\Http\Request;
use App\Facturelocationperiode;
use App\Jobs\ImportUserFileJob;
use App\Periode;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FacturelocationController extends SaveModelController
{
    //
    //extends

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "facturelocations";
    protected $model = Facturelocation::class;
    protected $job = ImportUserFileJob::class;


    public function save(Request $request)
    {
        // dd($request);

        try {
            return DB::transaction(
                function () use ($request) {
                    $errors = null;

                    $facturelocation = new Facturelocation();

                    if (isset($request->id)) {
                        if (is_numeric($request->id) === true) {
                            $facturelocation = Facturelocation::find($request->id);

                            if (!$facturelocation) {
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

                    $typefacture = $this->validateObject($request, Typefacture::class, 'typefacture');
                    $paiement_loyer = json_decode($request->periodefacturelocation, true);
                    // dd($paiement_loyer);

                    if (is_string($typefacture)) {
                        $errors =  $typefacture;
                    }

                    $contract = $this->validateObject($request, Contrat::class, 'contrat');

                    if (is_string($contract)) {
                        $errors = $contract;
                    }

                    $periodicite = null;
                    if (!is_string($typefacture) && isset($typefacture->id)) {
                        if ($typefacture->designation != "acompte") {
                            $periodicite = $this->validateObject($request, Periodicite::class, 'periodicite');

                            if (is_string($periodicite)) {
                                $errors =  $periodicite;
                            }
                        }

                        if (($typefacture->designation == "eau" || $typefacture->designation == "electricite") && empty($request->montant)) {
                            $errors = "Veuillez renseigner le montant de la facture " . $typefacture->designation;
                        }
                    }


                    if (isset($periodicite) && isset($periodicite->id)) {
                        if (isset($paiement_loyer)) {
                            $type = $periodicite->nbr_mois;
                            if (count($paiement_loyer) == 0) {
                                $errors = "Veuillez séléctionner au moins une période";
                            } else {
                                switch ($type) {
                                    case 1:
                                        if (count($paiement_loyer) > 1) {
                                            $errors = "veuillez séléctionner une seule période car la périodicité est mensuelle.";
                                        }
                                        break;
                                    case 2:
                                        if (count($paiement_loyer) != 2) {
                                            $errors = "veuillez séléctionner deux périodes car la périodicité est bimensuelle.";
                                        }
                                        break;
                                    case 3:
                                        if (count($paiement_loyer) != 3) {
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




                    if (empty($request->objetfacture)) {
                        $errors = "Veuillez renseigner l'objet de la facture";
                    }
                    if (empty($request->datefacture)) {
                        $errors = "Veuillez renseigner la date de la facture";
                    }

                    if (empty($request->periodefacturelocation)) {
                        $errors = "Veuillez renseigner le champs période ";
                    }

                    $idsP = [];
                    $idsP = array_map(function ($pay) {
                        return $pay['periode_id'];
                    }, $paiement_loyer);
                    // Récupérer les périodes associées aux IDs
                    $periodes = Periode::whereIn('id', $idsP)->get();
                    $typeF = isset($typefacture) ? $typefacture->id : null;



                    foreach ($periodes as $periode) {
                        $periodeYear = $periode->annee ??  explode(' ', $periode->designation)[1];
                        $existingDetails = Facturelocationperiode::where('periode_id', $periode->id)
                            ->where("contrat_id", $contract->id)
                            ->where("typefacture_id", $typeF)
                            ->whereYear("date", $periodeYear)
                            ->first();

                        if ($existingDetails) {
                            $errors = "Une facture avec la période " . $periode->designation . " existe déjà.";
                            break;
                        }
                    }


                    if (!isset($errors)) {

                        $facturelocation->typefacture_id = $typefacture->id;
                        $facturelocation->contrat_id = $contract->id;
                        $facturelocation->periodicite_id = isset($periodicite) && isset($periodicite->id) ? $periodicite->id : null;
                        $facturelocation->objetfacture = $request->objetfacture;
                        $facturelocation->datefacture = $request->datefacture;
                        $facturelocation->montant = ($request->montant) ? $request->montant : null;
                        $facturelocation->date_echeance = $request->dateecheance ? $request->dateecheance : null;
                        $facturelocation->nbremoiscausion = isset($contrat) && isset($contrat->nbremoiscausion) ? $contrat->nbremoiscausion : null;
                        $facturelocation->save();
                        if (isset($paiement_loyer) && count($paiement_loyer) > 0) {

                            foreach ($paiement_loyer as $pay) {
                                $detail                          = new Facturelocationperiode();
                                $detail->periode_id           = intval($pay["periode_id"]);
                                $detail->montant = (strtolower($typefacture->designation) == "loyer") ? $this->getAmountLoyer($contract, $request) : $request->montantfacture;
                                $detail->facturelocation_id    = $facturelocation->id;
                                $detail->typefacture_id           = $typeF;
                                $detail->contrat_id           = $contract->id;
                                $detail->date = $request->datefacture;
                                $detail->save();
                            }
                        }
                        if (!$errors) {
                            return Outil::redirectgraphql($this->queryName, "id:{$facturelocation->id}", Outil::$queries['facturelocations2']);
                        }
                    }
                    throw new \Exception($errors);
                }
            );
        } catch (\Throwable $th) {
            return Outil::getResponseError($th);
        }
    }



    private function getAvenantActive($idContrat)
    {
        $existing = Avenant::where([["contrat_id", $idContrat], ["est_activer", 2]])->first();
        return ($existing) ? $existing :  null;
    }

    private function getAmountLoyer(Contrat $contrat, Request $request)
    {
        $avenant = $this->getAvenantActive($contrat->id);
        $facture = Facturelocation::find($request->facturelocation_id);
        $total = 0;
        if ($avenant != null && $facture != null) {
            $yearAvenat =  explode("-", $avenant->dateenregistrement)[0];
            $yearContrat =  explode("-", $facture->datefacture)[0];
            if ($yearAvenat <= $yearContrat) {
                $total = $avenant->montantloyerbase + $avenant->montantloyertom + $avenant->montantcharge;
            }
        } else {
            $total = $contrat->montantloyerbase + $contrat->montantloyertom + $contrat->montantcharge;
        }
        return $total;
    }
}
