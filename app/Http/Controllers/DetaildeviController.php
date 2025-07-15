<?php

namespace App\Http\Controllers;

use App\Detaildevi;
use App\Outil;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetaildeviController extends SaveModelController
{
    //
    //
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queyName = "devis";
    protected $model = Devi::class;
    protected $job = ImportUserFileJob::class;

    public function save(Request $request)
    {
        try {


            return DB::transaction(
                function () use ($request) {
                    $errors = null;

                    $item = new Detaildevi();
                    if (isset($request->id)) {
                        if (is_numeric($request->id) === true) {
                            $item = Detaildevi::find($request->id);
                            if (!$item) {
                                return array(
                                    "data" => null,
                                    "error" => "Le paiement que vous tentez de modifier n'existe pas ",
                                );
                            }
                        } else {

                            return array(
                                "data" => null,
                                "error" => "L'id doit être un nombre entier",
                            );
                        }
                    }

                    $devi_id =  $this->validateObject($request, Detaildevi::class, "devi_id");
                    $errors =   (is_string($devi_id)) ? $devi_id = $devi_id : null;

                    $typeintervention_id =  $this->validateObject($request, Devi::class, "intervention_id");
                    $errors =   (is_string($typeintervention_id)) ?    $typeintervention_id = $typeintervention_id : null;



                    if (!isset($errors)) {
                        $item->devi_sid = $devi_id->id;
                        $item->typeintervention_id = $typeintervention_id->id;
                        $item->save();
                        if (!$errors) {
                            return Outil::redirectgraphql($this->queryName, "id:{$item->id}", Outil::$queries[$this->queryName]);
                        }
                    }

                    throw new \Exception($errors);
                }
            );
        } catch (\Throwable $th) {
            return Outil::getResponseError($th);
        }
    }
}
