<?php

namespace App\Http\Controllers;

use App\User;
use App\Outil;

use App\Typecontrat;
use App\Modepaiement;
use App\DomaineDetude;
use App\Typeappartement;
use App\UserDepartement;
use App\Constituantpiece;
use Illuminate\Http\Request;
use App\Jobs\ImportUserFileJob;
use App\Jobs\ImportEntiteFileJob;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Jobs\ImportParametrageFileJob;
use App\Jobs\ImportTypecontratFileJob;
use PragmaRX\Countries\Package\Countries;
use PragmaRX\Countries\Package\Services\Raw;
use App\Http\Controllers\SaveModelController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class ConstituantpieceController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "constituantpieces";
    protected $model = Constituantpiece::class;
    protected $job = ImportParametrageFileJob::class;

    public function countries() {
        $allCountries = (new Countries())->all();

        $countries = $allCountries->map(function ($country) {
            return [
                'name' => $country->name->common,
                'flag' => $country->flag['flag-icon'],
            ];
        })->values();
        
        return $countries;
        

    }
    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();

                $item = new Constituantpiece();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Constituantpiece::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "Le constituant que vous tentez de modifier n'existe pas ",
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
                } else if (!Outil::isUnique(['designation'], [$request->designation], $request->id, Constituantpiece::class)) {
                    $errors = "La designation existe deja !";
                }


                if (!isset($errors)) {
                    $item->designation = $request->designation;
                    if ($request->description) {
                        $item->description = $request->description;
                    }

                    $item->save();

                    if (!$errors) {
                        // return Outil::redirectgraphql($this->queryName, "id:{$item->id}", Outil::$queries[$this->queryName]);
                        return ["data" => ["data" => $item]];
                    }
                }


                throw new \Exception($errors);
            });
        } catch (\Exception $e) {
            return Outil::getResponseError($e);
        }
    }
}
