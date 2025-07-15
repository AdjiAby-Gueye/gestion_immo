<?php

namespace App\Jobs;

use App\User;
use App\Outil;
use App\Horaire;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportHoraireFileJob implements ShouldQueue
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
                        $get_designation     = isset($row[0]) ? trim($row[0]) : null;
                        $get_debut     = isset($row[1]) ? $row[1] : null;
                        $get_fin     = isset($row[2]) ? trim($row[2]) : null;

                    }
                    catch (\Exception $e)
                    {
                        $errors = "Vérifier le format du fichier";
                        array_push($report, [
                            'ligne'             => ($i),
                            'libelle'           => ucfirst($this->generateLink),
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                        break;
                    }
                    dd($get_debut);
                    $get_designation              ?:$errors = "Veuillez definir la designation";
                    $get_debut              ?:$errors = "Veuillez definir l'heure de debut";
                    $get_fin              ?:$errors = "Veuillez definir l'heure de fin";

                    $newobjet                               = Horaire::whereRaw('TRIM(lower(designation)) = TRIM(lower(?))', ["$get_designation"])->first();

                    if(!$errors)
                    {
                        if(!isset($newobjet))
                        {
                            $newobjet        = new Horaire();
                        }


                        $get_debut = Carbon::createFromFormat('H:i:s',$get_debut)->format('h:i');
                        $get_fin = Carbon::createFromFormat('H:i:s',$get_debut)->format('h:i');
                        dd($get_debut);

                        $newobjet->designation = $get_designation;
                        $newobjet->debut = $get_debut;
                        $newobjet->fin = $get_fin;

                        $is_save = $newobjet->save();

                        $lastnewappartement = $newobjet;
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
                            'libelle'           => $get_designation,
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                    }
                }
            });

            Outil::atEndUploadData($this->pathFile, $this->generateLink, $report, $this->user, $totalToUpload, $totalUpload, " des ".$this->generateLink."s", $lastnewappartement);


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
