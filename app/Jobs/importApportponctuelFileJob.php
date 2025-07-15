<?php

namespace App\Jobs;

use App\Appartement;
use App\Apportponctuel;
use App\Contrat;
use App\Outil;
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

class importApportponctuelFileJob implements ShouldQueue
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
            $lastnewapportponctuel = null;
            DB::transaction(function () use (&$totalUpload, &$data, &$report, &$lastnewprestataire)
            {
                for ($i=1;$i < count($data);$i++)
                {
                    $errors = null;
                    $is_save = 0;
                    $row = $data[$i];

                    try
                    {
                        $get_date     = isset($row[0]) ? trim($row[0]) : null;
                        $get_montant     =isset($row[1]) ? trim($row[1]) : null;
                        $get_lot     = isset($row[2]) ? trim($row[2]) : null;
                        $get_typeapportponctuel     = isset($row[3]) ? trim($row[3]) : null;
                        $get_observations     = isset($row[4]) ? trim($row[4]) : null;

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

                    $get_date              ?:$errors = "Veuillez definir la date";
                    $get_montant              ?:$errors = "Veuillez definir le montant";
                    $get_lot            ?:$errors = "Veuillez definir l'ID du contrat";
                    $get_typeapportponctuel              ?:$errors = "Veuillez definir l'ID du type d'apport ponctuel";
                    $get_observations            ?:$errors = "Veuillez definir l'observations";

                    //$appartement = Appartement::where('lot', $get_lot)->first();
                    //$contrat = Contrat::where('descriptif', $get_lot)->first();

                    $contrat = Contrat::whereRaw('RIGHT(descriptif, 3) = ?', [$get_lot])->first();
                    $typeapportponctuel= Typeapportponctuel::where('designation', $get_typeapportponctuel)->first();

                    if(!$contrat){
                        $errors = "Lot non trouvé !";
                        }else{
                            $contrat_id = $contrat->id;
                    }
                    if(!$typeapportponctuel){
                        $errors = "Type d'apport ponctuel non trouvé !";
                        }else{
                            $typeapportponctuel_id = $typeapportponctuel->id;
                            }

                    if(!$errors)
                    {

                        $newapportponctuel       = new  Apportponctuel();

                        $newapportponctuel->date = $get_date;
                        $newapportponctuel->montant = $get_montant;
                        $newapportponctuel->contrat_id = $contrat_id;
                        $newapportponctuel->typeapportponctuel_id = $typeapportponctuel_id;
                        $newapportponctuel->observations = $get_observations;

                        $is_save = $newapportponctuel->save();

                        $lastnewapportponctuel = $newapportponctuel;
                    }
                    if($is_save)
                    {
                        $totalUpload ++;
                    }


                }
            });

            Outil::atEndUploadData($this->pathFile, $this->generateLink, $report, $this->user, $totalToUpload, $totalUpload, " des apports ponctuel", $lastnewapportponctuel);


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
