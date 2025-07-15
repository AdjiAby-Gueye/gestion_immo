<?php

namespace App\Http\Controllers;


use App\Contrat;
use App\Demandeintervention;
use App\Demanderesiliation;
use App\Detailfactureintervention;
use App\Facture;
use App\Factureintervention;
use App\Immeuble;
use App\Intervention;
use App\Jobs\ImportEntiteFileJob;
use App\Jobs\ImportUserFileJob;
use App\Locataire;
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
use App\Etatlieu;
use Spatie\Permission\Models\Role;


class FactureinterventionController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "factureinterventions";
    protected $model = Factureintervention::class;
    protected $job = ImportUserFileJob::class;


    function generateCodeFacture()
    {

        $chars = "023456789";
        srand((float)microtime() * 1000000);
        $i = 0;
        $pass = '';

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
        //dd($request);
        //dd($request) ;
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();


                //   dd($request) ;
                $item = new Factureintervention();

                if (isset($request->id)) {
                    if (is_numeric($request->id)) {
                        $item = Factureintervention::find($request->id);

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

                if (empty($request->datefactureintervention)) {
                    $errors = "Veuillez renseigner la date de facture";
                }

                if ($request->file('documentfacture')) {
                    $filenameWithExt = $request->file('documentfacture')->getClientOriginalName();

                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                    $extension = $request->file('documentfacture')->getClientOriginalExtension();

                    $fileNameToStore = $filename . '-' . time() . '.' . $extension;

                    $path = $request->file('documentfacture')->storeAs('uploads/factures', $fileNameToStore);
                    $fichier = isset($_FILES['documentfacture']['name']) ? $_FILES['documentfacture']['name'] : "";
                    if (!empty($fichier)) {
                        $dateHeure = date('Y_m_d_H_i_s');
                        $fichier_tmp = $_FILES['documentfacture']['tmp_name'];
                        $ext = explode('.', $fichier);
                        $rename = config('view.uploads')[$this->queryName] . "/documentfacture_" . $dateHeure . "." . end($ext);
                        move_uploaded_file($fichier_tmp, $rename);
                        $item->documentfacture = $rename;
                        //  $item->document = $rename;
                    }
                }

                if ($request->file('recupaiement')) {
                    $filenameWithExt = $request->file('recupaiement')->getClientOriginalName();

                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                    $extension = $request->file('recupaiement')->getClientOriginalExtension();

                    $fileNameToStore = $filename . '-' . time() . '.' . $extension;

                    $path = $request->file('recupaiement')->storeAs('uploads/factures', $fileNameToStore);
                    $fichier = isset($_FILES['recupaiement']['name']) ? $_FILES['recupaiement']['name'] : "";
                    if (!empty($fichier)) {
                        $dateHeure = date('Y_m_d_H_i_s');
                        $fichier_tmp = $_FILES['recupaiement']['tmp_name'];
                        $ext = explode('.', $fichier);
                        $rename = config('view.uploads')[$this->queryName] . "/recupaiement_" . $dateHeure . "." . end($ext);
                        move_uploaded_file($fichier_tmp, $rename);
                        $item->recupaiement = $rename;
                        //  $item->document = $rename;
                    }
                }

                // dd($request->intervention) ;
                $demandeiintervention = Demandeintervention::find($request->intervention);
                $etatlieu = Etatlieu::find($request->etatlieu);
                if (!isset($demandeiintervention) && !isset($etatlieu)) {
                    $errors =  'intervention  introuvable ';
                }

                if (!isset($errors)) {

                    /// dd($request->intervenantassocie);
                    $item->datefacture            = $request->datefactureintervention;
                    $item->intervenantassocie     = $request->intervenantassocie;
                    $item->demandeintervention_id = ($request->intervention) ? $request->intervention : null;
                    $item->etatlieu_id            = ($request->etatlieu) ? $request->etatlieu : null;

                    if (isset($demandeiintervention)) {
                        $item->appartement_id = ($demandeiintervention->appartement_id) ? $demandeiintervention->appartement_id : null;
                    } elseif (isset($etatlieu)) {
                        $item->appartement_id = ($etatlieu->appartement_id) ? $etatlieu->appartement_id : null;
                    }



                    $item->locataire_id            = $request->locataireintervention;

                    $item->save();
                    $id = $item->id;



                    $facture_intervention = json_decode($request->factureintervention_intervention, true);
                    if (isset($facture_intervention) && count($facture_intervention) > 0) {
                        $oldDetailFacture = Detailfactureintervention::where('factureintervention_id', $item->id);

                        if (isset($oldDetailFacture)) {
                            $oldDetailFacture->delete();
                            $oldDetailFacture->forceDelete();
                        }

                        $id = 1;
                        foreach ($facture_intervention as $facture) {
                            $detailfactureintervention                            = new Detailfactureintervention();

                            $detailfactureintervention->intervention_id           = $facture["interventiondetail_id"];
                            $detailfactureintervention->montant                   = $facture["montant"];
                            $detailfactureintervention->factureintervention_id    = $item->id;
                            $detailfactureintervention->save();

                            //                            $id = $factureintervention->id;
                            //                            $item2 = Intervention::find(intval($facture["interventiondetail_id"]));
                            //                            if ($item2) {
                            //                                $item2->factureintervention_id = $factureintervention->id;
                            //                                $item2->save();
                            //                            }
                        }
                    }
                    $retourcaution = intval($request->contratcaution) - intval($request->retourcaution);
                    // dd($retour);
                    $contrat = Contrat::find($request->contratfacture);
                    if (isset($contrat)) {
                        $demanderesiliation = Demanderesiliation::where('contrat_id', $contrat->id)->first();
                        $demanderesiliation->etat = 3;
                        $demanderesiliation->retourcaution = $retourcaution;
                        $demanderesiliation->save();

                        $locataire = Locataire::find($contrat->locataire_id);
                        if ($locataire->prenom != "") {
                            $mail = $locataire->email;
                        } else {
                            $mail = $locataire->emailpersonneacontacter;
                        }
                        if ($contrat) {
                            $contrat->retourcaution = $retourcaution;
                            $contrat->save();
                        }
                        $text = "Bonjour , suite a l'état des lieux le montant du retour de la caution est de : $retourcaution  ";
                        Outil::envoiEmail($mail, 'Retour de caution', $text);
                    }





                    if (!$errors) {
                        return Outil::redirectgraphql($this->queryName, "id:{$id}", Outil::$queries[$this->queryName]);
                    }
                }

                throw new \Exception($errors);
            });
        } catch (\Exception $e) {
            return Outil::getResponseError($e);
        }
    }

    public function delete($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $errors = "";
                $favoris   = null;

                if (isset($id)) {
                    $factureintervention  = Factureintervention::find($id);
                    $detailFacture        = Detailfactureintervention::where('factureintervention_id', $id);
                    if (isset($factureintervention)) {
                        if (isset($detailFacture)) {
                            $detailFacture->delete();
                            $detailFacture->forceDelete();
                        }

                        $factureintervention->delete();
                        $factureintervention->forceDelete();

                        if (!$errors) {
                            return array(
                                "data" => 1
                            );
                        } else {
                            return array(
                                $errors = 'Une erreur est survenue lors de la suppression de la facture'
                            );
                        }
                    }
                }

                throw new \Exception($errors);
            });
        } catch (\Exception $e) {
            return Outil::getResponseError($e);
        }
    }
}
