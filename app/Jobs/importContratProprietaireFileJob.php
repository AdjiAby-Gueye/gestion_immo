<?php

namespace App\Jobs;

use App\Contrat;
use App\Contratproprietaire;
use App\Entite;
use App\Outil;
use App\Proprietaire;
use App\Typeapportponctuel;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class importContratProprietaireFileJob implements ShouldQueue
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
            $lastnewcontratproprietaire = null;
            DB::transaction(function () use (&$totalUpload, &$data, &$report, &$lastnewprestataire)
            {
                for ($i=1;$i < count($data);$i++)
                {
                    $errors = null;
                    $is_save = 0;
                    $row = $data[$i];

                    try
                    {
                        $get_entite     = isset($row[0]) ? trim($row[0]) : null;
                        $get_proprietaire     = isset($row[1]) ? trim($row[1]) : null;
                        $get_modelcontrat     = isset($row[2]) ? trim($row[2]) : null;
                        $get_date     = isset($row[3]) ? trim($row[3]) : null;
                        $get_descriptif     =isset($row[4]) ? trim($row[4]) : null;
                        $get_commissionvaleur     = isset($row[5]) ? trim($row[5]) : null;
                        $get_commissionpourcentage     = isset($row[6]) ? trim($row[6]) : null;
                        $get_tva     = isset($row[7]) ? trim($row[7]) : null;
                        $get_brs     = isset($row[8]) ? trim($row[8]) : null;
                        $get_tlv     = isset($row[9]) ? trim($row[9]) : null;

                        if ($get_date !== '') {
                            if (is_numeric($get_date)) {
                                $phpDate = Date::excelToDateTimeObject($get_date);
                                $get_date = $phpDate->format('Y-m-d');
                            } else {
                                $phpDate = new \DateTime($get_date);
                                $get_date = $phpDate->format('Y-m-d');
                            }
                        }

                         /* if($get_date !== ''){
                            if(!is_numeric($get_date))
                            {
                                $get_date = strtotime($get_date);
                            }



                            $get_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($get_date);
                            //File::delete($this->pathFile);
                            //dd($get_date);
                        } */


                    }
                    catch (\Exception $e)
                    {
                        $errors = "Vérifier le format du fichier";
                        array_push($report, [
                            'ligne'             => ($i),
                            'libelle'           => "Apportponctuels",
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                        break;
                    }

                    $get_entite              ?:$errors = "Veuillez definir l'entite";
                    $get_proprietaire              ?:$errors = "Veuillez definir le proprietaire";
                    $get_modelcontrat              ?:$errors = "Veuillez definir le model du contrat";
                    $get_date              ?:$errors = "Veuillez definir la date";
                    $get_descriptif              ?:$errors = "Veuillez definir le descriptif";
                    $get_commissionvaleur              ?:$errors = "Veuillez definir la valeur de la commission";
                    $get_commissionpourcentage              ?:$errors = "Veuillez definir le pourcentage de la commission";
                    $get_tva              ?:$errors = "Veuillez definir le tva";
                    $get_brs              ?:$errors = "Veuillez definir le brs";
                    $get_tlv              ?:$errors = "Veuillez definir le tlv";


                    //$appartement = Appartement::where('lot', $get_lot)->first();
                    //$contrat = Contrat::where('descriptif', $get_lot)->first();


                    $entite = Entite::where('designation', $get_entite)->first();
                    $proprietaire = Proprietaire::where('prenom', $get_proprietaire)->first();
                    $modelcontrat = Entite::where('designation', $get_modelcontrat)->first();

                    if(!$entite){
                        $errors = "Entite non trouvé !";
                        }else{
                            $entite_id = $entite->id;
                    }
                    if(!$proprietaire){
                        $errors = "Proprietaire non trouvé !";
                        }else{
                            $proprietaire_id = $proprietaire->id;
                    }
                    if(!$modelcontrat){
                        $errors = "Model de contrat non trouvé !";
                        }else{
                            $modelcontrat_id = $modelcontrat->id;
                    }


                    if(!$errors)
                    {

                        $newcontratproprietaire       = new  Contratproprietaire();

                        $newcontratproprietaire->entite_id = $entite_id;
                        $newcontratproprietaire->proprietaire_id = $proprietaire_id;
                        $newcontratproprietaire->modelcontrat_id = $modelcontrat_id;
                        $newcontratproprietaire->date = $get_date;
                        $newcontratproprietaire->descriptif = $get_descriptif;
                        $newcontratproprietaire->commissionvaleur = $get_commissionvaleur;
                        $newcontratproprietaire->commissionpourcentage = $get_commissionpourcentage;
                        $newcontratproprietaire->is_tva = $get_tva;
                        $newcontratproprietaire->is_brs = $get_brs;
                        $newcontratproprietaire->is_tlv = $get_tlv;

                        $is_save = $newcontratproprietaire->save();

                        $lastnewcontratproprietaire = $newcontratproprietaire;
                    }
                    if($is_save)
                    {
                        $totalUpload ++;
                    }


                }
            });

            Outil::atEndUploadData($this->pathFile, $this->generateLink, $report, $this->user, $totalToUpload, $totalUpload, " des contrats proprietaire", $lastnewcontratproprietaire);


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
}
