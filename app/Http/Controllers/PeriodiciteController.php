<?php

namespace App\Http\Controllers;

use App\Outil;
use App\Periode;
use App\Periodicite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ImportParametrageFileJob;
use App\Jobs\ImportTypecontratFileJob;
use App\Http\Controllers\SaveModelController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PeriodiciteController extends SaveModelController
{
    //

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "periodicites";
    protected $model = Periodicite::class;
    protected $job = ImportParametrageFileJob::class;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();

                $item = new Periodicite();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Periodicite::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "La periodicite que vous tentez de modifier n'existe pas ",
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
                } else if (!Outil::isUnique(['designation'], [$request->designation], $request->id, Periode::class)) {
                    $errors = "Cette periodicite existe deja !";
                }


                if (!isset($errors)) {
                    $item->designation = $request->designation;
                    $item->description = $request->description;

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
