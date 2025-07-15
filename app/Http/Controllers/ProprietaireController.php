<?php

namespace App\Http\Controllers;


use App\Immeuble;
use App\Jobs\ImportEntiteFileJob;
use App\Jobs\ImportUserFileJob;
use App\Pieceappartement;
use App\Proprietaire;
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
use App\Jobs\ImportProprietaireFileJob;
use Spatie\Permission\Models\Role;


class ProprietaireController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "proprietaires";
    protected $model = Proprietaire::class;
    protected $job = ImportProprietaireFileJob::class;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();

                // dd($request) ;
                $item = new Proprietaire();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Proprietaire::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "Le proprietaire que vous tentez de modifier n'existe pas ",
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

                if (empty($request->nom)) {
                    $errors = "Veuillez renseigner le nom du proprietaire";
                }
                if (empty($request->prenom)) {
                    $errors = "Veuillez renseigner le prenom du proprietaire";
                }
                if (empty($request->adresse)) {
                    $errors = "Veuillez renseigner l'adresse du proprietaire";
                }
                if (empty($request->telephone)) {
                    $errors = "Veuillez renseigner le telephone du proprietaire";
                }



                //ICI
                //   dd($request);
                $item->nom = $request->nom;
                $item->prenom = $request->prenom;
                $item->age = $request->age;
                $item->adresse = $request->adresse;
                $item->telephone = $request->telephone;
                $item->telephoneportable = $request->telephoneportable;
                // $item->telephonebureau = $request->telephonebureau;

                if ($request->isgestionnaire) {

                    if (empty($request->prenomgestionnaire)) {
                        $errors = "Veuillez renseigner le prenom du gestionnaire";
                    }
                    if (empty($request->nomgestionnaire)) {
                        $errors = "Veuillez renseigner le nom du gestionnaire";
                    }
                    if (empty($request->adressegestionnaire)) {
                        $errors = "Veuillez renseigner l'adresse du gestionnaire";
                    }
                    if (empty($request->telephone1gestionnaire)) {
                        $errors = "Veuillez renseigner un telephone du gestionnaire";
                    }

                    $item->prenomgestionnaire = $request->prenomgestionnaire;
                    $item->nomgestionnaire = $request->nomgestionnaire;
                    $item->adressegestionnaire = $request->adressegestionnaire;
                    $item->telephone1gestionnaire = $request->telephone1gestionnaire;
                    $item->telephone2gestionnaire = $request->telephone2gestionnaire;
                    $item->isgestionnaire = '1';
                } else {
                    // $item->isgestionnaire = '0';
                }


                if (!isset($errors)) {

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
