<?php

namespace App\Http\Controllers;


use App\Equipegestion;
use App\Immeuble;
use App\Jobs\ImportEntiteFileJob;
use App\Jobs\ImportUserFileJob;
use App\Membreequipegestion;
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
use Spatie\Permission\Models\Role;


class MembreequipegestionController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "membreequipegestions";
    protected $model = Membreequipegestion::class;
    protected $job = ImportUserFileJob::class;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();
             //   dd($request) ;
                $item = new Membreequipegestion();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Membreequipegestion::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "Le membre que vous tentez de modifier n'existe pas ",
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

                if (empty($request->prenom)) {
                    $errors = "Veuillez renseigner le prenom";
                }
                if (empty($request->nom)) {
                    $errors = "Veuillez renseigner le nom";
                }
                if (empty($request->email)) {
                    $errors = "Veuillez renseigner l'email";
                }
                if (empty($request->telephone)) {
                    $errors = "Veuillez renseigner le telephone";
                }



                //   dd($request);
                $item->prenom = $request->prenom;
                $item->nom = $request->nom;
                $item->email = $request->email;
                $item->telephone = $request->telephone;


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
