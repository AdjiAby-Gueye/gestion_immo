<?php

namespace App\Http\Controllers;


use App\User;
use App\Outil;
use App\Typecontrat;
use App\Typefacture;
use App\DomaineDetude;
use App\Typeassurance;
use App\Typeappartement;
use App\UserDepartement;
use App\Structureimmeuble;
use Illuminate\Http\Request;
use App\Jobs\ImportUserFileJob;
use App\Jobs\ImportEntiteFileJob;
use App\Jobs\ImportParametrageFileJob;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class StructureimmeubleController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "structureimmeubles";
    protected $model = Structureimmeuble::class;
    protected $job = ImportParametrageFileJob::class;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();

                $item = new Structureimmeuble();

               // dd($request) ;
                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Structureimmeuble::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "Le type d'assurance que vous tentez de modifier n'existe pas ",
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
                if($request->designation !== "0"){
                    $designation = "R+$request->designation" ;
                }else{
                    $designation = "RDC" ;
                }
               // dd($designation) ;
                if (empty($request->designation)) {
                    if($request->designation == "0"){
                        if (!Outil::isUnique(['designation'], [$designation], $request->id, Structureimmeuble::class)) {
                            $errors = "Ce nombre d'etage existe deja !";
                        }
                    }else {
                        $errors = "Veuillez renseigner la designation";
                    }
                } else if (!Outil::isUnique(['designation'], [$designation], $request->id, Structureimmeuble::class)) {
                    $errors = "Ce nombre d'etage existe deja !";
                }

                $item->designation = $designation;
                $item->etages = $request->designation;

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
