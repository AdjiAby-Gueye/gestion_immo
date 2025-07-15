<?php

namespace App\Http\Controllers;

use App\Outil;
use Throwable;
use App\Annexe;
use App\Entite;
use App\Avenant;
use App\Contrat;
use App\Periode;
use Carbon\Carbon;
use App\Periodicite;
use App\Avisecheance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\SaveModelController;
use App\Typecontrat;
use App\Typerenouvellement;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AvenantController extends SaveModelController
{
    //
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "avenants";
    protected $model = Avenant::class;
    protected $job = null;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();
                $item_typecontrat = null;
                $item_periodicite = null;
                $item_typerenouvellement = null;

                $isupdate = false;
                $contrat = null;


                $item = new Avenant();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Avenant::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "L'avenant que vous tentez de modifier n'existe pas ",
                            );
                            return $retour;
                        }
                        $isupdate = true;
                    } else {
                        $retour = array(
                            "data" => null,
                            "error" => "L'id doit être un nombre entier",
                        );
                        return $retour;
                    }
                }



                if (empty($request->contrat_id)) {
                    $errors = "Veuillez ajouter le contrat";
                }else {
                    $contrat = Contrat::find($request->contrat_id);
                    if (!$contrat) {
                        $errors = "Veuillez ajouter un contrat valide ";
                    }
                }
                if (empty($request->typecontrat)) {
                    $errors = "Veuillez ajouter le type de contrat";
                }else {
                    $item_typecontrat = Typecontrat::find($request->typecontrat);
                    if (!$item_typecontrat) {
                        $errors = "Veuillez ajouter un type de contrat valide ";
                    }
                }


                if (isset($request->montantloyertom) && isset($request->montantcharge) && isset($request->montantloyerbase)) {
                    if (isset($request->montantloyer)) {
                        if (intval($request->montantloyer) != $this->calculMontantLoyer($request)) {
                            $errors = "Veuillez regarder le montant total loyer renseigné";
                        }
                    }
                }
                if (empty($request->date)) {
                    $errors = "Veuillez ajouter la date de l'avenant";
                }
                if (empty($request->montantloyer)) {
                    $errors = "Veuillez ajouter le montant loyer ";
                }
                if (empty($request->montantloyerbase)) {
                    $errors = "Veuillez ajouter le montant loyer de base du contrat";
                }
                if (empty($request->montantloyertom)) {
                    $errors = "Veuillez ajouter le montant loyer tom";
                }
                if (empty($request->montantcharge)) {
                    $errors = "Veuillez ajouter le montant loyer charge ";
                }

                if (empty($request->periodicite)) {
                    $errors = "Veuillez ajouter la périodicité ";
                } else {
                    $item_periodicite = Periodicite::find($request->periodicite);
                    if (!$item_periodicite) {
                        $errors = "Veuillez ajouter une périodicité valide ";
                    }
                }

                if (empty($request->typerenouvellement)) {
                    $errors = "Veuillez ajouter le type de renouvellement ";
                } else {
                    $item_typerenouvellement = Typerenouvellement::find($request->typerenouvellement);
                    if (!$item_typerenouvellement) {
                        $errors = "Veuillez ajouter un type de renouvellement valide ";
                    }
                }

                if (empty($request->tauxrevision)) {
                    $errors = "Veuillez ajouter le taux de revision ";
                }
                if (empty($request->frequencerevision)) {
                    $errors = "Veuillez ajouter la frequence de revision ";
                }


                $currentYear = Carbon::now()->year;
                if (!$isupdate) {
                    $existing = Avenant::where([["contrat_id" , $contrat->id],['est_activer',2]])->whereYear("dateenregistrement",$currentYear)->first();
                    if ($existing) {
                        $errors = "Un avenant actif existe deja dans cette année !";
                    }
                }




                if (!isset($errors)) {

                    // dd($errors) ;
                    $oldAvenant = Avenant::where([["contrat_id" , $contrat->id],['est_activer',2]])->first();
                    if (isset($oldAvenant) && isset($oldAvenant->id)) {
                        $oldAvenant->est_activer = 1;
                        $oldAvenant->save();
                    }
                    $item->montantloyertom = $request->montantloyertom;
                    $item->montantcharge = $request->montantcharge;
                    $item->montantloyerbase = $request->montantloyerbase;
                    $item->montantloyer = $this->calculMontantLoyer($request);

                    $item->tauxrevision = $request->tauxrevision;
                    $item->frequencerevision = $request->frequencerevision;
                    $item->typerenouvellement_id = $request->typerenouvellement;
                    $item->descriptif = $contrat->descriptif;

                    $item->periodicite_id = $item_periodicite->id;
                    $item->dateenregistrement = $request->date;
                    $item->contrat_id = $contrat->id;
                    $item->typecontrat_id = $request->typecontrat;
                    $item->delaipreavi_id = isset($request->delaipreavi) ? $request->delaipreavi : null;
                    $item->dateecheance = ($request->dateecheance )? $request->dateecheance : null;
                    $item->est_activer = 2;
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



    private function calculMontantLoyer(Request $request) : int{

        $total = intval($request->montantloyerbase) + intval($request->montantloyertom) + intval($request->montantcharge);
        return $total;
    }
}
