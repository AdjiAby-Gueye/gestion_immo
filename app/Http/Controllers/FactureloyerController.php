<?php

namespace App\Http\Controllers;

use App\Contrat;
use App\Facturelocation;
use App\Outil;
use App\Periodicite;
use App\Typefacture;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Mpdf\Tag\S;

class FactureloyerController extends SaveModelController
{
    //extends

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "facturelocations";
    protected $model = Facturelocation::class;
    protected $job = ImportUserFileJob::class;


    public function save(Request $request)
    {

        try {
            return DB::transaction(
                function () use ($request) {
                    $errors = null;

                    $facturelocation = new Facturelocation();

                    if (isset($request->id)) {
                        if (is_numeric($request->id) == true) {
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


                    if (is_string($typefacture)) {
                        $errors =  $typefacture;
                    }

                    $contract = $this->validateObject($request, Contrat::class, 'contrat');

                    if (is_string($contract)) {
                        $errors = $contract;
                    }


                    $periodicite = $this->validateObject($request, Periodicite::class, 'periodicite');

                    if (is_string($periodicite)) {
                        $errors =  $periodicite;
                    }



                    if (empty($request->objetfcture)) {
                        $errors = "Veuillez renseigner l'objet de la facture";
                    }
                    if (empty($request->datefacture)) {
                        $errors = "Veuillez renseigner la date de la facture";
                    }
                    if (empty($request->typeformulaire)) {
                        $errors = "Veuillez renseigner le type de la facture";
                    }


                    if (!isset($errors)) {

                        $facturelocation->typefacture_id = $typefacture->id;
                        $facturelocation->contrat_id = $contract->id;
                        $facturelocation->periodicite_id = $periodicite->id;
                        $facturelocation->objetfacture = $request->objetfacture;
                        $facturelocation->datefacture = $request->datefacture;
                        $facturelocation->typeformulaire = $request->typeformulaire;
                        $facturelocation->nbremoiscausion = isset($contrat) && isset($contrat->nbremoiscausion) ? $contrat->nbremoiscausion : null;
                        $facturelocation->save();

                        if (!$errors) {
                            return Outil::redirectgraphql($this->queryName, "id:{$facturelocation->id}", Outil::$queries[$this->queryName]);
                        }
                    }
                    throw new \Exception($errors);
                }
            );
        } catch (\Throwable $th) {
            return Outil::getResponseError($th);
        }
    }
}
