<?php

namespace App\Jobs;

use App\Categorieprestataire;
use App\User;
use App\Outil;
use App\Prestataire;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportPrestataireFileJob implements ShouldQueue
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
            $lastnewprestataire = null;
            DB::transaction(function () use (&$totalUpload, &$data, &$report, &$lastnewprestataire)
            {
                for ($i=1;$i < count($data);$i++)
                {
                    $errors = null;
                    $is_save = 0;
                    $row = $data[$i];

                    try
                    {
                        $get_nom     = isset($row[0]) ? trim($row[0]) : null;
                        $get_email     =isset($row[1]) ? trim($row[1]) : null;
                        $get_adresse     = isset($row[2]) ? trim($row[2]) : null;
                        $get_telephone1     = isset($row[3]) ? trim($row[3]) : null;
                        $get_telephone2     = isset($row[4]) ? trim($row[4]) : null;
                        $get_categorie     = isset($row[5]) ? trim($row[5]) : null;
                    }
                    catch (\Exception $e)
                    {
                        $errors = "Vérifier le format du fichier";
                        array_push($report, [
                            'ligne'             => ($i),
                            'libelle'           => "Prestataires",
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                        break;
                    }

                    $get_nom              ?:$errors = "Veuillez definir le nom";
                    $get_email              ?:$errors = "Veuillez definir l'email";
                    $get_adresse              ?:$errors = "Veuillez definir l'adresse";
                    $get_telephone1              ?:$errors = "Veuillez definir le telephone1";
                    $get_categorie_new = Categorieprestataire::whereRaw('TRIM(lower(designation)) = TRIM(lower(?))', ["$get_categorie"])->first();
                    // $get_categorie              ?:$errors = "Veuillez definir la categorie";

                    $newprestataire                     = Prestataire::whereRaw('TRIM(lower(nom)) = TRIM(lower(?))', ["$get_nom"])->first();


                    
                    if(!$errors)
                    {
                        if(!isset($newprestataire))
                        {
                            $newprestataire        = new  Prestataire();
                        }
                        if (!isset($get_categorie_new)) {
                            $get_categorie_new = new Categorieprestataire();
                            $get_categorie_new->designation = $get_categorie;
                            $get_categorie_new->save();
                        }
                        $newprestataire->nom = $get_nom;
                        $newprestataire->email = $get_email;
                        $newprestataire->adresse = $get_adresse;
                        $newprestataire->telephone1 = $get_telephone1;
                        $newprestataire->telephone2 = $get_telephone2;
                        $newprestataire->categorieprestataire_id = $get_categorie_new ? $get_categorie_new->id : null;

                        $is_save = $newprestataire->save();

                        $lastnewprestataire = $newprestataire;
                    }
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

            Outil::atEndUploadData($this->pathFile, $this->generateLink, $report, $this->user, $totalToUpload, $totalUpload, " des prestataires", $lastnewprestataire);


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
