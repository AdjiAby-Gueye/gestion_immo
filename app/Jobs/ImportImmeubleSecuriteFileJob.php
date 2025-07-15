<?php

namespace App\Jobs;

use App\User;
use App\Outil;
use App\Horaire;
use App\Immeuble;
use App\Securite;
use App\Prestataire;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class ImportImmeubleSecuriteFileJob implements ShouldQueue
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
            $lastItem = null;
            DB::transaction(function () use (&$totalUpload, &$data, &$report, &$lastItem)
            {
                for ($i=1;$i < count($data);$i++)
                {
                    $errors = null;
                    $is_save = 0;
                    $row = $data[$i];

                    try
                    {

                        $get_immeuble               = isset($row[0]) ? trim($row[0]) : null;
                        $get_designation             = isset($row[1]) ? trim($row[1]) : null;
                        $get_horaire                    = isset($row[2]) ? trim($row[2]) : null;
                          // $get_etat                   = isset($row[3]) ? trim($row[3]) : null;
                        $get_adresse               = isset($row[4]) ? trim($row[4]) : null;
                        $get_telephone1                 = isset($row[5]) ? trim($row[5]) : null;
                        $get_telephone2                = isset($row[6]) ? trim($row[6]) : null;

                    }
                    catch (\Exception $e)
                    {
                        $errors = "Vérifier le format du fichier";
                        array_push($report, [
                            'ligne'             => ($i),
                            'libelle'           => "Utilisateur",
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                        break;
                    }

                    $get_designation            ?:$errors = "Veuillez definir la désignation";
                    $get_adresse                ?:$errors = "Veuillez definir l'adresse";
                    $get_horaire                ?:$errors = "Veuillez definir horaire";

                    $immeuble                        = Immeuble::whereRaw('TRIM(lower(nom)) = TRIM(lower(?))',["$get_immeuble"])->first();
                    $immeuble                        ?:  $errors = "Veuillez definir l'immeuble";

                    $horaire                         = Horaire::whereRaw('TRIM(lower(designation)) = TRIM(lower(?))',["$get_horaire"])->first();
                    $horaire                         ?:  $errors = "Veuillez definir horaire";

                    $prestataire                     = Prestataire::whereRaw('TRIM(lower(nom)) = TRIM(lower(?))',["$get_designation"])->first();
                    $prestataire                         ?:  $errors = "Veuillez definir le prestatire";

                   // dd($errors);
                    if(!$errors)
                    {
                        // if(!isset($newsecurite))
                        // {
                        //     $newsecurite        = new Securite();
                        // }

                        $newsecurite        = new Securite();

                        $newsecurite->prestataire_id = $prestataire->id;
                        $newsecurite->horaire_id =  $horaire ? $horaire->id : null;
                        $newsecurite->immeuble_id =  $immeuble ? $immeuble->id : null;
                        $newsecurite->etat = 1;

                        // $newsecurite->adresse =  $get_adresse;
                        // $newsecurite->telephone1 =  $get_telephone1;
                        // $newsecurite->telephone2 =  $get_telephone2;
                        // $newsecurite->est_activer = 1;

                        $is_save = $newsecurite->save();

                        $lastItem = $newsecurite;
                    }
                    if($is_save)
                    {
                        $totalUpload ++;
                    }

                    if (!empty($get_designation) && !$is_save)
                    {
                        array_push($report, [
                            'ligne'             => ($i+1),
                            'libelle'           => $get_designation,
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                    }
                }
            });
            Outil::atEndUploadData($this->pathFile, $this->generateLink, $report, $this->user, $totalToUpload, $totalUpload, " des securites immeubles", $lastItem);
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
