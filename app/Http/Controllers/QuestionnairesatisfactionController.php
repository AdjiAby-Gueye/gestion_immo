<?php

namespace App\Http\Controllers;


use App\Annonce;
use App\Assurance;
use App\Caution;
use App\Contrat;
use App\Document;
use App\Facture;
use App\Immeuble;
use App\Jobs\ImportEntiteFileJob;
use App\Jobs\ImportUserFileJob;
use App\Locataire;
use App\Locataire_message;
use App\Locataire_questionnairesatisfaction;
use App\Message;
use App\Obligationadministrative;
use App\Paiementloyer;
use App\Pieceappartement;
use App\Proprietaire;
use App\Proprietaire_message;
use App\Proprietaire_questionnairesatisfaction;
use App\Questionnairesatisfaction;
use App\Typeappartement;
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


class QuestionnairesatisfactionController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "questionnairesatisfactions";
    protected $model = Questionnairesatisfaction::class;
    protected $job = ImportUserFileJob::class;


    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();


                //dd($request) ;
                $item = new Questionnairesatisfaction();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Questionnairesatisfaction::find($request->id);

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

                if (empty($request->titre)) {
                    $errors = "le titre n'est pas renseigné";
                }
                if (empty($request->contenu)) {
                    $errors = "Veuillez renseigner la question";
                }

                $item->titre = $request->titre;
                $item->contenu = $request->contenu;

                if (!isset($errors)) {

                    $item->save();

                    $inputs = $request->input() ;

                    $locataires = Locataire::All();
                    foreach ($locataires as $locataire)
                    {
                      //  dd($inputs["locataire{$locataire->id}"]) ;
                        if(isset($inputs["locataire{$locataire->id}"])){
                            $locataireQuestionnairesatisfaction = new Locataire_questionnairesatisfaction() ;
                            $locataireQuestionnairesatisfaction->questionnairesatisfaction_id = $item->id;
                            $locataireQuestionnairesatisfaction->locataire_id = intval( $inputs["locataire{$locataire->id}"]);
                            $locataireQuestionnairesatisfaction->save() ;
                        }
                    }

                    $proprietaires = Proprietaire::All();
                    foreach ($proprietaires as $proprietaire)
                    {
                        //  dd($inputs["locataire{$locataire->id}"]) ;
                        if(isset($inputs["proprietaire{$proprietaire->id}"])){
                            $proprietaireQuestionnairesatisfaction = new Proprietaire_questionnairesatisfaction() ;
                            $proprietaireQuestionnairesatisfaction->questionnairesatisfaction_id = $item->id;
                            $proprietaireQuestionnairesatisfaction->proprietaire_id = intval( $inputs["proprietaire{$proprietaire->id}"]);
                            $proprietaireQuestionnairesatisfaction->save() ;
                        }
                    }

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
