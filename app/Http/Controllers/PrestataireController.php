<?php

namespace App\Http\Controllers;


use App\User;
use App\Outil;
use App\Immeuble;
use App\Prestataire;
use App\Proprietaire;
use App\DomaineDetude;
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
use App\Jobs\ImportPrestataireFileJob;
use App\Jobs\ImportProprietaireFileJob;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class PrestataireController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "prestataires";
    protected $model = Prestataire::class;
    protected $job = ImportPrestataireFileJob::class;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();

                $item = new Prestataire();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {

                        $item = Prestataire::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "Le prestataire que vous tentez de modifier n'existe pas ",
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
                    $errors = "Veuillez renseigner le nom du prestataire";
                }
                if (empty($request->adresse)) {
                    $errors = "Veuillez renseigner l'adresse du prestataire";
                }
                if (empty($request->email)) {
                    $errors = "Veuillez renseigner l'email du prestataire";
                }
                if (empty($request->telephone1)) {
                    $errors = "Veuillez renseigner le telephone du prestataire";
                }
                if (empty($request->categorieprestataire)) {
                    $errors = "Veuillez renseigner la categorie du prestataire";
                }

                //   dd($request);
                $item->nom = $request->nom;
                $item->adresse = $request->adresse;
                $item->email = $request->email;
                $item->telephone1 = $request->telephone1;
                $item->telephone2 = $request->telephone2;
                $item->categorieprestataire_id = $request->categorieprestataire;

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
