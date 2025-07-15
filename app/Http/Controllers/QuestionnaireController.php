<?php

namespace App\Http\Controllers;


use App\Jobs\ImportEntiteFileJob;
use App\Jobs\ImportUserFileJob;
use App\Questionnaire;
use App\Typeappartement;
use App\Typecontrat;
use App\Typefacture;
use App\Typequestionnaire;
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


class QuestionnaireController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "questionnaires";
    protected $model = Questionnaire::class;
    protected $job = ImportUserFileJob::class;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();

                $item = new Questionnaire();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Questionnaire::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "Le questionnaire que vous tentez de modifier n'existe pas ",
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
                } else if (!Outil::isUnique(['designation'], [$request->designation], $request->id, Questionnaire::class)) {
                    $errors = "Ce type d'appartement existe deja !";
                }

                $item->designation = $request->designation;
                $item->typequestionnaire_id = $request->typequestionnaire;
                $item->reponsetype = $request->reponsetype;
                $searchString = " ";
                $replaceString = "";
                $originalString = $request->designation ;

                $outputString = str_replace($searchString, $replaceString, $originalString);
               // dd($outputString) ;
                $item->nom = $outputString ;

                $item->nombre ="nombre_$outputString";

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
