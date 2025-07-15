<?php

namespace App\Http\Controllers;

use App\User;
use App\Outil;
use App\Entite;
use App\Appartement;
use App\DomaineDetude;
use App\Typeappartement;
use App\UserDepartement;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use App\Typeappartement_piece;
use App\Jobs\ImportUserFileJob;
use App\Jobs\ImportEntiteFileJob;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exports\TypeappartementsExport;
use App\Jobs\ImportTypeappartementFileJob;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class TypeappartementController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "typeappartements";
    protected $model = Typeappartement::class;
    protected $job = ImportTypeappartementFileJob::class;

    public function save(Request $request)
    {

        //    dd($request->all()) ;
        try {
            return DB::transaction(function () use ($request) {
                $errors = null;
                $user_connected = Auth::user();

                $item = new Typeappartement();

                if (isset($request->id)) {
                    if (is_numeric($request->id) == true) {
                        $item = Typeappartement::find($request->id);

                        if (!$item) {
                            $retour = array(
                                "data" => null,
                                "error" => "Le type d'appartement que vous tentez de modifier n'existe pas ",
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
                    $errors = "Veuillez renseigner la designation";
                } else if (!Outil::isUnique(['designation'], [$request->designation], $request->id, Typeappartement::class)) {
                    $errors = "Ce type d'appartement existe deja !";
                }

                $item->designation = $request->designation;
                $item->usage = $request->usage;



                if (!isset($errors)) {

                    $item->save();

                    $typeappartement_typepiece = json_decode($request->typeappartement_typepiece, true);

                    if (isset($request->id)) {
                        $item->typeappartement_pieces()->delete();
                    }
                    foreach ($typeappartement_typepiece as $type) {
                        $newtypeappartement_typepiece = new Typeappartement_piece();
                        $newtypeappartement_typepiece->typepiece_id = intval($type["typepiece_id"]);
                        $newtypeappartement_typepiece->typeappartement_id = $item->id;
                        $newtypeappartement_typepiece->designation = $type["typepiece_text"];
                        $newtypeappartement_typepiece->save();
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

    public function export()
    {
        return Excel::download(new TypeappartementsExport, "typeappartement.xlsx");
    }


    public function updateAppartementTRidwan()
    {
        $entite = Entite::where("code", "RID")->first();
        $villas = Appartement::where("entite_id", $entite->id)->get();
        foreach ($villas as $value) {
            # code...
            $value->etatlieu = "0";
            $value->save();
        }
    }
}
