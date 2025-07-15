<?php

namespace App\Http\Controllers;

use App\Detailfactureintervention;
use App\Factureintervention;
use App\Modepaiement;
use App\Outil;
use App\Paiementintervention;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaiementinterventionController extends SaveModelController
{
    //
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "paiementinterventions";
    protected $model = Paiementintervention::class;
    protected $job = ImportUserFileJob::class;
    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $is_update = false;

                $errors = null;


                $item = new Paiementintervention();

                if (isset($request->id)) {
                    if (is_numeric($request->id)) {
                        $item = Paiementintervention::find($request->id);

                        if (!$item) {

                            return  array(
                                "data" => null,
                                "error" => "Le paiement que vous tentez de modifier n'existe pas ",
                            );
                        }
                        $is_update = true;
                    } else {

                        return  array(
                            "data" => null,
                            "error" => "L'id doit être un nombre entier",
                        );
                    }
                }

                if (empty($request->factureinterventionid)) {
                    $errors = "Veuillez renseigner la facture";
                }


                $modepaiement = $this->validateObject($request, Modepaiement::class, 'modepaiement');
                if (is_string($modepaiement)) {
                    $errors = $modepaiement;
                }


                if (empty($request->date)) {
                    $errors = "Veuillez renseigner la date de paiement";
                }


                if (empty($request->montant)) {
                    $errors = "Veuillez renseigner le montant.";
                }


                if (!isset($errors)) {

                    $factureIntervention = Factureintervention::find($request->factureinterventionid);
                    
                    if ($factureIntervention) {

                        $query = Detailfactureintervention::where('factureintervention_id', $factureIntervention->id)
                            ->get();
                        $amount = 0;

                        foreach ($query as $value) {
                            $amount = $amount + intval($value->montant);
                        }

                        if (( intval($request->montant)) < $amount) {
                            $errors = "Le montant du paiement ne peut pas être inferieur au montant de la facture.";
                        }

                        if (( intval($request->montant)) == $amount) {
                            $item->montant = $request->montant ?? null;
                            $factureIntervention->est_activer = 0;
                        }

                        if (( intval($request->montant)) > $amount) {
                            $errors = "Le montant du paiement ne peut pas être supérieur au montant de la facture.";
                        }

                        $factureIntervention->save();
                    } else {
                        $errors = "Facture d'intervention non trouvée";
                    }



                    $item->factureintervention_id = $request->factureinterventionid;
                    $item->cheque = ($request->cheque) ? $request->cheque : null;
                    $item->date = $request->date;
                    $item->modepaiement_id = $modepaiement->id;
                    $item->save();

                    if (!$errors) {
                        return Outil::redirectgraphql($this->queryName, "id:{$item->id}", Outil::$queries[$this->queryName]);
                    }
                }

                throw new \Exception($errors);
            });
        } catch (\Exception $e) {
            return Outil::getResponseError($e);
        }
    }
}
