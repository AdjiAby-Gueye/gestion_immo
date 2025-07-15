<?php

namespace App\Http\Controllers;


use PDF;
use App\User;
use App\Inbox;
use App\Outil;
use App\Caution;
use App\Contrat;
use App\Facture;
use App\Immeuble;
use App\Locataire;
use App\Appartement;
use App\Attachement;
use App\Avisecheance;
use App\DomaineDetude;
use App\Paiementloyer;
use App\Facturelocation;
use App\Typeappartement;
use App\UserDepartement;
use App\Pieceappartement;
use App\Historiquerelance;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\ImportUserFileJob;
use App\Jobs\ImportEntiteFileJob;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class InboxController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "inboxs";
    protected $model = Inbox::class;
    protected $job = ImportUserFileJob::class;


    public function save(Request $request)
    {

        // dd($request);
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();
                $item = new Inbox();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Inbox::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "La caution que vous tentez de modifier n'existe pas ",
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

                if (empty($request->subject)) {
                    $errors =  "Renseigner le sujet";
                }
                if (empty($request->body)) {
                    $errors =  "Renseigner le contenu";
                }
                // if (!$request->hasFile("files")) {
                //     $errors  = "Selectionnez au moins un fichier ";
                // }

                $locataire = $this->validateObject($request, Locataire::class, 'locataire');
                if (is_string($locataire)) {
                    $errors = $locataire;
                }
                $contrat = $this->validateObject($request, Contrat::class, 'contrat');
                if (is_string($locataire)) {
                    $errors = $contrat;
                }

                $appartement = $this->validateObject($request, Appartement::class, 'appartement');
                if (is_string($appartement)) {
                    $errors = $appartement;
                }

                if (!isset($errors)) {

                    $item->body = $request->body;
                    $item->subject = $request->subject;
                    $item->sender_email = $locataire->email;
                    $item->locataire_id = $locataire->id;
                    $item->appartement_id = $appartement->id;
                    $item->user_id = Auth::user()->id;
                    $attachs = [];
                    $item->save();


                    if ($request->hasFile("files")) {
                        $filesToUpload = $request->file("files");

                        foreach ($filesToUpload as $file) {
                            $uploadedFile = $this->uploadFile2($file, public_path('uploads/inboxs'));

                            if ($uploadedFile) {
                                $uploadPath = public_path('uploads/inboxs/' . $uploadedFile['name']);
                                $attachs[] = $uploadPath;
                                $attach = new Attachement([
                                    'filepath' => "uploads/inboxs/" . $uploadedFile['name'],
                                    'filename' =>  $uploadedFile['name'],
                                ]);
                                $item->attachements()->save($attach);
                            }
                        }

                    } else {

                        $uploadedFile = $this->uploadFile($request, 'fileappelloyer', public_path('uploads/inboxs'));
                        // dd($uploadedFile);
                        if ($uploadedFile)
                        {
                            $uploadPath = public_path('uploads/inboxs/' . $uploadedFile['name']);
                            $attachs[] = $uploadPath;
                            $attach = new Attachement([
                                'filepath' => "uploads/inboxs/".$uploadedFile['name'],
                                'filename' =>  $uploadedFile['name'],
                            ]);
                            $item->attachements()->save($attach);
                        }
                        $uploadedFile2 = $this->uploadFile($request, 'filefacture', public_path('uploads/inboxs'));
                        if ($uploadedFile2)
                        {
                            $uploadPath2 = public_path('uploads/inboxs/' . $uploadedFile2['name']);
                            $attachs[] = $uploadPath2;
                            $attach2 = new Attachement([
                                'filepath' =>  "uploads/inboxs/".$uploadedFile2['name'],
                                'filename' =>  $uploadedFile2['name'],
                            ]);
                            $item->attachements()->save($attach2);
                        }

                        if (isset($request->mode_relance)) {

                            $dernierEcheance = $this->getDernierFactureEcheance($contrat->id);
                            // dd($dernierEcheance);
                            if ($dernierEcheance != null) {
                                self::saveHistoriqueRelance($contrat->id, $locataire->id ,$item->id, $dernierEcheance,null);
                            }

                            $dernierLoyer = $this->getDernierFactureLoyer($contrat->id);
                            if ($dernierLoyer != null) {
                                self::saveHistoriqueRelance($contrat->id, $locataire->id ,$item->id, null , $dernierLoyer);
                            }



                        }


                    }




                    // dd($attachs);
                    // Outil::inboxMail($locataire->email, $request->subject, $request->body, $attachs);

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



    public static function saveHistoriqueRelance($contrat , $locataire , $inbox , $avis ,$loyer) {

        $historique = Historiquerelance::create(
            [
                "contrat_id" => $contrat,
                "locataire_id" => $locataire,
                "inbox_id" => $inbox,
                "user_id" => Auth::user()->id,
                "date_envoie" => date("Y-m-d H:i:s"),
                "avisecheance_id" => $avis,
                "facturelocation_id" => $loyer,
            ]
        );

        return $historique;

    }

    function getDernierFactureEcheance($contratId) {
        $derniereFacture = Avisecheance::where("contrat_id", $contratId)
            ->where("est_activer", 1)
            ->orderBy('created_at', 'desc')
            ->first();
        return ($derniereFacture != null) ? $derniereFacture->id : null;
    }

    function getDernierFactureLoyer($contratId) {
        $derniereFacture = Facturelocation::where("contrat_id", $contratId)
        ->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('paiementloyers')
                ->whereRaw('facturelocations.id = paiementloyers.facturelocation_id');
        })
        ->orderBy('created_at', 'desc')
        ->first();
        return ($derniereFacture != null) ? $derniereFacture->id : null;
    }


    function uploadFile($request, $inputName, $uploadPath)
{
    if ($request->hasFile($inputName)) {
        $documentFile = $request->file($inputName);
        $originalName = explode(".", $documentFile->getClientOriginalName());
        $fileName = $originalName[0] . '_' . uniqid() . '.' . $documentFile->getClientOriginalExtension();
        $filePath = $uploadPath . '/' . $fileName;

        if (file_put_contents($filePath, file_get_contents($documentFile))) {
            return [
                "path" => $filePath,
                "name" => $fileName,
            ]; // Retourne le nom du fichier téléchargé avec succès
        }
    }

    return null; // Aucun fichier n'a été téléchargé
}

function uploadFile2($file, $uploadPath)
{
    if ($file->isValid()) {

        $originalName = explode(".", $file->getClientOriginalName());

        $fileName = $originalName[0] . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $filePath = $uploadPath . '/' . $fileName;

        if ($file->move($uploadPath, $fileName)) {
            return [
                "path" => $filePath,
                "name" => $fileName,
            ]; // Retourne le nom du fichier téléchargé avec succès
        }
    }

    return null; // Aucun fichier n'a été téléchargé
}

public function sendEcheanceEncours(Request $request) {
    try {
        $avis = Avisecheance::where('est_activer' , 1)->get();
        if ($avis) {
            $ccopiesEmail = ["abou050793@gmail.com" , "mansourpouye36@gmail.com"];

            foreach ($avis as $avi) {
                $email = $avi->contrat->locataire->email;
                $montant = $avi->montant_total_letter;
                $message = " Nous avons l'honneur de vous informer que vous êtes redevable du montant de ";
                $message .= "".$montant." (". $avi->montant_total .") Francs CFA. \n";
                $message .="Nous vous remercions de bien vouloir régler cette somme dès réception du présent avis. \n";
                $message .="et au plus tard le ".$avi->date_echeance_fr." \n";
                //  sur notre compte suivant le RIB ci-dessous
                // dd($montant);

                Outil::envoiEmail($email, "Notification avis d'echeance", $message,'maileur' , null ,$ccopiesEmail );
            }

            return response()->json(["data" => 1, "errors" => null]);
        }

        return response()->json(["data" => null, "errors" => "Aucun avis encours"]);

    }  catch (\Exception $e) {
        return response()->json(["data" => null, "errors" => "Une erreur est survenue, veuillez contacter l'administrateur"]);
    }

}

// foreach($annexes as $annex)  {
//     $uploadPath = public_path($annex['filepath']);
//     $attachs[] = $uploadPath;
// }

// Outil::envoiEmail($director->email, "Notification signature contrat", $message,'signaturecontratridwan' , $contrat->id , null , $attachs);

}
