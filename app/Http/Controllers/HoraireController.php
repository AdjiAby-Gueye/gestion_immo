<?php

namespace App\Http\Controllers;


use App\User;
use App\Outil;
use App\Horaire;
use App\Immeuble;
use App\Prestataire;
use App\Proprietaire;
use App\DomaineDetude;
use App\Typeappartement;
use App\UserDepartement;
use App\Pieceappartement;
use Illuminate\Http\Request;
use App\Categorieprestataire;
use App\Jobs\ImportEntiteFileJob;
use App\Jobs\ImportHoraireFileJob;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class HoraireController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "horaires";
    protected $model = Horaire::class;
    protected $job = ImportHoraireFileJob::class;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();

                $item = new Horaire();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Horaire::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "L'horaire que vous tentez de modifier n'existe pas ",
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

                if (empty($request->designation)) {
                    $errors = "Veuillez renseigner la designation de l'horaire";
                }
                if (empty($request->debut)) {
                    $errors = "Veuillez renseigner l'heure de debut de l'horaire";
                }
                if (empty($request->fin)) {
                    $errors = "Veuillez renseigner l'heure de fin de l'horaire";
                }

                //   dd($request);
                $item->designation = $request->designation;
                $item->debut = $request->debut;
                $item->fin = $request->fin;

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
