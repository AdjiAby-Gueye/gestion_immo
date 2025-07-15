<?php

namespace App\Jobs;

use App\Ilot;
use App\User;
use App\Outil;
use App\Entite;
use App\Immeuble;
use App\Appartement;
use App\Composition;
use App\Periodicite;
use App\Proprietaire;
use App\Etatappartement;
use App\Typeappartement;
use App\Niveauappartement;
use Illuminate\Bus\Queueable;
use App\Typeappartement_piece;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Frequencepaiementappartement;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportVillaFileJob implements ShouldQueue
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
                        $get_ilot     = isset($row[0]) ? trim($row[0]) : null;
                        $get_lot    = isset($row[1]) ? trim(strtolower($row[1])) : null;
                        $get_type     = isset($row[2]) ? trim($row[2]) : null;
                        $get_prixvilla     = isset($row[3]) ? trim($row[3]) : null;;
                        $get_acompte     = isset($row[4]) ? trim($row[4]) : null;
                        $get_periodicite    = isset($row[5]) ? trim($row[5]) : null;
                        $get_maturite    = isset($row[6]) ? trim($row[6]) : null;

                    }
                    catch (\Exception $e)
                    {
                        $errors = "Vérifier le format du fichier";
                        array_push($report, [
                            'ligne'             => ($i),
                            'libelle'           => "Appartements",
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                        break;
                    }
                  

                    $get_ilot =  Ilot::where('numero', $get_ilot)->first();
                    $get_ilot              ?:$errors = "Veuillez definir le numéro de l'ilot";
                  
                    $get_lot             ?:$errors = "Veuillez definir le lot";

                    $get_type = Typeappartement::whereRaw('TRIM(lower(designation)) = TRIM(lower(?))', ["$get_type"])->first();
                    $get_type             ?:$errors = "Veuillez definir le type d'appartement";

                    $get_prixvilla              ?:$errors = "Veuillez definir le prix de la villa ";

                    $get_acompte             ?:$errors = "Veuillez definir l'acompte ";

                    $get_maturite            ?:$errors = "Veuillez definir la maturité ";
                   
                    $get_periodicite                    = Periodicite::whereRaw('TRIM(lower(designation)) = TRIM(lower(?))', ["$get_periodicite"])->first();
                    $get_periodicite              ?:$errors = "Veuillez definir le proprietaire";
                    
                    $idIlot = isset($get_ilot) && isset($get_ilot->id) ? $get_ilot->id : null;
                    $idLot = isset($get_lot) ? $get_lot : null;
                    $entite = Entite::where("code","RID")->first();
                    $newappartement                     = Appartement::where([["ilot_id" , $idIlot] , ["lot" , $idLot]])->first();
                //    $newappartement                     = Appartement::whereRaw('TRIM(lower(nom)) = TRIM(lower(?))', ["$get_nom"])->first();

                    // dd($errors);
                    if(!$errors)
                    {
                        if(!isset($newappartement))
                        {
                            $newappartement        = new  Appartement();
                        
                        }

                        $newappartement->lot = $get_lot;
                        $newappartement->ilot_id = $get_ilot->id;
                        $newappartement->prixvilla = $get_prixvilla;
                        $newappartement->acomptevilla = $get_acompte;
                        $newappartement->maturite = $get_maturite;
                        $newappartement->periodicite_id = $get_periodicite->id;
        
                        $newappartement->typeappartement_id = $get_type->id;
                        $newappartement->entite_id    = ($entite != null) ? $entite->id : null;

                        $is_save = $newappartement->save();

                        $tabTypepieceAppartement = Typeappartement_piece::where('typeappartement_id' , $newappartement->typeappartement_id)->get();
                        if(count($tabTypepieceAppartement) > 0){
                            foreach ($tabTypepieceAppartement as $value) {
                                $newCompositionAppartemnt = new Composition();
                                $newCompositionAppartemnt->typeappartement_piece_id = $value->id;
                                $newCompositionAppartemnt->appartement_id = $newappartement->id;
                                $newCompositionAppartemnt->save();
                            }
                        }

                        $lastnewappartement = $newappartement;
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
                            'libelle'           => $get_nom,
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                    }
                }
            });

            Outil::atEndUploadData($this->pathFile, $this->generateLink, $report, $this->user, $totalToUpload, $totalUpload, " des appartements villas", $lastnewappartement);


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
