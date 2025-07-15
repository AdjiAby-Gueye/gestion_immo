<?php

namespace App\Http\Controllers;

use App\ActiviteEntite;
use App\Commande;
use App\Commandeproduit;
use App\Departement;
use App\Employe;
use App\Entite;
use App\Jobs\ImportEntiteFileJob;
use App\Jobs\ImportUserFileJob;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Outil;
use App\User;
use Spatie\Permission\Models\Role;


class UserController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "users";
    protected $model     = User::class;
    protected $job       = ImportUserFileJob::class;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $item_entite = null;
                $user_connected = Auth::user();
                $item = new User();
                // dd($request->files);
                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = User::find($request->id);
                        if (!$item) {

                            $retour = array(
                                "data" => null,
                                "error" => "L'utilisateur que vous tentez de modifier n'existe pas ",
                            );
                            return $retour;
                        }
                    } else {
                        $retour = array(
                            "data" => null,
                            "error" => "L'id doit être un nombre entier",
                        );
                        return $retour;
                    }
                }



            //    dd(env("APP_URL"));

                if (empty($request->name)) {
                    $errors = "Veuillez renseigner le nom";
                } else if (!Outil::isUnique(['name'], [$request->name], $request->id, User::class)) {
                    $errors = "L'utilisateur existe deja !";
                } else if (empty($request->role)) {
                    $errors = "Veuillez définir le profil";
                } else if (empty($request->email)) {
                    $errors = "Veuillez definir l'email";
                }else  if (!Outil::isUnique(['email'], [$request->email], $request->id, User::class)) {
                    $errors = "Cet email existe deja !";
                } else {
                    if (empty($request->id)) {
                        if (empty($request->password)) {
                            $errors = "Veuillez definir le mot de passe";
                        } else if ($request->password != $request->confirmpassword) {
                            $errors = "Les 2 mots de passe ne sont pas identiques";
                        }
                    } else {
                        if ((empty($request->password) && isset($request->confirmpassword)) || (isset($request->password) && empty($request->confirmpassword))) {
                            $errors = "Veuillez definir le mot de passe et le répéter pour le changer";
                        } else if (isset($request->password) && isset($request->confirmpassword) && $request->password != $request->confirmpassword) {
                            $errors = "Les 2 mots de passe ne sont pas identiques";
                        }
                    }
                }
                if(empty($request->entite))
				{
					//$errors = "Veuillez definir l'entité";
                } else if(isset($request->entite)){
                    $item_entite = Entite::where('id', $request->entite)->first();
                }



                if (!isset($errors)) {
                    //SANS erreurs
                    //dd($request->file('image'));

                    $default_image = 'assets/images/default.png';
                    if (!isset($item->image)) {
                        $item->image = $default_image;
                    }
                    if (!empty($request->file('image'))) {
                        // POUR UPLOAD DE L'IMAGE
                        $dateHeure = date('Y_m_d_H_i_s');
                        $fichier = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : "";
                        if (!empty($fichier)) {
                            $fichier_tmp = $_FILES['image']['tmp_name'];
                            $ext = explode('.', $fichier);
                            $rename = config('view.uploads')[$this->queryName] . "/user_" . $dateHeure . "." . end($ext);
                            move_uploaded_file($fichier_tmp, $rename);
                            $item->image = $rename;
                        }
                    } else if ($request->get('image_erase')) // Permet de supprimer l'image
                    {
                        $item->image = $default_image;
                    }

                    if (!empty($request->file('image_signature'))) {
                        // POUR UPLOAD DE L'IMAGE
                        $dateHeure = date('Y_m_d_H_i_s');
                        $fichier = isset($_FILES['image_signature']['name']) ? $_FILES['image_signature']['name'] : "";
                        if (!empty($fichier)) {
                            $fichier_tmp = $_FILES['image_signature']['tmp_name'];
                            $ext = explode('.', $fichier);
                            $rename = config('view.uploads')[$this->queryName] . "/user_signature_" . $dateHeure . "." . end($ext);
                            move_uploaded_file($fichier_tmp, $rename);
                            $item->uploadsignature = $rename;
                        }
                    } else if ($request->get('image_erase2')) // Permet de supprimer l'image
                    {
                        $item->uploadsignature =null;
                    }
                   // $item_role = explode(',', $request->role);
                   // $item_role = explode(',', $request->role);

                    if(isset($request->role)){
                        $item_role = Role::where('id', $request->role)->first();
                    }


                 //   $errors = $item_role->name;

                    $item->name = $request->name;
                    $item->email = $request->email;
                    $item->active = isset($request->active) ? $request->active : 1;
                    if (isset($request->password)) {
                       // $item->password = Hash::make($request->password);
                        Outil::saveUserPassword($item,$request->password);
                    }
                    // $item->image= isset($request->image) ? $request->image : null;




                    if(isset($request->id) && isset($user_connected)){
                        if($request->id == $user_connected->id){

                            //abdoulayeciss@kv1technology.com
                            if(isset($user_connected->roles[0]) && isset($user_connected->roles[0]['id']) ){
                                $test_role = Role::where('id', $request->role)->first();
                                if($test_role->id !== $user_connected->roles[0]['id']){
                                    $errors = "Impossible de modifier son profil en connexion." . $request->entites;
                                }
                            }
                        }
                    }
                   

                    if (!isset($errors)) {
                        $item->entite_id    = $item_entite->id ?? null;
                        $item->save();
                        $item->syncRoles($item_role);
                        $item->profil = $item_role->name ;



                        $item =  Outil::saveMatriculeUser($item);
                        if(!isset($item) || !isset($item->matricule) || $item->matricule ==''){
                            $errors = 'Erreur sur la generation du matricule'. $item->matricule;
                        }

                      //  }
                        if(!$errors){
                            return Outil::redirectgraphql($this->queryName, "id:{$item->id}", Outil::$queries[$this->queryName]);
                        }

                    }


                }
                throw new \Exception($errors);
            });
        } catch (\Exception $e) {
            return Outil::getResponseError($e);
        }
    }
}
