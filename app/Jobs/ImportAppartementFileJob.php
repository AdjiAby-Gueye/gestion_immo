<?php

namespace App\Jobs;

use App\User;
use App\Outil;
use App\Immeuble;
use App\Appartement;
use App\Composition;
use App\Etatappartement;
use App\Frequencepaiementappartement;
use App\Proprietaire;
use App\Niveauappartement;
use App\Typeappartement;
use App\Typeappartement_piece;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportAppartementFileJob implements ShouldQueue
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
        try
        {
            $this->user = User::find($this->userId);

            $filename = $this->file;
            $data = Excel::toArray(null, $filename);
            $data = $data[0]; // 0 => à la feuille 1

            $report = array();

            $totalToUpload = count($data) - 1;
            $totalUpload = 0;
            $lastnewappartement = null;
            DB::transaction(function () use (&$totalUpload, &$data, &$report, &$lastnewappartement)
            {
              //  dd($data);
                for ($i=1;$i < count($data);$i++)
                {
                    $errors = null;
                    $is_save = 0;
                    $row = $data[$i];

                    try
                    {
                        $get_nom     = isset($row[0]) ? trim($row[0]) : null;
                        $get_immeuble     = isset($row[1]) ? trim(strtolower($row[1])) : null;
                        $get_niveau     = isset($row[2]) ? trim($row[2]) : null;
                        $get_proprietaire     = isset($row[3]) ? trim($row[3]) : null;;
                        $get_type     = isset($row[4]) ? trim($row[4]) : null;
                        $get_etat    = isset($row[5]) ? trim($row[5]) : null;
                        $get_frequencepaiement    = isset($row[6]) ? trim($row[6]) : null;
                        $get_superficie    = isset($row[7]) ? trim($row[7]) : null;

                    }
                    catch (\Exception $e)
                    {
                        $errors = "Vérifier le format du fichier";
                        array_push($report, [
                            'ligne'             => ($i),
                            'libelle'           => "Appartements",
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                        break;
                    }
                    if (isset($get_proprietaire)) {
                        $proprietaire = explode(" ",$get_proprietaire);
                        $get_ppp_prenom = $proprietaire[0];
                        $get_ppp_nom = $proprietaire[1];
                    }

                    $get_nom              ?:$errors = "Veuillez definir le nom de l'appartement";
                    $get_immeuble =  Immeuble::whereRaw('TRIM(lower(nom)) = TRIM(?)', ["$get_immeuble"])->first();
                    $get_immeuble              ?:$errors = "Veuillez definir l'immeuble";
                    $get_niveau              ?:$errors = "Veuillez definir le niveau dans l'immeuble ";
                    $get_proprietaire                    = Proprietaire::whereRaw('TRIM(lower(nom)) = TRIM(lower(?)) AND TRIM(lower(prenom)) = TRIM(lower(?))', ["$get_ppp_nom" , "$get_ppp_prenom"])->first();
                    $get_proprietaire              ?:$errors = "Veuillez definir le proprietaire";
                    $get_type = Typeappartement::whereRaw('TRIM(lower(designation)) = TRIM(lower(?))', ["$get_type"])->first();
                    $get_type             ?:$errors = "Veuillez definir le type d'appartement";
                    $get_etat = Etatappartement::whereRaw('TRIM(lower(designation)) = TRIM(lower(?))', ["$get_etat"])->first();
                    $get_etat             ?:$errors = "Veuillez definir l'etat de l'appartement";
                    $get_frequencepaiement = Frequencepaiementappartement::whereRaw('TRIM(lower(designation)) = TRIM(lower(?))', ["$get_frequencepaiement"])->first();
                    $get_frequencepaiement             ?:$errors = "Veuillez definir le Frequencepaiementappartement";

                    $get_superficie             ?:$errors = "Veuillez definir la superficie";
                    $idImmeuble = $get_immeuble ? $get_immeuble->id : null;
                    $newappartement                     = Appartement::whereRaw('TRIM(lower(nom)) = TRIM(lower(?))', ["$get_nom"])->where("immeuble_id" , $idImmeuble)->first();
                //    $newappartement                     = Appartement::whereRaw('TRIM(lower(nom)) = TRIM(lower(?))', ["$get_nom"])->first();

                    // dd($errors);
                    if(!$errors)
                    {
                        if(!isset($newappartement))
                        {
                            $newappartement        = new  Appartement();
                            $codeAppartement = $this->generateCodeAppartement() ;
                            $newappartement->codeappartement = $codeAppartement;
                        }

                        $newappartement->nom = $get_nom;
                        $newappartement->immeuble_id = $get_immeuble ? $get_immeuble->id : null;
                        $newappartement->proprietaire_id = $get_proprietaire ? $get_proprietaire->id : null;
                        $newappartement->frequencepaiementappartement_id = $get_frequencepaiement ? $get_frequencepaiement->id : null;
                        $newappartement->typeappartement_id = $get_type ? $get_type->id : null;
                        $newappartement->etatappartement_id = $get_etat ? $get_etat->id : null;
                        $newappartement->niveau = $get_niveau;
                        $newappartement->superficie = $get_superficie;
                        $newappartement->isassurance = 0;
                        $newappartement->iscontrat = 0;
                        $newappartement->islocataire = 0;
                        $newappartement->etatlieu = "0";

                        $is_save = $newappartement->save();

                        $tabTypepieceAppartement = Typeappartement_piece::where('typeappartement_id' , $newappartement->typeappartement_id)->get();
                        if(count($tabTypepieceAppartement) > 0){
                            foreach ($tabTypepieceAppartement as $value) {
                                $newCompositionAppartemnt = new Composition();
                                $newCompositionAppartemnt->typeappartement_piece_id = $value->id;
                                $newCompositionAppartemnt->appartement_id = $newappartement->id;
                                $newCompositionAppartemnt->save();
                            }
                        }

                        $lastnewappartement = $newappartement;
                    }
                    //
                    if($is_save)
                    {
                        $totalUpload ++;
                    }

                    if (!empty($get_nom) && !$is_save)
                    {
                        array_push($report, [
                            'ligne'             => ($i+1),
                            'libelle'           => $get_nom,
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                    }
                }
            });

            Outil::atEndUploadData($this->pathFile, $this->generateLink, $report, $this->user, $totalToUpload, $totalUpload, " des appartements", $lastnewappartement);


        }
        catch (\Exception $e)
        {
            try
            {
                File::delete($this->pathFile);
            }
            catch (\Exception $eFile) {};
            throw new \Exception($e);
        }
    }
    function generateCodeAppartement() {

        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double)microtime()*1000000);
        $i = 0;
        $pass = '' ;

        while ($i <= 5) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }

        return $pass;

    }

}
