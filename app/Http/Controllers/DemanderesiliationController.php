<?php

namespace App\Http\Controllers;


use App\User;
use App\Outil;
use App\Contrat;
use App\Immeuble;
use App\Locataire;
use App\Appartement;
use App\Demanderesiliation;
use Illuminate\Http\Request;
use App\Jobs\ImportUserFileJob;
use App\Jobs\ImportEntiteFileJob;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class DemanderesiliationController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "demanderesiliations";
    protected $model = Demanderesiliation::class;
    protected $job = ImportUserFileJob::class;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();

                $item = new Demanderesiliation();

            //    dd($request->document) ;
                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Demanderesiliation::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "La demande que vous tentez de modifier n'existe pas ",
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


                if (empty($request->datedemande)) {
                    $errors = "Veuillez renseigner la date de la demmande de resiliation";
                }
                if (empty($request->dateeffectivite)) {
                    $errors = "Veuillez renseigner la date d'effectivité";
                }

                $locataire = $this->validateObject($request, Locataire::class, 'locataire');
                if (is_string($locataire)) {
                    $errors = $locataire;
                }
                $appartement = $this->validateObject($request, Appartement::class, 'appartement');
                if (is_string($appartement)) {
                    $errors = $appartement;
                }

                $contrat = Contrat::where([['appartement_id',$request->appartement],['locataire_id',$request->locataire]])->first();
                if (!$contrat) {
                    $errors = "Aucun contrat n'est liee a cet appartement et locataire";
                }
            //    dd($request);

                if (!isset($errors)) {

                    $item->contrat_id = $contrat->id;
                    $item->datedemande = $request->datedemande;
                    $item->dateeffectivite = $request->dateeffectivite;
                    if(empty($request->raisonnonrespectdelai)){
                        $item->delaipreavisrespecte = '1' ;
                        $item->raisonnonrespectdelai = 'neant' ;
                    }else{
                        $item->delaipreavisrespecte = '0' ;
                        $item->raisonnonrespectdelai = $request->raisonnonrespectdelai ;
                    }

                    $item->datedebutcontrat = $contrat->datedebutcontrat;
                    $item->delaipreavi_id = isset($contrat->delaipreavi_id) ? $contrat->delaipreavi_id : null;
                    $item->etat = 0;
                    $item->motif = $request->motif;
                    if (isset($request->document)) {
                        $uploadedFile = Outil::uploadFile($request, 'document', public_path('uploads/demanderesiliations'));
                        $item->document = "uploads/demanderesiliations/".($uploadedFile != null ?  $uploadedFile['name'] : null);
                    }

                    $item->save();

                    $this->updateAppartementAfterDemandeResiliation($appartement);

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


    private function updateAppartementAfterDemandeResiliation(Appartement $appartement) : void {
        try {
            //code...
            // $item3 = Appartement::find($appartement->appartement_id);
            //    $item3->iscontrat = 0 ;
            //    $item3->islocataire = 0 ;
            //    $item3->etatappartement_id = 2 ;
            $appartement->isdemanderesiliation = "1" ;
            $appartement->save() ;

        } catch (\Exception $th) {
            throw $th;
        }
    }

    // if($request->file('document')) {
        //     $filenameWithExt = $request->file('document')->getClientOriginalName();

        //     $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

        //     $extension = $request->file('document')->getClientOriginalExtension();

        //     $fileNameToStore = $filename.'-'.time().'.'.$extension;

        //     $path = $request->file('document')->storeAs('uploads/demanderesiliations', $fileNameToStore);
        //     $fichier = isset($_FILES['document']['name']) ? $_FILES['document']['name'] : "";
        //     if (!empty($fichier)) {
        //         $dateHeure = date('Y_m_d_H_i_s');
        //         $fichier_tmp = $_FILES['document']['tmp_name'];
        //         $ext = explode('.', $fichier);
        //         $rename = config('view.uploads')[$this->queryName] . "/demanderesiliation_" . $dateHeure . "." . end($ext);
        //         move_uploaded_file($fichier_tmp, $rename);
        //         $item->document = $rename ;
        //         //  $item->document = $rename;
        //     }

        // }
}
