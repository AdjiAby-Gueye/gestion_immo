<?php

namespace App\Jobs;

use App\User;
use DateTime;
use App\Outil;
use App\Contrat;
use App\Immeuble;
use App\Locataire;
use Spatie\Permission\Models\Role;

use Carbon\Carbon;
use App\Appartement;
use App\Composition;
use App\Delaipreavi;
use App\Typecontrat;
use App\Proprietaire;
use App\Etatappartement;
use App\Typeappartement;
use App\Niveauappartement;
use Illuminate\Bus\Queueable;
use App\Typeappartement_piece;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Frequencepaiementappartement;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportContratLocationFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    private $file;

    /**
     * @var string
     */
    private $pathFile;

    /**
     * @var string
     */
    private $generateLink;

    /**
     * @var Model
     */
    private $model;

    /**
     * @var User
     */
    private $user;
    private $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model, $generateLink, string $file, $userId, $pathFile)
    {
        $this->model = $model;
        $this->generateLink = $generateLink;
        $this->file = $file;
        $this->userId = $userId;
        $this->pathFile = $pathFile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Outil::setParametersExecution();
        try {
            $this->user = User::find($this->userId);

            $filename = $this->file;
            $data = Excel::toArray(null, $filename);
            $data = $data[0]; // 0 => à la feuille 1

            $report = array();

            $totalToUpload = count($data) - 1;
            $totalUpload = 0;
            $lastnewcontrat = null;
            DB::transaction(function () use (&$totalUpload, &$data, &$report, &$lastnewcontrat) {
                //    dd($data);
                for ($i = 1; $i < count($data); $i++) {
                    $errors = null;
                    $is_save = 0;
                    $row = $data[$i];

                    try {
                        $get_description     = isset($row[0]) ? trim($row[0]) : null;
                        $get_locataire     = isset($row[1]) ? trim(strtolower($row[1])) : null;
                        $get_appartement     = isset($row[2]) ? trim($row[2]) : null;
                        $get_immeuble     = isset($row[3]) ? trim($row[3]) : null;;
                        $get_adresse     = isset($row[4]) ? trim($row[4]) : null;
                        $get_montantloyer    = isset($row[5]) ? trim($row[5]) : null;
                        $get_montantloyerbase    = isset($row[6]) ? trim($row[6]) : null;
                        $get_montantloyertom    = isset($row[7]) ? trim($row[7]) : null;
                        $get_montantcharge    = isset($row[8]) ? trim($row[8]) : null;
                        $get_tauxrevision   = isset($row[9]) ? trim($row[9]) : null;
                        $get_frequencerevision    = isset($row[10]) ? trim($row[10]) : null;
                        $get_datenregistrement   = isset($row[11]) ? trim($row[11]) : null;
                        $get_datepremierpaiement   = isset($row[12]) ? trim($row[12]) : null;
                        $get_daterenouvellement   = isset($row[13]) ? trim($row[13]) : null;
                        $get_datedebutcontrat   = isset($row[14]) ? trim($row[14]) : null;

                        $get_typecontrat    = isset($row[15]) ? trim($row[15]) : null;
                        $get_preavi   = isset($row[17]) ? trim($row[17]) : null;
                        $get_etatcontrat   = isset($row[18]) ? trim($row[18]) : null;
                    } catch (\Exception $e) {
                        $errors = "Vérifier le format du fichier";
                        array_push($report, [
                            'ligne'             => ($i),
                            'libelle'           => "Contrats",
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                        break;
                    }


                    $locataire = null;
                    $get_locataire              ?: $errors = "Veuillez definir le locataire";
                    if (isset($get_locataire)) {
                        if (strpos($get_locataire, " ") !== false) {
                            $get_locataire2 = explode(" ", $get_locataire);
                            $get_ppp_prenom = $get_locataire2[0];
                            $get_ppp_nom = $get_locataire2[1];
                            $locataire = Locataire::whereRaw('TRIM(lower(nom)) = TRIM(lower(?)) AND TRIM(lower(prenom)) = TRIM(lower(?))', ["$get_ppp_nom", "$get_ppp_prenom"])->first();

                        }
                        if (!$locataire) {
                            $locataire = Locataire::whereRaw("TRIM(lower(nomentreprise)) = TRIM(?)", ["$get_locataire"])->first();
                        }
                        $locataire              ?: $errors = "Veuillez definir le locataire locataire";
                    }
                    // dd($locataire);
                    $get_immeuble =  Immeuble::whereRaw('TRIM(lower(nom)) = TRIM(lower(?))', ["$get_immeuble"])->first();
                    $get_immeuble              ?: $errors = "Veuillez definir l'immeuble";


                    $get_appartement                     = Appartement::whereRaw('TRIM(lower(nom)) = TRIM(lower(?))', ["$get_appartement"])->where("immeuble_id", (isset($get_immeuble) && isset($get_immeuble->id)) ? $get_immeuble->id : null)->first();
                    $get_appartement              ?: $errors = "Veuillez definir l'apppartement";

                    $get_montantloyer              ?: $errors = "Veuillez definir le montant loyer";

                    $get_frequencerevision             ?: $errors = "Veuillez definir la frequence de revision";

                    $get_typecontrat = Typecontrat::whereRaw('TRIM(lower(designation)) = TRIM(lower(?))', ["$get_typecontrat"])->first();
                    $get_typecontrat             ?: $errors = "Veuillez definir le type de contrat ";

                    $get_preavi = Delaipreavi::whereRaw('TRIM(lower(designation)) = TRIM(lower(?))', ["$get_preavi"])->first();
                    $get_preavi             ?: $errors = "Veuillez definir le type de preavis";

                    // $newcontrat = Contrat::whereRaw('TRIM(lower(descriptif)) = TRIM(lower(?))', ["$get_description"])->first();


                    // $dateEnregistrement = DateTime::createFromFormat('d/m/Y', $get_datenregistrement);

                    // dd($errors);
                    if (!$errors) {
                        // if(!isset($newcontrat))
                        // {
                        //     $newcontrat        = new  Contrat();
                        //     $newcontrat->descriptif = "Contrat location / ".$get_appartement->nom." / ".$get_immeuble->nom;
                        // }
                        // $newcontrat = new Contrat();
                        $newcontrat        = new  Contrat();
                        $newcontrat->descriptif = "Contrat location / " . $get_appartement->nom . " / " . $get_immeuble->nom;
                        $newcontrat->montantloyer = $get_montantloyer;
                        $newcontrat->delaipreavi_id = $get_preavi->id;
                        $newcontrat->montantloyerbase = $get_montantloyerbase;
                        $newcontrat->montantloyertom = $get_montantloyertom;
                        $newcontrat->montantcharge = $get_montantcharge;


                        // $newcontrat->dateenregistrement = date_format(date_create($get_datenregistrement), "Y-m-d");
                        // $newcontrat->datepremierpaiement = date_format(date_create($get_datepremierpaiement), "Y-m-d");
                        // $newcontrat->datedebutcontrat = date_format(date_create($get_datedebutcontrat), "Y-m-d");
                        // $newcontrat->daterenouvellement = date_format(date_create($get_daterenouvellement), "Y-m-d");
                        //  $newcontrat->dateenregistrement = Carbon::parse($get_datenregistrement);
                        // $newcontrat->datepremierpaiement = Carbon::parse($get_datepremierpaiement);
                        // $newcontrat->datedebutcontrat =  Carbon::parse($get_datedebutcontrat);
                        // $newcontrat->daterenouvellement = Carbon::parse($get_daterenouvellement);
                        $newcontrat->typecontrat_id = $get_typecontrat->id;


                        $newcontrat->appartement_id = $get_appartement->id;
                        $get_appartement->iscontrat = 1;
                        $get_appartement->islocataire = 1;
                        $get_appartement->etatappartement_id = 1;
                        $get_appartement->locataire_id = $locataire->id;
                        $get_appartement->save();

                        $newcontrat->locataire_id = $locataire->id;
                        $newcontrat->codeappartement = $get_appartement->codeappartement;
                        $newcontrat->etat = 1;
                        $newcontrat->status = '1';
                        $get_frequencerevision = explode(" ", $get_frequencerevision);
                        $newcontrat->frequencerevision = $get_frequencerevision[0];
                        $newcontrat->tauxrevision = $get_tauxrevision;

                        $newcontrat->dateenregistrement = date("Y-m-d");
                        $newcontrat->datepremierpaiement = date("Y-m-d");
                        $newcontrat->daterenouvellement = date("Y-m-d");
                        $newcontrat->datedebutcontrat = date("Y-m-d");
                        $emaillocataire = null;
                        if(($locataire->emailpersonneacontacter) ){
                            $emaillocataire = $locataire->emailpersonneacontacter ;
                        }
                        else{
                            $emaillocataire = $locataire->email ;
                        }
                        $user = User::where('email', $emaillocataire)->first();

                       // dd($user) ;
                        if ($user) {

                            $locataire->user_id = $user->id;
                            $locataire->save() ;
                        }
                        else if (($locataire->nomentreprise)){

                            $newuser = new User() ;
                            $newuser->image  = 'assets/images/default.png';
                            $newuser->name = $locataire->nomentreprise;
                            $newuser->email = $locataire->emailpersonneacontacter;
                            $mail = $locataire->emailpersonneacontacter ;
                            $newuser->locataire_id = $locataire->id;
                            $newuser->active = 1;
                           // $pwd = $this->generatepwd() ;
                            Outil::saveUserPassword($newuser,"passer");

                            $item_role = Role::where('id', 2)->first();

                            $newuser->save();
                            $newuser->syncRoles($item_role);

                            $locataire->user_id = $newuser->id;
                            $locataire->save() ;

                        }

                        else if (isset($locataire->prenom)){

                          //  dd($locataire) ;
                            $prenom = $locataire->prenom ;
                            $nom = $locataire->nom ;
                            $name = $prenom . ' ' . $nom ;
                            $newuser = new User() ;
                            $newuser->image = $default_image = 'assets/images/default.png';
                            $newuser->locataire_id = $locataire->id;
                            $newuser->name = $name;
                            $newuser->email = $locataire->email;
                            $mail = $locataire->email ;
                            $newuser->active = 1;
                         //   $pwd = $this->generatepwd() ;
                            Outil::saveUserPassword($newuser,"passer");

                            $item_role = Role::where('id' , 2)->first();

                            $newuser->save();
                            $newuser->syncRoles($item_role);

                            $locataire->user_id = $newuser->id;
                            $locataire->save() ;


                        }

                        $is_save =  $newcontrat->save();

                        $lastnewcontrat = $newcontrat;

                    }
                    //
                    if ($is_save) {
                        $totalUpload++;
                    }

                    if (!empty($montantloyer) && !$is_save) {
                        array_push($report, [
                            'ligne'             => ($i + 1),
                            'libelle'           => $montantloyer,
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                    }
                }
            });

            Outil::atEndUploadData($this->pathFile, $this->generateLink, $report, $this->user, $totalToUpload, $totalUpload, " des contrats", $lastnewcontrat);
        } catch (\Exception $e) {
            try {
                File::delete($this->pathFile);
            } catch (\Exception $eFile) {
            };
            throw new \Exception($e);
        }
    }
    function generateCodeAppartement()
    {

        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((float)microtime() * 1000000);
        $i = 0;
        $pass = '';

        while ($i <= 5) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }

        return $pass;
    }
}
