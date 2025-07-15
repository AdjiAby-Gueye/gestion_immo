<?php

namespace App\Http\Controllers;


use App\Equipegestion;
use App\Equipegestion_membreequipegestion;
use App\Immeuble;
use App\Jobs\ImportEntiteFileJob;
use App\Jobs\ImportUserFileJob;
use App\Membreequipegestion;
use App\Pieceappartement;
use App\Proprietaire;
use App\Typeappartement;
use App\UserDepartement;
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
use App\DomaineDetude;
use Spatie\Permission\Models\Role;


class EquipegestionController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "equipegestions";
    protected $model = Equipegestion::class;
    protected $job = ImportUserFileJob::class;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();
              //  dd($request) ;
                $item = new Equipegestion();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Equipegestion::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "L'equipe que vous tentez de modifier n'existe pas ",
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

                if (empty($request->designation)) {
                    $errors = "Veuillez renseigner la designation de l'equipe";
                }

                //   dd($request);
                $item->designation = $request->designation;


                if (!isset($errors)) {

                    $item->save();

                    $inputs = $request->input() ;

                    $immeubles = Immeuble::All();
                    foreach ($immeubles as $immeuble)
                    {
                        //  dd($inputs["locataire{$locataire->id}"]) ;
                        if(isset($inputs["immeuble{$immeuble->id}"])){
                            $item2 = Immeuble::find($inputs["immeuble{$immeuble->id}"]);
                            $item2->equipegestion_id = $item->id;
                            $item2->save() ;
                        }
                    }

                    $membreequipegestions = Membreequipegestion::All();
                    foreach ($membreequipegestions as $membreequipegestion)
                    {
                        //  dd($inputs["locataire{$locataire->id}"]) ;
                        if(isset($inputs["membreequipegestion{$membreequipegestion->id}"])){
                            $membreequipegestionEquipegestion = new Equipegestion_membreequipegestion() ;
                            $membreequipegestionEquipegestion->equipegestion_id = $item->id;
                            $membreequipegestionEquipegestion->membreequipegestion_id = intval( $inputs["membreequipegestion{$membreequipegestion->id}"]);
                            $membreequipegestionEquipegestion->fonction_id = intval( $inputs["membreequipegestion_fonction{$membreequipegestion->id}"]);
                            $membreequipegestionEquipegestion->save() ;
                        }
                    }

                    if (!$errors) {
                        return Outil::redirectgraphql($this->queryName, "id:{$item->id}", Outil::$queries[$this->queryName]);
                    }
                }


                throw new \Exception($errors);
            });
        } catch (\Exception $e) {
            return Outil::getResponseError($e);
        }
    }
}
