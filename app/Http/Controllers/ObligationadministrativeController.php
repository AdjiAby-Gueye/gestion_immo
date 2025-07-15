<?php

namespace App\Http\Controllers;


use App\Assurance;
use App\Caution;
use App\Contrat;
use App\Facture;
use App\Immeuble;
use App\Jobs\ImportEntiteFileJob;
use App\Jobs\ImportUserFileJob;
use App\Obligationadministrative;
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


class ObligationadministrativeController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "obligationadministratives";
    protected $model = Obligationadministrative::class;
    protected $job = ImportUserFileJob::class;


    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();


              // dd($request) ;
                $item = new Obligationadministrative();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Obligationadministrative::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "L'obligation que vous tentez de modifier n'existe pas ",
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
                    $errors = "la designation n'est pas renseigné";
                }
                if (empty($request->debut)) {
                    $errors = "Veuillez renseigner la date de debut de l'assurance";
                }
                if (empty($request->montant)) {
                    $errors = "Veuillez renseigner le montant payé";
                }
                if (empty($request->fin)) {
                    $errors = "Veuillez renseigner la date de fin de l'assurance";
                }
                if (empty($request->document)) {
                    $errors = "Veuillez ajouter le document";
                }
                if (empty($request->typeobligationadministrative)) {
                    $errors = "Veuillez renseigner le type d'obligation";
                }
                if (empty($request->obligation_immeuble_appartement)) {
                    $errors = "Veuillez renseigner si ca concerne l'immeuble entier ou un appartement";
                }


                if($request->file('document')) {
                    $filenameWithExt = $request->file('document')->getClientOriginalName();

                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                    $extension = $request->file('document')->getClientOriginalExtension();

                    $fileNameToStore = $filename.'-'.time().'.'.$extension;

                    $path = $request->file('document')->storeAs('uploads/obligationadministratives', $fileNameToStore);
                    $fichier = isset($_FILES['document']['name']) ? $_FILES['document']['name'] : "";
                    if (!empty($fichier)) {
                        $dateHeure = date('Y_m_d_H_i_s');
                        $fichier_tmp = $_FILES['document']['tmp_name'];
                        $ext = explode('.', $fichier);
                        $rename = config('view.uploads')[$this->queryName] . "/documentobligationadministrative_" . $dateHeure . "." . end($ext);
                        move_uploaded_file($fichier_tmp, $rename);
                        $item->document = $rename ;
                        //  $item->document = $rename;
                    }

                }

                $item->designation = $request->designation;
                $item->debut = $request->debut;
                $item->fin = $request->fin;
                $item->montant = $request->montant;
                $item->typeobligationadministrative_id = $request->typeobligationadministrative;
                if($request->obligation_immeuble_appartement == "immeuble") {

                    $item->immeuble_id = $request->immeuble;
                }
                if($request->obligation_immeuble_appartement == "appartement") {

                    $item->immeuble_id = $request->immeuble;
                    $item->appartement_id = $request->appartement;
                }

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
