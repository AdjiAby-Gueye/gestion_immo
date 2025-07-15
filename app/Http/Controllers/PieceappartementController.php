<?php

namespace App\Http\Controllers;


use App\Immeuble;
use App\Jobs\ImportEntiteFileJob;
use App\Jobs\ImportUserFileJob;
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


class PieceappartementController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "pieceappartements";
    protected $model = Pieceappartement::class;
    protected $job = ImportUserFileJob::class;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();

                $item = new Pieceappartement();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Pieceappartement::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "La piece d'appartement que vous tentez de modifier n'existe pas ",
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
                    $errors = "Veuillez renseigner la designation";
                }
                if (empty($request->appartement) && empty($request->immeuble) ) {
                    $errors = "Veuillez renseigner l'appartement ou l'immeuble";
                }

             //   dd($request);
                $item->designation = $request->designation;
                $item->appartement_id = $request->appartement;
                $item->immeuble_id = $request->immeuble;
                $item->typepiece_id = $request->typepiece;

                if (!isset($errors)) {

                    if ($request->typepiece == 3){
                        $immeuble = Immeuble::find($request->immeuble);


                        $nombre = intval($immeuble->nombreascenseur) + 1 ;
                        $immeuble->nombreascenseur = "$nombre";

                        $immeuble->save();
                    }

                    if ($request->typepiece == 4){
                        $immeuble = Immeuble::find($request->immeuble);


                        $nombre = intval($immeuble->nombrepiscine)  + 1 ;
                        $immeuble->nombrepiscine = "$nombre";

                        $immeuble->save();
                    }

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
