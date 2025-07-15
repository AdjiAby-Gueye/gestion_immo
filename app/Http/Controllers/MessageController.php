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
use App\Message;
use App\Obligationadministrative;
use App\Paiementloyer;
use App\Pieceappartement;
use App\Proprietaire;
use App\Proprietaire_message;
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


class MessageController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "messages";
    protected $model = Message::class;
    protected $job = ImportUserFileJob::class;


    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();

              // dd($request) ;
                $item = new Message();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Message::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "Le message que vous tentez de modifier n'existe pas ",
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

                if (empty($request->objet)) {
                    $errors = "l'objet n'est pas renseigné";
                }
                if (empty($request->contenu)) {
                    $errors = "Veuillez renseigner le contenu";
                }

                $item->objet = $request->objet;
                $item->contenu = $request->contenu;

                if (!isset($errors)) {

                    $item->save();

                    $inputs = $request->input() ;

                    $locataireChamps = array_filter($inputs, function ($key) {
                        return substr($key, 0, 9) === 'locataire';
                    }, ARRAY_FILTER_USE_KEY);
                    $proprietaireChamps = array_filter($inputs, function ($key) {
                        return substr($key, 0, 12) === 'proprietaire';
                    }, ARRAY_FILTER_USE_KEY);

                    // dd($proprietaireChamps);
                    if (!empty($locataireChamps)) {
                        foreach ($locataireChamps as $locataire){
                            $locataireMessage = new Locataire_message() ;
                            $locataireMessage->message_id = $item->id;
                            $locataireMessage->locataire_id = intval( $locataire);
                            $locataireMessage->save() ;
                        }
                    }
                    if (!empty($proprietaireChamps)) {
                        foreach ($proprietaireChamps as $proprietaire){
                            $proprietaireMessage = new Proprietaire_message() ;
                            $proprietaireMessage->message_id = $item->id;
                            $proprietaireMessage->proprietaire_id = intval( $proprietaire);
                            $proprietaireMessage->save() ;
                        }
                    }

                    // $locataires = Locataire::All();
                    // foreach ($locataires as $locataire)
                    // {
                    //    dd($inputs["locataire{$locataire->id}"]) ;
                    //     if(isset($inputs["locataire{$locataire->id}"])){
                    //         $locataireMessage = new Locataire_message() ;
                    //         $locataireMessage->message_id = $item->id;
                    //         $locataireMessage->locataire_id = intval( $inputs["locataire{$locataire->id}"]);
                    //         $locataireMessage->save() ;
                    //     }
                    // }

                    // $proprietaires = Proprietaire::All();
                    // foreach ($proprietaires as $proprietaire)
                    // {
                    //     //  dd($inputs["locataire{$locataire->id}"]) ;
                    //     if(isset($inputs["proprietaire{$proprietaire->id}"])){
                    //         $proprietaireMessage = new Proprietaire_message() ;
                    //         $proprietaireMessage->message_id = $item->id;
                    //         $proprietaireMessage->proprietaire_id = intval( $inputs["proprietaire{$proprietaire->id}"]);
                    //         $proprietaireMessage->save() ;
                    //     }
                    // }

                   // dd($request->files->all()) ;
                    $i = 1;
                    foreach ($request->files->all() as $file) {
                        $filenameWithExt = $file->getClientOriginalName();

                        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        //  dd($filename) ;
                        $extension = $file->getClientOriginalExtension();

                        $fileNameToStore = $filename . '-' . time() . '.' . $extension;
                        // dd($fileNameToStore) ;
                        $path = $request->file("document${i}")->storeAs('uploads/documents', $fileNameToStore);
                        //     dd($path) ;
                        $fichier = isset($_FILES["document${i}"]['name']) ? $_FILES["document${i}"]['name'] : "";
                        // dd(config('view.uploads'));
                        if (!empty($fichier)) {
                            $dateHeure = date('Y_m_d_H_i_s');
                            $fichier_tmp = $_FILES["document${i}"]['tmp_name'];
                            $ext = explode('.', $fichier);
                            $rename = config('view.uploads')['documents'] . "/documentmessage_" . $dateHeure . $i . "." . end($ext);
                            move_uploaded_file($fichier_tmp, $rename);
                            // dd($rename) ;
                            $document = new Document() ;
                            $document->chemin = $rename ;
                            $document->typedocument_id = 1 ;
                            $document->message_id = $item->id ;
                            $document->save() ;

                        }
                        $i++;




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
