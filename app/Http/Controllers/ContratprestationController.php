<?php

namespace App\Http\Controllers;


use App\Contrat;
use App\Contratprestation;
use App\Immeuble;
use App\Jobs\ImportEntiteFileJob;
use App\Jobs\ImportUserFileJob;
use App\Paiementloyer;
use App\Pieceappartement;
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


class ContratprestationController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "contratprestations";
    protected $model = Contratprestation::class;
    protected $job = ImportUserFileJob::class;


    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();


               // dd($request) ;
                $item = new Contratprestation();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Contratprestation::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "Le contrat que vous tentez de modifier n'existe pas ",
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

                if (empty($request->datesignaturecontrat)) {
                    $errors = "Veuillez renseigner la date de signature";
                }
                if (empty($request->datedemarragecontrat)) {
                    $errors = "Veuillez renseigner la date de demarrage";
                }
                if (empty($request->daterenouvellementcontrat)) {
                    $errors = "Veuillez renseigner la date de renouvellement";
                }
                if (empty($request->document)) {
                    $errors = "Veuillez renseigner la document";
                }
                if (empty($request->montant)) {
                    $errors = "Veuillez renseigner le montant";
                }
                if (empty($request->frequenceprestation)) {
                    $errors = "Veuillez renseigner la frequence de prestation";
                }
                if (empty($request->categorieprestation)) {
                    $errors = "Veuillez renseigner la categorie de prestation";
                }if (empty($request->prestataire)) {
                    $errors = "Veuillez renseigner le prestataire";
                }

                if($request->file('document')) {
                    $filenameWithExt = $request->file('document')->getClientOriginalName();

                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                    $extension = $request->file('document')->getClientOriginalExtension();

                    $fileNameToStore = $filename.'-'.time().'.'.$extension;

                    $path = $request->file('document')->storeAs('uploads/contratprestations', $fileNameToStore);
                    $fichier = isset($_FILES['document']['name']) ? $_FILES['document']['name'] : "";
                    if (!empty($fichier)) {
                        $dateHeure = date('Y_m_d_H_i_s');
                        $fichier_tmp = $_FILES['document']['tmp_name'];
                        $ext = explode('.', $fichier);
                        $rename = config('view.uploads')[$this->queryName] . "/documentcontratprestation_" . $dateHeure . "." . end($ext);
                        move_uploaded_file($fichier_tmp, $rename);
                        $item->document = $rename ;
                        //  $item->document = $rename;
                    }

                }

                $item->datesignaturecontrat = $request->datesignaturecontrat;
                $item->datedemarragecontrat = $request->datedemarragecontrat;
                $item->daterenouvellementcontrat = $request->daterenouvellementcontrat;
                $item->datepremiereprestation = $request->datepremiereprestation;
                $item->datepremierefacture = $request->datepremierefacture;
                $item->montant = $request->montant;
                $item->frequencepaiementappartement_id = $request->frequenceprestation;
                $item->categorieprestation_id = $request->categorieprestation;
                $item->prestataire_id = $request->prestataire;

                if (!isset($errors)) {

                    $item->save();

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
