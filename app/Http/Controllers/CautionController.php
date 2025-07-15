<?php

namespace App\Http\Controllers;


use App\Caution;
use App\Contrat;
use App\Facture;
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


class CautionController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "cautions";
    protected $model = Caution::class;
    protected $job = ImportUserFileJob::class;


    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();


              // dd($request) ;
                $item = new Caution();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Caution::find($request->id);

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

                if (empty($request->contrat)) {
                    $errors = "le contrat n'est pas renseigné";
                }
                if (empty($request->montantloyer)) {
                    $errors = "le loyer n'est pas renseigné";
                }
                if (empty($request->montantcaution)) {
                    $errors = "Veuillez renseigner le montant de la caution";
                }
                if (empty($request->dateversement)) {
                    $errors = "Veuillez renseigner la date de versement";
                }


                if($request->file('document')) {
                    $filenameWithExt = $request->file('document')->getClientOriginalName();

                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                    $extension = $request->file('document')->getClientOriginalExtension();

                    $fileNameToStore = $filename.'-'.time().'.'.$extension;

                    $path = $request->file('document')->storeAs('uploads/cautions', $fileNameToStore);
                    $fichier = isset($_FILES['document']['name']) ? $_FILES['document']['name'] : "";
                    if (!empty($fichier)) {
                        $dateHeure = date('Y_m_d_H_i_s');
                        $fichier_tmp = $_FILES['document']['tmp_name'];
                        $ext = explode('.', $fichier);
                        $rename = config('view.uploads')[$this->queryName] . "/documentcaution_" . $dateHeure . "." . end($ext);
                        move_uploaded_file($fichier_tmp, $rename);
                        $item->document = $rename ;
                        //  $item->document = $rename;
                    }

                }

                $item->montantloyer = $request->montantloyer;
                $item->montantcaution = $request->montantcaution;
                $item->dateversement = $request->dateversement;
                $item->codeappartement = $request->appartement;
                $item->contrat_id = $request->contrat;
                $item->etat = 'payé';


                if (!isset($errors)) {

                    $item->save();

                    $item2 = Contrat::find($request->contrat);
                    $item2->caution_id = $item->id ;
                    $item2->dateretourcaution = date('Y-m-d'); ;
                    $item2->save() ;

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
