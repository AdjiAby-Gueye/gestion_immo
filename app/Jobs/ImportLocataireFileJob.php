<?php

namespace App\Jobs;

use App\User;
use App\Outil;
use App\Entite;
use App\Immeuble;
use App\Locataire;
use App\Proprietaire;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportLocataireFileJob implements ShouldQueue
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
            $lastnewlocataire = null;
            DB::transaction(function () use (&$totalUpload, &$data, &$report, &$lastnewlocataire)
            {
                for ($i=1;$i < count($data);$i++)
                {
                    $errors = null;
                    $is_save = 0;
                    $row = $data[$i];

                    try
                    {
                        $get_nom     = isset($row[0]) ? trim($row[0]) : null;
                        $get_prenom     =isset($row[1]) ? trim($row[1]) : null;
                        $get_adresse     = isset($row[2]) ? trim($row[2]) : null;
                        $get_telephone1     = isset($row[3]) ? trim($row[3]) : null;
                        $get_telephone2     = isset($row[4]) ? trim($row[4]) : null;
                        $get_email     = isset($row[5]) ? trim($row[5]) : null;
                        $get_profession     = isset($row[6]) ? trim($row[6]) : null;
                        $get_cni     = isset($row[7]) ? trim($row[7]) : null;
                        $get_entite     = isset($row[8]) ? trim($row[8]) : null;
                    }
                    catch (\Exception $e)
                    {
                        $errors = "Vérifier le format du fichier";
                        array_push($report, [
                            'ligne'             => ($i),
                            'libelle'           => "Proprietaires",
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                        break;
                    }

                    $get_nom              ?:$errors = "Veuillez definir le nom";
                    $get_prenom              ?:$errors = "Veuillez definir le prenom";
                    $get_adresse              ?:$errors = "Veuillez definir l'adresse";
                    $get_entite              ?:$errors = "Veuillez definir l'entite";
                    // $get_telephone1              ?:$errors = "Veuillez definir le telephone1";

                    $newlocataire                     = Locataire::whereRaw('TRIM(lower(nom)) = TRIM(lower(?)) AND TRIM(lower(prenom)) = TRIM(lower(?))', ["$get_nom" , "$get_prenom"])->first();
                   
                  

                    if(!$errors)
                    {
                        if(!isset($newlocataire))
                        {
                            $newlocataire        = new  Locataire();
                        }

                        if ($get_entite) {
                            $get_entite = Entite::where("code" ,$get_entite)->first();
                        }

                        $newlocataire->nom = $get_nom;
                        $newlocataire->prenom = $get_prenom;
                        // $newlocataire->adresse = $get_adresse;
                        $newlocataire->telephoneportable1 = $get_telephone1;
                        $newlocataire->telephoneportable2 = $get_telephone2;
                        $newlocataire->email = $get_email;
                        $newlocataire->profession = $get_profession;
                        $newlocataire->cni = $get_cni;
                        $newlocataire->typelocataire_id = "1";
                        $newlocataire->etatlocataire = '0';
                        $newlocataire->entite_id =  $get_entite->id;
                        $is_save = $newlocataire->save();

                        $lastnewlocataire = $newlocataire;
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

            Outil::atEndUploadData($this->pathFile, $this->generateLink, $report, $this->user, $totalToUpload, $totalUpload, " des proprietaires", $lastnewlocataire);
            // dd($report);

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
