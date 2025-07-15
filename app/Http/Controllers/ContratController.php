<?php

namespace App\Http\Controllers;




use App\User;
use App\Outil;
use App\Annexe;
use App\Entite;
use App\Caution;
use App\Contrat;
use App\Immeuble;
use App\Copreneur;
use App\Locataire;
use App\Appartement;
use App\Periodicite;
use App\DomaineDetude;
use GuzzleHttp\Client;
use App\Typeappartement;
use App\UserDepartement;
use App\Pieceappartement;
use Illuminate\Http\Request;
// use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use App\Jobs\ImportUserFileJob;

use App\Jobs\ImportEntiteFileJob;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Jobs\ImportContratLocationFileJob;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;


class ContratController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "contrats";
    protected $model = Contrat::class;
    protected $job = ImportContratLocationFileJob::class;

    function generatepwd()
    {

        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((float)microtime() * 1000000);
        $i = 0;
        $pass = '';

        while ($i <= 8) {
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
                $item_entite = null;
                $item_periodicite = null;
                $isupdate = false;
                $villavilla = null;
                //     dd($request) ;

                $item = new Contrat();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Contrat::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "Le contrat que vous tentez de modifier n'existe pas ",
                            );
                            return $retour;
                        }
                        $isupdate = true;
                    } else {
                        $retour = array(
                            "data" => null,
                            "error" => "L'id doit être un nombre entier",
                        );
                        return $retour;
                    }
                }


                if (isset($request->isRidwan)) {
                    $villavilla = isset($request->appartement) ? Appartement::find($request->appartement) : null;

                    $item_entite = Entite::where('code', "RID")->first();
                    if (empty($request->locataireexistant)) {
                        $errors = "Veuillez ajouter le reservataire";
                    }

                    if (!empty($request->numerodossier)) {
                        if (!Outil::isUnique(['numerodossier'], [$request->numerodossier], $request->id, Contrat::class)) {
                            $errors = "Ce numero de dossier existe déja !";
                        }
                    }
                    if (empty($request->apportinitial)) {
                        $errors = "Veuillez ajouter l'apport initial";
                    }
                    if (empty($request->dateremisecles)) {
                        $errors = "Veuillez ajouter la date de livraison des locaux";
                    }

                    // if (empty($request->dureelocationvente)) {
                    //     $errors = "Veuillez ajouter la durée de location vente";
                    // }
                    if (empty($request->clausepenale)) {
                        $errors = "Veuillez ajouter laclause pénale";
                    }
                    // if (empty($request->fraiscoutlocationvente)) {
                    //     $errors = "Veuillez ajouter les frais et coûts de la location-vente";
                    // }
                    if (empty($request->indemnite)) {
                        $errors = "Veuillez ajouter les intérêts de retard - indemnités ";
                    }
                    // if (empty($request->fraisgestion)) {
                    //     $errors = "Veuillez ajouter les frais de gestion ";
                    // }

                    if (empty($request->prixvilla)) {
                        $errors = "Veuillez ajouter le prix du villa ";
                    }
                    if (empty($request->acompteinitial)) {
                        $errors = "Veuillez ajouter l'acompte initial ";
                    }
                    // if (empty($request->maturite)) {
                    //     $errors = "Veuillez ajouter la maturité en année ";
                    // }
                    if (empty($request->appartement)) {
                        $errors = "Veuillez ajouter l'appartement du contrat";
                    } else {
                        $findApp = Appartement::find($request->appartement);
                        // dd($item->id);
                        if ($isupdate == false) {
                            if ($findApp && $findApp->iscontrat == '1') {
                                $errors = "La villa sélectionnée a déja un contrat encours";
                            }
                        }
                    }
                } else {
                    $item_entite = Entite::where('code', "SCI")->first();

                    if (empty($request->montantloyerbase)) {
                        $errors = "Veuillez ajouter le montant loyer de base du contrat";
                    }
                    if (empty($request->daterenouvellement)) {
                        $errors = "Veuillez ajouter la date de renouvellement du contrat";
                    }
                    if (empty($request->datepremierpaiement)) {
                        $errors = "Veuillez ajouter la date de premier paiement du contrat";
                    }
                    if (empty($request->appartement) && empty($request->appartementcontrat_id)) {
                        $errors = "Veuillez ajouter l'appartement du contrat";
                    }
                    if (empty($request->descriptif)) {
                        $errors = "Veuillez ajouter le descriptif du contrat";
                    }
                    if (empty($request->dateenregistrement)) {
                       // $errors = "Veuillez ajouter la date d'enregistrement du contrat";
                    }
                }

                if (empty($request->montantloyer)) {
                    $errors = "Veuillez ajouter le montant loyer du contrat";
                }
                // if (empty($request->dateecheance)) {
                //     $errors = "Veuillez ajouter la date d'echéance";
                // }
                if (empty($request->periodicite)) {
                    $errors = "Veuillez ajouter la périodicité ";
                } else {
                    $item_periodicite = Periodicite::find($request->periodicite);
                }
                // if (empty($request->document)) {
                //     $errors = "Veuillez ajouter le document du contrat";
                // }




                if (empty($request->datedebutcontrat)) {
                    $errors = "Veuillez ajouter la date du contrat";
                }
                // if (empty($request->rappelpaiement)) {
                //     $errors = "Veuillez ajouter la date de rappel paiement";
                // }

                if (empty($request->type_locataire)) {
                    $errors = "Veuillez renseigneer le locataire";
                } else {
                    if (isset($request->locataireexistant)) {
                        if ($request->type_locataire == 'existant') {
                            $item->locataire_id = $request->locataireexistant;
                            $locataireexistant = Locataire::find($item->locataire_id);


                            if ($locataireexistant->emailpersonneacontacter) {
                                $emaillocataire = $locataireexistant->emailpersonneacontacter;
                            } else {
                                $emaillocataire = $locataireexistant->email;
                            }
                            // dd($emaillocataire);
                            $user = User::where('email', $emaillocataire)->first();

                            if ($user) {

                                $locataireexistant->user_id = $user->id;
                                $locataireexistant->save();
                                if ($item_entite != null) {
                                    $user->entite_id    = $item_entite->id;
                                    $user->save();
                                }
                            } else if (($locataireexistant->nomentreprise)) {

                                $newuser = new User();
                                $newuser->image = $default_image = 'assets/images/default.png';
                                $newuser->name = $locataireexistant->nomentreprise;
                                $newuser->email = $locataireexistant->emailpersonneacontacter;
                                $mail = $locataireexistant->emailpersonneacontacter;
                                $newuser->locataire_id = $request->locataireexistant;
                                $newuser->active = 1;
                                // $pwd = $this->generatepwd() ;
                                Outil::saveUserPassword($newuser, "passer");

                                $item_role = Role::where('id', 2)->first();
                                if ($item_entite != null) {
                                    $newuser->entite_id    = $item_entite->id;
                                }
                                $newuser->save();
                                $newuser->syncRoles($item_role);

                                $locataireexistant->user_id = $newuser->id;
                                if ($item_entite != null) {
                                    $locataireexistant->entite_id    = $item_entite->id;
                                }

                                $locataireexistant->save();
                                $text = "Bonjour , votre compte résident GESTIMMO vient d'etre crée! voici vos informations de connection: login: $mail  , mot de passe: passer ";

                            //    Outil::envoiEmail($mail, 'COMPTE RESIDENT', $text);
                            } else if ($locataireexistant->prenom) {

                                //  dd($locataireexistant) ;
                                $prenom = $locataireexistant->prenom;
                                $nom = $locataireexistant->nom;
                                $name = $prenom . ' ' . $nom;
                                $newuser = new User();
                                $newuser->image = $default_image = 'assets/images/default.png';
                                $newuser->locataire_id = $request->locataireexistant;;
                                $newuser->name = $name;
                                $newuser->email = $locataireexistant->email;
                                $mail = $locataireexistant->email;
                                $newuser->active = 1;
                                //   $pwd = $this->generatepwd() ;
                                Outil::saveUserPassword($newuser, "passer");

                                $item_role = Role::where('id', 2)->first();
                                if ($item_entite != null) {
                                    $newuser->entite_id    = $item_entite->id;
                                }
                                $newuser->save();
                                $newuser->syncRoles($item_role);

                                $locataireexistant->user_id = $newuser->id;
                                if ($item_entite != null) {
                                    $locataireexistant->entite_id    = $item_entite->id;
                                }

                                $locataireexistant->save();

                                $text = "Bonjour , votre compte résident GESTIMMO vient d\'etre crée. ci apres vous trouverez vos informations de connection: login: $mail  , mot de passe: passer ";
                                // Outil::envoiEmail($mail, 'COMPTE RESIDENT', $text);
                            }
                        }
                    }

                    if ($request->type_locataire == 'nouveau') {


                        $default_image = 'assets/images/default.png';
                        $locataire = new Locataire();

                        if (empty($request->typelocataire)) {
                            $errors = "Veuillez choisir le type de locataire";
                        } else {
                            if ($request->typelocataire == '1') {
                                if (empty($request->prenom)) {
                                    $errors = "Veuillez renseigner le prenom du locataire";
                                }
                                if (empty($request->nom)) {
                                    $errors = "Veuillez renseigner le nom";
                                }
                                if (empty($request->telephoneportable1)) {
                                    $errors = "Veuillez renseigner le telephone";
                                }
                                if (empty($request->telephonebureau)) {
                                    $errors = "Veuillez renseigner le telephone bureau";
                                }
                                if (empty($request->profession)) {
                                    $errors = "Veuillez renseigner la profession";
                                }
                                if (empty($request->documentcnipassport)) {
                                    $errors = "Veuillez renseigner les le cni ou le passport";
                                }
                                if (empty($request->documentcontrattravail)) {
                                    $errors = "Veuillez renseigner le contrat de travail ou un justificatif";
                                }
                                if (empty($request->expatlocale)) {
                                    $errors = "Veuillez renseigner si c'est un expatrié ou un locale";
                                }
                                if (!empty($request->priseencharge)) {
                                    if (empty($request->nomcompletpersonnepriseencharge)) {
                                        $errors = "Veuillez renseigner le nom complet de la personne qui prend en charge";
                                    } else {
                                        $locataire->nomcompletpersonnepriseencharge = $request->nomcompletpersonnepriseencharge;
                                    }
                                    if (empty($request->telephonepersonnepriseencharge)) {
                                        $errors = "Veuillez renseigner le telephone de la personne qui prend en charge";
                                    } else {
                                        $locataire->telephonepersonnepriseencharge = $request->telephonepersonnepriseencharge;
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
                                        $locataire->contrattravail = $rename;
                                        //  $item->document = $rename;
                                    }
                                }

                                if ($request->file('documentcnipassport')) {
                                    $filenameWithExt = $request->file('documentcnipassport')->getClientOriginalName();

                                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                                    $extension = $request->file('documentcnipassport')->getClientOriginalExtension();

                                    $fileNameToStore = $filename . '-' . time() . '.' . $extension;

                                    $path = $request->file('documentcnipassport')->storeAs('uploads/locataires', $fileNameToStore);
                                    $fichier = isset($_FILES['documentcnipassport']['name']) ? $_FILES['documentcnipassport']['name'] : "";
                                    if (!empty($fichier)) {
                                        $dateHeure = date('Y_m_d_H_i_s');
                                        $fichier_tmp = $_FILES['documentcnipassport']['tmp_name'];
                                        $ext = explode('.', $fichier);
                                        $rename = config('view.uploads')[$this->queryName] . "/cnipassport_" . $dateHeure . "." . end($ext);
                                        move_uploaded_file($fichier_tmp, $rename);
                                        $locataire->documentcnipassport = $rename;
                                        //  $item->document = $rename;
                                    }
                                }

                                //   dd($request);
                                $locataire->prenom = $request->prenom;
                                $locataire->nom = $request->nom;
                                $locataire->telephoneportable1 = $request->telephoneportable1;
                                $locataire->telephoneportable2 = $request->telephoneportable2;
                                $locataire->telephonebureau = $request->telephonebureau;
                                $locataire->email = $request->email;
                                $locataire->profession = $request->profession;
                                $locataire->age = $request->age;
                                $locataire->cni = $request->cni;
                                $locataire->passeport = $request->passeport;
                                $locataire->typelocataire_id = $request->typelocataire;
                                $locataire->etatlocataire = '1';
                                $locataire->expatlocale = $request->expatlocale;
                                if ($item_entite != null) {
                                    $locataire->entite_id    = $item_entite->id;
                                }
                                $locataire->save();
                                $item->locataire_id = $locataire->id;



                                $user = User::where('email', $request->email)->first();
                                //  dd($user) ;
                                if ($user) {
                                    if ($item_entite != null) {
                                        $user->entite_id    = $item_entite->id;
                                        $user->save();
                                    }
                                    $locataire->user_id = $user->id;
                                    $locataire->save();
                                } else {

                                    $prenom = $request->prenom;
                                    $nom = $request->nom;
                                    $name = $prenom . ' ' . $nom;
                                    $newuser = new User();
                                    $newuser->image = $default_image = 'assets/images/default.png';
                                    $newuser->locataire_id = $locataire->id;
                                    $newuser->name = $name;
                                    $newuser->email = $request->email;
                                    $mail = $request->email;
                                    $newuser->active = 1;
                                    //   $pwd = $this->generatepwd() ;
                                    Outil::saveUserPassword($newuser, "passer");

                                    $item_role = Role::where('id', 2)->first();
                                    if ($item_entite != null) {
                                        $newuser->entite_id    = $item_entite->id;
                                    }

                                    $newuser->save();
                                    $newuser->syncRoles($item_role);

                                    $locataire->user_id = $newuser->id;
                                    if ($item_entite != null) {
                                        $locataire->entite_id    = $item_entite->id;
                                    }
                                    $locataire->save();

                                    $text = "Bonjour , votre compte résident GESTIMMO vient d\'etre crée. ci apres vous trouverez vos informations de connection: login: $mail  , mot de passe: passer ";

                                    // Outil::envoiEmail($mail, 'COMPTE RESIDENT', $text);
                                }
                            }
                            if ($request->typelocataire == '2') {
                                $locataire = new Locataire();

                                if (empty($request->nomentreprise)) {
                                    $errors = "Veuillez renseigner le nom de l'entreprise";
                                }
                                if (empty($request->adresseentreprise)) {
                                    $errors = "Veuillez renseigner l'adresse de l'entreprise";
                                }
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
                                    $errors = "Veuillez renseigner le telephone de la personne a contacter";
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
                                        $locataire->documentninea = $rename;
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
                                        $locataire->documentnumerorg = $rename;
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
                                        $locataire->documentstatut = $rename;
                                        //  $item->document = $rename;
                                    }
                                }

                                //   dd($request);

                                $locataire->nomentreprise = $request->nomentreprise;
                                $locataire->adresseentreprise = $request->adresseentreprise;
                                $locataire->ninea = $request->ninea;
                                $locataire->numerorg = $request->numerorg;
                                $locataire->email = $request->email;
                                $locataire->personnehabiliteasigner = $request->personnehabiliteasigner;
                                $locataire->fonctionpersonnehabilite = $request->fonctionpersonnehabilite;
                                $locataire->nompersonneacontacter = $request->nompersonneacontacter;
                                $locataire->prenompersonneacontacter = $request->prenompersonneacontacter;
                                $locataire->emailpersonneacontacter = $request->emailpersonneacontacter;
                                $locataire->telephone1personneacontacter = $request->telephone1personneacontacter;
                                $locataire->telephone2personneacontacter = $request->telephone2personneacontacter;
                                $locataire->typelocataire_id = $request->typelocataire;
                                $locataire->etatlocataire = '1';
                                if ($item_entite != null) {
                                    $locataire->entite_id    = $item_entite->id;
                                }
                                $locataire->save();
                                $item->locataire_id = $locataire->id;

                                $user = User::where('email', $request->emailpersonneacontacter)->first();
                                if ($user) {
                                    if ($item_entite != null) {
                                        $user->entite_id    = $item_entite->id;
                                    }
                                    $locataire->user_id = $user->id;
                                    $locataire->save();
                                } else {

                                    $newuser = new User();
                                    $newuser->image = $default_image = 'assets/images/default.png';
                                    $newuser->name = $request->nomentreprise;
                                    $newuser->email = $request->emailpersonneacontacter;
                                    $mail = $request->emailpersonneacontacter;

                                    $newuser->locataire_id = $locataire->id;
                                    $newuser->active = 1;
                                    //  $pwd = $this->generatepwd() ;
                                    Outil::saveUserPassword($newuser, "passer");

                                    $item_role = Role::where('id', 2)->first();
                                    if ($item_entite != null) {
                                        $newuser->entite_id    = $item_entite->id;
                                    }
                                    $newuser->save();
                                    $newuser->syncRoles($item_role);

                                    $locataire->user_id = $newuser->id;
                                    $locataire->save();

                                    $text = "Bonjour , votre compte résident GESTIMMO vient d\'etre crée. ci apres vous trouverez vos informations de connection: login: $mail  , mot de passe: passer ";

                                    // Outil::envoiEmail($mail, 'COMPTE RESIDENT', $text);
                                }

                                //   dd($locataire) ;
                            }
                        }
                    }
                }


                if ($request->scanpreavis) {
                    $item->scanpreavis = $request->scanpreavis;
                }
                if ($request->documentretourcaution) {
                    $item->documentretourcaution = $request->documentretourcaution;
                }
                if ($request->documentrecucaution) {
                    $item->documentrecucaution = $request->documentrecucaution;
                }
                if ($request->montantloyertom) {
                    $item->montantloyertom = $request->montantloyertom;
                }
                if ($request->montantcharge) {
                    $item->montantcharge = $request->montantcharge;
                }
                if ($request->tauxrevision) {
                    $item->tauxrevision = $request->tauxrevision;
                }
                if ($request->frequencerevision) {
                    $item->frequencerevision = $request->frequencerevision;
                }
                if ($request->dateretourcaution) {
                    $item->dateretourcaution = $request->dateretourcaution;
                }
                if ($request->typerenouvellement) {
                    $item->typerenouvellement_id = $request->typerenouvellement;
                }
                if ($request->caution) {
                    $item->caution_id = $request->caution;
                }
                if ($request->demanderesiliations) {
                    $item->demanderesiliations_id = $request->demanderesiliations;
                }
                if ($request->daterenouvellement) {
                    $item->daterenouvellement = $request->daterenouvellement;
                    $item->daterenouvellementcontrat = $request->daterenouvellement;
                }
                if ($request->rappelpaiement) {
                    $item->rappelpaiement = $request->rappelpaiement;
                }

                if ($request->file('document')) {
                    $filenameWithExt = $request->file('document')->getClientOriginalName();

                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                    $extension = $request->file('document')->getClientOriginalExtension();

                    $fileNameToStore = $filename . '-' . time() . '.' . $extension;

                    $path = $request->file('document')->storeAs('uploads/contrats', $fileNameToStore);
                    $fichier = isset($_FILES['document']['name']) ? $_FILES['document']['name'] : "";
                    if (!empty($fichier)) {
                        $dateHeure = date('Y_m_d_H_i_s');
                        $fichier_tmp = $_FILES['document']['tmp_name'];
                        $ext = explode('.', $fichier);
                        $rename = config('view.uploads')[$this->queryName] . "/contrat_" . $dateHeure . "." . end($ext);
                        move_uploaded_file($fichier_tmp, $rename);
                        $item->etat = 2;
                        $item->document = $rename;
                        //  $item->document = $rename;
                    }
                }
                if ($request->file('scanpreavis')) {
                    $filenameWithExt = $request->file('scanpreavis')->getClientOriginalName();

                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                    $extension = $request->file('scanpreavis')->getClientOriginalExtension();

                    $fileNameToStore = $filename . '-' . time() . '.' . $extension;

                    $path = $request->file('scanpreavis')->storeAs('uploads/contrats', $fileNameToStore);
                    $fichier = isset($_FILES['scanpreavis']['name']) ? $_FILES['scanpreavis']['name'] : "";
                    if (!empty($fichier)) {
                        $dateHeure = date('Y_m_d_H_i_s');
                        $fichier_tmp = $_FILES['scanpreavis']['tmp_name'];
                        $ext = explode('.', $fichier);
                        $rename = config('view.uploads')[$this->queryName] . "/preavis_" . $dateHeure . "." . end($ext);
                        move_uploaded_file($fichier_tmp, $rename);
                        $item->scanpreavis = $rename;
                        //  $item->document = $rename;
                    }
                }
                if ($request->file('documentretourcaution')) {
                    $filenameWithExt = $request->file('documentretourcaution')->getClientOriginalName();

                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                    $extension = $request->file('documentretourcaution')->getClientOriginalExtension();

                    $fileNameToStore = $filename . '-' . time() . '.' . $extension;

                    $path = $request->file('documentretourcaution')->storeAs('uploads/contrats', $fileNameToStore);
                    $fichier = isset($_FILES['documentretourcaution']['name']) ? $_FILES['documentretourcaution']['name'] : "";
                    if (!empty($fichier)) {
                        $dateHeure = date('Y_m_d_H_i_s');
                        $fichier_tmp = $_FILES['documentretourcaution']['tmp_name'];
                        $ext = explode('.', $fichier);
                        $rename = config('view.uploads')[$this->queryName] . "/retourcaution_" . $dateHeure . "." . end($ext);
                        move_uploaded_file($fichier_tmp, $rename);
                        $item->documentretourcaution = $rename;
                        //  $item->document = $rename;
                    }
                }
                if ($request->file('documentrecucaution')) {
                    $filenameWithExt = $request->file('documentrecucaution')->getClientOriginalName();

                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                    $extension = $request->file('documentrecucaution')->getClientOriginalExtension();

                    $fileNameToStore = $filename . '-' . time() . '.' . $extension;

                    $path = $request->file('documentrecucaution')->storeAs('uploads/contrats', $fileNameToStore);
                    $fichier = isset($_FILES['documentrecucaution']['name']) ? $_FILES['documentrecucaution']['name'] : "";
                    if (!empty($fichier)) {
                        $dateHeure = date('Y_m_d_H_i_s');
                        $fichier_tmp = $_FILES['documentrecucaution']['tmp_name'];
                        $ext = explode('.', $fichier);
                        $rename = config('view.uploads')[$this->queryName] . "/recucaution_" . $dateHeure . "." . end($ext);
                        move_uploaded_file($fichier_tmp, $rename);
                        $item->documentrecucaution = $rename;
                        //  $item->document = $rename;
                    }
                }

                if (isset($request->isRidwan)) {
                    $item->descriptif = isset($villavilla) && isset($villavilla->lot) ? "Location vente villa : " . $villavilla->lot : null;
                    $item->dateremisecles = $request->dateremisecles;
                    $item->apportinitial = $request->apportinitial;
                    if ($request->apportiponctuel) {
                        $item->apportiponctuel = $request->apportiponctuel;
                    }
                    $item->email  = $request->email;

                    $item->dureelocationvente = isset($request->dureelocationvente) ? $request->dureelocationvente : null;
                    $item->clausepenale = $request->clausepenale;

                    $item->indemnite = $request->indemnite;
                    //   $item->depot_initial = $request->depot_initial;
                    // $item->frais_gestion  = $request->fraisgestion;
                    $item->numerodossier  = $request->numerodossier;

                    $item->acompteinitial = $request->acompteinitial;
                    $item->prixvilla = $request->prixvilla;
                    $item->maturite = isset($request->maturite) ? $request->maturite : null;
                    $item->prixvilla = $request->prixvilla;

                    if (isset($request->fraisdegestion)) {
                        $item->fraisdegestion = $request->fraisdegestion;
                    }
                    if (isset($request->fraislocative)) {
                        $item->fraislocative = $request->fraislocative;
                    }
                    if (isset($request->codepartamortissemnt)) {
                        $item->codepartamortissemnt = $request->codepartamortissemnt;
                    }
                    if (isset($request->fraislocative) && isset($request->fraisdegestion)) {
                        $item->fraiscoutlocationvente = intval($request->fraislocative) + intval($request->fraisdegestion);
                    }
                    // if (isset($request->fraiscoutlocationvente)) {
                    //     $item->fraiscoutlocationvente = $request->fraiscoutlocationvente;
                    // }


                } else {

                    $item->montantloyerbase = $request->montantloyerbase;
                    $item->descriptif = $request->descriptif;
                }
                if ($item_periodicite != null) {
                    $item->periodicite_id = $item_periodicite->id;
                }
                // dd($item_periodicite);
                $item->montantloyer = $request->montantloyer;

                $item->dateenregistrement = isset($request->dateenregistrement) ? $request->dateenregistrement : date('Y-m-d');
                $item->datedebutcontrat = $request->datedebutcontrat;
                $item->typecontrat_id = $request->typecontrat;
                $item->delaipreavi_id = isset($request->delaipreavi) ? $request->delaipreavi : null;
                $item->datepremierpaiement = $request->datepremierpaiement;
                $item->dateecheance = $request->dateecheance ? $request->dateecheance : null;

                //Enregistrer les infos beneficaire si il y'a en
                $item->nomcompletbeneficiaire  = $request->nomcompletbeneficiaire;
                $item->telephonebeneficiaire   = $request->telephonebeneficiaire;
                $item->emailbeneficiaire       = $request->emailbeneficiaire;

                if ($request->appartementcontrat_id) {
                    $item->appartement_id = $request->appartementcontrat_id;
                    // $item->locataire_id = $request->locataire;
                    $item2 = Appartement::find($request->appartementcontrat_id);
                    $item2->iscontrat = 1;
                    $item2->islocataire = 1;
                    $item2->etatappartement_id = 1;
                    $item2->locataire_id = $item->locataire_id;
                    if (isset($request->isRidwan)) {
                        $item2->prixvilla = $request->prixvilla;
                        $item2->acomptevilla = $request->acompteinitial;
                    }
                    $item2->save();
                } else {
                    $item->appartement_id = $request->appartement;
                    // $item->locataire_id = $request->locataire;
                    $item2 = Appartement::find($request->appartement);
                    $item2->iscontrat = 1;
                    $item2->islocataire = 1;
                    $item2->etatappartement_id = 1;
                    $item2->locataire_id = $item->locataire_id;
                    if (isset($request->isRidwan)) {
                        $item2->prixvilla = $request->prixvilla;
                        $item2->acomptevilla = $request->acompteinitial;
                    }
                    $item2->save();
                }
                $item->codeappartement = $item2->codeappartement;
                if (!$isupdate) {
                    $item->etat = 1;
                }

                $item->status = '1';

                if (!empty($request->montantcaution)) {
                    if (empty($request->dateversement)) {
                        $errors = "Veuillez ajouter la date de versement de la caution";
                    }
                    if (empty($request->documentcaution)) {
                        $errors = "Veuillez ajouter le document de la caution";
                    }
                }
                //    dd($item) ;


                if (!isset($errors)) {
                    if ($isupdate) {
                        $existingContrat = Contrat::find($request->id);
                        if ($existingContrat->appartement->id != $request->appartement) {
                            # code...
                            $this->updateAppartement($existingContrat);
                        }
                    }

                    $item->save();
                    /// save annexe
                    $this->saveAnnexe($request, $item);

                    if (!empty($request->montantcaution)) {
                        $item3 = new Caution();
                        $item3->montantloyer = $request->montantloyer;
                        $item3->montantcaution = $request->montantcaution;
                        $item3->dateversement = $request->dateversement;
                        $item3->codeappartement = $item->codeappartement;
                        $item3->contrat_id = $item->id;
                        $item3->etat = 'payé';
                        $item3->document = $request->documentcaution;

                        if ($request->file('documentcaution')) {
                            $filenameWithExt = $request->file('documentcaution')->getClientOriginalName();

                            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

                            $extension = $request->file('documentcaution')->getClientOriginalExtension();

                            $fileNameToStore = $filename . '-' . time() . '.' . $extension;

                            $path = $request->file('documentcaution')->storeAs('uploads/cautions', $fileNameToStore);
                            $fichier = isset($_FILES['documentcaution']['name']) ? $_FILES['documentcaution']['name'] : "";
                            if (!empty($fichier)) {
                                $dateHeure = date('Y_m_d_H_i_s');
                                $fichier_tmp = $_FILES['documentcaution']['tmp_name'];
                                $ext = explode('.', $fichier);
                                $rename = config('view.uploads')[$this->queryName] . "/documentcaution_" . $dateHeure . "." . end($ext);
                                move_uploaded_file($fichier_tmp, $rename);
                                $item3->document = $rename;
                                //  $item->document = $rename;
                            }
                        }
                        $item3->save();
                        $item->caution_id = $item3->id;
                        $item->save();
                    }
                    $this->saveWithCoPreneur($request, $item);

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



    // save annexe
    public function saveAnnexe(Request $request, $contrat)
    {

        // dd($request->all());

        $annexes = [];

        if (isset($request->contrat_annexes) || isset($request->contrat_annexesreyhan)) {
            $array_rid =  json_decode($request->contrat_annexes, true);
            $array_rey = json_decode($request->contrat_annexesreyhan, true);
            if (count($array_rid) > 0) {
                $annexes = $array_rid;
            }
            if (count($array_rey) > 0) {
                $annexes = $array_rey;
            }
        }
        $oldAnnexes =  Annexe::where('contrat_id', $contrat->id)->get();

        if (count($oldAnnexes) > 0) {
            Outil::Checkdetail($oldAnnexes, $annexes, Annexe::class, "id");
        }
        // dd($annexes);
        if (count($annexes) > 0) {
            foreach ($annexes as $doc) {
                $anx = Annexe::where([['numero', $doc['numero']], ['contrat_id', $contrat->id]])->first();
                if (!$anx) {
                    if ($request->file('fichier_' . $doc['numero'])) {
                        $uploadedFile = Outil::uploadFile($request, 'fichier_' . $doc['numero'], public_path('uploads/annexes'));
                        $annexe = Annexe::create([
                            "filename" => $doc['nom'],
                            "numero" => $doc['numero'],
                            "filepath" => 'uploads/annexes/' . $uploadedFile['name'],
                            "contrat_id" => $contrat->id
                        ]);
                    }
                }
            }
        }
    }

    public function signature(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $data = null;
                $item = new Contrat();

                if (isset($request->contrat_id)) {
                    if (is_numeric($request->contrat_id) == true) {
                        $item = Contrat::find($request->contrat_id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "Le contrat que vous tentez de valider n'existe pas ",
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

                if (empty($request->signature_director)) {
                    $errors = "Le signature de la directrice est requis.";
                }


                if (!isset($errors)) {
                    $entite = Entite::where("code", "SCI")->first();
                    $gestionnaire = $entite->gestionnaire;

                    $signaturedg = $this->upload($request->signature_director);
                    $signaturecl = "";
                    if ($request->signature_client) {
                        $signaturecl = $this->upload($request->signature_client);
                    }
                    $item->signaturedirecteur = $signaturedg;
                    //  $item->usersigned_id = Auth::user()->id;
                    $item->etat = 2;
                    if ($signaturecl) {
                        $item->signatureclient = $signaturecl;
                    }

                    $item->save();

                    if (!$errors) {
                        // return Outil::redirectgraphql($this->queryName, "id:{$item->id}", Outil::$queries[$this->queryName]);
                        $data = 1;
                        if ($gestionnaire) {
                            // Outil::envoiEmail($gestionnaire->email, "Notification signature contrat", "Bonjour, la directrice vient de valider le nouveau contrat soumis.", 'maileur', null, ['abou050793@gmail.com', 'mansourpouye36@gmail.com']);
                        }
                    }
                }


                // throw new \Exception($errors);
                return response()->json(["data" => ["data" => $data], "errors" => $errors]);
            });
        } catch (\Exception $e) {
            return response()->json(["data" => ["data" => null], "errors" => $e]);
            // return Outil::getResponseError($e);
        }
    }


    public function relancePaiement(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;

                $contrat = $this->validateObject($request, Contrat::class, 'contrat');
                if (is_string($contrat)) {
                    $errors = "Le contrat n'existe pas !";
                }
                $locataire = $this->validateObject($request, Locataire::class, 'locataire');
                if (is_string($locataire)) {
                    $errors = "Le locataire n'existe pas !";
                }

                if (!isset($errors)) {
                    $mailSend = $locataire->email;


                    if (!$errors) {
                        return Outil::redirectgraphql($this->queryName, "id:{$contrat->id}", Outil::$queries[$this->queryName]);
                    }
                }


                throw new \Exception($errors);
                // return response()->json(["data" => ["data" => 1], "errors" => $errors]);
            });
        } catch (\Exception $e) {
            return Outil::getResponseError($e);
        }
    }

    public function sendMailDirector(Request $request)
    {
        $errors = null;
        $data = 0;
        $nomlocataire = "";
        $director = null;
        $message =
            "Je vous soumet un nouveau contrat en validation pour notre client ";

        try {
            $contrat = $this->validateObject($request, Contrat::class, 'contrat');
            if (is_string($contrat)) {
                $errors = $contrat;
            }
            $role = Role::where("is_director", 1)->first();
            if (!$role) {
                $errors = "une erreur est survenue";
            }

            // var_dump($contrat);
            // dd($role);
            if (!isset($erros)) {
                // for many director $director = User::where("role_id" , $role->id)->get();
                $director = User::whereHas('roles', function ($query) use ($role) {
                    $query->where('id', $role->id);
                })->first();

                // dd($director);
                $locataire = $contrat->locataire;
                if ($locataire->nom) {
                    $nomlocataire = $locataire->prenom . " " . $locataire->nom;
                } else if ($locataire->nomentreprise) {
                    $nomlocataire = $locataire->nomentreprise;
                }
                $message .= $nomlocataire;
                // Outil::envoiEmail($director->email, "Notification signature contrat", $message, 'signaturecontrat', $contrat->id, ['abou050793@gmail.com', 'mansourpouye36@gmail.com']);
            }
        } catch (\Exception $e) {
            // dd($e);
            $errors = "Vérifier les données fournies";
        }

        return response()->json(["data" => $data, "errors" => $errors]);
    }


    public function validationContratRidwanByJuriste(Request $request)
    {
        $errors = null;
        $data = 0;
        $message =
            "Un nouveau contrat est valider le Juriste, dont détails ci-dessous : \n ";
        try {
            if ($request->user()->can("valider-contrat-locationvente")) {
                $contrat = $this->validateObject($request, Contrat::class, 'contrat');
                if (is_string($contrat)) {
                    $errors = $contrat;
                }
                if (!isset($errors)) {
                    $data = 1;
                    $contrat->est_soumis = 1;
                    $contrat->etat = 2;
                    $contrat->save();
                }
            } else {
                $errors = "401 unautorizd";
            }
        } catch (\Exception $e) {
            $errors = "Vérifier les données fournies";
        }
        return response()->json(["data" => $data, "errors" => $errors]);
    }
    public function changeStatut(Request $request)
    {
        $errors = null;
        $data = 0;
        $nomlocataire = "";
        try {

            if (empty($request->statut)) {
                $errors = "statut is required";
            } else {
                if (!in_array($request->statut, [1, 2])) {
                    $errors = "statut is required";
                }
            }
            $contrat = $this->validateObject($request, Contrat::class, 'contrat');
            if (is_string($contrat)) {
                $errors = $contrat;
            }
            if (!isset($errors)) {
                $data = 1;
                $contrat->etat = $request->statut;
                $contrat->save();
            }
        } catch (\Exception $e) {
            $errors = "Vérifier les données fournies";
        }
        return response()->json(["data" => $data, "errors" => $errors]);
    }
    public function initAppartement(Request $request)
    {
        $errors = null;
        $data = null;
        $nomlocataire = "";
        try {

            $appartement = Appartement::find($request->id);
            $appartement->iscontrat = 0;
            $appartement->islocataire = 0;
            $appartement->etatlieu = "0";
            $appartement->etatappartement_id = 2;
            $appartement->locataire_id = null;
            $appartement->Etatlieux()->delete();
            $appartement->save();
        } catch (\Exception $e) {
            $errors = "Vérifier les données fournies";
        }
        return response()->json(["data" => $data, "errors" => $errors]);
    }
    public function sendMailDirectorRidwan(Request $request)
    {
        $errors = null;
        $data = 0;
        $nomlocataire = "";
        $director = null;
        $message =
            "Je vous soumet un nouveau contrat en validation pour notre client ";

        try {
            $contrat = $this->validateObject($request, Contrat::class, 'contrat');
            if (is_string($contrat)) {
                $errors = $contrat;
            }
            $role = Role::where("is_director", 1)->first();
            if (!$role) {
                $errors = "une erreur est survenue";
            }

            if (!isset($errors)) {
                $director = User::whereHas('roles', function ($query) use ($role) {
                    $query->where('id', $role->id);
                })->first();

                $locataire = $contrat->locataire;
                if ($locataire->nom) {
                    $nomlocataire = $locataire->prenom . " " . $locataire->nom;
                } else if ($locataire->nomentreprise) {
                    $nomlocataire = $locataire->nomentreprise;
                }
                $message .= $nomlocataire;
                $data = 1;

                $contrat->est_soumis = 1;
                $contrat->save();
                // Outil::envoiEmail($director->email, "Notification signature contrat de location vente", $message, 'signaturecontratridwan', $contrat->id, ['abou050793@gmail.com', 'mansourpouye36@gmail.com'], null);
            }
        } catch (\Exception $e) {
            $errors = "Vérifier les données fournies";
        }

        return response()->json(["data" => $data, "errors" => $errors]);
    }
    public function annulerEnvoieContratRidwan(Request $request)
    {
        $errors = null;
        $data = 0;

        try {
            $contrat = $this->validateObject($request, Contrat::class, 'contrat');
            if (is_string($contrat)) {
                $errors = $contrat;
            }
            if (!isset($erros)) {
                $data = 1;
                $contrat->est_soumis = 0;
                $contrat->save();
            }
        } catch (\Exception $e) {

            $errors = "Vérifier les données fournies";
        }
        return response()->json(["data" => $data, "errors" => $errors]);
    }
    public function upload($signature,  $folderPath = "uploads/contrats/")
    {

        $base64Image = explode(";base64,", $signature);
        $explodeImage = explode("image/", $base64Image[0]);
        $imageType = $explodeImage[1];
        $image_base64 = base64_decode($base64Image[1]);
        $file = $folderPath . "signature_" . uniqid() . '.' . $imageType;
        file_put_contents($file, $image_base64);
        return $file;
    }


    public function delete($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $errors = null;

                if ((int) $id) {
                    $item = Contrat::find($id);
                    if (isset($item)) {
                        // related models
                        $item->Assurances()->delete();
                        $item->Caution()->delete();
                        $item->Versementloyers()->delete();
                        $item->Versementchargecoproprietes()->delete();
                        $item->Paiementloyers()->delete();
                        $item->Demanderesiliation()->delete();
                        $item->facturelocations()->delete();
                        $item->avisecheances()->delete();
                        $item->annexes()->delete();
                        $item->factureeauxs()->delete();
                        $this->updateAppartement($item);
                        $item->delete();
                        $item->forceDelete();
                        $data = 1;
                    } else {
                        $errors = "Cet élément n'existe pas";
                    }
                } else {
                    $errors = "Données manquantes";
                }
                if ($errors) {
                    throw new \Exception($errors);
                } else {
                    $retour = array(
                        'data' => $data,
                    );
                }
                return response()->json($retour);
            });
        } catch (\Exception $e) {
            return Outil::getResponseError($e);
        }
    }

    function updateAppartement($item)
    {
        $appartement = Appartement::find($item->appartement_id);
        $appartement->iscontrat = 0;
        $appartement->islocataire = 0;
        $appartement->etatlieu = "0";
        $appartement->etatappartement_id = 2;
        $appartement->locataire_id = null;
        $appartement->Etatlieux()->delete();
        $appartement->save();
    }


    /**
     * Ajouter le co-preneur dans le contrat s'il existe
     *
     * @param Request $request
     * @param Contrat $contrat
     * @return void
     */
    function saveWithCoPreneur(Request $request, Contrat $contrat)
    {
        $estCopreneur = $request->input('est_copreuneur', false);
        $isRidwan = $request->isRidwan;
        if ($estCopreneur && isset($isRidwan)) {
            $copreneurId = $request->input('copreneur');
            $copreneur = $copreneurId ? Copreneur::find($copreneurId) : null;
            if ($copreneur) {
                $contrat->copreneur()->associate($copreneur);
                $contrat->est_copreuneur = 1;
            } else {
                $contrat->est_copreuneur = 0;
            }
        } else {
            $contrat->est_copreuneur = 0;
        }

        $contrat->save();
    }

    public function contratlocationventerepaire()
    {
        return Outil::resolveContratlocationVente();
    }
}
