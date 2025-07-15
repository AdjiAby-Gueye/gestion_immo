<?php

namespace App\Http\Controllers;

use App\User;

use App\Outil;
use App\Typecontrat;
use App\Modepaiement;
use App\DomaineDetude;
use App\Typeappartement;
use App\UserDepartement;
use Illuminate\Http\Request;
use App\Jobs\ImportUserFileJob;
use App\Jobs\ImportEntiteFileJob;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Jobs\ImportParametrageFileJob;
use App\Jobs\ImportTypecontratFileJob;
use App\Http\Controllers\SaveModelController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class ModepaiementController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "modepaiements";
    protected $model = Modepaiement::class;
    protected $job = ImportParametrageFileJob::class;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();

                $item = new Modepaiement();
                // dd($request);
                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Modepaiement::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "Le mode de paiement que vous tentez de modifier n'existe pas ",
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
                } else if (!Outil::isUnique(['designation'], [$request->designation], $request->id, Modepaiement::class)) {
                    $errors = "Ce mode de paiement existe deja !";
                }

                if (empty($request->code)) {
                    $errors = "Veuillez renseigner le code";
                } else if (!Outil::isUnique(['code'], [$request->code], $request->id, Modepaiement::class)) {
                    $errors = "Ce code de mode paiement existe deja !";
                }

                if (!isset($errors)) {
                    $item->designation = $request->designation;
                    $item->description = isset($request->description) ? $request->description :  null;
                    $item->code = $request->code;
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
