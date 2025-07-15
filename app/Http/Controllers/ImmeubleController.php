<?php

namespace App\Http\Controllers;


use App\Appartement;
use App\Bien_questionnaire;
use App\Gardien;
use App\Immeuble;
use App\Jobs\ImportEntiteFileJob;
use App\Jobs\ImportUserFileJob;
use App\Pieceappartement;
use App\Pieceimmeuble;
use App\Questionnaire;
use App\Securite;
use App\Typeappartement;
use App\Typepiece;
use App\UserDepartement;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Outil;
use App\User;
use App\DomaineDetude;
use App\Jobs\ImportImmeubleFileJob;
use Spatie\Permission\Models\Role;


class ImmeubleController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "immeubles";
    protected $model = Immeuble::class;
    protected $job = ImportImmeubleFileJob::class;


    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();
                $item = new Immeuble();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Immeuble::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "L'immeuble que vous tentez de modifier n'existe pas ",
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
                    $errors = "Veuillez renseigner la designation de l'immeuble";
                }
                if (empty($request->adresse)) {
                    $errors = "Veuillez renseigner l'adresse de l'immeuble";
                }
                if (empty($request->structureimmeuble)) {
                    $errors = "Veuillez renseigner le type d'immeuble";
                }
                if ($request->prenomgardien && $request->nomgardien && $request->adressegardien && $request->telephone1gardien) {

                    $gardien = new Gardien();
                    $gardien->prenom = $request->prenomgardien;
                    $gardien->nom = $request->nomgardien;
                    $gardien->adresse =  $request->adressegardien;
                    $gardien->telephone1 = $request->telephone1gardien;
                    $gardien->telephone2 = $request->telephone2gardien;
                    $gardien->save();
                    $item->gardien_id = $gardien->id;
                }

                $item->nom = $request->nom;
                $item->adresse = $request->adresse;
                $item->structureimmeuble_id = $request->structureimmeuble;
                $item->nombreappartement = $request->nombreappartement;
                $item->nombreascenseur = $request->nombreascenseur;
                $item->nombrepiscine = $request->nombrepiscine;
                $item->nombregroupeelectrogene = $request->nombregroupeelectrogene;



                if (!isset($errors)) {
                    $item->save();



                    $typepieces = Typepiece::All();
                    foreach ($typepieces as $typepiece) {
                        $id = $typepiece->id;
                        if (isset($request->{$id})) {
                            for ($i = 1; $i <= $request->{$id}; $i++) {
                                $pieceimmeuble = new Pieceimmeuble();
                                $pieceimmeuble->typepiece_id = $typepiece->id;
                                $pieceimmeuble->immeuble_id = $item->id;
                                $pieceimmeuble->save();
                            }
                        }
                    }

                    $immeuble_securite = json_decode($request->immeuble_securite, true);


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
