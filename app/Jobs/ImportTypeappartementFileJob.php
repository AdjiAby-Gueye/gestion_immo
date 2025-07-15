<?php

namespace App\Jobs;

use App\{Activite,
    ActiviteEntite,
    Agence,
    Caisse,
    Departement,
    Depot,
    Entite,
    Outil,
    Service,
    Societefacturation,
    Typeappartement,
    Typeappartement_piece,
    TypeDepot,
    Typepiece,
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

class ImportTypeappartementFileJob implements ShouldQueue
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
                    $is_save2 = 0;
                    $row = $data[$i];

                    try
                    {


                        $get_designation             = isset($row[0]) ? trim($row[0]) : null;
                        $get_piece             = isset($row[1]) ? trim($row[1]) : null;
                        // $get_nombrechambre            = isset($row[1]) ? trim($row[1]) : null;
                        // $get_nombrechambresalledebain             = isset($row[2]) ? trim($row[2]) : null;
                        // $get_nombresallon           = isset($row[3]) ? trim($row[3]) : null;
                        // $get_nombredoucheexterne             = isset($row[4]) ? trim($row[4]) : null;
                        // $get_nombreespacefamilliale             = isset($row[5]) ? trim($row[5]) : null;
                        // $get_nombrecouloire             = isset($row[6]) ? trim($row[6]) : null;
                        // $get_nombrecuisine             = isset($row[7]) ? trim($row[7]) : null;
                        // $get_nombremezzanine             = isset($row[8]) ? trim($row[8]) : null;


                    }
                    catch (\Exception $e)
                    {
                        $errors = "Vérifier le format du fichier";
                        array_push($report, [
                            'ligne'             => ($i),
                            'libelle'           => "Type appartement",
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                        break;
                    }
                   // dd($report) ;
                    $get_piece                  ?:$errors = "Veuillez definir la piece";
                    $newpiece                            = Typepiece::whereRaw('TRIM(lower(designation)) = TRIM(lower(?))',["$get_piece"])->first();

                    $get_designation            ?:$errors = "Veuillez definir la désignation";
                    $newtypeappartement                    = Typeappartement::whereRaw('TRIM(lower(designation)) = TRIM(lower(?))',["$get_designation"])->first();


                   // dd($errors);
                    if(!$errors)
                    {
                        if(!isset($newtypeappartement))
                        {
                            $newtypeappartement    = new Typeappartement();
                        }

                        $newtypeappartement->designation = $get_designation;


                        // $newtypeappartement->nombrechambre = $get_nombrechambre;
                        // $newtypeappartement->nombrechambresalledebain = $get_nombrechambresalledebain;
                        // $newtypeappartement->nombresallon = $get_nombresallon;
                        // $newtypeappartement->nombredoucheexterne = $get_nombredoucheexterne;
                        // $newtypeappartement->nombreespacefamilliale = $get_nombreespacefamilliale;
                        // $newtypeappartement->nombrecouloire = $get_nombrecouloire;
                        // $newtypeappartement->nombrecuisine = $get_nombrecuisine;
                        // $newtypeappartement->nombremezzanine = $get_nombremezzanine;

                        $is_save = $newtypeappartement->save();
                        if(!isset($newpiece))
                        {
                            $newpiece    = new Typepiece();
                            $newpiece->designation = $get_piece;
                            $newpiece->iscommun = 1;
                            $newpiece->save();
                        }

                        $newtypeappartement_typepiece = Typeappartement_piece::where([['typeappartement_id' ,$newtypeappartement->id ] , ['typepiece_id' , $newpiece->id]])->first();
                        if(!isset($newtypeappartement_typepiece))
                        {
                            $newtypeappartement_typepiece = new Typeappartement_piece() ;
                        }

                        $newtypeappartement_typepiece->typepiece_id = $newpiece->id;
                        $newtypeappartement_typepiece->typeappartement_id = $newtypeappartement->id;
                        $newtypeappartement_typepiece->designation = $newtypeappartement->designation;

                        $is_save = $newtypeappartement_typepiece->save() ;

                        $lastItem = $newtypeappartement;
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
                  //  dd($report) ;
                }
            });
          //  dd($lastItem) ;
            Outil::atEndUploadData($this->pathFile, $this->generateLink, $report, $this->user, $totalToUpload, $totalUpload, " des types appartement", $lastItem);
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
