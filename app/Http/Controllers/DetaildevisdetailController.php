<?php

namespace App\Http\Controllers;

use App\Outil;
use App\Unite;
use App\Detaildevi;
use App\Detaildevisdetail;
use Illuminate\Http\Request;
use App\Soustypeintervention;
use App\Jobs\ImportUserFileJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DetaildevisdetailController extends SaveModelController
{
    //
    //
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "unites";
    protected $model = Detaildevisdetail::class;
    protected $job = ImportUserFileJob::class;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function ()  use ($request) {
                $errors = null;

                $item = new Detaildevisdetail();

                if (isset($item->id)) {
                    if (is_numeric($item->id) && $item->id > 0) {
                        $item = Detaildevisdetail::find($item->id);

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
                $detaildevi = $this->validateObject($request, Detaildevi::class, 'appartement');
                if (is_string($detaildevi)) {
                    $errors = $detaildevi;
                }

                $soustypeintervention = $this->validateObject($request, Soustypeintervention::class, 'appartement');
                if (is_string($soustypeintervention)) {
                    $errors = $soustypeintervention;
                }

                
                $unite = $this->validateObject($request, Unite::class, 'appartement');
                if (is_string($unite)) {
                    $errors = $unite;
                }


                if (empty($request->puhtva)) {
                    $errors = "Veuillez renseigner puhtva";
                }

                if (!isset($errors)) {
                    $item->detaildevi_id = $detaildevi->id;
                    $item->soustypeintervention_id = $soustypeintervention->id;
                    $item->unite_id = $unite->id;
                    $item->puhtva = $request->puhtva;
                    $item->save();
                }
            });
        } catch (\Throwable $th) {
            return Outil::getResponseError($th);
        }
    }
}
