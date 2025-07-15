<?php

namespace App\Http\Controllers;


use App\Appartement;
use App\Composition;
use App\Constituantpiece;
use App\Contrat;
use App\Demandeintervention;
use App\Demanderesiliation;
use App\Detailcomposition;
use App\Detailconstituant;
use App\Detailequipement;
use App\Detailintervention;
use App\Equipement_observation;
use App\Equipementpiece;
use App\Etatlieu;
use App\Etatlieu_piece;
use App\Etatlieupiece_equipementpiece_constituantpiece;
use App\Imageetatlieupiece;
use App\Immeuble;
use App\Intervention;
use App\Jobs\ImportEntiteFileJob;
use App\Jobs\ImportUserFileJob;
use App\Locataire;
use App\Piece_constituant_observation;
use App\Piece_equipement_observation;
use App\Pieceappartement;
use App\Proprietaire;
use App\Typeappartement;
use App\Typeappartement_piece;
use App\Typepiece;
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


class EtatlieuController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "etatlieus";
    protected $model = Etatlieu::class;
    protected $job = ImportUserFileJob::class;

    public function save(Request $request)
    {
    //    dd($request) ;
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $isupdate = false ;
                $user_connected = Auth::user();
                $emaillocataire = null;
                $item = new Etatlieu();

             //   dd($request);

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $isupdate = true ;
                        $item = Etatlieu::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "L'etat des lieux que vous tentez de modifier n'existe pas ",
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
                //dd($request);

                if (empty($request->designation)) {
                    $errors = "Veuillez renseigner la designation";
                }
                if (empty($request->appartement)) {
                    $errors = "Veuillez renseigner l'appartement";
                }
                if (empty($request->dateredaction)) {
                    $errors = "Veuillez renseigner la date de redaction";
                }
                if (empty($request->etatgenerale)) {
                    $errors = "Veuillez renseigner l'etat generale";
                }

                // if (($request->contrat) && ($request->type)) {
                    if( isset($request->appartement) ) {

                        $appartementTest = Appartement::find(intval($request->appartement));
                        if($appartementTest){
                            // dd($appartementTest);


                            if ($appartementTest->etatlieu == "1") {
                                $contratTest = Contrat::where(['appartement_id' => $request->appartement, 'locataire_id' => $request->locataire])->first() ;

                                if ($contratTest->etat == 1) {
                                    $errors = "Veuillez d'abord valider le contrat";
                                }
                                // dd("entre ici");
                                if ($contratTest) {
                                    $demanderesiliationTest = Demanderesiliation::where('contrat_id' , $contratTest->id)->first() ;
                                    if (!$demanderesiliationTest) {
                                        $errors = "Veuillez d'abord enregister la resiliation de bail du contrat";
                                    }
                                }
                            }
                        }

                    }
                    // dd($errors);

                // }


                if(!$isupdate){

                    $appartement = Appartement::find($request->appartement);
                    if($appartement && $appartement->etatlieu != "1" && $appartement->iscontrat == "1"){
                        $item->type = "entrée" ;
                        $appartement->etatlieu = "1" ;
                        $appartement->save() ;
                    }else if($appartement && $appartement->etatlieu == "1" && $appartement->iscontrat == "1") {
                        $item->type = "sortie" ;
                        $appartement->etatlieu = "0" ;
                        $appartement->save() ;
                    }
                }else{

                    $item->type = $item->type ;
                }

                $item->designation = $request->designation;
                $item->appartement_id = $request->appartement;
                $item->locataire_id = $request->locataire;
                $item->dateredaction = $request->dateredaction;
                $item->etatgenerale = $request->etatgenerale;
                $item->particularite = $request->particularite;

                if (!isset($errors)) {

                    $item->save();

                    if($item->type == "entrée"){

                        $locataireexistant = Locataire::find($item->locataire_id);
                        if(($locataireexistant->emailpersonneacontacter) ){
                            $emaillocataire = $locataireexistant->emailpersonneacontacter ;
                        }
                        else if($locataireexistant->email){
                            $emaillocataire = $locataireexistant->email ;
                        }
                        $user = User::where('email', $emaillocataire)->first();

                        if ($user) {

                            $locataireexistant->user_id = $user->id;
                            $locataireexistant->save() ;
                        }
                        else if (($locataireexistant->nomentreprise)){
                            // dump("user");
                            // dd($emaillocataire) ;
                            $newuser = new User() ;
                            $newuser->image = $default_image = 'assets/images/default.png';
                            $newuser->name = $locataireexistant->nomentreprise;
                            $newuser->email = $locataireexistant->emailpersonneacontacter;
                            $mail = $locataireexistant->emailpersonneacontacter ;
                            $newuser->locataire_id = $request->locataireexistant;
                            $newuser->active = 1;
                            // $pwd = $this->generatepwd() ;
                            Outil::saveUserPassword($newuser,"passer");

                            $item_role = Role::where('id', 2)->first();
                            $newuser->profil = $item_role->name ;
                            $newuser->save();
                            $newuser->syncRoles($item_role);

                            $locataireexistant->user_id = $newuser->id;
                            $locataireexistant->save() ;
                            $text = "Bonjour , votre compte résident GESTIMMO vient d'etre crée! voici vos informations de connection: login: $mail  , mot de passe: passer " ;
                            // dd($mail);
                            //  Outil::envoiEmail($mail,'COMPTE RESIDENT', $text) ;
                        }

                        else if (($locataireexistant->prenom)){
                            // dump("user 1");
                            // dd($emaillocataire) ;
                            //  dd($locataireexistant) ;
                            $prenom = $locataireexistant->prenom ;
                            $nom = $locataireexistant->nom ;
                            $name = $prenom . ' ' . $nom ;
                            $newuser = new User() ;
                            $newuser->image = $default_image = 'assets/images/default.png';
                            $newuser->locataire_id = $request->locataireexistant;;
                            $newuser->name = $name;
                            $newuser->email = $locataireexistant->email;
                            $mail = $locataireexistant->email ;
                            $newuser->active = 1;
                            //   $pwd = $this->generatepwd() ;
                            Outil::saveUserPassword($newuser,"passer");

                            $item_role = Role::where('id' , 2)->first();

                            $newuser->save();
                            $newuser->syncRoles($item_role);
                            $newuser->profil = $item_role->name ;


                            $locataireexistant->user_id = $newuser->id;
                            $locataireexistant->save() ;

                            $text = "Bonjour , votre compte résident GESTIMMO vient d'etre crée. ci apres vous trouverez vos informations de connection: login: $mail  , mot de passe: passer " ;
                            // dd($locataireexistant);
                            Outil::envoiEmail($mail,'COMPTE RESIDENT', $text) ;
                        }
                    } ;

                    $inputs = $request->input() ;

                    $compositions = Composition::All();
                    $constituants = Constituantpiece::All();
                    $equipements = Equipementpiece::All();
                    $etatlieupieces = Etatlieu_piece::All();

                    if(!$isupdate){

                        if($item->type == "entrée"){

                            foreach ($compositions as $composition)
                            {

                                if(isset($inputs["composition_{$composition->id}"])){
                                    $detaiIdcomposition = intval( $inputs["composition_{$composition->id}"]) ;
                                    $etatlieupiece = new Etatlieu_piece() ;
                                    $etatlieupiece->etatlieu_id = $item->id;
                                    $etatlieupiece->composition_id = intval( $inputs["composition_{$composition->id}"]);

                                    $etatlieupiece->save() ;

                                    for ($i = 1 ; $i<= $request->compteurimageetatlieu ; $i++) {

                                        if (!empty($request->file("pieceimagecomposition_{$detaiIdcomposition}_$i"))) {
                                            // POUR UPLOAD DE L'IMAGE
                                            $imageetatlieupiece = new Imageetatlieupiece() ;
                                            $imageetatlieupiece->etatlieupiece_id = $etatlieupiece->id ;
                                            $imageetatlieupiece->imagecompteur = $i ;
                                            $dateHeure = date('Y_m_d_H_i_s');
                                            $fichier = isset($_FILES["pieceimagecomposition_{$detaiIdcomposition}_$i"]['name']) ? $_FILES["pieceimagecomposition_{$detaiIdcomposition}_$i"]['name'] : "";
                                            if (!empty($fichier)) {

                                                $fichier_tmp = $_FILES["pieceimagecomposition_{$detaiIdcomposition}_$i"]['tmp_name'];
                                                $ext = explode('.', $fichier);
                                                $rename = config('view.uploads')[$this->queryName] . "/etatlieupiece_" . $detaiIdcomposition . "." . $i . $dateHeure . "." . end($ext);
                                                move_uploaded_file($fichier_tmp, $rename);
                                                $imageetatlieupiece->image = $rename;

                                            }
                                            $imageetatlieupiece->save() ;
                                        } else if ($request->get('image_erase')) // Permet de supprimer l'image
                                        {

                                            $imageetatlieupiece = new Imageetatlieupiece() ;
                                            $imageetatlieupiece->etatlieupiece_id = $etatlieupiece->id ;
                                            $imageetatlieupiece->imagecompteur = $i ;
                                            $imageetatlieupiece->image = "";
                                            $imageetatlieupiece->save() ;

                                        }
                                        /* else {
                                             $req ="imgetatlieuupdatename_$i" ;
                                             //    dd($request->$req) ;
                                             $cutword = strstr($request->$req, 'uploads');

                                             $cutwordend = substr($cutword, 0, strpos($cutword, "?"));

                                             //dd($cutwordend) ;
                                             $imageetatlieupiece->image = $cutwordend ;
                                         }*/

                                    }

                                    foreach ($constituants as $constituant)
                                    {
                                        if(isset($inputs["observation_{$constituant->id}_{$composition->id}"])){

                                            $detailconstituant = new Detailconstituant() ;
                                            $detailconstituant->etatlieu_piece_id = $etatlieupiece->id ;
                                            $detailconstituant->observation_id = intval( $inputs["observation_{$constituant->id}_{$composition->id}"] ) ;
                                            $detailconstituant->constituantpiece_id = $constituant->id ;
                                            $detailconstituant->commentaire = $inputs["observation_{$constituant->id}_{$composition->id}_commentaire"] ;
                                            $detailconstituant->save() ;

                                        }
                                    }
                                    foreach ($equipements as $equipement)
                                    {

                                        if(isset($inputs["equipement_observation_{$equipement->id}_{$composition->id}"])){

                                            $detailequipement = new Detailequipement() ;
                                            $detailequipement->etatlieu_piece_id = $etatlieupiece->id ;
                                            $detailequipement->observation_id = intval( $inputs["equipement_observation_{$equipement->id}_{$composition->id}"]) ;
                                            $detailequipement->equipementpiece_id = $equipement->id ;
                                            $detailequipement->commentaire = $inputs["equipement_observation_{$equipement->id}_{$composition->id}_commentaire"] ;
                                            $detailequipement->save() ;
                                        }
                                        if(isset($inputs["equipementgenerale_observation_{$equipement->id}_{$request->appartement}"])){

                                            $detailequipement = new Detailequipement() ;
                                            $detailequipement->etatlieu_piece_id = $etatlieupiece->id ;
                                            $detailequipement->observation_id = intval( $inputs["equipementgenerale_observation_{$equipement->id}_{$request->appartement}"]) ;
                                            $detailequipement->equipementpiece_id = $equipement->id ;
                                            $detailequipement->commentaire = $inputs["equipementgenerale_observation_{$equipement->id}_{$request->appartement}_commentaire"] ;
                                            $detailequipement->save() ;
                                        }
                                    }
                                }
                            }
                        }
                        else{

                            // $demandeintervention = new Demandeintervention();
                            // $demandeintervention->designation = "Etat des lieux de sortie / $appartement->nom ";
                            // $demandeintervention->immeuble_id = $appartement->immeuble_id;
                            // $demandeintervention->appartement_id = $request->appartement;
                            // $demandeintervention->locataire_id = $request->locataire;

                            // $demandeintervention->isgeneral = "0";

                            // $demandeintervention->save() ;

                            $contrat = Contrat::where(['appartement_id' => $request->appartement, 'etat' => 2])->first() ;
                            $demanderesiliation = Demanderesiliation::where('contrat_id' , $contrat->id)->first() ;
                            $demanderesiliation->etat = 1 ;
                            $demanderesiliation->save() ;
                            $item->demanderesiliation_id = $demanderesiliation->id ;
                            $item->save() ;
                            // $demandeintervention->demanderesiliation_id = $demanderesiliation->id ;
                            // $demandeintervention->save() ;
                            foreach ($compositions as $composition)
                            {

                                if(isset($inputs["composition_{$composition->id}"])){
                                    $detaiIdcomposition = intval( $inputs["composition_{$composition->id}"]) ;
                                    $etatlieupiece = new Etatlieu_piece() ;
                                    $etatlieupiece->etatlieu_id = $item->id;
                                    $etatlieupiece->composition_id = intval( $inputs["composition_{$composition->id}"]);

                                    $etatlieupiece->save() ;
                                    for ($i = 1 ; $i<= $request->compteurimageetatlieu ; $i++) {

                                        if (!empty($request->file("pieceimagecomposition_{$detaiIdcomposition}_$i"))) {
                                            // POUR UPLOAD DE L'IMAGE
                                            $imageetatlieupiece = new Imageetatlieupiece() ;
                                            $imageetatlieupiece->etatlieupiece_id = $etatlieupiece->id ;
                                            $imageetatlieupiece->imagecompteur = $i ;
                                            $dateHeure = date('Y_m_d_H_i_s');
                                            $fichier = isset($_FILES["pieceimagecomposition_{$detaiIdcomposition}_$i"]['name']) ? $_FILES["pieceimagecomposition_{$detaiIdcomposition}_$i"]['name'] : "";
                                            if (!empty($fichier)) {

                                                $fichier_tmp = $_FILES["pieceimagecomposition_{$detaiIdcomposition}_$i"]['tmp_name'];
                                                $ext = explode('.', $fichier);
                                                $rename = config('view.uploads')[$this->queryName] . "/etatlieupiece_" . $detaiIdcomposition . "." . $i . $dateHeure . "." . end($ext);
                                                move_uploaded_file($fichier_tmp, $rename);
                                                $imageetatlieupiece->image = $rename;

                                            }
                                            $imageetatlieupiece->save() ;
                                        } else if ($request->get('image_erase')) // Permet de supprimer l'image
                                        {

                                            $imageetatlieupiece = new Imageetatlieupiece() ;
                                            $imageetatlieupiece->etatlieupiece_id = $etatlieupiece->id ;
                                            $imageetatlieupiece->imagecompteur = $i ;
                                            $imageetatlieupiece->image = "";
                                            $imageetatlieupiece->save() ;

                                        }
                                        /* else {
                                             $req ="imgetatlieuupdatename_$i" ;
                                             //    dd($request->$req) ;
                                             $cutword = strstr($request->$req, 'uploads');

                                             $cutwordend = substr($cutword, 0, strpos($cutword, "?"));

                                             //dd($cutwordend) ;
                                             $imageetatlieupiece->image = $cutwordend ;
                                         }*/

                                    }

                                    foreach ($constituants as $constituant)
                                    {
                                        if(isset($inputs["observation_{$constituant->id}_{$composition->id}"])){

                                            $detailconstituant = new Detailconstituant() ;
                                            $detailconstituant->etatlieu_piece_id = $etatlieupiece->id ;
                                            $detailconstituant->observation_id = intval( $inputs["observation_{$constituant->id}_{$composition->id}"] ) ;
                                            $detailconstituant->constituantpiece_id = $constituant->id ;
                                            $detailconstituant->commentaire = $inputs["observation_{$constituant->id}_{$composition->id}_commentaire"] ;
                                            $detailconstituant->save() ;

                                            // if(intval( $inputs["observation_{$constituant->id}_{$composition->id}"] ) !== 1){

                                            //     $intervention = new Intervention() ;

                                            //     $typeapppiece = Typeappartement_piece::find($composition->typeappartement_piece_id);
                                            //     $intervention->descriptif = "etat lieu $constituant->designation / $typeapppiece->designation / $appartement->nom  ";
                                            //     $intervention->demandeintervention_id = $demandeintervention->id ;
                                            //     $intervention->etat = "En attente" ;
                                            //     $intervention->save() ;

                                            //     $detailintervention = new Detailintervention() ;
                                            //     $detailintervention->intervention_id = $intervention->id ;
                                            //     $detailintervention->detailconstituant_id = $detailconstituant->id ;
                                            //     $detailintervention->save() ;

                                            // }


                                        }
                                    }
                                    foreach ($equipements as $equipement)
                                    {

                                        if(isset($inputs["equipement_observation_{$equipement->id}_{$composition->id}"])){

                                            $detailequipement = new Detailequipement() ;
                                            $detailequipement->etatlieu_piece_id = $etatlieupiece->id ;
                                            $detailequipement->observation_id = intval( $inputs["equipement_observation_{$equipement->id}_{$composition->id}"]) ;
                                            $detailequipement->equipementpiece_id = $equipement->id ;
                                            $detailequipement->commentaire = $inputs["equipement_observation_{$equipement->id}_{$composition->id}_commentaire"] ;
                                            $detailequipement->save() ;

                                            if(intval( $inputs["equipement_observation_{$equipement->id}_{$composition->id}"]) !== 1){

                                                $intervention = new Intervention() ;

                                                $typeapppiece = Typeappartement_piece::find($composition->typeappartement_piece_id);
                                                $intervention->descriptif = "etat lieu $equipement->designation / $typeapppiece->designation / $appartement->nom  ";
                                                // $intervention->demandeintervention_id = $demandeintervention->id ;
                                                $intervention->etat = "En attente" ;
                                                $intervention->save() ;

                                                $detailintervention = new Detailintervention() ;
                                                $detailintervention->intervention_id = $intervention->id ;
                                                $detailintervention->detailequipement_id = $detailequipement->id ;
                                                $detailintervention->save() ;

                                            }
                                        }

                                        if(isset($inputs["equipementgenerale_observation_{$equipement->id}_{$request->appartement}"])){

                                            $detailequipement = new Detailequipement() ;
                                            $detailequipement->etatlieu_piece_id = $etatlieupiece->id ;
                                            $detailequipement->observation_id = intval( $inputs["equipementgenerale_observation_{$equipement->id}_{$request->appartement}"]) ;
                                            $detailequipement->equipementpiece_id = $equipement->id ;
                                            $detailequipement->commentaire = $inputs["equipementgenerale_observation_{$equipement->id}_{$request->appartement}_commentaire"] ;
                                            $detailequipement->save() ;

                                            // if(intval( $inputs["equipementgenerale_observation_{$equipement->id}_{$request->appartement}"]) !== 1){

                                            //     $intervention = new Intervention() ;

                                            //     $intervention->descriptif = "etat lieu $equipement->designation / Equipement générale / $appartement->nom  ";
                                            //     $intervention->demandeintervention_id = $demandeintervention->id ;
                                            //     $intervention->etat = "En attente" ;
                                            //     $intervention->save() ;

                                            //     $detailintervention = new Detailintervention() ;
                                            //     $detailintervention->intervention_id = $intervention->id ;
                                            //     $detailintervention->detailequipement_id = $detailequipement->id ;
                                            //     $detailintervention->save() ;

                                            // }
                                        }
                                    }
                                }
                            }
                        }




                    }

                    else {

                        $detailconstituants = Detailconstituant::All();
                        $detailequipements = Detailequipement::All();
                        $imagetatlieupieces = Imageetatlieupiece::All() ;
                        foreach ($compositions as $composition)
                        {

                            if(isset($inputs["composition_{$composition->id}"])){

                                $detailcompId = $composition->id ;
                                foreach ($etatlieupieces as $etatlieupiece)
                                {
                                    if($etatlieupiece->composition_id == $composition->id && $etatlieupiece->etatlieu_id == intval($inputs["compositionEtatlieu_{$composition->id}"]) ) {

                                        $etattomodif = Etatlieu_piece::find($etatlieupiece->id);

                                        /*if (!isset($etattomodif->image)) {
                                            $etattomodif->image = "";
                                        }
                                        if (!empty($request->file("pieceimagecomposition_$detailcompId"))) {
                                            // POUR UPLOAD DE L'IMAGE
                                            $dateHeure = date('Y_m_d_H_i_s');
                                            $fichier = isset($_FILES["pieceimagecomposition_$detailcompId"]['name']) ? $_FILES["pieceimagecomposition_$detailcompId"]['name'] : "";
                                            if (!empty($fichier)) {
                                                $fichier_tmp = $_FILES["pieceimagecomposition_$detailcompId"]['tmp_name'];
                                                $ext = explode('.', $fichier);
                                                $rename = config('view.uploads')[$this->queryName] . "/composition_" . $detailcompId . "." . $dateHeure . "." . end($ext);
                                                move_uploaded_file($fichier_tmp, $rename);
                                                $etattomodif->image = $rename;
                                            }
                                        } else if ($request->get('image_erase')) // Permet de supprimer l'image
                                        {
                                            $etattomodif->image = "";
                                        }
                                        else {
                                            $req ="imgpieceimageupdatenamecomposition_$detailcompId" ;

                                            $cutword = strstr($request->$req, 'uploads');

                                            $cutwordend = substr($cutword, 0, strpos($cutword, "?"));

                                            $etattomodif->image = $cutwordend ;
                                        }*/

                                        $etattomodif->save() ;

                                        foreach ($imagetatlieupieces as $image)
                                        {
                                            if($image->etatlieupiece_id == $etattomodif->id){
                                                $image->delete() ;
                                            }
                                        }

                                        for ($i = 1 ; $i<= $request->compteurimageetatlieu ; $i++) {

                                            if (!empty($request->file("pieceimagecomposition_{$detailcompId}_$i"))) {
                                                // POUR UPLOAD DE L'IMAGE
                                                $imageetatlieupiece = new Imageetatlieupiece() ;
                                                $imageetatlieupiece->etatlieupiece_id = $etattomodif->id ;
                                                $imageetatlieupiece->imagecompteur = $i ;
                                                $dateHeure = date('Y_m_d_H_i_s');
                                                $fichier = isset($_FILES["pieceimagecomposition_{$detailcompId}_$i"]['name']) ? $_FILES["pieceimagecomposition_{$detailcompId}_$i"]['name'] : "";
                                                if (!empty($fichier)) {
                                                    $fichier_tmp = $_FILES["pieceimagecomposition_{$detailcompId}_$i"]['tmp_name'];
                                                    $ext = explode('.', $fichier);
                                                    $rename = config('view.uploads')[$this->queryName] . "/etatlieupiece_" . $detailcompId . "." . $i . $dateHeure . "." . end($ext);
                                                    move_uploaded_file($fichier_tmp, $rename);
                                                    $imageetatlieupiece->image = $rename;
                                                }
                                                $imageetatlieupiece->save() ;

                                            } else if ($request->get('image_erase')) // Permet de supprimer l'image
                                            {
                                                $imageetatlieupiece = new Imageetatlieupiece() ;
                                                $imageetatlieupiece->etatlieupiece_id = $etattomodif->id ;
                                                $imageetatlieupiece->imagecompteur = $i ;
                                                $imageetatlieupiece->image = "";
                                                $imageetatlieupiece->save() ;
                                            }
                                             else {

                                                 $req ="imgpieceimageupdatenamecomposition_{$detailcompId}_$i" ;

                                                 if($request->$req !== null) {

                                                     $imageetatlieupiece = new Imageetatlieupiece() ;
                                                     $imageetatlieupiece->etatlieupiece_id = $etattomodif->id ;
                                                     $imageetatlieupiece->imagecompteur = $i ;
                                                     $cutword = strstr($request->$req, 'uploads');

                                                     $cutwordend = substr($cutword, 0, strpos($cutword, "?"));

                                                     //dd($cutwordend) ;
                                                     $imageetatlieupiece->image = $cutwordend;
                                                     $imageetatlieupiece->save();
                                                 }
                                             }

                                        }


                                        foreach ($constituants as $constituant)
                                        {

                                            if( isset($inputs["observation_{$constituant->id}_{$composition->id}"]) ) {

                                                foreach ($detailconstituants as $detailconstituant)
                                                {

                                                    if($detailconstituant->etatlieu_piece_id == $etattomodif->id && $detailconstituant->constituantpiece_id == $constituant->id){

                                                        $detatilconstituantmodif = Detailconstituant::find($detailconstituant->id);

                                                        $detatilconstituantmodif->observation_id = intval($inputs["observation_{$constituant->id}_{$composition->id}"]) ;

                                                        $detatilconstituantmodif->commentaire = $inputs["observation_{$constituant->id}_{$composition->id}_commentaire"] ;

                                                        //dd($detatilequipementmodif) ;
                                                        $detatilconstituantmodif->save() ;
                                                    }

                                                }

                                            }

                                        }


                                        foreach ($equipements as $equipement)
                                        {

                                            if( isset($inputs["equipement_observation_{$equipement->id}_{$composition->id}"]) ) {

                                                foreach ($detailequipements as $detailequipement)
                                                {

                                                    if($detailequipement->etatlieu_piece_id == $etattomodif->id && $detailequipement->equipementpiece_id == $equipement->id){

                                                        $detatilequipementmodif = Detailequipement::find($detailequipement->id);

                                                        $detatilequipementmodif->observation_id = intval($inputs["equipement_observation_{$equipement->id}_{$composition->id}"]) ;

                                                        $detatilequipementmodif->commentaire = $inputs["equipement_observation_{$equipement->id}_{$composition->id}_commentaire"] ;

                                                        $detatilequipementmodif->save() ;


                                                    }

                                                }

                                            }

                                            if( isset($inputs["equipementgenerale_observation_{$equipement->id}_{$request->appartement}"]) ) {

                                                foreach ($detailequipements as $detailequipement)
                                                {

                                                    if($detailequipement->etatlieu_piece_id == $etattomodif->id && $detailequipement->equipementpiece_id == $equipement->id){

                                                        $detatilequipementmodif = Detailequipement::find($detailequipement->id);
                                                        // var_dump($detatilequipementmodif->get());
                                                        $detatilequipementmodif->observation_id = intval($inputs["equipementgenerale_observation_{$equipement->id}_{$composition->id}"]) ;

                                                        $detatilequipementmodif->commentaire = $inputs["equipementgenerale_observation_{$equipement->id}_{$composition->id}_commentaire"] ;

                                                        $detatilequipementmodif->save() ;
                                                    }

                                                }

                                            }


                                           /* if( isset($inputs["observationequipementgeneral_{$equipement->id}"]) ) {

                                                foreach ($detailequipements as $detailequipement)
                                                {

                                                    if($detailequipement->etatlieu_piece_id == $etattomodif->id && $detailequipement->equipementpiece_id == $equipement->id){

                                                        $detatilequipementmodif = Detailequipement::find($detailequipement->id);

                                                        $detatilequipementmodif->observation_id = intval($inputs["observationequipementgeneral_{$equipement->id}"]) ;

                                                        $detatilequipementmodif->commentaire = $inputs["commentaireequipementgeneral_{$equipement->id}"] ;

                                                        $detatilequipementmodif->save() ;


                                                    }

                                                }

                                            }*/

                                        }


                                    }
                                }

                            }
                        }


                    }

                   /* $equipements = Equipementpiece::All();
                    foreach ($equipements as $equipement)
                    {
                        if(isset($inputs["observationequipementgeneral_{$equipement->id}"])){
                            $equipementObservation = new Equipement_observation() ;
                            $equipementObservation->etatlieu_id = $item->id;
                            $equipementObservation->observation_id = intval( $inputs["observationequipementgeneral_{$equipement->id}"]);
                            $equipementObservation->commentaire = $inputs["commentaireequipementgeneral_{$equipement->id}"];
                            $strings = "observationequipementgeneral_{$equipement->id}" ;
                            $equipementObservation->equipement_id = substr("$strings", -1);
                            $equipementObservation->save() ;
                        }
                    }
                    //dd($inputs) ;
                    for ($i = 1 ; $i<=count($inputs)/4 ; $i++) {
                        if(isset($inputs["typepiece{$i}"])){

                            $etatlieuPiece = new Etatlieu_piece() ;
                            $etatlieuPiece->etatlieu_id = $item->id;
                            $etatlieuPiece->typepiece_id = intval( $inputs["typepiece{$i}"]);
                            $etatlieuPiece->save() ;

                            $equipements = Equipementpiece::All();
                            foreach ($equipements as $equipement)
                            {
                                if(isset($inputs["typepiece{$i}_equipement_observation_{$equipement->id}"])){
                                    $pieceEquipementObservation = new Piece_equipement_observation() ;
                                    $pieceEquipementObservation->typepiece_id = intval( $inputs["typepiece{$i}"]);
                                    $pieceEquipementObservation->observation_id = intval( $inputs["typepiece{$i}_equipement_observation_{$equipement->id}"]);
                                    $pieceEquipementObservation->commentaire = $inputs["typepiece{$i}_equipement_commentaire_{$equipement->id}"];
                                    $strings = "typepiece{$i}_equipement_observation_{$equipement->id}" ;
                                    $pieceEquipementObservation->equipementpiece_id = substr("$strings", -1);
                                    $pieceEquipementObservation->save() ;
                                }
                            }

                            $constituants = Constituantpiece::All();
                            foreach ($constituants as $constituant)
                            {
                                if(isset($inputs["typepiece{$i}_constituant_observation_{$constituant->id}"])){
                                    $pieceConstituantObservation = new Piece_constituant_observation() ;
                                    $pieceConstituantObservation->typepiece_id = intval( $inputs["typepiece{$i}"]);
                                    $pieceConstituantObservation->observation_id = intval( $inputs["typepiece{$i}_constituant_observation_{$constituant->id}"]);
                                    $pieceConstituantObservation->commentaire = $inputs["typepiece{$i}_constituant_commentaire_{$constituant->id}"];
                                    $strings = "typepiece{$i}_constituant_observation_{$constituant->id}" ;
                                    $pieceConstituantObservation->constituantpiece_id = substr("$strings", -1);
                                    $pieceConstituantObservation->save() ;
                                }
                            }

                            $etatlieupieceEquipementpiece_constituantpiece = new Etatlieupiece_equipementpiece_constituantpiece() ;
                            $etatlieupieceEquipementpiece_constituantpiece->etatlieu_piece_id = $etatlieuPiece->id;
                            $etatlieupieceEquipementpiece_constituantpiece->piece_equipement_observation_id = $pieceEquipementObservation->id;
                            $etatlieupieceEquipementpiece_constituantpiece->piece_constituant_observation_id = $pieceConstituantObservation->id;
                            $etatlieupieceEquipementpiece_constituantpiece->save();

                            //dd($strings) ;

                            //  dd($pieceEquipementObservation->equipementpiece_id) ;
                            //recuperer l'id de equipement a l'aide de pregmatch
                        } ;
                    }*/

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
