<?php

namespace App\Http\Controllers;


use App\User;
use App\Outil;
use App\Immeuble;
use App\Prestataire;
use App\Proprietaire;
use App\DomaineDetude;
use App\Typeappartement;
use App\UserDepartement;
use App\Pieceappartement;
use Illuminate\Http\Request;
use App\Categorieprestataire;
use App\Jobs\ImportUserFileJob;
use App\Jobs\ImportEntiteFileJob;
use App\Jobs\ImportParametrageFileJob;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class CategorieprestataireController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "categorieprestataires";
    protected $model = Categorieprestataire::class;
    protected $job = ImportParametrageFileJob::class;

    public function save(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();

                $item = new Categorieprestataire();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Categorieprestataire::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "La categorie que vous tentez de modifier n'existe pas ",
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
                    $errors = "Veuillez renseigner la designation de la catégorie";
                }

                //   dd($request);
                $item->designation = $request->designation;

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
