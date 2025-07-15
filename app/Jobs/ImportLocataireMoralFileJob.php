<?php

namespace App\Jobs;

use App\User;
use App\Outil;
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

class ImportLocataireMoralFileJob implements ShouldQueue
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
                        $get_nomentreprise     = isset($row[0]) ? trim($row[0]) : null;
                        $get_ninea     =isset($row[1]) ? trim($row[1]) : null;
                        $get_adresse     = isset($row[2]) ? trim($row[2]) : null;
                        $get_prenompersonneacontacter     = isset($row[3]) ? trim($row[3]) : null;
                        $get_nompersonneacontacter     = isset($row[4]) ? trim($row[4]) : null;
                        $get_emailpersonneacontacter     = isset($row[5]) ? trim($row[5]) : null;
                        $get_telephone1personneacontacter     = isset($row[6]) ? trim($row[6]) : null;
                        $get_telephone2personneacontacter    = isset($row[7]) ? trim($row[7]) : null;
                    }
                    catch (\Exception $e)
                    {
                        $errors = "Vérifier le format du fichier";
                        array_push($report, [
                            'ligne'             => ($i),
                            'libelle'           => "locataire morale",
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                        break;
                    }

                    $get_nomentreprise              ?:$errors = "Veuillez definir le nom";
                    $get_prenompersonneacontacter              ?:$errors = "Veuillez definir le prenom de la personne a contacter";
                    $get_nompersonneacontacter              ?:$errors = "Veuillez definir le nom de la personne a contacter";
                    $get_emailpersonneacontacter              ?:$errors = "Veuillez definir l'email de la personne a contacter";
                    $get_telephone1personneacontacter              ?:$errors = "Veuillez definir le telephone1 de la personne a contacter";

                    $newlocataire                     = Locataire::whereRaw('TRIM(lower(nomentreprise)) = TRIM(lower(?))', ["$get_nomentreprise"])->first();


                    if(!$errors)
                    {
                        if(!isset($newlocataire))
                        {
                            $newlocataire        = new  Locataire();
                        }

                        // $newlocataire->nom = $get_nom;
                        // $newlocataire->prenom = $get_prenom;
                        // // $newlocataire->adresse = $get_adresse;
                        // $newlocataire->telephoneportable1 = $get_telephone1;
                        // $newlocataire->telephoneportable2 = $get_telephone2;
                        // $newlocataire->email = $get_email;
                        // $newlocataire->profession = $get_profession;
                        // $newlocataire->cni = $get_cni;
                        // $newlocataire->typelocataire_id = "1";
                        // $newlocataire->etatlocataire = '0';

                        $newlocataire->nomentreprise = $get_nomentreprise;
                        $newlocataire->adresseentreprise = $get_adresse;
                        $newlocataire->ninea = $get_ninea;
                        $newlocataire->personnehabiliteasigner = $get_prenompersonneacontacter." ".$get_nompersonneacontacter;
                        $newlocataire->nompersonneacontacter = $get_nompersonneacontacter;
                        $newlocataire->prenompersonneacontacter = $get_prenompersonneacontacter;
                        $newlocataire->emailpersonneacontacter = $get_emailpersonneacontacter;
                        $newlocataire->telephone1personneacontacter = $get_telephone1personneacontacter;
                        $newlocataire->telephone2personneacontacter = $get_telephone2personneacontacter;
                        $newlocataire->typelocataire_id = "2";
                        $newlocataire->etatlocataire = '0';
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
