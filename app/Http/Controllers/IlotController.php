<?php

namespace App\Http\Controllers;

use App\Ilot;
use App\Outil;
use Illuminate\Http\Request;
use App\Jobs\ImportIlotFileJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ImportParametrageFileJob;
use App\Http\Controllers\SaveModelController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IlotController extends  SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "ilots";
    protected $model = Ilot::class;
    protected $job = ImportIlotFileJob::class;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();

                // dd($request) ;
                $item = new Ilot();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Ilot::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "L'ilot que vous tentez de modifier n'existe pas ",
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

                if (empty($request->numero)) {
                    $errors = "Veuillez renseigner le numero";
                } else  if (!Outil::isUnique(['numero'], [$request->numero], $request->id, Ilot::class)) {
                    $errors = "Le numéro renseigné existe déja";
                }
                if (empty($request->adresse)) {
                    $errors = "Veuillez renseigner l'adresse ";
                }

                if (!isset($errors)) {
                    $item->numero = $request->numero;
                    $item->adresse = $request->adresse;
                    // $item->numerotitrefoncier =  isset($request->numerotitrefoncier) ? $request->numerotitrefoncier : null;
                    // $item->datetitrefoncier =  isset($request->datetitrefoncier) ? $request->datetitrefoncier : null;
                    // $item->adressetitrefoncier =  isset($request->adressetitrefoncier) ? $request->adressetitrefoncier : null;
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
