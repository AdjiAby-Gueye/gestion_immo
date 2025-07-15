<?php

namespace App\Jobs;

use App\User;
use App\Outil;
use App\Immeuble;
use App\Appartement;
use App\Equipementpiece;
use App\Detailcomposition;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportEquipementsAppartementFileJob implements ShouldQueue
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
            $App = null;
            DB::transaction(function () use (&$totalUpload, &$data, &$report, &$lastItem)
            {

                for ($i=1;$i < count($data);$i++)
                {
                    $errors = null;
                    $is_save = 0;
                    $row = $data[$i];

                    try
                    {
                        $get_appartement     = isset($row[0]) ? trim($row[0]) : null;
                        $get_immeuble     = isset($row[1]) ? trim($row[1]) : null;
                        $get_nomequipement     = isset($row[2]) ? trim($row[2]) : null;

                    }
                    catch (\Exception $e)
                    {
                        $errors = "Vérifier le format du fichier";
                        array_push($report, [
                            'ligne'             => ($i),
                            'libelle'           => "Equipements appartement",
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                        break;
                    }


                    $immeuble                        = Immeuble::whereRaw('TRIM(lower(nom)) = TRIM(lower(?))',["$get_immeuble"])->first();
                    $immeuble                        ?:  $errors = "Veuillez definir l'immeuble";
                    $idImmeuble = null;
                    isset($immeuble) ? $idImmeuble = $immeuble->id : $idImmeuble;
                    // dd($idImmeuble);
                    $appartement                     = Appartement::whereRaw('TRIM(lower(nom)) = TRIM(lower(?))', ["$get_appartement"])->where('immeuble_id' , $idImmeuble)->first();
                    $appartement                     ?:   $errors = "Veuillez definir l'appartement";
                    $idAppart = null;
                    isset($appartement) ? $idAppart = $appartement->id : $idAppart;

                    $equipement                     = Equipementpiece::whereRaw('TRIM(lower(designation)) = TRIM(lower(?)) ', ["$get_nomequipement"])->first();
                    $equipement                     ?:   $errors = "Veuillez definir l'equipement";
                    // dd($equipement , true);
                    $idEquipment = null;
                    isset($equipement) ? $idEquipment = $equipement->id : $idEquipment;
                    $newDetailComposition       = Detailcomposition::where([['appartement_id' , $idAppart],['equipement_id' , $idEquipment]])->first();

                    if(!$errors)
                    {
                        if(!isset($newDetailComposition))
                        {
                            $newDetailComposition        = new  Detailcomposition();
                        }
                    //    $newDetailComposition        = new  Detailcomposition();

                        $newDetailComposition->appartement_id = $appartement->id;
                        $newDetailComposition->equipement_id = $equipement->id;
                        $newDetailComposition->est_activer = 1;

                        $is_save = $newDetailComposition->save();
                        $App = $appartement;
                        $lastItem = $newDetailComposition;
                    }
                    if($is_save)
                    {
                        $totalUpload ++;
                    }

                    if (!empty($getdesignation) && !$is_save)
                    {
                        array_push($report, [
                            'ligne'             => ($i+1),
                            'libelle'           => $get_nomequipement,
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                    }
                }
            });

            Outil::atEndUploadData($this->pathFile, $this->generateLink , $report, $this->user, $totalToUpload, $totalUpload, " des equipements appartements", $App);


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
