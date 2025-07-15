<?php

namespace App\Http\Controllers;


use App\Immeuble;
use App\Intervention;
use App\Jobs\ImportEntiteFileJob;
use App\Jobs\ImportUserFileJob;
use App\Pieceappartement;
use App\Prestataire;
use App\Produitsutilise;
use App\Produitsutilises_rapportintervention;
use App\Proprietaire;
use App\Rapportintervention;
use App\Typeappartement;
use App\UserDepartement;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Outil;
use App\User;
use App\DomaineDetude;
use Spatie\Permission\Models\Role;


class RapportinterventionController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "rapportinterventions";
    protected $model = Rapportintervention::class;
    protected $job = ImportUserFileJob::class;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();

                $item = new Rapportintervention();

              //  dd($request);
                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Rapportintervention::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "Le rapport d'intervention que vous tentez de modifier n'existe pas ",
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

                if (empty($request->intervention)) {
                    $errors = "Veuillez renseigner l'intervention";
                }
                if (empty($request->immeuble)) {
                    $errors = "Veuillez renseigner l'immeuble";
                }
                if (empty($request->prenom)) {
                    $errors = "Veuillez renseigner le prenom";
                }
                if (empty($request->compagnietechnicien)) {
                    $errors = "Veuillez renseigner la compagnie";
                }
                if (empty($request->debut)) {
                    $errors = "Veuillez renseigner la date de debut";
                }
                if (empty($request->fin)) {
                    $errors = "Veuillez renseigner la date de fin";
                }
                if (empty($request->observations)) {
                    $errors = "Veuillez renseigner les observations";
                }
                if (empty($request->etat)) {
                    $errors = "Veuillez renseigner l'etat";
                }
                if (empty($request->recommandations)) {
                    $errors = "Veuillez renseigner les recommandations";
                }

                //   dd($request);
                $item->intervention_id = $request->intervention;
                $item->immeuble_id = $request->immeuble;
                $item->appartement_id = $request->appartement;
                $item->prenom = $request->prenom;
                $item->compagnietechnicien = $request->compagnietechnicien;
                $item->debut = $request->debut;
                $item->fin = $request->fin;
                $item->observations = $request->observations;
                $item->etat = $request->etat;
                $item->recommandations = $request->recommandations;
             //   $item->membreequipegestion_id = $request->employe;

                if (!isset($errors)) {

                    $item->save();

                    $inputs = $request->input() ;

                    $produits = Produitsutilise::All();
                    foreach ($produits as $produit)
                    {
                        //  dd($inputs["locataire{$locataire->id}"]) ;
                        if(isset($inputs["produit{$produit->id}"])){
                            $produitrapportintervention = new Produitsutilises_rapportintervention() ;
                            $produitrapportintervention-> rapportintervention_id = $item->id;
                            $produitrapportintervention->produitsutilise_id = intval( $inputs["produit{$produit->id}"]);
                            $produitrapportintervention->save() ;
                        }
                    }

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
