<?php

namespace App\Http\Controllers;


use App\Documentappartement;
use App\User;
use App\Outil;
use App\Entite;
use App\Appartement;
use App\Composition;
use App\DomaineDetude;
use App\Typeappartement;
use App\UserDepartement;
use App\Imageappartement;
use App\Imagecomposition;
use App\Pieceappartement;
use App\Detailcomposition;
use Illuminate\Http\Request;
use App\Typeappartement_piece;
use App\Jobs\ImportUserFileJob;
use App\Jobs\ImportEntiteFileJob;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Jobs\ImportAppartementFileJob;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class AppartementController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "appartements";
    protected $model = Appartement::class;
    protected $job = ImportAppartementFileJob::class;


    function generateCodeAppartement() {

        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double)microtime()*1000000);
        $i = 0;
        $pass = '' ;

        while ($i <= 5) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }

        return $pass;

    }


    public function save(Request $request)
    {

        // dd($request->all()) ;
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $isupdate = false ;
                $item_entite = null;
                $user_connected = Auth::user();

                $item = new Appartement();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Appartement::find($request->id);
                        $isupdate = true ;
                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "L'appartement que vous tentez de modifier n'existe pas ",
                            );
                            return $retour;
                        }else{

                            if ($request->entite == "SCI" || $request->entite == "SERTEM") {
                                $item->isassurance = $item->isassurance ;
                                $item->iscontrat = $item->iscontrat ;
                                $item->islocataire = $item->islocataire ;
                                // $item->etatlieu = $item->etatlieu ;
                            }

                            $item->codeappartement = $item->codeappartement ;

                        }
                    } else {
                        $retour = array(
                            "data" => null,
                            "error" => "L'id doit être un nombre entier",
                        );
                        return $retour;
                    }
                }
                // dd($request);
                if ($request->entite == "SCI"  || $request->entite == "SERTEM") {
                    if (empty($request->nom)) {
                        $errors = "Veuillez renseigner le nom";
                    }
                    if (empty($request->immeuble) && empty($request->immeuble_id)) {
                        $errors = "Veuillez renseigner l'immeuble";
                    }
                    if (empty($request->frequencepaiementappartement)) {
                        $errors = "Veuillez renseigner la frequence de paiement";
                    }
                    if (empty($request->etatappartement)) {
                        $errors = "Veuillez renseigner l'etat de l'appartement";
                    }
                    if (empty($request->typeappartement)) {
                        $errors = "Veuillez renseigner le type de l'appartement";
                    }
                    if (empty($request->proprietaire)) {
                        $errors = "Veuillez renseigner le proprietaire de l'appartement";
                    }
                }

                if ($request->entite == "RID") {
                    if (empty($request->lot)) {
                        $errors = "Veuillez renseigner le lot";
                    }
                    if (empty($request->ilot)) {
                        $errors = "Veuillez renseigner l'ilot";
                    }
                    if (empty($request->prixvilla)) {
                        $errors = "Veuillez renseigner le prix de la villa";
                    }
                    if (empty($request->maturite)) {
                        $errors = "Veuillez renseigner la maturite en année";
                    }
                    if (empty($request->typevilla)) {
                        $errors = "Veuillez renseigner le type de villa";
                    }
                    if (empty($request->periodicite)) {
                        $errors = "Veuillez renseigner la périodicité ";
                    }
                    if (empty($request->acomptevilla)) {
                        $errors = "Veuillez renseigner l'acompte villa ";
                    }
                }

                if(empty($request->entite))
				{
					$errors = "Veuillez definir l'entité";
                } else if(isset($request->entite)){
                    $item_entite = Entite::where('code', $request->entite)->first();
                }





                $codeAppartement = $this->generateCodeAppartement() ;
                if ($request->entite == "SCI"  || $request->entite == "SERTEM") {
                    $item->nom = $request->nom;
                    $item->immeuble_id = $request->immeuble;
                    if (!empty($request->immeuble_id)) {
                        $item->immeuble_id = $request->immeuble_id;
                    }
                    $item->proprietaire_id = $request->proprietaire;
                    $item->frequencepaiementappartement_id = $request->frequencepaiementappartement;
                    $item->typeappartement_id = $request->typeappartement;
                    $item->etatappartement_id = $request->etatappartement;

                    $item->niveau = $request->niveau;
                    $item->superficie = $request->superficie;
                    $item->typevente = intval($request->typevente);
                    if(isset($request->montantvilla)){
                        $item->montantvilla = intval($request->montantvilla);
                    }
                    if(isset($request->prixappartement)){
                        $item->prixappartement = intval($request->prixappartement);
                    }

                }

                if ($request->entite == "RID") {

                    $item->lot = $request->lot;
                    $item->ilot_id = $request->ilot;
                    $item->prixvilla = $request->prixvilla;
                    $item->acomptevilla = $request->acomptevilla;
                    $item->maturite = $request->maturite;
                    $item->periodicite_id = $request->periodicite;
                    $item->typeappartement_id = $request->typevilla;
                    $item->superficie = $request->superficievilla;


                }


                $item->entite_id    = $item_entite->id;


                if(!$isupdate) {
                    $item->codeappartement = $codeAppartement;
                    if ($request->entite == "SCI"  || $request->entite == "SERTEM") {
                        $item->isassurance = 0;
                        $item->iscontrat = 0;
                        $item->islocataire = 0;
                        // $item->etatlieu = "0";
                    }

                }

                if (!isset($errors)) {

                    if (!empty($request->file('image'))) {
                        // POUR UPLOAD DE L'IMAGE
                        $dateHeure = date('Y_m_d_H_i_s');
                        $fichier = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : "";
                        if (!empty($fichier)) {
                            $fichier_tmp = $_FILES['image']['tmp_name'];
                            $ext = explode('.', $fichier);
                            $rename = config('view.uploads')[$this->queryName] . "/appartement_" . $dateHeure . "." . end($ext);
                            move_uploaded_file($fichier_tmp, $rename);
                            $item->image = $rename;
                        }
                    } else if ($request->get('image_erase')) // Permet de supprimer l'image
                    {
                        $item->image = '' ;
                    }

                    if (isset($request->tva) && ($request->tva == 'on' || $request->tva == true)) {
                        $request['tva']                = 1;
                    } else {
                        $request['tva']                = 0;
                    }

                    if (isset($request->brs) && ($request->brs == 'on' || $request->brs == true)) {
                        $request['brs']                = 1;
                    } else {
                        $request['brs']                = 0;
                    }

                    if (isset($request->tlv) && ($request->tlv == 'on' || $request->tlv == true)) {
                        $request['tlv']                = 1;
                    } else {
                        $request['tlv']                = 0;
                    }

                    if (isset($request->position) && ($request->position == 'on' || $request->position == true)) {
                        $request['position']                = 1;
                    } else {
                        $request['position']                = 0;
                    }

                    //Renseigner les nuveaux champs avant la validation
                    $item->proprietaire_id = $request->proprietaire;
                    $item->contratproprietaire_id = $request->contratproprietaire_id;
                    $item->commissionvaleur = $request->commissionvaleur;
                    $item->commissionpourcentage = $request->commissionpourcentage;
                    $item->montantloyer = $request->montantloyer;
                    $item->montantcaution = $request->montantcaution;
                    $item->tva = $request->tva;
                    $item->brs = $request->brs;
                    $item->tlv = $request->tlv;
                    $item->position = $request->position;

                    $item->save();



                    $default_image = 'assets/images/default.png';

                    $typeappartement_piece_equipement_composition = json_decode($request->typeappartement_piece_equipepementpiece_typeappartement_piece , true) ;
                    // dd($typeappartement_typepiece) ;

                    if(!$isupdate){
                        if ($request->entite == "SCI") {
                            foreach ($typeappartement_piece_equipement_composition as $type)
                            {

                                $detailIdpiece = intval($type["detailId"]) ;
                              //dd($typeappartement_piece_equipement_composition) ;
                                $testappcomp = Composition::All();
                                $iter = 0 ;
                                if(count($testappcomp) > 0 ){

                                    foreach ($testappcomp as $test)
                                    {

                                        if($test->appartement_id !== $item->id && $test->typeappartement_piece_id !== intval($type["detailId"])){

                                            $iter = 0 ;

                                        }else if($test->appartement_id == $item->id && $test->typeappartement_piece_id == intval($type["detailId"])){

                                            $iter++ ;

                                        }

                                    }

                                    if($iter >0) {
                                        $newDetailComposition = new Detailcomposition() ;

                                        $newDetailComposition->equipement_id = intval($type["equipement_id"]);
                                        if($type["detailId"] != 0){
                                            $newDetailComposition->composition_id = $test->id;
                                            $newDetailComposition->idDetailtypeappartement = intval($type["detailId"]);

                                        }else if($type["detailId"] == 0){
                                            $newDetailComposition->appartement_id = $item->id;
                                        }
                                        $newDetailComposition->save() ;
                                    }else{
                                        if($type["detailId"] != 0){

                                            $newComposition = new Composition() ;
                                            $newComposition->typeappartement_piece_id = intval($type["detailId"]);
                                            $newComposition->appartement_id = $item->id;
                                            $iddd = intval($type["detailId"]) ;

                                            $superficie = "superficiecomposition_$iddd" ;
                                            $newComposition->superficie = $request->$superficie;

                                            $newComposition->save() ;

                                            for ($i = 1 ; $i<= $request->compteurimage ; $i++) {

                                                if (!empty($request->file("pieceimage_{$detailIdpiece}_$i"))) {

                                                    $imagecomposition = new Imagecomposition() ;
                                                    $imagecomposition->composition_id = $newComposition->id ;
                                                    $imagecomposition->imagecompteur = $i ;
                                                    // POUR UPLOAD DE L'IMAGE
                                                    $dateHeure = date('Y_m_d_H_i_s');
                                                    $fichier = isset($_FILES["pieceimage_{$detailIdpiece}_$i"]['name']) ? $_FILES["pieceimage_{$detailIdpiece}_$i"]['name'] : "";
                                                    if (!empty($fichier)) {
                                                        $fichier_tmp = $_FILES["pieceimage_{$detailIdpiece}_$i"]['tmp_name'];
                                                        $ext = explode('.', $fichier);
                                                        $rename = config('view.uploads')[$this->queryName] . "/composition_" . $detailIdpiece . "." . $i . $dateHeure . "." . end($ext);
                                                        move_uploaded_file($fichier_tmp, $rename);
                                                        $imagecomposition->image = $rename;
                                                        $imagecomposition->save() ;
                                                    }

                                                    //  dd($imagecomposition) ;
                                                } else if ($request->get('image_erase')) // Permet de supprimer l'image
                                                {
                                                    $imagecomposition = new Imagecomposition() ;
                                                    $imagecomposition->composition_id = $newComposition->id ;
                                                    $imagecomposition->save() ;
                                                }
                                            }
                                        }


                                        $newDetailComposition = new Detailcomposition() ;

                                        if($type["detailId"] != 0){
                                          //  dd($type);
                                            $newDetailComposition->composition_id = $newComposition->id;
                                            $newDetailComposition->idDetailtypeappartement = intval($type["detailId"]);

                                        }else if($type["detailId"] == 0){

                                           // dd($type);
                                            $newDetailComposition->appartement_id = $item->id;

                                        }
                                        $newDetailComposition->equipement_id = intval($type["equipement_id"]);
                                        $newDetailComposition->save() ;
                                    }
                                }else {

                                    if($type["detailId"] != 0){

                                        $newComposition = new Composition() ;
                                        $newComposition->typeappartement_piece_id = intval($type["detailId"]);
                                        $newComposition->appartement_id = $item->id;

                                        $iddd = intval($type["detailId"]) ;
                                        $superficie = "superficiecomposition_$iddd" ;
                                        $newComposition->superficie = $request->$superficie;
                                        $newComposition->save();

                                    }


                                    // faire un find () typepieceappartement_piece_id

                                    for ($i = 1 ; $i<= $request->compteurimage ; $i++) {


                                        if (!empty($request->file("pieceimage_{$detailIdpiece}_$i"))) {
                                            // POUR UPLOAD DE L'IMAGE
                                            $imagecomposition = new Imagecomposition();
                                            $imagecomposition->composition_id = $newComposition->id;
                                            $imagecomposition->imagecompteur = $i;
                                            $dateHeure = date('Y_m_d_H_i_s');
                                            $fichier = isset($_FILES["pieceimage_{$detailIdpiece}_$i"]['name']) ? $_FILES["pieceimage_{$detailIdpiece}_$i"]['name'] : "";
                                            if (!empty($fichier)) {
                                                $fichier_tmp = $_FILES["pieceimage_{$detailIdpiece}_$i"]['tmp_name'];
                                                $ext = explode('.', $fichier);
                                                $rename = config('view.uploads')[$this->queryName] . "/composition_" . $detailIdpiece . "." . $i . $dateHeure . "." . end($ext);
                                                move_uploaded_file($fichier_tmp, $rename);
                                                $imagecomposition->image = $rename;
                                                $imagecomposition->save() ;
                                            }

                                        } else if ($request->get('image_erase')) // Permet de supprimer l'image
                                        {
                                            $imagecomposition = new Imagecomposition();
                                            $imagecomposition->composition_id = $newComposition->id;
                                            $imagecomposition->image = "";
                                            $imagecomposition->save() ;
                                        }
                                    }

                                    $newDetailComposition = new Detailcomposition() ;
                                    if($type["detailId"] != 0){

                                        $newDetailComposition->composition_id = $newComposition->id;
                                        $newDetailComposition->idDetailtypeappartement = intval($type["detailId"]);

                                    }else if($type["detailId"] == 0){

                                        $newDetailComposition->appartement_id = $item->id;

                                    }
                                    $newDetailComposition->equipement_id = intval($type["equipement_id"]);
                                    $newDetailComposition->save() ;
                                }

                            }



                        }


                        for ($i = 1 ; $i<= $request->compteurimage2 ; $i++) {
                            $imageappartement = new Imageappartement() ;
                            $imageappartement->appartement_id = $item->id ;
                            $imageappartement->imagecompteur = $i ;
                            if (!empty($request->file("appartement_$i"))) {

                                // POUR UPLOAD DE L'IMAGE
                                $dateHeure = date('Y_m_d_H_i_s');
                                $fichier = isset($_FILES["appartement_$i"]['name']) ? $_FILES["appartement_$i"]['name'] : "";
                                if (!empty($fichier)) {

                                    $fichier_tmp = $_FILES["appartement_$i"]['tmp_name'];
                                    $ext = explode('.', $fichier);
                                    $rename = config('view.uploads')[$this->queryName] . "/appartement_" . $i . $dateHeure . "." . end($ext);
                                    move_uploaded_file($fichier_tmp, $rename);
                                    $imageappartement->image = $rename;
                                    $imageappartement->save() ;

                                }
                            } else if ($request->get('image_erase')) // Permet de supprimer l'image
                            {
                                $imageappartement->image = $default_image;
                                $imageappartement->save() ;
                            }


                        }
                    }

                    else{

                        $imageappartements = Imageappartement::All() ;

                        foreach ($imageappartements as $image)
                        {
                            if($image->appartement_id == $item->id){

                                $image->delete() ;

                            }
                        }

                        for ($i = 1 ; $i<= $request->compteurimage2 ; $i++) {

                            $imageappartement = new Imageappartement() ;
                            $imageappartement->appartement_id = $item->id ;
                            $imageappartement->imagecompteur = $i ;

                            if (!empty($request->file("appartement_$i"))) {

                                // POUR UPLOAD DE L'IMAGE
                                $dateHeure = date('Y_m_d_H_i_s');
                                $fichier = isset($_FILES["appartement_$i"]['name']) ? $_FILES["appartement_$i"]['name'] : "";
                                if (!empty($fichier)) {
                                    $fichier_tmp = $_FILES["appartement_$i"]['tmp_name'];
                                    $ext = explode('.', $fichier);
                                    $rename = config('view.uploads')[$this->queryName] . "/appartement_" . $i . $dateHeure . "." . end($ext);
                                    move_uploaded_file($fichier_tmp, $rename);
                                    $imageappartement->image = $rename;
                                }
                            } else if ($request->get('image_erase')) // Permet de supprimer l'image
                            {

                                $imageappartement->image = $default_image;

                            }
                            else {

                                $req ="imgappartementupdatename_$i" ;
                              //    dd($request->$req) ;
                                $cutword = strstr($request->$req, 'uploads');

                                $cutwordend = substr($cutword, 0, strpos($cutword, "?"));

                                //dd($cutwordend) ;
                                $imageappartement->image = $cutwordend ;

                            }

                            $imageappartement->save() ;
                        }

                        if ($request->entite == "SCI") {
                            foreach ($typeappartement_piece_equipement_composition as $type)
                            {

                                $detailIdpiece = intval($type["detailId"]) ;
                                $testappcomp = Composition::All();
                                $detailscomp = Detailcomposition::All();
                                $imagecompositions = Imagecomposition::All() ;
                                $appartements = Appartement::All() ;
                                if(count($testappcomp) > 0 ){

                                    foreach ($testappcomp as $test)
                                    {

                                        if($test->appartement_id == $item->id && $test->typeappartement_piece_id == intval($type["detailId"])){

                                            $comp=Composition::find($test->id);

                                            $comp->typeappartement_piece_id = intval($type["detailId"]);
                                            $comp->appartement_id = $item->id;

                                          /*  if (!empty($request->file("pieceimage_{$detailIdpiece}_$i"))) {
                                                // POUR UPLOAD DE L'IMAGE
                                                $dateHeure = date('Y_m_d_H_i_s');
                                                $fichier = isset($_FILES["pieceimage_{$detailIdpiece}_$i"]['name']) ? $_FILES["pieceimage_{$detailIdpiece}_$i"]['name'] : "";
                                                if (!empty($fichier)) {
                                                    $fichier_tmp = $_FILES["pieceimage_{$detailIdpiece}_$i"]['tmp_name'];
                                                    $ext = explode('.', $fichier);
                                                    $rename = config('view.uploads')[$this->queryName] . "/composition_" . $detailIdpiece . "." . $dateHeure . "." . end($ext);
                                                    move_uploaded_file($fichier_tmp, $rename);
                                                    $comp->image = $rename;
                                                }
                                            } else if ($request->get('image_erase')) // Permet de supprimer l'image
                                            {
                                                $comp->image = "";
                                            }
                                            else {
                                                $req ="imgpieceimageupdatename_{$detailIdpiece}_$i" ;
                                                //  dd($req) ;
                                                $cutword = strstr($request->$req, 'uploads');

                                                $cutwordend = substr($cutword, 0, strpos($cutword, "?"));

                                                $comp->image = $cutwordend ;
                                            }*/

                                            $comp->save() ;

                                            foreach ($imagecompositions as $image)
                                            {
                                                if($image->composition_id == $comp->id){
                                                    $image->delete() ;
                                                }
                                            }

                                            for ($i = 1 ; $i<= $request->compteurimage ; $i++) {

                                                if (!empty($request->file("pieceimage_{$detailIdpiece}_$i"))) {
                                                    // POUR UPLOAD DE L'IMAGE
                                                    $imagecomposition = new Imagecomposition();
                                                    $imagecomposition->composition_id = $comp->id;
                                                    $imagecomposition->imagecompteur = $i;
                                                    $dateHeure = date('Y_m_d_H_i_s');
                                                    $fichier = isset($_FILES["pieceimage_{$detailIdpiece}_$i"]['name']) ? $_FILES["pieceimage_{$detailIdpiece}_$i"]['name'] : "";
                                                    if (!empty($fichier)) {
                                                        $fichier_tmp = $_FILES["pieceimage_{$detailIdpiece}_$i"]['tmp_name'];
                                                        $ext = explode('.', $fichier);
                                                        $rename = config('view.uploads')[$this->queryName] . "/composition_" . $detailIdpiece . "." . $i . $dateHeure . "." . end($ext);
                                                        move_uploaded_file($fichier_tmp, $rename);
                                                        $imagecomposition->image = $rename;
                                                        $imagecomposition->save() ;
                                                    }

                                                } else if ($request->get('image_erase')) // Permet de supprimer l'image
                                                {
                                                    $imagecomposition = new Imagecomposition();
                                                    $imagecomposition->composition_id = $comp->id;
                                                    $imagecomposition->image = "";
                                                    $imagecomposition->save() ;
                                                }
                                                else {
                                                    $req ="imgpieceimageupdatename_{$detailIdpiece}_$i" ;

                                                  //  dd($request) ;
                                                    if($request->$req !== null){

                                                        $cutword = strstr($request->$req, 'uploads');
                                                        $cutwordend = substr($cutword, 0, strpos($cutword, "?"));

                                                            $imagecomposition = new Imagecomposition();
                                                            $imagecomposition->composition_id = $comp->id;
                                                            $imagecomposition->imagecompteur = $i;
                                                            $imagecomposition->image = $cutwordend ;
                                                            $imagecomposition->save() ;

                                                    } ;
                                                }
                                            }

                                            $testeur  = 0 ;

                                                        foreach ($detailscomp as $detailcomp)
                                                        {
                                                            if( $detailcomp->composition_id == $comp->id && $detailcomp->equipement_id == intval($type["equipement_id"]) ){

                                                             //   dd($item->id) ;
                                                                $testeur++ ;

                                                            }
                                                        }

                                                        if($testeur == 0 ){
                                                          //  dd($testeur) ;
                                                            $newDetailComposition = new Detailcomposition() ;
                                                            if($type["detailId"] != 0){

                                                                $newDetailComposition->composition_id = $comp->id;
                                                                $newDetailComposition->idDetailtypeappartement = intval($type["detailId"]);
                                                                $newDetailComposition->equipement_id = intval($type["equipement_id"]);
                                                                $newDetailComposition->save() ;

                                                            }

                                                        }

                                                }
                                        }


                                    $testeur2  = 0 ;
                                    foreach ($detailscomp as $detailcomp)
                                    {
                                        if( $detailcomp->appartement_id == $item->id && $detailcomp->equipement_id == intval($type["equipement_id"]) ){

                                           // dd($detailcomp) ;
                                            $testeur2++ ;

                                        }
                                    }

                                    if($testeur2 == 0 ){
                                        //  dd($testeur) ;
                                        $newDetailComposition = new Detailcomposition() ;
                                        if($type["detailId"] == 0){

                                            $newDetailComposition->appartement_id = $item->id;
                                            $newDetailComposition->equipement_id = intval($type["equipement_id"]);
                                            $newDetailComposition->save() ;
                                        }
                                    }
                                }
                            }
                        }

                    }

                    if ($request->entite == "RID") {

                        if ($isupdate) {
                            $composAppart = Composition::where("appartement_id" , $item->id)->get();

                            if (count($composAppart) > 0) {
                                foreach($composAppart as $compo) {

                                    if ($compo->typeappartement_piece->typeappartement_id != $item->typeappartement_id) {
                                        $compo->delete();
                                        $compo->forceDelete();
                                    }
                                }

                            }
                        }


                        $tabTypepieceAppartement = Typeappartement_piece::where('typeappartement_id' , $item->typeappartement_id)->get();
                        if(count($tabTypepieceAppartement) > 0){
                            foreach ($tabTypepieceAppartement as $value) {
                                $newCompositionAppartemnt = new Composition();
                                $newCompositionAppartemnt->typeappartement_piece_id = $value->id;
                                $newCompositionAppartemnt->appartement_id = $item->id;
                                if ($request->input("niveaupiece_".$value->id)) {
                                    $newCompositionAppartemnt->niveauappartement_id = intval($request->input("niveaupiece_".$value->id));
                                }

                                $newCompositionAppartemnt->save();
                            }
                        }

                    }

                    $this->saveDocument($request, $item);


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

    public function saveDocument(Request $request, $appartement)
    {
        $documents = [];

        if (isset($request->document)) {
            $array_rey = json_decode($request->document, true);

            if (count($array_rey) > 0) {
                $documents = $array_rey;
            }
        }
        // dd($request->all());

        if (count($documents) > 0) {
            foreach ($documents as $doc) {
                // $docu = Documentappartement::where('appartement_id', $appartement->id)->first();
                // if (!$docu) {
                    if (isset($doc['numero']) && $request->file('fichier_' . $doc['numero'])) {
                        $uploadedFile = Outil::uploadFile($request, 'fichier_' . $doc['numero'], public_path('uploads/documentsappartement'));
                        $document = Documentappartement::create([
                            "nom" => $doc['nom'],
                            "document" => 'uploads/documentsappartement/' . $uploadedFile['name'],
                            "appartement_id" => $appartement->id
                        ]);
                    }
                // }
            }
        }
    }


}

