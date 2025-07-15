<?php

namespace App\Http\Controllers;


use App\Contrat;
use App\Facture;
use App\Immeuble;
use App\Intervention;
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


class FactureController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "factures";
    protected $model = Facture::class;
    protected $job = ImportUserFileJob::class;

    function generateCodeFacture() {

        $chars = "023456789";
        srand((double)microtime()*1000000);
        $i = 0;
        $pass = '' ;

        while ($i <= 10) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }

        return $pass;

    }

    public function save(Request $request)
    {
       // dd($request) ;
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();


             //   dd($request) ;
                $item = new Facture();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Facture::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "Le paiement que vous tentez de modifier n'existe pas ",
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

                if (empty($request->datefacture)) {
                    $errors = "Veuillez renseigner la date de facture";
                }
                if (empty($request->mois)) {
                    $errors = "Veuillez renseigner le mois";
                }
                if (empty($request->montant)) {
                    $errors = "Veuillez renseigner le montant";
                }
                if (empty($request->typefacture)) {
                    $errors = "Veuillez renseigner le type de facture";
                }
                if (empty($request->documentfacture)) {
                    $errors = "Veuillez renseigner le document de la facture";
                }
                if (empty($request->recupaiement)) {
                    $errors = "Veuillez renseigner le recu de paiement";
                }


                if($request->file('documentfacture')) {
                    $filenameWithExt = $request->file('documentfacture')->getClientOriginalName();

                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                    $extension = $request->file('documentfacture')->getClientOriginalExtension();

                    $fileNameToStore = $filename.'-'.time().'.'.$extension;

                    $path = $request->file('documentfacture')->storeAs('uploads/factures', $fileNameToStore);
                    $fichier = isset($_FILES['documentfacture']['name']) ? $_FILES['documentfacture']['name'] : "";
                    if (!empty($fichier)) {
                        $dateHeure = date('Y_m_d_H_i_s');
                        $fichier_tmp = $_FILES['documentfacture']['tmp_name'];
                        $ext = explode('.', $fichier);
                        $rename = config('view.uploads')[$this->queryName] . "/documentfacture_" . $dateHeure . "." . end($ext);
                        move_uploaded_file($fichier_tmp, $rename);
                        $item->documentfacture = $rename ;
                        //  $item->document = $rename;
                    }

                }

                if($request->file('recupaiement')) {
                    $filenameWithExt = $request->file('recupaiement')->getClientOriginalName();

                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                    $extension = $request->file('recupaiement')->getClientOriginalExtension();

                    $fileNameToStore = $filename.'-'.time().'.'.$extension;

                    $path = $request->file('recupaiement')->storeAs('uploads/factures', $fileNameToStore);
                    $fichier = isset($_FILES['recupaiement']['name']) ? $_FILES['recupaiement']['name'] : "";
                    if (!empty($fichier)) {
                        $dateHeure = date('Y_m_d_H_i_s');
                        $fichier_tmp = $_FILES['recupaiement']['tmp_name'];
                        $ext = explode('.', $fichier);
                        $rename = config('view.uploads')[$this->queryName] . "/recupaiement_" . $dateHeure . "." . end($ext);
                        move_uploaded_file($fichier_tmp, $rename);
                        $item->recupaiement = $rename ;
                        //  $item->document = $rename;
                    }

                }

                if($request->typefacture == '1') {

                    if (empty($request->intervention)) {
                       // $errors = "Veuillez renseigner l'intervention";
                    }

                }
                if($request->typefacture !== '1') {

                    if (empty($request->appartement)) {
                        $errors = "Veuillez renseigner l'appartement";
                    }

                }

                $item->datefacture = $request->datefacture;
                $item->moisfacture = $request->mois;
                $item->montant = $request->montant;
                $item->intervenantassocie = $request->intervenantassocie;
                $item->typefacture_id = $request->typefacture;
                $item->appartement_id = $request->appartement;
                $item->proprietaire_id = $request->proprietaire;
                $item->locataire_id = $request->locataire;
                $item->immeuble_id = $request->immeubl;

                if (!isset($errors)) {

                    $item->save();

                    if(isset($request->intervention)){
                        $item2 = Intervention::find($request->intervention);

                        if($item2){
                            $item2->facture_id = $item->id;
                            $item2->save() ;
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
}
