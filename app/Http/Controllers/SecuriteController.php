<?php

namespace App\Http\Controllers;

use App\Immeuble;
use App\Securite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use App\Jobs\ImportImmeubleSecuriteFileJob;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\{Appartement, Events\SendNotifEvent, Notif, NotifPermUser, Outil, Produit};

class SecuriteController extends SaveModelController
{
    //

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "immeubles";
    protected $model = Immeuble::class;
    protected $job = ImportImmeubleSecuriteFileJob::class;

    public function sendNotifImport($userId, $filename)
    {
        $extension = pathinfo($filename->getClientOriginalName(), PATHINFO_EXTENSION);

        //dd($filename);

        $queryName = Outil::getQueryNameOfModel(app($this->model)->getTable());
        $generateLink = substr($queryName, 0, (strlen($queryName) - 1));
        $generateLink = 'immeuble';
        // ENVOIE DE LA NOTIFICATION DE DEBUT
        $notif = new Notif();
        $notif->message = "<strong>L'import du fichier excel est en cours</strong>,<br>Vous serez notifié une fois le traitement terminé";
        $notif->link = "#!/list-{$generateLink}";
        $notif->save();

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
                                $this->sendNotifImport($userId, $filename);
                            }
                        }
                    }
                }

            }

            if (isset($errors)) {
                throw new \Exception($errors);
            }
            $type = Outil::getQueryNameOfModel(app($this->model)->getTable());
            if($type == 'cartes'){

                $path_file = public_path('uploads') . "/" . Outil::getQueryNameOfModel(app($this->model)->getTable()) . "/{$userId}/upload.{$extension}";
                $data = Outil::importCarte($data, $path_file);
            }
            else
            if($type == 'entresortiestocks'){

                $path_file = public_path('uploads') . "/" . Outil::getQueryNameOfModel(app($this->model)->getTable()) . "/{$userId}/upload.{$extension}";
                $data = Outil::importStockProduit($data, $path_file);
                //dd($data);

            }else{
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

}
