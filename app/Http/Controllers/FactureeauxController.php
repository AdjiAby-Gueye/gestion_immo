<?php

namespace App\Http\Controllers;

use App\User;
use App\Outil;
use App\Contrat;
use App\Immeuble;
use App\Appartement;
use App\Modepaiement;
use App\DomaineDetude;
use App\Paiementloyer;
use App\Detailpaiement;
use App\Factureeaux;
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

class FactureeauxController extends SaveModelController
{
    //

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "factureeauxs";
    protected $model = Factureeaux::class;
    protected $job = ImportUserFileJob::class;


    public function save(Request $request)
    {

        try {
            return DB::transaction(function () use ($request) {


                $errors = null;



                $item = new Factureeaux();

                if (isset($request->id)) {
                    if (is_numeric($request->id) && $request->id > 0) {
                        $item = Factureeaux::find($request->id);

                        if (!$item) {

                            return array(
                                "data" => null,
                                "error" => "la facture d'eaux que vous tentez de modifier n'existe pas ",
                            );
                        }

                    } else {

                        return array(
                            "data" => null,
                            "error" => "L'id doit être un nombre entier",
                        );
                    }
                }


                $contrat = $this->validateObject($request, Contrat::class, 'contrat');
                if (is_string($contrat)) {
                    $errors = $contrat;
                }



                if (empty($request->debutperiode)) {
                    $errors = "Veuillez renseigner la date de début de la période";
                }
                if (empty($request->finperiode)) {
                    $errors = " Veuillez renseigner la date de fin de la période";
                }
                if (empty($request->consommation)) {
                    $errors = " Veuillez renseigner la consommation";
                }
                if (empty($request->prixmetrecube)) {
                    $errors = " Veuillez renseigner prixmetrecube";
                }

                if (empty($request->montantfacture)) {
                    $errors = " Veuillez renseigner  prixmetrecube";
                }


                if(empty($request->quantitedebut)){
                    $errors = " Veuillez renseigner  quantite initiale";
                }
                if(empty($request->quantitefin)){
                    $errors = " Veuillez renseigner  quantite finale";
                }






                if (!isset($errors)) {

                    $item->contrat_id = ($contrat->id)? $contrat->id : null;
                    $item->consommation = $request->consommation;
                    $item->prixmetrecube = $request->prixmetrecube;
                    $item->soldeanterieur =( $request->soldeanterieur)? $request->soldeanterieur : 0;
                    $item->finperiode = $request->finperiode;
                    $item->debutperiode = $request->debutperiode;
                    $item->montantfacture = $request->montantfacture;
                    $item->quantitefin = $request->quantitefin;
                    $item->quantitedebut = $request->quantitedebut;
                    $item->dateecheance = $request->dateecheance;
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

    public function delete($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $errors = "";
                $favoris   = null;

                if (isset($id)) {
                    $Factureeaux  = Factureeaux::find($id);
                    if (isset($Factureeaux)) {

                        $Factureeaux->delete();
                        $Factureeaux->forceDelete();

                        if (!$errors) {
                            return array(
                                "data" => 1
                            );
                        } else {
                            return array(
                                $errors = 'Une erreur est survenue lors de la suppression de la facture'
                            );
                        }
                    }
                }

                throw new \Exception($errors);
            });
        } catch (\Exception $e) {
            return Outil::getResponseError($e);
        }
    }
}
