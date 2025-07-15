<?php

namespace App\Http\Controllers;

use App\User;

use App\Outil;
use App\Typecontrat;
use App\DomaineDetude;
use App\Typeappartement;
use App\UserDepartement;
use App\Typeintervention;
use Illuminate\Http\Request;
use App\Jobs\ImportUserFileJob;
use App\Jobs\ImportEntiteFileJob;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Jobs\ImportParametrageFileJob;
use App\Jobs\ImportTypeinterventionFileJob;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class TypeinterventionController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "typeinterventions";
    protected $model = Typeintervention::class;
    // protected $job = ImportTypeinterventionFileJob::class;
    protected $job = ImportParametrageFileJob::class;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();

                $item = new Typeintervention();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Typeintervention::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "Le type de contrat que vous tentez de modifier n'existe pas ",
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
                } else if (!Outil::isUnique(['designation'], [$request->designation], $request->id, Typeintervention::class)) {
                    $errors = "Ce type d'appartement existe deja !";
                }

                $item->designation = $request->designation;

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
