<?php

namespace App\Jobs;

use App\{Activite,
    ActiviteEntite,
    Agence,
    Caisse,
    Departement,
    Depot,
    Entite,
    Ilot,
    Outil,
    Service,
    Societefacturation,
    Typecontrat,
    TypeDepot,
    Typeservice,
    UserCaisse,
    UserDepartement,
    UserEntite};

use App\PointVente;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class ImportIlotFileJob implements ShouldQueue
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
      //      dd($filename) ;
            $data = Excel::toArray(null, $filename);
            $data = $data[0]; // 0 => à la feuille 1

            $report = array();

            $totalToUpload = count($data) - 1;
            $totalUpload   = 0;
            $lastItem      = null;
            DB::transaction(function () use (&$totalUpload, &$data, &$report, &$lastItem)
            {
                for ($i=1;$i < count($data);$i++)
                {
                    $errors = null;
                    $is_save = 0;
                    $row = $data[$i];

                    try
                    {


                        $get_numero                  = isset($row[0]) ? trim($row[0]) : null;
                        $get_adresse             = isset($row[1]) ? trim($row[1]) : null;
                        


                    }
                    catch (\Exception $e)
                    {
                        $errors = "Vérifier le format du fichier";
                        array_push($report, [
                            'ligne'             => ($i),
                            'libelle'           => "Type contratTypecontrat",
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                        break;
                    }
                   // dd($report) ;
                    $get_numero            ?:$errors = "Veuillez definir le numéro";
                    $get_adresse           ?:$errors = "Veuillez definir l'adresse";


                    $newilot                    = Ilot::where('numero',$get_numero)->first();

                   // dd($errors);
                    if(!$errors)
                    {
                        if(!isset($newilot))
                        {
                            $newilot    = new Ilot();
                        }

                        $newilot->numero = $get_numero;
                        $newilot->adresse = $get_adresse;
                        

                        $is_save = $newilot->save();

                        $lastItem = $newilot;
                    }
                    if($is_save)
                    {
                        $totalUpload ++;
                    }

                    if (!empty($get_designation) && !$is_save)
                    {
                        array_push($report, [
                            'ligne'             => ($i+1),
                            'libelle'           => $get_numero,
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                    }
                  //  dd($report) ;
                }
            });
          //  dd($lastItem) ;
            Outil::atEndUploadData($this->pathFile, $this->generateLink, $report, $this->user, $totalToUpload, $totalUpload, " des ilots", $lastItem);
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
