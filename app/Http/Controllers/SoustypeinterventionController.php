<?php

namespace App\Http\Controllers;

use App\Outil;
use App\Soustypeintervention;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SoustypeinterventionController extends SaveModelController
{
       //
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "soustypeinterventions";
    protected $model = Soustypeintervention::class;
    protected $job = ImportUserFileJob::class;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function ()  use ($request) {
                $errors = null;

                $item = new Soustypeintervention();

                if (isset($item->id)) {
                    if (is_numeric($item->id) && $item->id > 0) { 
                        $item = Soustypeintervention::find($item->id);

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

                if (empty($request->designation)) {
                    $errors = "Veuillez renseigner designatiion";
                }

                if (!isset($errors)) {
                    $item->designation = $request->designation;
                    $item->save();
                }
            });
        } catch (\Throwable $th) {
            return Outil::getResponseError($th);
        }
    }

}
