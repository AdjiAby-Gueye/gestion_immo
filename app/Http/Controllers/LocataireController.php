<?php

namespace App\Http\Controllers;

use App\User;
use App\Outil;
use App\Entite;
use App\Immeuble;
use App\Copreneur;
use App\Locataire;
use App\Appartement;
use App\Compteclient;
use App\DomaineDetude;
use App\Secteuractivite;
use App\Typeappartement;
use App\UserDepartement;
use App\Pieceappartement;
use App\Demanderesiliation;
use Illuminate\Http\Request;
use App\Jobs\ImportUserFileJob;
use App\Jobs\ImportEntiteFileJob;
use GraphQL\Language\AST\Location;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Jobs\ImportLocataireFileJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use App\Jobs\ImportLocataireMoralFileJob;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class LocataireController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "locataires";
    protected $model = Locataire::class;
    protected $job = ImportLocataireFileJob::class;

    public function save(Request $request)
    {
        // dd($request);

        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();
                $item_entite = null;
                $item_secteuractivite = null;

                $item = new Locataire();
                // $locataire_copreneurs = isset($request->locataire_copreneurs) ? json_decode($request->locataire_copreneurs , true) : [];


                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Locataire::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "Le locataire que vous tentez de modifier n'existe pas ",
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

                // dd($request);
                if ($request->typelocataire == '1') {
                    if (empty($request->prenom)) {
                        $errors = "Veuillez renseigner le prenom du locataire";
                    }
                    if (empty($request->nom)) {
                        $errors = "Veuillez renseigner le nom";
                    }
                    if (empty($request->telephoneportable1)) {
                        $errors = "Veuillez renseigner le telephone";
                    } else if (!preg_match('/^\+\d{1,4} \d{9,12}$/', $request->telephoneportable1)) {
                        $errors = "Numéro de téléphone invalide, respectez ce format (+33 778909876)";
                    }

                    if (empty($request->email)) {
                        $errors = "Veuillez renseigner l'email";
                    }
                    if (empty($request->email)) {
                        $errors = "Veuillez renseigner l'email";
                    } else  if (!Outil::isUnique(['email'], [$request->email], $request->id, Locataire::class)) {
                        $errors = "Cet email existe deja !";
                    }
                    if (!empty($request->telephoneportable2)) {
                        if (!preg_match('/^\+\d{1,4} \d{9,12}$/', $request->telephoneportable2)) {
                            $errors = "Numéro de téléphone 2 invalide, respectez ce format (+33 778909876)";
                        }
                    }
                    if (!empty($request->telephonebureau)) {
                        if (!preg_match('/^\+\d{1,4} \d{9,12}$/', $request->telephonebureau)) {
                            $errors = "Numéro de téléphone bureau invalide, respectez ce format (+33 778909876)";
                        }
                    }

                    // if (empty($request->profession)) {
                    //     $errors = "Veuillez renseigner la profession";
                    // }
                    // if (empty($request->revenus)) {
                    //     $errors = "Veuillez renseigner les revenus";
                    // }
                    // if (empty($request->documentcontrattravail)) {
                    //     $errors = "Veuillez renseigner le contrat de travail ou un justificatif";
                    // }
                    // if (empty($request->expatlocale)) {
                    //     $errors = "Veuillez renseigner si c'est un expatrié ou un locale";
                    // }
                    if (!empty($request->priseencharge)) {
                        if (empty($request->nomcompletpersonnepriseencharge)) {
                            $errors = "Veuillez renseigner le nom complet de la personne qui prend en charge";
                        } else {
                            $item->nomcompletpersonnepriseencharge = $request->nomcompletpersonnepriseencharge;
                        }
                        if (empty($request->telephonepersonnepriseencharge)) {
                            $errors = "Veuillez renseigner le telephone de la personne qui prend en charge";
                        } else {
                            $item->telephonepersonnepriseencharge = $request->telephonepersonnepriseencharge;
                        }
                    }

                    if ($request->file('documentcontrattravail')) {
                        $filenameWithExt = $request->file('documentcontrattravail')->getClientOriginalName();

                        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                        $extension = $request->file('documentcontrattravail')->getClientOriginalExtension();

                        $fileNameToStore = $filename . '-' . time() . '.' . $extension;

                        $path = $request->file('documentcontrattravail')->storeAs('uploads/locataires', $fileNameToStore);
                        $fichier = isset($_FILES['documentcontrattravail']['name']) ? $_FILES['documentcontrattravail']['name'] : "";
                        if (!empty($fichier)) {
                            $dateHeure = date('Y_m_d_H_i_s');
                            $fichier_tmp = $_FILES['documentcontrattravail']['tmp_name'];
                            $ext = explode('.', $fichier);
                            $rename = config('view.uploads')[$this->queryName] . "/contrattravail_" . $dateHeure . "." . end($ext);
                            move_uploaded_file($fichier_tmp, $rename);
                            $item->contrattravail = $rename;
                            //  $item->document = $rename;
                        }
                    }

                    $item->prenom = $request->prenom;
                    $item->nom = $request->nom;
                    $item->telephoneportable1 =  $request->telephoneportable1;
                    if ($request->telephoneportable2) {
                        $item->telephoneportable2 =  $request->telephoneportable2;
                    }
                    if ($request->telephonebureau) {
                        $item->telephonebureau =  $request->telephonebureau;
                    }
                    if ($request->profession) {
                        $item->profession = $request->profession;
                    }

                    $item->email = $request->email;

                    $item->age = $request->age;
                    $item->cni = $request->cni;
                    $item->passeport = $request->passeport;
                    $item->typelocataire_id = $request->typelocataire;
                    $item->etatlocataire = '0';
                    $item->revenus = $request->revenus;
                    $item->expatlocale = $request->expatlocale;

                    if ($request->mandataire) {
                        $item->mandataire = $request->mandataire;
                    }
                    if ($request->lieux_naissance) {
                        $item->lieux_naissance = $request->lieux_naissance;
                    }
                    if ($request->date_naissance) {
                        $item->date_naissance = $request->date_naissance;
                    }
                    if ($request->paysnaissance) {
                        $item->pays_naissance = $request->paysnaissance;
                    }
                    if ($request->adresse) {
                        $item->adresseentreprise = $request->adresse;
                    }
                    if ($request->ville) {
                        $item->ville = $request->ville;
                    }
                    if ($request->njf) {
                        $item->njf = $request->njf;
                    }
                    if ($request->nationalite) {
                        $item->nationalite = $request->nationalite;
                    }
                    if ($request->situationfamiliale) {
                        $item->situationfamiliale = $request->situationfamiliale;
                    }
                    if ($request->codepostal) {
                        $item->codepostal = $request->codepostal;
                    }
                }
                if ($request->typelocataire == '2') {
                    if (empty($request->nomentreprise)) {
                        $errors = "Veuillez renseigner le nom de l'entreprise";
                    }
                    if (!empty($request->email2)) {
                        if (!Outil::isUnique(['email'], [$request->email2], $request->id, Locataire::class)) {
                            $errors = "Cet email existe deja !";
                        }
                    }

                    if (empty($request->adresseentreprise)) {
                        $errors = "Veuillez renseigner l'adresse de l'entreprise";
                    }
                    if (!empty($request->secteuractivite)) {

                        $item_secteuractivite = Secteuractivite::where('id', $request->secteuractivite)->first();
                        if (!$item_secteuractivite) {
                            $errors = "Veuillez definir le secteur ";
                        }
                    }
                    // if (empty($request->ninea)) {
                    //     $errors = "Veuillez renseigner le ninea";
                    // }
                    // if (empty($request->numerorg)) {
                    //     $errors = "Veuillez renseigner le numero RG";
                    // }
                    if (empty($request->personnehabiliteasigner)) {
                        $errors = "Veuillez renseigner la personne habileté a signer";
                    }
                    if (empty($request->prenompersonneacontacter)) {
                        $errors = "Veuillez renseigner le prenom de la personne a contacter";
                    }
                    if (empty($request->nompersonneacontacter)) {
                        $errors = "Veuillez renseigner le nom de la personne a contacter";
                    }

                    if (empty($request->telephone1personneacontacter)) {
                        $errors = "Veuillez renseigner le téléphone de la personne a contacter";
                    } else if (!preg_match('/^\+\d{1,4} \d{9,12}$/', $request->telephone1personneacontacter)) {
                        $errors = "Numéro de téléphone de la personne a contacter est invalide,\n respectez ce format (+33 778909876)";
                    }

                    if ($request->file('documentninea')) {
                        $filenameWithExt = $request->file('documentninea')->getClientOriginalName();

                        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                        $extension = $request->file('documentninea')->getClientOriginalExtension();

                        $fileNameToStore = $filename . '-' . time() . '.' . $extension;

                        $path = $request->file('documentninea')->storeAs('uploads/locataires', $fileNameToStore);
                        $fichier = isset($_FILES['documentninea']['name']) ? $_FILES['documentninea']['name'] : "";
                        if (!empty($fichier)) {
                            $dateHeure = date('Y_m_d_H_i_s');
                            $fichier_tmp = $_FILES['documentninea']['tmp_name'];
                            $ext = explode('.', $fichier);
                            $rename = config('view.uploads')[$this->queryName] . "/ninea_" . $dateHeure . "." . end($ext);
                            move_uploaded_file($fichier_tmp, $rename);
                            $item->documentninea = $rename;
                            //  $item->document = $rename;
                        }
                    }

                    if ($request->file('documentnumerorg')) {
                        $filenameWithExt = $request->file('documentnumerorg')->getClientOriginalName();

                        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                        $extension = $request->file('documentnumerorg')->getClientOriginalExtension();

                        $fileNameToStore = $filename . '-' . time() . '.' . $extension;

                        $path = $request->file('documentnumerorg')->storeAs('uploads/locataires', $fileNameToStore);
                        $fichier = isset($_FILES['documentnumerorg']['name']) ? $_FILES['documentnumerorg']['name'] : "";
                        if (!empty($fichier)) {
                            $dateHeure = date('Y_m_d_H_i_s');
                            $fichier_tmp = $_FILES['documentnumerorg']['tmp_name'];
                            $ext = explode('.', $fichier);
                            $rename = config('view.uploads')[$this->queryName] . "/numerorg_" . $dateHeure . "." . end($ext);
                            move_uploaded_file($fichier_tmp, $rename);
                            $item->documentnumerorg = $rename;
                            //  $item->document = $rename;
                        }
                    }

                    if ($request->file('documentstatut')) {
                        $filenameWithExt = $request->file('documentstatut')->getClientOriginalName();

                        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                        $extension = $request->file('documentstatut')->getClientOriginalExtension();

                        $fileNameToStore = $filename . '-' . time() . '.' . $extension;

                        $path = $request->file('documentstatut')->storeAs('uploads/locataires', $fileNameToStore);
                        $fichier = isset($_FILES['documentstatut']['name']) ? $_FILES['documentstatut']['name'] : "";
                        if (!empty($fichier)) {
                            $dateHeure = date('Y_m_d_H_i_s');
                            $fichier_tmp = $_FILES['documentstatut']['tmp_name'];
                            $ext = explode('.', $fichier);
                            $rename = config('view.uploads')[$this->queryName] . "/staut_" . $dateHeure . "." . end($ext);
                            move_uploaded_file($fichier_tmp, $rename);
                            $item->documentstatut = $rename;
                            //  $item->document = $rename;
                        }
                    }

                    //   dd($request);
                    $item->nomentreprise = $request->nomentreprise;
                    $item->adresseentreprise = $request->adresseentreprise;
                    $item->ninea = $request->ninea;
                    if (isset($request->numerorg)) {
                        $item->numerorg = $request->numerorg;
                    }

                    if (isset($request->email2)) {
                        $item->email = $request->email2;
                    }

                    $item->personnehabiliteasigner = $request->personnehabiliteasigner;
                    $item->fonctionpersonnehabilite = $request->fonctionpersonnehabilite;
                    $item->nompersonneacontacter = $request->nompersonneacontacter;
                    $item->prenompersonneacontacter = $request->prenompersonneacontacter;
                    $item->emailpersonneacontacter = $request->emailpersonneacontacter;
                    $item->telephone1personneacontacter = $request->telephone1personneacontacter;
                    if (isset($request->telephone2personneacontacter)) {
                        $item->telephone2personneacontacter =   $request->telephone2personneacontacter;
                    }
                    $item->typelocataire_id = $request->typelocataire;
                    if (isset($item_secteuractivite)) {
                        $item->secteuractivite_id    = $item_secteuractivite->id;
                    }

                    $item->etatlocataire = '0';
                }

                if (empty($request->entite)) {
                    $errors = "Veuillez definir l'entité";
                } else if (isset($request->entite)) {
                    $item_entite = Entite::where('id', $request->entite)->first();
                }

                if (!empty($request->numeroclient)) {
                    if (!Outil::isUnique(['numeroclient'], [$request->numeroclient], $request->id, Locataire::class)) {
                        $errors = "Ce numero de client existe déja !";
                    }
                }

            //    $errors =  $this->validateDataCopreneur($request);

                if (!isset($errors)) {
                    $item->entite_id    = $item_entite->id;
                    $item->numeroclient = $request->numeroclient ? $request->numeroclient : null;
                    $item->save();
                    $this->saveCopreneur($request , $item);
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

    public function mailToLocataire()
    {
        Outil::newrappelPaiement();
    }


    /**
     * Enregistre les copreneurs associé à un locataire.
     *
     * @param Request $request
     * @param Locataire $locataire
     */
    function saveCopreneur(Request $request, Locataire $locataire)
    {
        $entite = isset($request->entite) ? Entite::find($request->entite) : null;
        if (isset($entite) && isset($entite->id)) {
            if ($entite->code == "RID") {
                if ($request->est_copreuneur && $request->est_copreuneur == true) {
                    $copreneurs = isset($request->locataire_copreneurs) ? json_decode($request->locataire_copreneurs , true) : [];
                    if (count($copreneurs) > 0 ) {

                        if ($request->id) {

                            Outil::Checkdetail($locataire->copreneurs , $copreneurs , Copreneur::class ,'id');
                        }

                        foreach($copreneurs as $c) {
                            // dd($c);
                            $cop = isset($c['id']) ? Copreneur::find($c['id']) : null;
                            if ($cop == null) {
                                 $locataire->copreneurs()->create([
                                "nom" => $c['nom'],
                                "prenom" => $c['prenom'],
                                "email" => $c['email'],
                                "adresse" => $c['adresse'],
                                "njf" => $c['njf'],
                                "codepostal" => $c['codepostal'],
                                "ville" => $c['ville'],
                                "pays" => $c['paysnaissance_id'],
                                "nationalite" => $c['nationalite'],
                                "datenaissance" => $c['datenaissance'] ? date("Y-m-d", strtotime(date($c['datenaissance']))) : null,
                                "lieunaissance" => $c['lieunaissance'],
                                "profession" => $c['profession'],
                                "telephone1" => $c['telephone1'],
                                "telephone2" => $c['telephone2'],
                                "cni" => $c['cni'],
                                "passport" => $c['passeport'],
                                "situationfamiliale" => $c['situationfamiliale'],
                            ]);
                            }


                        }
                        $locataire->est_copreuneur = 1;
                    }
                }else {
                    $locataire->est_copreuneur = 0;
                }

                $locataire->save();
            }
        }


    }

    function handleSaveCopreneur(Request $request , Locataire $locataire) {
        if (isset($request->est_copreuneur)) {
            if (empty($this->validateDataCopreneur($request)) ||  $this->validateDataCopreneur($request) == "") {
                    $this->saveCopreneur($request , $locataire);
            }
        }
    }
    function validateDataCopreneur(Request $request)
    {

        $errors = "";
        if ($request->est_copreuneur && $request->est_copreuneur == true) {
            if (!$request->has('copreneurnom') || empty($request->copreneurnom)) {
                $errors = 'Le champ nom copreneur est obligatoire.';
            } elseif (strlen($request->copreneurnom) > 255) {
                $errors = 'Le champ nom copreneur ne doit pas dépasser 255 caractères.';
            }
            if (!$request->has('copreneurprenom') || empty($request->copreneurprenom)) {
                $errors = 'Le champ prénom copreneur est obligatoire.';
            } elseif (strlen($request->copreneurprenom) > 255) {
                $errors = 'Le champ prénom copreneur ne doit pas dépasser 255 caractères.';
            }
            if (!$request->has('copreneurtelephone1') || empty($request->copreneurtelephone1)) {
                $errors = 'Le champ téléphone copreneur est obligatoire.';
            }
            if (!preg_match('/^\+\d{1,4} \d{9,12}$/', $request->copreneurtelephone1)) {
                $errors = "Numéro de téléphone invalide, respectez ce format (+33 778909876)";
            }

            if (!$request->has('copreneuremail') || empty($request->copreneuremail)) {
                $errors = 'Le champ email copreneur est obligatoire.';
            } elseif (strlen($request->copreneurnom) > 255) {
                $errors = 'Le champ email copreneur ne doit pas dépasser 255 caractères.';
            } else if (!Outil::isUnique(['email'], [$request->copreneuremail], $request->id, Copreneur::class)) {
                $errors = "Cet email copreneur existe deja !";
            }
        }

        return $errors;
    }
    public function getlocataireimpayebyperiode()
    {
        //return Outil::getLocataireImppayeavisecheanceByPeriode();
        return Outil::sendeRelancePaiement();
    }

    public function reinitcompteclient(Request $request)
    {
        $errors = null;
        $data = null;

        $locataire = Locataire::find($request->locataire_id);

        if (!$locataire) {
            $errors = 'Locataire introuvable';
            return response()->json(["data" => ["data" => $data], "errors" => $errors]);
        }

        $isDeleted = Compteclient::where('locataire_id', $locataire->id)
        ->whereNull('paiementecheance_id')
        ->delete();

        if ($isDeleted > 0) {
            $data = "Compte client réinitialisé avec succès.";
        } else {
            $errors = 'Aucun montant à réinitialiser.';
        }

        return response()->json(["data" => ["data" => $data], "errors" => $errors]);

    }

    public function desactiveCompteClient (Request $request){
        $errors = null;
        $data = null;

        $locataire = Locataire::find($request->locataire_id);

        if (!$locataire) {
            $errors = 'Locataire introuvable';
            return response()->json(["data" => ["data" => $data], "errors" => $errors]);
        }

        $isDesactived = Compteclient::where('locataire_id', $locataire->id)
        ->update(['etat' => -1]);

        if ($isDesactived > 0) {
            $data = "Compte client desactiver avec succès.";
        } else {
            $errors = 'Echec de la desactivation du compte.';
        }

        return response()->json(["data" => ["data" => $data], "errors" => $errors]);

    }

    public function activeCompteClient (Request $request){
        $errors = null;
        $data = null;

        $locataire = Locataire::find($request->locataire_id);

        if (!$locataire) {
            $errors = 'Locataire introuvable';
            return response()->json(["data" => ["data" => $data], "errors" => $errors]);
        }

        $isActived = Compteclient::where('locataire_id', $locataire->id)
        ->update(['etat' => null]);

        if ($isActived > 0) {
            $data = "Compte client activer avec succès.";
        } else {
            $errors = 'Echec de l\'activation du compte.';
        }

        return response()->json(["data" => ["data" => $data], "errors" => $errors]);

    }

}
