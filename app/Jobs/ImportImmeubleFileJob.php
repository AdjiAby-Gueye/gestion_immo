<?php

namespace App\Jobs;

use App\User;
use App\Outil;
use App\Immeuble;
use App\Typepiece;
use App\Pieceimmeuble;
use App\Structureimmeuble;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;



class ImportImmeubleFileJob implements ShouldQueue
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
        try {
            $this->user = User::find($this->userId);

            $filename = $this->file;
            $data = Excel::toArray(null, $filename);
            $data = $data[0]; // 0 => à la feuille 1

            $report = array();

            $totalToUpload = count($data) - 1;
            $totalUpload = 0;
            $lastItem = null;

            DB::transaction(function () use (&$totalUpload, &$data, &$report, &$lastItem) {
                for ($i = 1; $i < count($data); $i++) {
                    $errors = null;
                    $is_save = 0;
                    $row = $data[$i];
                    $piecesimmeubletab = [];

                    // dd($row);
                    try {

                        $get_designation             = isset($row[0]) ? trim($row[0]) : null;
                        $get_typeimmeuble              = isset($row[1]) ? trim($row[1]) : null;
                        $get_adresse                    = isset($row[2]) ? trim($row[2]) : null;
                        $get_piscine               = isset($row[3]) ? trim($row[3]) : null;
                        $get_sallefete                 = isset($row[4]) ? trim($row[4]) : null;
                        $get_sallegym                = isset($row[5]) ? trim($row[5]) : null;
                        $get_jardin                = isset($row[6]) ? trim($row[6]) : null;
                        $get_chambreparental                = isset($row[7]) ? trim($row[7]) : null;
                        $get_ascenseur                = isset($row[8]) ? trim($row[8]) : null;
                        $get_parkinginterne                = isset($row[9]) ? trim($row[9]) : null;
                        $get_suiteparentale                = isset($row[10]) ? trim($row[10]) : null;
                        $get_escalier                = isset($row[11]) ? trim($row[11]) : null;
                        $get_groupeelectro                = isset($row[12]) ? trim($row[12]) : null;
                        $get_parkingextene                = isset($row[13]) ? trim($row[13]) : null;
                        $get_cuisineamericaine                = isset($row[14]) ? trim($row[14]) : null;


                        array_push($piecesimmeubletab, ['name' => 'piscine', 'value' => $get_piscine]);
                        array_push($piecesimmeubletab, ['name' => 'ascenceur', 'value' => $get_ascenseur]);
                        array_push($piecesimmeubletab, ['name' => 'groupe électrogène', 'value' => $get_groupeelectro]);
                        array_push($piecesimmeubletab, ['name' => 'salle de fete', 'value' => $get_sallefete]);
                        array_push($piecesimmeubletab, ['name' => 'salle de gym', 'value' => $get_sallegym]);
                        array_push($piecesimmeubletab, ['name' => 'jardin', 'value' => $get_jardin]);
                        array_push($piecesimmeubletab, ['name' => 'chambre parental', 'value' => $get_chambreparental]);
                        array_push($piecesimmeubletab, ['name' => 'parking interne', 'value' => $get_parkinginterne]);
                        array_push($piecesimmeubletab, ['name' => 'suite parentale', 'value' => $get_suiteparentale]);
                        array_push($piecesimmeubletab, ['name' => 'escalier', 'value' => $get_escalier]);
                        array_push($piecesimmeubletab, ['name' => 'parking externe', 'value' => $get_parkingextene]);
                        array_push($piecesimmeubletab, ['name' => 'cuisine americaine', 'value' => $get_cuisineamericaine]);
                    } catch (\Exception $e) {
                        $errors = "Vérifier le format du fichier";
                        array_push($report, [
                            'ligne'             => ($i),
                            'libelle'           => "Immeuble",
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                        break;
                    }

                    // dd($get_typeimmeuble);
                    $get_designation            ?: $errors = "Veuillez definir la désignation";
                    $get_adresse            ?: $errors = "Veuillez definir l'adresse";
                    $get_typeimmeuble             = Structureimmeuble::whereRaw('TRIM(lower(designation)) = TRIM(lower(?))', ["$get_typeimmeuble"])->first();
                    $get_typeimmeuble            ?: $errors = "Veuillez renseigner le type";

                    $newimmeuble                     = Immeuble::whereRaw('TRIM(lower(nom)) = TRIM(lower(?))', ["$get_designation"])->first();

                    // dd($errors);

                    if (!$errors) {
                        if (!isset($newimmeuble)) {
                            $newimmeuble        = new Immeuble();
                        }

                        $newimmeuble->nom                            = $get_designation;
                        $newimmeuble->adresse                            = $get_adresse;
                        $newimmeuble->structureimmeuble_id                            = $get_typeimmeuble->id;
                        $newimmeuble->nombreascenseur = $get_ascenseur;
                        $newimmeuble->nombregroupeelectrogene = $get_groupeelectro;
                        $newimmeuble->nombrepiscine = $get_piscine;
                        $newimmeuble->est_activer = 1;
                        $newimmeuble->iscopropriete = 0;
                        $newimmeuble->save();
                        $is_save = $newimmeuble;
                        foreach ($piecesimmeubletab as $item) {
                            $objet = null;
                            if (isset($item['value']) && $item['value'] > 0) {
                                $name = $item['name'];
                                $objet = Typepiece::whereRaw('TRIM(lower(designation)) = TRIM(lower(?))', ["$name"])->first();
                                if ($objet) {
                                    // for ($i = 1; $i <= $item['value']; $i++) {
                                        $pieceimmeuble = new Pieceimmeuble();
                                        $pieceimmeuble->typepiece_id = $objet->id;
                                        $pieceimmeuble->immeuble_id = $newimmeuble->id;
                                        $pieceimmeuble->save();
                                    // }
                                }
                            }
                        }

                        //  dd($errors);

                        $lastItem = $is_save;
                    }
                    if ($is_save) {
                        $totalUpload++;
                    }

                    if (!empty($get_designation) && !$is_save) {
                        array_push($report, [
                            'ligne'             => ($i + 1),
                            'libelle'           => $get_designation,
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                    }
                }
            });

            Outil::atEndUploadData($this->pathFile, $this->generateLink, $report, $this->user, $totalToUpload, $totalUpload, " des immeubles", $lastItem);
        } catch (\Exception $e) {
            try {
                File::delete($this->pathFile);
            } catch (\Exception $eFile) {
            };
            throw new \Exception($e);
        }
    }
}
