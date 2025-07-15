<?php

namespace App\Jobs;

use App\{Activite, Depot, Entite, Outil, TypeDepot};

use App\PointVente;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class ImportRoleFileJob implements ShouldQueue
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
                        $getdesignation     = trim($row[0]);
//                        $nwdesignation      = trim($row[1]);

                    }
                    catch (\Exception $e)
                    {
                        $errors = "Vérifier le format du fichier";
                        array_push($report, [
                            'ligne'             => ($i),
                            'libelle'           => "Roles",
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                        break;
                    }

                    $getdesignation              ?:$errors = "Veuillez definir la désignation";
                    $newrole = Role::with('users')->whereRaw('TRIM(lower(name)) = TRIM(lower(?))',["$getdesignation"])->first();

                    if (isset($newrole) && isset($newrole->name)) {
                        if (isset($newrole->users) && count($newrole->users) > 0) {
                            $errors = "Ce profil est déjà lié à des utilisateurs, vous ne pouvez pas le modifier";
                        }
                    }
                //    $newrole                 = Role::whereRaw('TRIM(lower(designation)) = TRIM(lower(?))',["$getdesignation"])->first();

                    if(!$errors)
                    {
                        if(!isset($newrole))
                        {
                            $newrole        = new  Role();
                        }

                        $newrole->name = $getdesignation;

                        $is_save = $newrole->save();

                        $lastItem = $newrole;
                    }
                    if($is_save)
                    {
                        $totalUpload ++;
                    }

                    if (!empty($getdesignation) && !$is_save)
                    {
                        array_push($report, [
                            'ligne'             => ($i+1),
                            'libelle'           => $getdesignation,
                            'erreur'            => $errors,
                            'is_save'           => $is_save,
                        ]);
                    }
                }
            });

            Outil::atEndUploadData($this->pathFile, $this->generateLink, $report, $this->user, $totalToUpload, $totalUpload, " des roles", $lastItem);


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
