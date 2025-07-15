<?php

namespace App\Http\Controllers;

use App\Devi;
use App\Outil;
use Exception;
use App\Detaildevi;
use App\Detaildevisdetail;
use App\Demandeintervention;
use Illuminate\Http\Request;
use App\Soustypeintervention;
use App\Jobs\ImportUserFileJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DeviController extends SaveModelController
{
    //
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "devis";
    protected $model = Devi::class;
    protected $job = ImportUserFileJob::class;

    public function save(Request $request)
    {

        try {
            return DB::transaction(
                function () use ($request) {
                    $errors = null;
                    $item = new Devi();


                    if (isset($request->id)) {
                        if (is_numeric($request->id) && $request->id > 0) {
                            $item = Devi::find($request->id);
                            if (!$item) {

                                $errors = "Le devi que vous tentez de modifier n'existe pas";
                            }
                        } else {
                            return array(
                                "data" => null,
                                "error" => "L'id doit être un nombre entier",
                            );
                        }
                    }


                    if (isset($request->demandeintervention_id)) {
                        if (is_numeric($request->demandeintervention_id) && $request->demandeintervention_id > 0) {
                            $oldevis = Devi::where('demandeintervention_id', $request->demandeintervention_id)->first();
                            if (isset($oldevis)) {
                                $item = $oldevis;
                            }
                        } else {
                            return array(
                                "data" => null,
                                "error" => "L'id doit être un nombre entier",
                            );
                        }
                    }

                    
                    if (isset($request->etatlieu_id)) {
                        if (is_numeric($request->etatlieu_id) && $request->etatlieu_id > 0) {
                            $oldevis = Devi::where('etatlieu_id', $request->etatlieu_id)->first();
                            if (isset($oldevis)) {
                                $item = $oldevis;
                            }
                        } else {
                            return array(
                                "data" => null,
                                "error" => "L'id doit être un nombre entier",
                            );
                        }
                    }

                    // dd($request);

                    if (empty($request->etatlieu_id) && empty($request->demandeintervention_id)) {
                        $errors = " id  du type de  devis introuvable";
                    }


                    if (empty($request->objet)) {
                        $errors = " l'objet de la devis est obligatoire";
                    }

                    if (empty($request->date)) {
                        $errors = "La date d'enregistrement est obligatoire";
                    }

                    if (empty($request->detaildevis_)) {
                        $errors = "aucune categorie n'est selectionné";
                    }

                    if (!isset($errors)) {

                        $item->date = $request->date;
                        $item->object = $request->objet;
                        $item->demandeintervention_id = ($request->demandeintervention_id) ? $request->demandeintervention_id : null;
                        $item->etatlieu_id = ($request->etatlieu_id) ? $request->etatlieu_id : null;
                        $item->save();

                        //verifier si devis est
                        if (!isset($request->id)) {
                            $item->code = $this->generateCodeDevis($item->id);
                            $item->save();
                        }

                        $devis = Devi::find($item->id);
                        if ($devis) {

                            $olddetail_devis = Detaildevi::where("devi_id", $item->id);

                            if (isset($olddetail_devis) && !empty($olddetail_devis)) {
                                $olddetail_devis->delete();
                                $olddetail_devis->forceDelete();
                            }

                            foreach ($this->getCategoriesFromData($request->input('detaildevis_')) as $category) {

                                $detaildevi = new Detaildevi();
                                $detaildevi->devi_id = $item->id;
                                $detaildevi->categorieintervention_id = ($category['categorieintervention_id']) ? $category['categorieintervention_id'] : null;
                                $detaildevi->save();
                                $detaildeviselect = Detaildevi::find($detaildevi->id);
                                if ($detaildeviselect) {
                                    foreach ($this->groupSubcategoriesData($category['subcategories']) as $soustypeintervention) {
                                        $detaildevisdetail = new Detaildevisdetail();
                                        $detaildevisdetail->detaildevi_id = $detaildeviselect->id;
                                        $detaildevisdetail->quantite =  ($soustypeintervention[0]['quantite']) ? $soustypeintervention[0]['quantite'] : 0;
                                        $detaildevisdetail->prixunitaire = ($soustypeintervention[0]['prix']) ? $soustypeintervention[0]['prix'] : 0;
                                        $detaildevisdetail->unite_id = ($soustypeintervention[0]['unite_id']) ? $soustypeintervention[0]['unite_id'] : null;
                                        $detaildevisdetail->soustypeintervention_id = ($soustypeintervention['id']) ? $soustypeintervention['id'] : null;
                                        $detaildevisdetail->save();
                                    }
                                }
                            }
                        }
                        if (!$errors) {
                            return Outil::redirectgraphql($this->queryName, "id:{$item->id}", Outil::$queries[$this->queryName]);
                        }
                    }

                    throw new Exception($errors);
                }
            );
        } catch (\Throwable $th) {
            return Outil::getResponseError($th);
        }
    }

    // fonction qui change l'etat du devi 






    // get data from request

    private  function getCategoriesFromData($categorieData)
    {
        $detaildevis = json_decode($categorieData, true);

        if (is_array($detaildevis)) {
            return $detaildevis;
        }
    }


    private function findOrCreateDevi($request)
    {

        if ($request->filled('id')) {

            $existingDevi = Devi::find($request->input('id'));

            if ($existingDevi) {

                if ($request->filled('demandeintervention_id')) {
                    $existingDevi = Devi::where(
                        'demandeintervention_id',
                        $request->input('demandeintervention_id')
                    )->first();

                    if ($existingDevi) {
                        return $existingDevi;
                    }
                }

                if ($request->filled('etatlieu_id')) {
                    $existingDevi = Devi::where('etatlieu_id', $request->input('etatlieu_id'))->first();
                    if ($existingDevi) {
                        return $existingDevi;
                    }
                }
            }
        }





        return new Devi();
    }



    // group data et dataElement
    private  function groupSubcategoriesData($subcategories)
    {
        $groupedData = [];
        $grouped = [];
        foreach ($subcategories['data'] as $item) {
            $subcategorie_id = $item['id'];
            if (!isset($grouped[$subcategorie_id])) {
                $grouped[$subcategorie_id] = [
                    'data' => $item,
                    'dataElement' => [],
                ];
            } else {
                $grouped[$subcategorie_id]['data'] = $item;
            }
        }
        foreach ($subcategories['dataElement'] as $item) {
            $subcategorie_id = $item['subcategorie_id'];
            if (isset($grouped[$subcategorie_id])) {
                $grouped[$subcategorie_id]['dataElement'][] = $item;
            }
        }
        $groupedData = array_values($grouped);
        foreach ($groupedData as &$group) {
            $mixedData = array_merge($group['data'], $group['dataElement']);
            $group = $mixedData;
        }

        return $groupedData;
    }


    /// genre un nbr  aleatoire

    private function generateCodeDevis($iddevi)
    {
        $code = "";
        if (isset($iddevi)) {
            $devis = Devi::find($iddevi);
            $code = "LN/IBD/" . $devis->id . '/' . Outil::resolveAllDateCompletFRSlash($devis->date, false);
        }

        // $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // // Génération de 2 lettres aléatoires   
        // for ($i = 0; $i < 2; $i++) {
        //     $code .= $letters[rand(0, strlen($letters) - 1)];
        // }
        // $code .= '/';
        // // Génération de 3 lettres aléatoires
        // for ($i = 0; $i < 3; $i++) {
        //     $code .= $letters[rand(0, strlen($letters) - 1)];
        // }
        // $code .= '/';
        // $code .= date('ymd');
        return $code;
    }
}
