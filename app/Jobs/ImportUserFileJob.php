<?php

namespace App\Jobs;

use App\{Activite,
    ActiviteEntite,
    Caisse,
    Departement,
    Depot,
    Entite,
    Outil,
    Societefacturation,
    TypeDepot,
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

class ImportUserFileJob implements ShouldQueue
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

                        $get_matricule               = isset($row[0]) ? trim($row[0]) : null;
                        $get_designation             = isset($row[1]) ? trim($row[1]) : null;
                        $get_role                    = isset($row[2]) ? trim($row[2]) : null;
                        $get_email                   = isset($row[4]) ? trim($row[4]) : null;
                        $get_telephone               = isset($row[5]) ? trim($row[5]) : null;
                        $get_adresse                 = isset($row[6]) ? trim($row[6]) : null;
                        $get_password                = isset($row[9]) ? trim($row[9]) : null;

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

                    $role                        = Role::whereRaw('TRIM(lower(name)) = TRIM(lower(?))',["$get_role"])->first();
                    $role                        ?:  $errors = "Un utilisateur doit avoir un role";

                    $newuser                     = User::whereRaw('TRIM(lower(name)) = TRIM(lower(?))',["$get_designation"])->first();

                    if (!Outil::isUnique(['email'], [$get_email], null, User::class)) {
                        if(!isset($newuser)){
                            $errors = "Cette email existe deja !";
                        }
                    }
                   // dd($errors);
                    if(!$errors)
                    {
                        if(!isset($newuser))
                        {
                            $newuser        = new User();
                        }

                        $newuser->matricule                          = $get_matricule;
                        $newuser->name                               = $get_designation;
                        $newuser->email                              = $get_email;
                        $newuser->password                           = bcrypt($get_password);
                        $newuser->active                             = 1;
                        $newuser->image                              = '';
                        $newuser->telephone                          = $get_telephone;
                        $newuser->adresse                            = $get_adresse;

                        $is_save = $newuser->save();
                        $newuser->assignRole($role->id);



                        if(!isset($newuser->matricule) || $newuser->matricule ==''){
                            $newuser =  Outil::saveMatriculeUser($newuser);
                            if(!isset($newuser) || !isset($newuser->matricule) || $newuser->matricule ==''){
                                $errors = 'Erreur sur la generation du matricule';
                            }
                        }

                     //   Outil::saveUserPassword($newuser);
                      //  dd($errors);

                        $lastItem = $newuser;
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

            Outil::atEndUploadData($this->pathFile, $this->generateLink, $report, $this->user, $totalToUpload, $totalUpload, " des utilisateurs", $lastItem);
//            dd($report);
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
