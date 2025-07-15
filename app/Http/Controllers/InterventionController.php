<?php

namespace App\Http\Controllers;


use App\Immeuble;
use App\Intervention;
use App\Jobs\ImportEntiteFileJob;
use App\Jobs\ImportUserFileJob;
use App\Pieceappartement;
use App\Prestataire;
use App\Proprietaire;
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


class InterventionController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "interventions";
    protected $model = Intervention::class;
    protected $job = ImportUserFileJob::class;

    public function save(Request $request)
    {
       // dd($request) ;
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();

                $item = new Intervention();

                //   dd($request);
                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Intervention::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "L'intervention que vous tentez de modifier n'existe pas ",
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

                if (empty($request->descriptif)) {
                    $errors = "Veuillez renseigner le descriptif";
                }
                if (empty($request->categorieintervention)) {
                    $errors = "Veuillez renseigner la categorie d'intervention";
                }
                if (empty($request->etat)) {
                    $errors = "Veuillez renseigner l'etat d'avancement";
                }

                //   dd($request);
                if ($request->demandeintervention) {
                    $item->demandeintervention_id = $request->demandeintervention;
                }
                if ($request->id_demandeintervention) {
                    $item->demandeintervention_id = $request->id_demandeintervention;
                }
                $item->descriptif = $request->descriptif;
                $item->dateintervention = $request->dateintervention;
                $item->datefinintervention = $request->datefinintervention;
                $item->categorieintervention_id = $request->categorieintervention;
                $item->etat = $request->etat;
                // $item->prestataire_id = $request->prestataire;
                // $item->user_id = $request->user;
                $prestataire = Prestataire::find($request->prestataire);
                $user = User::find($request->user) ;
             //   $item->membreequipegestion_id = $request->employe;

                if (!isset($errors)) {

                    $item->save();

                    if($prestataire){
                        $item->prestataire_id = $prestataire->id;
                        $item->save() ;
                        if (!isset($request->id)) {
                            $text = "Bonjour , vous avez une nouvelle intervention a faire : $item->descriptif " ;
                            Outil::envoiEmail($prestataire->email,'NOUVELLE INTERVENTION', $text) ;
                        }

                    }else if($user){
                        $item->user_id = $request->user;
                        $item->save();
                        if (!isset($request->id)) {
                            $text = "Bonjour , vous avez une nouvelle intervention a faire : $item->descriptif ";
                            Outil::envoiEmail($user->email, 'NOUVELLE INTERVENTION', $text);
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
