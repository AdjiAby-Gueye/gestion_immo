<?php

namespace App\Http\Controllers;


use App\Contrat;
use App\Immeuble;
use App\Jobs\ImportEntiteFileJob;
use App\Jobs\ImportUserFileJob;
use App\Paiementloyer;
use App\Pieceappartement;
use App\Typeappartement;
use App\UserDepartement;
use App\Versementchargecopropriete;
use App\Versementloyer;
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


class VersementchargecoproprieteController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "versementchargecoproprietes";
    protected $model = Versementchargecopropriete::class;
    protected $job = ImportUserFileJob::class;



    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();


                //dd($request) ;
                $item = new Versementchargecopropriete();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Versementchargecopropriete::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "Le versement que vous tentez de modifier n'existe pas ",
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

                if (empty($request->proprietaire)) {
                    $errors = "Veillez renseigner un proprietaire";
                }
                if (empty($request->montant)) {
                    $errors = "Veuillez renseigner le montant";
                }
                if (empty($request->dateversement)) {
                    $errors = "Veuillez renseigner la date de versement";
                }
                if (empty($request->anneecouverte)) {
                    $errors = "Veuillez renseigner l'annee couverte";
                }

                if($request->file('document')) {
                    $filenameWithExt = $request->file('document')->getClientOriginalName();

                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                    $extension = $request->file('document')->getClientOriginalExtension();

                    $fileNameToStore = $filename.'-'.time().'.'.$extension;

                    $path = $request->file('document')->storeAs('uploads/versementchargecoproprietes', $fileNameToStore);
                    $fichier = isset($_FILES['document']['name']) ? $_FILES['document']['name'] : "";
                    if (!empty($fichier)) {
                        $dateHeure = date('Y_m_d_H_i_s');
                        $fichier_tmp = $_FILES['document']['tmp_name'];
                        $ext = explode('.', $fichier);
                        $rename = config('view.uploads')[$this->queryName] . "/documentversementchargecopropriete_" . $dateHeure . "." . end($ext);
                        move_uploaded_file($fichier_tmp, $rename);
                        $item->document = $rename ;
                        //  $item->document = $rename;
                    }

                }



           //     $codeFacture = $this->generateCodeFacture() ;
                $item->proprietaire_id = $request->proprietaire;
                $item->montant = $request->montant;
                $item->dateversement = $request->dateversement;
                $item->anneecouverte = $request->anneecouverte;

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
