<?php

namespace App\Http\Controllers;


use App\User;
use App\Outil;
use App\Entite;
use App\Immeuble;
use App\Locataire;
use App\Appartement;
use App\Proprietaire;
use App\DomaineDetude;
use App\Equipegestion;
use App\Typeappartement;
use App\UserDepartement;
use App\Pieceappartement;
use App\Demandeintervention;
use Illuminate\Http\Request;
use App\Jobs\ImportUserFileJob;
use App\Jobs\ImportEntiteFileJob;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class DemandeinterventionController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "demandeinterventions";
    protected $model = Demandeintervention::class;
    protected $job = ImportUserFileJob::class;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $locataire = null;
                $appartement = null;
                $user_connected = Auth::user();

                $item = new Demandeintervention();

               // dd($request) ;
                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Demandeintervention::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "La demande d'intervention que vous tentez de modifier n'existe pas ",
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
                    $errors = "Veuillez renseigner le descriptif de la demande";
                }

                //dd($request);
                if (empty($request->appartement)) {
                    $item->isgeneral = '1' ;
                }else{
                    $item->isgeneral = '0' ;
                    $locataire = Locataire::find($request->locataire);
                    if (!$locataire) {
                        $errors = "Veuillez renseigner le locataire";
                    }
                    $appartement = Appartement::find($request->appartement);
                    if (!$appartement) {
                        $errors = "Veuillez renseigner l'appartement";
                    }
                }
                $item->designation = $request->designation;
                $item->immeuble_id = $request->immeuble;
                $item->appartement_id = $request->appartement;
                $item->locataire_id = $request->locataire;
                $item->typepiece_id = $request->partiecommune;
                $item->membreequipegestion_id = $request->membreequipegestion;


                if (!isset($errors)) {
                    $entite = Entite::where("code" , "SCI")->first();
                    $gestionnaire = $entite->gestionnaire;

                    if (!empty($request->file('image'))) {
                        // POUR UPLOAD DE L'IMAGE
                        $dateHeure = date('Y_m_d_H_i_s');
                        $fichier = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : "";
                        if (!empty($fichier)) {
                            $fichier_tmp = $_FILES['image']['tmp_name'];
                            $ext = explode('.', $fichier);
                            $rename = config('view.uploads')[$this->queryName] . "/demandeintervention_" . $dateHeure . "." . end($ext);
                            move_uploaded_file($fichier_tmp, $rename);
                            $item->image = $rename;
                        }
                    } else if ($request->get('image_erase')) // Permet de supprimer l'image
                    {
                        $item->image = '' ;
                    }
                    
                    $item->save();

                    if(isset($request->locataire)){
                        $item->locataire_id = $request->locataire;

                        $item->save() ;
                        if (!isset($request->id)) {
                            $newText = "";
                            $nomlocataire = "";
                            if ( isset($locataire) ) {
                                if ($locataire->nom) {
                                    $nomlocataire = $locataire->prenom . " " . $locataire->nom;
                                } else if ($locataire->nomentreprise) {
                                    $nomlocataire = $locataire->nomentreprise;
                                }

                                $newtext = "pour le locataire : ".$nomlocataire.", concernant l'appartement ".$appartement->nom;
                            }

                            if ( isset($gestionnaire) && isset($gestionnaire->email) ) {
                               
                                $text = "Bonjour , une nouvelle demande d'intervention est soumise : $item->designation ".$newtext ;
                                Outil::envoiEmail($gestionnaire->email,'NOUVELLE INTERVENTION', $text , "maileur" , null , [$locataire->email]) ;
                            }


                            
                         
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
