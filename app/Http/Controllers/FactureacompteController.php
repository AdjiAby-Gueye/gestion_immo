<?php

namespace App\Http\Controllers;

use App\Outil;
use App\Contrat;
use App\Factureacompte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;use App\Jobs\ImportUserFileJob;

class FactureacompteController extends SaveModelController
{
    //
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "factureacomptes";
    protected $model = Factureacompte::class;
    protected $job = ImportUserFileJob::class;


    public function save(Request $request)
    {

        try {
            return DB::transaction(
                function () use ($request) {
                    $errors = null;
                    $ccopiesEmail = [];
                    $avis = new Factureacompte();

                    if (isset($request->id)) {
                        if (is_numeric($request->id) === true) {
                            $avis = Factureacompte::find($request->id);

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

                    $contract = $this->validateObject($request, Contrat::class, 'contrat');
                    if (is_string($contract)) {
                        $errors = $contract;
                    }

                    if (empty($request->date)) {
                        $errors = "Veuillez renseigner la date de l'avis d'échéance";
                    }

                    if (empty($request->montant)) {
                        $errors = "Veuillez renseigner le montant ";
                    }else if (!is_numeric($request->montant)) {
                        $errors = "Le champs montant doit être un entier ";
                    }

                    $existingAvis = Factureacompte::where('contrat_id', $request->contrat)->first();
                    if ($existingAvis) {

                        $errors = "Une facture d'acompte  existe déjà.";

                    }

                    if (!isset($errors)) {

                        $avis->contrat_id = $contract->id;
                        $avis->commentaire = $request->commentaire;
                        $avis->montant = $request->montant;
                        $avis->date = $request->date;
                        $avis->date_echeance = $request->dateecheance;
                        $avis->save();

                        if (!$errors) {
                            return Outil::redirectgraphql($this->queryName, "id:{$avis->id}", "id,montant,contrat_id");
                        }
                    }
                    throw new \Exception($errors);
                }
            );
        } catch (\Exception $th) {
            return Outil::getResponseError($th);
        }
    }
}
