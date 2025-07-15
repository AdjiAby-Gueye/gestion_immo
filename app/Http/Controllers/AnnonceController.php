<?php

namespace App\Http\Controllers;


use App\Annonce;
use App\Assurance;
use App\Caution;
use App\Contrat;
use App\Facture;
use App\Immeuble;
use App\Jobs\ImportEntiteFileJob;
use App\Jobs\ImportUserFileJob;
use App\Obligationadministrative;
use App\Paiementloyer;
use App\Pieceappartement;
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


class AnnonceController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "annonces";
    protected $model = Annonce::class;
    protected $job = ImportUserFileJob::class;


    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();


            //   dd($request) ;
                $item = new Annonce();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Annonce::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "L'annonce que vous tentez de modifier n'existe pas ",
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

                if (empty($request->titre)) {
                    $errors = "le titre n'est pas renseigné";
                }
                if (empty($request->debut)) {
                    $errors = "Veuillez renseigner la date de debut de l'annonce";
                }
                if (empty($request->description)) {
                    $errors = "Veuillez renseigner la description";
                }
                if (empty($request->fin)) {
                    $errors = "Veuillez renseigner la date de fin de l'annonce";
                }
                if (empty($request->annonce_destinataire)) {
                    $errors = "Veuillez renseigner les concernés";
                }

                $item->titre = $request->titre;
                $item->debut = $request->debut;
                $item->fin = $request->fin;
                $item->description = $request->description;
                if($request->annonce_destinataire == "immeuble") {

                    if (empty($request->immeuble)) {
                        $errors = "Veuillez renseigner l'immeuble";
                    }
                    $item->immeuble_id = $request->immeuble;
                    if (empty($request->appartement)) {
                        $item->concernes = 'immeuble';
                    }else{
                        $item->concernes = 'appartement';
                        $item->appartement_id = $request->appartement;
                    }


                }
                if($request->annonce_destinataire == "immeubles") {

                    $item->concernes = 'immeubles';
                }

                if($request->annonce_destinataire == "marketing") {

                    $item->concernes = 'marketing';
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
