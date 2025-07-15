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

class ImportParametrageFileJob implements ShouldQueue
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
                    $nbretage = null;
                    $typeequipement = null;

                    try
                    {
                        $get_designation     = isset($row[0]) ? trim($row[0]) : null;
                        if($this->generateLink == "equipementpiece" || $this->generateLink == "typepiece")
                        {
                            $get_typeequipement     = isset($row[1]) ? trim($row[1]) : null;
                        }


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
                    $get_designation              ?:$errors = "Veuillez definir la designation";

                    $classe = ucfirst(strtolower($this->generateLink));
                    $obj = "App" . "\\" . $classe;
                    $instance = app($obj);

                    if($this->generateLink == "structureimmeuble")
                    {
                        $get_designation              ?:$errors = "Veuillez definir le nombre d'etages";
                        $nbretage = $get_designation;
                        $get_designation = "R+".$get_designation;
                    }else if($this->generateLink == "equipementpiece" || $this->generateLink == "typepiece")
                    {
                        $get_typeequipement              ?:$errors = "Veuillez definir le type";
                    }

                    $newobjet                               = $instance::whereRaw('TRIM(lower(designation)) = TRIM(lower(?))', ["$get_designation"])->first();

                    if(!$errors)
                    {
                        if(!isset($newobjet))
                        {
                            $newobjet        = new $obj;

                        }

                        $newobjet->designation = $get_designation;

                        $nbretage ?  $newobjet->etages = $nbretage : "";
                        if($this->generateLink == "equipementpiece")
                        {
                            $intger = $get_typeequipement == "générale" ? 1 : 0;
                            $newobjet->generale = $intger;
                        }else if($this->generateLink == "typepiece")
                        {
                            $newobjet->iscommun = $get_typeequipement == "commune" ? 1 : 0;
                        }
                     //   $newobjet->description = isset($get_description) ? $get_description : null;

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
