<?php

namespace App\Http\Controllers;

use App\Avenant;
use App\Contrat;
use Carbon\Carbon;
use App\Avisecheance;
use App\Helpers\MyHelper;
use App\Paiementecheance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use App\{Appartement,
    Devi,
    Events\SendNotifEvent,
    Fraisupplementaire,
    Notif,
    NotifPermUser,
    Outil,
    Periodicite,
    Produit,
    Apportponctuel,
    Etatappartement,
    Typeapportponctuel,
};
use Exception;

class SaveModelController extends Controller
{
    protected $queryName;
    protected $model;
    protected $job;

    public function save(Request $request)
    {
        $this->queryName = app($this->model)->getTable;

        $this->model = app($this->model);

            try{
                return DB::transaction(function () use ($request) {

                    $item = new $this->model;
                    $id = $request->input('id');
                    if(isset($id)){
                        $item = $this->model::find($id);
                        if (!$item) {
                            return response()->json(['error' => 'Enregistrement introuvable'], 404);
                        }
                    }


                    $item->fill($request->all());
                    $item->save();

                    return Outil::redirectIfModeliSSaved($item, $this->queryName);
            });

            }catch(Exception $e){
                return Outil::getResponseError($e);
            }

    }
    public function statut(Request $request)
    {
        $errors = null;
        $data = 0;

        try {

            $item = app($this->model)::find($request->id);

            if ($item != null) {
                if ($this->model !== "App\Appartement"){
                    $item->status = $request->status;
                }
                if ($this->model == "App\Demanderesiliation") {
                    $item->etat = $request->status;
                }
                if ($this->model == "App\Contrat" && $request->status == 0) {
                    $item->etat = 0;
                    $appartement = Appartement::find($item->appartement_id);
                    $appartement->iscontrat = 0;
                    $appartement->islocataire = 0;
                    $appartement->etatappartement_id = 2;
                    $appartement->save();
                }
                if ($this->model == "App\Devi") {
                    $item = Devi::where("id", $item->id)->first();
                    $item->est_activer = $request->status;
                }
                if ($this->model == "App\Avenant") {

                    $item = Avenant::find($item->id);
                    $item->est_activer = $request->status;
                }
                if ($this->model == "App\Appartement") {
                    $etatAppart = Etatappartement::where('designation', Outil::getOperateurLikeDB(), '%' . 'Archive' . '%')->first();

                    if($etatAppart){
                        $item->etatappartement_id = $etatAppart->id;
                    }
                }
                $item->save();

            } else {
                $errors = "Cette donnée n'existe pas";
            }


            if (!isset($errors) && $item->save()) {
                if ($this->model == "App\Devi" && $request->status == 0) {
                    $result = Outil::chargeAllInterventions($item->id);
                    ($result == 1) ? $data = 1 : $errors = $result;
                } else {
                    $data = 1;
                }
            }
        } catch (\Exception $e) {
            return Outil::getResponseError($e);
        }

        return response('{"data":' . $data . ', "errors": "' . $errors . '" }')
            ->header('Content-Type', 'application/json');
    }
    public function delete($id)
    {
        if ($this->model == "App\Paiementecheance") {
            $paiement = Paiementecheance::find($id);
            $avis = Avisecheance::find($paiement->avisecheance_id);

            // $paiements = Paiementecheance::where('avisecheance_id',$paiement->avisecheance_id)
            // ->whereNull('etat')
            // ->get();

            $paiement->etat = -1;
            $paiement->save();

            $montantFacture = (int) str_replace(' ', '', $avis->montant_total);
            $montantenattente = Outil::GetMontantenattente($avis->id,$montantFacture);
            // dd($montantenattente);
            if($montantenattente < $montantFacture){
                    $avis->est_activer = 4; // paiement partiel
            }else{
                $avis->est_activer = 1;
            }

            // // Vérification du nombre de paiements liés à cet avis
            // if(count($paiements) > 1){
            //     $avis->est_activer = 4; // paiement partiel
            // }else{
            //     $avis->est_activer = 1;
            // }

            $avis->signature = null;
            $avis->save();
        }
        if ($this->model == "App\Avisecheance") {
            $frais = Fraisupplementaire::where('avisecheance_id',$id);
            $frais->delete();
            $frais->forceDelete();
        }
        return Outil::supprimerElement($this->model, $id);
    }


    public function sendNotifImport($userId, $filename)
    {
        $extension = pathinfo($filename->getClientOriginalName(), PATHINFO_EXTENSION);

        //dd($filename);

        $queryName = Outil::getQueryNameOfModel(app($this->model)->getTable());
        $generateLink = substr($queryName, 0, (strlen($queryName) - 1));
        // ENVOIE DE LA NOTIFICATION DE DEBUT
        $notif = new Notif();
        $notif->message = "<strong>L'import du fichier excel est en cours</strong>,<br>Vous serez notifié une fois le traitement terminé";
        $notif->link = "#!/list-{$generateLink}";
        $notif->save();
        // $sc = true;
        // if($generateLink == 'securite'){
        //     $generateLink = 'immeuble';
        //     $sc = false;
        // }
        // dd($generateLink);
        $notifPermUser = new NotifPermUser();
        $notifPermUser->notif_id = $notif->id;
        // dd($generateLink);
        $notifPermUser->permission_id = Permission::where('name', "creation-{$generateLink}")->first()->id;
        $notifPermUser->user_id = $userId;
        $notifPermUser->save();

        //$eventNotif = new SendNotifEvent($notifPermUser);
        //event($eventNotif);

        $from  = public_path('uploads') . "/{$queryName}/{$userId}/";
        $to    = "upload.{$extension}";
        $file  = $filename->move($from, $to);
        // if(!$sc){
        //     $generateLink = 'securite';
        // }
        $this->dispatch((new $this->job($this->model, $generateLink, $file, $userId, $from . $to)));

        //  dd($file) ;
    }


    public function import(Request $request)
    {
        try {

            $errors = null;
            $data = 0;
            if (!isset($this->job)) {
                $errors = "L'import sur ce type de donnée n'a pas été configuré dans le système";
            } else {
                if (empty($request->file('file'))) {
                    $errors = 'Un fichier Excel est requis';
                }
                if ($request->hasFile('file')) {
                    $filename = request()->file('file');
                    $extension = pathinfo($filename->getClientOriginalName(), PATHINFO_EXTENSION);
                    if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
                        $data = Excel::toArray(null, $filename);
                        $data = $data[0]; // 0 => à la feuille 1
                        // dd($data) ;

                        if (count($data) < 2) {
                            $errors = "Le fichier ne doit pas être vide";
                        } else {
                            $userId = Auth::user()->id;
                            //   dd($this->model);
                            if (file_exists(public_path('uploads') . "/" . Outil::getQueryNameOfModel(app($this->model)->getTable()) . "/{$userId}/upload.{$extension}")) {
                                $errors = "Un fichier est déjà en cours d'upload, merci de patienter, la fin de celui-ci";
                            } else {
                                if ($this->model == "App\Avisecheance") {
                                    // dd($data);
                                   MyHelper::saveAvisAndPaiementScript($data);
                                }else {
                                    $this->sendNotifImport($userId, $filename);
                                }

                            }
                        }
                    }
                }
            }
            // dd($errors);
            if (isset($errors)) {
                throw new \Exception($errors);
            }
            $type = Outil::getQueryNameOfModel(app($this->model)->getTable());
            if ($type == 'cartes') {

                $path_file = public_path('uploads') . "/" . Outil::getQueryNameOfModel(app($this->model)->getTable()) . "/{$userId}/upload.{$extension}";
                $data = Outil::importCarte($data, $path_file);
            } else
            if ($type == 'entresortiestocks') {

                $path_file = public_path('uploads') . "/" . Outil::getQueryNameOfModel(app($this->model)->getTable()) . "/{$userId}/upload.{$extension}";
                $data = Outil::importStockProduit($data, $path_file);
                //dd($data);

            } else {
                $data = 1;
            }
            return response()->json(
                array(
                    "data" => $data,
                    //"message" => "Le fichier est en cours de traitement..."
                    "message" => "Importation reussie."
                )
            );
            //});
        } catch (\Exception $e) {
            return Outil::getResponseError($e);
        }
    }

    function validateObject($request, $class, $columnName)
    {
        $columnName = strtolower($columnName);

        if (empty($request->$columnName)) {
            return "Veuillez renseigner $columnName";
        } else {
            $item = $class::find($request->$columnName);
            if (!$item) {
                return "$columnName spécifié n'existe pas";
            }
            return $item;
        }

        return null; // Si tout est valide
    }


}
