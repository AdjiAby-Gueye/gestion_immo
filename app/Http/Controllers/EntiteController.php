<?php

namespace App\Http\Controllers;

use App\Infobancaire;
use App\User;
use App\Outil;
use App\Entite;
use App\Locataire;
use App\Appartement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ImportParametrageFileJob;
use App\Http\Controllers\SaveModelController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EntiteController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "entites";
    protected $model = Entite::class;
    protected $job = ImportParametrageFileJob::class;

    public function save(Request $request)
    {


        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();
                $arrayId = [];

                $item = new Entite();

                if (isset($request->id)) {
                    if (is_numeric($request->id)) {
                        $item = Entite::find($request->id);

                        if (!$item) {
                            return array(
                                "data" => null,
                                "error" => "L'ilot que vous tentez de modifier n'existe pas ",
                            );
                        }
                    } else {
                        return  array(
                            "data" => null,
                            "error" => "L'id doit être un nombre entier",
                        );
                    }
                }


                if (empty($request->designation)) {
                    $errors = "Veuillez renseigner la designation";
                } elseif (!Outil::isUnique(['designation'], [$request->designation], $request->id, Entite::class)) {
                    $errors = "La designation renseignée existe déja";
                }



                $users                  = json_decode($request->users, true);
                $infobancaires          = json_decode($request->info_bancaires, true);

                if (isset($request->location) && ($request->location == 'on' || $request->location == true)) {
                    $item->location                = 1;
                } else{
                    $item->location                = 0;
                }

                if (isset($request->vente) && ($request->vente == 'on' || $request->vente == true)) {
                    $item->vente                = 1;
                } else{
                    $item->vente                = 0;
                }


                if (!isset($errors)) {
                    $item->designation = $request->designation;
                    $item->description = $request->description;
                    if($request->description =="SERTEM"){
                        $item->code = "SERTEM";
                    }
                    // dd($request->all());
                    // $item->gestionnaire_id = $request->gestionnaire ? $request->gestionnaire : null;

                    $item->nomcompletnotaire = $request->nomcompletnotaire ? $request->nomcompletnotaire : null;
                    $item->emailnotaire = $request->emailnotaire ? $request->emailnotaire : null;
                    $item->telephone1notaire = $request->telephone1notaire ? $request->telephone1notaire : null;
                    $item->nometudenotaire = $request->nometudenotaire ? $request->nometudenotaire : null;
                    $item->emailetudenotaire = $request->emailetudenotaire ? $request->emailetudenotaire : null;
                    $item->telephoneetudenotaire = $request->telephoneetudenotaire ? $request->telephoneetudenotaire : null;
                    $item->assistantetudenotaire = $request->assistantetudenotaire ? $request->assistantetudenotaire : null;
                    $item->adressenotaire = $request->adressenotaire ? $request->adressenotaire : null;
                    $item->adresseetudenotaire = $request->adresseetudenotaire ? $request->adresseetudenotaire : null;

                    if ($request->hasFile("image")) {
                        $filesToUpload = $request->file("image");
                        $uploadedFile = $this->uploadFile2($filesToUpload, public_path('uploads/entites'));

                        if ($uploadedFile) {
                            $item->image = $uploadedFile['pathtocall'];
                        }
                    }

                    $item->save();

                   if (isset($users) && count($users) > 0) {

                       foreach($users as $user) {
                           $arrayId []= $user['user_id'];
                       }
                       if(isset($arrayId) && count($arrayId) > 0){
                           $item->usersentite()->sync($arrayId);
                       }
                   }

                    if (isset($infobancaires) && (count($infobancaires) > 0)) {

                       // dd($infobancaires);
                        $oldInfobcs   = Infobancaire::where('entite_id',$item->id)->get();
                        if(isset($oldInfobcs)) {
                            //dd(count($oldInfobcs->get()));
                           Outil::Checkdetail($oldInfobcs,$infobancaires,Infobancaire::class, ["entite_id","datedebut","datefin"]);
                            //$oldInfobcs->delete();
                            //$oldInfobcs->forceDelete();
                        }

                        foreach($infobancaires as $infobancaire) {
                            $infobc = Infobancaire::where('entite_id',$item->id)
                                ->whereDate('datedebut',$infobancaire['datedebut'])
                                ->whereDate('datefin',$infobancaire['datefin'])
                                ->first();

                            if(!isset($infobc)){
                                $infobc = new Infobancaire();
                            }

                            $infobc->banque            = $infobancaire['banque'];
                            $infobc->agence            = $infobancaire['agence'];
                            $infobc->codebanque        = $infobancaire['codebanque'];
                            $infobc->codeguichet       = $infobancaire['codeguichet'];
                            $infobc->clerib            = $infobancaire['clerib'];
                            $infobc->datedebut         = $infobancaire['datedebut'];
                            $infobc->datefin           = $infobancaire['datefin'];
                            $infobc->numerocompte      = $infobancaire['numerocompte'];
                            $infobc->entite_id         = $item->id;

                            $infobc->save();

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


    function uploadFile2($file, $uploadPath)
    {
        if ($file->isValid()) {

            $originalName = explode(".", $file->getClientOriginalName());

            $fileName = $originalName[0] . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $filePath =  '/' . $fileName;

            $pathtocall = 'uploads/entites' . '/' . $fileName;

            if ($file->move($uploadPath, $fileName)) {
                return [
                    "path" => $filePath,
                    "name" => $fileName,
                    "pathtocall" => $pathtocall
                ]; // Retourne le nom du fichier téléchargé avec succès
            }
        }

        return null; // Aucun fichier n'a été téléchargé
    }


    public function links()
    {
        try {
            return DB::transaction(function () {
                $entite = Entite::where("designation", "SCI REYHAN")->first();
                $appartements = Appartement::all();
                $users = User::all();
                $locataires = Locataire::all();
                if ($entite != null) {

                    foreach ($appartements as $ap) {
                        $ap->entite_id = $entite->id;
                        $ap->save();
                    }

                    foreach ($users as $user) {
                        $user->entite_id = $entite->id;
                        $user->save();
                    }

                    foreach ($locataires as $locataire) {
                        $locataire->entite_id = $entite->id;
                        $locataire->save();
                    }
                }
                return ['data' => "1"];
            });
        } catch (\Exception $e) {
            return Outil::getResponseError($e);
        }
    }
}
