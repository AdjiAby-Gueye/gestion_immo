<?php

namespace App\Http\Controllers;


use App\User;
use App\Outil;
use App\Annonce;
use App\Caution;
use App\Contrat;
use App\Facture;
use App\Immeuble;
use App\Assurance;
use App\DomaineDetude;
use App\Paiementloyer;
use App\Equipementpiece;
use App\Typeappartement;
use App\UserDepartement;
use App\Pieceappartement;
use Illuminate\Http\Request;
use App\Jobs\ImportUserFileJob;
use App\Jobs\ImportEntiteFileJob;
use App\Obligationadministrative;
use App\Jobs\ImportParametrageFileJob;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class EquipementpieceController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "equipementpieces";
    protected $model = Equipementpiece::class;
    protected $job = ImportParametrageFileJob::class;


    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();


               //dd($request) ;
                $item = new Equipementpiece();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Equipementpiece::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "L'équipement que vous tentez de modifier n'existe pas ",
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
                    $errors = "la désignation n'est pas renseigné";
                }

                if ($request->generale !== '0' && $request->generale !== '1') {
                    $errors = "Veuillez renseigner le type";
                }


                $item->designation = $request->designation;
                $item->generale = $request->generale;

                if (!isset($errors)) {

                    $item->save();

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
